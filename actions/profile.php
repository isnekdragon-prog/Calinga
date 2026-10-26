<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth_helpers.php';
require_once __DIR__ . '/upload_helpers.php';

$user = requireLogin($conn);
$profileToken = getCsrfToken('profile_token');

$profileUserStmt = $conn->prepare("SELECT id, fullname, email, password, profile_image, COALESCE(is_admin,0) AS is_admin, is_verified FROM users WHERE id = ?");
$profileUserStmt->bind_param("i", $user['id']);
$profileUserStmt->execute();
$profileUser = $profileUserStmt->get_result()->fetch_assoc();
$profileUserStmt->close();

$message = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '', 'profile_token')) {
        $error = 'Security token mismatch. Please refresh and try again.';
    } else {
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $profileImage = $profileUser['profile_image'] ?? null;

        if ($fullname === '' || $email === '') {
            $error = 'Full name and email are required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            $emailCheckStmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id <> ?");
            $emailCheckStmt->bind_param("si", $email, $user['id']);
            $emailCheckStmt->execute();
            $emailExists = $emailCheckStmt->get_result()->fetch_assoc();
            $emailCheckStmt->close();

            if ($emailExists) {
                $error = 'That email is already being used by another account.';
            } else {
                $changingPassword = ($currentPassword !== '' || $newPassword !== '' || $confirmPassword !== '');

                if ($changingPassword) {
                    if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
                        $error = 'To change your password, fill in current password, new password, and confirm password.';
                    } elseif (!password_verify($currentPassword, $profileUser['password'])) {
                        $error = 'Current password is incorrect.';
                    } elseif (strlen($newPassword) < 8) {
                        $error = 'New password must be at least 8 characters.';
                    } elseif ($newPassword !== $confirmPassword) {
                        $error = 'New password and confirm password do not match.';
                    }
                }

                if ($error === '') {
                    // SECURE FILE UPLOAD - Using existing storeUploadedImage function
                    $uploadError = null;
                    $uploadedImage = storeUploadedImage(
                        $_FILES['profile_image'] ?? [],
                        realpath(__DIR__ . '/../uploads'),
                        $uploadError,
                        'profile'
                    );

                    if ($uploadError !== null) {
                        $error = $uploadError;
                    } elseif ($uploadedImage !== null) {
                        $oldImage = $profileUser['profile_image'] ?? '';
                        if ($oldImage !== '') {
                            $oldImagePath = realpath(__DIR__ . '/../uploads') . DIRECTORY_SEPARATOR . $oldImage;
                            if (is_file($oldImagePath)) {
                                @unlink($oldImagePath);
                            }
                        }
                        $profileImage = $uploadedImage;
                    }
                }

                if ($error === '') {
                    if ($changingPassword) {
                        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                        $updateStmt = $conn->prepare("UPDATE users SET fullname = ?, email = ?, password = ?, profile_image = ? WHERE id = ?");
                        $updateStmt->bind_param("ssssi", $fullname, $email, $passwordHash, $profileImage, $user['id']);
                    } else {
                        $updateStmt = $conn->prepare("UPDATE users SET fullname = ?, email = ?, profile_image = ? WHERE id = ?");
                        $updateStmt->bind_param("sssi", $fullname, $email, $profileImage, $user['id']);
                    }

                    if ($updateStmt->execute()) {
                        $updateStmt->close();
                        header('Location: profile.php?success=' . urlencode('Profile updated successfully.'));
                        exit;
                    }

                    $error = 'Unable to update your profile right now.';
                    $updateStmt->close();
                }
            }
        }

        $profileUser['fullname'] = $fullname;
        $profileUser['email'] = $email;
        $profileUser['profile_image'] = $profileImage;
    }
}

$summaryStmt = $conn->prepare("
    SELECT
        (SELECT COUNT(*) FROM aics_applications WHERE user_id = ?) AS aics_total,
        (SELECT COUNT(*) FROM solo_parent_applications WHERE user_id = ?) AS solo_total,
        (SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0) AS unread_total
");
$summaryStmt->bind_param("iii", $user['id'], $user['id'], $user['id']);
$summaryStmt->execute();
$summary = $summaryStmt->get_result()->fetch_assoc() ?: ['aics_total' => 0, 'solo_total' => 0, 'unread_total' => 0];
$summaryStmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - CALINGA</title>
    <link rel="stylesheet" href="../style1.css">
    <style>
        body {
            min-height: 100vh;
        }

        .profile-page {
            width: min(calc(100% - 32px), 1080px);
            margin: 0 auto;
            padding: 8rem 0 4rem;
        }

        .profile-shell {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 1.5rem;
            align-items: start;
        }

        .profile-sidebar,
        .profile-main {
            background: rgba(255, 253, 248, 0.96);
            border: 1px solid rgba(36, 51, 61, 0.08);
            border-radius: 24px;
            box-shadow: 0 18px 40px rgba(17, 42, 54, 0.08);
        }

        .profile-sidebar {
            padding: 1.5rem;
            position: sticky;
            top: 110px;
        }

        .profile-back {
            display: inline-flex;
            margin-bottom: 1rem;
            color: #0f6c7b;
            text-decoration: none;
            font-weight: 700;
        }

        .profile-sidebar h1 {
            margin: 0;
            font-size: 2rem;
            line-height: 1.05;
            color: #20313d;
        }

        .profile-avatar {
            width: 108px;
            height: 108px;
            border-radius: 50%;
            overflow: hidden;
            display: grid;
            place-items: center;
            margin-bottom: 1rem;
            border: 4px solid rgba(15, 108, 123, 0.12);
            background: linear-gradient(135deg, #dff3f6, #f1ece2);
            color: #0f6c7b;
            font-size: 2rem;
            font-weight: 800;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-subtitle {
            margin-top: 0.55rem;
            color: #66757f;
        }

        .profile-badge {
            display: inline-flex;
            align-items: center;
            margin-top: 1rem;
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: #dff3f6;
            color: #0f6c7b;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .profile-stats {
            display: grid;
            gap: 0.85rem;
            margin-top: 1.5rem;
        }

        .profile-stat {
            padding: 1rem;
            border-radius: 18px;
            background: linear-gradient(180deg, #fff, #f7f2e8);
            border: 1px solid rgba(36, 51, 61, 0.06);
        }

        .profile-stat strong {
            display: block;
            font-size: 1.7rem;
            color: #20313d;
            line-height: 1;
        }

        .profile-stat span {
            display: block;
            margin-top: 0.35rem;
            color: #6d7c85;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .profile-links {
            display: grid;
            gap: 0.75rem;
            margin-top: 1.4rem;
        }

        .profile-links a {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 0.85rem 1rem;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 700;
            background: #f3f6f7;
            color: #20313d;
            border: 1px solid rgba(36, 51, 61, 0.08);
        }

        .profile-main {
            overflow: hidden;
        }

        .profile-main-header {
            padding: 1.35rem 1.5rem;
            border-bottom: 1px solid #edf1f4;
            background: linear-gradient(180deg, #ffffff, #f9fbfc);
        }

        .profile-main-header h2 {
            margin: 0;
            color: #20313d;
        }

        .profile-main-header p {
            margin-top: 0.3rem;
            color: #74858f;
        }

        .profile-form {
            padding: 1.5rem;
        }

        .profile-alert {
            margin-bottom: 1.2rem;
            padding: 0.95rem 1rem;
            border-radius: 14px;
            font-weight: 600;
        }

        .profile-alert.success {
            background: #e8f7ed;
            color: #23633e;
        }

        .profile-alert.error {
            background: #fdeeee;
            color: #9d3434;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .profile-field {
            display: grid;
            gap: 0.45rem;
        }

        .profile-field--full {
            grid-column: 1 / -1;
        }

        .profile-field label {
            color: #30424d;
            font-weight: 700;
            font-size: 0.92rem;
        }

        .profile-field input {
            width: 100%;
            padding: 0.9rem 1rem;
            border-radius: 14px;
            border: 1px solid rgba(36, 51, 61, 0.14);
            background: #fff;
            font-size: 0.98rem;
        }

        .profile-field input:focus {
            outline: none;
            border-color: #0f6c7b;
            box-shadow: 0 0 0 4px rgba(15, 108, 123, 0.12);
        }

        .profile-section-title {
            margin: 1.6rem 0 1rem;
            color: #20313d;
            font-size: 1.05rem;
        }

        .profile-note {
            margin-top: 0.8rem;
            color: #70808a;
            font-size: 0.86rem;
        }

        .profile-upload-note {
            margin-top: 0.35rem;
            color: #70808a;
            font-size: 0.82rem;
        }

        .profile-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 1.5rem;
        }

        .profile-save {
            border: none;
            border-radius: 999px;
            padding: 0.9rem 1.5rem;
            background: linear-gradient(135deg, #0f6c7b, #183243);
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        @media (max-width: 900px) {
            .profile-shell {
                grid-template-columns: 1fr;
            }

            .profile-sidebar {
                position: static;
            }
        }

        @media (max-width: 640px) {
            .profile-page {
                padding-top: 7rem;
            }

            .profile-grid {
                grid-template-columns: 1fr;
            }

            .profile-form,
            .profile-main-header,
            .profile-sidebar {
                padding: 1.1rem;
            }

            .profile-actions {
                justify-content: stretch;
            }

            .profile-save {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <nav class="menu scrolled">
        <div class="menu__logo">
            <img src="../imgs/c4.png" alt="Logo">
            <span>CALINGA</span>
        </div>

        <button class="menu__toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <ul class="menu__links">
            <li><a href="../index.php">Home</a></li>
            <li><a href="forms.php">My Forms</a></li>
            <li><a href="notifications.php">Notifications</a></li>
        </ul>

        <div class="menu__auth">
            <span class="btn btn-outline nav-user-chip" style="cursor: default;">
                <span class="nav-avatar">
                    <?php if (!empty($profileUser['profile_image'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($profileUser['profile_image']) ?>" alt="Profile picture">
                    <?php else: ?>
                        <?= htmlspecialchars(function_exists('mb_substr') ? mb_substr($profileUser['fullname'] ?? 'U', 0, 1) : substr($profileUser['fullname'] ?? 'U', 0, 1)) ?>
                    <?php endif; ?>
                </span>
                <span>Hi, <?= htmlspecialchars($profileUser['fullname'] ?? '') ?></span>
            </span>
            <?php if (!empty($profileUser['is_admin'])): ?>
                <a href="admin_panel.php" class="btn btn-outline">Admin</a>
            <?php endif; ?>
            <a href="logout.php" class="btn btn-outline">Logout</a>
        </div>
    </nav>

    <main class="profile-page">
        <div class="profile-shell">
            <aside class="profile-sidebar">
                <a href="../index.php" class="profile-back">&larr; Back to homepage</a>
                <div class="profile-avatar">
                    <?php if (!empty($profileUser['profile_image'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($profileUser['profile_image']) ?>" alt="Profile picture">
                    <?php else: ?>
                        <?= htmlspecialchars(function_exists('mb_substr') ? mb_substr($profileUser['fullname'] ?? 'U', 0, 1) : substr($profileUser['fullname'] ?? 'U', 0, 1)) ?>
                    <?php endif; ?>
                </div>
                <h1>My Profile</h1>
                <p class="profile-subtitle">Update your account details, keep your email current, and change your password when needed.</p>
                <span class="profile-badge"><?= !empty($profileUser['is_admin']) ? 'Administrator Account' : 'Verified User Account' ?></span>

                <div class="profile-stats">
                    <div class="profile-stat">
                        <strong><?= (int)($summary['aics_total'] ?? 0) ?></strong>
                        <span>AICS Applications</span>
                    </div>
                    <div class="profile-stat">
                        <strong><?= (int)($summary['solo_total'] ?? 0) ?></strong>
                        <span>Solo Parent Forms</span>
                    </div>
                    <div class="profile-stat">
                        <strong><?= (int)($summary['unread_total'] ?? 0) ?></strong>
                        <span>Unread Notifications</span>
                    </div>
                </div>

                <div class="profile-links">
                    <a href="forms.php">Open My Forms</a>
                    <a href="notifications.php">Open Notifications</a>
                </div>
            </aside>

            <section class="profile-main">
                <div class="profile-main-header">
                    <h2>Account Settings</h2>
                    <p>Changes here apply to your login account. Leave password fields blank if you only want to update your name or email.</p>
                </div>

                <form method="POST" enctype="multipart/form-data" class="profile-form">
                    <?php if ($message): ?>
                        <div class="profile-alert success"><?= htmlspecialchars($message) ?></div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="profile-alert error"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($profileToken) ?>">

                    <div class="profile-grid">
                        <div class="profile-field">
                            <label for="fullname">Full Name</label>
                            <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($profileUser['fullname'] ?? '') ?>" required>
                        </div>

                        <div class="profile-field">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($profileUser['email'] ?? '') ?>" required>
                        </div>

                        <div class="profile-field profile-field--full">
                            <label for="profile_image">Profile Picture</label>
                            <input type="file" id="profile_image" name="profile_image" accept=".jpg,.jpeg,.png,.webp,.avif,image/jpeg,image/png,image/webp,image/avif">
                            <div class="profile-upload-note">Upload a JPG, PNG, WEBP, or AVIF image up to 5MB.</div>
                        </div>

                        <div class="profile-field">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" placeholder="Required only if changing password">
                        </div>

                        <div class="profile-field">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" placeholder="At least 8 characters">
                        </div>

                        <div class="profile-field profile-field--full">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Retype your new password">
                        </div>
                    </div>

                    <h3 class="profile-section-title">Account Info</h3>
                    <div class="profile-grid">
                        <div class="profile-field">
                            <label>Verification Status</label>
                            <input type="text" value="<?= !empty($profileUser['is_verified']) ? 'Verified' : 'Not Verified' ?>" readonly>
                        </div>

                        <div class="profile-field">
                            <label>Account Type</label>
                            <input type="text" value="<?= !empty($profileUser['is_admin']) ? 'Administrator' : 'Beneficiary User' ?>" readonly>
                        </div>
                    </div>

                    <p class="profile-note">For security, the system asks for your current password only when you want to set a new one.</p>

                    <div class="profile-actions">
                        <button type="submit" class="profile-save">Save Profile Changes</button>
                    </div>
                </form>
            </section>
        </div>
    </main>

    <script>
        const menu = document.querySelector('.menu');
        const menuToggle = document.querySelector('.menu__toggle');

        if (menuToggle) {
            menuToggle.addEventListener('click', () => {
                const isOpen = menu.classList.toggle('menu--open');
                menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        }

        document.querySelectorAll('.menu__links a, .menu__auth a').forEach((link) => {
            link.addEventListener('click', () => {
                menu.classList.remove('menu--open');
                if (menuToggle) {
                    menuToggle.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
</body>
</html>
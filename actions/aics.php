<?php
session_start();
require_once __DIR__ . '/db.php';

$loggedIn = false;
$userName = "";
$myApplications = [];
$aicsCooldownUntil = null;
$isAicsReadyToRequest = true;

if (isset($_SESSION['user_id'])) {
    $loggedIn = true;
    $stmt = $conn->prepare("SELECT fullname, email, profile_image, COALESCE(is_admin,0) AS is_admin, aics_cooldown_until FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $userName = $user['fullname'];
        $userEmail = $user['email'];
        $userProfileImage = $user['profile_image'] ?? '';
        $isAdmin = (bool)$user['is_admin'];
        $aicsCooldownUntil = $user['aics_cooldown_until'] ?? null;
        $isAicsReadyToRequest = empty($aicsCooldownUntil) || strtotime($aicsCooldownUntil) <= time();
        
        // Get user's applications
        $appStmt = $conn->prepare("SELECT * FROM aics_applications WHERE user_id = ? ORDER BY created_at DESC");
        $appStmt->bind_param("i", $_SESSION['user_id']);
        $appStmt->execute();
        $appResult = $appStmt->get_result();
        while ($appRow = $appResult->fetch_assoc()) {
            $myApplications[] = $appRow;
        }
        $appStmt->close();
    }
}

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AICS - CALINGA MSWD</title>
    <link rel="stylesheet" href="../style1.css">
    <style>
        .aics-page {
            max-width: 1000px;
            margin: 0 auto;
            padding: 8rem 2rem 4rem;
        }
        
        .aics-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .aics-header h1 {
            color: #2c3e50;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .aics-content {
            background: #fff;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }
        
        .aics-content h2 {
            color: #2c3e50;
            margin-bottom: 1rem;
            margin-top: 1.5rem;
        }
        
        .aics-content h2:first-child {
            margin-top: 0;
        }
        
        .aics-content p {
            color: #555;
            line-height: 1.8;
            margin-bottom: 1rem;
        }
        
        .aics-content ul {
            margin-left: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .aics-content li {
            color: #555;
            margin-bottom: 0.5rem;
        }
        
        .requirements-box {
            background: #f8f9fa;
            border-left: 4px solid #3498db;
            padding: 1.5rem;
            border-radius: 0 8px 8px 0;
            margin: 1.5rem 0;
        }
        
        .requirements-box h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        /* Login Warning Box */
        .login-warning {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .login-warning h3 {
            color: #856404;
            margin-bottom: 1rem;
        }
        
        .login-warning p {
            color: #856404;
            margin-bottom: 1.5rem;
        }
        
        .login-warning .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        /* Apply Button */
        .apply-section {
            text-align: center;
            padding: 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            color: #fff;
        }
        
        .apply-section h3 {
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        
        .apply-section p {
            color: rgba(255,255,255,0.9);
            margin-bottom: 1.5rem;
        }
        
        .btn-apply {
            display: inline-block;
            background: #fff;
            color: #667eea;
            padding: 1rem 2.5rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
        }
        
        .btn-apply:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .btn-apply.disabled {
            background: #dfe6e9;
            color: #636e72;
            pointer-events: none;
            box-shadow: none;
        }
        .cooldown-note {
            margin-top: 1rem;
            font-size: 0.95rem;
            color: #fff4d6;
        }

        .my-applications {
            background: #fffdf8;
            border-radius: 18px;
            padding: 1rem 1.25rem 0.25rem;
            box-shadow: 0 18px 40px rgba(17, 42, 54, 0.12);
            margin-bottom: 2rem;
            border: 1px solid rgba(36, 51, 61, 0.12);
        }

        .my-applications h3 {
            color: #24333d;
            margin: 0 0 1rem;
            font-size: 1.6rem;
            letter-spacing: 0.02em;
        }

        .aics-table-wrap {
            overflow: hidden;
            border-radius: 14px;
            background: #fff;
            border: 1px solid rgba(36, 51, 61, 0.12);
        }

        .aics-table {
            width: 100%;
            border-collapse: collapse;
            color: #24333d;
        }

        .aics-table thead th {
            background: #e8eef1;
            color: #334155;
            font-size: 0.82rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.9rem 1rem;
            text-align: left;
            font-weight: 700;
        }

        .aics-table tbody tr {
            border-bottom: 1px solid rgba(36, 51, 61, 0.1);
            transition: background-color 0.2s ease;
        }

        .aics-table tbody tr:hover {
            background: #f5f8fa;
        }

        .aics-table tbody td {
            padding: 1rem;
            font-size: 1rem;
            color: #24333d;
        }

        .aics-table tbody tr:last-child {
            border-bottom: none;
        }

        .aics-app-id {
            font-weight: 700;
            color: #1f2937;
            letter-spacing: 0.02em;
        }

        .aics-app-type {
            color: #334155;
        }

        .aics-date {
            color: #334155;
        }

        .aics-status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.35rem 0.65rem;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: capitalize;
            line-height: 1.2;
            border: 1px solid transparent;
        }

        .aics-status-pending {
            background: #fff0c2;
            color: #946200;
            border-color: transparent;
        }

        .aics-status-approved {
            background: #d4edda;
            color: #155724;
            border-color: transparent;
        }

        .aics-status-declined {
            background: #f8d7da;
            color: #721c24;
            border-color: transparent;
        }

        .aics-view-btn {
            display: inline-block;
            padding: 0.7rem 1.1rem;
            background: linear-gradient(180deg, #5aa6d8, #3a8cc7);
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            box-shadow: 0 8px 18px rgba(53, 126, 189, 0.28);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .aics-view-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 22px rgba(53, 126, 189, 0.34);
        }

        [data-theme="dark"] .my-applications {
            background: #101820;
            border-color: rgba(148, 163, 184, 0.2);
        }

        [data-theme="dark"] .my-applications h3,
        [data-theme="dark"] .aics-table thead th,
        [data-theme="dark"] .aics-table tbody td,
        [data-theme="dark"] .aics-app-id,
        [data-theme="dark"] .aics-app-type,
        [data-theme="dark"] .aics-date {
            color: #eaf2ff;
        }

        [data-theme="dark"] .aics-table-wrap {
            background: rgba(19, 33, 48, 0.86);
            border-color: rgba(148, 163, 184, 0.2);
        }

        [data-theme="dark"] .aics-table thead th {
            background: rgba(30, 47, 64, 0.92);
        }

        [data-theme="dark"] .aics-table tbody tr {
            border-color: rgba(148, 163, 184, 0.14);
        }

        [data-theme="dark"] .aics-table tbody tr:hover {
            background: rgba(59, 99, 131, 0.18);
        }

        [data-theme="dark"] .aics-status-pending {
            background: rgba(250, 204, 21, 0.18);
            color: #f6d995;
            border-color: rgba(250, 204, 21, 0.35);
        }

        [data-theme="dark"] .aics-status-approved {
            background: rgba(52, 211, 153, 0.18);
            color: #a7f3d0;
            border-color: rgba(52, 211, 153, 0.35);
        }

        [data-theme="dark"] .aics-status-declined {
            background: rgba(244, 114, 182, 0.18);
            color: #f9c7dc;
            border-color: rgba(244, 114, 182, 0.35);
        }

        @media (max-width: 768px) {
            .aics-table-wrap {
                overflow-x: auto;
            }

            .aics-table {
                min-width: 620px;
            }
        }
        
        /* Success/Error Messages */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }
        
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .alert-error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 1rem;
            color: #3498db;
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="menu scrolled">
    <div class="menu__logo">
        <img src="../imgs/c4.png" alt="Logo">
        <span>CALINGA</span>
    </div>

    <ul class="menu__links">
        <li><a href="../index.php">Home</a></li>

        <li class="dropdown">
            <a href="#">About</a>
            <div class="dropdown__menu">
                <a href="municipal_history.php">Municipal History</a>
                <a href="municipal_officials.php">Municipal Officials</a>
                <a href="#">Social Workers</a>
            </div>
        </li>

        <li class="dropdown">
            <a href="#">Services</a>
            <div class="dropdown__menu">
                <a href="solo_parent.php">Solo Parent</a>
                <a href="aics.php">AICS</a>
            </div>
        </li>

        <li><a href="#">Contact</a></li>
    </ul>

    <div class="menu__auth">
        <?php if ($loggedIn): ?>
            <!-- Hi User Dropdown -->
            <div class="auth-dropdown">
                <button class="dropdown-toggle">
                    <span class="nav-avatar">
                        <?php if (!empty($userProfileImage)): ?>
                            <img src="../uploads/<?= htmlspecialchars($userProfileImage) ?>" alt="Profile picture">
                        <?php else: ?>
                            <?= htmlspecialchars(function_exists('mb_substr') ? mb_substr($userName, 0, 1) : substr($userName, 0, 1)) ?>
                        <?php endif; ?>
                    </span>
                    <span>Hi, <?= htmlspecialchars($userName) ?> &#9662;</span>
                </button>

                <div class="dropdown-menu">
                    <?php if ($isAdmin): ?>
                        <a href="admin_panel.php" class="dropdown-item">Admin Panel</a>
                    <?php endif; ?>
                    <a href="profile.php" class="dropdown-item">My Profile</a>
                    <a href="forms.php" class="dropdown-item">My Forms</a> <!-- Added My Forms -->
                    <a href="logout.php" class="dropdown-item">Logout</a>
                </div>
            </div>

            <!-- Notifications -->
            <div class="nav-notifications">
                <?php include "notification_dropdown.php"; ?>
            </div>
        <?php else: ?>
            <a href="login.php" class="btn btn-outline">Login</a>
            <a href="signup.php" class="btn btn-primary">Sign Up</a>
        <?php endif; ?>
    </div>
</nav>

<div class="aics-page">
    <a href="../index.php" class="back-link">← Back to Home</a>
    
    <div class="aics-header">
        <h1>AICS (Assistance to Individuals in Crisis Situation)</h1>
        <p>Municipal Social Welfare and Development Office - Cainta, Rizal</p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="aics-content">
        <h2>What is AICS?</h2>
        <p>
            The Assistance to Individuals in Crisis Situation (AICS) is a program by the Department of Social Welfare and Development (DSWD) that provides immediate assistance to individuals and families who are in crisis or have experienced calamities, disasters, or other emergency situations.
        </p>
        
        <h2>Purpose</h2>
        <p>
            This program aims to help individuals and families cope with crisis situations by providing financial, material, or psychosocial support to enable them to meet their basic needs and recover from the crisis.
        </p>
        
        <h2>Types of Assistance</h2>
        <ul>
            <li><strong>Medical Assistance</strong> - For hospitalization, medicines, and medical procedures</li>
            <li><strong>Educational Assistance</strong> - For school fees, books, and supplies</li>
            <li><strong>Emergency Shelter Assistance</strong> - For temporary shelter or home repair</li>
            <li><strong>Food Assistance</strong> - For immediate food needs</li>
            <li><strong>Transportation Assistance</strong> - For travel to medical facilities or for repatriation</li>
            <li><strong>Funeral Assistance</strong> - For burial expenses</li>
            <li><strong>Other Crisis Situations</strong> - As assessed by the social worker</li>
        </ul>
        
        <div class="requirements-box">
            <h3>General Requirements</h3>
            <ul>
                <li>Valid ID of the applicant</li>
                <li>Barangay Certificate or Indigency Certificate</li>
                <li>Medical certificate or supporting documents (for medical cases)</li>
                <li>Proof of income or sworn statement of income</li>
                <li>Letter of request or referral from the Barangay</li>
            </ul>
        </div>
        
        <h2>How to Apply</h2>
        <p>
            To apply for AICS assistance, you need to fill out an application form. The application will be assessed by our social workers to determine the appropriate type and amount of assistance.
        </p>
    </div>

    <?php if ($loggedIn): ?>
        <?php if (!empty($myApplications)): ?>
            <div class="my-applications">
                <h3>My Applications</h3>
                <div class="aics-table-wrap">
                    <table class="aics-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($myApplications as $app): ?>
                            <tr>
                                <td class="aics-app-id">#<?= $app['id'] ?></td>
                                <td class="aics-app-type"><?= htmlspecialchars($app['assistance_type']) ?></td>
                                <td class="aics-date"><?= date('M d, Y', strtotime($app['created_at'])) ?></td>
                                <td>
                                    <?php
                                        $statusClass = 'aics-status-pending';
                                        if ($app['status'] === 'approved') {
                                            $statusClass = 'aics-status-approved';
                                        } elseif ($app['status'] === 'declined') {
                                            $statusClass = 'aics-status-declined';
                                        }
                                    ?>
                                    <span class="aics-status-badge <?= $statusClass ?>"><?= ucfirst($app['status']) ?></span>
                                </td>
                                <td>
                                    <a href="aics_view.php?id=<?= $app['id'] ?>" class="aics-view-btn">View</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <div class="apply-section">
            <h3>Ready to Apply?</h3>
            <?php if ($isAicsReadyToRequest): ?>
                <p>If you need assistance, please fill out our application form. Our team will review your request and get back to you as soon as possible.</p>
                <a href="aics_apply.php" class="btn-apply">Apply Now</a>
            <?php else: ?>
                <p>Your last approved AICS request is still on cooldown.</p>
                <a href="#" class="btn-apply disabled">Apply Locked</a>
                <div class="cooldown-note">You may request again after <?= htmlspecialchars(date('F d, Y', strtotime($aicsCooldownUntil))) ?>.</div>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="login-warning">
            <h3>⚠️ Login Required</h3>
            <p>You need to be logged in to apply for AICS assistance. Please login or create an account to proceed with your application.</p>
            <div class="btn-group">
                <a href="login.php" class="btn btn-primary">Login</a>
                <a href="signup.php" class="btn btn-outline" style="border-color: #856404; color: #856404;">Sign Up</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
$assistantEndpoint = 'inquiry_assistant.php';
$assistantTitle = 'AICS Inquiry Assistant';
$assistantIntro = 'Ask about AICS requirements, eligibility, follow-up status, or application steps.';
include __DIR__ . '/inquiry_widget.php';
?>

<script>
// AUTH DROPDOWN TOGGLE
document.addEventListener('DOMContentLoaded', () => {
    const dropdown = document.querySelector('.auth-dropdown');
    if(dropdown){
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');

        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        });

        window.addEventListener('click', () => {
            menu.style.display = 'none';
        });
    }
});
</script>

<footer class="footer">
    <p>© 2026 CALINGA - MSWD | Cainta, Rizal, Philippines</p>
</footer>

</body>
</html>

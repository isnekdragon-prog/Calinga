<?php
session_start();
require_once __DIR__ . '/db.php';

$loggedIn = false;
$userName = "";
$isAdmin = false;
$myApplications = [];

if (isset($_SESSION['user_id'])) {

    $loggedIn = true;

    // Get user info
    $stmt = $conn->prepare(
        "SELECT fullname, email, profile_image, COALESCE(is_admin,0) AS is_admin FROM users WHERE id=?"
    );
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $userName = $user['fullname'];
        $userProfileImage = $user['profile_image'] ?? '';
        $isAdmin = (bool)$user['is_admin'];

        // Get solo parent applications
        $appStmt = $conn->prepare(
            "SELECT * FROM solo_parent_applications WHERE user_id=? ORDER BY created_at DESC"
        );
        $appStmt->bind_param("i", $_SESSION['user_id']);
        $appStmt->execute();
        $appResult = $appStmt->get_result();

        while ($row = $appResult->fetch_assoc()) {
            $myApplications[] = $row;
        }

        $appStmt->close();
    }
}

$error = $_GET['error'] ?? "";
$success = $_GET['success'] ?? "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Solo Parent Program - CALINGA MSWD</title>
<link rel="stylesheet" href="../style1.css">

<style>
body {
    font-family: Arial, sans-serif;
    background: #f4f6f9;
    margin: 0;
}

.solo-parent-page {
    max-width: 1000px;
    margin: auto;
    padding: 120px 20px 40px;
}

.solo-parent-header {
    text-align: center;
    margin-bottom: 30px;
}

.solo-parent-header h1 {
    font-size: 36px;
    color: #2c3e50;
}

.solo-parent-content {
    background: #fff;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
}

.solo-parent-content h2 {
    color: #2c3e50;
    margin-top: 20px;
}

.requirements-box {
    background: #f8f9fa;
    border-left: 4px solid #3c6ae7;
    padding: 20px;
    border-radius: 8px;
    margin-top: 20px;
}

.apply-section {
    text-align: center;
    padding: 30px;
    background: linear-gradient(135deg, #0D47A1, #1976D2); /* dark blue to blue */
    border-radius: 15px;
    color: #fff;
}

.btn-apply {
    display: inline-block;
    background: #fff;
    color: #0D47A1;
    padding: 12px 30px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: bold;
    margin-top: 10px;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th {
    background: #3c56e7;
    color: #fff;
    padding: 10px;
    text-align: left;
}

td {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.back-link {
    display: inline-block;
    margin-bottom: 20px;
    color: #3498db;
    text-decoration: none;
}
</style>
</head>

<body>

<!-- NAVIGATION -->
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

<!-- PAGE -->
<div class="solo-parent-page">

    <a href="../index.php" class="back-link">← Back to Home</a>

    <div class="solo-parent-header">
        <h1>Solo Parent Assistance Program</h1>
        <p>Municipal Social Welfare and Development Office</p>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="solo-parent-content">
        <h2>What is the Solo Parent Program?</h2>
        <p>
            The Solo Parent Program supports parents who are raising their children alone.
            This program provides financial assistance, livelihood opportunities, counseling,
            and other services to improve the quality of life of solo parents and their families.
        </p>

        <h2>Services Available</h2>
        <ul>
            <li><b>Financial Assistance</b> – Monthly aid for daily needs</li>
            <li><b>Educational Assistance</b> – Support for children's schooling</li>
            <li><b>Livelihood Programs</b> – Skills training and small business</li>
            <li><b>Counseling Services</b> – Psychosocial support</li>
            <li><b>Health Services</b> – Medical assistance</li>
        </ul>

        <div class="requirements-box">
            <h3>Eligibility Requirements</h3>
            <ul>
                <li>Valid Solo Parent ID or Certification</li>
                <li>Birth Certificate of dependent children</li>
                <li>Barangay Certificate of Residency</li>
                <li>Proof of income</li>
                <li>Valid Government ID</li>
            </ul>
        </div>
    </div>

    <?php if ($loggedIn): ?>
        <?php if (!empty($myApplications)): ?>
            <div class="solo-parent-content">
                <h3>My Solo Parent Applications</h3>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Application</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>

                    <?php foreach ($myApplications as $app): ?>
                        <tr>
                            <td>#<?= $app['id'] ?></td>
                            <td>Solo Parent Application</td>
                            <td><?= date("M d, Y", strtotime($app['created_at'])) ?></td>
                            <td><?= ucfirst($app['status']) ?></td>
                            <td><a href="solo_parent_view.php?id=<?= $app['id'] ?>" class="btn-apply">View</a></td>
                        </tr>
                    <?php endforeach; ?>

                </table>
            </div>
        <?php endif; ?>

        <div class="apply-section">
            <h3>Ready to Apply?</h3>
            <p>Fill out the Solo Parent application form.</p>
            <a href="solo_parent_apply.php" class="btn-apply">Apply Now</a>
        </div>

    <?php else: ?>
        <div class="apply-section">
            <h3>Login Required</h3>
            <p>You must login to apply for Solo Parent assistance.</p>
            <a href="login.php" class="btn-apply">Login</a>
        </div>
    <?php endif; ?>
</div>

<?php
$assistantEndpoint = 'inquiry_assistant.php';
$assistantTitle = 'Solo Parent Inquiry Assistant';
$assistantIntro = 'Ask about Solo Parent requirements, eligibility, follow-up status, or application steps.';
include __DIR__ . '/inquiry_widget.php';
?>

<footer class="footer">
    <p>© 2026 CALINGA MSWD | Cainta, Rizal, Philippines</p>
</footer>

<script>
// AUTH DROPDOWN TOGGLE
document.addEventListener('DOMContentLoaded', () => {
    const dropdown = document.querySelector('.auth-dropdown');
    if (dropdown) {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');

        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        });

        // Close dropdown if clicking outside
        window.addEventListener('click', () => {
            menu.style.display = 'none';
        });
    }
});
</script>

</body>
</html>

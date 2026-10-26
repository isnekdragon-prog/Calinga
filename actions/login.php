<?php
session_start();
require_once __DIR__ . '/db.php';

// if already logged in redirect to home
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// check admin
$adminMissing = false;
$res = $conn->query("SELECT id FROM users WHERE is_admin=1 LIMIT 1");
if ($res && $res->num_rows === 0) {
    $adminMissing = true;
}

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CALINGA MSWD</title>
    <link rel="stylesheet" href="../style1.css">

    <style>
        .error-popup {
            background: #ff4d4d;
            color: white;
            padding: 8px;
            border-radius: 6px;
            margin-top: 5px;
            display: none;
            font-size: 0.9rem;
            animation: fadeIn 0.3s ease;
        }

        .lock-popup {
            background: orange;
            color: black;
            padding: 8px;
            border-radius: 6px;
            margin-top: 5px;
            font-size: 0.9rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="auth-page">
    <div class="auth-container">
        <h2>Login</h2>

        <?php if ($success): ?>
            <div class="auth-success" style="color:green;margin-bottom:1rem;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form action="login_process.php" method="POST">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <input type="password" name="password" id="password" placeholder="Enter your password" required>

            <!-- ERROR POPUP -->
            <div id="errorPopup" class="error-popup">
                <?= htmlspecialchars($error) ?>
            </div>

            <button type="submit" class="btn-primary">Login</button>

            <p class="auth-text"><a href="forgot_password.php">Forgot Password?</a></p>

            <p class="auth-text">Don't have an account? <a href="signup.php">Sign Up</a></p>
        </form>

        <?php if ($adminMissing): ?>
            <p style="margin-top:1rem;">
                <a href="create_admin.php">Create administrator account</a>
            </p>
        <?php endif; ?>

        <a href="../index.php" class="btn btn-outline" style="margin-top: 1rem; display: inline-block;">
            Return to Homepage
        </a>
    </div>
</div>

<script>
const error = "<?= $error ?>";
const errorPopup = document.getElementById("errorPopup");

// Show error popup
if (error) {
    errorPopup.style.display = "block";
}
</script>

</body>
</html>

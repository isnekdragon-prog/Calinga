<?php
session_start();
require_once __DIR__ . '/db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
$email = $_GET['email'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - CALINGA MSWD</title>
    <link rel="stylesheet" href="../style1.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-container">
        <h2>Reset Password</h2>

        <?php if ($error): ?>
            <div class="auth-error" style="color:red;margin-bottom:1rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="auth-success" style="color:green;margin-bottom:1rem;">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <p style="margin-bottom:1rem;">Enter the 6-digit code sent to your email, then choose a new password.</p>

        <form action="reset_password_process.php" method="POST" id="reset-password-form">
            <label for="email">Email</label>
            <input
                type="email"
                name="email"
                id="email"
                placeholder="Enter your email"
                value="<?= htmlspecialchars($email) ?>"
                required
            >

            <label for="code">Reset Code</label>
            <input
                type="text"
                name="code"
                id="code"
                placeholder="Enter 6-digit code"
                inputmode="numeric"
                maxlength="6"
                required
            >

            <label for="password">New Password</label>
            <input
                type="password"
                name="password"
                id="password"
                placeholder="Enter new password"
                minlength="8"
                required
            >
            <div id="password-error" style="color:red; font-size:0.9rem; margin-top:0.3rem;"></div>

            <label for="confirm_password">Confirm New Password</label>
            <input
                type="password"
                name="confirm_password"
                id="confirm_password"
                placeholder="Confirm new password"
                minlength="8"
                required
            >

            <button type="submit" class="btn-primary">Change Password</button>

            <p class="auth-text"><a href="forgot_password.php">Send another code</a></p>
            <p class="auth-text"><a href="login.php">Back to Login</a></p>
        </form>
    </div>
</div>

<script>
const form = document.getElementById("reset-password-form");
const passwordInput = document.getElementById("password");
const passwordError = document.getElementById("password-error");

form.addEventListener("submit", function(e) {
    const password = passwordInput.value;
    const specialChar = /[!@#$%^&*(),.?":{}|<>]/;

    if (password.length < 8) {
        passwordError.textContent = "Password must be at least 8 characters.";
        e.preventDefault();
    } else if (!specialChar.test(password)) {
        passwordError.textContent = "Password must include at least one special character (!@#$%^&* etc.).";
        e.preventDefault();
    } else {
        passwordError.textContent = "";
    }
});
</script>

</body>
</html>

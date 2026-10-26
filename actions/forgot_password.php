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
    <title>Forgot Password - CALINGA MSWD</title>
    <link rel="stylesheet" href="../style1.css">
</head>
<body>

<div class="auth-page">
    <div class="auth-container">
        <h2>Forgot Password</h2>

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

        <p style="margin-bottom:1rem;">Enter your Gmail address and we will send a 6-digit reset code.</p>

        <form action="forgot_password_process.php" method="POST">
            <label for="email">Email</label>
            <input
                type="email"
                name="email"
                id="email"
                placeholder="Enter your Gmail address"
                value="<?= htmlspecialchars($email) ?>"
                required
            >

            <button type="submit" class="btn-primary">Send Reset Code</button>

            <p class="auth-text">Already remember it? <a href="login.php">Back to Login</a></p>
        </form>

        <a href="../index.php" class="btn btn-outline" style="margin-top: 1rem; display: inline-block;">
            Return to Homepage
        </a>
    </div>
</div>

</body>
</html>

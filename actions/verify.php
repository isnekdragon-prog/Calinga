<?php
session_start();
require_once __DIR__ . '/db.php';

$returnToHome = ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['auth_context'] ?? '') === 'home');

if (!isset($_SESSION['mfa_user_id'])) {
    header('Location: ' . ($returnToHome ? '../index.php?auth=login&error=' . urlencode('Please log in again to verify your account.') : 'login.php'));
    exit;
}

$userId = $_SESSION['mfa_user_id'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredOtp = trim($_POST['otp'] ?? '');

    if (!preg_match('/^\d{6}$/', $enteredOtp)) {
        $error = 'Please enter the 6-digit code from your email.';
    } else {
        $stmt = $conn->prepare("SELECT otp, otp_expiry FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();

        $expiryTimestamp = $user ? strtotime($user['otp_expiry'] ?? '') : false;

        if ($user && $user['otp'] !== null && hash_equals((string)$user['otp'], $enteredOtp) && $expiryTimestamp !== false && $expiryTimestamp >= time()) {

            $stmt = $conn->prepare("UPDATE users SET otp = NULL, otp_expiry = NULL WHERE id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();

            session_regenerate_id(true);
            $_SESSION['user_id'] = $userId;
            unset($_SESSION['mfa_user_id']);

            header('Location: ../index.php');
            exit;
        } else {
            $error = 'That code is invalid or has expired. Please try again.';
        }
    }

    if ($returnToHome) {
        header('Location: ../index.php?auth=verify&error=' . urlencode($error));
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Verify OTP</title>
<link rel="stylesheet" href="../style1.css">
</head>
<style>
body { font-family: Arial, sans-serif; }
</style>
<body>
<div class="auth-page">
    <div class="auth-container">
        <h2>Verify your sign-in</h2>
        <p>Enter the 6-digit code we emailed you. The code expires after 5 minutes.</p>

        <?php if ($error): ?>
            <p class="error" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <input type="hidden" name="auth_context" value="standalone">
            <label for="otp">Verification code</label>
            <input type="text" name="otp" id="otp" placeholder="000000" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" minlength="6" maxlength="6" required>
            <button type="submit">Verify</button>
        </form>
    </div>
</div>
</body>
</html>

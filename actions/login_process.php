<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$returnToHome = ($_POST['auth_context'] ?? '') === 'home';
$max_failed_attempts = 3;
$lock_duration_seconds = 1800;

function redirectToLogin(string $message, bool $returnToHome): void
{
    $page = $returnToHome ? '../index.php?auth=login&error=' : 'login.php?error=';
    header('Location: ' . $page . urlencode($message));
    exit;
}
if (!$email || !$password) {
    redirectToLogin('Please provide email and password.', $returnToHome);
}
// Get user (include admin flag)
$stmt = $conn->prepare("SELECT id, fullname, email, password, is_verified, account_status, COALESCE(is_admin,0) AS is_admin, COALESCE(failed_login_attempts, 0) AS failed_login_attempts, locked_until FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if ($user && !empty($user['locked_until'])) {
    $lockedUntil = strtotime($user['locked_until']);
    if ($lockedUntil !== false && $lockedUntil > time()) {
        $remainingSeconds = $lockedUntil - time();
        $remainingMinutes = (int) ceil($remainingSeconds / 60);
        $message = 'Your account is locked because of 3 failed login attempts. Try again in ' . $remainingMinutes . ' minute' . ($remainingMinutes === 1 ? '' : 's') . '.';
        redirectToLogin($message, $returnToHome);
    }

    if ($lockedUntil !== false && $lockedUntil <= time()) {
        $resetLockStmt = $conn->prepare("UPDATE users SET failed_login_attempts = 0, locked_until = NULL WHERE id = ?");
        $resetLockStmt->bind_param("i", $user['id']);
        $resetLockStmt->execute();
        $resetLockStmt->close();
        $user['failed_login_attempts'] = 0;
        $user['locked_until'] = null;
    }
}

// Check if user exists and password is correct
if ($user && password_verify($password, $user['password'])) {
    unset($_SESSION['user_id']);
    unset($_SESSION['mfa_user_id']);

    $resetAttemptsStmt = $conn->prepare("UPDATE users SET failed_login_attempts = 0, locked_until = NULL WHERE id = ?");
    $resetAttemptsStmt->bind_param("i", $user['id']);
    $resetAttemptsStmt->execute();
    $resetAttemptsStmt->close();

    // Check if verified
    if ($user['is_verified'] == 0) {
        redirectToLogin('Please verify your email before logging in.', $returnToHome);
    }

    // Check admin approval status
    if ($user['account_status'] === 'pending') {
        redirectToLogin('Your account is awaiting admin approval.', $returnToHome);
    }

    if ($user['account_status'] === 'rejected') {
        redirectToLogin('Your account application was not approved.', $returnToHome);
    }

    if ($user['is_admin']) {
        // admin logs in directly
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        header('Location: ../index.php');
        exit;
    }

    // Regular user: generate OTP
    $otp = rand(100000, 999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+5 minutes"));

    $stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE id = ?");
    $stmt->bind_param("ssi", $otp, $expiry, $user['id']);
    $stmt->execute();
    $stmt->close();

    $_SESSION['mfa_user_id'] = $user['id'];

    // Send OTP email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'edwardzabaldan4@gmail.com';
        $mail->Password   = 'afewakfjqmvurxzw'; // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('edwardzabaldan4@gmail.com', 'CALINGA MSWD');
        $mail->addAddress($user['email'], $user['fullname']);
        $mail->isHTML(true);
        $mail->Subject = 'Your Login OTP Code';
        $mail->Body    = "<h3>Hello {$user['fullname']},</h3><p>Your OTP code is:</p><h2>$otp</h2>";

        $mail->send();

        header('Location: ' . ($returnToHome ? '../index.php?auth=verify' : 'verify.php'));
        exit;

    } catch (Exception $e) {
        die("Mailer Error: " . $mail->ErrorInfo);
    }
}

if ($user) {
    $failedAttempts = (int) $user['failed_login_attempts'] + 1;

    if ($failedAttempts >= $max_failed_attempts) {
        $lockedUntil = date("Y-m-d H:i:s", time() + $lock_duration_seconds);
        $lockStmt = $conn->prepare("UPDATE users SET failed_login_attempts = 0, locked_until = ? WHERE id = ?");
        $lockStmt->bind_param("si", $lockedUntil, $user['id']);
        $lockStmt->execute();
        $lockStmt->close();

        redirectToLogin('Your account is locked because of 3 failed login attempts. Try again in 30 minutes.', $returnToHome);
    }

    $attemptStmt = $conn->prepare("UPDATE users SET failed_login_attempts = ? WHERE id = ?");
    $attemptStmt->bind_param("ii", $failedAttempts, $user['id']);
    $attemptStmt->execute();
    $attemptStmt->close();
}
// Invalid login
redirectToLogin('Invalid email or password.', $returnToHome);
?>
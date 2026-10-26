<?php
session_start();
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: forgot_password.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$code = trim($_POST['code'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm = trim($_POST['confirm_password'] ?? '');

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: reset_password.php?error=' . urlencode('Please enter a valid email address.') . '&email=' . urlencode($email));
    exit;
}

if (!preg_match('/^\d{6}$/', $code)) {
    header('Location: reset_password.php?error=' . urlencode('Please enter a valid 6-digit reset code.') . '&email=' . urlencode($email));
    exit;
}

if (strlen($password) < 8) {
    header('Location: reset_password.php?error=' . urlencode('Password must be at least 8 characters.') . '&email=' . urlencode($email));
    exit;
}

if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
    header('Location: reset_password.php?error=' . urlencode('Password must include at least one special character.') . '&email=' . urlencode($email));
    exit;
}

if ($password !== $confirm) {
    header('Location: reset_password.php?error=' . urlencode('Passwords do not match.') . '&email=' . urlencode($email));
    exit;
}

$stmt = $conn->prepare("SELECT id, reset_code, reset_code_expiry FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || empty($user['reset_code']) || empty($user['reset_code_expiry'])) {
    header('Location: forgot_password.php?error=' . urlencode('No active reset request was found for that email.') . '&email=' . urlencode($email));
    exit;
}

if (!hash_equals((string) $user['reset_code'], $code)) {
    header('Location: reset_password.php?error=' . urlencode('Invalid reset code.') . '&email=' . urlencode($email));
    exit;
}

if (strtotime((string) $user['reset_code_expiry']) < time()) {
    header('Location: forgot_password.php?error=' . urlencode('Your reset code has expired. Please request a new one.') . '&email=' . urlencode($email));
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("UPDATE users SET password = ?, reset_code = NULL, reset_code_expiry = NULL, otp = NULL, otp_expiry = NULL WHERE id = ?");
$stmt->bind_param("si", $hashedPassword, $user['id']);
$stmt->execute();
$stmt->close();

header('Location: login.php?success=' . urlencode('Password changed successfully. Please log in with your new password.'));
exit;
?>

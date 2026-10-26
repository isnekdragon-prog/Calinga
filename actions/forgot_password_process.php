<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: forgot_password.php');
    exit;
}

$email = trim($_POST['email'] ?? '');

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: forgot_password.php?error=' . urlencode('Please enter a valid email address.') . '&email=' . urlencode($email));
    exit;
}

$stmt = $conn->prepare("SELECT id, fullname, email FROM users WHERE email = ? LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    header('Location: forgot_password.php?success=' . urlencode('If the email exists, a reset code has been sent.'));
    exit;
}

$resetCode = (string) random_int(100000, 999999);
$expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

$stmt = $conn->prepare("UPDATE users SET reset_code = ?, reset_code_expiry = ? WHERE id = ?");
$stmt->bind_param("ssi", $resetCode, $expiry, $user['id']);
$stmt->execute();
$stmt->close();

try {
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'edwardzabaldan4@gmail.com';
    $mail->Password   = 'afewakfjqmvurxzw';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('edwardzabaldan4@gmail.com', 'CALINGA MSWD');
    $mail->addAddress($user['email'], $user['fullname']);
    $mail->isHTML(true);
    $mail->Subject = 'Your Password Reset Code';
    $mail->Body = "<h3>Hello {$user['fullname']},</h3>
        <p>You requested to reset your CALINGA MSWD password.</p>
        <p>Your password reset code is:</p>
        <h2>{$resetCode}</h2>
        <p>This code expires in 10 minutes.</p>
        <p>If you did not request this, you can ignore this email.</p>";

    $mail->send();
} catch (Exception $e) {
    header('Location: forgot_password.php?error=' . urlencode('Unable to send reset email right now. Please try again later.') . '&email=' . urlencode($email));
    exit;
}

header('Location: reset_password.php?email=' . urlencode($user['email']) . '&success=' . urlencode('A reset code has been sent to your email.'));
exit;
?>

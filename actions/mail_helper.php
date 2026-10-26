<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

function createMailer(): PHPMailer
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'edwardzabaldan4@gmail.com';
    $mail->Password = 'afewakfjqmvurxzw';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('edwardzabaldan4@gmail.com', 'CALINGA MSWD');
    $mail->isHTML(true);

    return $mail;
}

function sendAppMail(string $toEmail, string $toName, string $subject, string $htmlBody): void
{
    $mail = createMailer();
    $mail->addAddress($toEmail, $toName);
    $mail->Subject = $subject;
    $mail->Body = $htmlBody;
    $mail->send();
}

<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/mail_helper.php';

session_start();

$returnToHome = ($_POST['auth_context'] ?? '') === 'home'
    || ($_GET['source'] ?? '') === 'home';

function redirectToSignup(string $message = '', bool $success = false, bool $returnToHome = false): void
{
    $location = $returnToHome ? '../index.php' : 'signup.php';
    $parameters = [];

    if ($returnToHome) {
        $parameters['auth'] = 'signup';
    }

    if ($message !== '') {
        $parameters[$success ? 'notice' : 'error'] = $message;
    }
    if ($success) {
        $parameters['success'] = '1';
    }

    if ($parameters) {
        $location .= '?' . http_build_query($parameters);
    }

    header('Location: ' . $location);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$confirm = trim($_POST['confirm_password'] ?? '');

if (empty($fullname) || empty($email) || empty($password) || empty($confirm)) {
    redirectToSignup('All fields are required.', false, $returnToHome);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectToSignup('Invalid email format.', false, $returnToHome);
}

if ($password !== $confirm) {
    redirectToSignup('Passwords do not match.', false, $returnToHome);
}

if (strlen($password) < 8 || !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
    redirectToSignup('Password must be at least 8 characters and include a special character.', false, $returnToHome);
}

# ===================== FILE UPLOAD VALIDATION =====================
$file = $_FILES['verification_file'] ?? null;

if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
    redirectToSignup('Please upload a verification document.', false, $returnToHome);
}

$maxSize = 5 * 1024 * 1024; // 5MB
$allowedMime = ['image/jpeg', 'image/png', 'application/pdf'];

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mime, $allowedMime, true)) {
    redirectToSignup('Invalid file type. Only JPG, PNG, or PDF allowed.', false, $returnToHome);
}

if ($file['size'] > $maxSize) {
    redirectToSignup('File too large. Maximum size is 5MB.', false, $returnToHome);
}

// Save to /uploads/verification/ (created if missing)
$uploadDir = __DIR__ . '/uploads/verification/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$newName = bin2hex(random_bytes(16)) . '.' . $ext;
$destination = $uploadDir . $newName;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    redirectToSignup('Failed to save uploaded file. Please try again.', false, $returnToHome);
}

// Path stored in DB (relative, safe for web access)
$verification_path = 'uploads/verification/' . $newName;
# ==================================================================

# CAPTCHA CHECK
$secretKey = "6LeVwogsAAAAAOyhOhKAUz4btXu_ljq5S6igPl_N";
$responseKey = $_POST['g-recaptcha-response'] ?? '';
$userIP = $_SERVER['REMOTE_ADDR'];

$url = "https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey&remoteip=$userIP";
$response = file_get_contents($url);
$responseData = json_decode($response);

if (!$responseData || empty($responseData->success)) {
    // Clean up uploaded file if captcha fails
    if (file_exists($destination)) unlink($destination);
    redirectToSignup('Captcha verification failed. Please try again.', false, $returnToHome);
}

# CHECK EMAIL
$checkQuery = "SELECT id FROM users WHERE email = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param("s", $email);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    // Clean up uploaded file if email already exists
    if (file_exists($destination)) unlink($destination);
    redirectToSignup('Email already registered.', false, $returnToHome);
}

$checkStmt->close();

# HASH PASSWORD
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

# GENERATE ACTIVATION CODE
$activation_code = bin2hex(random_bytes(16));

# INSERT USER (now includes verification_document)
$query = "INSERT INTO users (fullname,email,password,is_verified,activation_code,verification_document)
VALUES (?,?,?,?,?,?)";

$stmt = $conn->prepare($query);
$verified = 0;

$stmt->bind_param("sssiss",$fullname,$email,$hashedPassword,$verified,$activation_code,$verification_path);

$stmt->execute();
$stmt->close();

# SEND EMAIL
try{
$activation_link = "http://192.168.1.3/calinga/actions/activation.php?code=".$activation_code;
sendAppMail(
    $email,
    $fullname,
    "Activate Your CALINGA Account",
    "  <h3>Hello $fullname,</h3>
        <p>Please click the button below to activate your account:</p>
        <table cellspacing='0' cellpadding='0'>
            <tr>
                <td align='center' bgcolor='#1e3580' style='border-radius:5px;'>
                    <a href='$activation_link' target='_blank' 
                        style='display:inline-block; padding:10px 20px; font-size:16px; color:#fff; text-decoration:none;'>
                        Activate Account
                    </a>
                </td>
            </tr>
        </table>
        <p>If the button doesn't work, copy and paste this URL into your browser:</p>
        <p>$activation_link</p>"
);

if ($returnToHome) {
    $_SESSION['activation_notice'] = true;
}
redirectToSignup('Registration successful! Please check your email to activate your account.', true, $returnToHome);

}catch(Throwable $e){

redirectToSignup('Your account was created, but the activation email could not be sent. Please contact support.', false, $returnToHome);

}

}
?>
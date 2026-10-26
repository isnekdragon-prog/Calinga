<?php
session_start();
require_once __DIR__ . '/db.php';

// ✅ GENERATE TOKEN IF NOT EXISTS
if (empty($_SESSION['admin_action_token'])) {
    $_SESSION['admin_action_token'] = bin2hex(random_bytes(32));
}

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    die('Please login first');
}

// ✅ Also check content admin token
if (empty($_SESSION['content_admin_token'])) {
    $_SESSION['content_admin_token'] = bin2hex(random_bytes(32));
}

echo "=== CSRF TOKEN INFORMATION ===<br><br>";
echo "Admin Action Token: " . $_SESSION['admin_action_token'] . "<br>";
echo "Token Length: " . strlen($_SESSION['admin_action_token']) . " characters<br>";
echo "Content Admin Token: " . $_SESSION['content_admin_token'] . "<br>";
echo "Content Token Length: " . strlen($_SESSION['content_admin_token']) . " characters<br><br>";

echo "=== SESSION INFORMATION ===<br>";
echo "Session ID: " . session_id() . "<br>";
echo "User ID: " . $_SESSION['user_id'] . "<br>";

// Check if user is admin
$stmt = $conn->prepare("SELECT is_admin, is_staff, fullname FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

echo "Name: " . ($user['fullname'] ?? 'Unknown') . "<br>";
echo "Is Admin: " . ($user['is_admin'] ? 'Yes' : 'No') . "<br>";
echo "Is Staff: " . ($user['is_staff'] ? 'Yes' : 'No') . "<br><br>";

// Check if tokens are random (64 characters hex)
function isHex($str) {
    return preg_match('/^[0-9a-f]+$/', $str);
}

echo "=== TOKEN VALIDATION ===<br>";
echo "Admin Token is 64 chars: " . (strlen($_SESSION['admin_action_token']) === 64 ? '✅ Yes' : '❌ No') . "<br>";
echo "Admin Token is hex: " . (isHex($_SESSION['admin_action_token']) ? '✅ Yes' : '❌ No') . "<br>";
echo "Content Token is 64 chars: " . (strlen($_SESSION['content_admin_token']) === 64 ? '✅ Yes' : '❌ No') . "<br>";
echo "Content Token is hex: " . (isHex($_SESSION['content_admin_token']) ? '✅ Yes' : '❌ No') . "<br>";
?>
<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth_helpers.php';

$adminUser = requireAdmin($conn);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_panel.php?tab=locked_accounts&error=' . urlencode('Invalid request method.'));
    exit;
}

$token = $_POST['csrf_token'] ?? '';
if (empty($_SESSION['admin_action_token']) || !hash_equals($_SESSION['admin_action_token'], $token)) {
    header('Location: admin_panel.php?tab=locked_accounts&error=' . urlencode('Invalid security token.'));
    exit;
}

$userId = (int) ($_POST['user_id'] ?? 0);
if ($userId <= 0) {
    header('Location: admin_panel.php?tab=locked_accounts&error=' . urlencode('Invalid user selected.'));
    exit;
}

$stmt = $conn->prepare("UPDATE users SET failed_login_attempts = 0, locked_until = NULL WHERE id = ? AND COALESCE(is_admin, 0) = 0");
$stmt->bind_param("i", $userId);
$stmt->execute();
$affectedRows = $stmt->affected_rows;
$stmt->close();

if ($affectedRows > 0) {
    header('Location: admin_panel.php?tab=locked_accounts&success=' . urlencode('User account unlocked successfully.'));
    exit;
}

header('Location: admin_panel.php?tab=locked_accounts&error=' . urlencode('User account could not be unlocked.'));
exit;
?>

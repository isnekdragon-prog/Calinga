<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $conn->prepare('SELECT is_admin, is_staff FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

// approval is admin-only, staff can view but not decide — adjust if you want staff to approve too
if (!$user || !$user['is_admin']) {
    header('Location: admin_panel.php?tab=aics&error=' . urlencode('Access denied.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_panel.php?tab=pending_accounts');
    exit;
}

if (!hash_equals($_SESSION['admin_action_token'] ?? '', $_POST['csrf_token'] ?? '')) {
    header('Location: admin_panel.php?tab=pending_accounts&error=' . urlencode('Invalid request token.'));
    exit;
}

$targetUserId = (int) ($_POST['user_id'] ?? 0);
$action = $_POST['action'] ?? '';

if (!$targetUserId || !in_array($action, ['approve', 'reject'], true)) {
    header('Location: admin_panel.php?tab=pending_accounts&error=' . urlencode('Invalid request.'));
    exit;
}

$newStatus = $action === 'approve' ? 'approved' : 'rejected';

$stmt = $conn->prepare('UPDATE users SET account_status = ? WHERE id = ? AND account_status = "pending"');
$stmt->bind_param('si', $newStatus, $targetUserId);
$stmt->execute();
$affected = $stmt->affected_rows;
$stmt->close();

if ($affected > 0) {
    $logStmt = $conn->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
    $logAction = $action === 'approve' ? 'ACCOUNT_APPROVED' : 'ACCOUNT_REJECTED';
    $details = 'User ID: ' . $targetUserId;
    $ip = $_SERVER['REMOTE_ADDR'];
    $logStmt->bind_param('isss', $_SESSION['user_id'], $logAction, $details, $ip);
    $logStmt->execute();
    $logStmt->close();

    // Optional: email the user their result
    // require_once __DIR__ . '/mail_helper.php';
    // fetch fullname/email and sendAppMail(...) here

    header('Location: admin_panel.php?tab=pending_accounts&success=' . urlencode('Account ' . $newStatus . '.'));
} else {
    header('Location: admin_panel.php?tab=pending_accounts&error=' . urlencode('Account already processed.'));
}
exit;
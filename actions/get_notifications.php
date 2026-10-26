<?php
session_start();
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'mark_all_read') {
        $markReadStmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = ? AND is_read = 0");
        $markReadStmt->bind_param("i", $user_id);
        $markReadStmt->execute();
        $markReadStmt->close();
    } elseif ($action === 'mark_read') {
        $notification_id = intval($_POST['notification_id'] ?? 0);
        if ($notification_id > 0) {
            $markSingleStmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?");
            $markSingleStmt->bind_param("ii", $notification_id, $user_id);
            $markSingleStmt->execute();
            $markSingleStmt->close();
        }
    }
}

// Get recent notifications
$notifStmt = $conn->prepare("SELECT id, title, message, type, target_url, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 50");
$notifStmt->bind_param("i", $user_id);
$notifStmt->execute();
$notifResult = $notifStmt->get_result();
$notifications = [];
while ($row = $notifResult->fetch_assoc()) {
    $notifications[] = $row;
}
$notifStmt->close();

// Get unread count
$unreadStmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
$unreadStmt->bind_param("i", $user_id);
$unreadStmt->execute();
$unreadResult = $unreadStmt->get_result();
$unreadCount = $unreadResult->fetch_assoc()['count'] ?? 0;
$unreadStmt->close();

echo json_encode([
    'success' => true,
    'notifications' => $notifications,
    'unread_count' => $unreadCount
]);


<?php
session_start();
require_once "db.php";
require_once "create_notifications.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "User not logged in"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

/* GET NOTIFICATIONS */
$stmt = $conn->prepare("
    SELECT id, title, message, type, is_read, created_at
    FROM notifications
    WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT 10
");

if(!$stmt){
    die("SQL Error: ".$conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];
$unread_count = 0;

while ($row = $result->fetch_assoc()) {

    if (!$row['is_read']) {
        $unread_count++;
    }

    $notifications[] = [
        "id" => $row['id'],
        "title" => $row['title'],
        "message" => $row['message'],
        "type" => $row['type'],
        "is_read" => $row['is_read'],
        "created_at" => $row['created_at']
    ];
}

$stmt->close();


/* MARK ALL AS READ */
$update = $conn->prepare("
    UPDATE notifications
    SET is_read = 1
    WHERE user_id = ?
");

if($update){
    $update->bind_param("i", $user_id);
    $update->execute();
    $update->close();
}


/* RETURN JSON */
echo json_encode([
    "success" => true,
    "notifications" => $notifications,
    "unread_count" => $unread_count
]);


/* CREATE NOTIFICATION FUNCTION 
function createNotification($conn, $user_id, $title, $message, $type = "general")
{
    $stmt = $conn->prepare("
        INSERT INTO notifications (user_id, title, message, type)
        VALUES (?, ?, ?, ?)
    ");

    if(!$stmt){
        die("SQL Error: ".$conn->error);
    }

    $stmt->bind_param("isss", $user_id, $title, $message, $type);
    $stmt->execute();
    $stmt->close();
} */

?>
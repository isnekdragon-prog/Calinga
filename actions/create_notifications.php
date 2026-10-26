<?php

function createNotification($conn, $user_id, $title, $message, $type = "general", $targetUrl = null)
{
    $stmt = $conn->prepare("
        INSERT INTO notifications (user_id, title, message, type, target_url)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("issss", $user_id, $title, $message, $type, $targetUrl);
    $stmt->execute();
    $stmt->close();
}

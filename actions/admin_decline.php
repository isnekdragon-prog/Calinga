<?php

require_once "create_notification.php";

createNotification(
    $conn,
    $app['user_id'],
    "Application Declined",
    "Unfortunately your AICS request was declined. Please contact MSWD.",
    "declined"
);
?>
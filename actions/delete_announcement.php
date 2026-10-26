<?php
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/auth_helpers.php";

requireAdmin($conn);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrfToken($_POST['csrf_token'] ?? '', 'content_admin_token')) {
    header("Location: admin_panel.php?tab=announcements&error=" . urlencode("Invalid delete request."));
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    header("Location: admin_panel.php?tab=announcements&error=" . urlencode("Invalid announcement ID."));
    exit;
}

$stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: admin_panel.php?tab=announcements&success=" . urlencode("Announcement deleted."));
exit;

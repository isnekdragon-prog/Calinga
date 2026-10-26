<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth_helpers.php';

requireAdmin($conn);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verifyCsrfToken($_POST['csrf_token'] ?? '', 'content_admin_token')) {
    header("Location: admin_panel.php?tab=news&error=" . urlencode("Invalid delete request."));
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    header("Location: admin_panel.php?tab=news&error=" . urlencode("Invalid news ID."));
    exit;
}

$stmt = $conn->prepare("SELECT image FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($row && !empty($row['image']) && file_exists(__DIR__ . "/../uploads/" . $row['image'])) {
    unlink(__DIR__ . "/../uploads/" . $row['image']);
}

$stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

header("Location: admin_panel.php?tab=news&success=" . urlencode("News article deleted."));
exit;

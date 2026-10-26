<?php
require_once __DIR__ . '/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$application_id = intval($_GET['id'] ?? 0);
if ($application_id > 0) {
    header('Location: solo_parent_admin_view_fixed.php?id=' . $application_id);
    exit;
}

header('Location: admin_panel.php?tab=solo_parent&error=' . urlencode('Invalid application ID.'));
exit;
?>

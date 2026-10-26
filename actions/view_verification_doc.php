<?php
session_start();
require_once __DIR__ . '/db.php';

// Only logged-in admins may view verification documents
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Forbidden');
}

$stmt = $conn->prepare('SELECT is_admin FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$viewer = $result->fetch_assoc();
$stmt->close();

if (!$viewer || !$viewer['is_admin']) {
    http_response_code(403);
    exit('Forbidden');
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(400);
    exit('Invalid request');
}

$stmt = $conn->prepare("SELECT verification_document FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($path);
$stmt->fetch();
$stmt->close();

if (!$path) {
    http_response_code(404);
    exit('No verification document on file.');
}

// Prevent path traversal — only allow uploads/verification/
$baseDir = realpath(__DIR__ . '/uploads/verification');
$fullPath = realpath(__DIR__ . '/' . $path);

if (!$baseDir || !$fullPath || strpos($fullPath, $baseDir) !== 0 || !is_file($fullPath)) {
    http_response_code(404);
    exit('File not found.');
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $fullPath);
finfo_close($finfo);

$allowedMime = ['image/jpeg', 'image/png', 'application/pdf'];
if (!in_array($mime, $allowedMime, true)) {
    http_response_code(415);
    exit('Unsupported file type.');
}

header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($fullPath));
header('Content-Disposition: inline; filename="' . basename($fullPath) . '"');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store');
readfile($fullPath);
exit;
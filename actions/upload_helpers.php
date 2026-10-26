<?php

function storeUploadedImage(array $file, string $destinationDir, ?string &$error = null, string $prefix = 'image'): ?string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        $error = 'Image upload failed.';
        return null;
    }

    if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
        $error = 'Image must be 5MB or smaller.';
        return null;
    }

    // 🟢 NEW: Validate file extension first
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowedExt = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
    if (!in_array($ext, $allowedExt)) {
        $error = 'Invalid file extension.';
        return null;
    }

    // MIME type validation
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/avif' => 'avif',
    ];

    if (!isset($allowed[$mime])) {
        $error = 'Only JPG, PNG, WEBP, and AVIF images are allowed.';
        return null;
    }

    // 🟢 NEW: Cross-check extension matches MIME type
    $expectedExt = $allowed[$mime];
    if ($ext === 'jpeg') $ext = 'jpg';
    if ($ext !== $expectedExt) {
        $error = 'File extension does not match its content.';
        return null;
    }

    if (!is_dir($destinationDir)) {
        $error = 'Upload directory is missing.';
        return null;
    }

    $safePrefix = preg_replace('/[^a-z0-9_-]/i', '', $prefix) ?: 'image';
    $filename = sprintf('%s_%s.%s', $safePrefix, bin2hex(random_bytes(8)), $allowed[$mime]);
    $targetPath = rtrim($destinationDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        $error = 'Unable to save the uploaded image.';
        return null;
    }

    // 🟢 NEW: Set secure permissions
    chmod($targetPath, 0644);

    return $filename;
}
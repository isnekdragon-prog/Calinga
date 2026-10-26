<?php
/**
 * Secure file upload helper with virus scanning
 */

function storeUploadedImage($file, $targetDir, &$error, $context = 'generic') {
    // Check if file was uploaded
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    
    // 1. Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds server upload limit.',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds form upload limit.',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension.'
        ];
        $error = $uploadErrors[$file['error']] ?? 'Unknown upload error.';
        return null;
    }
    
    // 2. Validate file size (max 5MB)
    $maxSize = 5 * 1024 * 1024; // 5MB
    if ($file['size'] > $maxSize) {
        $error = 'File too large. Maximum size is 5MB.';
        return null;
    }
    
    // 3. Validate file type using finfo (MIME type)
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/avif'];
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
    
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mimeType, $allowedMimeTypes)) {
        $error = 'Invalid file type. Only JPG, PNG, WEBP, and AVIF images are allowed.';
        return null;
    }
    
    // 4. Validate file extension
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowedExtensions)) {
        $error = 'Invalid file extension.';
        return null;
    }
    
    // 5. Verify it's a valid image
    if (!@getimagesize($file['tmp_name'])) {
        $error = 'File is not a valid image.';
        return null;
    }
    
    // 6. VIRUS SCANNING - Check file for malware
    $virusScanResult = scanFileForVirus($file['tmp_name'], $file['name']);
    if ($virusScanResult === false) {
        $error = 'Security scan detected potential threats. File rejected.';
        error_log("Virus detected in uploaded file: {$file['name']} by user context: $context");
        return null;
    }
    
    // 7. Scan for malicious content patterns
    if (scanForMaliciousContent($file['tmp_name']) === false) {
        $error = 'File contains suspicious content.';
        error_log("Suspicious content detected in file: {$file['name']}");
        return null;
    }
    
    // 8. Generate secure random filename (prevents path traversal)
    $randomBytes = random_bytes(32);
    $newFilename = bin2hex($randomBytes) . '.' . $extension;
    
    // 9. Ensure upload directory exists and is secure
    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0755, true)) {
            $error = 'Server configuration error. Cannot save file.';
            return null;
        }
    }
    
    // 10. Prevent directory traversal
    $safePath = realpath($targetDir) . DIRECTORY_SEPARATOR . $newFilename;
    if (strpos($safePath, realpath($targetDir)) !== 0) {
        $error = 'Invalid file path detected.';
        return null;
    }
    
    // 11. Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $safePath)) {
        $error = 'Failed to save uploaded file.';
        return null;
    }
    
    // 12. Set secure file permissions
    chmod($safePath, 0644);
    
    // 13. Log successful upload
    error_log("Secure file upload: {$newFilename} uploaded by user ID: " . ($_SESSION['user_id'] ?? 'unknown') . " context: $context");
    
    return $newFilename;
}

/**
 * Scan file for viruses using multiple methods
 * 
 * @param string $filePath Path to the uploaded file
 * @param string $originalName Original filename (for logging)
 * @return bool True if clean, false if infected or suspicious
 */
function scanFileForVirus($filePath, $originalName = '') {
    // Method 1: Check if ClamAV is available (Linux/Unix)
    if (function_exists('exec') && !is_windows_server()) {
        $clamavPaths = ['/usr/bin/clamscan', '/usr/local/bin/clamscan', '/bin/clamscan'];
        
        foreach ($clamavPaths as $clamavPath) {
            if (file_exists($clamavPath)) {
                $output = [];
                $returnCode = 0;
                exec(escapeshellcmd($clamavPath) . " --no-summary --infected " . escapeshellarg($filePath) . " 2>&1", $output, $returnCode);
                
                // Return code 1 means virus found
                if ($returnCode === 1) {
                    error_log("ClamAV: Virus detected in file: $originalName - " . implode(' ', $output));
                    return false;
                }
                
                // Return code 0 means clean
                if ($returnCode === 0) {
                    return true;
                }
            }
        }
    }
    
    // Method 2: Basic signature scanning (if ClamAV not available)
    $basicScan = basicVirusSignatureScan($filePath);
    if ($basicScan === false) {
        error_log("Basic signature scan detected threat in file: $originalName");
        return false;
    }
    
    // Method 3: Check file extension against blacklist
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $dangerousExtensions = ['php', 'php3', 'php4', 'php5', 'phtml', 'exe', 'bat', 'cmd', 'sh', 'vbs', 'js', 'jar', 'scr'];
    if (in_array($extension, $dangerousExtensions)) {
        error_log("Dangerous file extension blocked: $extension - $originalName");
        return false;
    }
    
    return true;
}

/**
 * Basic virus signature scanning (fallback when ClamAV is not available)
 * Checks for known malware patterns in files
 * 
 * @param string $filePath Path to file
 * @return bool True if clean, false if suspicious
 */
function basicVirusSignatureScan($filePath) {
    $content = file_get_contents($filePath);
    if ($content === false || empty($content)) {
        return true; // Empty or unreadable file, assume clean
    }
    
    // Known malware signatures (simplified patterns)
    $malwareSignatures = [
        // PHP backdoors
        '/<\?php\s*eval\(\$_POST/i',
        '/<\?php\s*system\(\$_GET/i',
        '/<\?php\s*shell_exec/i',
        '/<\?php\s*base64_decode.*eval/i',
        '/<\?php\s*preg_replace.*\/e/i',
        '/<\?php\s*assert\(\$_/i',
        '/<\?php\s*create_function/i',
        
        // JavaScript malware
        '/document\.write.*unescape/i',
        '/eval\(.*fromCharCode/i',
        
        // Common exploits
        '/wget\s+http:\/\/.*\.pl/i',
        '/c99shell/i',
        '/r57shell/i',
        '/webshell/i',
        
        // Obfuscation patterns
        '/\\x[0-9a-f]{2}/i',
        '/%[0-9a-f]{2}%[0-9a-f]{2}/i',
        
        // Remote file inclusion
        '/include.*http:\/\//i',
        '/require.*https:\/\//i',
    ];
    
    foreach ($malwareSignatures as $pattern) {
        if (preg_match($pattern, $content)) {
            error_log("Malware signature detected: " . $pattern);
            return false;
        }
    }
    
    // Check for suspicious file size (extremely small or large)
    $fileSize = filesize($filePath);
    if ($fileSize < 100 && $fileSize > 0) {
        // Very small files might be suspicious (under 100 bytes)
        $tinyFileCheck = preg_match('/<\?php/i', $content);
        if ($tinyFileCheck) {
            error_log("Suspicious tiny PHP file detected");
            return false;
        }
    }
    
    return true;
}

/**
 * Scan file for malicious content (XSS, SQL injection patterns)
 * 
 * @param string $filePath Path to file
 * @return bool True if safe, false if suspicious patterns found
 */
function scanForMaliciousContent($filePath) {
    if (!is_file($filePath)) {
        return false;
    }
    
    $content = file_get_contents($filePath);
    if ($content === false) {
        return false;
    }
    
    // Check for malicious patterns in images (executable code hidden in metadata)
    $suspiciousPatterns = [
        '/<\?php/i',
        '/<script/i',
        '/eval\(/i',
        '/base64_decode/i',
        '/system\(/i',
        '/exec\(/i',
        '/shell_exec/i',
        '/passthru/i',
        '/popen/i',
        '/proc_open/i',
        '/\\\\x00\\\\x00\\\\x00\\\\x00/', // Null bytes
        '/%00/', // URL encoded null byte
    ];
    
    foreach ($suspiciousPatterns as $pattern) {
        if (preg_match($pattern, $content)) {
            error_log("Suspicious pattern detected in file: " . basename($filePath) . " - Pattern: " . $pattern);
            return false;
        }
    }
    
    return true;
}

/**
 * Check if running on Windows server
 * 
 * @return bool
 */
function is_windows_server() {
    return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
}

/**
 * Clean up old uploaded files (optional maintenance)
 * 
 * @param string $uploadDir Directory to clean
 * @param int $maxAgeDays Maximum age in days
 * @return int Number of files deleted
 */
function cleanupOldUploads($uploadDir, $maxAgeDays = 30) {
    $deleted = 0;
    $maxAge = time() - ($maxAgeDays * 24 * 60 * 60);
    
    $files = glob($uploadDir . '/*');
    foreach ($files as $file) {
        if (is_file($file) && filemtime($file) < $maxAge) {
            if (@unlink($file)) {
                $deleted++;
            }
        }
    }
    
    return $deleted;
}

/**
 * Check if a file is an actual image (extra security)
 * 
 * @param string $filePath Path to file
 * @return bool True if valid image
 */
function isValidImage($filePath) {
    $imageInfo = @getimagesize($filePath);
    if ($imageInfo === false) {
        return false;
    }
    
    $validTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];
    return in_array($imageInfo[2], $validTypes);
}
?>
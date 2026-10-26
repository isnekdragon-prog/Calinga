<?php

function ensureSessionStarted(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function requireLogin(mysqli $conn): array
{
    ensureSessionStarted();

    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    $stmt = $conn->prepare("SELECT id, fullname, email, COALESCE(is_admin,0) AS is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$user) {
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }

    return $user;
}

function requireAdmin(mysqli $conn): array
{
    $user = requireLogin($conn);

    if (empty($user['is_admin'])) {
        echo '<p>Access denied.</p>';
        exit;
    }

    return $user;
}

function getCsrfToken(string $key = 'csrf_token'): string
{
    ensureSessionStarted();

    if (empty($_SESSION[$key])) {
        $_SESSION[$key] = bin2hex(random_bytes(32));
    }

    return $_SESSION[$key];
}

function verifyCsrfToken(string $token, string $key = 'csrf_token'): bool
{
    ensureSessionStarted();
    return !empty($_SESSION[$key]) && hash_equals($_SESSION[$key], $token);
}

<?php
session_start();

$redirect = $_GET['redirect'] ?? '../index.php?auth=login';
$reason = $_GET['reason'] ?? '';
if (preg_match('/^(?:https?:)?\/\//i', $redirect)) {
    $redirect = '../index.php?auth=login';
}

if ($reason === 'inactivity' && ($redirect === '../index.php' || $redirect === '../index.php?auth=login')) {
    $redirect = '../index.php?auth=login&error=' . urlencode('You were logged out due to inactivity.');
}

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_unset();
session_destroy();
header('Location: ' . $redirect);
exit;

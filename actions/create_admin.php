<?php
session_start();
require_once __DIR__ . '/db.php';

// if an admin already exists, don't allow another through this script
$res = $conn->query("SELECT id FROM users WHERE is_admin = 1 LIMIT 1");
if ($res && $res->num_rows > 0) {
    echo '<p>Administrator account already exists. <a href="login.php">Log in</a>.</p>';
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (!$fullname || !$email || !$password) {
        $error = 'Please fill in all fields.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // insert admin user
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (fullname,email,password,is_admin) VALUES (?,?,?,1)');
        $stmt->bind_param('sss', $fullname, $email, $hash);
        if ($stmt->execute()) {
            header('Location: login.php?success=1');
            exit;
        } else {
            $error = 'Failed to create admin account.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Admin</title>
<link rel="stylesheet" href="../style.css">
</head>
<body>
<div class="auth-page">
    <div class="auth-container">
        <h2>Create Administrator</h2>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <label>Full Name</label>
            <input type="text" name="fullname" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <label>Confirm Password</label>
            <input type="password" name="confirm" required>
            <button type="submit">Create Admin</button>
        </form>
    </div>
</div>
</body>
</html>
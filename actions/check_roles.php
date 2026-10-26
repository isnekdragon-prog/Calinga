<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    die('Please login first at <a href="../login.php">login.php</a>');
}

echo "<h2>User Role Diagnostic</h2>";

// Check if is_staff column exists
$columnCheck = $conn->query("SHOW COLUMNS FROM users LIKE 'is_staff'");
if ($columnCheck->num_rows == 0) {
    echo "<p style='color:red;'>❌ The 'is_staff' column does NOT exist in the users table!</p>";
    echo "<p>Run this SQL to add it:</p>";
    echo "<pre>ALTER TABLE users ADD COLUMN is_staff TINYINT(1) DEFAULT 0 AFTER is_admin;</pre>";
} else {
    echo "<p style='color:green;'>✅ The 'is_staff' column exists!</p>";
}

// Check current user
$stmt = $conn->prepare("SELECT id, email, fullname, is_admin, is_staff FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

echo "<h3>Your Account:</h3>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Email</th><th>Name</th><th>is_admin</th><th>is_staff</th><th>Access Level</th></tr>";
echo "<tr>";
echo "<td>" . $user['id'] . "</td>";
echo "<td>" . htmlspecialchars($user['email']) . "</td>";
echo "<td>" . htmlspecialchars($user['fullname']) . "</td>";
echo "<td>" . $user['is_admin'] . "</td>";
echo "<td>" . ($user['is_staff'] ?? 'NULL') . "</td>";
echo "<td>";
if ($user['is_admin'] == 1) {
    echo "<span style='color:red; font-weight:bold;'>👑 ADMIN</span>";
} elseif (($user['is_staff'] ?? 0) == 1) {
    echo "<span style='color:orange; font-weight:bold;'>👔 STAFF</span>";
} else {
    echo "<span style='color:gray;'>Regular User</span>";
}
echo "</td>";
echo "</tr>";
echo "</table>";

// Fix buttons
echo "<h3>Quick Fix:</h3>";
echo "<form method='POST'>";
echo "<input type='hidden' name='fix_admin' value='1'>";
echo "<button type='submit' style='padding:10px 20px; background:#e74c3c; color:white; border:none; border-radius:5px; cursor:pointer;'>Make Me ADMIN</button>";
echo "</form>";

echo "<form method='POST' style='margin-top:10px;'>";
echo "<input type='hidden' name='fix_staff' value='1'>";
echo "<button type='submit' style='padding:10px 20px; background:#f39c12; color:white; border:none; border-radius:5px; cursor:pointer;'>Make Me STAFF</button>";
echo "</form>";

echo "<form method='POST' style='margin-top:10px;'>";
echo "<input type='hidden' name='fix_both' value='1'>";
echo "<button type='submit' style='padding:10px 20px; background:#27ae60; color:white; border:none; border-radius:5px; cursor:pointer;'>Make Me BOTH (Admin + Staff)</button>";
echo "</form>";

// Process fixes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['fix_admin'])) {
        $conn->query("UPDATE users SET is_admin = 1 WHERE id = " . $_SESSION['user_id']);
        echo "<p style='color:green;'>✅ You are now ADMIN! <a href='check_roles.php'>Refresh</a></p>";
    }
    if (isset($_POST['fix_staff'])) {
        $conn->query("UPDATE users SET is_admin = 0, is_staff = 1 WHERE id = " . $_SESSION['user_id']);
        echo "<p style='color:orange;'>👔 You are now STAFF! <a href='check_roles.php'>Refresh</a></p>";
    }
    if (isset($_POST['fix_both'])) {
        $conn->query("UPDATE users SET is_admin = 1, is_staff = 1 WHERE id = " . $_SESSION['user_id']);
        echo "<p style='color:green;'>✅ You are now BOTH Admin and Staff! <a href='check_roles.php'>Refresh</a></p>";
    }
}

echo "<p><a href='calinga/index.php'>Go to Admin Panel</a></p>";
?>
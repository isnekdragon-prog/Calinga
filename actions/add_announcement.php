<?php
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/auth_helpers.php";
require_once __DIR__ . "/create_notifications.php";

requireAdmin($conn);
$csrfToken = getCsrfToken('content_admin_token');
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '', 'content_admin_token')) {
        $error = 'Security token mismatch. Please refresh and try again.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $link = trim($_POST['link'] ?? '');

        if ($title === '' || $content === '') {
            $error = 'Title and content are required.';
        } else {
            $sql = "INSERT INTO announcements (title, content, link) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $title, $content, $link);
            $stmt->execute();
            $stmt->close();

            $result = $conn->query("SELECT id FROM users");
            while ($user = $result->fetch_assoc()) {
                createNotification(
                    $conn,
                    $user['id'],
                    "New Announcement",
                    "MSWD posted a new announcement. Check the announcements section.",
                    "announcement",
                    "../index.php#announcements"
                );
            }

            header("Location: admin_panel.php?tab=announcements&success=" . urlencode("Announcement added successfully."));
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Announcement - CALINGA Admin</title>
    <link rel="stylesheet" href="../style1.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 50px auto; background: #fff; padding: 2rem; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        h2 { color: #2c3e50; margin-bottom: 1.5rem; text-align: center; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; }
        input[type="text"], textarea { width: 100%; padding: 0.8rem; margin-bottom: 1.5rem; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem; transition: 0.3s; }
        input[type="text"]:focus, textarea:focus { outline: none; border-color: #3498db; box-shadow: 0 0 5px rgba(52,152,219,0.3); }
        textarea { resize: vertical; min-height: 120px; }
        .btn { display: inline-block; padding: 0.8rem 1.5rem; background: #3498db; color: #fff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn:hover { background: #2980b9; }
        .btn-back { display: inline-block; margin-bottom: 1.5rem; padding: 0.5rem 1rem; background: #95a5a6; color: #fff; border-radius: 6px; text-decoration: none; transition: 0.3s; }
        .btn-back:hover { background: #7f8c8d; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 0.85rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="container">
        <a href="admin_panel.php?tab=announcements" class="btn-back">&larr; Back to Admin Panel</a>
        <h2>Add Announcement</h2>
        <?php if ($error): ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" placeholder="Announcement Title" required>

            <label for="content">Content</label>
            <textarea name="content" id="content" placeholder="Write the announcement here..." required></textarea>

            <label for="link">Read More Link</label>
            <input type="text" name="link" id="link" placeholder="https://example.com">

            <button type="submit" class="btn">Add Announcement</button>
        </form>
    </div>
</body>
</html>

<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth_helpers.php';
require_once __DIR__ . '/upload_helpers.php';

requireAdmin($conn);
$csrfToken = getCsrfToken('content_admin_token');
$id = (int)($_GET['id'] ?? 0);
$error = '';

if ($id <= 0) {
    header("Location: admin_panel.php?tab=news&error=" . urlencode("Invalid news ID."));
    exit;
}

$stmt = $conn->prepare("SELECT * FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$news = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$news) {
    header("Location: admin_panel.php?tab=news&error=" . urlencode("News not found."));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCsrfToken($_POST['csrf_token'] ?? '', 'content_admin_token')) {
        $error = 'Security token mismatch. Please refresh and try again.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if ($title === '' || $content === '') {
            $error = 'Title and content are required.';
        } else {
            $filename = $news['image'];
            if (!empty($_FILES['image']['name'])) {
                $uploadError = null;
                $newImage = storeUploadedImage($_FILES['image'], realpath(__DIR__ . '/../uploads'), $uploadError);
                if ($uploadError) {
                    $error = $uploadError;
                } else {
                    $filename = $newImage;
                    if (!empty($news['image']) && file_exists(__DIR__ . "/../uploads/" . $news['image'])) {
                        unlink(__DIR__ . "/../uploads/" . $news['image']);
                    }
                }
            }

            if ($error === '') {
                $stmt = $conn->prepare("UPDATE news SET title = ?, content = ?, image = ? WHERE id = ?");
                $stmt->bind_param("sssi", $title, $content, $filename, $id);
                $stmt->execute();
                $stmt->close();

                header("Location: admin_panel.php?tab=news&success=" . urlencode("News article updated successfully."));
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit News - CALINGA Admin</title>
    <link rel="stylesheet" href="../style1.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 50px auto; background: #fff; padding: 2rem; border-radius: 10px; box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; text-align: center; margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: #2c3e50; }
        input[type="text"], textarea, input[type="file"] { width: 100%; padding: 0.8rem; margin-bottom: 1.5rem; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem; transition: 0.3s; }
        input[type="text"]:focus, textarea:focus, input[type="file"]:focus { outline: none; border-color: #3498db; box-shadow: 0 0 5px rgba(52,152,219,0.3); }
        textarea { resize: vertical; min-height: 120px; }
        .btn { display: inline-block; padding: 0.8rem 1.5rem; background: #3498db; color: #fff; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn:hover { background: #2980b9; }
        .btn-back { display: inline-block; margin-bottom: 1.5rem; padding: 0.5rem 1rem; background: #95a5a6; color: #fff; border-radius: 6px; text-decoration: none; transition: 0.3s; }
        .btn-back:hover { background: #7f8c8d; }
        .preview-img { display: block; margin-bottom: 1.5rem; max-width: 100%; border-radius: 6px; border: 1px solid #ddd; }
        .alert-error { background: #f8d7da; color: #721c24; padding: 0.85rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
    </style>
    <script>
        function previewImage(event){
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('imgPreview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <a href="admin_panel.php?tab=news" class="btn-back">&larr; Back to Admin Panel</a>
        <h1>Edit News</h1>
        <?php if ($error): ?><div class="alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($news['title']) ?>" required>

            <label for="content">Content</label>
            <textarea name="content" id="content" required><?= htmlspecialchars($news['content']) ?></textarea>

            <label for="image">Image</label>
            <?php if(!empty($news['image'])): ?>
                <img id="imgPreview" class="preview-img" src="../uploads/<?= htmlspecialchars($news['image']) ?>" alt="Current Image">
            <?php else: ?>
                <img id="imgPreview" class="preview-img" style="display:none;" alt="Image Preview">
            <?php endif; ?>
            <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp,image/avif" onchange="previewImage(event)">

            <button type="submit" class="btn">Update News</button>
        </form>
    </div>
</body>
</html>

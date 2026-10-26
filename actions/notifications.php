<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT fullname, email, profile_image, COALESCE(is_admin,0) AS is_admin FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$userName = $user['fullname'] ?? '';
$userProfileImage = $user['profile_image'] ?? '';
$isAdmin = (bool)($user['is_admin'] ?? 0);

$notifStmt = $conn->prepare("SELECT id, title, message, type, target_url, is_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 100");
$notifStmt->bind_param("i", $user_id);
$notifStmt->execute();
$notifResult = $notifStmt->get_result();
$notifications = [];
while ($row = $notifResult->fetch_assoc()) {
    $notifications[] = $row;
}
$notifStmt->close();

$unreadStmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
$unreadStmt->bind_param("i", $user_id);
$unreadStmt->execute();
$unreadResult = $unreadStmt->get_result();
$unreadCount = (int)($unreadResult->fetch_assoc()['count'] ?? 0);
$unreadStmt->close();

function notificationTypeLabel(string $type): string {
    $type = strtolower(trim($type));
    if ($type === 'solo_parent') {
        return 'Solo Parent';
    }
    return ucfirst($type ?: 'General');
}

function notificationTypeIcon(string $type): string {
    $type = strtolower(trim($type));
    switch ($type) {
        case 'application':
            return '&#128221;';
        case 'approved':
            return '&#10003;';
        case 'declined':
            return '&#10005;';
        case 'announcement':
            return '&#128227;';
        case 'solo_parent':
            return '&#128106;';
        default:
            return '&#128276;';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - CALINGA MSWD</title>
    <link rel="stylesheet" href="../style1.css">
    <style>
        .notif-page {
            width: min(calc(100% - 32px), 1100px);
            margin: 0 auto;
            padding: 8rem 0 4rem;
        }

        .notif-shell {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 1.5rem;
            align-items: start;
        }

        .notif-sidebar,
        .notif-main {
            background: rgba(255, 253, 248, 0.96);
            border: 1px solid rgba(36, 51, 61, 0.08);
            border-radius: 24px;
            box-shadow: 0 18px 40px rgba(17, 42, 54, 0.08);
        }

        .notif-sidebar {
            padding: 1.4rem;
            position: sticky;
            top: 110px;
        }

        .notif-back {
            display: inline-flex;
            margin-bottom: 1rem;
            color: #0f6c7b;
            text-decoration: none;
            font-weight: 700;
        }

        .notif-back:hover {
            color: #0a5560;
        }

        .notif-sidebar h1 {
            color: #20313d;
            font-size: 1.8rem;
            line-height: 1.1;
            margin-bottom: 0.45rem;
        }

        .notif-sidebar p {
            color: #66757f;
            font-size: 0.95rem;
        }

        .notif-stats {
            display: grid;
            gap: 0.9rem;
            margin-top: 1.4rem;
        }

        .notif-stat {
            padding: 1rem;
            border-radius: 18px;
            background: linear-gradient(180deg, #fff, #f7f2e8);
            border: 1px solid rgba(36, 51, 61, 0.06);
        }

        .notif-stat strong {
            display: block;
            font-size: 1.75rem;
            color: #20313d;
            line-height: 1;
        }

        .notif-stat span {
            display: block;
            margin-top: 0.35rem;
            color: #6d7c85;
            font-size: 0.86rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .notif-actions {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1.4rem;
        }

        .notif-actions button,
        .notif-actions a {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 0.85rem 1rem;
            border-radius: 999px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }

        .notif-actions button {
            background: linear-gradient(135deg, #0f6c7b, #183243);
            color: #fff;
        }

        .notif-actions a {
            background: #f3f6f7;
            color: #20313d;
            border: 1px solid rgba(36, 51, 61, 0.08);
        }

        .notif-main {
            overflow: hidden;
        }

        .notif-main-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem 1.4rem;
            border-bottom: 1px solid #edf1f4;
            background: linear-gradient(180deg, #ffffff, #f9fbfc);
        }

        .notif-main-header h2 {
            margin: 0;
            color: #20313d;
        }

        .notif-main-subtitle {
            color: #74858f;
            font-size: 0.9rem;
            margin-top: 0.2rem;
        }

        .notif-unread-chip {
            padding: 0.35rem 0.75rem;
            border-radius: 999px;
            background: #dff3f6;
            color: #0f6c7b;
            font-size: 0.82rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .notif-list {
            display: flex;
            flex-direction: column;
        }

        .notif-item {
            display: flex;
            gap: 1rem;
            padding: 1.2rem 1.4rem;
            border-bottom: 1px solid #edf1f4;
            transition: background 0.2s ease;
        }

        .notif-item:hover {
            background: #fbfcfd;
        }

        .notif-item.unread {
            background: linear-gradient(90deg, rgba(15, 108, 123, 0.08), transparent 100%);
        }

        .notif-item:last-child {
            border-bottom: none;
        }

        .notif-icon {
            width: 50px;
            height: 50px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
            font-weight: 700;
        }

        .notif-icon.application {
            background: #e6f4f7;
            color: #0f6c7b;
        }

        .notif-icon.approved {
            background: #e8f7ed;
            color: #2b8f55;
        }

        .notif-icon.declined {
            background: #fdeeee;
            color: #cf4d4d;
        }

        .notif-icon.announcement,
        .notif-icon.general,
        .notif-icon.info,
        .notif-icon.solo_parent {
            background: #fdf2df;
            color: #b26d13;
        }

        .notif-content {
            flex: 1;
            min-width: 0;
        }

        .notif-topline {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.35rem;
        }

        .notif-title {
            font-weight: 700;
            color: #24333d;
            font-size: 1rem;
        }

        .notif-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.55rem;
            border-radius: 999px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
        }

        .notif-pill.unread {
            background: #dff3f6;
            color: #0f6c7b;
        }

        .notif-pill.application,
        .notif-pill.announcement,
        .notif-pill.general,
        .notif-pill.info,
        .notif-pill.solo_parent {
            background: #fdf2df;
            color: #9b6518;
        }

        .notif-pill.approved {
            background: #e8f7ed;
            color: #2b8f55;
        }

        .notif-pill.declined {
            background: #fdeeee;
            color: #cf4d4d;
        }

        .notif-message {
            color: #66757f;
            line-height: 1.55;
            font-size: 0.94rem;
        }

        .notif-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-top: 0.6rem;
            flex-wrap: wrap;
        }

        .notif-time {
            color: #90a0a9;
            font-size: 0.82rem;
        }

        .notif-item button {
            border: none;
            background: transparent;
            color: #0f6c7b;
            font-weight: 700;
            cursor: pointer;
            padding: 0;
        }

        .notif-item button:hover {
            color: #0a5560;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1.5rem;
            color: #7f9099;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 0.75rem;
        }

        @media (max-width: 920px) {
            .notif-shell {
                grid-template-columns: 1fr;
            }

            .notif-sidebar {
                position: static;
            }
        }

        @media (max-width: 640px) {
            .notif-page {
                width: min(calc(100% - 20px), 1100px);
                padding-top: 9rem;
            }

            .notif-main-header,
            .notif-item {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
</head>
<body>

<nav class="menu scrolled">
    <div class="menu__logo">
        <img src="../imgs/c4.png" alt="Logo">
        <span>CALINGA</span>
    </div>

    <button class="menu__toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <ul class="menu__links">
        <li><a href="../index.php">Home</a></li>
        <li><a href="aics.php">AICS</a></li>
        <li><a href="solo_parent.php">Solo Parent</a></li>
    </ul>

    <div class="menu__auth">
        <span class="btn btn-outline nav-user-chip" style="cursor: default;">
            <span class="nav-avatar">
                <?php if (!empty($userProfileImage)): ?>
                    <img src="../uploads/<?= htmlspecialchars($userProfileImage) ?>" alt="Profile picture">
                <?php else: ?>
                    <?= htmlspecialchars(function_exists('mb_substr') ? mb_substr($userName, 0, 1) : substr($userName, 0, 1)) ?>
                <?php endif; ?>
            </span>
            <span>Hi, <?= htmlspecialchars($userName) ?></span>
        </span>
        <?php if ($isAdmin): ?>
            <a href="admin_panel.php" class="btn btn-outline">Admin</a>
        <?php endif; ?>
        <a href="profile.php" class="btn btn-outline">Profile</a>
        <a href="logout.php" class="btn btn-outline">Logout</a>
    </div>
</nav>

<div class="notif-page">
    <div class="notif-shell">
        <aside class="notif-sidebar">
            <a href="../index.php" class="notif-back">&larr; Back to homepage</a>
            <h1>Notification Center</h1>
            <p>Review application updates, office announcements, and important system activity in one place.</p>

            <div class="notif-stats">
                <div class="notif-stat">
                    <strong><?= count($notifications) ?></strong>
                    <span>Total Notifications</span>
                </div>
                <div class="notif-stat">
                    <strong id="notifUnreadCount"><?= $unreadCount ?></strong>
                    <span>Unread Right Now</span>
                </div>
            </div>

            <div class="notif-actions">
                <button type="button" onclick="markAllNotificationsRead()">Mark all as read</button>
                <a href="../index.php#announcements">View announcements</a>
            </div>
        </aside>

        <section class="notif-main">
            <div class="notif-main-header">
                <div>
                    <h2>Recent Notifications</h2>
                    <div class="notif-main-subtitle">Newest updates appear first</div>
                </div>
                <div class="notif-unread-chip" id="notifUnreadChip"><?= $unreadCount ?> unread</div>
            </div>

            <div class="notif-list" id="notifPageList">
                <?php if (empty($notifications)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">&#128276;</div>
                        <p>No notifications yet</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($notifications as $notif): ?>
                        <?php $type = strtolower(trim($notif['type'] ?: 'general')); ?>
                        <div class="notif-item <?= $notif['is_read'] ? '' : 'unread' ?>" data-id="<?= (int)$notif['id'] ?>">
                            <div class="notif-icon <?= htmlspecialchars($type) ?>"><?= notificationTypeIcon($type) ?></div>
                            <div class="notif-content">
                                <div class="notif-topline">
                                    <span class="notif-title"><?= htmlspecialchars($notif['title']) ?></span>
                                    <?php if (!$notif['is_read']): ?>
                                        <span class="notif-pill unread">Unread</span>
                                    <?php endif; ?>
                                    <span class="notif-pill <?= htmlspecialchars($type) ?>"><?= htmlspecialchars(notificationTypeLabel($type)) ?></span>
                                </div>
                                <div class="notif-message"><?= nl2br(htmlspecialchars($notif['message'])) ?></div>
                                <div class="notif-footer">
                                    <span class="notif-time"><?= date('M d, Y - h:i A', strtotime($notif['created_at'])) ?></span>
                                    <div style="display:flex; gap:0.85rem; align-items:center; flex-wrap:wrap;">
                                        <?php if (!$notif['is_read']): ?>
                                            <button type="button" onclick="markSingleNotificationRead(<?= (int)$notif['id'] ?>)">Mark as read</button>
                                        <?php endif; ?>
                                        <?php if (!empty($notif['target_url'])): ?>
                                            <a href="<?= htmlspecialchars($notif['target_url']) ?>" style="color:#0f6c7b; font-weight:700; text-decoration:none;">Open related item</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 CALINGA - MSWD | Cainta, Rizal, Philippines</p>
</footer>

<script>
const menu = document.querySelector('.menu');
const menuToggle = document.querySelector('.menu__toggle');

if (menu && menuToggle) {
    menuToggle.addEventListener('click', function() {
        const isOpen = menu.classList.toggle('menu--open');
        menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });

    document.querySelectorAll('.menu__links a, .menu__auth a').forEach(function(link) {
        link.addEventListener('click', function() {
            menu.classList.remove('menu--open');
            menuToggle.setAttribute('aria-expanded', 'false');
        });
    });
}

function updateUnreadDisplay(count) {
    const unreadCount = document.getElementById('notifUnreadCount');
    const unreadChip = document.getElementById('notifUnreadChip');

    if (unreadCount) unreadCount.textContent = count;
    if (unreadChip) unreadChip.textContent = count + ' unread';
}

function postNotificationAction(payload, onSuccess) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'get_notifications.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success && typeof onSuccess === 'function') {
                    onSuccess(response);
                }
            } catch (e) {
                console.error('Notification action failed:', e);
            }
        }
    };
    xhr.send(payload);
}

function markSingleNotificationRead(notificationId) {
    postNotificationAction('action=mark_read&notification_id=' + encodeURIComponent(notificationId), function(response) {
        const item = document.querySelector('.notif-item[data-id="' + notificationId + '"]');
        if (item) {
            item.classList.remove('unread');
            const unreadPill = item.querySelector('.notif-pill.unread');
            const button = item.querySelector('button');
            if (unreadPill) unreadPill.remove();
            if (button) button.remove();
        }
        updateUnreadDisplay(response.unread_count);
    });
}

function markAllNotificationsRead() {
    postNotificationAction('action=mark_all_read', function(response) {
        document.querySelectorAll('.notif-item.unread').forEach(function(item) {
            item.classList.remove('unread');
            const unreadPill = item.querySelector('.notif-pill.unread');
            const button = item.querySelector('button');
            if (unreadPill) unreadPill.remove();
            if (button) button.remove();
        });
        updateUnreadDisplay(response.unread_count);
    });
}
</script>

</body>
</html>

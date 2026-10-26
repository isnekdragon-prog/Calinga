<?php
// This file should be included after session_start() and db.php connection.

if (!isset($conn) || !isset($_SESSION['user_id'])) {
    return;
}

$notif_user_id = $_SESSION['user_id'];

$notifCount = 0;
$notifCountStmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
$notifCountStmt->bind_param("i", $notif_user_id);
$notifCountStmt->execute();
$notifCountResult = $notifCountStmt->get_result();
$notifCount = $notifCountResult->fetch_assoc()['count'] ?? 0;
$notifCountStmt->close();

$currentScriptDir = trim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$insideActions = basename($currentScriptDir) === 'actions';
$notificationEndpoint = $insideActions ? 'get_notifications.php' : 'actions/get_notifications.php';
$notificationPage = $insideActions ? 'notifications.php' : 'actions/notifications.php';
?>

<style>
.notification-dropdown {
    position: relative;
    display: inline-block;
}

.notification-dropdown[open] .notification-menu {
    display: block;
}

.notification-bell {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 46px;
    height: 46px;
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.12);
    cursor: pointer;
    position: relative;
    color: #fff;
    transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
}

.notification-bell {
    list-style: none;
}

.notification-bell::-webkit-details-marker {
    display: none;
}

.notification-bell:hover {
    transform: translateY(-1px);
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
}

.notification-bell-icon {
    font-size: 1.15rem;
    line-height: 1;
}

.notification-badge {
    position: absolute;
    top: -2px;
    right: -4px;
    background: linear-gradient(135deg, #ff7b54, #d94343);
    color: white;
    font-size: 0.68rem;
    padding: 3px 6px;
    border-radius: 999px;
    min-width: 20px;
    text-align: center;
    font-weight: 700;
    box-shadow: 0 8px 18px rgba(217, 67, 67, 0.28);
}

.notification-menu {
    display: none;
    position: absolute;
    right: 0;
    top: calc(100% + 12px);
    width: min(420px, calc(100vw - 24px));
    max-height: 540px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 20px 50px rgba(13, 31, 41, 0.18);
    border-radius: 20px;
    z-index: 1000;
    border: 1px solid rgba(36, 51, 61, 0.08);
    animation: notificationSlideDown 0.2s ease-out;
}

@keyframes notificationSlideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notification-header {
    padding: 1rem 1rem 0.85rem;
    border-bottom: 1px solid #edf1f4;
    background: linear-gradient(180deg, #ffffff, #f7fafb);
}

.notification-title-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
}

.notification-title-wrap h4 {
    margin: 0;
    color: #20313d;
    font-size: 1rem;
    font-weight: 700;
}

.notification-subtitle {
    margin-top: 0.2rem;
    color: #74858f;
    font-size: 0.82rem;
}

.notification-actions {
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

.notification-mark-read,
.notification-view-all {
    font-size: 0.8rem;
    color: #0f6c7b;
    text-decoration: none;
    cursor: pointer;
    font-weight: 700;
}

.notification-mark-read {
    background: none;
    border: none;
    padding: 0;
    font: inherit;
}

.notification-mark-read:hover,
.notification-view-all:hover {
    color: #0a5560;
}

.notification-list {
    max-height: 410px;
    overflow-y: auto;
    background: #fff;
}

.notification-item {
    width: 100%;
    padding: 0.95rem 1rem;
    border: none;
    border-bottom: 1px solid #f0f3f5;
    display: flex;
    align-items: flex-start;
    gap: 0.85rem;
    cursor: pointer;
    transition: background 0.2s ease;
    background: #fff;
    text-align: left;
    text-decoration: none;
}

.notification-item:hover {
    background: #f7fafb;
}

.notification-item.unread {
    background: linear-gradient(90deg, rgba(15, 108, 123, 0.08), transparent 100%);
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-icon {
    width: 42px;
    height: 42px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
    flex-shrink: 0;
    font-weight: 700;
}

.notification-icon.application {
    background: #e6f4f7;
    color: #0f6c7b;
}

.notification-icon.approved {
    background: #e8f7ed;
    color: #2b8f55;
}

.notification-icon.declined {
    background: #fdeeee;
    color: #cf4d4d;
}

.notification-icon.announcement,
.notification-icon.general,
.notification-icon.info,
.notification-icon.solo_parent {
    background: #fdf2df;
    color: #b26d13;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    margin-bottom: 0.3rem;
    flex-wrap: wrap;
}

.notification-title {
    font-size: 0.92rem;
    font-weight: 700;
    color: #24333d;
}

.notification-pill {
    display: inline-flex;
    align-items: center;
    padding: 0.14rem 0.45rem;
    border-radius: 999px;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    text-transform: uppercase;
}

.notification-pill.unread {
    background: #dff3f6;
    color: #0f6c7b;
}

.notification-pill.approved {
    background: #e8f7ed;
    color: #2b8f55;
}

.notification-pill.declined {
    background: #fdeeee;
    color: #cf4d4d;
}

.notification-pill.application,
.notification-pill.announcement,
.notification-pill.general,
.notification-pill.info,
.notification-pill.solo_parent {
    background: #fdf2df;
    color: #9b6518;
}

.notification-message {
    font-size: 0.83rem;
    color: #66757f;
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.notification-time {
    font-size: 0.74rem;
    color: #90a0a9;
    margin-top: 0.35rem;
}

.notification-empty,
.notification-loading {
    padding: 2.5rem 1.2rem;
    text-align: center;
    color: #7f9099;
}

.notification-empty-icon,
.notification-loading-icon {
    font-size: 2rem;
    margin-bottom: 0.65rem;
}

.notification-footer {
    border-top: 1px solid #edf1f4;
    padding: 0.85rem 1rem;
    background: #fafcfd;
    text-align: center;
}

.notification-footer a {
    color: #0f6c7b;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.85rem;
}

.notification-footer a:hover {
    color: #0a5560;
}

.notification-list::-webkit-scrollbar {
    width: 7px;
}

.notification-list::-webkit-scrollbar-track {
    background: #f2f5f7;
}

.notification-list::-webkit-scrollbar-thumb {
    background: #cad4d9;
    border-radius: 999px;
}

.notification-list::-webkit-scrollbar-thumb:hover {
    background: #b9c5cb;
}

[data-theme="dark"] .notification-menu,
[data-theme="dark"] .notification-header,
[data-theme="dark"] .notification-list,
[data-theme="dark"] .notification-footer,
[data-theme="dark"] .notification-item {
    background: #172331;
    color: #e8f1f5;
    border-color: rgba(232, 241, 245, 0.14);
}

[data-theme="dark"] .notification-header {
    background: linear-gradient(180deg, #1d2d3d, #172331);
}

[data-theme="dark"] .notification-item:hover,
[data-theme="dark"] .notification-item.unread {
    background: #203244;
}

[data-theme="dark"] .notification-title,
[data-theme="dark"] .notification-title-wrap h4 {
    color: #e8f1f5;
}

[data-theme="dark"] .notification-message,
[data-theme="dark"] .notification-subtitle,
[data-theme="dark"] .notification-time,
[data-theme="dark"] .notification-empty,
[data-theme="dark"] .notification-loading {
    color: #afbec7;
}

[data-theme="dark"] .notification-mark-read,
[data-theme="dark"] .notification-view-all,
[data-theme="dark"] .notification-footer a {
    color: #74d4de;
}

[data-theme="dark"] .notification-icon.application {
    background: rgba(116, 212, 222, 0.16);
    color: #9eeaf0;
}

[data-theme="dark"] .notification-icon.approved {
    background: rgba(74, 216, 138, 0.16);
    color: #8ff0b7;
}

[data-theme="dark"] .notification-icon.declined {
    background: rgba(216, 74, 74, 0.16);
    color: #ff9b9b;
}

[data-theme="dark"] .notification-icon.announcement,
[data-theme="dark"] .notification-icon.general,
[data-theme="dark"] .notification-icon.info,
[data-theme="dark"] .notification-icon.solo_parent {
    background: rgba(255, 210, 122, 0.16);
    color: #ffd27a;
}

[data-theme="dark"] .notification-pill {
    background: rgba(116, 212, 222, 0.14);
    color: #9eeaf0;
}

[data-theme="dark"] .notification-list::-webkit-scrollbar-track {
    background: #101820;
}

[data-theme="dark"] .notification-list::-webkit-scrollbar-thumb {
    background: #3c5366;
}
</style>

<details class="notification-dropdown" id="notificationDropdown" data-endpoint="<?= htmlspecialchars($notificationEndpoint) ?>" data-page="<?= htmlspecialchars($notificationPage) ?>">
    <summary class="notification-bell" aria-label="Open notifications">
        <span class="notification-bell-icon">&#128276;</span>
        <?php if ($notifCount > 0): ?>
            <span class="notification-badge" id="notifBadge"><?= $notifCount > 9 ? '9+' : $notifCount ?></span>
        <?php endif; ?>
    </summary>

    <div class="notification-menu">
        <div class="notification-header">
            <div class="notification-title-row">
                <div class="notification-title-wrap">
                    <h4>Notifications</h4>
                    <div class="notification-subtitle">Updates about your applications and system activity</div>
                </div>
                <div class="notification-actions">
                    <button class="notification-mark-read" type="button">Mark all as read</button>
                    <a class="notification-view-all" href="<?= htmlspecialchars($notificationPage) ?>">View all</a>
                </div>
            </div>
        </div>

        <div class="notification-list" id="notificationList">
            <div class="notification-loading">
                <div class="notification-loading-icon">&#128276;</div>
                <p>Loading notifications...</p>
            </div>
        </div>

        <div class="notification-footer">
            <a href="<?= htmlspecialchars($notificationPage) ?>">Open full notifications page</a>
        </div>
    </div>
</details>

<script>
let notificationsLoaded = false;

function getNotificationDropdown() {
    return document.getElementById('notificationDropdown');
}

function getNotificationEndpoint() {
    const dropdown = getNotificationDropdown();
    return dropdown ? dropdown.dataset.endpoint : 'actions/get_notifications.php';
}

function getNotificationPage() {
    const dropdown = getNotificationDropdown();
    return dropdown ? dropdown.dataset.page : 'actions/notifications.php';
}

function resolveNotificationTarget(targetUrl) {
    if (!targetUrl) return '';
    if (/^(https?:\/\/|\/|\.\.\/)/i.test(targetUrl) || targetUrl.startsWith('actions/')) {
        return targetUrl;
    }

    const path = window.location.pathname.replace(/\\/g, '/');
    if (path.includes('/actions/')) {
        return targetUrl;
    }

    return 'actions/' + targetUrl;
}

function loadNotifications() {
    const xhr = new XMLHttpRequest();
    xhr.open('GET', getNotificationEndpoint(), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    renderNotifications(response.notifications);
                    updateBadge(response.unread_count);
                    notificationsLoaded = true;
                }
            } catch (e) {
                console.error('Error parsing notifications:', e);
            }
        }
    };
    xhr.send();
}

function renderNotifications(notifications) {
    const container = document.getElementById('notificationList');

    if (!notifications || notifications.length === 0) {
        container.innerHTML = `
            <div class="notification-empty">
                <div class="notification-empty-icon">&#128276;</div>
                <p>No notifications yet</p>
            </div>
        `;
        return;
    }

    let html = '';
    notifications.forEach(function(notif) {
        const type = normalizeNotificationType(notif.type);
        const icon = getNotificationIcon(type);
        const timeAgo = getTimeAgo(notif.created_at);
        const unreadPill = notif.is_read ? '' : '<span class="notification-pill unread">Unread</span>';
        const targetUrl = notif.target_url ? String(notif.target_url) : '';

        const resolvedTarget = resolveNotificationTarget(targetUrl) || getNotificationPage();

        html += `
            <a
                class="notification-item ${notif.is_read ? '' : 'unread'}"
                href="${escapeHtml(resolvedTarget)}"
                data-notification-id="${notif.id}"
                data-is-read="${notif.is_read ? 'true' : 'false'}"
                data-target-url="${escapeHtml(targetUrl)}"
            >
                <div class="notification-icon ${type}">
                    ${icon}
                </div>
                <div class="notification-content">
                    <div class="notification-meta">
                        <span class="notification-title">${escapeHtml(notif.title)}</span>
                        ${unreadPill}
                        <span class="notification-pill ${type}">${formatNotificationType(type)}</span>
                    </div>
                    <div class="notification-message">${escapeHtml(notif.message)}</div>
                    <div class="notification-time">${timeAgo}</div>
                </div>
            </a>
        `;
    });

    container.innerHTML = html;
}

function normalizeNotificationType(type) {
    if (!type) return 'general';
    const normalized = String(type).toLowerCase();
    const known = ['application', 'approved', 'declined', 'announcement', 'general', 'info', 'solo_parent'];
    return known.includes(normalized) ? normalized : 'general';
}

function getNotificationIcon(type) {
    switch (type) {
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

function formatNotificationType(type) {
    switch (type) {
        case 'solo_parent':
            return 'Solo Parent';
        default:
            return type.charAt(0).toUpperCase() + type.slice(1);
    }
}

function getTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);

    if (seconds < 60) return 'Just now';
    if (seconds < 3600) return Math.floor(seconds / 60) + ' minutes ago';
    if (seconds < 86400) return Math.floor(seconds / 3600) + ' hours ago';
    if (seconds < 604800) return Math.floor(seconds / 86400) + ' days ago';

    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text || '';
    return div.innerHTML;
}

function updateBadge(count) {
    const badge = document.getElementById('notifBadge');
    const bell = document.querySelector('.notification-bell');

    if (count > 0) {
        if (badge) {
            badge.textContent = count > 9 ? '9+' : count;
        } else if (bell) {
            const newBadge = document.createElement('span');
            newBadge.className = 'notification-badge';
            newBadge.id = 'notifBadge';
            newBadge.textContent = count > 9 ? '9+' : count;
            bell.appendChild(newBadge);
        }
    } else if (badge) {
        badge.remove();
    }
}

function postNotificationAction(payload, callback) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', getNotificationEndpoint(), true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    if (typeof callback === 'function') {
                        callback(response);
                    }
                }
            } catch (e) {
                console.error('Notification action failed:', e);
            }
        }
    };
    xhr.send(payload);
}

function markAllAsRead(event) {
    if (event) {
        event.stopPropagation();
        event.preventDefault();
    }
    postNotificationAction('action=mark_all_read', function(response) {
        updateBadge(response.unread_count);
        renderNotifications(response.notifications);
    });
}

function handleNotificationClick(notifId, isRead, element) {
    if (isRead) {
        return;
    }

    const payload = 'action=mark_read&notification_id=' + encodeURIComponent(notifId);

    if (navigator.sendBeacon) {
        const data = new Blob([payload], { type: 'application/x-www-form-urlencoded' });
        navigator.sendBeacon(getNotificationEndpoint(), data);
        return;
    }

    postNotificationAction(payload, function(response) {
        updateBadge(response.unread_count);
    });
}

document.addEventListener('click', function(event) {
    const dropdown = getNotificationDropdown();
    if (dropdown && !dropdown.contains(event.target)) {
        dropdown.open = false;
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const dropdown = getNotificationDropdown();
    if (!dropdown) {
        return;
    }

    const markAllButton = dropdown.querySelector('.notification-mark-read');
    const list = dropdown.querySelector('#notificationList');

    dropdown.addEventListener('toggle', function() {
        if (dropdown.open) {
            loadNotifications();
        }
    });

    if (markAllButton) {
        markAllButton.addEventListener('click', markAllAsRead);
    }

    if (list) {
        list.addEventListener('click', function(event) {
            const item = event.target.closest('.notification-item');
            if (!item) {
                return;
            }

            event.stopPropagation();

            handleNotificationClick(
                item.dataset.notificationId,
                item.dataset.isRead === 'true',
                item
            );
        });
    }
});
</script>

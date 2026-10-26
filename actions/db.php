<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "calinga";

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_errno) {
    die("Database connection failed.");
}

// Set charset
$conn->set_charset("utf8mb4");

function getLoginRedirectPath(): string
{
    return getActionsBasePath() . '/login.php?error=' . urlencode('You were logged out due to inactivity.');
}

function getLogoutPath(): string
{
    return getActionsBasePath() . '/logout.php';
}

function getActionsBasePath(): string
{
    $scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    $marker = '/actions/';
    $position = strpos($scriptName, $marker);

    if ($position !== false) {
        return substr($scriptName, 0, $position + strlen('/actions'));
    }

    return rtrim(dirname($scriptName), '/') . '/actions';
}

function injectInactivityLogoutScript(string $html, int $sessionTimeoutSeconds, string $logoutPath, string $loginRedirectPath): string
{
    if (stripos($html, '</body>') === false) {
        return $html;
    }

    $script = '<script>
const inactivityLogoutSeconds = ' . json_encode($sessionTimeoutSeconds) . ';
const inactivityLogoutUrl = ' . json_encode($logoutPath . '?reason=inactivity&redirect=' . rawurlencode($loginRedirectPath)) . ';
let inactivityLogoutTimer = null;

function resetInactivityLogoutTimer() {
    if (inactivityLogoutTimer) {
        clearTimeout(inactivityLogoutTimer);
    }

    inactivityLogoutTimer = setTimeout(() => {
        window.location.href = inactivityLogoutUrl;
    }, inactivityLogoutSeconds * 1000);
}

[\'click\', \'keydown\', \'mousemove\', \'scroll\', \'touchstart\'].forEach((eventName) => {
    window.addEventListener(eventName, resetInactivityLogoutTimer, { passive: true });
});

resetInactivityLogoutTimer();
</script>';

    return preg_replace('/<\/body>/i', $script . '</body>', $html, 1) ?? $html;
}

function shouldInjectSiteThemeAssets(): bool
{
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') {
        return false;
    }

    $scriptName = basename(str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? ''));
    $excludedScripts = [
        'actions_notifications.php',
        'get_notifications.php',
        'inquiry_assistant.php',
        'view_verification_doc.php',
        'generate_aics_pdf.php',
        'generate_solo_parent_pdf.php',
        'admin_panel.php',
    ];

    if (in_array($scriptName, $excludedScripts, true)) {
        return false;
    }

    if (strpos($scriptName, '_process.php') !== false) {
        return false;
    }

    return true;
}

function injectSiteThemeAssets(string $html): string
{
    if (stripos($html, '</head>') === false || stripos($html, '</body>') === false) {
        return $html;
    }

    $headScript = <<<'HTML'
<script>
(function () {
    try {
        if (localStorage.getItem('theme') === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        }
    } catch (error) {}
})();
</script>
HTML;

    $style = <<<'HTML'
<style>
.site-theme-toggle {
    position: fixed;
    right: 18px;
    bottom: 18px;
    z-index: 1200;
    width: 46px;
    height: 46px;
    border-radius: 50%;
    border: 1px solid rgba(255, 255, 255, 0.28);
    background: rgba(15, 108, 123, 0.94);
    color: #fff;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 16px 32px rgba(13, 33, 43, 0.28);
    transition: transform 0.2s ease, background 0.2s ease, border-color 0.2s ease;
    flex-shrink: 0;
}
.site-theme-toggle--in-nav {
    position: static;
    right: auto;
    bottom: auto;
    z-index: auto;
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, 0.18);
    box-shadow: none;
}
.nav-notifications {
    display: inline-flex;
    align-items: center;
    gap: 0.65rem;
}
.site-theme-toggle:hover {
    transform: translateY(-2px);
    background: #0a5560;
}
.site-theme-toggle--in-nav:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
}
.site-theme-toggle:focus-visible {
    outline: 3px solid rgba(45, 127, 240, 0.35);
    outline-offset: 3px;
}
.site-theme-toggle svg {
    width: 21px;
    height: 21px;
}
.site-theme-toggle .site-theme-toggle__moon {
    display: none;
}
[data-theme="dark"] .site-theme-toggle {
    background: rgba(255, 255, 255, 0.9);
    color: #101820;
    border-color: rgba(255, 255, 255, 0.35);
}
[data-theme="dark"] .site-theme-toggle:hover {
    background: #fff;
}
[data-theme="dark"] .site-theme-toggle--in-nav {
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
    border-color: rgba(255, 255, 255, 0.22);
}
[data-theme="dark"] .site-theme-toggle--in-nav:hover {
    background: rgba(255, 255, 255, 0.22);
}
[data-theme="dark"] .site-theme-toggle .site-theme-toggle__sun {
    display: none;
}
[data-theme="dark"] .site-theme-toggle .site-theme-toggle__moon {
    display: inline;
}
[data-theme="dark"] {
    color-scheme: dark;
    --bg: #101820;
    --surface: #172331;
    --surface-strong: #203244;
    --surface-dark: #e8f1f5;
    --surface-dark-soft: #b9c7cf;
    --primary: #45b9c7;
    --primary-strong: #74d4de;
    --accent: #6da7ff;
    --accent-soft: rgba(109, 167, 255, 0.18);
    --text: #e8f1f5;
    --text-soft: #afbec7;
    --border: rgba(232, 241, 245, 0.14);
    --shadow-soft: 0 18px 45px rgba(0, 0, 0, 0.26);
    --shadow-strong: 0 22px 60px rgba(0, 0, 0, 0.36);
}
[data-theme="dark"] body {
    color: var(--text);
    background:
        radial-gradient(circle at top left, rgba(69, 185, 199, 0.12), transparent 28%),
        radial-gradient(circle at top right, rgba(109, 167, 255, 0.12), transparent 24%),
        linear-gradient(180deg, #101820 0%, #0d141d 100%) !important;
}
[data-theme="dark"] body > :where(h1, h2, h3, p, label, span, div):not(.menu):not(.hero) {
    color: var(--text) !important;
}
[data-theme="dark"] .form-container,
[data-theme="dark"] .container,
[data-theme="dark"] .profile-sidebar,
[data-theme="dark"] .profile-main,
[data-theme="dark"] .profile-main-header,
[data-theme="dark"] .profile-form,
[data-theme="dark"] .notif-sidebar,
[data-theme="dark"] .notif-main,
[data-theme="dark"] .notif-main-header,
[data-theme="dark"] .application-card,
[data-theme="dark"] .my-applications,
[data-theme="dark"] .info-section,
[data-theme="dark"] .login-warning,
[data-theme="dark"] .auth-card,
[data-theme="dark"] .auth-container,
[data-theme="dark"] .announcement-card,
[data-theme="dark"] .news-card,
[data-theme="dark"] .service-card,
[data-theme="dark"] .highlight-card,
[data-theme="dark"] .contact-card,
[data-theme="dark"] .section-card,
[data-theme="dark"] .history-content,
[data-theme="dark"] .history-card,
[data-theme="dark"] .officials-section,
[data-theme="dark"] .workers-section,
[data-theme="dark"] .card {
    background: var(--surface) !important;
    color: var(--text) !important;
    border-color: var(--border) !important;
}
[data-theme="dark"] .policy-container,
[data-theme="dark"] .profile-stat,
[data-theme="dark"] .profile-field,
[data-theme="dark"] .notif-stat,
[data-theme="dark"] .notif-item,
[data-theme="dark"] .application-item,
[data-theme="dark"] .form-section,
[data-theme="dark"] .info-item,
[data-theme="dark"] .text-block,
[data-theme="dark"] .admin-actions,
[data-theme="dark"] .checkbox-group label,
[data-theme="dark"] .family-table,
[data-theme="dark"] .aics-card,
[data-theme="dark"] .solo-parent-card,
[data-theme="dark"] .official-card,
[data-theme="dark"] .worker-card,
[data-theme="dark"] .service-box,
[data-theme="dark"] .program-card,
[data-theme="dark"] .news-item,
[data-theme="dark"] .announcement-item {
    background: #172331 !important;
    color: var(--text) !important;
    border-color: var(--border) !important;
}
[data-theme="dark"] :where(
    .container,
    .form-container,
    .profile-sidebar,
    .profile-main,
    .profile-main-header,
    .profile-form,
    .notif-sidebar,
    .notif-main,
    .notif-main-header,
    .policy-container,
    .application-card,
    .application-item,
    .my-applications,
    .info-section,
    .login-warning,
    .auth-card,
    .auth-container,
    .section-card,
    .aics-page,
    .solo-parent-page,
    .profile-page,
    .notif-page,
    .history-content,
    .official-card,
    .worker-card,
    .text-block,
    .admin-actions,
    .card
) :where(h1, h2, h3, h4, h5, h6, p, label, span, li, strong, small, div) {
    color: var(--text) !important;
}
[data-theme="dark"] :where(.subtitle, .profile-subtitle, .profile-note, .notification-subtitle, .notif-copy, .info-item label, .form-group label, .policy-container p, .policy-container li, .application-meta, .card-subtitle, .muted, .help-text) {
    color: var(--text-soft) !important;
}
[data-theme="dark"] input,
[data-theme="dark"] select,
[data-theme="dark"] textarea {
    background: #0f1924 !important;
    color: var(--text) !important;
    border-color: var(--border) !important;
}
[data-theme="dark"] input::placeholder,
[data-theme="dark"] textarea::placeholder {
    color: #7f9099 !important;
}
[data-theme="dark"] table,
[data-theme="dark"] th,
[data-theme="dark"] td {
    color: var(--text) !important;
    border-color: var(--border) !important;
}
[data-theme="dark"] th {
    background: #203244 !important;
}
[data-theme="dark"] td {
    background: #172331 !important;
}
[data-theme="dark"] tr:hover td {
    background: #203244 !important;
}
[data-theme="dark"] {
    --bg: #1a1a2e;
    --card: #16213e;
    --text: #e8e8e8;
    --text-soft: #b0b0c8;
    --border: #2a2a4a;
    --sidebar-bg: #0f0f1f;
    --sidebar-hover: #1a1a3a;
    --sidebar-active: #1a3a5a;
    --warning-light: #3a2a0a;
    --success-light: #1a3a2a;
    --danger-light: #3a1a1a;
    --shadow-soft: 0 18px 45px rgba(0, 0, 0, 0.28);
    --shadow-strong: 0 22px 60px rgba(0, 0, 0, 0.38);
}
[data-theme="dark"] .sidebar-logo {
    background: linear-gradient(180deg, #2a6a9a, #1a4a7a) !important;
}
[data-theme="dark"] .stat-card:hover {
    box-shadow: var(--shadow-strong) !important;
}
[data-theme="dark"] .role-badge.admin {
    background: #2a6a9a !important;
    color: #fff !important;
}
[data-theme="dark"] .role-badge.staff {
    background: #b8860b !important;
    color: #fff !important;
}
[data-theme="dark"] .filter-bar {
    background: var(--card) !important;
    border: 1px solid var(--border) !important;
}
[data-theme="dark"] .filter-bar input,
[data-theme="dark"] .filter-bar select {
    background: #1a1a3a !important;
    border: 1px solid #2a2a4a !important;
    color: var(--text) !important;
}
[data-theme="dark"] .filter-bar input:focus,
[data-theme="dark"] .filter-bar select:focus {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 3px rgba(26, 92, 140, 0.2) !important;
}
[data-theme="dark"] .report-panel {
    background: var(--card) !important;
    border: 1px solid var(--border) !important;
}
[data-theme="dark"] .report-note {
    background: #1a2a4a !important;
    color: #8ab4d8 !important;
    border: 1px solid #2a4a6a !important;
}
[data-theme="dark"] .tracker-ready {
    color: #4ad88a !important;
}
[data-theme="dark"] .tracker-cooldown,
[data-theme="dark"] .tracker-locked {
    color: #d84a4a !important;
}
[data-theme="dark"] .btn-view,
[data-theme="dark"] .btn-edit {
    background: #4a6a8a !important;
}
[data-theme="dark"] .sidebar-nav a:hover {
    background: #1a1a3a !important;
    color: var(--text) !important;
}
[data-theme="dark"] .sidebar-nav a.active {
    background: #1a3a5a !important;
    color: #6ab4e8 !important;
}
[data-theme="dark"] .sidebar-footer .return-link:hover {
    background: #1a1a3a !important;
    color: var(--text) !important;
}
[data-theme="dark"] .topbar {
    border-bottom: 1px solid var(--border) !important;
}
[data-theme="dark"] .sidebar-brand {
    border-bottom: 1px solid var(--border) !important;
}
[data-theme="dark"] .sidebar-footer {
    border-top: 1px solid var(--border) !important;
}
[data-theme="dark"] .menu-toggle {
    background: var(--card) !important;
    color: var(--text) !important;
    border: 1px solid var(--border) !important;
}
[data-theme="dark"] .menu-toggle:hover {
    background: var(--sidebar-hover) !important;
}
[data-theme="dark"] .overlay.active {
    background: rgba(0, 0, 0, 0.6) !important;
}
[data-theme="dark"] .menu {
    background: rgba(12, 18, 26, 0.78) !important;
    border-color: rgba(255, 255, 255, 0.14) !important;
}
[data-theme="dark"] .menu.scrolled {
    background: rgba(15, 24, 32, 0.94) !important;
}
[data-theme="dark"] .menu__links a,
[data-theme="dark"] .menu__links a:hover,
[data-theme="dark"] .dropdown__menu a,
[data-theme="dark"] .dropdown__menu a:hover {
    color: #edf8ff !important;
}
[data-theme="dark"] .dropdown__menu {
    background: rgba(18, 29, 38, 0.98) !important;
    border: 1px solid var(--border) !important;
}
[data-theme="dark"] .btn-outline,
[data-theme="dark"] .btn-ghost {
    border-color: rgba(255, 255, 255, 0.2) !important;
    color: #f0f7ff !important;
    background: rgba(255, 255, 255, 0.05) !important;
}
[data-theme="dark"] .hero__eyebrow,
[data-theme="dark"] .section-kicker {
    background: rgba(109, 167, 255, 0.12) !important;
    border-color: rgba(148, 197, 255, 0.2) !important;
    color: #e3f3ff !important;
}
[data-theme="dark"] .service-card,
[data-theme="dark"] .service-card--accent,
[data-theme="dark"] .intro-panel,
[data-theme="dark"] .about,
[data-theme="dark"] .announcement-card,
[data-theme="dark"] .news-card,
[data-theme="dark"] .highlight-card,
[data-theme="dark"] .contact-card,
[data-theme="dark"] .history-card,
[data-theme="dark"] .card,
[data-theme="dark"] .contact-panel {
    background: #172331 !important;
    border-color: var(--border) !important;
    color: var(--text) !important;
    box-shadow: var(--shadow-soft) !important;
}
[data-theme="dark"] .service-card--accent {
    background: linear-gradient(180deg, #1d2d3d, #172331) !important;
}
[data-theme="dark"] .intro-panel__content h2,
[data-theme="dark"] .contact-panel__info h2,
[data-theme="dark"] .section-title,
[data-theme="dark"] .about h2,
[data-theme="dark"] .service-card h3,
[data-theme="dark"] .announcement-card h3,
[data-theme="dark"] .news-content h3,
[data-theme="dark"] .highlight-card h3,
[data-theme="dark"] .contact-card h3,
[data-theme="dark"] .history-card h3,
[data-theme="dark"] .card h2,
[data-theme="dark"] .contact-panel__info h2,
[data-theme="dark"] .hero__content h1 {
    color: #f3f7ff !important;
}
[data-theme="dark"] .intro-panel__content p,
[data-theme="dark"] .contact-panel__info p,
[data-theme="dark"] .section-heading p,
[data-theme="dark"] .about p,
[data-theme="dark"] .service-card p,
[data-theme="dark"] .highlight-card p,
[data-theme="dark"] .contact-card p,
[data-theme="dark"] .announcement-card p,
[data-theme="dark"] .news-content p,
[data-theme="dark"] .history-card p,
[data-theme="dark"] .hero__lead,
[data-theme="dark"] .stat-chip span,
[data-theme="dark"] .mission .card p {
    color: #d1d9e4 !important;
}
[data-theme="dark"] .service-link,
[data-theme="dark"] .read-more,
[data-theme="dark"] .news-content a,
[data-theme="dark"] .announcement-card a {
    color: #9eeaf0 !important;
}
[data-theme="dark"] .service-card__icon,
[data-theme="dark"] .stat-chip,
[data-theme="dark"] .hero__stats .stat-chip,
[data-theme="dark"] .nav-avatar {
    background: rgba(255, 255, 255, 0.08) !important;
    border-color: rgba(255, 255, 255, 0.12) !important;
}
[data-theme="dark"] .stat-chip strong {
    color: #ffffff !important;
}
[data-theme="dark"] .modal-content {
    background: #172331 !important;
    color: var(--text) !important;
    border: 1px solid var(--border) !important;
}
[data-theme="dark"] .modal-content p,
[data-theme="dark"] .modal-content h2 {
    color: var(--text) !important;
}
[data-theme="dark"] .auth-dropdown .dropdown-menu,
[data-theme="dark"] .dropdown__menu {
    background: #172331 !important;
    border-color: var(--border) !important;
}
[data-theme="dark"] .auth-dropdown .dropdown-menu a,
[data-theme="dark"] .dropdown__menu a {
    color: var(--text) !important;
    border-color: var(--border) !important;
}
[data-theme="dark"] .auth-dropdown .dropdown-menu a:hover,
[data-theme="dark"] .dropdown__menu a:hover {
    background: #203244 !important;
}
[data-theme="dark"] .btn-outline {
    color: var(--text) !important;
    border-color: rgba(232, 241, 245, 0.35) !important;
}
[data-theme="dark"] .btn-primary,
[data-theme="dark"] .btn-submit,
[data-theme="dark"] .btn-apply,
[data-theme="dark"] .profile-save {
    color: #fff !important;
}
[data-theme="dark"] .btn-back:not(.btn),
[data-theme="dark"] .profile-back,
[data-theme="dark"] .tab-nav a {
    color: #9eeaf0 !important;
}
[data-theme="dark"] .alert-success {
    background: rgba(74, 216, 138, 0.14) !important;
    color: #8ff0b7 !important;
    border-color: rgba(74, 216, 138, 0.28) !important;
}
[data-theme="dark"] .alert-error,
[data-theme="dark"] .error-popup,
[data-theme="dark"] .error {
    background: rgba(216, 74, 74, 0.16) !important;
    color: #ffb4b4 !important;
    border-color: rgba(216, 74, 74, 0.3) !important;
}
[data-theme="dark"] .status-pending {
    background: rgba(245, 183, 72, 0.18) !important;
    color: #ffd27a !important;
}
[data-theme="dark"] .status-approved {
    background: rgba(74, 216, 138, 0.16) !important;
    color: #8ff0b7 !important;
}
[data-theme="dark"] .status-declined {
    background: rgba(216, 74, 74, 0.16) !important;
    color: #ff9b9b !important;
}
[data-theme="dark"] .notification-menu,
[data-theme="dark"] .notification-header,
[data-theme="dark"] .notification-list,
[data-theme="dark"] .notification-footer,
[data-theme="dark"] .notification-item {
    background: #172331 !important;
    color: var(--text) !important;
    border-color: var(--border) !important;
}
[data-theme="dark"] .notification-header {
    background: linear-gradient(180deg, #1d2d3d, #172331) !important;
}
[data-theme="dark"] .notification-item:hover,
[data-theme="dark"] .notification-item.unread {
    background: #203244 !important;
}
[data-theme="dark"] .notification-title,
[data-theme="dark"] .notification-title-wrap h4 {
    color: var(--text) !important;
}
[data-theme="dark"] .notification-message,
[data-theme="dark"] .notification-subtitle,
[data-theme="dark"] .notification-time,
[data-theme="dark"] .notification-empty,
[data-theme="dark"] .notification-loading {
    color: var(--text-soft) !important;
}
[data-theme="dark"] .notification-mark-read,
[data-theme="dark"] .notification-view-all,
[data-theme="dark"] .notification-footer a {
    color: #74d4de !important;
}
[data-theme="dark"] .notification-icon.application,
[data-theme="dark"] .notif-icon.application {
    background: rgba(116, 212, 222, 0.16) !important;
    color: #9eeaf0 !important;
}
[data-theme="dark"] .notification-icon.approved,
[data-theme="dark"] .notif-icon.approved {
    background: rgba(74, 216, 138, 0.16) !important;
    color: #8ff0b7 !important;
}
[data-theme="dark"] .notification-icon.declined,
[data-theme="dark"] .notif-icon.declined {
    background: rgba(216, 74, 74, 0.16) !important;
    color: #ff9b9b !important;
}
[data-theme="dark"] .notification-icon.announcement,
[data-theme="dark"] .notification-icon.general,
[data-theme="dark"] .notification-icon.info,
[data-theme="dark"] .notification-icon.solo_parent,
[data-theme="dark"] .notif-icon.announcement,
[data-theme="dark"] .notif-icon.general,
[data-theme="dark"] .notif-icon.info,
[data-theme="dark"] .notif-icon.solo_parent {
    background: rgba(255, 210, 122, 0.16) !important;
    color: #ffd27a !important;
}
[data-theme="dark"] .notification-pill,
[data-theme="dark"] .notif-pill {
    background: rgba(116, 212, 222, 0.14) !important;
    color: #9eeaf0 !important;
}
[data-theme="dark"] a:not(.btn):not(.dropdown-item) {
    color: inherit;
}
[data-theme="dark"] .solo-parent-page,
[data-theme="dark"] .aics-page {
    color: var(--text) !important;
}
[data-theme="dark"] .solo-parent-header h1,
[data-theme="dark"] .aics-header h1,
[data-theme="dark"] .solo-parent-content h2,
[data-theme="dark"] .aics-content h2,
[data-theme="dark"] .requirements-box h3,
[data-theme="dark"] .aics-content h3,
[data-theme="dark"] .apply-section h3,
[data-theme="dark"] .login-warning h3,
[data-theme="dark"] .solo-parent-content h3,
[data-theme="dark"] .aics-header p,
[data-theme="dark"] .solo-parent-header p {
    color: #f4f8ff !important;
}
[data-theme="dark"] .solo-parent-content,
[data-theme="dark"] .aics-content,
[data-theme="dark"] .requirements-box,
[data-theme="dark"] .apply-section,
[data-theme="dark"] .login-warning,
[data-theme="dark"] .solo-parent-page table,
[data-theme="dark"] .solo-parent-content table,
[data-theme="dark"] .aics-content table {
    background: #172331 !important;
    border-color: var(--border) !important;
    color: var(--text) !important;
    box-shadow: var(--shadow-soft) !important;
}
[data-theme="dark"] .solo-parent-content p,
[data-theme="dark"] .aics-content p,
[data-theme="dark"] .solo-parent-content li,
[data-theme="dark"] .aics-content li,
[data-theme="dark"] .requirements-box li,
[data-theme="dark"] .apply-section p,
[data-theme="dark"] .login-warning p,
[data-theme="dark"] .aics-content ul,
[data-theme="dark"] .solo-parent-content ul,
[data-theme="dark"] .solo-parent-content td,
[data-theme="dark"] .aics-content td,
[data-theme="dark"] .solo-parent-content th,
[data-theme="dark"] .aics-content th {
    color: #dfeaf7 !important;
}
[data-theme="dark"] .requirements-box {
    background: rgba(124, 169, 255, 0.08) !important;
    border-left-color: #7ca9ff !important;
}
[data-theme="dark"] .alert-success {
    background: rgba(74, 216, 138, 0.18) !important;
    color: #a8f0c4 !important;
    border-color: rgba(74, 216, 138, 0.3) !important;
}
[data-theme="dark"] .alert-error {
    background: rgba(216, 74, 74, 0.18) !important;
    color: #ffb8b8 !important;
    border-color: rgba(216, 74, 74, 0.28) !important;
}
[data-theme="dark"] .btn-apply,
[data-theme="dark"] .apply-section .btn-apply,
[data-theme="dark"] .solo-parent-content .btn-apply {
    background: #fff !important;
    color: #0f172a !important;
    border: 1px solid rgba(255, 255, 255, 0.24) !important;
}
[data-theme="dark"] .apply-section {
    background: linear-gradient(135deg, #12273d, #1e3a5f) !important;
    color: #f1f7ff !important;
}
[data-theme="dark"] .apply-section p,
[data-theme="dark"] .cooldown-note {
    color: rgba(255, 255, 255, 0.9) !important;
}
[data-theme="dark"] .back-link {
    color: #9eeaf0 !important;
}
[data-theme="dark"] .back-link:hover {
    color: #d7fbff !important;
}
[data-theme="dark"] th {
    background: #1f3a5a !important;
    color: #eaf5ff !important;
}
[data-theme="dark"] td {
    background: #172331 !important;
    border-bottom: 1px solid rgba(206, 221, 238, 0.15) !important;
}
[data-theme="dark"] tr:hover td {
    background: #1d2c3f !important;
}
@media (max-width: 720px) {
    .site-theme-toggle {
        right: 14px;
        bottom: 14px;
        width: 42px;
        height: 42px;
    }
}
</style>
HTML;

    $button = <<<'HTML'
<button class="site-theme-toggle" type="button" aria-label="Switch to dark mode" title="Toggle dark mode" data-site-theme-toggle>
    <svg class="site-theme-toggle__sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <circle cx="12" cy="12" r="4"></circle>
        <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"></path>
    </svg>
    <svg class="site-theme-toggle__moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <path d="M20.99 12.79A9 9 0 1 1 11.21 3.01 7 7 0 0 0 20.99 12.79z"></path>
    </svg>
</button>
<script>
(function () {
    const root = document.documentElement;
    const toggle = document.querySelector('[data-site-theme-toggle]');
    if (!toggle) return;

    const navTarget = document.querySelector('.nav-notifications') || document.querySelector('.menu__auth');
    if (navTarget) {
        navTarget.appendChild(toggle);
        toggle.classList.add('site-theme-toggle--in-nav');
    }

    function setTheme(theme) {
        const isDark = theme === 'dark';
        if (isDark) {
            root.setAttribute('data-theme', 'dark');
        } else {
            root.removeAttribute('data-theme');
        }
        toggle.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
        toggle.title = isDark ? 'Switch to light mode' : 'Switch to dark mode';
        try {
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        } catch (error) {}
    }

    setTheme(root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light');
    toggle.addEventListener('click', function () {
        setTheme(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
    });
})();
</script>
HTML;

    $html = preg_replace('/<\/head>/i', $headScript . $style . '</head>', $html, 1) ?? $html;
    return preg_replace('/<\/body>/i', $button . '</body>', $html, 1) ?? $html;
}

if (shouldInjectSiteThemeAssets()) {
    ob_start('injectSiteThemeAssets');
}

if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['user_id'])) {
    $currentTime = time();
    $sessionTimeoutSeconds = 3600; // EDIT THIS VALUE to change the auto-logout timeout in seconds.
    $skipInactivityTimeout = false;

    if (isset($_SESSION['user_id'])) {
        $adminCheckStmt = $conn->prepare("SELECT COALESCE(is_admin, 0) AS is_admin FROM users WHERE id = ?");
        if ($adminCheckStmt) {
            $adminCheckStmt->bind_param("i", $_SESSION['user_id']);
            $adminCheckStmt->execute();
            $adminRow = $adminCheckStmt->get_result()->fetch_assoc();
            $skipInactivityTimeout = !empty($adminRow['is_admin']);
            $adminCheckStmt->close();
        }
    }

    if (!$skipInactivityTimeout) {
        if (isset($_SESSION['last_activity']) && ($currentTime - (int) $_SESSION['last_activity']) > $sessionTimeoutSeconds) {
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
            }
            session_unset();
            session_destroy();
            header('Location: ' . getLoginRedirectPath());
            exit;
        }

        $_SESSION['last_activity'] = $currentTime;

        $contentType = '';
        foreach (headers_list() as $headerLine) {
            if (stripos($headerLine, 'Content-Type:') === 0) {
                $contentType = trim(substr($headerLine, strlen('Content-Type:')));
                break;
            }
        }

        $requestMethod = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        if ($requestMethod === 'GET' && ($contentType === '' || stripos($contentType, 'text/html') !== false)) {
            ob_start(function (string $html) use ($sessionTimeoutSeconds): string {
                return injectInactivityLogoutScript($html, $sessionTimeoutSeconds, getLogoutPath(), getLoginRedirectPath());
            });
        }
    }
}

// Ensure necessary columns exist - using a safer approach
$columns_to_add = [
    "is_admin" => "ALTER TABLE users ADD COLUMN is_admin TINYINT(1) NOT NULL DEFAULT 0",
    "otp" => "ALTER TABLE users ADD COLUMN otp VARCHAR(6) NULL",
    "otp_expiry" => "ALTER TABLE users ADD COLUMN otp_expiry DATETIME NULL",
    "profile_image" => "ALTER TABLE users ADD COLUMN profile_image VARCHAR(255) DEFAULT NULL",
    "reset_code" => "ALTER TABLE users ADD COLUMN reset_code VARCHAR(6) NULL",
    "reset_code_expiry" => "ALTER TABLE users ADD COLUMN reset_code_expiry DATETIME NULL",
    "aics_cooldown_until" => "ALTER TABLE users ADD COLUMN aics_cooldown_until DATETIME NULL",
    "failed_login_attempts" => "ALTER TABLE users ADD COLUMN failed_login_attempts INT(11) NOT NULL DEFAULT 0",
    "locked_until" => "ALTER TABLE users ADD COLUMN locked_until DATETIME NULL"
];

foreach ($columns_to_add as $column => $sql) {
    $columnExists = $conn->query("SHOW COLUMNS FROM users LIKE '{$column}'");
    if ($columnExists && $columnExists->num_rows == 0) {
        $conn->query($sql);
    }
}

// Create notifications table
$notifTableExists = $conn->query("SHOW TABLES LIKE 'notifications'");
if ($notifTableExists->num_rows == 0) {
    $conn->query("CREATE TABLE notifications (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) NOT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        type VARCHAR(50) DEFAULT 'info',
        target_url VARCHAR(255) DEFAULT NULL,
        is_read TINYINT(1) NOT NULL DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
} else {
    $targetUrlCheck = $conn->query("SHOW COLUMNS FROM notifications LIKE 'target_url'");
    if ($targetUrlCheck && $targetUrlCheck->num_rows == 0) {
        $conn->query("ALTER TABLE notifications ADD COLUMN target_url VARCHAR(255) DEFAULT NULL AFTER type");
    }
}

// Ensure aics_applications table exists with proper structure
$tableExists = $conn->query("SHOW TABLES LIKE 'aics_applications'");
if ($tableExists->num_rows == 0) {
    $conn->query("CREATE TABLE aics_applications (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) NOT NULL,
        assistance_type VARCHAR(50) DEFAULT NULL,
        assistance_subtype VARCHAR(100) DEFAULT NULL,
        financial_assistance_type VARCHAR(50) DEFAULT NULL,
        cash_amount DECIMAL(10,2) DEFAULT NULL,
        status VARCHAR(20) DEFAULT 'pending',
        ticket_number VARCHAR(50) DEFAULT NULL,
        description_of_need TEXT DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY (ticket_number),
        KEY user_id (user_id),
        CONSTRAINT fk_aics_applications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
} else {
    $check = $conn->query("SHOW COLUMNS FROM aics_applications LIKE 'admin_notes'");
    if ($check && $check->num_rows == 0) {
        $conn->query("ALTER TABLE aics_applications ADD COLUMN admin_notes TEXT DEFAULT NULL");
    }

    $aicsColumns = [
        'schedule_date' => "ALTER TABLE aics_applications ADD COLUMN schedule_date DATE DEFAULT NULL",
        'schedule_time' => "ALTER TABLE aics_applications ADD COLUMN schedule_time TIME DEFAULT NULL",
        'schedule_location' => "ALTER TABLE aics_applications ADD COLUMN schedule_location VARCHAR(255) DEFAULT NULL"
    ];
    foreach ($aicsColumns as $col => $sql) {
        $colExists = $conn->query("SHOW COLUMNS FROM aics_applications LIKE '$col'");
        if ($colExists && $colExists->num_rows == 0) {
            $conn->query($sql);
        }
    }
}

// Ensure aics_applicants table exists
$applicantTableExists = $conn->query("SHOW TABLES LIKE 'aics_applicants'");
if ($applicantTableExists->num_rows == 0) {
    $conn->query("CREATE TABLE aics_applicants (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        application_id INT(11) DEFAULT NULL,
        last_name VARCHAR(50) DEFAULT NULL,
        first_name VARCHAR(50) DEFAULT NULL,
        middle_name VARCHAR(50) DEFAULT NULL,
        phone VARCHAR(20) DEFAULT NULL,
        birthdate DATE DEFAULT NULL,
        age INT(3) DEFAULT NULL,
        gender VARCHAR(20) DEFAULT NULL,
        civil_status VARCHAR(30) DEFAULT NULL,
        occupation VARCHAR(100) DEFAULT NULL,
        monthly_salary DECIMAL(10,2) DEFAULT NULL,
        KEY application_id (application_id),
        CONSTRAINT fk_aics_applicants_application FOREIGN KEY (application_id) REFERENCES aics_applications(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
} else {
    $applicantColumns = [
        'age' => "ALTER TABLE aics_applicants ADD COLUMN age INT(3) DEFAULT NULL AFTER birthdate"
    ];
    foreach ($applicantColumns as $col => $sql) {
        $colExists = $conn->query("SHOW COLUMNS FROM aics_applicants LIKE '$col'");
        if ($colExists && $colExists->num_rows == 0) {
            $conn->query($sql);
        }
    }
}

// Ensure aics_addresses table exists
$addressTableExists = $conn->query("SHOW TABLES LIKE 'aics_addresses'");
if ($addressTableExists->num_rows == 0) {
    $conn->query("CREATE TABLE aics_addresses (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        application_id INT(11) DEFAULT NULL,
        house_no VARCHAR(100) DEFAULT NULL,
        barangay VARCHAR(100) DEFAULT NULL,
        city VARCHAR(100) DEFAULT NULL,
        province VARCHAR(100) DEFAULT NULL,
        region VARCHAR(50) DEFAULT NULL,
        KEY application_id (application_id),
        CONSTRAINT fk_aics_addresses_application FOREIGN KEY (application_id) REFERENCES aics_applications(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
}

// Ensure aics_representatives table exists
// Ensure aics_representatives table exists with all needed columns
$repTableExists = $conn->query("SHOW TABLES LIKE 'aics_representatives'");
if ($repTableExists->num_rows == 0) {
    $conn->query("CREATE TABLE aics_representatives (
        id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        application_id INT(11) DEFAULT NULL,
        last_name VARCHAR(50) DEFAULT NULL,
        first_name VARCHAR(50) DEFAULT NULL,
        middle_name VARCHAR(50) DEFAULT NULL,
        name_ext VARCHAR(20) DEFAULT NULL,
        house_no VARCHAR(100) DEFAULT NULL,
        barangay VARCHAR(100) DEFAULT NULL,
        city VARCHAR(100) DEFAULT NULL,
        province VARCHAR(100) DEFAULT NULL,
        region VARCHAR(50) DEFAULT NULL,
        birthdate DATE DEFAULT NULL,
        age INT(3) DEFAULT NULL,
        gender VARCHAR(20) DEFAULT NULL,
        civil_status VARCHAR(30) DEFAULT NULL,
        relationship VARCHAR(100) DEFAULT NULL,
        phone VARCHAR(20) DEFAULT NULL,
        occupation VARCHAR(100) DEFAULT NULL,
        monthly_salary DECIMAL(10,2) DEFAULT NULL,
        KEY application_id (application_id),
        CONSTRAINT fk_aics_representatives_application FOREIGN KEY (application_id) REFERENCES aics_applications(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
} else {
    // Add missing columns if not present
    $repColumns = [
        'middle_name' => "ALTER TABLE aics_representatives ADD COLUMN middle_name VARCHAR(50) DEFAULT NULL",
        'name_ext' => "ALTER TABLE aics_representatives ADD COLUMN name_ext VARCHAR(20) DEFAULT NULL",
        'house_no' => "ALTER TABLE aics_representatives ADD COLUMN house_no VARCHAR(100) DEFAULT NULL",
        'barangay' => "ALTER TABLE aics_representatives ADD COLUMN barangay VARCHAR(100) DEFAULT NULL",
        'city' => "ALTER TABLE aics_representatives ADD COLUMN city VARCHAR(100) DEFAULT NULL",
        'province' => "ALTER TABLE aics_representatives ADD COLUMN province VARCHAR(100) DEFAULT NULL",
        'region' => "ALTER TABLE aics_representatives ADD COLUMN region VARCHAR(50) DEFAULT NULL",
        'birthdate' => "ALTER TABLE aics_representatives ADD COLUMN birthdate DATE DEFAULT NULL",
        'age' => "ALTER TABLE aics_representatives ADD COLUMN age INT(3) DEFAULT NULL",
        'gender' => "ALTER TABLE aics_representatives ADD COLUMN gender VARCHAR(20) DEFAULT NULL",
        'civil_status' => "ALTER TABLE aics_representatives ADD COLUMN civil_status VARCHAR(30) DEFAULT NULL",
        'monthly_salary' => "ALTER TABLE aics_representatives ADD COLUMN monthly_salary DECIMAL(10,2) DEFAULT NULL"
    ];
    foreach ($repColumns as $col => $sql) {
        $colExists = $conn->query("SHOW COLUMNS FROM aics_representatives LIKE '$col'");
        if ($colExists->num_rows == 0) {
            $conn->query($sql);
        }
    }
}

// Ensure solo_parent main application table exists
$soloTableExists = $conn->query("SHOW TABLES LIKE 'solo_parent_applications'");
if ($soloTableExists->num_rows == 0) {
    $conn->query("CREATE TABLE solo_parent_applications (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            user_id INT(11) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            first_name VARCHAR(50) NOT NULL,
            middle_name VARCHAR(50) DEFAULT '',
            name_extension VARCHAR(20) DEFAULT '',
            house_no VARCHAR(100) DEFAULT '',
            barangay VARCHAR(100) DEFAULT '',
            city_municipality VARCHAR(100) DEFAULT '',
            province VARCHAR(100) DEFAULT '',
            region VARCHAR(50) DEFAULT '',
            phone VARCHAR(20) DEFAULT '',
            email TEXT,
            birthdate DATE DEFAULT NULL,
            place_of_birth TEXT,
            age INT(3) DEFAULT 0,
            gender VARCHAR(20) DEFAULT '',
            civil_status VARCHAR(30) DEFAULT '',
            education TEXT,
            religion TEXT,
            occupation TEXT,
            monthly_income DECIMAL(10,2) DEFAULT 0.00,
            employer_address TEXT,
            family_income DECIMAL(10,2) DEFAULT 0.00,
            spouse_name TEXT,
            spouse_address TEXT,
            housing_status TEXT,
            emergency_contact_name TEXT,
            emergency_contact_address TEXT,
            emergency_contact_number VARCHAR(20) DEFAULT '',
            member_4ps TINYINT(1) DEFAULT 0,
            member_lgbtq TINYINT(1) DEFAULT 0,
            philhealth_member TINYINT(1) DEFAULT 0,
            philhealth_number TEXT,
            philhealth_membership_category TEXT,
            philhealth_dependent TINYINT(1) DEFAULT 0,
            philhealth_dependent_name TEXT,
            philhealth_dependent_number TEXT,
            philhealth_relationship TEXT,
            family_members LONGTEXT,
            program_info TEXT,
            solo_parent_classification TEXT,
            solo_parent_needs TEXT,
            family_resources TEXT,
            description_of_need TEXT,
            certification_date DATE DEFAULT NULL,
            applicant_signature TEXT,
            status VARCHAR(20) DEFAULT 'pending',
            ticket_number VARCHAR(50) DEFAULT NULL,
            admin_notes TEXT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            KEY user_id (user_id),
            CONSTRAINT fk_solo_parent_applications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
} else {
    $soloTextColumns = [
        "email" => "ALTER TABLE solo_parent_applications MODIFY COLUMN email TEXT NULL",
        "place_of_birth" => "ALTER TABLE solo_parent_applications MODIFY COLUMN place_of_birth TEXT NULL",
        "education" => "ALTER TABLE solo_parent_applications MODIFY COLUMN education TEXT NULL",
        "religion" => "ALTER TABLE solo_parent_applications MODIFY COLUMN religion TEXT NULL",
        "occupation" => "ALTER TABLE solo_parent_applications MODIFY COLUMN occupation TEXT NULL",
        "spouse_name" => "ALTER TABLE solo_parent_applications MODIFY COLUMN spouse_name TEXT NULL",
        "housing_status" => "ALTER TABLE solo_parent_applications MODIFY COLUMN housing_status TEXT NULL",
        "emergency_contact_name" => "ALTER TABLE solo_parent_applications MODIFY COLUMN emergency_contact_name TEXT NULL",
        "philhealth_number" => "ALTER TABLE solo_parent_applications MODIFY COLUMN philhealth_number TEXT NULL",
        "philhealth_membership_category" => "ALTER TABLE solo_parent_applications MODIFY COLUMN philhealth_membership_category TEXT NULL",
        "philhealth_dependent_name" => "ALTER TABLE solo_parent_applications MODIFY COLUMN philhealth_dependent_name TEXT NULL",
        "philhealth_dependent_number" => "ALTER TABLE solo_parent_applications MODIFY COLUMN philhealth_dependent_number TEXT NULL",
        "philhealth_relationship" => "ALTER TABLE solo_parent_applications MODIFY COLUMN philhealth_relationship TEXT NULL",
        "applicant_signature" => "ALTER TABLE solo_parent_applications MODIFY COLUMN applicant_signature TEXT NULL"
    ];
    foreach ($soloTextColumns as $name => $sql) {
        $columnCheck = $conn->query("SHOW COLUMNS FROM solo_parent_applications LIKE '$name'");
        if ($columnCheck && $columnCheck->num_rows > 0) {
            $column = $columnCheck->fetch_assoc();
            $type = strtolower($column['Type'] ?? '');
            if (strpos($type, 'text') === false) {
                $conn->query($sql);
            }
        }
    }

    $soloColumns = [
        "status" => "ALTER TABLE solo_parent_applications ADD COLUMN status VARCHAR(20) DEFAULT 'pending'",
        "ticket_number" => "ALTER TABLE solo_parent_applications ADD COLUMN ticket_number VARCHAR(50) DEFAULT NULL",
        "admin_notes" => "ALTER TABLE solo_parent_applications ADD COLUMN admin_notes TEXT",
        "certification_date" => "ALTER TABLE solo_parent_applications ADD COLUMN certification_date DATE DEFAULT NULL",
        "applicant_signature" => "ALTER TABLE solo_parent_applications ADD COLUMN applicant_signature TEXT NULL",
        "updated_at" => "ALTER TABLE solo_parent_applications ADD COLUMN updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP",
        "member_4ps" => "ALTER TABLE solo_parent_applications ADD COLUMN member_4ps TINYINT(1) DEFAULT 0",
        "member_lgbtq" => "ALTER TABLE solo_parent_applications ADD COLUMN member_lgbtq TINYINT(1) DEFAULT 0"
    ];
    foreach ($soloColumns as $name => $sql) {
        $check = $conn->query("SHOW COLUMNS FROM solo_parent_applications LIKE '$name'");
        if ($check && $check->num_rows == 0) {
            $conn->query($sql);
        }
    }
}

// Personal info table
$personalTableExists = $conn->query("SHOW TABLES LIKE 'solo_parent_personal'");
if ($personalTableExists->num_rows == 0) {
    $conn->query("CREATE TABLE solo_parent_personal (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            application_id INT(11) NOT NULL,
            last_name VARCHAR(50) DEFAULT NULL,
            first_name VARCHAR(50) DEFAULT NULL,
            middle_name VARCHAR(50) DEFAULT NULL,
            name_extension VARCHAR(20) DEFAULT NULL,
            birthdate DATE DEFAULT NULL,
            place_of_birth VARCHAR(150) DEFAULT NULL,
            age INT(3) DEFAULT NULL,
            gender VARCHAR(20) DEFAULT NULL,
            civil_status VARCHAR(30) DEFAULT NULL,
            education VARCHAR(100) DEFAULT NULL,
            religion VARCHAR(100) DEFAULT NULL,
            occupation VARCHAR(100) DEFAULT NULL,
            monthly_income DECIMAL(10,2) DEFAULT 0.00,
            employer_address TEXT,
            email VARCHAR(100) DEFAULT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            CONSTRAINT fk_solo_parent_personal_app FOREIGN KEY (application_id) REFERENCES solo_parent_applications(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");
}

// Address table
$addressTableExists = $conn->query("SHOW TABLES LIKE 'solo_parent_address'");
if ($addressTableExists->num_rows == 0) {
    $conn->query("CREATE TABLE solo_parent_address (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            application_id INT(11) NOT NULL,
            house_no VARCHAR(100) DEFAULT NULL,
            barangay VARCHAR(100) DEFAULT NULL,
            city_municipality VARCHAR(100) DEFAULT NULL,
            province VARCHAR(100) DEFAULT NULL,
            region VARCHAR(50) DEFAULT NULL,
            CONSTRAINT fk_solo_parent_address_app FOREIGN KEY (application_id) REFERENCES solo_parent_applications(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");
}

// Family members table
$familyTableExists = $conn->query("SHOW TABLES LIKE 'solo_parent_family_members'");
if ($familyTableExists->num_rows == 0) {
    $conn->query("CREATE TABLE solo_parent_family_members (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            application_id INT(11) NOT NULL,
            name VARCHAR(100) DEFAULT NULL,
            birthdate DATE DEFAULT NULL,
            relationship VARCHAR(50) DEFAULT NULL,
            age INT(3) DEFAULT NULL,
            school VARCHAR(100) DEFAULT NULL,
            educational_attainment VARCHAR(150) DEFAULT NULL,
            occupation VARCHAR(150) DEFAULT NULL,
            monthly_income DECIMAL(10,2) DEFAULT 0.00,
            CONSTRAINT fk_solo_parent_family_app FOREIGN KEY (application_id) REFERENCES solo_parent_applications(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");
} else {
    $familyColumns = [
        "birthdate" => "ALTER TABLE solo_parent_family_members ADD COLUMN birthdate DATE DEFAULT NULL",
        "educational_attainment" => "ALTER TABLE solo_parent_family_members ADD COLUMN educational_attainment VARCHAR(150) DEFAULT NULL",
        "occupation" => "ALTER TABLE solo_parent_family_members ADD COLUMN occupation VARCHAR(150) DEFAULT NULL",
        "monthly_income" => "ALTER TABLE solo_parent_family_members ADD COLUMN monthly_income DECIMAL(10,2) DEFAULT 0.00"
    ];
    foreach ($familyColumns as $name => $sql) {
        $check = $conn->query("SHOW COLUMNS FROM solo_parent_family_members LIKE '$name'");
        if ($check && $check->num_rows == 0) {
            $conn->query($sql);
        }
    }
}

// Membership / programs table
$membershipTableExists = $conn->query("SHOW TABLES LIKE 'solo_parent_membership'");
if ($membershipTableExists->num_rows == 0) {
    $conn->query("CREATE TABLE solo_parent_membership (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            application_id INT(11) NOT NULL,
            member_4ps TINYINT(1) DEFAULT 0,
            member_lgbtq TINYINT(1) DEFAULT 0,
            philhealth_member TINYINT(1) DEFAULT 0,
            philhealth_number VARCHAR(50) DEFAULT NULL,
            philhealth_membership_category VARCHAR(100) DEFAULT NULL,
            philhealth_dependent TINYINT(1) DEFAULT 0,
            philhealth_dependent_name VARCHAR(150) DEFAULT NULL,
            philhealth_dependent_number VARCHAR(50) DEFAULT NULL,
            philhealth_relationship VARCHAR(50) DEFAULT NULL,
            CONSTRAINT fk_solo_parent_membership_app FOREIGN KEY (application_id) REFERENCES solo_parent_applications(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");
}

// Programs table (optional if multiple programs)
$programTableExists = $conn->query("SHOW TABLES LIKE 'solo_parent_programs'");
if ($programTableExists->num_rows == 0) {
    $conn->query("CREATE TABLE solo_parent_programs (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            application_id INT(11) NOT NULL,
            program_name VARCHAR(100) DEFAULT NULL,
            CONSTRAINT fk_solo_parent_program_app FOREIGN KEY (application_id) REFERENCES solo_parent_applications(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");
}

// Emergency contact table
$emergencyTableExists = $conn->query("SHOW TABLES LIKE 'solo_parent_emergency_contact'");
if ($emergencyTableExists->num_rows == 0) {
    $conn->query("CREATE TABLE solo_parent_emergency_contact (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            application_id INT(11) NOT NULL,
            name VARCHAR(150) DEFAULT NULL,
            address TEXT DEFAULT NULL,
            contact_number VARCHAR(20) DEFAULT NULL,
            CONSTRAINT fk_solo_parent_emergency_app FOREIGN KEY (application_id) REFERENCES solo_parent_applications(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");
}

// Spouse info table
$spouseTableExists = $conn->query("SHOW TABLES LIKE 'solo_parent_spouse'");
if ($spouseTableExists->num_rows == 0) {
    $conn->query("CREATE TABLE solo_parent_spouse (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            application_id INT(11) NOT NULL,
            spouse_name VARCHAR(150) DEFAULT NULL,
            spouse_address TEXT DEFAULT NULL,
            CONSTRAINT fk_solo_parent_spouse_app FOREIGN KEY (application_id) REFERENCES solo_parent_applications(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");
}

// Additional application info table
$additionalInfoTableExists = $conn->query("SHOW TABLES LIKE 'solo_parent_additional_info'");
if ($additionalInfoTableExists->num_rows == 0) {
    $conn->query("CREATE TABLE solo_parent_additional_info (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            application_id INT(11) NOT NULL,
            housing_status VARCHAR(50) DEFAULT NULL,
            family_income DECIMAL(10,2) DEFAULT 0.00,
            solo_parent_classification TEXT DEFAULT NULL,
            solo_parent_needs TEXT DEFAULT NULL,
            family_resources TEXT DEFAULT NULL,
            hear_about_program TEXT DEFAULT NULL,
            skills_hobbies TEXT DEFAULT NULL,
            solo_parent_id_purpose TEXT DEFAULT NULL,
            CONSTRAINT fk_solo_parent_additional_info_app FOREIGN KEY (application_id) REFERENCES solo_parent_applications(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
    ");
} else {
    $additionalColumns = [
        "hear_about_program" => "ALTER TABLE solo_parent_additional_info ADD COLUMN hear_about_program TEXT DEFAULT NULL",
        "skills_hobbies" => "ALTER TABLE solo_parent_additional_info ADD COLUMN skills_hobbies TEXT DEFAULT NULL",
        "solo_parent_id_purpose" => "ALTER TABLE solo_parent_additional_info ADD COLUMN solo_parent_id_purpose TEXT DEFAULT NULL"
    ];
    foreach ($additionalColumns as $name => $sql) {
        $check = $conn->query("SHOW COLUMNS FROM solo_parent_additional_info LIKE '$name'");
        if ($check && $check->num_rows == 0) {
            $conn->query($sql);
        }
    }
}
?>

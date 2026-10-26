<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth_helpers.php';

if (empty($_SESSION['admin_action_token'])) {
    $_SESSION['admin_action_token'] = bin2hex(random_bytes(32));
}
$contentAdminToken = getCsrfToken('content_admin_token');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check admin/staff status
$stmt = $conn->prepare('SELECT is_admin, is_staff FROM users WHERE id = ?');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || (!$user['is_admin'] && !$user['is_staff'])) {
    echo '<p>Access denied. Admin or Staff privileges required.</p>';
    exit;
}

$isAdmin = (bool)$user['is_admin'];
$isStaff = (bool)$user['is_staff'];

// ====================== AUDIT TRAIL SYSTEM ======================
function logAudit($conn, $user_id, $action, $details = '', $ip_address = '') {
    $stmt = $conn->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isss", $user_id, $action, $details, $ip_address);
    $stmt->execute();
    $stmt->close();
}

// Create audit table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at DATETIME NOT NULL,
    INDEX (user_id),
    INDEX (action),
    INDEX (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_aics_application') {
    $applicationId = filter_var($_POST['application_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!$isAdmin) {
        header('Location: admin_panel.php?tab=aics&error=' . urlencode('Only administrators can delete AICS applications.'));
        exit;
    }

    if ($applicationId === false || !is_string($csrfToken) || !hash_equals($_SESSION['admin_action_token'], $csrfToken)) {
        header('Location: admin_panel.php?tab=aics&error=' . urlencode('Invalid delete request. Please try again.'));
        exit;
    }

    try {
        $conn->begin_transaction();
        $deleteStmt = $conn->prepare('DELETE FROM aics_applications WHERE id = ?');
        $deleteStmt->bind_param('i', $applicationId);
        $deleteStmt->execute();

        if ($deleteStmt->affected_rows !== 1) {
            $deleteStmt->close();
            $conn->rollback();
            header('Location: admin_panel.php?tab=aics&error=' . urlencode('AICS application not found.'));
            exit;
        }

        $deleteStmt->close();
        logAudit($conn, $_SESSION['user_id'], 'AICS_APPLICATION_DELETED', 'Application ID: ' . $applicationId, $_SERVER['REMOTE_ADDR'] ?? '');
        $conn->commit();
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        error_log('AICS application deletion failed: ' . $exception->getMessage());
        header('Location: admin_panel.php?tab=aics&error=' . urlencode('Unable to delete the AICS application. Please try again.'));
        exit;
    }

    header('Location: admin_panel.php?tab=aics&success=' . urlencode('AICS application deleted successfully.'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_solo_parent_application') {
    $applicationId = filter_var($_POST['application_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!$isAdmin) {
        header('Location: admin_panel.php?tab=solo_parent&error=' . urlencode('Only administrators can delete Solo Parent applications.'));
        exit;
    }

    if ($applicationId === false || !is_string($csrfToken) || !hash_equals($_SESSION['admin_action_token'], $csrfToken)) {
        header('Location: admin_panel.php?tab=solo_parent&error=' . urlencode('Invalid delete request. Please try again.'));
        exit;
    }

    try {
        $conn->begin_transaction();
        $deleteStmt = $conn->prepare('DELETE FROM solo_parent_applications WHERE id = ?');
        $deleteStmt->bind_param('i', $applicationId);
        $deleteStmt->execute();

        if ($deleteStmt->affected_rows !== 1) {
            $deleteStmt->close();
            $conn->rollback();
            header('Location: admin_panel.php?tab=solo_parent&error=' . urlencode('Solo Parent application not found.'));
            exit;
        }

        $deleteStmt->close();
        logAudit($conn, $_SESSION['user_id'], 'SOLO_PARENT_APPLICATION_DELETED', 'Application ID: ' . $applicationId, $_SERVER['REMOTE_ADDR'] ?? '');
        $conn->commit();
    } catch (mysqli_sql_exception $exception) {
        $conn->rollback();
        error_log('Solo Parent application deletion failed: ' . $exception->getMessage());
        header('Location: admin_panel.php?tab=solo_parent&error=' . urlencode('Unable to delete the Solo Parent application. Please try again.'));
        exit;
    }

    header('Location: admin_panel.php?tab=solo_parent&success=' . urlencode('Solo Parent application deleted successfully.'));
    exit;
}

// Log page access
logAudit($conn, $_SESSION['user_id'], 'PAGE_ACCESS', 'Tab: ' . ($_GET['tab'] ?? 'aics'), $_SERVER['REMOTE_ADDR']);

// Fetch audit logs for admin
if ($isAdmin && ($_GET['tab'] ?? '') == 'audit_logs') {
    $auditLogs = $conn->query("SELECT al.*, u.fullname FROM audit_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT 500");
}

if ($isAdmin) {
    $announcements = $conn->query("SELECT * FROM announcements ORDER BY date_posted DESC");
    $news = $conn->query("SELECT * FROM news ORDER BY date_posted DESC");
}

$aics_applications = $conn->query("SELECT a.*, u.fullname as user_name, u.email as user_email, ap.first_name, ap.middle_name, ap.last_name, ad.barangay as barangay
    FROM aics_applications a
    LEFT JOIN users u ON a.user_id = u.id
    LEFT JOIN aics_applicants ap ON ap.application_id = a.id
    LEFT JOIN aics_addresses ad ON ad.application_id = a.id
    ORDER BY a.created_at DESC");

$solo_parent_applications = $conn->query("SELECT sp.*, u.fullname as user_name, u.email as user_email, sp_personal.first_name, sp_personal.middle_name, sp_personal.last_name, sp_personal.name_extension, sp_personal.phone as personal_phone, sp_address.house_no, sp_address.barangay, sp_address.city_municipality, sp_address.province, sp_address.region
    FROM solo_parent_applications sp
    LEFT JOIN users u ON sp.user_id = u.id
    LEFT JOIN solo_parent_personal sp_personal ON sp_personal.application_id = sp.id
    LEFT JOIN solo_parent_address sp_address ON sp_address.application_id = sp.id
    ORDER BY sp.created_at DESC
");

$adminNotifCount = 0;
$adminNotifStmt = $conn->prepare("SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0");
$adminNotifStmt->bind_param("i", $_SESSION['user_id']);
$adminNotifStmt->execute();
$adminNotifResult = $adminNotifStmt->get_result();
$adminNotifCount = $adminNotifResult->fetch_assoc()['count'] ?? 0;
$adminNotifStmt->close();

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
$tab = $_GET['tab'] ?? 'aics';
$export = $_GET['export'] ?? '';

$requestTracker = $conn->query("
    SELECT u.id, u.fullname, u.email, u.aics_cooldown_until,
        COALESCE(
            DATE_SUB(u.aics_cooldown_until, INTERVAL 2 MONTH),
            MAX(CASE WHEN LOWER(TRIM(a.status)) = 'approved' THEN a.created_at END)
        ) AS last_approved_at
    FROM users u
    LEFT JOIN aics_applications a ON a.user_id = u.id
    WHERE COALESCE(u.is_admin, 0) = 0
    GROUP BY u.id, u.fullname, u.email, u.aics_cooldown_until
    ORDER BY u.fullname ASC
");

if ($isAdmin) {
    $lockedAccounts = $conn->query("
        SELECT id, fullname, email, COALESCE(failed_login_attempts, 0) AS failed_login_attempts, locked_until
        FROM users
        WHERE COALESCE(is_admin, 0) = 0 AND locked_until IS NOT NULL
        ORDER BY locked_until ASC, fullname ASC
    ");
}

if ($isAdmin) {
    function fetchReportRows(mysqli $conn, string $sql): array {
        $result = $conn->query($sql);
        if (!$result) return [];
        $rows = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        return $rows;
    }
    function formatReportLabel(?string $value, string $fallback = 'Unspecified'): string {
        $value = trim((string) $value);
        return $value !== '' ? $value : $fallback;
    }
    function buildAgeGroupReport(array $ages): array {
        $groups = ['17 and below' => 0, '18-29' => 0, '30-44' => 0, '45-59' => 0, '60 and above' => 0, 'Unspecified' => 0];
        foreach ($ages as $ageValue) {
            $age = is_numeric($ageValue) ? (int) $ageValue : 0;
            if ($age <= 0) $groups['Unspecified']++;
            elseif ($age <= 17) $groups['17 and below']++;
            elseif ($age <= 29) $groups['18-29']++;
            elseif ($age <= 44) $groups['30-44']++;
            elseif ($age <= 59) $groups['45-59']++;
            else $groups['60 and above']++;
        }
        $rows = [];
        foreach ($groups as $label => $count) $rows[] = ['label' => $label, 'total' => $count];
        return $rows;
    }
    function streamCsvDownload(string $filename, array $headers, array $rows): void {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);
        foreach ($rows as $row) fputcsv($output, $row);
        fclose($output);
        exit;
    }

    $aicsSummary = $conn->query("SELECT COUNT(DISTINCT a.id) AS total_applications, SUM(CASE WHEN a.status = 'pending' THEN 1 ELSE 0 END) AS pending_count, SUM(CASE WHEN a.status = 'approved' THEN 1 ELSE 0 END) AS approved_count, SUM(CASE WHEN a.status = 'declined' THEN 1 ELSE 0 END) AS declined_count FROM aics_applications a")->fetch_assoc();
    $soloSummary = $conn->query("SELECT COUNT(DISTINCT sp.id) AS total_applications, SUM(CASE WHEN sp.status = 'pending' THEN 1 ELSE 0 END) AS pending_count, SUM(CASE WHEN sp.status = 'approved' THEN 1 ELSE 0 END) AS approved_count, SUM(CASE WHEN sp.status = 'declined' THEN 1 ELSE 0 END) AS declined_count FROM solo_parent_applications sp")->fetch_assoc();
    $aicsByGender = fetchReportRows($conn, "SELECT COALESCE(NULLIF(TRIM(ap.gender), ''), 'Unspecified') AS label, COUNT(DISTINCT a.id) AS total FROM aics_applications a LEFT JOIN aics_applicants ap ON ap.application_id = a.id GROUP BY label ORDER BY total DESC, label ASC");
    $aicsByBarangay = fetchReportRows($conn, "SELECT COALESCE(NULLIF(TRIM(ad.barangay), ''), 'Unspecified') AS label, COUNT(DISTINCT a.id) AS total FROM aics_applications a LEFT JOIN aics_addresses ad ON ad.application_id = a.id GROUP BY label ORDER BY total DESC, label ASC");
    $aicsByAssistanceType = fetchReportRows($conn, "SELECT COALESCE(NULLIF(TRIM(a.financial_assistance_type), ''), 'Unspecified') AS label, COUNT(DISTINCT a.id) AS total FROM aics_applications a GROUP BY label ORDER BY total DESC, label ASC");
    $aicsAgeValues = fetchReportRows($conn, "SELECT CASE WHEN ap.birthdate IS NOT NULL AND ap.birthdate <> '0000-00-00' THEN TIMESTAMPDIFF(YEAR, ap.birthdate, CURDATE()) ELSE NULL END AS age FROM aics_applications a LEFT JOIN aics_applicants ap ON ap.application_id = a.id");
    $aicsByAge = buildAgeGroupReport(array_column($aicsAgeValues, 'age'));
    $soloByGender = fetchReportRows($conn, "SELECT COALESCE(NULLIF(TRIM(sp_personal.gender), ''), 'Unspecified') AS label, COUNT(DISTINCT sp.id) AS total FROM solo_parent_applications sp LEFT JOIN solo_parent_personal sp_personal ON sp_personal.application_id = sp.id GROUP BY label ORDER BY total DESC, label ASC");
    $soloByBarangay = fetchReportRows($conn, "SELECT COALESCE(NULLIF(TRIM(sp_address.barangay), ''), 'Unspecified') AS label, COUNT(DISTINCT sp.id) AS total FROM solo_parent_applications sp LEFT JOIN solo_parent_address sp_address ON sp_address.application_id = sp.id GROUP BY label ORDER BY total DESC, label ASC");
    $soloByStatus = fetchReportRows($conn, "SELECT COALESCE(NULLIF(TRIM(sp.status), ''), 'Unspecified') AS label, COUNT(DISTINCT sp.id) AS total FROM solo_parent_applications sp GROUP BY label ORDER BY total DESC, label ASC");
    $soloByClassification = fetchReportRows($conn, "SELECT COALESCE(NULLIF(TRIM(addi.solo_parent_classification), ''), 'Unspecified') AS label, COUNT(DISTINCT sp.id) AS total FROM solo_parent_applications sp LEFT JOIN solo_parent_additional_info addi ON addi.application_id = sp.id GROUP BY label ORDER BY total DESC, label ASC");
    $soloAgeValues = fetchReportRows($conn, "SELECT sp_personal.age FROM solo_parent_applications sp LEFT JOIN solo_parent_personal sp_personal ON sp_personal.application_id = sp.id");
    $soloByAge = buildAgeGroupReport(array_column($soloAgeValues, 'age'));

    if ($export === 'aics_reports') {
        $csvRows = [];
        foreach ($aicsByGender as $row) $csvRows[] = ['AICS', 'Gender', formatReportLabel($row['label']), (int)$row['total']];
        foreach ($aicsByAge as $row) $csvRows[] = ['AICS', 'Age Group', $row['label'], (int)$row['total']];
        foreach ($aicsByBarangay as $row) $csvRows[] = ['AICS', 'Barangay', formatReportLabel($row['label']), (int)$row['total']];
        foreach ($aicsByAssistanceType as $row) $csvRows[] = ['AICS', 'Assistance Type', formatReportLabel($row['label']), (int)$row['total']];
        streamCsvDownload('aics_reports.csv', ['Program', 'Category', 'Label', 'Total'], $csvRows);
    }
    if ($export === 'solo_parent_reports') {
        $csvRows = [];
        foreach ($soloByGender as $row) $csvRows[] = ['Solo Parent', 'Gender', formatReportLabel($row['label']), (int)$row['total']];
        foreach ($soloByAge as $row) $csvRows[] = ['Solo Parent', 'Age Group', $row['label'], (int)$row['total']];
        foreach ($soloByBarangay as $row) $csvRows[] = ['Solo Parent', 'Barangay', formatReportLabel($row['label']), (int)$row['total']];
        foreach ($soloByStatus as $row) $csvRows[] = ['Solo Parent', 'Status', formatReportLabel($row['label']), (int)$row['total']];
        foreach ($soloByClassification as $row) $csvRows[] = ['Solo Parent', 'Classification', formatReportLabel($row['label']), (int)$row['total']];
        streamCsvDownload('solo_parent_reports.csv', ['Program', 'Category', 'Label', 'Total'], $csvRows);
    }
}

if ($isStaff && !$isAdmin) {
    $adminOnlyTabs = ['locked_accounts', 'reports', 'announcements', 'news', 'audit_logs'];
    if (in_array($tab, $adminOnlyTabs)) {
        header('Location: admin_panel.php?tab=aics');
        exit;
    }
    if (!empty($export)) {
        header('Location: admin_panel.php?tab=aics');
        exit;
    }
}
if ($isAdmin) {
    $pendingAccounts = $conn->query("
        SELECT id, fullname, email, is_verified, created_at, verification_document
        FROM users
        WHERE COALESCE(is_admin, 0) = 0 AND account_status = 'pending'
        ORDER BY created_at ASC
    ");

    $duplicateAccountsResult = $conn->query("
        SELECT u.id, u.fullname, u.email, u.account_status, u.is_verified, u.created_at,
            LOWER(TRIM(u.fullname)) AS normalized_name
        FROM users u
        INNER JOIN (
            SELECT LOWER(TRIM(fullname)) AS normalized_name
            FROM users
            WHERE COALESCE(is_admin, 0) = 0 AND NULLIF(TRIM(fullname), '') IS NOT NULL
            GROUP BY LOWER(TRIM(fullname))
            HAVING COUNT(*) > 1
        ) duplicate_names ON LOWER(TRIM(u.fullname)) = duplicate_names.normalized_name
        WHERE COALESCE(u.is_admin, 0) = 0 AND NULLIF(TRIM(u.fullname), '') IS NOT NULL
        ORDER BY normalized_name ASC, u.created_at ASC
    ");

    $duplicateAccountsByName = [];
    while ($duplicateAccount = $duplicateAccountsResult->fetch_assoc()) {
        $duplicateAccountsByName[$duplicateAccount['normalized_name']][] = $duplicateAccount;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isAdmin ? 'Admin' : 'Staff' ?> Panel - CALINGA</title>
    <link rel="stylesheet" href="../style1.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-width: 260px;
            --primary: #1a5c8c;
            --primary-dark: #0d3b5e;
            --primary-light: #e8f0f8;
            --primary-gradient: linear-gradient(180deg, #1a5c8c, #0d3b5e);
            --success: #2d7d46;
            --success-light: #e6f4ea;
            --danger: #b33c3c;
            --danger-light: #fde8e8;
            --warning: #b8860b;
            --warning-light: #fef9e7;
            --bg: #f0f2f5;
            --card: #ffffff;
            --text: #1a2332;
            --text-secondary: #4a5a6e;
            --text-muted: #8a9aa8;
            --border: #dce3ea;
            --sidebar-bg: #f8f9fb;
            --sidebar-hover: #e8ecf1;
            --sidebar-active: #1a5c8c;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
            --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.06);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.08);
            --radius: 8px;
            --transition: all 0.2s ease;
        }

        /* Dark Mode Variables */
        [data-theme="dark"] {
            --bg: #1a1a2e;
            --card: #16213e;
            --text: #e8e8e8;
            --text-secondary: #b0b0c8;
            --text-muted: #7a7a9a;
            --border: #2a2a4a;
            --sidebar-bg: #0f0f1f;
            --sidebar-hover: #1a1a3a;
            --primary-light: #1a3a5a;
            --success-light: #1a3a2a;
            --danger-light: #3a1a1a;
            --warning-light: #3a2a0a;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.3);
            --shadow: 0 1px 3px rgba(0,0,0,0.4), 0 1px 2px rgba(0,0,0,0.3);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.4);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.5);
        }

        [data-theme="dark"] .sidebar-logo {
            background: linear-gradient(180deg, #2a6a9a, #1a4a7a);
        }

        [data-theme="dark"] .stat-card:hover {
            box-shadow: var(--shadow-md);
        }

        [data-theme="dark"] .role-badge.admin { 
            background: #2a6a9a; 
            color: #fff; 
        }

        [data-theme="dark"] .role-badge.staff { 
            background: #b8860b; 
            color: #fff; 
        }

        [data-theme="dark"] th {
            background: #1a1a3a;
            color: #b0b0c8;
            border-bottom: 2px solid #2a2a4a;
        }

        [data-theme="dark"] td {
            border-bottom: 1px solid #2a2a4a;
        }

        [data-theme="dark"] tr:hover td {
            background: #1a1a3a;
        }

        [data-theme="dark"] .filter-bar {
            background: var(--card);
            border: 1px solid var(--border);
        }

        [data-theme="dark"] .filter-bar input,
        [data-theme="dark"] .filter-bar select {
            background: #1a1a3a;
            border: 1px solid #2a2a4a;
            color: var(--text);
        }

        [data-theme="dark"] .filter-bar input:focus,
        [data-theme="dark"] .filter-bar select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 92, 140, 0.2);
        }

        [data-theme="dark"] .report-panel {
            background: var(--card);
            border: 1px solid var(--border);
        }

        [data-theme="dark"] .report-note {
            background: #1a2a4a;
            color: #8ab4d8;
            border: 1px solid #2a4a6a;
        }

        [data-theme="dark"] .status-pending { 
            background: #3a2a0a; 
            color: #d4b84a; 
        }

        [data-theme="dark"] .status-approved { 
            background: #1a3a2a; 
            color: #4ad88a; 
        }

        [data-theme="dark"] .status-declined { 
            background: #3a1a1a; 
            color: #d84a4a; 
        }

        [data-theme="dark"] .alert-success { 
            background: #1a3a2a; 
            color: #4ad88a; 
            border-color: #2a5a3a; 
        }

        [data-theme="dark"] .alert-error { 
            background: #3a1a1a; 
            color: #d84a4a; 
            border-color: #5a2a2a; 
        }

        [data-theme="dark"] .tracker-ready { color: #4ad88a; }
        [data-theme="dark"] .tracker-cooldown { color: #d84a4a; }
        [data-theme="dark"] .tracker-locked { color: #d84a4a; }

        [data-theme="dark"] .btn-view { background: #4a6a8a; }
        [data-theme="dark"] .btn-edit { background: #4a6a8a; }

        [data-theme="dark"] .sidebar-nav a:hover {
            background: #1a1a3a;
            color: var(--text);
        }

        [data-theme="dark"] .sidebar-nav a.active {
            background: #1a3a5a;
            color: #6ab4e8;
        }

        [data-theme="dark"] .sidebar-footer .return-link:hover {
            background: #1a1a3a;
            color: var(--text);
        }

        [data-theme="dark"] .topbar {
            border-bottom: 1px solid var(--border);
        }

        [data-theme="dark"] .sidebar-brand {
            border-bottom: 1px solid var(--border);
        }

        [data-theme="dark"] .sidebar-footer {
            border-top: 1px solid var(--border);
        }

        [data-theme="dark"] .menu-toggle {
            background: var(--card);
            color: var(--text);
            border: 1px solid var(--border);
        }

        [data-theme="dark"] .menu-toggle:hover {
            background: var(--sidebar-hover);
        }

        [data-theme="dark"] .overlay.active {
            background: rgba(0,0,0,0.6);
        }

        /* Dark Mode Toggle Button */
        .theme-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 1px solid var(--border);
            background: var(--card);
            color: var(--text-secondary);
            cursor: pointer;
            transition: var(--transition);
            font-size: 1.1rem;
            padding: 0;
        }

        .theme-toggle:hover {
            background: var(--sidebar-hover);
            color: var(--text);
            border-color: var(--text-muted);
        }

        .theme-toggle .icon-sun {
            display: inline;
        }
        .theme-toggle .icon-moon {
            display: none;
        }

        [data-theme="dark"] .theme-toggle .icon-sun {
            display: none;
        }
        [data-theme="dark"] .theme-toggle .icon-moon {
            display: inline;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', -apple-system, system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            display: flex;
            min-height: 100vh;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--text);
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, background 0.3s ease, color 0.3s ease;
            border-right: 1px solid var(--border);
            box-shadow: 2px 0 8px rgba(0,0,0,0.02);
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem 1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.875rem;
        }

        .sidebar-logo {
            width: 40px;
            height: 40px;
            background: var(--primary-gradient);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
            color: #fff;
            flex-shrink: 0;
            transition: background 0.3s ease;
        }

        .sidebar-brand-text {
            flex: 1;
        }

        .sidebar-brand-text h1 {
            font-size: 1.05rem;
            font-weight: 600;
            letter-spacing: -0.3px;
            color: var(--text);
            line-height: 1.2;
        }

        .sidebar-brand-text .role-badge {
            display: inline-block;
            font-size: 0.55rem;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-top: 2px;
        }
        .role-badge.admin { background: var(--primary); color: #fff; }
        .role-badge.staff { background: var(--warning); color: #fff; }

        .sidebar-nav {
            flex: 1;
            padding: 0.75rem 0.5rem;
            overflow-y: auto;
        }

        .sidebar-nav .nav-label {
            font-size: 0.6rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--text-muted);
            padding: 0.75rem 0.75rem 0.4rem;
        }

        .sidebar-nav a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.875rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 6px;
            transition: var(--transition);
            position: relative;
        }

        .sidebar-nav a:hover {
            background: var(--sidebar-hover);
            color: var(--text);
        }

        .sidebar-nav a.active {
            background: var(--primary-light);
            color: var(--primary);
            font-weight: 600;
        }

        .sidebar-nav a .icon {
            width: 20px;
            text-align: center;
            font-size: 1rem;
            flex-shrink: 0;
            opacity: 0.7;
        }

        .sidebar-nav a.active .icon {
            opacity: 1;
        }

        .sidebar-nav .badge {
            margin-left: auto;
            background: var(--danger);
            color: #fff;
            padding: 1px 8px;
            border-radius: 10px;
            font-size: 0.65rem;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 0.75rem 1rem;
            border-top: 1px solid var(--border);
        }

        .sidebar-footer .return-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.82rem;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            transition: var(--transition);
        }

        .sidebar-footer .return-link:hover {
            background: var(--sidebar-hover);
            color: var(--text);
        }

        .sidebar-footer .return-link .icon {
            font-size: 0.95rem;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 1.5rem 2rem 2rem;
            min-height: 100vh;
            transition: background 0.3s ease;
        }

        /* ===== TOP BAR ===== */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
            gap: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
            transition: border-color 0.3s ease;
        }

        .topbar-left h1 {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--text);
            letter-spacing: -0.3px;
        }

        .topbar-left .breadcrumb {
            font-size: 0.78rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .topbar-left .breadcrumb span {
            color: var(--text-secondary);
        }

        .topbar-actions {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            margin-bottom: 1.25rem;
            font-size: 0.88rem;
            display: flex;
            align-items: center;
            gap: 0.625rem;
            animation: slideDown 0.25s ease;
            border: 1px solid transparent;
        }
        .alert-success { background: var(--success-light); color: #1a5c3a; border-color: #b7dcc8; }
        .alert-error { background: var(--danger-light); color: #7a2a2a; border-color: #f5cdcd; }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== SECTIONS ===== */
        section { display: none; animation: fadeIn 0.3s ease; }
        section.active { display: block; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .section-header h2 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text);
        }

        /* ===== FILTER BAR ===== */
        .filter-bar {
            display: flex;
            gap: 0.625rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
            padding: 0.75rem 1rem;
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            align-items: center;
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .filter-bar input,
        .filter-bar select {
            padding: 0.4rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-size: 0.82rem;
            background: #fff;
            transition: var(--transition);
            font-family: 'Inter', sans-serif;
            min-width: 130px;
            color: var(--text);
        }

        .filter-bar input:focus,
        .filter-bar select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 92, 140, 0.08);
        }

        .filter-bar input::placeholder {
            color: var(--text-muted);
        }

        .filter-bar button {
            padding: 0.4rem 1.1rem;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.82rem;
            font-weight: 500;
            transition: var(--transition);
            font-family: 'Inter', sans-serif;
        }

        .filter-bar button:hover {
            background: var(--primary-dark);
        }

        /* ===== STAT CARDS ===== */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 0.875rem;
            margin-bottom: 1.25rem;
        }

        .stat-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 1rem 1.25rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
        }

        .stat-card .stat-label {
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--text-muted);
            margin-bottom: 2px;
        }

        .stat-card .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text);
        }

        .stat-card .stat-number.primary { color: var(--primary); }
        .stat-card .stat-number.success { color: var(--success); }
        .stat-card .stat-number.warning { color: var(--warning); }
        .stat-card .stat-number.danger { color: var(--danger); }

        /* ===== TABLES ===== */
        .table-container {
            background: var(--card);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .table-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
        }

        th {
            background: #f7f9fc;
            color: var(--text-secondary);
            padding: 0.6rem 0.875rem;
            text-align: left;
            font-weight: 600;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-bottom: 2px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 2;
            transition: background 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        td {
            padding: 0.6rem 0.875rem;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            transition: border-color 0.3s ease;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #f8fafc;
        }

        /* ===== STATUS BADGES ===== */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: capitalize;
        }
        .status-pending { background: var(--warning-light); color: #7a6a0a; }
        .status-approved { background: var(--success-light); color: #1a5c3a; }
        .status-declined { background: var(--danger-light); color: #7a2a2a; }

        .tracker-ready { color: var(--success); font-weight: 600; }
        .tracker-cooldown { color: var(--danger); font-weight: 600; }
        .tracker-locked { color: #7a2a2a; font-weight: 600; }

        /* ===== ACTION BUTTONS ===== */
        .action-btns {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 500;
            text-decoration: none;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            white-space: nowrap;
            font-family: 'Inter', sans-serif;
        }

        .action-btn:hover {
            opacity: 0.85;
        }

        .btn-view { background: #5b7a9a; }
        .btn-pdf { background: var(--danger); }
        .btn-approve { background: var(--success); }
        .btn-decline { background: var(--warning); color: #fff; }
        .btn-edit { background: #5b7a9a; }
        .btn-delete { background: var(--danger); }
        .btn-add { background: var(--primary); }

        form.inline-action {
            display: inline;
        }

        .application-actions {
            align-items: center;
            gap: 0.5rem;
            min-width: 250px;
        }

        .application-action-group {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding-right: 0.5rem;
            border-right: 1px solid var(--border);
        }

        .application-action-group:last-child {
            padding-right: 0;
            border-right: 0;
        }

        .application-actions .action-btn {
            min-height: 30px;
            padding: 0.35rem 0.7rem;
            border: 1px solid transparent;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .application-actions .action-btn:hover {
            opacity: 1;
            filter: brightness(0.97);
        }

        /* ===== REPORT LAYOUT ===== */
        .report-layout {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        .report-panel {
            background: var(--card);
            border-radius: var(--radius);
            padding: 1rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .report-panel h3 {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.625rem;
            padding-bottom: 0.4rem;
            border-bottom: 1px solid var(--border);
            transition: color 0.3s ease, border-color 0.3s ease;
        }

        .report-panel table {
            font-size: 0.78rem;
        }

        .report-panel th {
            font-size: 0.6rem;
            padding: 0.3rem 0.4rem;
            background: #f7f9fc;
        }

        .report-panel td {
            padding: 0.3rem 0.4rem;
        }

        .report-actions {
            display: flex;
            gap: 0.625rem;
            flex-wrap: wrap;
            margin-bottom: 1.25rem;
        }

        .report-note {
            background: var(--primary-light);
            padding: 0.625rem 1rem;
            border-radius: 6px;
            font-size: 0.82rem;
            color: var(--primary);
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid #c5d8e8;
            transition: background 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        .pending-account-filter {
            display: flex;
            align-items: center;
            gap: 0.875rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
            padding: 0.875rem 1rem;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--card);
        }

        .pending-account-filter label:first-child {
            flex: 1 1 240px;
            color: var(--text-secondary);
            font-size: 0.78rem;
            font-weight: 600;
        }

        .pending-account-filter input[type="search"] {
            display: block;
            width: 100%;
            margin-top: 0.35rem;
            padding: 0.65rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: var(--bg);
            color: var(--text);
            font: inherit;
        }

        .pending-account-filter__duplicates {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            color: var(--text);
            font-size: 0.82rem;
            cursor: pointer;
        }

        .pending-account-filter__duplicates input {
            accent-color: var(--primary);
        }

        .pending-account-filter__count {
            color: var(--text-muted);
            font-size: 0.78rem;
        }

        .name-match-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.55rem;
            border: 1px solid #f2d08a;
            border-radius: 999px;
            background: #fff7e5;
            color: #805512;
            font-size: 0.72rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .name-match-details {
            margin-top: 0.45rem;
            color: var(--text-secondary);
            font-size: 0.76rem;
        }

        .name-match-details summary {
            width: fit-content;
            color: var(--primary);
            font-weight: 600;
            cursor: pointer;
        }

        .name-match-details ul {
            display: grid;
            gap: 0.45rem;
            margin: 0.55rem 0 0;
            padding: 0;
            list-style: none;
        }

        .name-match-details li {
            padding: 0.55rem 0.65rem;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: var(--bg);
            line-height: 1.5;
        }

        [data-theme="dark"] .name-match-badge {
            border-color: #6b5225;
            background: #3a2a0a;
            color: #e8c66c;
        }

        @media (max-width: 640px) {
            .pending-account-filter {
                align-items: stretch;
            }

            .pending-account-filter__duplicates {
                flex-basis: 100%;
            }
        }

        .dashboard-shell {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
        }

        .kpi-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.98), rgba(245,248,252,0.96));
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 1rem 1rem 0.9rem;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .kpi-card:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .kpi-label {
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            margin-bottom: 0.4rem;
        }

        .kpi-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.1;
            margin-bottom: 0.3rem;
        }

        .kpi-value.primary { color: var(--primary); }
        .kpi-value.success { color: var(--success); }
        .kpi-value.warning { color: var(--warning); }
        .kpi-value.danger { color: var(--danger); }

        .kpi-foot {
            font-size: 0.72rem;
            color: var(--text-muted);
        }

        .insight-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1rem;
        }

        .insight-panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1rem;
            box-shadow: var(--shadow-sm);
        }

        .insight-panel h3 {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--text-secondary);
            margin-bottom: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .insight-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .insight-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.55rem 0.7rem;
            background: rgba(26, 92, 140, 0.04);
            border-radius: 8px;
            border: 1px solid rgba(26, 92, 140, 0.08);
            font-size: 0.76rem;
            color: var(--text-secondary);
        }

        .insight-list strong {
            color: var(--text);
            font-weight: 600;
            text-align: right;
        }

        .dashboard-filter-bar {
            justify-content: flex-start;
            align-items: center;
        }

        .is-hidden {
            display: none !important;
        }

        [data-theme="dark"] .kpi-card {
            background: linear-gradient(135deg, rgba(18,20,34,0.98), rgba(23,29,45,0.96));
            border-color: var(--border);
        }

        [data-theme="dark"] .kpi-value {
            color: var(--text);
        }

        [data-theme="dark"] .insight-list li {
            background: rgba(255,255,255,0.02);
            border-color: rgba(255,255,255,0.05);
        }

        /* ===== DOCUMENT VIEWER MODAL ===== */
        .doc-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 500;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            animation: fadeIn 0.2s ease;
        }
        .doc-modal.active {
            display: flex;
        }
        .doc-modal-content {
            background: var(--card);
            border-radius: var(--radius);
            width: 100%;
            max-width: 900px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
        }
        .doc-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border);
            gap: 0.75rem;
        }
        .doc-modal-header h3 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text);
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .doc-modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0 0.3rem;
            transition: color 0.2s ease;
        }
        .doc-modal-close:hover { color: var(--danger); }
        .doc-modal-body {
            flex: 1;
            overflow: auto;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
        }
        [data-theme="dark"] .doc-modal-body { background: #0f0f1f; }
        .doc-modal-body img {
            max-width: 100%;
            max-height: 80vh;
            display: block;
            object-fit: contain;
        }
        .doc-modal-body iframe {
            width: 100%;
            height: 80vh;
            border: none;
            background: #fff;
        }
        .doc-modal-body .doc-error {
            padding: 2rem;
            color: var(--danger);
            text-align: center;
        }

        /* ===== MOBILE ===== */
        .menu-toggle {
            display: none;
            position: fixed;
            top: 0.75rem;
            left: 0.75rem;
            z-index: 200;
            background: var(--card);
            color: var(--text);
            border: 1px solid var(--border);
            padding: 0.5rem 0.7rem;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1rem;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .menu-toggle:hover {
            background: var(--bg);
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.25);
            z-index: 99;
            transition: background 0.3s ease;
        }

        .overlay.active {
            display: block;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                padding: 1rem;
                padding-top: 3.5rem;
            }
            .menu-toggle {
                display: block;
            }
            .overlay.active {
                display: block;
            }
            .report-layout {
                grid-template-columns: 1fr;
            }
            .filter-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-bar input,
            .filter-bar select {
                min-width: auto;
                width: 100%;
            }
            .topbar-left h1 {
                font-size: 1.1rem;
            }
            .stat-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .action-btns {
                flex-direction: column;
                gap: 3px;
            }
            .application-actions {
                flex-direction: row;
                align-items: flex-start;
                min-width: 250px;
            }
            .application-action-group {
                flex-direction: row;
            }
            .action-btn {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .stat-grid {
                grid-template-columns: 1fr;
            }
            .topbar {
                flex-direction: column;
                align-items: flex-start;
            }
            .topbar-actions {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
</head>
<body>

<!-- MOBILE MENU TOGGLE -->
<button class="menu-toggle" onclick="toggleSidebar()">☰</button>
<div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-logo">C</div>
        <div class="sidebar-brand-text">
            <h1>CALINGA</h1>
            <span class="role-badge <?= $isAdmin ? 'admin' : 'staff' ?>">
                <?= $isAdmin ? 'Administrator' : 'Staff' ?>
            </span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">Applications</div>
        <a href="?tab=aics" class="<?= $tab == 'aics' ? 'active' : '' ?>">
            <span class="icon">📋</span> AICS Applications
        </a>
        <a href="?tab=request_tracker" class="<?= $tab == 'request_tracker' ? 'active' : '' ?>">
            <span class="icon">📊</span> Request Tracker
        </a>
        <a href="?tab=solo_parent" class="<?= $tab == 'solo_parent' ? 'active' : '' ?>">
            <span class="icon">👨‍👩‍👧</span> Solo Parent
        </a>

        <?php if ($isAdmin): ?>
        <div class="nav-label">Management</div>
        <a href="?tab=locked_accounts" class="<?= $tab == 'locked_accounts' ? 'active' : '' ?>">
            <span class="icon">🔒</span> Locked Accounts
        </a>
        <a href="?tab=reports" class="<?= $tab == 'reports' ? 'active' : '' ?>">
            <span class="icon">📈</span> Reports
        </a>
        <a href="?tab=announcements" class="<?= $tab == 'announcements' ? 'active' : '' ?>">
            <span class="icon">📢</span> Announcements
        </a>
        <a href="?tab=news" class="<?= $tab == 'news' ? 'active' : '' ?>">
            <span class="icon">📰</span> News
        </a>
        <a href="?tab=audit_logs" class="<?= $tab == 'audit_logs' ? 'active' : '' ?>">
            <span class="icon">📝</span> Audit Logs
        </a>
        <a href="?tab=pending_accounts" class="<?= $tab == 'pending_accounts' ? 'active' : '' ?>">
            <span class="icon">🆕</span> Pending Accounts
        </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <a href="../index.php" class="return-link">
            <span class="icon">↩</span> Return to Homepage
        </a>
    </div>
</aside>

<!-- MAIN CONTENT -->
<main class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <h1><?= $isAdmin ? 'Admin' : 'Staff' ?> Dashboard</h1>
            <div class="breadcrumb">Home / <span><?= ucfirst(str_replace('_', ' ', $tab)) ?></span></div>
        </div>
        <div class="topbar-actions">
            <!-- Dark Mode Toggle -->
            <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
                <span class="icon-sun">☀️</span>
                <span class="icon-moon">🌙</span>
            </button>
            <?php include "notification_dropdown.php"; ?>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success">✔ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error">✖ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- ===== AICS Applications ===== -->
    <section id="aics" class="<?= $tab == 'aics' ? 'active' : '' ?>">
        <div class="section-header">
            <h2>AICS Applications</h2>
        </div>
        <div class="filter-bar">
            <form method="GET" style="display: flex; gap: 0.625rem; flex-wrap: wrap; align-items: center; width: 100%;">
                <input type="hidden" name="tab" value="aics">
                <input type="text" name="barangay" placeholder="Barangay" value="<?= htmlspecialchars($_GET['barangay'] ?? '') ?>">
                <select name="gender">
                    <option value="">All Genders</option>
                    <option value="Male" <?= ($_GET['gender'] ?? '')=='Male'?'selected':'' ?>>Male</option>
                    <option value="Female" <?= ($_GET['gender'] ?? '')=='Female'?'selected':'' ?>>Female</option>
                </select>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="pending" <?= ($_GET['status'] ?? '')=='pending'?'selected':'' ?>>Pending</option>
                    <option value="approved" <?= ($_GET['status'] ?? '')=='approved'?'selected':'' ?>>Approved</option>
                    <option value="declined" <?= ($_GET['status'] ?? '')=='declined'?'selected':'' ?>>Declined</option>
                </select>
                <input type="text" name="assistance_type" placeholder="Assistance Type" value="<?= htmlspecialchars($_GET['assistance_type'] ?? '') ?>">
                <button type="submit">Filter</button>
            </form>
        </div>

        <div class="table-container">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Applicant Name</th>
                            <th>Barangay</th>
                            <th>Gender</th>
                            <th>Assistance Type</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $filters = ['barangay' => $_GET['barangay'] ?? '', 'gender' => $_GET['gender'] ?? '', 'status' => $_GET['status'] ?? '', 'assistance_type' => $_GET['assistance_type'] ?? ''];
                    $filterClauses = []; $params = []; $types = '';
                    if(!empty($filters['barangay'])) { $filterClauses[] = "ad.barangay LIKE ?"; $params[] = "%".$filters['barangay']."%"; $types .= "s"; }
                    if(!empty($filters['gender'])) { $filterClauses[] = "ap.gender = ?"; $params[] = $filters['gender']; $types .= "s"; }
                    if(!empty($filters['status'])) { $filterClauses[] = "a.status = ?"; $params[] = $filters['status']; $types .= "s"; }
                    if(!empty($filters['assistance_type'])) { $filterClauses[] = "a.financial_assistance_type LIKE ?"; $params[] = "%".$filters['assistance_type']."%"; $types .= "s"; }
                    $filterSQL = $filterClauses ? "WHERE " . implode(" AND ", $filterClauses) : "";
                    $stmt = $conn->prepare("SELECT a.id, a.status, a.created_at, a.financial_assistance_type AS assistance_type, u.fullname AS user_name, u.email AS user_email, MIN(ap.first_name) AS first_name, MIN(ap.middle_name) AS middle_name, MIN(ap.last_name) AS last_name, MIN(ap.gender) AS gender, MIN(ad.barangay) AS barangay FROM aics_applications a LEFT JOIN users u ON u.id = a.user_id LEFT JOIN aics_applicants ap ON ap.application_id = a.id LEFT JOIN aics_addresses ad ON ad.application_id = a.id $filterSQL GROUP BY a.id ORDER BY a.created_at DESC");
                    if($params) $stmt->bind_param($types, ...$params);
                    $stmt->execute();
                    $aics_applications = $stmt->get_result();
                    if ($aics_applications->num_rows > 0):
                    while($row = $aics_applications->fetch_assoc()):
                    ?>
                    <tr>
                        <td><strong>#<?= $row['id'] ?></strong></td>
                        <td><?= htmlspecialchars(trim(($row['first_name']??'').' '.($row['middle_name']??'').' '.($row['last_name']??''))) ?></td>
                        <td><?= htmlspecialchars($row['barangay']??'') ?></td>
                        <td><?= htmlspecialchars($row['gender']??'') ?></td>
                        <td><?= htmlspecialchars($row['assistance_type']??'') ?></td>
                        <td><span class="status-badge status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
                        <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                        <td>
                            <div class="action-btns application-actions">
                                <div class="application-action-group">
                                    <a href="aics_admin_view.php?id=<?= $row['id'] ?>" class="action-btn btn-view">View</a>
                                    <a href="generate_aics_pdf.php?id=<?= $row['id'] ?>" class="action-btn btn-pdf" target="_blank">PDF</a>
                                </div>
                                <?php if(($isAdmin || $isStaff) && $row['status']=='pending'): ?>
                                    <div class="application-action-group">
                                        <form method="POST" action="aics_admin_process.php" class="inline-action">
                                            <input type="hidden" name="application_id" value="<?= $row['id'] ?>">
                                            <input type="hidden" name="action" value="approve">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_action_token']) ?>">
                                            <button type="submit" class="action-btn btn-approve">Approve</button>
                                        </form>
                                        <form method="POST" action="aics_admin_process.php" class="inline-action">
                                            <input type="hidden" name="application_id" value="<?= $row['id'] ?>">
                                            <input type="hidden" name="action" value="decline">
                                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_action_token']) ?>">
                                            <button type="submit" class="action-btn btn-decline">Decline</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                                <?php if ($isAdmin): ?>
                                    <div class="application-action-group">
                                    <form method="POST" action="admin_panel.php?tab=aics" class="inline-action" onsubmit="return confirm('Delete AICS application #<?= (int)$row['id'] ?>? This permanently deletes the application and its associated records. This cannot be undone.');">
                                        <input type="hidden" name="application_id" value="<?= (int)$row['id'] ?>">
                                        <input type="hidden" name="action" value="delete_aics_application">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_action_token']) ?>">
                                        <button type="submit" class="action-btn btn-delete">Delete</button>
                                    </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="8" style="text-align:center; padding:1.5rem; color: var(--text-muted);">No AICS applications found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ===== Request Tracker ===== -->
    <section id="request_tracker" class="<?= $tab == 'request_tracker' ? 'active' : '' ?>">
        <div class="section-header">
            <h2>AICS Request Tracker</h2>
        </div>
        <div class="table-container">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Cooldown Until</th><th>Last Approved</th></tr>
                    </thead>
                    <tbody>
                    <?php 
                    $hasTrackerRows = false;
                    while ($trackerRow = $requestTracker->fetch_assoc()):
                        $hasTrackerRows = true;
                        $isOnCooldown = !empty($trackerRow['aics_cooldown_until']) && strtotime($trackerRow['aics_cooldown_until']) > time();
                        $nameClass = $isOnCooldown ? 'tracker-cooldown' : 'tracker-ready';
                        $trackerStatus = $isOnCooldown ? 'On Cooldown' : 'Ready to Request';
                    ?>
                    <tr>
                        <td><strong>#<?= (int)$trackerRow['id'] ?></strong></td>
                        <td class="<?= $nameClass ?>"><?= htmlspecialchars($trackerRow['fullname'] ?? '') ?></td>
                        <td><?= htmlspecialchars($trackerRow['email'] ?? '') ?></td>
                        <td><span class="<?= $nameClass ?>"><?= $trackerStatus ?></span></td>
                        <td><?= !empty($trackerRow['aics_cooldown_until']) ? htmlspecialchars(date('M d, Y h:i A', strtotime($trackerRow['aics_cooldown_until']))) : 'N/A' ?></td>
                        <td><?= !empty($trackerRow['last_approved_at']) ? htmlspecialchars(date('M d, Y', strtotime($trackerRow['last_approved_at']))) : 'No approved request yet' ?></td>
                    </tr>
                    <?php endwhile; 
                    if (!$hasTrackerRows): ?>
                    <tr><td colspan="6" style="text-align:center; padding:1.5rem; color: var(--text-muted);">No users with AICS applications.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ===== Solo Parent Applications ===== -->
    <section id="solo_parent" class="<?= $tab == 'solo_parent' ? 'active' : '' ?>">
        <div class="section-header">
            <h2>Solo Parent Applications</h2>
        </div>
        <div class="filter-bar">
            <form method="GET" style="display: flex; gap: 0.625rem; flex-wrap: wrap; align-items: center; width: 100%;">
                <input type="hidden" name="tab" value="solo_parent">
                <input type="text" name="sp_barangay" placeholder="Barangay" value="<?= htmlspecialchars($_GET['sp_barangay'] ?? '') ?>">
                <select name="sp_gender">
                    <option value="">All Genders</option>
                    <option value="Male" <?= ($_GET['sp_gender'] ?? '')=='Male'?'selected':'' ?>>Male</option>
                    <option value="Female" <?= ($_GET['sp_gender'] ?? '')=='Female'?'selected':'' ?>>Female</option>
                </select>
                <select name="sp_status">
                    <option value="">All Status</option>
                    <option value="pending" <?= ($_GET['sp_status'] ?? '')=='pending'?'selected':'' ?>>Pending</option>
                    <option value="approved" <?= ($_GET['sp_status'] ?? '')=='approved'?'selected':'' ?>>Approved</option>
                    <option value="declined" <?= ($_GET['sp_status'] ?? '')=='declined'?'selected':'' ?>>Declined</option>
                </select>
                <button type="submit">Filter</button>
            </form>
        </div>
        <div class="table-container">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr><th>ID</th><th>Applicant Name</th><th>Barangay</th><th>Contact No.</th><th>Date</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    $spFilters = ['barangay' => $_GET['sp_barangay'] ?? '', 'gender' => $_GET['sp_gender'] ?? '', 'status' => $_GET['sp_status'] ?? ''];
                    $spFilterClauses = []; $spParams = []; $spTypes = '';
                    if(!empty($spFilters['barangay'])) { $spFilterClauses[] = "sp_address.barangay LIKE ?"; $spParams[] = "%".$spFilters['barangay']."%"; $spTypes .= "s"; }
                    if(!empty($spFilters['gender'])) { $spFilterClauses[] = "sp_personal.gender = ?"; $spParams[] = $spFilters['gender']; $spTypes .= "s"; }
                    if(!empty($spFilters['status'])) { $spFilterClauses[] = "sp.status = ?"; $spParams[] = $spFilters['status']; $spTypes .= "s"; }
                    $spFilterSQL = $spFilterClauses ? "WHERE " . implode(" AND ", $spFilterClauses) : "";
                    
                    $spStmt = $conn->prepare("SELECT sp.id, sp.status, sp.created_at, sp_personal.first_name, sp_personal.middle_name, sp_personal.last_name, sp_personal.name_extension, sp_personal.phone as personal_phone, sp_address.house_no, sp_address.barangay, sp_address.city_municipality, sp_address.province FROM solo_parent_applications sp LEFT JOIN solo_parent_personal sp_personal ON sp_personal.application_id = sp.id LEFT JOIN solo_parent_address sp_address ON sp_address.application_id = sp.id $spFilterSQL ORDER BY sp.created_at DESC");
                    if($spParams) $spStmt->bind_param($spTypes, ...$spParams);
                    $spStmt->execute();
                    $solo_parent_applications = $spStmt->get_result();
                    if ($solo_parent_applications->num_rows > 0):
                    while($row = $solo_parent_applications->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?= $row['id'] ?></strong></td>
                        <td><?= htmlspecialchars(trim(($row['first_name'] ?? '') . ' ' . ($row['middle_name'] ?? '') . ' ' . ($row['last_name'] ?? '') . ' ' . ($row['name_extension'] ?? ''))) ?></td>
                        <td><?= htmlspecialchars(trim(($row['house_no'] ?? '') . ' ' . ($row['barangay'] ?? '') . ', ' . ($row['city_municipality'] ?? '') . ', ' . ($row['province'] ?? ''))) ?></td>
                        <td><?= htmlspecialchars($row['personal_phone'] ?? '') ?></td>
                        <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                        <td><span class="status-badge status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></span></td>
                        <td>
                            <div class="action-btns application-actions">
                                <div class="application-action-group">
                                    <a href="solo_parent_admin_view_fixed.php?id=<?= $row['id'] ?>" class="action-btn btn-view">View</a>
                                    <a href="generate_solo_parent_pdf.php?id=<?= $row['id'] ?>" class="action-btn btn-pdf" target="_blank">PDF</a>
                                </div>
                                <?php if(($isAdmin || $isStaff) && $row['status'] == 'pending'): ?>
                                <div class="application-action-group">
                                    <form method="POST" action="solo_parent_admin_process.php" class="inline-action" onsubmit="return confirm('Approve this application?')">
                                        <input type="hidden" name="application_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_action_token']) ?>">
                                        <button type="submit" class="action-btn btn-approve">Approve</button>
                                    </form>
                                    <form method="POST" action="solo_parent_admin_process.php" class="inline-action" onsubmit="return confirm('Decline this application?')">
                                        <input type="hidden" name="application_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="action" value="decline">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_action_token']) ?>">
                                        <button type="submit" class="action-btn btn-decline">Decline</button>
                                    </form>
                                </div>
                                <?php endif; ?>
                                <?php if ($isAdmin): ?>
                                <div class="application-action-group">
                                    <form method="POST" action="admin_panel.php?tab=solo_parent" class="inline-action" onsubmit="return confirm('Delete Solo Parent application #<?= (int)$row['id'] ?>? This permanently deletes the application and its associated records. This cannot be undone.');">
                                        <input type="hidden" name="application_id" value="<?= (int)$row['id'] ?>">
                                        <input type="hidden" name="action" value="delete_solo_parent_application">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_action_token']) ?>">
                                        <button type="submit" class="action-btn btn-delete">Delete</button>
                                    </form>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="7" style="text-align:center; padding:1.5rem; color: var(--text-muted);">No Solo Parent applications found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <?php if ($isAdmin): ?>
    <!-- ===== Locked Accounts ===== -->
    <section id="locked_accounts" class="<?= $tab == 'locked_accounts' ? 'active' : '' ?>">
        <div class="section-header">
            <h2>Locked Accounts</h2>
        </div>
        <div class="table-container">
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr><th>ID</th><th>Name</th><th>Email</th><th>Status</th><th>Locked Until</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                    <?php
                    $hasActiveLockedAccounts = false;
                    if (isset($lockedAccounts)):
                        while ($lockedRow = $lockedAccounts->fetch_assoc()):
                            $lockedUntilTs = !empty($lockedRow['locked_until']) ? strtotime($lockedRow['locked_until']) : false;
                            if ($lockedUntilTs === false || $lockedUntilTs <= time()) continue;
                            $hasActiveLockedAccounts = true;
                    ?>
                    <tr>
                        <td><strong>#<?= (int) $lockedRow['id'] ?></strong></td>
                        <td class="tracker-locked"><?= htmlspecialchars($lockedRow['fullname'] ?? '') ?></td>
                        <td><?= htmlspecialchars($lockedRow['email'] ?? '') ?></td>
                        <td><span class="status-badge status-declined">Locked</span></td>
                        <td><?= htmlspecialchars(date('M d, Y h:i A', $lockedUntilTs)) ?></td>
                        <td>
                            <form method="POST" action="admin_unlock_user.php" class="inline-action" onsubmit="return confirm('Unlock this account now?')">
                                <input type="hidden" name="user_id" value="<?= (int) $lockedRow['id'] ?>">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_action_token']) ?>">
                                <button type="submit" class="action-btn btn-approve">Unlock</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; endif; if (!$hasActiveLockedAccounts): ?>
                    <tr><td colspan="6" style="text-align:center; padding:1.5rem; color: var(--text-muted);">No user accounts are currently locked.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ===== Pending Accounts ===== -->
    <section id="pending_accounts" class="<?= $tab == 'pending_accounts' ? 'active' : '' ?>">
        <div class="section-header">
            <h2>Pending Accounts</h2>
        </div>
        <div class="report-note">🆕 New signups waiting for admin approval. Email-verified users are marked below. Click "View ID" to inspect the uploaded verification document.</div>
        <div class="pending-account-filter">
            <label for="pendingAccountSearch">
                Find pending accounts by name or email
                <input type="search" id="pendingAccountSearch" placeholder="Type a name or email…" autocomplete="off">
            </label>
            <label class="pending-account-filter__duplicates">
                <input type="checkbox" id="pendingAccountDuplicatesOnly">
                Show possible same-name accounts only
            </label>
            <span class="pending-account-filter__count" id="pendingAccountFilterCount" aria-live="polite"></span>
        </div>
        <div class="table-container">
            <div class="table-scroll">
                <table id="pendingAccountsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Same-name accounts</th>
                            <th>Email</th>
                            <th>ID Document</th>
                            <th>Email Verified</th>
                            <th>Signed Up</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $hasPending = false;
                    if (isset($pendingAccounts)):
                        while ($pendingRow = $pendingAccounts->fetch_assoc()):
                            $hasPending = true;
                            $pendingName = trim((string) ($pendingRow['fullname'] ?? ''));
                            $normalizedPendingName = function_exists('mb_strtolower')
                                ? mb_strtolower($pendingName, 'UTF-8')
                                : strtolower($pendingName);
                            $sameNameAccounts = $duplicateAccountsByName[$normalizedPendingName] ?? [];
                            $sameNameAccounts = array_values(array_filter(
                                $sameNameAccounts,
                                static fn($account) => (int) $account['id'] !== (int) $pendingRow['id']
                            ));
                    ?>
                    <tr class="pending-account-row"
                        data-name-search="<?= htmlspecialchars($normalizedPendingName, ENT_QUOTES, 'UTF-8') ?>"
                        data-email-search="<?= htmlspecialchars(strtolower((string) $pendingRow['email']), ENT_QUOTES, 'UTF-8') ?>"
                        data-has-name-matches="<?= $sameNameAccounts ? '1' : '0' ?>">
                        <td><strong>#<?= (int) $pendingRow['id'] ?></strong></td>
                        <td><?= htmlspecialchars($pendingRow['fullname'] ?? '') ?></td>
                        <td>
                            <?php if ($sameNameAccounts): ?>
                                <span class="name-match-badge">⚠ <?= count($sameNameAccounts) ?> possible match<?= count($sameNameAccounts) === 1 ? '' : 'es' ?></span>
                                <details class="name-match-details">
                                    <summary>Review matching accounts</summary>
                                    <ul>
                                        <?php foreach ($sameNameAccounts as $sameNameAccount): ?>
                                            <li>
                                                <strong>#<?= (int) $sameNameAccount['id'] ?> <?= htmlspecialchars($sameNameAccount['fullname'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong><br>
                                                <?= htmlspecialchars($sameNameAccount['email'] ?? '', ENT_QUOTES, 'UTF-8') ?><br>
                                                Status: <?= htmlspecialchars(ucfirst((string) ($sameNameAccount['account_status'] ?? 'unknown')), ENT_QUOTES, 'UTF-8') ?>
                                                · Email: <?= !empty($sameNameAccount['is_verified']) ? 'Verified' : 'Not verified' ?>
                                                · Signed up <?= htmlspecialchars(date('M d, Y', strtotime($sameNameAccount['created_at'])), ENT_QUOTES, 'UTF-8') ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </details>
                            <?php else: ?>
                                <span style="color: var(--text-muted);">No exact name match</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($pendingRow['email'] ?? '') ?></td>
                        <td>
                            <?php if (!empty($pendingRow['verification_document'])): ?>
                                <button type="button"
                                        class="action-btn btn-view"
                                        onclick="openDocViewer(<?= (int) $pendingRow['id'] ?>, '<?= htmlspecialchars($pendingRow['fullname'], ENT_QUOTES) ?>')">
                                    View ID
                                </button>
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size:0.75rem;">No document</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $pendingRow['is_verified'] ? '<span class="status-badge status-approved">Verified</span>' : '<span class="status-badge status-pending">Not Verified</span>' ?></td>
                        <td><?= htmlspecialchars(date('M d, Y h:i A', strtotime($pendingRow['created_at']))) ?></td>
                        <td>
                            <div class="action-btns">
                                <form method="POST" action="admin_approve_user.php" class="inline-action" onsubmit="return confirm('Approve this account?')">
                                    <input type="hidden" name="user_id" value="<?= (int) $pendingRow['id'] ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_action_token']) ?>">
                                    <button type="submit" class="action-btn btn-approve">Approve</button>
                                </form>
                                <form method="POST" action="admin_approve_user.php" class="inline-action" onsubmit="return confirm('Reject this account?')">
                                    <input type="hidden" name="user_id" value="<?= (int) $pendingRow['id'] ?>">
                                    <input type="hidden" name="action" value="reject">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_action_token']) ?>">
                                    <button type="submit" class="action-btn btn-decline">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; endif; if (!$hasPending): ?>
                    <tr class="pending-accounts-empty"><td colspan="8" style="text-align:center; padding:1.5rem; color: var(--text-muted);">No pending accounts.</td></tr>
                    <?php elseif ($hasPending): ?>
                    <tr class="pending-accounts-no-match" hidden><td colspan="8" style="text-align:center; padding:1.5rem; color: var(--text-muted);">No accounts match these filters.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ===== Reports ===== -->
    <section id="reports" class="<?= $tab == 'reports' ? 'active' : '' ?>">
        <div class="section-header">
            <h2>Reports Dashboard</h2>
        </div>

        <div class="report-note">📊 Live operational snapshot of collected service data. Use the filters to focus on the program or status you need.</div>

        <div class="filter-bar dashboard-filter-bar" style="margin-bottom:1rem;">
            <select id="reportProgramFilter">
                <option value="all">All Programs</option>
                <option value="aics">AICS</option>
                <option value="solo">Solo Parent</option>
            </select>
            <select id="reportStatusFilter">
                <option value="all">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="declined">Declined</option>
            </select>
            <a href="?tab=reports&export=aics_reports" class="action-btn btn-view">Export AICS CSV</a>
            <a href="?tab=reports&export=solo_parent_reports" class="action-btn btn-view">Export Solo Parent CSV</a>
        </div>

        <div class="dashboard-shell">
            <div class="dashboard-grid">
                <div class="kpi-card" data-program="aics" data-status="all">
                    <div class="kpi-label">AICS Total</div>
                    <div class="kpi-value primary"><?= (int)($aicsSummary['total_applications'] ?? 0) ?></div>
                    <div class="kpi-foot">All submitted requests</div>
                </div>
                <div class="kpi-card" data-program="aics" data-status="pending">
                    <div class="kpi-label">AICS Pending</div>
                    <div class="kpi-value warning"><?= (int)($aicsSummary['pending_count'] ?? 0) ?></div>
                    <div class="kpi-foot">Awaiting review</div>
                </div>
                <div class="kpi-card" data-program="aics" data-status="approved">
                    <div class="kpi-label">AICS Approved</div>
                    <div class="kpi-value success"><?= (int)($aicsSummary['approved_count'] ?? 0) ?></div>
                    <div class="kpi-foot">Granted assistance</div>
                </div>
                <div class="kpi-card" data-program="aics" data-status="declined">
                    <div class="kpi-label">AICS Declined</div>
                    <div class="kpi-value danger"><?= (int)($aicsSummary['declined_count'] ?? 0) ?></div>
                    <div class="kpi-foot">Needs follow-up</div>
                </div>

                <div class="kpi-card" data-program="solo" data-status="all">
                    <div class="kpi-label">Solo Parent Total</div>
                    <div class="kpi-value primary"><?= (int)($soloSummary['total_applications'] ?? 0) ?></div>
                    <div class="kpi-foot">All applications</div>
                </div>
                <div class="kpi-card" data-program="solo" data-status="pending">
                    <div class="kpi-label">Solo Parent Pending</div>
                    <div class="kpi-value warning"><?= (int)($soloSummary['pending_count'] ?? 0) ?></div>
                    <div class="kpi-foot">Awaiting action</div>
                </div>
                <div class="kpi-card" data-program="solo" data-status="approved">
                    <div class="kpi-label">Solo Parent Approved</div>
                    <div class="kpi-value success"><?= (int)($soloSummary['approved_count'] ?? 0) ?></div>
                    <div class="kpi-foot">Approved cases</div>
                </div>
                <div class="kpi-card" data-program="solo" data-status="declined">
                    <div class="kpi-label">Solo Parent Declined</div>
                    <div class="kpi-value danger"><?= (int)($soloSummary['declined_count'] ?? 0) ?></div>
                    <div class="kpi-foot">Dismissed cases</div>
                </div>
            </div>

            <div class="insight-grid">
                <div class="insight-panel" data-program="aics" data-status="all">
                    <h3>Program Insights</h3>
                    <ul class="insight-list">
                        <li><span>Most common request</span><strong><?= htmlspecialchars(formatReportLabel(($aicsByAssistanceType[0]['label'] ?? 'Unspecified'))) ?></strong></li>
                        <li><span>Top barangay</span><strong><?= htmlspecialchars(formatReportLabel(($aicsByBarangay[0]['label'] ?? 'Unspecified'))) ?></strong></li>
                        <li><span>Largest age group</span><strong><?= htmlspecialchars(($aicsByAge[0]['label'] ?? 'Unspecified')) ?></strong></li>
                    </ul>
                </div>
                <div class="insight-panel" data-program="solo" data-status="all">
                    <h3>Program Insights</h3>
                    <ul class="insight-list">
                        <li><span>Most common classification</span><strong><?= htmlspecialchars(formatReportLabel(($soloByClassification[0]['label'] ?? 'Unspecified'))) ?></strong></li>
                        <li><span>Top barangay</span><strong><?= htmlspecialchars(formatReportLabel(($soloByBarangay[0]['label'] ?? 'Unspecified'))) ?></strong></li>
                        <li><span>Largest age group</span><strong><?= htmlspecialchars(($soloByAge[0]['label'] ?? 'Unspecified')) ?></strong></li>
                    </ul>
                </div>
                <div class="insight-panel" data-program="all" data-status="all">
                    <h3>Operational Snapshot</h3>
                    <ul class="insight-list">
                        <li><span>Current workload</span><strong><?= (int)($aicsSummary['pending_count'] ?? 0) + (int)($soloSummary['pending_count'] ?? 0) ?> pending</strong></li>
                        <li><span>Approval rate</span><strong><?= round((((int)($aicsSummary['approved_count'] ?? 0) + (int)($soloSummary['approved_count'] ?? 0)) / max(1, (int)($aicsSummary['total_applications'] ?? 0) + (int)($soloSummary['total_applications'] ?? 0))) * 100, 1) ?>%</strong></li>
                        <li><span>Data quality</span><strong>Live collection enabled</strong></li>
                    </ul>
                </div>
            </div>

            <div class="report-layout">
                <div class="report-panel" data-program="aics" data-status="all">
                    <h3>AICS — By Gender</h3>
                    <table>
                        <thead><tr><th>Gender</th><th>Total</th></tr></thead>
                        <tbody>
                            <?php foreach ($aicsByGender as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars(formatReportLabel($row['label'])) ?></td>
                                    <td><strong><?= (int)$row['total'] ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="report-panel" data-program="aics" data-status="all">
                    <h3>AICS — By Age</h3>
                    <table>
                        <thead><tr><th>Age Group</th><th>Total</th></tr></thead>
                        <tbody>
                            <?php foreach ($aicsByAge as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['label']) ?></td>
                                    <td><strong><?= (int)$row['total'] ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="report-panel" data-program="aics" data-status="all">
                    <h3>AICS — By Barangay</h3>
                    <table>
                        <thead><tr><th>Barangay</th><th>Total</th></tr></thead>
                        <tbody>
                            <?php foreach ($aicsByBarangay as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars(formatReportLabel($row['label'])) ?></td>
                                    <td><strong><?= (int)$row['total'] ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="report-panel" data-program="aics" data-status="all">
                    <h3>AICS — By Assistance Type</h3>
                    <table>
                        <thead><tr><th>Type</th><th>Total</th></tr></thead>
                        <tbody>
                            <?php foreach ($aicsByAssistanceType as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars(formatReportLabel($row['label'])) ?></td>
                                    <td><strong><?= (int)$row['total'] ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="report-panel" data-program="solo" data-status="all">
                    <h3>Solo Parent — By Gender</h3>
                    <table>
                        <thead><tr><th>Gender</th><th>Total</th></tr></thead>
                        <tbody>
                            <?php foreach ($soloByGender as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars(formatReportLabel($row['label'])) ?></td>
                                    <td><strong><?= (int)$row['total'] ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="report-panel" data-program="solo" data-status="all">
                    <h3>Solo Parent — By Age</h3>
                    <table>
                        <thead><tr><th>Age Group</th><th>Total</th></tr></thead>
                        <tbody>
                            <?php foreach ($soloByAge as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['label']) ?></td>
                                    <td><strong><?= (int)$row['total'] ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="report-panel" data-program="solo" data-status="all">
                    <h3>Solo Parent — By Barangay</h3>
                    <table>
                        <thead><tr><th>Barangay</th><th>Total</th></tr></thead>
                        <tbody>
                            <?php foreach ($soloByBarangay as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars(formatReportLabel($row['label'])) ?></td>
                                    <td><strong><?= (int)$row['total'] ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="report-panel" data-program="solo" data-status="all">
                    <h3>Solo Parent — By Status</h3>
                    <table>
                        <thead><tr><th>Status</th><th>Total</th></tr></thead>
                        <tbody>
                            <?php foreach ($soloByStatus as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars(formatReportLabel(ucfirst($row['label']))) ?></td>
                                    <td><strong><?= (int)$row['total'] ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== Announcements ===== -->
    <section id="announcements" class="<?= $tab == 'announcements' ? 'active' : '' ?>">
        <div class="section-header">
            <h2>Announcements</h2>
            <a href="add_announcement.php" class="action-btn btn-add">+ Add Announcement</a>
        </div>
        <div class="table-container">
            <div class="table-scroll">
                <table>
                    <thead><tr><th>ID</th><th>Title</th><th>Date</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php if (isset($announcements) && $announcements->num_rows > 0):
                        while($row = $announcements->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?= $row['id'] ?></strong></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= date('M d, Y', strtotime($row['date_posted'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <a href="edit_announcement.php?id=<?= $row['id'] ?>" class="action-btn btn-edit">Edit</a>
                                <form method="POST" action="delete_announcement.php" class="inline-action" onsubmit="return confirm('Delete this announcement?')">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($contentAdminToken) ?>">
                                    <button type="submit" class="action-btn btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="4" style="text-align:center; padding:1.5rem; color: var(--text-muted);">No announcements found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ===== News ===== -->
    <section id="news" class="<?= $tab == 'news' ? 'active' : '' ?>">
        <div class="section-header">
            <h2>News</h2>
            <a href="add_news.php" class="action-btn btn-add">+ Add News</a>
        </div>
        <div class="table-container">
            <div class="table-scroll">
                <table>
                    <thead><tr><th>ID</th><th>Image</th><th>Title</th><th>Date</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php if (isset($news) && $news->num_rows > 0):
                        while($row = $news->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?= $row['id'] ?></strong></td>
                        <td><?php if(!empty($row['image'])): ?><img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>" style="width:40px; height:40px; object-fit:cover; border-radius:6px;"><?php endif; ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= date('M d, Y', strtotime($row['date_posted'])) ?></td>
                        <td>
                            <div class="action-btns">
                                <a href="edit_news.php?id=<?= $row['id'] ?>" class="action-btn btn-edit">Edit</a>
                                <form method="POST" action="delete_news.php" class="inline-action" onsubmit="return confirm('Delete this news?')">
                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($contentAdminToken) ?>">
                                    <button type="submit" class="action-btn btn-delete">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="5" style="text-align:center; padding:1.5rem; color: var(--text-muted);">No news articles found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- ===== Audit Logs ===== -->
    <section id="audit_logs" class="<?= $tab == 'audit_logs' ? 'active' : '' ?>">
        <div class="section-header">
            <h2>Audit Logs</h2>
        </div>
        <div class="report-note">📝 Tracks all admin/staff actions for security and accountability.</div>
        <div class="table-container">
            <div class="table-scroll">
                <table>
                    <thead><tr><th>ID</th><th>User</th><th>Action</th><th>Details</th><th>IP Address</th><th>Date/Time</th></tr></thead>
                    <tbody>
                    <?php if (isset($auditLogs) && $auditLogs->num_rows > 0): 
                        while($log = $auditLogs->fetch_assoc()): ?>
                    <tr>
                        <td><strong>#<?= $log['id'] ?></strong></td>
                        <td><?= htmlspecialchars($log['fullname'] ?? 'System') ?></td>
                        <td><span style="background:#e8ecf1; padding:2px 10px; border-radius:4px; font-size:0.7rem; font-weight:500; color:var(--text-secondary);"><?= htmlspecialchars($log['action']) ?></span></td>
                        <td style="max-width:300px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= htmlspecialchars($log['details'] ?? '') ?></td>
                        <td><?= htmlspecialchars($log['ip_address'] ?? '') ?></td>
                        <td><?= date('M d, Y h:i A', strtotime($log['created_at'])) ?></td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="6" style="text-align:center; padding:1.5rem; color: var(--text-muted);">No audit logs found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main>

<!-- ===== Document Viewer Modal ===== -->
<div id="docModal" class="doc-modal" onclick="if(event.target===this) closeDocViewer()">
    <div class="doc-modal-content">
        <div class="doc-modal-header">
            <h3 id="docModalTitle">Verification Document</h3>
            <div style="display:flex; gap:0.5rem; align-items:center;">
                <a id="docOpenNew" href="#" target="_blank" class="action-btn btn-view" style="text-decoration:none;">Open in new tab</a>
                <button type="button" class="doc-modal-close" onclick="closeDocViewer()" aria-label="Close">&times;</button>
            </div>
        </div>
        <div class="doc-modal-body" id="docModalBody">
            <div style="padding:2rem; text-align:center; color: var(--text-muted);">Loading…</div>
        </div>
    </div>
</div>

<script>
    // Dark mode toggle functionality
    const themeToggle = document.getElementById('themeToggle');
    
    // Check for saved theme preference
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }

    themeToggle.addEventListener('click', function() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme');
        
        if (currentTheme === 'dark') {
            html.removeAttribute('data-theme');
            localStorage.setItem('theme', 'light');
        } else {
            html.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        }
    });

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('active');
    }

    document.querySelectorAll('.sidebar-nav a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('overlay').classList.remove('active');
        });
    });

    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab') || 'aics';
    const adminOnlyTabs = ['locked_accounts', 'reports', 'announcements', 'news', 'audit_logs'];
    const isAdmin = <?= $isAdmin ? 'true' : 'false' ?>;

    if (!isAdmin && adminOnlyTabs.includes(tab)) {
        window.location.href = 'admin_panel.php?tab=aics';
    } else {
        document.querySelectorAll('section').forEach(s => s.classList.remove('active'));
        const activeSection = document.getElementById(tab);
        if (activeSection) {
            activeSection.classList.add('active');
        } else {
            document.getElementById('aics').classList.add('active');
        }
    }

    const pendingAccountSearch = document.getElementById('pendingAccountSearch');
    const pendingAccountDuplicatesOnly = document.getElementById('pendingAccountDuplicatesOnly');
    const pendingAccountFilterCount = document.getElementById('pendingAccountFilterCount');
    const pendingAccountRows = Array.from(document.querySelectorAll('.pending-account-row'));
    const pendingAccountsNoMatch = document.querySelector('.pending-accounts-no-match');

    function filterPendingAccounts() {
        if (!pendingAccountSearch || !pendingAccountDuplicatesOnly) return;

        const searchValue = pendingAccountSearch.value.trim().toLocaleLowerCase();
        let visibleCount = 0;

        pendingAccountRows.forEach((row) => {
            const matchesSearch = !searchValue
                || row.dataset.nameSearch.includes(searchValue)
                || row.dataset.emailSearch.includes(searchValue);
            const matchesDuplicateFilter = !pendingAccountDuplicatesOnly.checked
                || row.dataset.hasNameMatches === '1';
            const isVisible = matchesSearch && matchesDuplicateFilter;

            row.hidden = !isVisible;
            if (isVisible) visibleCount++;
        });

        if (pendingAccountsNoMatch) {
            pendingAccountsNoMatch.hidden = visibleCount > 0;
        }
        if (pendingAccountFilterCount) {
            pendingAccountFilterCount.textContent = `${visibleCount} of ${pendingAccountRows.length} pending account${pendingAccountRows.length === 1 ? '' : 's'} shown`;
        }
    }

    if (pendingAccountSearch && pendingAccountDuplicatesOnly) {
        pendingAccountSearch.addEventListener('input', filterPendingAccounts);
        pendingAccountDuplicatesOnly.addEventListener('change', filterPendingAccounts);
        filterPendingAccounts();
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('overlay').classList.remove('active');
        }
    });

    // ===== Document Viewer =====
    function openDocViewer(userId, fullname) {
        const modal = document.getElementById('docModal');
        const body = document.getElementById('docModalBody');
        const title = document.getElementById('docModalTitle');
        const openNew = document.getElementById('docOpenNew');

        const url = 'view_verification_doc.php?id=' + encodeURIComponent(userId);

        title.textContent = 'ID Document — ' + (fullname || ('User #' + userId));
        openNew.href = url;
        body.innerHTML = '<div style="padding:2rem; text-align:center; color: var(--text-muted);">Loading…</div>';
        modal.classList.add('active');

        fetch(url, { method: 'HEAD', credentials: 'same-origin' })
            .then(res => {
                if (!res.ok) throw new Error('Unable to load document (HTTP ' + res.status + ')');
                const type = res.headers.get('Content-Type') || '';

                if (type.startsWith('image/')) {
                    body.innerHTML = '';
                    const img = document.createElement('img');
                    img.src = url;
                    img.alt = 'Verification document';
                    img.onerror = () => {
                        body.innerHTML = '<div class="doc-error">Failed to load image.</div>';
                    };
                    body.appendChild(img);
                } else if (type === 'application/pdf') {
                    body.innerHTML = '';
                    const iframe = document.createElement('iframe');
                    iframe.src = url;
                    iframe.title = 'Verification PDF';
                    body.appendChild(iframe);
                } else {
                    body.innerHTML = '<div class="doc-error">Unsupported document type.</div>';
                }
            })
            .catch(err => {
                body.innerHTML = '<div class="doc-error">' + err.message + '</div>';
            });
    }

    function closeDocViewer() {
        const modal = document.getElementById('docModal');
        modal.classList.remove('active');
        document.getElementById('docModalBody').innerHTML = '';
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeDocViewer();
        }
    });

    const reportProgramFilter = document.getElementById('reportProgramFilter');
    const reportStatusFilter = document.getElementById('reportStatusFilter');
    const reportDashboardItems = document.querySelectorAll('.kpi-card, .insight-panel, .report-panel');

    function applyReportDashboardFilter() {
        if (!reportProgramFilter || !reportStatusFilter) return;

        const programValue = reportProgramFilter.value;
        const statusValue = reportStatusFilter.value;

        reportDashboardItems.forEach((item) => {
            const itemProgram = item.dataset.program || 'all';
            const itemStatus = item.dataset.status || 'all';
            const matchesProgram = programValue === 'all' || itemProgram === 'all' || itemProgram === programValue;
            const matchesStatus = statusValue === 'all' || itemStatus === 'all' || itemStatus === statusValue;
            item.classList.toggle('is-hidden', !(matchesProgram && matchesStatus));
        });
    }

    if (reportProgramFilter && reportStatusFilter) {
        reportProgramFilter.addEventListener('change', applyReportDashboardFilter);
        reportStatusFilter.addEventListener('change', applyReportDashboardFilter);
        applyReportDashboardFilter();
    }
</script>
</body>
</html>
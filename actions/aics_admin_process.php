<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/generate_ticket.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ====================== CSRF TOKEN REGENERATION ======================
function regenerateCsrfToken() {
    $_SESSION['admin_action_token'] = bin2hex(random_bytes(32));
}

function logAudit($conn, $user_id, $action, $details = '', $ip_address = '') {
    $stmt = $conn->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isss", $user_id, $action, $details, $ip_address);
    $stmt->execute();
    $stmt->close();
}

function buildDefaultAICSSchedule(): array {
    $schedule = new DateTime('tomorrow 09:00:00');
    $dayOfWeek = (int) $schedule->format('N');

    if ($dayOfWeek === 6) {
        $schedule->modify('+2 days');
    } elseif ($dayOfWeek === 7) {
        $schedule->modify('+1 day');
    }

    return [
        'date' => $schedule->format('Y-m-d'),
        'time' => $schedule->format('H:i:s'),
        'location' => 'MSWD Office, Cainta',
    ];
}

function sendApprovalEmail($recipientEmail, $recipientName, $ticketNumber, $pdfFilePath) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'edwardzabaldan4@gmail.com';
        $mail->Password = 'afewakfjqmvurxzw'; // TODO: Move to config file
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('edwardzabaldan4@gmail.com', 'MSWD System');
        $mail->addAddress($recipientEmail, $recipientName);

        $mail->Subject = 'AICS Application Approved - Ticket ' . $ticketNumber;
        $mail->Body = "Dear $recipientName,\n\nYour AICS application has been approved. Your ticket number is $ticketNumber. Please download the attached ticket and present it during verification.\n\nRegards,\nMSWD Team";
        $mail->addAttachment($pdfFilePath);
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('AICS approval email failed: ' . $e->getMessage());
        return false;
    }
}

function approveAICSApplication($conn, $application_id, $admin_notes = '', $schedule_date = '', $schedule_time = '', $schedule_location = '') {
    // Fetch application and user details
    $stmt = $conn->prepare("SELECT a.user_id, a.ticket_number, a.schedule_date, a.schedule_time, a.schedule_location, u.email, u.fullname, ap.first_name, ap.middle_name, ap.last_name
        FROM aics_applications a
        JOIN users u ON a.user_id = u.id
        LEFT JOIN aics_applicants ap ON ap.application_id = a.id
        WHERE a.id = ?");
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $app = $result->fetch_assoc();
    $stmt->close();

    if (!$app) {
        return ['success' => false, 'message' => 'Application not found.'];
    }

    // ✅ Check if application is already processed
    $checkStmt = $conn->prepare("SELECT status FROM aics_applications WHERE id = ?");
    $checkStmt->bind_param("i", $application_id);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $currentStatus = $checkResult->fetch_assoc();
    $checkStmt->close();

    if ($currentStatus && $currentStatus['status'] !== 'pending') {
        return ['success' => false, 'message' => 'Application has already been processed.'];
    }

    $ticket_number = $app['ticket_number'];
    $applicantNameParts = array_filter([$app['first_name'] ?? '', $app['middle_name'] ?? '', $app['last_name'] ?? '']);
    $applicantName = trim(implode(' ', $applicantNameParts));
    if (empty($applicantName)) {
        $applicantName = $app['fullname'] ?? 'Applicant';
    }

    if (empty($ticket_number)) {
        $ticket_number = "AICS-" . date('Y') . "-" . str_pad($application_id, 6, '0', STR_PAD_LEFT);
    }
    
    $defaultSchedule = buildDefaultAICSSchedule();
    $schedule_date = trim($schedule_date) !== '' ? trim($schedule_date) : ($app['schedule_date'] ?: $defaultSchedule['date']);
    $schedule_time = trim($schedule_time) !== '' ? trim($schedule_time) : ($app['schedule_time'] ?: $defaultSchedule['time']);
    $schedule_location = trim($schedule_location) !== '' ? trim($schedule_location) : ($app['schedule_location'] ?: $defaultSchedule['location']);

    $updateStmt = $conn->prepare("UPDATE aics_applications SET status = 'approved', ticket_number = ?, admin_notes = ?, schedule_date = ?, schedule_time = ?, schedule_location = ? WHERE id = ?");
    $updateStmt->bind_param("sssssi", $ticket_number, $admin_notes, $schedule_date, $schedule_time, $schedule_location, $application_id);
    if (!$updateStmt->execute()) {
        $error = $updateStmt->error;
        $updateStmt->close();
        return ['success' => false, 'message' => 'Failed to update application: ' . $error];
    }
    $updateStmt->close();

    $cooldownUntil = (new DateTime())->modify('+2 months')->format('Y-m-d H:i:s');
    $cooldownStmt = $conn->prepare("UPDATE users SET aics_cooldown_until = ? WHERE id = ?");
    $cooldownStmt->bind_param("si", $cooldownUntil, $app['user_id']);
    $cooldownStmt->execute();
    $cooldownStmt->close();

    // Generate ticket PDF
    $applicantName = trim($app['first_name'] . ' ' . $app['last_name']);
    $pdfFile = generateTicketPDF($applicantName, $ticket_number, $schedule_date, $schedule_time, $schedule_location);

    // Send email
    if (!empty($app['email']) && filter_var($app['email'], FILTER_VALIDATE_EMAIL)) {
        sendApprovalEmail($app['email'], $applicantName, $ticket_number, $pdfFile);
    }

    // create notification
    $targetUrl = "aics_view.php?id={$application_id}";
    $notifStmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, type, target_url) VALUES (?, ?, ?, ?, ?)");
    $statusText = 'approved';
    $notifTitle = "AICS Application APPROVED";
    $notifMessage = "Your AICS application (#{$application_id}) has been approved. Ticket: {$ticket_number}. Schedule: " . date('M j, Y', strtotime($schedule_date)) . ' ' . date('g:i A', strtotime($schedule_time)) . '. You may submit a new AICS request again after ' . date('M j, Y', strtotime($cooldownUntil)) . '.';
    $notifStmt->bind_param("issss", $app['user_id'], $notifTitle, $notifMessage, $statusText, $targetUrl);
    $notifStmt->execute();
    $notifStmt->close();

    return ['success' => true, 'ticket_number' => $ticket_number, 'pdf' => $pdfFile];
}

function declineAICSApplication($conn, $application_id, $admin_notes = '') {
    $stmt = $conn->prepare("SELECT user_id, status FROM aics_applications WHERE id = ?");
    $stmt->bind_param("i", $application_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $app = $result->fetch_assoc();
    $stmt->close();

    if (!$app) {
        return ['success' => false, 'message' => 'Application not found.'];
    }

    // ✅ Check if application is already processed
    if ($app['status'] !== 'pending') {
        return ['success' => false, 'message' => 'Application has already been processed.'];
    }

    $updateStmt = $conn->prepare("UPDATE aics_applications SET status = 'declined', admin_notes = ? WHERE id = ?");
    $updateStmt->bind_param("si", $admin_notes, $application_id);
    if (!$updateStmt->execute()) {
        $error = $updateStmt->error;
        $updateStmt->close();
        return ['success' => false, 'message' => 'Failed to update application: ' . $error];
    }
    $updateStmt->close();

    $targetUrl = "aics_view.php?id={$application_id}";
    $notifStmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, type, target_url) VALUES (?, ?, ?, ?, ?)");
    $statusText = 'declined';
    $notifTitle = "AICS Application DECLINED";
    $notifMessage = "Your AICS application (#{$application_id}) has been declined.";
    $notifStmt->bind_param("issss", $app['user_id'], $notifTitle, $notifMessage, $statusText, $targetUrl);
    $notifStmt->execute();
    $notifStmt->close();

    return ['success' => true];
}

// Check if user is logged in and is admin OR staff
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $conn->prepare("SELECT is_admin, is_staff FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || (!$user['is_admin'] && !$user['is_staff'])) {
    echo '<p>Access denied.</p>';
    exit;
}

if (empty($_SESSION['admin_action_token'])) {
    $_SESSION['admin_action_token'] = bin2hex(random_bytes(32));
}

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $application_id = intval($_POST['application_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $admin_notes = trim($_POST['admin_notes'] ?? '');
    $schedule_date = trim($_POST['schedule_date'] ?? '');
    $schedule_time = trim($_POST['schedule_time'] ?? '');
    $schedule_location = trim($_POST['schedule_location'] ?? '');
    $token = $_POST['csrf_token'] ?? '';

    // ✅ CSRF validation - Check if token exists AND matches
    if (empty($token) || !hash_equals($_SESSION['admin_action_token'], $token)) {
        // Regenerate token on failure
        regenerateCsrfToken();
        header('Location: admin_panel.php?tab=aics&error=' . urlencode('Security token mismatch. Please try again.'));
        exit;
    }

    if (!$application_id) {
        header('Location: admin_panel.php?tab=aics&error=' . urlencode('Invalid application ID.'));
        exit;
    }

    if ($action === 'approve') {
        $result = approveAICSApplication($conn, $application_id, $admin_notes, $schedule_date, $schedule_time, $schedule_location);
        if ($result['success']) {
            // Log the approval
            logAudit($conn, $_SESSION['user_id'], 'AICS_APPROVE', 'Application #' . $application_id . ' approved. Ticket: ' . ($result['ticket_number'] ?? ''), $_SERVER['REMOTE_ADDR']);
            
            // ✅ REGENERATE CSRF TOKEN AFTER SUCCESSFUL ACTION
            regenerateCsrfToken();
            
            header('Location: admin_panel.php?tab=aics&success=' . urlencode('Application #' . $application_id . ' approved successfully. Ticket: ' . $result['ticket_number']));
            exit;
        } else {
            // ✅ Regenerate token on error too
            regenerateCsrfToken();
            header('Location: admin_panel.php?tab=aics&error=' . urlencode($result['message']));
            exit;
        }
    } elseif ($action === 'decline') {
        $result = declineAICSApplication($conn, $application_id, $admin_notes);
        if ($result['success']) {
            // Log the decline
            logAudit($conn, $_SESSION['user_id'], 'AICS_DECLINE', 'Application #' . $application_id . ' declined.', $_SERVER['REMOTE_ADDR']);
            
            // ✅ REGENERATE CSRF TOKEN AFTER SUCCESSFUL ACTION
            regenerateCsrfToken();
            
            header('Location: admin_panel.php?tab=aics&success=' . urlencode('Application #' . $application_id . ' declined.'));
            exit;
        } else {
            // ✅ Regenerate token on error too
            regenerateCsrfToken();
            header('Location: admin_panel.php?tab=aics&error=' . urlencode($result['message']));
            exit;
        }
    }

    // Invalid action
    regenerateCsrfToken();
    header('Location: admin_panel.php?tab=aics&error=' . urlencode('Invalid action.'));
    exit;
}

header('Location: admin_panel.php?tab=aics&error=' . urlencode('Invalid request.'));
exit;
?>

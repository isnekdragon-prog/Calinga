<?php
session_start();

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/mail_helper.php';
require_once __DIR__ . '/generate_solo_parent_ticket.php';

// ====================== CSRF TOKEN REGENERATION ======================
function regenerateCsrfToken(): void
{
    $_SESSION['admin_action_token'] = bin2hex(random_bytes(32));
}

// ====================== AUDIT LOGGING ======================
function logAudit($conn, $user_id, $action, $details = '', $ip_address = '')
{
    $stmt = $conn->prepare("
        INSERT INTO audit_logs
            (user_id, action, details, ip_address, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param(
        "isss",
        $user_id,
        $action,
        $details,
        $ip_address
    );

    $stmt->execute();
    $stmt->close();
}

// ====================== CHECK LOGIN ======================
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ====================== CHECK ADMIN OR STAFF ======================
$stmt = $conn->prepare("
    SELECT is_admin, is_staff
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();

if (!$user || (!$user['is_admin'] && !$user['is_staff'])) {
    http_response_code(403);
    exit("Access denied.");
}

// ====================== INITIALIZE CSRF TOKEN ======================
if (empty($_SESSION['admin_action_token'])) {
    regenerateCsrfToken();
}

// ====================== HANDLE POST ACTION ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $application_id = intval($_POST['application_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $admin_notes = trim($_POST['admin_notes'] ?? '');
    $token = $_POST['csrf_token'] ?? '';

    // ====================== VALIDATE CSRF TOKEN ======================
    if (
        empty($token) ||
        !hash_equals($_SESSION['admin_action_token'], $token)
    ) {
        regenerateCsrfToken();

        header(
            "Location: admin_panel.php?tab=solo_parent&error="
            . urlencode("Security token mismatch. Please try again.")
        );
        exit;
    }

    if ($application_id <= 0) {
        header(
            "Location: admin_panel.php?tab=solo_parent&error="
            . urlencode("Invalid application ID.")
        );
        exit;
    }

    // ====================== FETCH APPLICATION ======================
    $checkStmt = $conn->prepare("
        SELECT
            sp.status,
            sp.user_id,
            u.fullname,
            u.email AS account_email,
            p.first_name,
            p.middle_name,
            p.last_name,
            p.name_extension,
            p.email AS personal_email
        FROM solo_parent_applications sp
        LEFT JOIN users u ON u.id = sp.user_id
        LEFT JOIN solo_parent_personal p ON p.application_id = sp.id
        WHERE sp.id = ?
    ");

    $checkStmt->bind_param("i", $application_id);
    $checkStmt->execute();

    $checkResult = $checkStmt->get_result();
    $currentStatus = $checkResult->fetch_assoc();

    $checkStmt->close();

    if (!$currentStatus) {
        regenerateCsrfToken();

        header(
            "Location: admin_panel.php?tab=solo_parent&error="
            . urlencode("Application not found.")
        );
        exit;
    }

    if ($currentStatus['status'] !== 'pending') {
        regenerateCsrfToken();

        header(
            "Location: admin_panel.php?tab=solo_parent&error="
            . urlencode("Application has already been processed.")
        );
        exit;
    }

    // ====================== VALIDATE ACTION ======================
    if ($action === 'approve') {
        $status = 'approved';
    } elseif ($action === 'decline') {
        $status = 'declined';
    } else {
        regenerateCsrfToken();

        header(
            "Location: admin_panel.php?tab=solo_parent&error="
            . urlencode("Invalid action.")
        );
        exit;
    }

    // ====================== GENERATE TICKET NUMBER ======================
    $ticketNumber = $action === 'approve'
        ? 'SP-' . date('Y') . '-'
            . str_pad((string) $application_id, 6, '0', STR_PAD_LEFT)
        : null;

    // ====================== UPDATE APPLICATION STATUS ======================
    if ($action === 'approve') {

        $stmt = $conn->prepare("
            UPDATE solo_parent_applications
            SET
                status = ?,
                admin_notes = ?,
                ticket_number = ?,
                updated_at = NOW()
            WHERE id = ?
              AND status = 'pending'
        ");

        $stmt->bind_param(
            "sssi",
            $status,
            $admin_notes,
            $ticketNumber,
            $application_id
        );

    } else {

        $stmt = $conn->prepare("
            UPDATE solo_parent_applications
            SET
                status = ?,
                admin_notes = ?,
                updated_at = NOW()
            WHERE id = ?
              AND status = 'pending'
        ");

        $stmt->bind_param(
            "ssi",
            $status,
            $admin_notes,
            $application_id
        );
    }

    if (!$stmt->execute()) {
        $stmt->close();

        regenerateCsrfToken();

        header(
            "Location: admin_panel.php?tab=solo_parent&error="
            . urlencode("Database update failed.")
        );
        exit;
    }

    $updated = $stmt->affected_rows === 1;
    $stmt->close();

    if (!$updated) {
        regenerateCsrfToken();

        header(
            "Location: admin_panel.php?tab=solo_parent&error="
            . urlencode("Application has already been processed.")
        );
        exit;
    }

    // ====================== CREATE NOTIFICATION ======================
    if (!empty($currentStatus['user_id'])) {

        $statusText = strtoupper($status);
        $title = "Solo Parent Application " . $statusText;

        $message = "Your Solo Parent application (#"
            . $application_id . ") has been "
            . $statusText . ".";

        if ($admin_notes !== '') {
            $message .= " Admin notes: " . $admin_notes;
        }

        $targetUrl = "solo_parent_view.php?id=" . $application_id;

        $notif = $conn->prepare("
            INSERT INTO notifications
                (user_id, title, message, type, target_url)
            VALUES (?, ?, ?, ?, ?)
        ");

        $notif->bind_param(
            "issss",
            $currentStatus['user_id'],
            $title,
            $message,
            $status,
            $targetUrl
        );

        $notif->execute();
        $notif->close();
    }

    // ====================== SEND APPROVAL EMAIL ======================
    $emailSent = false;

    if ($action === 'approve') {

        // Prefer personal email; fall back to account email.
        $personalEmail = trim(
            (string) ($currentStatus['personal_email'] ?? '')
        );

        $accountEmail = trim(
            (string) ($currentStatus['account_email'] ?? '')
        );

        $recipientEmail = filter_var(
            $personalEmail,
            FILTER_VALIDATE_EMAIL
        ) ? $personalEmail : $accountEmail;

        // Build applicant's full name.
        $applicantName = trim(implode(' ', array_filter([
            $currentStatus['first_name'] ?? '',
            $currentStatus['middle_name'] ?? '',
            $currentStatus['last_name'] ?? '',
            $currentStatus['name_extension'] ?? '',
        ], function ($part) {
            return trim((string) $part) !== '';
        })));

        if ($applicantName === '') {
            $applicantName = trim(
                (string) ($currentStatus['fullname'] ?? '')
            );
        }

        if ($applicantName === '') {
            $applicantName = 'Applicant';
        }

        if (filter_var($recipientEmail, FILTER_VALIDATE_EMAIL)) {

            $ticketPdf = null;

            try {
                // Generate the PDF ticket.
                $ticketPdf = generateSoloParentTicketPDF(
                    $applicantName,
                    $ticketNumber
                );

                if (
                    !is_string($ticketPdf) ||
                    !is_file($ticketPdf) ||
                    !is_readable($ticketPdf)
                ) {
                    throw new RuntimeException(
                        "Generated ticket PDF is missing or unreadable."
                    );
                }

                // Escape values used in the HTML email.
                $safeName = htmlspecialchars(
                    $applicantName,
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                );

                $safeTicketNumber = htmlspecialchars(
                    $ticketNumber,
                    ENT_QUOTES | ENT_SUBSTITUTE,
                    'UTF-8'
                );

                // Use the mail configuration from mail_helper.php.
                $mail = createMailer();

                $mail->addAddress(
                    $recipientEmail,
                    $applicantName
                );

                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';

                $mail->Subject =
                    'Solo Parent Application Approved - Ticket '
                    . $ticketNumber;

                $mail->Body =
                    '<p>Dear ' . $safeName . ',</p>'
                    . '<p>Your Solo Parent application has been approved.</p>'
                    . '<p>Your ticket number is <strong>'
                    . $safeTicketNumber
                    . '</strong>. Please see the attached ticket '
                    . 'for your records.</p>'
                    . '<p>Regards,<br>CALINGA MSWD</p>';

                $mail->AltBody =
                    "Dear " . $applicantName . ",\n\n"
                    . "Your Solo Parent application has been approved.\n"
                    . "Your ticket number is " . $ticketNumber . ".\n"
                    . "Please see the attached ticket for your records.\n\n"
                    . "Regards,\nCALINGA MSWD";

                // Attach the generated PDF.
                $mail->addAttachment(
                    $ticketPdf,
                    'Solo_Parent_Ticket_' . $ticketNumber . '.pdf'
                );

                // Send the email.
                $mail->send();

                $emailSent = true;

            } catch (Throwable $e) {

                error_log(
                    'Solo Parent approval email failed for application #'
                    . $application_id
                    . ': '
                    . $e->getMessage()
                );

            } finally {

                // Remove the temporary PDF only if your generator
                // creates a temporary file specifically for this email.
                //
                // Uncomment these lines ONLY if the function returns
                // a temporary file that should be deleted afterward.
                //
                // if ($ticketPdf && is_file($ticketPdf)) {
                //     unlink($ticketPdf);
                // }
            }

        } else {

            error_log(
                'Solo Parent approval email skipped for application #'
                . $application_id
                . ': No valid recipient email address.'
            );
        }
    }

    // ====================== AUDIT LOG ======================
    logAudit(
        $conn,
        $_SESSION['user_id'],
        'SOLO_PARENT_' . strtoupper($status),
        'Application #' . $application_id
            . ' ' . $status
            . '. Notes: ' . $admin_notes,
        $_SERVER['REMOTE_ADDR'] ?? ''
    );

    // ====================== REGENERATE CSRF TOKEN ======================
    regenerateCsrfToken();

    // ====================== REDIRECT WITH RESULT ======================
    if ($action === 'approve') {

        $successMessage = $emailSent
            ? 'Application approved and ticket emailed successfully.'
            : 'Application approved, but the ticket email could not be sent. Check the server error log.';

    } else {

        $successMessage = 'Application declined successfully.';
    }

    header(
        "Location: admin_panel.php?tab=solo_parent&success="
        . urlencode($successMessage)
    );

    exit;
}

// ====================== INVALID REQUEST ======================
regenerateCsrfToken();

header(
    "Location: admin_panel.php?tab=solo_parent&error="
    . urlencode("Invalid request.")
);

exit;
?>
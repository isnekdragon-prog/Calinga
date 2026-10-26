<?php
require_once __DIR__ . '/../vendor/setasign/fpdf/fpdf.php';

function generateTicketPDF($name, $ticket_number, $scheduleDate = null, $scheduleTime = null, $scheduleLocation = 'MSWD Office, Cainta'){
    // Create a small rectangular ticket size
    $pdf = new FPDF('L', 'mm', [80, 140]);
    $pdf->SetMargins(5, 5, 5);
    $pdf->AddPage();
    $pdf->SetAutoPageBreak(false);

    $caintaLogo = __DIR__ . '/../imgs/cainta.png';
    $dswdLogo = __DIR__ . '/../imgs/DSWD.png';

    if (is_file($caintaLogo)) {
        $pdf->Image($caintaLogo, 8, 6, 14, 14);
    }
    if (is_file($dswdLogo)) {
        $pdf->Image($dswdLogo, 118, 6, 14, 14);
    }

    $pdf->SetFont('Arial', 'B', 9);
    $pdf->SetXY(26, 7);
    $pdf->Cell(88, 3.5, 'MUNICIPAL SOCIAL WELFARE AND', 0, 1, 'C');
    $pdf->SetX(26);
    $pdf->Cell(88, 3.5, 'DEVELOPMENT OFFICE', 0, 1, 'C');

    $pdf->SetFont('Arial', '', 7);
    $pdf->SetX(26);
    $pdf->Cell(88, 3.5, 'Cainta, Rizal', 0, 1, 'C');

    $pdf->Ln(0.5);
    $pdf->SetDrawColor(30, 53, 128);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(8, 22, 132, 22);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(30, 53, 128);
    $pdf->SetY(23.5);
    $pdf->Cell(0, 5, 'AICS APPLICATION TICKET', 0, 1, 'C');

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFillColor(241, 245, 255);
    $pdf->SetDrawColor(200, 210, 230);
    $pdf->Rect(8, 31, 56, 14, 'DF');

    $pdf->SetXY(11, 34);
    $pdf->SetFont('Arial', '', 7);
    $pdf->Cell(0, 3, 'Ticket Number', 0, 1, 'L');
    $pdf->SetX(11);
    $pdf->SetFont('Arial', 'B', 9);
    $pdf->Cell(0, 4, $ticket_number, 0, 1, 'L');

    $detailY = 31;
    $labelWidth = 17;
    $valueWidth = 40;
    $lineHeight = 4.5;
    $formattedScheduleDate = !empty($scheduleDate) ? date('F j, Y', strtotime($scheduleDate)) : 'To be announced';
    $formattedScheduleTime = !empty($scheduleTime) ? date('g:i A', strtotime($scheduleTime)) : 'To be announced';

    $details = [
        'Name' => $name,
        'Status' => 'APPROVED',
        'Issued' => date('F j, Y'),
        'Sched.' => $formattedScheduleDate,
        'Time' => $formattedScheduleTime,
    ];

    foreach ($details as $label => $value) {
        $pdf->SetXY(72, $detailY);
        $pdf->SetFont('Arial', 'B', 7.5);
        $pdf->Cell($labelWidth, $lineHeight, $label . ':', 0, 0, 'L');
        $pdf->SetFont('Arial', '', 7.5);
        $pdf->MultiCell($valueWidth, 4, $value, 0, 'L');
        $detailY += 6;
    }

    $pdf->SetDrawColor(220, 220, 220);
    $pdf->Line(8, 56, 132, 56);

    $pdf->SetXY(10, 58);
    $pdf->SetFont('Arial', 'B', 7);
    $pdf->Cell(120, 3.2, 'Location: ' . $scheduleLocation, 0, 1, 'C');
    $pdf->SetX(10);
    $pdf->SetFont('Arial', '', 6.8);
    $pdf->MultiCell(120, 3.3, 'Please present this ticket during verification at the scheduled date and time. Bring a valid ID and any required supporting documents.', 0, 'C');

    $pdf->Line(8, 71, 132, 71);

    $pdf->SetXY(10, 73);
    $pdf->SetFont('Arial', 'I', 6.5);
    $pdf->Cell(120, 3, 'Generated: ' . date('Y-m-d h:i A') . '   |   System-generated ticket', 0, 0, 'C');

    $ticketsDir = __DIR__ . '/tickets';
    if (!is_dir($ticketsDir)) {
        mkdir($ticketsDir, 0755, true);
    }

    $filePath = $ticketsDir . '/' . $ticket_number . '.pdf';
    $pdf->Output('F', $filePath);
    return $filePath;
}

?>

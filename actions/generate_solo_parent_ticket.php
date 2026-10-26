<?php
require_once __DIR__ . '/../vendor/setasign/fpdf/fpdf.php';

function generateSoloParentTicketPDF(string $name, string $ticketNumber): string
{
    $pdf = new FPDF('L', 'mm', [80, 140]);
    $pdf->SetMargins(8, 7, 8);
    $pdf->SetAutoPageBreak(false);
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->SetTextColor(35, 65, 85);
    $pdf->Cell(0, 7, 'SOLO PARENT APPLICATION TICKET', 0, 1, 'C');
    $pdf->SetDrawColor(45, 66, 82);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(8, 17, 132, 17);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('Arial', '', 9);
    $pdf->SetXY(12, 24);
    $pdf->Cell(30, 6, 'Ticket Number:', 0, 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(0, 6, $ticketNumber, 0, 1);

    $pdf->SetX(12);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(30, 7, 'Applicant:', 0, 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->MultiCell(0, 7, iconv('UTF-8', 'Windows-1252//TRANSLIT', $name) ?: $name, 0, 'L');

    $pdf->SetX(12);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(30, 7, 'Status:', 0, 0);
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->SetTextColor(33, 120, 75);
    $pdf->Cell(0, 7, 'APPROVED', 0, 1);

    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetX(12);
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(30, 7, 'Issued:', 0, 0);
    $pdf->Cell(0, 7, date('F j, Y'), 0, 1);

    $pdf->SetDrawColor(220, 220, 220);
    $pdf->Line(8, 61, 132, 61);
    $pdf->SetFont('Arial', '', 8);
    $pdf->SetXY(10, 64);
    $pdf->MultiCell(120, 4, 'Please present this ticket to the Municipal Social Welfare and Development Office when following up on your Solo Parent application.', 0, 'C');

    $ticketsDirectory = __DIR__ . '/tickets';
    if (!is_dir($ticketsDirectory) && !mkdir($ticketsDirectory, 0755, true) && !is_dir($ticketsDirectory)) {
        throw new RuntimeException('Unable to create Solo Parent tickets directory.');
    }

    $filePath = $ticketsDirectory . '/' . $ticketNumber . '.pdf';
    $pdf->Output('F', $filePath);

    return $filePath;
}

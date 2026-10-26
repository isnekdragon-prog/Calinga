<?php

require __DIR__ . '/vendor/autoload.php';

use setasign\Fpdi\Fpdi;

// only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

// sanitize incoming values
$lastname   = trim(filter_input(INPUT_POST, 'lastname',   FILTER_SANITIZE_STRING) ?? '');
$firstname  = trim(filter_input(INPUT_POST, 'firstname',  FILTER_SANITIZE_STRING) ?? '');
$middlename = trim(filter_input(INPUT_POST, 'middlename', FILTER_SANITIZE_STRING) ?? '');
$barangay   = trim(filter_input(INPUT_POST, 'barangay',   FILTER_SANITIZE_STRING) ?? '');
$city       = trim(filter_input(INPUT_POST, 'city',       FILTER_SANITIZE_STRING) ?? '');
$age        = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 0, 'max_range' => 150]
]);
if ($age === false) {
    $age = '';
}

// instantiate FPDI
try {
    $pdf = new FPDI();
} catch (Throwable $e) {
    http_response_code(500);
    exit('PDF library initialization failed: ' . $e->getMessage());
}

$pdf->AddPage();

// load template file
$templateFile = __DIR__ . '/Calinga_form.pdf';
if (!is_file($templateFile)) {
    http_response_code(500);
    exit('Template PDF not found.');
}

$pageCount = $pdf->setSourceFile($templateFile);
if ($pageCount < 1) {
    http_response_code(500);
    exit('Unable to read template PDF.');
}

$template = $pdf->importPage(1);
$pdf->useTemplate($template);

// option: show rulers when ?debug=1 is passed
if (!empty($_GET['debug'])) {
    $pdf->SetFont('Arial', '', 8);
    // horizontal ruler
    for ($x = 0; $x <= 210; $x += 10) {
        $pdf->Text($x, 10, (string) $x);
    }
    // vertical ruler
    for ($y = 0; $y <= 297; $y += 10) {
        $pdf->Text(5, $y, (string) $y);
    }
}

// font for actual data
$pdf->SetFont('Arial','',10);

// write values at known coordinates (adjusted to match the PDF template)
// coordinates were calibrated using the debug ruler (?debug=1)
$pdf->SetXY(25, 48);
$pdf->Cell(45, 7, $lastname, 0, 0);
$pdf->SetXY(75, 48);
$pdf->Cell(45, 7, $firstname, 0, 0);
$pdf->SetXY(125, 48);
$pdf->Cell(45, 7, $middlename, 0, 0);

$pdf->SetXY(25, 58);
$pdf->Cell(60, 7, $barangay, 0, 0);
$pdf->SetXY(90, 58);
$pdf->Cell(50, 7, $city, 0, 0);

if ($age !== '') {
    // age field on template sits lower/right of the name block
    $pdf->SetXY(130, 74);
    $pdf->Cell(20, 7, (string)$age, 0, 0);
}

// send to browser as inline PDF
$pdf->Output('I', 'Calinga_form.pdf');

?>
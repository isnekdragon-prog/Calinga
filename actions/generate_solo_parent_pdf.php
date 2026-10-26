<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/db.php';

use setasign\Fpdi\Fpdi;

// Check if user is logged in and is admin or staff
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Access denied');
}

$stmt = $conn->prepare("SELECT is_admin, is_staff FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || (!$user['is_admin'] && !$user['is_staff'])) {
    http_response_code(403);
    exit('Access denied');
}

function calculateAgeFromBirthdate(string $birthdate): ?int
{
    $birthdate = trim($birthdate);
    if ($birthdate === '' || $birthdate === '0000-00-00') {
        return null;
    }

    try {
        $dob = new DateTimeImmutable($birthdate);
        $today = new DateTimeImmutable('today');
    } catch (Exception $e) {
        return null;
    }

    if ($dob > $today) {
        return null;
    }

    return (int) $dob->diff($today)->y;
}

$application_id = intval($_GET['id'] ?? 0);
if (!$application_id) {
    http_response_code(400);
    exit('Invalid application ID');
}

$stmt = $conn->prepare("
    SELECT
        a.*, u.fullname AS user_name, u.email AS user_email,
        p.last_name, p.first_name, p.middle_name, p.name_extension, p.birthdate,
        NULLIF(NULLIF(TRIM(p.place_of_birth), ''), '0') AS place_of_birth,
        p.age, p.gender, p.civil_status, p.education, p.religion, p.occupation, p.monthly_income,
        p.employer_address, p.email AS personal_email, p.phone AS personal_phone,
        addr.house_no, addr.barangay, addr.city_municipality, addr.province, addr.region,
        m.philhealth_member, m.philhealth_number, m.philhealth_membership_category, m.philhealth_dependent,
        m.philhealth_dependent_name, m.philhealth_dependent_number, m.philhealth_relationship,
        ec.name AS emergency_contact_name, ec.address AS emergency_contact_address, ec.contact_number AS emergency_contact_number,
        addi.housing_status, addi.family_income, addi.solo_parent_classification, addi.solo_parent_needs,
        addi.family_resources, addi.hear_about_program, addi.skills_hobbies, addi.solo_parent_id_purpose,
        s.spouse_name, s.spouse_address
    FROM solo_parent_applications a
    LEFT JOIN users u ON a.user_id = u.id
    LEFT JOIN solo_parent_personal p ON p.application_id = a.id
    LEFT JOIN solo_parent_address addr ON addr.application_id = a.id
    LEFT JOIN solo_parent_membership m ON m.application_id = a.id
    LEFT JOIN solo_parent_emergency_contact ec ON ec.application_id = a.id
    LEFT JOIN solo_parent_additional_info addi ON addi.application_id = a.id
    LEFT JOIN solo_parent_spouse s ON s.application_id = a.id
    WHERE a.id = ?
");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$app = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$app) {
    http_response_code(404);
    exit('Application not found');
}

if ((int)($app['age'] ?? 0) <= 0) {
    $calculatedAge = calculateAgeFromBirthdate($app['birthdate'] ?? '');
    if ($calculatedAge !== null) {
        $app['age'] = $calculatedAge;
    }
}

// Fixed family members query
$stmt = $conn->prepare("
    SELECT name, birthdate, relationship, age, educational_attainment, occupation, monthly_income
    FROM solo_parent_family_members
    WHERE application_id = ?
");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$result = $stmt->get_result();
$family_members = [];
while ($row = $result->fetch_assoc()) {
    if ((int)($row['age'] ?? 0) <= 0) {
        $calculatedMemberAge = calculateAgeFromBirthdate($row['birthdate'] ?? '');
        if ($calculatedMemberAge !== null) {
            $row['age'] = $calculatedMemberAge;
        }
    }
    $family_members[] = $row;
}
$stmt->close();

function textValue($value): string
{
    return trim((string) $value);
}

function dateValue($value, string $format = 'm/d/Y'): string
{
    $value = trim((string) $value);
    if ($value === '' || $value === '0000-00-00') {
        return '';
    }

    $timestamp = strtotime($value);
    return $timestamp ? date($format, $timestamp) : $value;
}

function moneyValue($value): string
{
    return number_format((float) $value, 2);
}

function yn($value): string
{
    return !empty($value) ? 'Yes' : 'No';
}

function ptToMm(float $pt): float
{
    return $pt * 25.4 / 72;
}

function drawText(Fpdi $pdf, float $x, float $y, float $w, string $text, int $size = 9, string $style = ''): void
{
    $text = textValue($text);
    if ($text === '') {
        return;
    }

    $pdf->SetFont('Arial', $style, $size);
    $pdf->SetXY($x, $y);
    $pdf->MultiCell($w, max(3.8, $size * 0.45), $text, 0, 'L');
}

function drawMark(Fpdi $pdf, float $x, float $y, string $mark, int $size = 10): void
{
    $pdf->SetFont('Arial', 'B', $size);
    $pdf->SetXY($x, $y);
    $pdf->Cell(4, 4, $mark, 0, 0, 'C');
}

function coverTemplateMark(Fpdi $pdf, float $x, float $y, float $width, float $height): void
{
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Rect($x, $y, $width, $height, 'F');
}

$fullName = trim(($app['first_name'] ?? '') . ' ' . ($app['middle_name'] ?? '') . ' ' . ($app['last_name'] ?? '') . ' ' . ($app['name_extension'] ?? ''));
$address = trim(($app['house_no'] ?? '') . ' ' . ($app['barangay'] ?? '') . ', ' . ($app['city_municipality'] ?? '') . ', ' . ($app['province'] ?? ''));
$spouseAddress = textValue($app['spouse_address'] ?? '');
$emergencyAddress = textValue($app['emergency_contact_address'] ?? '');

$templateFile = __DIR__ . '/../solo_parent_form_new.pdf';
$pdf = new Fpdi();

if (is_file($templateFile)) {
    $pageCount = $pdf->setSourceFile($templateFile);
    $templateId = $pdf->importPage(1);
    $templateSize = $pdf->getTemplateSize($templateId);
    $pageWidth = $templateSize['width'];
    $pageHeight = $templateSize['height'];

    $pdf->AddPage($templateSize['orientation'], [$pageWidth, $pageHeight]);
    $pdf->useTemplate($templateId);

    $pdf->SetTextColor(0, 0, 0);

    // Main identity fields
    drawText($pdf, 37, 57, 72, $fullName, 11);
    drawText($pdf, 126, 57, 12, textValue($app['age'] ?? ''), 11);
    drawText($pdf, 143, 57, 25, textValue($app['gender'] ?? ''), 11);
    drawText($pdf, 48, 61.7, 56, dateValue($app['birthdate'] ?? ''), 11);
    drawText($pdf, 147, 61.7, 42, textValue($app['place_of_birth'] ?? ''), 11);
    drawText($pdf, 41, 65.8, 150, $address, 11);
    drawText($pdf, 45, 71, 23, textValue($app['civil_status'] ?? ''), 11);
    drawText($pdf, 120, 71, 40, textValue($app['education'] ?? ''), 7);
    drawText($pdf, 161, 71, 35, textValue($app['religion'] ?? ''), 10);
    drawText($pdf, 47, 76, 86, textValue($app['occupation'] ?? ''), 11);
    // Remove the template's preprinted "Specified" label beside the income field.
    coverTemplateMark($pdf, 174, 75.5, 20, 4.5);
    drawText($pdf, 158, 76, 30, moneyValue($app['monthly_income'] ?? 0), 11);
    drawText($pdf, 78, 80.2, 110, textValue($app['employer_address'] ?? ''), 11);
    drawText($pdf, 50, 85, 22, moneyValue($app['family_income'] ?? 0), 11);
    drawText($pdf, 92, 85, 28, textValue($app['personal_phone'] ?? ''), 11);
    // The form template has a stray printed X in the email field.
    coverTemplateMark($pdf, 161.5, 83.5, 5.5, 3.5);
    drawText($pdf, 144, 86, 52, textValue($app['personal_email'] ?? ''), 9);
    drawText($pdf, 70, 90, 53, textValue($app['spouse_name'] ?? ''), 11);
    drawText($pdf, 142, 91, 55, $spouseAddress, 9);

    // Housing status
    $housing = strtolower(textValue($app['housing_status'] ?? ''));
    $housingMarks = [
        'homeowner' => [49, 95.9],
        'renter' => [76.5, 95.7],
        'sharer' => [102.5, 95.7],
        'family extended unit' => [144.2, 95.7],
        'caretaker' => [173.5, 95.7],
    ];
    foreach ($housingMarks as $key => $point) {
        if ($housing === $key) {
            drawMark($pdf, $point[0], $point[1], '/', 10);
        }
    }

    // Emergency information
    drawText($pdf, 60, 100, 48, textValue($app['emergency_contact_name'] ?? ''), 9);
    drawText($pdf, 118, 100, 52, $emergencyAddress, 9);
    drawText($pdf, 62, 105, 36, textValue($app['emergency_contact_number'] ?? ''), 9);

    // Membership checkboxes
    if (!empty($app['member_4ps'])) {
        drawMark($pdf, 98.3, 110, '/', 13);
    } else {
        drawMark($pdf, 110.2, 110, '/', 13);
    }
    
    if (!empty($app['member_lgbtq'])) {
        drawMark($pdf, 140.2, 110, '/', 13);
    } else {
        drawMark($pdf, 152, 110, '/', 13);
    }

    if (!empty($app['philhealth_member'])) {
        drawMark($pdf, 82, 115, '/', 13);
    } else {
        drawMark($pdf, 92, 115, '/', 13);
    }
    drawText($pdf, 138, 114.4, 55, textValue($app['philhealth_number'] ?? ''), 9);
    drawText($pdf, 130, 121, 60, textValue($app['philhealth_membership_category'] ?? ''), 7);

    // ============================================
    // FAMILY COMPOSITION - FIXED
    // ============================================
    if (!empty($family_members)) {
        $rowYs = [136.5, 141.0, 145.5, 150, 154];
        foreach (array_slice($family_members, 0, 5) as $index => $member) {
            $y = $rowYs[$index];
            drawText($pdf, 24.5, $y, 39, textValue($member['name'] ?? ''), 7);
            drawText($pdf, 66.2, $y, 16, dateValue($member['birthdate'] ?? ''), 6);
            drawText($pdf, 84.8, $y, 23, textValue($member['relationship'] ?? ''), 6);
            drawText($pdf, 110.7, $y, 8, textValue($member['age'] ?? ''), 7);
            drawText($pdf, 121.2, $y, 25, textValue($member['educational_attainment'] ?? ''), 6);
            drawText($pdf, 148.6, $y, 24, textValue($member['occupation'] ?? ''), 6);
            drawText($pdf, 176.2, $y, 18, moneyValue($member['monthly_income'] ?? 0), 6);
        }
    } else {
        // Show message if no family members found
        drawText($pdf, 24.5, 136.5, 100, 'No family members listed', 8);
    }

    // Narrative sections
    drawText($pdf, 47, 169.5, 145, textValue($app['solo_parent_classification'] ?? ''), 9);
    drawText($pdf, 47, 189.5, 145, textValue($app['solo_parent_needs'] ?? ''), 9);
    drawText($pdf, 47, 210, 145, textValue($app['family_resources'] ?? ''), 9);
    drawText($pdf, 47, 224.5, 145, textValue($app['hear_about_program'] ?? ''), 9);
    drawText($pdf, 47, 239, 145, textValue($app['skills_hobbies'] ?? ''), 9);
    drawText($pdf, 47, 254, 145, textValue($app['solo_parent_id_purpose'] ?? ''), 9);

    // Signature block
    drawText($pdf, 38, 282, 28, dateValue($app['certification_date'] ?? '', 'M d, Y'), 8);
    drawText($pdf, 142, 282.2, 60, textValue($app['applicant_signature'] ?? $fullName), 10);
}

$pdf->Output('I', 'Solo_Parent_Application_' . $application_id . '.pdf');
?>

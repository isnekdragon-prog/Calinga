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

// Get application details
$stmt = $conn->prepare("SELECT * FROM aics_applications WHERE id = ?");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$result = $stmt->get_result();
$app = $result->fetch_assoc();
$stmt->close();

if (!$app) {
    http_response_code(404);
    exit('Application not found');
}

$applicant = [];
$address = [];
$rep = [];

$stmt = $conn->prepare("SELECT * FROM aics_applicants WHERE application_id = ? LIMIT 1");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$result = $stmt->get_result();
$applicant = $result->fetch_assoc() ?: [];
$stmt->close();

$stmt = $conn->prepare("SELECT * FROM aics_addresses WHERE application_id = ? LIMIT 1");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$result = $stmt->get_result();
$address = $result->fetch_assoc() ?: [];
$stmt->close();

$stmt = $conn->prepare("SELECT * FROM aics_representatives WHERE application_id = ? LIMIT 1");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$result = $stmt->get_result();
$rep = $result->fetch_assoc() ?: [];
$stmt->close();

$app['last_name'] = $applicant['last_name'] ?? '';
$app['first_name'] = $applicant['first_name'] ?? '';
$app['middle_name'] = $applicant['middle_name'] ?? '';
$app['name_extension'] = $applicant['name_extension'] ?? '';
$app['house_no'] = $address['house_no'] ?? '';
$app['barangay'] = $address['barangay'] ?? '';
$app['city'] = $address['city'] ?? '';
$app['province'] = $address['province'] ?? '';
$app['region'] = $address['region'] ?? '';
$app['phone'] = $applicant['phone'] ?? '';
$app['birthdate'] = $applicant['birthdate'] ?? '';
$app['age'] = $applicant['age'] ?? '';
if ((int) $app['age'] <= 0) {
    $calculatedAge = calculateAgeFromBirthdate($app['birthdate']);
    if ($calculatedAge !== null) {
        $app['age'] = $calculatedAge;
    }
}
$app['gender'] = $applicant['gender'] ?? '';
$app['civil_status'] = $applicant['civil_status'] ?? '';
$app['occupation'] = $applicant['occupation'] ?? '';
$app['monthly_salary'] = $applicant['monthly_salary'] ?? 0;
$app['target_sector'] = $app['assistance_subtype'] ?? '';
$app['beneficiary_category'] = '';
$app['family_composition'] = json_encode([]);
$app['rep_last_name'] = $rep['rep_last_name'] ?? '';
$app['rep_first_name'] = $rep['rep_first_name'] ?? '';
$app['rep_middle_name'] = $rep['rep_middle_name'] ?? '';
$app['rep_extension'] = $rep['rep_name_ext'] ?? '';
$app['rep_house_no'] = $rep['rep_house_no'] ?? '';
$app['rep_barangay'] = $rep['rep_barangay'] ?? '';
$app['rep_city'] = $rep['rep_city'] ?? '';
$app['rep_province'] = $rep['rep_province'] ?? '';
$app['rep_region'] = $rep['rep_region'] ?? '';
$app['rep_phone'] = $rep['rep_phone'] ?? '';
$app['rep_birthdate'] = $rep['rep_birthdate'] ?? '';
$app['rep_age'] = $rep['rep_age'] ?? '';
if ((int) $app['rep_age'] <= 0) {
    $calculatedRepAge = calculateAgeFromBirthdate($app['rep_birthdate']);
    if ($calculatedRepAge !== null) {
        $app['rep_age'] = $calculatedRepAge;
    }
}
$app['rep_gender'] = $rep['rep_gender'] ?? '';
$app['rep_civil_status'] = $rep['rep_civil_status'] ?? '';
$app['rep_occupation'] = $rep['rep_occupation'] ?? '';
$app['rep_monthly_salary'] = $rep['rep_monthly_salary'] ?? 0;
$app['rep_relationship'] = $rep['rep_relationship'] ?? '';

// Decode JSON fields
$family_composition = json_decode($app['family_composition'] ?? '[]', true);

// instantiate FPDI
try {
    $pdf = new FPDI();
} catch (Throwable $e) {
    http_response_code(500);
    exit('PDF library initialization failed: ' . $e->getMessage());
}

$pdf->AddPage();

$pdf->SetFont('Arial','',8);

// show cursor coordinates
if(isset($_GET['debug'])){
    for($x=0;$x<=210;$x+=10){
        $pdf->Line($x,0,$x,297);
    }

    for($y=0;$y<=297;$y+=10){
        $pdf->Line(0,$y,210,$y);
    }

    // print coordinates every 20mm
    for($x=0;$x<=210;$x+=20){
        for($y=0;$y<=297;$y+=20){
            $pdf->SetXY($x,$y);
            $pdf->Cell(10,4,"$x,$y",0,0);
        }
    }
}

// load template file
$templateFile = __DIR__ . '/../Calinga_form.pdf';
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

// font for actual data
$pdf->SetFont('Arial', '', 10);

// ============================================
// BENEFICIARY INFORMATION (Page 1)
// ============================================

// Name fields - Lastname, Firstname, Middlename
$pdf->SetXY(29, 52);
$pdf->Cell(50, 6, $app['last_name'] ?? '', 0, 0);


$pdf->SetXY(89, 52);
$pdf->Cell(45, 7, $app['first_name'] ?? '', 0, 0);

$pdf->SetXY(145, 52);
$pdf->Cell(45, 7, $app['middle_name'] ?? '', 0, 0);

// Extension (Jr, Sr, etc)
$pdf->SetXY(175, 52);
$pdf->Cell(15, 7, $app['name_extension'] ?? '', 0, 0);

// Address - House No/Street/Purok
$pdf->SetXY(12, 60);
$pdf->Cell(60, 7, $app['house_no'] ?? '', 0, 0);

// Barangay
$pdf->SetXY(60, 60);
$pdf->Cell(50, 7, $app['barangay'] ?? '', 0, 0);

// City/Municipality
$pdf->SetXY(98, 60);
$pdf->Cell(50, 7, $app['city'] ?? '', 0, 0);

// Province
$pdf->SetXY(149, 60);
$pdf->Cell(60, 7, $app['province'] ?? '', 0, 0);

// Region
$pdf->SetXY(175, 60);
$pdf->Cell(50, 7, $app['region'] ?? '', 0, 0);

// Phone Number
$pdf->SetXY(15, 69);
$pdf->Cell(50, 7, $app['phone'] ?? '', 0, 0);

// Birthdate
$birthdate = !empty($app['birthdate']) ? date('m/d/Y', strtotime($app['birthdate'])) : '';
$pdf->SetXY(50, 69);
$pdf->Cell(40, 7, $birthdate, 0, 0);

// Age
$pdf->SetXY(83, 69);
$pdf->Cell(20, 7, (string)($app['age'] ?? ''), 0, 0);

// Gender - check appropriate box
$gender = strtolower($app['gender'] ?? '');
if ($gender == 'male') {
    $pdf->SetXY(95, 69);
    $pdf->Cell(10, 7, 'Male', 0, 0);
} elseif ($gender == 'female') {
    $pdf->SetXY(95, 69);
    $pdf->Cell(10, 7, 'Female', 0, 0);
}

// Civil Status
$civil_status = strtolower($app['civil_status'] ?? '');
if ($civil_status == 'single') {
    $pdf->SetXY(120, 69);
    $pdf->Cell(10, 7, 'Single', 0, 0);
} elseif ($civil_status == 'married') {
    $pdf->SetXY(120, 69);
    $pdf->Cell(10, 7, 'Married', 0, 0);
} elseif ($civil_status == 'widowed') {
    $pdf->SetXY(120, 69);
    $pdf->Cell(10, 7, 'Widowed', 0, 0);
} elseif ($civil_status == 'separated') {
    $pdf->SetXY(120, 69);
    $pdf->Cell(10, 7, 'Separated', 0, 0);
} elseif ($civil_status == 'co-habiting') {
    $pdf->SetXY(120, 69);
    $pdf->Cell(10, 7, 'Co-habiting', 0, 0);
}

// Occupations
$pdf->SetXY(150, 69);
$pdf->Cell(70, 7, $app['occupation'] ?? '', 0, 0);

// Monthly Income
$pdf->SetXY(175, 69);
$pdf->Cell(50, 7, number_format($app['monthly_salary'] ?? 0, 2), 0, 0);

// ============================================
// TARGET SECTOR
// ============================================
$target_sector = strtoupper($app['target_sector'] ?? '');
if (strpos($target_sector, 'FHONA') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 133);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($target_sector, 'SC') !== false || strpos($target_sector, 'SENIOR') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 136);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($target_sector, 'WEDC') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 140);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($target_sector, 'YNSP') !== false || strpos($target_sector, 'YOUTH') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 145);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($target_sector, 'PWD') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 149);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($target_sector, 'PLHIV') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 152);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($target_sector, 'CNSP') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 156);
    $pdf->Cell(10, 7, '3', 0, 0);
}
// New Target Sectors
if (strpos($target_sector, 'SOLO PARENTS') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 160);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($target_sector, 'INDIGENOUS PEOPLE') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 164);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($target_sector, 'RECOVERING') !== false || strpos($target_sector, 'DRUGS') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 168);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($target_sector, '4PS') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 172);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($target_sector, 'STREET DWELLERS') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 176);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($target_sector, 'PSYCHOSOCIAL') !== false || strpos($target_sector, 'MENTAL') !== false || strpos($target_sector, 'LEARNING') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 180);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($target_sector, 'STATELESS') !== false || strpos($target_sector, 'ASYLUM') !== false || strpos($target_sector, 'REFUGEE') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 184);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($target_sector, 'OTHERS') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 188);
    $pdf->Cell(10, 7, '3', 0, 0);
}

// Beneficiary Category
$pdf->SetXY(12, 80);
$pdf->Cell(70, 7, $app['beneficiary_category'] ?? '', 0, 0);

// ============================================
// FAMILY COMPOSITION
// ============================================
$pdf->SetFont('Arial', '', 8);
$familyY = 177;
if (!empty($family_composition) && is_array($family_composition)) {
    $rowIndex = 0;
    foreach ($family_composition as $member) {
        if ($rowIndex >= 8) break; // Max 8 family members fit
        
        $pdf->SetXY(15, $familyY + ($rowIndex * 7));
        $pdf->Cell(50, 6, $member['name'] ?? '', 0, 0);
        
        $pdf->SetXY(75, $familyY + ($rowIndex * 7));
        $pdf->Cell(30, 6, $member['relationship'] ?? '', 0, 0);
        
        $pdf->SetXY(125, $familyY + ($rowIndex * 7));
        $pdf->Cell(20, 6, (string)($member['age'] ?? ''), 0, 0);
        
        $pdf->SetXY(148, $familyY + ($rowIndex * 7));
        $pdf->Cell(45, 6, $member['occupation'] ?? '', 0, 0);
        
        $pdf->SetXY(176, $familyY + ($rowIndex * 7));
        $pdf->Cell(25, 6, number_format($member['income'] ?? 0, 2), 0, 0);
        
        $rowIndex++;
    }
}
$pdf->SetFont('Arial', '', 10);

// ============================================
// TYPE OF ASSISTANCE
// ============================================

// Get the assistance_category from database (stores selected checkbox values as JSON/array)
$assistance_category = strtolower($app['financial_assistance_type'] ?? '');

// Use ZapfDingbats font for checkmarks (character '3' renders as a checkmark)


// Financial Assistance checkboxes
if (strpos($assistance_category, 'financial-medical') !== false || strpos($assistance_category, 'medical') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 193);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($assistance_category, 'financial-funeral') !== false || strpos($assistance_category, 'funeral') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 198);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($assistance_category, 'financial-transportation') !== false || strpos($assistance_category, 'transportation') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(15, 202);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($assistance_category, 'financial-educational') !== false || strpos($assistance_category, 'educational') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);

    $pdf->SetXY(15, 206);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($assistance_category, 'financial-cash assistance') !== false || strpos($assistance_category, 'cash') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(41, 193);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($assistance_category, 'financial-other support') !== false || strpos($assistance_category, 'other support') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(41, 202);
    $pdf->Cell(10, 7, '3', 0, 0);
}

// Material Assistance checkboxes
if (strpos($assistance_category, 'material-family food packs') !== false || strpos($assistance_category, 'family food packs') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(79, 194);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($assistance_category, 'material-other food items') !== false || strpos($assistance_category, 'other food items') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(79, 198);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($assistance_category, 'material-hygiene sleeping kits') !== false || strpos($assistance_category, 'hygiene') !== false || strpos($assistance_category, 'sleeping kits') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);

    $pdf->SetXY(79, 202);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($assistance_category, 'material-assistive device') !== false || strpos($assistance_category, 'assistive device') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);

    $pdf->SetXY(79, 206);
    $pdf->Cell(10, 7, '3', 0, 0);
}

// Psychosocial Support checkboxes
if (strpos($assistance_category, 'psychosocial-referral') !== false || strpos($assistance_category, 'referral') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(173, 187);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($assistance_category, 'psychosocial-psychological first aid') !== false || strpos($assistance_category, 'psychological first aid') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);
    $pdf->SetXY(138, 196);
    $pdf->Cell(10, 7, '3', 0, 0);
}
if (strpos($assistance_category, 'psychosocial-social work counseling') !== false || strpos($assistance_category, 'social work counseling') !== false) {
    $pdf->SetFont('ZapfDingbats', '', 12);

    $pdf->SetXY(138, 203);
    $pdf->Cell(10, 7, '3', 0, 0);
}

// Reset font back to Arial
$pdf->SetFont('Arial', '', 10);

// Cash Amount
$pdf->SetXY(153, 220);
$pdf->Cell(40, 7, number_format($app['cash_amount'] ?? 0, 2), 0, 0);

// ============================================
// DESCRIPTION OF NEED
// ============================================
$pdf->SetXY(23, 221);
$pdf->SetFont('Arial', '', 9);
$pdf->MultiCell(170, 5, $app['description_of_need'] ?? '', 0, 'L');
$pdf->SetFont('Arial', '', 10);

// ============================================
// SIGNATURE LINE
// ============================================
// Date
$pdf->SetXY(173, 35);
$pdf->Cell(40, 7, date('m  d   Y'), 0, 0);

// Signatory name
$pdf->SetXY(30, 255);
$pdf->Cell(80, 7, $app['first_name'] . ' ' . $app['last_name'], 0, 0);

// ============================================
// REPRESENTATIVE INFORMATION (on Page 1, below signature)
// ============================================
$hasRep = !empty($app['rep_first_name']) || !empty($app['rep_last_name']);
if ($hasRep) {
    $pdf->SetFont('Arial', '', 10);
    
    // Representative Name - Last, First, Middle (below signature)
    $pdf->SetXY(29, 85);
    $pdf->Cell(45, 7, $app['rep_last_name'] ?? '', 0, 0);
    $pdf->SetXY(89, 85);
    $pdf->Cell(45, 7, $app['rep_first_name'] ?? '', 0, 0);
    $pdf->SetXY(145, 85);
    $pdf->Cell(45, 7, $app['rep_middle_name'] ?? '', 0, 0);
    $pdf->SetXY(175, 85);
    $pdf->Cell(15, 7, $app['rep_extension'] ?? 'sample', 0, 0);
    
// Representative Address
    $pdf->SetXY(12, 94);
    $pdf->Cell(60, 7, $app['rep_house_no'] ?? '', 0, 0);
    $pdf->SetXY(60, 94);
    $pdf->Cell(50, 7, $app['rep_barangay'] ?? '', 0, 0);
    $pdf->SetXY(98, 94);
    $pdf->Cell(50, 7, $app['rep_city'] ?? '', 0, 0);
    
    // Province and Region
    $pdf->SetXY(145, 94);
    $pdf->Cell(50, 7, $app['rep_province'] ?? '', 0, 0);
    $pdf->SetXY(175, 94);
    $pdf->Cell(50, 7, $app['rep_region'] ?? '', 0, 0);
    
    // Representative Phone Number
    $pdf->SetXY(15, 103);
    $pdf->Cell(50, 7, $app['rep_phone'] ?? '', 0, 0);
    
    // Representative Birthdate
    $rep_birthdate = !empty($app['rep_birthdate']) ? date('m/d/Y', strtotime($app['rep_birthdate'])) : '';
    $pdf->SetXY(50, 103);
    $pdf->Cell(40, 7, $rep_birthdate, 0, 0);
    
    // Representative Age
    $pdf->SetXY(83, 103);
    $pdf->Cell(20, 7, (string)($app['rep_age'] ?? ''), 0, 0);
    
    // Representative Gender
    $rep_gender = strtolower($app['rep_gender'] ?? '');
    if ($rep_gender == 'male') {
        $pdf->SetXY(95, 103);
        $pdf->Cell(10, 7, 'Male', 0, 0);
    } elseif ($rep_gender == 'female') {
        $pdf->SetXY(95, 103);
        $pdf->Cell(10, 7, 'Female', 0, 0);
    }
    
    // Representative Civil Status
    $rep_civil_status = strtolower($app['rep_civil_status'] ?? '');
    if ($rep_civil_status == 'single') {
        $pdf->SetXY(120, 103);
        $pdf->Cell(10, 7, 'Single', 0, 0);
    } elseif ($rep_civil_status == 'married') {
        $pdf->SetXY(120, 103);
        $pdf->Cell(10, 7, 'Married', 0, 0);
    } elseif ($rep_civil_status == 'widowed') {
        $pdf->SetXY(120, 103);
        $pdf->Cell(10, 7, 'Widowed', 0, 0);
    } elseif ($rep_civil_status == 'separated') {
        $pdf->SetXY(120, 103);
        $pdf->Cell(10, 7, 'Separated', 0, 0);
    } elseif ($rep_civil_status == 'co-habiting') {
        $pdf->SetXY(120, 103);
        $pdf->Cell(10, 7, 'Co-habiting', 0, 0);
    }
    
    // Representative Occupation
    $pdf->SetXY(150, 103);
    $pdf->Cell(70, 7, $app['rep_occupation'] ?? '', 0, 0);
    
    // Representative Monthly Income
    $pdf->SetXY(175, 103);
    $pdf->Cell(50, 7, number_format($app['rep_monthly_salary'] ?? 0, 2), 0, 0);
    
    // Representative Relationship to Beneficiary
    $pdf->SetXY(20, 112);
    $pdf->Cell(60, 7, $app['rep_relationship'] ?? '', 0, 0);
}

// send to browser as inline PDF
$pdf->Output('I', 'AICS_Application_' . $application_id . '.pdf');
?>


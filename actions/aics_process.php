<?php
session_start();
require_once __DIR__ . '/create_notifications.php';
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?error=' . urlencode('Please login to apply for AICS assistance.'));
    exit;
}

$user_id = intval($_SESSION['user_id']);

function calculateAgeFromBirthdate(string $birthdate): int
{
    $birthdate = trim($birthdate);
    if ($birthdate === '' || $birthdate === '0000-00-00') {
        return 0;
    }

    try {
        $dob = new DateTimeImmutable($birthdate);
        $today = new DateTimeImmutable('today');
    } catch (Exception $e) {
        return 0;
    }

    if ($dob > $today) {
        return 0;
    }

    return (int) $dob->diff($today)->y;
}

$cooldownStmt = $conn->prepare('SELECT aics_cooldown_until FROM users WHERE id = ? LIMIT 1');
$cooldownStmt->bind_param('i', $user_id);
$cooldownStmt->execute();
$cooldownResult = $cooldownStmt->get_result();
$userRow = $cooldownResult->fetch_assoc() ?: [];
$cooldownStmt->close();

if (!empty($userRow['aics_cooldown_until']) && strtotime($userRow['aics_cooldown_until']) > time()) {
    $cooldownMessage = 'You already have an approved AICS request. You may submit again after ' . date('F d, Y', strtotime($userRow['aics_cooldown_until'])) . '.';
    header('Location: aics.php?error=' . urlencode($cooldownMessage));
    exit;
}

$last_name = trim($_POST['last_name'] ?? '');
$first_name = trim($_POST['first_name'] ?? '');
$middle_name = trim($_POST['middle_name'] ?? '');
$name_extension = trim($_POST['name_extension'] ?? '');
$house_no = trim($_POST['house_no'] ?? '');
$barangay = trim($_POST['barangay'] ?? '');
$city = trim($_POST['city_municipality'] ?? '');
$province = trim($_POST['province'] ?? '');
$region = trim($_POST['region'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$birthdate = trim($_POST['birthdate'] ?? '');
$age = intval($_POST['age'] ?? 0);
$gender = trim($_POST['gender'] ?? '');
$civil_status = trim($_POST['civil_status'] ?? '');
$occupation = trim($_POST['occupation'] ?? '');
$monthly_salary = floatval($_POST['monthly_salary'] ?? 0);

$target_sector = trim($_POST['target_sector'] ?? '');
$assistance_type = trim($_POST['assistance_type'] ?? '');
$assistance_categories = $_POST['assistance_category'] ?? [];
$financial_assistance_type = implode(', ', $assistance_categories);
$description_of_need = trim($_POST['description'] ?? '');

// Representative fields
$rep_last_name = trim($_POST['rep_last_name'] ?? '');
$rep_first_name = trim($_POST['rep_first_name'] ?? '');
$rep_middle_name = trim($_POST['rep_middle_name'] ?? '');
$rep_extension = trim($_POST['rep_extension'] ?? '');
$rep_house_no = trim($_POST['rep_house_no'] ?? '');
$rep_barangay = trim($_POST['rep_barangay'] ?? '');
$rep_city = trim($_POST['rep_city'] ?? '');
$rep_province = trim($_POST['rep_province'] ?? '');
$rep_region = trim($_POST['rep_region'] ?? '');
$rep_phone = trim($_POST['rep_phone'] ?? '');
$rep_relationship = trim($_POST['rep_relationship'] ?? '');
$rep_occupation = trim($_POST['rep_occupation'] ?? '');
$rep_birthdate = trim($_POST['rep_birthdate'] ?? '');
$rep_age = intval($_POST['rep_age'] ?? 0);
$rep_gender = trim($_POST['rep_gender'] ?? '');
$rep_civil_status = trim($_POST['rep_civil_status'] ?? '');
$rep_monthly_salary = floatval($_POST['rep_monthly_salary'] ?? 0);

if ($age <= 0) {
    $age = calculateAgeFromBirthdate($birthdate);
}

if ($rep_age <= 0) {
    $rep_age = calculateAgeFromBirthdate($rep_birthdate);
}

$errors = [];
if (empty($last_name)) $errors[] = 'Last name is required.';
if (empty($first_name)) $errors[] = 'First name is required.';
if (empty($barangay)) $errors[] = 'Barangay is required.';
if (empty($city)) $errors[] = 'City/Municipality is required.';
if (empty($province)) $errors[] = 'Province is required.';
if (empty($phone)) $errors[] = 'Phone number is required.';
if (empty($birthdate)) $errors[] = 'Birthdate is required.';
if (empty($gender)) $errors[] = 'Gender is required.';
if (empty($description_of_need)) $errors[] = 'Description of need is required.';

if (!empty($errors)) {
    header('Location: aics_apply.php?error=' . urlencode(implode(' ', $errors)));
    exit;
}

// Insert main application
$insertApp = $conn->prepare('INSERT INTO aics_applications (user_id, assistance_type, assistance_subtype, financial_assistance_type, cash_amount, status, description_of_need, created_at) VALUES (?, ?, ?, ?, ?, "pending", ?, NOW())');
$cash_amount = 0.00;
$insertApp->bind_param('isssds', $user_id, $assistance_type, $target_sector, $financial_assistance_type, $cash_amount, $description_of_need);
if (!$insertApp->execute()) {
    $err = $insertApp->error;
    $insertApp->close();
    header('Location: aics_apply.php?error=' . urlencode('Failed to submit application: ' . $err));
    exit;
}
$application_id = $insertApp->insert_id;
$insertApp->close();

$insertApplicant = $conn->prepare('INSERT INTO aics_applicants (application_id, last_name, first_name, middle_name, phone, birthdate, age, gender, civil_status, occupation, monthly_salary) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
$insertApplicant->bind_param('isssssisssd', $application_id, $last_name, $first_name, $middle_name, $phone, $birthdate, $age, $gender, $civil_status, $occupation, $monthly_salary);
$insertApplicant->execute();
$insertApplicant->close();

$insertAddress = $conn->prepare('INSERT INTO aics_addresses (application_id, house_no, barangay, city, province, region) VALUES (?, ?, ?, ?, ?, ?)');
$insertAddress->bind_param('isssss', $application_id, $house_no, $barangay, $city, $province, $region);
$insertAddress->execute();
$insertAddress->close();

if (!empty($rep_first_name) || !empty($rep_last_name)) {
    $insertRep = $conn->prepare('INSERT INTO aics_representatives (application_id, rep_last_name, rep_first_name, rep_middle_name, rep_name_ext, rep_house_no, rep_barangay, rep_city, rep_province, rep_region, rep_birthdate, rep_age, rep_gender, rep_civil_status, rep_relationship, rep_phone, rep_occupation, rep_monthly_salary) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
    $insertRep->bind_param('issssssssssisssssd', $application_id, $rep_last_name, $rep_first_name, $rep_middle_name, $rep_name_ext, $rep_house_no, $rep_barangay, $rep_city, $rep_province, $rep_region, $rep_birthdate, $rep_age, $rep_gender, $rep_civil_status, $rep_relationship, $rep_phone, $rep_occupation, $rep_monthly_salary);
    $insertRep->execute();
    $insertRep->close();
}

$notifTitle = 'New AICS Application Submitted';
$notifMessage = "A new AICS application (#$application_id) has been submitted by $first_name $last_name. Please review.";
$notifType = 'application';

$adminStmt = $conn->prepare('SELECT id FROM users WHERE is_admin = 1');
$adminStmt->execute();
$adminResult = $adminStmt->get_result();
while ($admin = $adminResult->fetch_assoc()) {
    $targetUrl = 'aics_admin_view.php?id=' . $application_id;
    $notifInsert = $conn->prepare('INSERT INTO notifications (user_id, title, message, type, target_url) VALUES (?, ?, ?, ?, ?)');
    $notifInsert->bind_param('issss', $admin['id'], $notifTitle, $notifMessage, $notifType, $targetUrl);
    $notifInsert->execute();
    $notifInsert->close();
}
$adminStmt->close();

createNotification($conn, $user_id, 'Application Submitted', 'Your AICS assistance request has been submitted successfully.', 'application', 'aics_view.php?id=' . $application_id);

header('Location: aics.php?success=' . urlencode('Your application has been submitted successfully! Application ID: #' . $application_id));
exit;
?>

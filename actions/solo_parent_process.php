<?php
session_start();
require_once "create_notifications.php";
require_once __DIR__ . "/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=" . urlencode("Please login to apply."));
    exit;
}

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

$user_id = $_SESSION['user_id'];

$last_name = trim($_POST['last_name'] ?? '');
$first_name = trim($_POST['first_name'] ?? '');
$middle_name = trim($_POST['middle_name'] ?? '');
$name_extension = trim($_POST['name_extension'] ?? '');

$house_no = trim($_POST['house_no'] ?? '');
$barangay = trim($_POST['barangay'] ?? '');
$city_municipality = trim($_POST['city_municipality'] ?? '');
$province = trim($_POST['province'] ?? '');
$region = trim($_POST['region'] ?? '');

$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');

$birthdate = $_POST['birthdate'] ?? '';
$place_of_birth = trim($_POST['place_of_birth'] ?? '');
$age = intval($_POST['age'] ?? 0);
$gender = trim($_POST['gender'] ?? '');
$civil_status = trim($_POST['civil_status'] ?? '');

$education = trim($_POST['education'] ?? '');
$religion = trim($_POST['religion'] ?? '');
$occupation = trim($_POST['occupation'] ?? '');
$monthly_income = floatval($_POST['monthly_income'] ?? 0);
$employer_address = trim($_POST['employer_address'] ?? '');

$family_income = floatval($_POST['family_income'] ?? 0);
$spouse_name = trim($_POST['spouse_name'] ?? '');
$spouse_address = trim($_POST['spouse_address'] ?? '');
$housing_status = trim($_POST['housing_status'] ?? '');

$emergency_contact_name = trim($_POST['emergency_contact_name'] ?? '');
$emergency_contact_address = trim($_POST['emergency_contact_address'] ?? '');
$emergency_contact_number = trim($_POST['emergency_contact_number'] ?? '');

$member_4ps = intval($_POST['member_4ps'] ?? 0);
$member_lgbtq = intval($_POST['member_lgbtq'] ?? 0);

$philhealth_member = intval($_POST['philhealth_member'] ?? 0);
$philhealth_number = trim($_POST['philhealth_number'] ?? '');
$philhealth_membership_category = trim($_POST['philhealth_membership_category'] ?? '');
$philhealth_dependent = intval($_POST['philhealth_dependent'] ?? 0);
$philhealth_dependent_name = trim($_POST['philhealth_dependent_name'] ?? '');
$philhealth_dependent_number = trim($_POST['philhealth_dependent_number'] ?? '');
$philhealth_relationship = trim($_POST['philhealth_relationship'] ?? '');

$solo_parent_classification = trim($_POST['solo_parent_classification'] ?? '');
$solo_parent_needs = trim($_POST['solo_parent_needs'] ?? '');
$family_resources = trim($_POST['family_resources'] ?? '');
$hear_about_program = trim($_POST['hear_about_program'] ?? '');
$skills_hobbies = trim($_POST['skills_hobbies'] ?? '');
$solo_parent_id_purpose = trim($_POST['solo_parent_id_purpose'] ?? '');

$certification_date = $_POST['certification_date'] ?? null;
$applicant_signature = trim($_POST['applicant_signature'] ?? '');

if ($age <= 0) {
    $age = calculateAgeFromBirthdate($birthdate);
}

$family_names = $_POST['family_name'] ?? [];
$family_birthdates = $_POST['family_birthdate'] ?? [];
$family_relationships = $_POST['family_relationship'] ?? [];
$family_ages = $_POST['family_age'] ?? [];
$family_educations = $_POST['family_education'] ?? [];
$family_occupations = $_POST['family_occupation'] ?? [];
$family_incomes = $_POST['family_member_income'] ?? [];

$family_members = [];
for ($i = 0; $i < count($family_names); $i++) {
    $name = trim($family_names[$i] ?? '');
    if ($name === '') {
        continue;
    }

    $member_birthdate = trim($family_birthdates[$i] ?? '');
    $member_age = intval($family_ages[$i] ?? 0);
    if ($member_age <= 0) {
        $member_age = calculateAgeFromBirthdate($member_birthdate);
    }

    $family_members[] = [
        'name' => $name,
        'birthdate' => $member_birthdate,
        'relationship' => trim($family_relationships[$i] ?? ''),
        'age' => $member_age,
        'educational_attainment' => trim($family_educations[$i] ?? ''),
        'occupation' => trim($family_occupations[$i] ?? ''),
        'monthly_income' => floatval($family_incomes[$i] ?? 0),
    ];
}

$errors = [];
if ($last_name === '') $errors[] = "Last name is required.";
if ($first_name === '') $errors[] = "First name is required.";
if ($barangay === '') $errors[] = "Barangay is required.";
if ($city_municipality === '') $errors[] = "City/Municipality is required.";
if ($province === '') $errors[] = "Province is required.";
if ($phone === '') $errors[] = "Contact number is required.";
if ($birthdate === '') $errors[] = "Birthdate is required.";
if ($gender === '') $errors[] = "Sex is required.";

if (!empty($errors)) {
    header("Location: solo_parent_apply.php?error=" . urlencode(implode(" ", $errors)));
    exit;
}

$status = "pending";
$conn->begin_transaction();

try {
    $stmt = $conn->prepare("
        INSERT INTO solo_parent_applications (
            user_id, member_4ps, member_lgbtq, status, certification_date, applicant_signature
        ) VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iiisss", $user_id, $member_4ps, $member_lgbtq, $status, $certification_date, $applicant_signature);
    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }
    $application_id = $conn->insert_id;
    $stmt->close();

    $stmt = $conn->prepare("
        INSERT INTO solo_parent_personal (
            application_id, last_name, first_name, middle_name, name_extension,
            birthdate, place_of_birth, age, gender, civil_status, education,
            religion, occupation, monthly_income, employer_address, email, phone
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "issssssisssssdsss",
        $application_id,
        $last_name,
        $first_name,
        $middle_name,
        $name_extension,
        $birthdate,
        $place_of_birth,
        $age,
        $gender,
        $civil_status,
        $education,
        $religion,
        $occupation,
        $monthly_income,
        $employer_address,
        $email,
        $phone
    );
    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }
    $stmt->close();

    $stmt = $conn->prepare("
        INSERT INTO solo_parent_address (application_id, house_no, barangay, city_municipality, province, region)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("isssss", $application_id, $house_no, $barangay, $city_municipality, $province, $region);
    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }
    $stmt->close();

    $stmt = $conn->prepare("
        INSERT INTO solo_parent_membership (
            application_id, philhealth_member, philhealth_number, philhealth_membership_category,
            philhealth_dependent, philhealth_dependent_name, philhealth_dependent_number, philhealth_relationship
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "iississs",
        $application_id,
        $philhealth_member,
        $philhealth_number,
        $philhealth_membership_category,
        $philhealth_dependent,
        $philhealth_dependent_name,
        $philhealth_dependent_number,
        $philhealth_relationship
    );
    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }
    $stmt->close();

    $stmt = $conn->prepare("
        INSERT INTO solo_parent_emergency_contact (application_id, name, address, contact_number)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("isss", $application_id, $emergency_contact_name, $emergency_contact_address, $emergency_contact_number);
    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }
    $stmt->close();

    $stmt = $conn->prepare("
        INSERT INTO solo_parent_spouse (application_id, spouse_name, spouse_address)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("iss", $application_id, $spouse_name, $spouse_address);
    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }
    $stmt->close();

    $stmt = $conn->prepare("
        INSERT INTO solo_parent_additional_info (
            application_id, housing_status, family_income, solo_parent_classification,
            solo_parent_needs, family_resources, hear_about_program, skills_hobbies, solo_parent_id_purpose
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "isdssssss",
        $application_id,
        $housing_status,
        $family_income,
        $solo_parent_classification,
        $solo_parent_needs,
        $family_resources,
        $hear_about_program,
        $skills_hobbies,
        $solo_parent_id_purpose
    );
    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }
    $stmt->close();

    $stmt = $conn->prepare("
        INSERT INTO solo_parent_family_members (
            application_id, name, birthdate, relationship, age, school, educational_attainment, occupation, monthly_income
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    foreach ($family_members as $member) {
        $legacySchool = $member['educational_attainment'];
        $stmt->bind_param(
            "isssisssd",
            $application_id,
            $member['name'],
            $member['birthdate'],
            $member['relationship'],
            $member['age'],
            $legacySchool,
            $member['educational_attainment'],
            $member['occupation'],
            $member['monthly_income']
        );
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }
    }
    $stmt->close();

    $conn->commit();

    createNotification(
        $conn,
        $user_id,
        "Solo Parent Application Submitted",
        "Your Solo Parent application (ID #$application_id) has been submitted and is under review.",
        "solo_parent",
        "solo_parent_view.php?id=$application_id"
    );

    header("Location: solo_parent.php?success=" . urlencode("Application submitted successfully! ID: #$application_id"));
    exit;
} catch (Exception $e) {
    $conn->rollback();
    header("Location: solo_parent_apply.php?error=" . urlencode("Submission failed: " . $e->getMessage()));
    exit;
}
?>

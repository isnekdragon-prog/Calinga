<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?error=' . urlencode('Please login to view your application.'));
    exit;
}

$application_id = intval($_GET['id'] ?? 0);
if (!$application_id) {
    header('Location: solo_parent.php?error=' . urlencode('Invalid application ID.'));
    exit;
}

$stmt = $conn->prepare("
    SELECT
        a.*,
        p.last_name, p.first_name, p.middle_name, p.name_extension, p.birthdate, p.place_of_birth,
        p.age, p.gender, p.civil_status, p.education, p.religion, p.occupation, p.monthly_income,
        p.employer_address, p.email, p.phone AS personal_phone,
        addr.house_no, addr.barangay, addr.city_municipality, addr.province, addr.region,
        m.philhealth_member, m.philhealth_number, m.philhealth_membership_category, m.philhealth_dependent,
        m.philhealth_dependent_name, m.philhealth_dependent_number, m.philhealth_relationship,
        ec.name AS emergency_contact_name, ec.address AS emergency_contact_address, ec.contact_number AS emergency_contact_number,
        addi.housing_status, addi.family_income, addi.solo_parent_classification, addi.solo_parent_needs,
        addi.family_resources, addi.hear_about_program, addi.skills_hobbies, addi.solo_parent_id_purpose,
        s.spouse_name, s.spouse_address
    FROM solo_parent_applications a
    LEFT JOIN solo_parent_personal p ON p.application_id = a.id
    LEFT JOIN solo_parent_address addr ON addr.application_id = a.id
    LEFT JOIN solo_parent_membership m ON m.application_id = a.id
    LEFT JOIN solo_parent_emergency_contact ec ON ec.application_id = a.id
    LEFT JOIN solo_parent_additional_info addi ON addi.application_id = a.id
    LEFT JOIN solo_parent_spouse s ON s.application_id = a.id
    WHERE a.id = ? AND a.user_id = ?
");
$stmt->bind_param("ii", $application_id, $_SESSION['user_id']);
$stmt->execute();
$application = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$application) {
    header('Location: solo_parent.php?error=' . urlencode('Application not found.'));
    exit;
}

$stmt = $conn->prepare("
    SELECT name, birthdate, relationship, age, educational_attainment, occupation, monthly_income
    FROM solo_parent_family_members
    WHERE application_id = ?
");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$family_members = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Solo Parent Application Details</title>
<link rel="stylesheet" href="../style1.css">
<style>
.solo-page {
    max-width: 980px;
    margin: auto;
    padding: 7rem 1rem 3rem;
}

.panel {
    background: #fff;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.btn-back {
    display: inline-block;
    margin-bottom: 1rem;
    color: #1d4bd8;
    text-decoration: none;
}

h1 {
    color: #1d4bd8;
    margin-bottom: 0.4rem;
}

.subtitle {
    color: #54627f;
    margin-bottom: 1.5rem;
}

.status-badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 999px;
    font-weight: 700;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-approved { background: #d4edda; color: #155724; }
.status-declined { background: #f8d7da; color: #721c24; }

.section {
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid #e5ebf7;
}

.section h3 {
    color: #1d4bd8;
    margin-top: 0;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 12px;
}

.info-item {
    background: #f7faff;
    border: 1px solid #dce8ff;
    border-radius: 10px;
    padding: 12px;
}

.info-item label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #61718f;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.text-block {
    background: #f7faff;
    border: 1px solid #dce8ff;
    border-radius: 10px;
    padding: 12px;
    white-space: pre-wrap;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 0.75rem;
}

th, td {
    border: 1px solid #dce8ff;
    padding: 10px;
    text-align: left;
}

th {
    background: #3498db;
    color: #fff;
}
</style>
</head>
<body>
<div class="solo-page">
    <a href="solo_parent.php" class="btn-back">&larr; Back</a>

    <div class="panel">
        <h1>Solo Parent Application</h1>
        <p class="subtitle">
            Application ID: #<?= (int)$application['id'] ?>
            |
            Status:
            <span class="status-badge status-<?= htmlspecialchars($application['status']) ?>">
                <?= htmlspecialchars(ucfirst($application['status'])) ?>
            </span>
        </p>

        <div class="section">
            <h3>Personal Information</h3>
            <div class="info-grid">
                <div class="info-item"><label>Name</label><?= htmlspecialchars(trim(($application['first_name'] ?? '') . ' ' . ($application['middle_name'] ?? '') . ' ' . ($application['last_name'] ?? '') . ' ' . ($application['name_extension'] ?? ''))) ?></div>
                <div class="info-item"><label>Age / Sex</label><?= (int)($application['age'] ?? 0) ?> / <?= htmlspecialchars($application['gender'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Date of Birth</label><?= !empty($application['birthdate']) ? htmlspecialchars(date('M d, Y', strtotime($application['birthdate']))) : 'N/A' ?></div>
                <div class="info-item"><label>Place of Birth</label><?= htmlspecialchars($application['place_of_birth'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Address</label><?= htmlspecialchars(trim(($application['house_no'] ?? '') . ' ' . ($application['barangay'] ?? '') . ', ' . ($application['city_municipality'] ?? '') . ', ' . ($application['province'] ?? ''))) ?></div>
                <div class="info-item"><label>Civil Status</label><?= htmlspecialchars($application['civil_status'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Highest Educational Attainment</label><?= htmlspecialchars($application['education'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Religion</label><?= htmlspecialchars($application['religion'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Occupation</label><?= htmlspecialchars($application['occupation'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Monthly Income</label>PHP <?= number_format((float)($application['monthly_income'] ?? 0), 2) ?></div>
                <div class="info-item"><label>Employer Address</label><?= htmlspecialchars($application['employer_address'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Family Income</label>PHP <?= number_format((float)($application['family_income'] ?? 0), 2) ?></div>
                <div class="info-item"><label>Contact No.</label><?= htmlspecialchars($application['personal_phone'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Email Address</label><?= htmlspecialchars($application['email'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Spouse / Partner</label><?= htmlspecialchars($application['spouse_name'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Spouse Address</label><?= htmlspecialchars($application['spouse_address'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Housing Status</label><?= htmlspecialchars($application['housing_status'] ?? 'N/A') ?></div>
            </div>
        </div>

        <div class="section">
            <h3>Emergency and Membership Information</h3>
            <div class="info-grid">
                <div class="info-item"><label>Emergency Contact</label><?= htmlspecialchars($application['emergency_contact_name'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Emergency Address</label><?= htmlspecialchars($application['emergency_contact_address'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Emergency Contact No.</label><?= htmlspecialchars($application['emergency_contact_number'] ?? 'N/A') ?></div>
                <div class="info-item"><label>4Ps Member</label><?= !empty($application['member_4ps']) ? 'Yes' : 'No' ?></div>
                <div class="info-item"><label>LGBTQ Member</label><?= !empty($application['member_lgbtq']) ? 'Yes' : 'No' ?></div>
                <div class="info-item"><label>PhilHealth Member</label><?= !empty($application['philhealth_member']) ? 'Yes' : 'No' ?></div>
                <div class="info-item"><label>PhilHealth No.</label><?= htmlspecialchars($application['philhealth_number'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Membership Category</label><?= htmlspecialchars($application['philhealth_membership_category'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Dependent</label><?= !empty($application['philhealth_dependent']) ? 'Yes' : 'No' ?></div>
                <div class="info-item"><label>Dependent Name</label><?= htmlspecialchars($application['philhealth_dependent_name'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Dependent Number</label><?= htmlspecialchars($application['philhealth_dependent_number'] ?? 'N/A') ?></div>
                <div class="info-item"><label>Relationship</label><?= htmlspecialchars($application['philhealth_relationship'] ?? 'N/A') ?></div>
            </div>
        </div>

        <div class="section">
            <h3>Family Composition</h3>
            <?php if ($family_members): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Birthdate</th>
                            <th>Relationship</th>
                            <th>Age</th>
                            <th>Educational Attainment</th>
                            <th>Occupation</th>
                            <th>Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($family_members as $member): ?>
                            <tr>
                                <td><?= htmlspecialchars($member['name'] ?? '') ?></td>
                                <td><?= !empty($member['birthdate']) ? htmlspecialchars(date('M d, Y', strtotime($member['birthdate']))) : 'N/A' ?></td>
                                <td><?= htmlspecialchars($member['relationship'] ?? '') ?></td>
                                <td><?= (int)($member['age'] ?? 0) ?></td>
                                <td><?= htmlspecialchars($member['educational_attainment'] ?? '') ?></td>
                                <td><?= htmlspecialchars($member['occupation'] ?? '') ?></td>
                                <td>PHP <?= number_format((float)($member['monthly_income'] ?? 0), 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-block">No family members listed.</div>
            <?php endif; ?>
        </div>

        <div class="section">
            <h3>Narrative Information</h3>
            <div class="info-grid">
                <div>
                    <label>Classification / Circumstances of Being a Solo Parent</label>
                    <div class="text-block"><?= htmlspecialchars($application['solo_parent_classification'] ?? '') ?></div>
                </div>
                <div>
                    <label>Needs / Problems of Solo Parent</label>
                    <div class="text-block"><?= htmlspecialchars($application['solo_parent_needs'] ?? '') ?></div>
                </div>
                <div>
                    <label>Family Resources</label>
                    <div class="text-block"><?= htmlspecialchars($application['family_resources'] ?? '') ?></div>
                </div>
                <div>
                    <label>How Did You Know About the Program?</label>
                    <div class="text-block"><?= htmlspecialchars($application['hear_about_program'] ?? '') ?></div>
                </div>
                <div>
                    <label>Skills / Potentials / Hobbies</label>
                    <div class="text-block"><?= htmlspecialchars($application['skills_hobbies'] ?? '') ?></div>
                </div>
                <div>
                    <label>Purpose of Securing Solo Parent ID</label>
                    <div class="text-block"><?= htmlspecialchars($application['solo_parent_id_purpose'] ?? '') ?></div>
                </div>
            </div>
        </div>

        <div class="section">
            <h3>Certification</h3>
            <div class="info-grid">
                <div class="info-item"><label>Date</label><?= !empty($application['certification_date']) ? htmlspecialchars(date('M d, Y', strtotime($application['certification_date']))) : 'N/A' ?></div>
                <div class="info-item"><label>Signature / Printed Name</label><?= htmlspecialchars($application['applicant_signature'] ?? 'N/A') ?></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

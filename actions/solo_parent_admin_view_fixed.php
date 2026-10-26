<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $conn->prepare("SELECT is_admin, is_staff FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user || (!$user['is_admin'] && !$user['is_staff'])) {
    echo '<p>Access denied.</p>';
    exit;
}
if (empty($_SESSION['admin_action_token'])) {
    $_SESSION['admin_action_token'] = bin2hex(random_bytes(32));
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
    header('Location: admin_panel.php?tab=solo_parent&error=' . urlencode('Invalid application ID.'));
    exit;
}

$stmt = $conn->prepare("
    SELECT
        a.*, u.fullname AS user_name, u.email AS user_email,
        p.last_name, p.first_name, p.middle_name, p.name_extension, p.birthdate, p.place_of_birth,
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
    LEFT JOIN users u ON u.id = a.user_id
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
$application = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$application) {
    header('Location: admin_panel.php?tab=solo_parent&error=' . urlencode('Application not found.'));
    exit;
}

if ((int)($application['age'] ?? 0) <= 0) {
    $calculatedAge = calculateAgeFromBirthdate($application['birthdate'] ?? '');
    if ($calculatedAge !== null) {
        $application['age'] = $calculatedAge;
    }
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

foreach ($family_members as &$member) {
    if ((int)($member['age'] ?? 0) <= 0) {
        $calculatedMemberAge = calculateAgeFromBirthdate($member['birthdate'] ?? '');
        if ($calculatedMemberAge !== null) {
            $member['age'] = $calculatedMemberAge;
        }
    }
}
unset($member);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solo Parent Application #<?= $application_id ?> - CALINGA MSWD</title>
    <link rel="stylesheet" href="../style1.css">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f4f6f8; margin: 0; padding: 20px; }
        .action-bar { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 14px; display: inline-block; }
        .btn-back { background: #95a5a6; color: #fff; }
        .btn-pdf { background: #e74c3c; color: #fff; }
        .btn-approve { background: #27ae60; color: #fff; }
        .btn-decline { background: #c0392b; color: #fff; }
        .form-container { background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 1100px; margin: 0 auto; }
        h1 { color: #1d4bd8; font-size: 24px; text-align: center; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 30px; font-size: 14px; background: #f8f9fa; padding: 15px; border-radius: 6px; }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-declined { background: #f8d7da; color: #721c24; }
        .form-section { padding-bottom: 25px; border-bottom: 2px solid #eee; margin-bottom: 25px; }
        .form-section:last-child { border-bottom: none; margin-bottom: 0; }
        .form-section h3 { color: #1d4bd8; font-size: 16px; margin-bottom: 15px; border-left: 4px solid #1d4bd8; padding-left: 12px; }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 15px; }
        .info-item { background: #f8f9fa; padding: 15px; border-radius: 8px; }
        .info-item label { font-weight: bold; color: #555; display: block; font-size: 12px; text-transform: uppercase; margin-bottom: 5px; }
        .family-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 13px; }
        .family-table th, .family-table td { padding: 12px 10px; border: 1px solid #eee; text-align: left; }
        .family-table th { background: #fdf2f2; color: #c0392b; font-weight: 600; }
        .text-block { background: #f8f9fa; padding: 15px; border-radius: 8px; white-space: pre-wrap; }
        .admin-actions { background: #fdf2f2; padding: 25px; border-radius: 8px; border-left: 5px solid #e74c3c; margin-top: 30px; }
        .admin-actions textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; min-height: 100px; font-family: inherit; resize: vertical; }
        .action-row { display: flex; gap: 10px; flex-wrap: wrap; }
    </style>
</head>
<body>
<div class="action-bar">
    <a href="admin_panel.php?tab=solo_parent" class="btn btn-back">&larr; Back to Applications</a>
    <a href="generate_solo_parent_pdf.php?id=<?= $application_id ?>" class="btn btn-pdf" target="_blank">Generate PDF</a>
</div>

<div class="form-container">
    <h1>Solo Parent Application Review</h1>
    <p class="subtitle">
        <strong>Application ID:</strong> #<?= $application_id ?>
        |
        <strong>Applicant:</strong> <?= htmlspecialchars($application['user_name'] ?? 'N/A') ?>
        |
        <strong>Status:</strong>
        <span class="status-badge status-<?= htmlspecialchars($application['status']) ?>"><?= htmlspecialchars(ucfirst($application['status'])) ?></span>
        |
        <strong>Submitted:</strong> <?= htmlspecialchars(date('F d, Y H:i', strtotime($application['created_at']))) ?>
    </p>

    <div class="form-section">
        <h3>Personal Information</h3>
        <div class="info-grid">
            <div class="info-item"><label>Name</label><span><?= htmlspecialchars(trim(($application['first_name'] ?? '') . ' ' . ($application['middle_name'] ?? '') . ' ' . ($application['last_name'] ?? '') . ' ' . ($application['name_extension'] ?? ''))) ?></span></div>
            <div class="info-item"><label>Age / Sex</label><span><?= (int)($application['age'] ?? 0) ?> / <?= htmlspecialchars($application['gender'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Date of Birth</label><span><?= !empty($application['birthdate']) ? htmlspecialchars(date('M d, Y', strtotime($application['birthdate']))) : 'N/A' ?></span></div>
            <div class="info-item"><label>Place of Birth</label><span><?= htmlspecialchars($application['place_of_birth'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Address</label><span><?= htmlspecialchars(trim(($application['house_no'] ?? '') . ' ' . ($application['barangay'] ?? '') . ', ' . ($application['city_municipality'] ?? '') . ', ' . ($application['province'] ?? ''))) ?></span></div>
            <div class="info-item"><label>Civil Status</label><span><?= htmlspecialchars($application['civil_status'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Educational Attainment</label><span><?= htmlspecialchars($application['education'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Religion</label><span><?= htmlspecialchars($application['religion'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Occupation</label><span><?= htmlspecialchars($application['occupation'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Monthly Income</label><span>PHP <?= number_format((float)($application['monthly_income'] ?? 0), 2) ?></span></div>
            <div class="info-item"><label>Employer Address</label><span><?= htmlspecialchars($application['employer_address'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Family Income</label><span>PHP <?= number_format((float)($application['family_income'] ?? 0), 2) ?></span></div>
            <div class="info-item"><label>Contact No.</label><span><?= htmlspecialchars($application['personal_phone'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Email Address</label><span><?= htmlspecialchars($application['personal_email'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Spouse / Partner</label><span><?= htmlspecialchars($application['spouse_name'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Spouse Address</label><span><?= htmlspecialchars($application['spouse_address'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Housing Status</label><span><?= htmlspecialchars($application['housing_status'] ?? 'N/A') ?></span></div>
        </div>
    </div>

    <div class="form-section">
        <h3>Emergency and Membership Information</h3>
        <div class="info-grid">
            <div class="info-item"><label>Emergency Contact</label><span><?= htmlspecialchars($application['emergency_contact_name'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Emergency Address</label><span><?= htmlspecialchars($application['emergency_contact_address'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Emergency Contact No.</label><span><?= htmlspecialchars($application['emergency_contact_number'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>4Ps Member</label><span><?= !empty($application['member_4ps']) ? 'Yes' : 'No' ?></span></div>
            <div class="info-item"><label>LGBTQ Member</label><span><?= !empty($application['member_lgbtq']) ? 'Yes' : 'No' ?></span></div>
            <div class="info-item"><label>PhilHealth Member</label><span><?= !empty($application['philhealth_member']) ? 'Yes' : 'No' ?></span></div>
            <div class="info-item"><label>PhilHealth No.</label><span><?= htmlspecialchars($application['philhealth_number'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Membership Category</label><span><?= htmlspecialchars($application['philhealth_membership_category'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Dependent</label><span><?= !empty($application['philhealth_dependent']) ? 'Yes' : 'No' ?></span></div>
            <div class="info-item"><label>Dependent Name</label><span><?= htmlspecialchars($application['philhealth_dependent_name'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Dependent Number</label><span><?= htmlspecialchars($application['philhealth_dependent_number'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Relationship</label><span><?= htmlspecialchars($application['philhealth_relationship'] ?? 'N/A') ?></span></div>
        </div>
    </div>

    <div class="form-section">
        <h3>Family Composition</h3>
        <?php if ($family_members): ?>
            <table class="family-table">
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

    <div class="form-section">
        <h3>Narrative Information</h3>
        <div class="info-grid">
            <div><label>Classification / Circumstances</label><div class="text-block"><?= htmlspecialchars($application['solo_parent_classification'] ?? '') ?></div></div>
            <div><label>Needs / Problems</label><div class="text-block"><?= htmlspecialchars($application['solo_parent_needs'] ?? '') ?></div></div>
            <div><label>Family Resources</label><div class="text-block"><?= htmlspecialchars($application['family_resources'] ?? '') ?></div></div>
            <div><label>How Did You Know About the Program?</label><div class="text-block"><?= htmlspecialchars($application['hear_about_program'] ?? '') ?></div></div>
            <div><label>Skills / Potentials / Hobbies</label><div class="text-block"><?= htmlspecialchars($application['skills_hobbies'] ?? '') ?></div></div>
            <div><label>Purpose of Securing Solo Parent ID</label><div class="text-block"><?= htmlspecialchars($application['solo_parent_id_purpose'] ?? '') ?></div></div>
        </div>
    </div>

    <?php if ($application['status'] === 'pending'): ?>
        <div class="admin-actions">
            <h4>Process Application</h4>
            <form method="POST" action="solo_parent_admin_process.php">
                <input type="hidden" name="application_id" value="<?= $application_id ?>">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_action_token']) ?>">
                <textarea name="admin_notes" placeholder="Enter decision notes..." required></textarea>
                <div class="action-row">
                    <button type="submit" name="action" value="approve" class="btn btn-approve">Approve Application</button>
                    <button type="submit" name="action" value="decline" class="btn btn-decline">Decline Application</button>
                </div>
            </form>
        </div>
    <?php else: ?>
        <div class="admin-actions">
            <h4>Admin Notes</h4>
            <div class="text-block"><?= htmlspecialchars($application['admin_notes'] ?? 'No notes recorded.') ?></div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>

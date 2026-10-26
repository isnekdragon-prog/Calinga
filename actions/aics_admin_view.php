<?php
session_start();
require_once __DIR__ . '/db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $conn->prepare("SELECT is_admin, is_staff FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || (!$user['is_admin'] && !$user['is_staff'])) {
    echo '<p>Access denied.</p>';
    exit;
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
    header('Location: admin_panel.php?tab=aics&error=' . urlencode('Invalid application ID.'));
    exit;
}

// Get application details and related data
$stmt = $conn->prepare("SELECT a.*, u.fullname AS user_name, u.email AS user_email,
    ap.first_name AS first_name, ap.middle_name AS middle_name, ap.last_name AS last_name,
    ap.birthdate AS birthdate, ap.gender AS gender, ap.civil_status AS civil_status,
    ap.age AS age, ap.occupation AS occupation, ap.monthly_salary AS monthly_salary, ap.phone AS phone,
    ad.house_no AS house_no, ad.barangay AS barangay, ad.city AS city, ad.province AS province,
    rep.rep_first_name AS rep_first_name, rep.rep_middle_name AS rep_middle_name, rep.rep_last_name AS rep_last_name, rep.rep_name_ext AS rep_extension,
    rep.rep_birthdate AS rep_birthdate, rep.rep_age AS rep_age, rep.rep_gender AS rep_gender, rep.rep_civil_status AS rep_civil_status,
    rep.rep_occupation AS rep_occupation, rep.rep_monthly_salary AS rep_monthly_salary, rep.rep_relationship AS rep_relationship,
    rep.rep_house_no AS rep_house_no, rep.rep_barangay AS rep_barangay, rep.rep_city AS rep_city, rep.rep_province AS rep_province, rep.rep_phone AS rep_phone
    FROM aics_applications a
    LEFT JOIN users u ON a.user_id = u.id
    LEFT JOIN aics_applicants ap ON ap.application_id = a.id
    LEFT JOIN aics_addresses ad ON ad.application_id = a.id
    LEFT JOIN aics_representatives rep ON rep.application_id = a.id
    WHERE a.id = ?");
$stmt->bind_param("i", $application_id);
$stmt->execute();
$result = $stmt->get_result();
$application = $result->fetch_assoc();
$stmt->close();

if (!$application) {
    header('Location: admin_panel.php?tab=aics&error=' . urlencode('Application not found.'));
    exit;
}

// Ensure fields exist for display
$application['first_name'] = $application['first_name'] ?? '';
$application['middle_name'] = $application['middle_name'] ?? '';
$application['last_name'] = $application['last_name'] ?? '';
$application['birthdate'] = $application['birthdate'] ?? '';
$application['age'] = $application['age'] ?? 0;
if ((int) $application['age'] <= 0) {
    $calculatedAge = calculateAgeFromBirthdate($application['birthdate']);
    if ($calculatedAge !== null) {
        $application['age'] = $calculatedAge;
    }
}
$application['gender'] = $application['gender'] ?? '';
$application['occupation'] = $application['occupation'] ?? '';
$application['monthly_salary'] = $application['monthly_salary'] ?? 0;
$application['phone'] = $application['phone'] ?? '';
$application['house_no'] = $application['house_no'] ?? '';
$application['street'] = $application['street'] ?? '';
$application['barangay'] = $application['barangay'] ?? '';
$application['city'] = $application['city'] ?? '';
$application['province'] = $application['province'] ?? '';
$application['rep_first_name'] = $application['rep_first_name'] ?? '';
$application['rep_middle_name'] = $application['rep_middle_name'] ?? '';
$application['rep_last_name'] = $application['rep_last_name'] ?? '';
$application['rep_extension'] = $application['rep_extension'] ?? '';
$application['rep_birthdate'] = $application['rep_birthdate'] ?? '';
$application['rep_age'] = $application['rep_age'] ?? 0;
if ((int) $application['rep_age'] <= 0) {
    $calculatedRepAge = calculateAgeFromBirthdate($application['rep_birthdate']);
    if ($calculatedRepAge !== null) {
        $application['rep_age'] = $calculatedRepAge;
    }
}
$application['rep_gender'] = $application['rep_gender'] ?? '';
$application['rep_civil_status'] = $application['rep_civil_status'] ?? '';
$application['rep_occupation'] = $application['rep_occupation'] ?? '';
$application['rep_monthly_salary'] = $application['rep_monthly_salary'] ?? 0;
$application['rep_relationship'] = $application['rep_relationship'] ?? '';
$application['rep_valid_id'] = $application['rep_valid_id'] ?? '';
$application['rep_valid_id_number'] = $application['rep_valid_id_number'] ?? '';
$application['rep_house_no'] = $application['rep_house_no'] ?? '';
$application['rep_street'] = $application['rep_street'] ?? '';
$application['rep_barangay'] = $application['rep_barangay'] ?? '';
$application['rep_city'] = $application['rep_city'] ?? '';
$application['rep_province'] = $application['rep_province'] ?? '';
$application['rep_phone'] = $application['rep_phone'] ?? '';

$application['rep_first_name'] = $application['rep_first_name'] ?? '';
$application['rep_middle_name'] = $application['rep_middle_name'] ?? '';
$application['rep_last_name'] = $application['rep_last_name'] ?? '';
$application['rep_extension'] = $application['rep_extension'] ?? '';
$application['rep_birthdate'] = $application['rep_birthdate'] ?? '';
$application['rep_age'] = $application['rep_age'] ?? 0;
$application['rep_gender'] = $application['rep_gender'] ?? '';
$application['rep_civil_status'] = $application['rep_civil_status'] ?? '';
$application['rep_occupation'] = $application['rep_occupation'] ?? '';
$application['rep_monthly_salary'] = $application['rep_monthly_salary'] ?? 0;
$application['rep_relationship'] = $application['rep_relationship'] ?? '';
$application['rep_valid_id'] = $application['rep_valid_id'] ?? '';
$application['rep_valid_id_number'] = $application['rep_valid_id_number'] ?? '';
$application['rep_house_no'] = $application['rep_house_no'] ?? '';
$application['rep_street'] = $application['rep_street'] ?? '';
$application['rep_barangay'] = $application['rep_barangay'] ?? '';
$application['rep_city'] = $application['rep_city'] ?? '';
$application['rep_province'] = $application['rep_province'] ?? '';
$application['rep_phone'] = $application['rep_phone'] ?? '';

// Decode JSON fields
$family_composition = json_decode($application['family_composition'] ?? '[]', true);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application #<?= $application_id ?> - CALINGA MSWD</title>
    <link rel="stylesheet" href="../style1.css">
    <style>

        .action-bar { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .btn { padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; text-decoration: none; font-size: 14px; display: inline-block; }
        .btn-back { background: #95a5a6; color: #fff; }
        .btn-approve { background: #27ae60; color: #fff; }
        .btn-decline { background: #c0392b; color: #fff; }
        .form-container { background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 900px; margin: 0 auto; }
        h1 { color: #2c3e50; font-size: 20px; text-align: center; margin-bottom: 5px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 25px; font-size: 14px; }
        .form-section { margin-bottom: 20px; padding-bottom: 15px; border-bottom: 2px solid #eee; }
        .form-section:last-child { border-bottom: none; }
        .form-section h3 { color: #2c3e50; font-size: 14px; margin-bottom: 12px; border-left: 4px solid #3498db; padding-left: 10px; }
        .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .info-item { font-size: 13px; }
        .info-item label { font-weight: bold; color: #555; display: block; font-size: 11px; text-transform: uppercase; }
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-approved { background: #d4edda; color: #155724; }
        .status-declined { background: #f8d7da; color: #721c24; }
        .family-table { width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 12px; }
        .family-table th, .family-table td { padding: 8px; border: 1px solid #ddd; text-align: left; }
        .family-table th { background: #f8f9fa; }
        .admin-actions { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-top: 20px; }
        .admin-actions h4 { margin-top: 0; margin-bottom: 15px; }
        .admin-actions form { display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-start; }
        .admin-actions textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; min-height: 80px; margin-bottom: 10px; font-family: inherit; }
        .action-row { display: flex; gap: 10px; width: 100%; }
        .schedule-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; width: 100%; margin-bottom: 10px; }
        .schedule-grid input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; }
    </style>
</head>
<body>

<div class="action-bar">
    <a href="admin_panel.php?tab=aics" class="btn btn-back">← Back</a>
    <a href="generate_aics_pdf.php?id=<?= $application_id ?>" class="btn" style="background: #e74c3c; color: #fff;" target="_blank">Generate PDF</a>
</div>

<div class="form-container">
    <h1>DSWD GENERAL INTAKE SHEET (GIS)</h1>
    <p class="subtitle">
        <strong>Application ID:</strong> #<?= $application_id ?> | 
        <strong>Status:</strong> <span class="status-badge status-<?= $application['status'] ?>"><?= strtoupper($application['status']) ?></span> | 
        <strong>Date:</strong> <?= date('F d, Y', strtotime($application['created_at'])) ?>
    </p>
    
    <div class="form-section">
        <h3>BENEFICIARY INFORMATION</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Name</label>
                <span><?= htmlspecialchars(($application['first_name'] ?? '') . ' ' . ($application['middle_name'] ?? '') . ' ' . ($application['last_name'] ?? '')) ?></span>
            </div>
            <div class="info-item">
                <label>Address</label>
                <span><?= htmlspecialchars(($application['house_no'] ?? '') . ', ' . ($application['barangay'] ?? '') . ', ' . ($application['city'] ?? '') . ', ' . ($application['province'] ?? '')) ?></span>
            </div>
            <div class="info-item">
                <label>Phone</label>
                <span><?= htmlspecialchars($application['phone'] ?? '') ?></span>
            </div>
            <div class="info-item">
                <label>Birthdate / Age</label>
                <span><?= !empty($application['birthdate']) ? date('M d, Y', strtotime($application['birthdate'])) : 'N/A' ?> / <?= $application['age'] ?? 0 ?></span>
            </div>
            <div class="info-item">
                <label>Gender</label>
                <span><?= htmlspecialchars($application['gender'] ?? '') ?></span>
            </div>
            <div class="info-item">
                <label>Occupation</label>
                <span><?= htmlspecialchars($application['occupation'] ?? '') ?></span>
            </div>
            <div class="info-item">
                <label>Monthly Salary</label>
                <span>PHP <?= number_format($application['monthly_salary'] ?? 0, 2) ?></span>
            </div>
    </div>
    
    <div class="form-section">
        <h3>TARGET SECTOR</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Target Sector</label>
                <span><?= htmlspecialchars($application['assistance_subtype'] ?? 'None') ?></span>
            </div>
        </div>
    </div>
    
    <?php if (!empty($family_composition)): ?>
    <div class="form-section">
        <h3>FAMILY COMPOSITION</h3>
        <table class="family-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Relationship</th>
                    <th>Age</th>
                    <th>Occupation</th>
                    <th>Income</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($family_composition as $member): ?>
                <tr>
                    <td><?= htmlspecialchars($member['name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($member['relationship'] ?? '') ?></td>
                    <td><?= $member['age'] ?? 0 ?></td>
                    <td><?= htmlspecialchars($member['occupation'] ?? '') ?></td>
                    <td>PHP <?= number_format($member['income'] ?? 0, 2) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
    
    <div class="form-section">
        <h3>ASSISTANCE TYPE</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Financial Assistance</label>
                <span><?= htmlspecialchars($application['financial_assistance_type'] ?? 'None') ?></span>
            </div>
    </div>
    
    <div class="form-section">
        <h3>DESCRIPTION OF NEED</h3>
        <p><?= nl2br(htmlspecialchars($application['description_of_need'] ?? '')) ?></p>
    </div>
    
    <?php
    // Check if there's a representative
    $hasRep = !empty($application['rep_first_name']) || !empty($application['rep_last_name']);
    if ($hasRep): 
    ?>
    <div class="form-section">
        <h3>REPRESENTATIVE INFORMATION</h3>
        <div class="info-grid">
            <div class="info-item">
                <label>Name</label>
                <span><?= htmlspecialchars(($application['rep_first_name'] ?? '') . ' ' . ($application['rep_middle_name'] ?? '') . ' ' . ($application['rep_last_name'] ?? '') . ' ' . ($application['rep_extension'] ?? '')) ?></span>
            </div>
            <div class="info-item">
                <label>Address</label>
                <span><?= htmlspecialchars(($application['rep_house_no'] ?? '') . ', ' . ($application['rep_barangay'] ?? '') . ', ' . ($application['rep_city'] ?? '') . ', ' . ($application['rep_province'] ?? '')) ?></span>
            </div>
            <div class="info-item">
                <label>Phone</label>
                <span><?= htmlspecialchars($application['rep_phone'] ?? '') ?></span>
            </div>
            <div class="info-item">
                <label>Birthdate / Age</label>
                <span><?= !empty($application['rep_birthdate']) ? date('M d, Y', strtotime($application['rep_birthdate'])) : 'N/A' ?> / <?= $application['rep_age'] ?? 0 ?></span>
            </div>
            <div class="info-item">
                <label>Gender</label>
                <span><?= htmlspecialchars($application['rep_gender'] ?? '') ?></span>
            </div>
            <div class="info-item">
                <label>Civil Status</label>
                <span><?= htmlspecialchars($application['rep_civil_status'] ?? '') ?></span>
            </div>
            <div class="info-item">
                <label>Occupation</label>
                <span><?= htmlspecialchars($application['rep_occupation'] ?? '') ?></span>
            </div>
            <div class="info-item">
                <label>Monthly Salary</label>
                <span>PHP <?= number_format($application['rep_monthly_salary'] ?? 0, 2) ?></span>
            </div>
            <div class="info-item">
                <label>Relationship to Beneficiary</label>
                <span><?= htmlspecialchars($application['rep_relationship'] ?? '') ?></span>
            </div>
            <div class="info-item">
                <label>Valid ID</label>
                <span><?= htmlspecialchars($application['rep_valid_id'] ?? 'N/A') ?></span>
            </div>
            <div class="info-item">
                <label>Valid ID Number</label>
                <span><?= htmlspecialchars($application['rep_valid_id_number'] ?? 'N/A') ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($application['status'] == 'pending'): ?>
    <div class="admin-actions">
        <h4>Process Application</h4>
        <form method="POST" action="aics_admin_process.php">
            <input type="hidden" name="application_id" value="<?= $application_id ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['admin_action_token']) ?>">
            <div class="schedule-grid">
                <input type="date" name="schedule_date" value="<?= htmlspecialchars($application['schedule_date'] ?? '') ?>">
                <input type="time" name="schedule_time" value="<?= htmlspecialchars(!empty($application['schedule_time']) ? substr((string)$application['schedule_time'], 0, 5) : '09:00') ?>">
                <input type="text" name="schedule_location" value="<?= htmlspecialchars($application['schedule_location'] ?? 'MSWD Office, Cainta') ?>" placeholder="Schedule location">
            </div>
            <textarea name="admin_notes" placeholder="Add notes about this application..."></textarea>
            <div class="action-row">
                <button type="submit" name="action" value="approve" class="btn btn-approve">Approve</button>
                <button type="submit" name="action" value="decline" class="btn btn-decline">Decline</button>
            </div>
        </form>
    </div>
    <?php else: ?>
    <div class="admin-actions">
        <h4>Admin Notes</h4>
        <p><strong>Schedule:</strong>
            <?php if (!empty($application['schedule_date']) || !empty($application['schedule_time'])): ?>
                <?= htmlspecialchars(!empty($application['schedule_date']) ? date('F d, Y', strtotime($application['schedule_date'])) : 'N/A') ?>
                <?= htmlspecialchars(!empty($application['schedule_time']) ? date('g:i A', strtotime($application['schedule_time'])) : '') ?>
                <?= !empty($application['schedule_location']) ? '| ' . htmlspecialchars($application['schedule_location']) : '' ?>
            <?php else: ?>
                Not scheduled
            <?php endif; ?>
        </p>
        <p><?= nl2br(htmlspecialchars($application['admin_notes'] ?? 'No notes added.')) ?></p>
    </div>
    <?php endif; ?>
</div>

</body>
</html>

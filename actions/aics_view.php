<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?error=' . urlencode('Please login to view your application.'));
    exit;
}

$application_id = intval($_GET['id'] ?? 0);
if (!$application_id) {
    header('Location: aics.php?error=' . urlencode('Invalid application ID.'));
    exit;
}

$stmt = $conn->prepare('SELECT * FROM aics_applications WHERE id = ? AND user_id = ?');
$stmt->bind_param('ii', $application_id, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$application = $result->fetch_assoc();
$stmt->close();

if (!$application) {
    header('Location: aics.php?error=' . urlencode('Application not found.'));
    exit;
}

$applicant = [];
$address = [];
$rep = [];

$stmt = $conn->prepare('SELECT * FROM aics_applicants WHERE application_id = ? LIMIT 1');
$stmt->bind_param('i', $application_id);
$stmt->execute();
$result = $stmt->get_result();
$applicant = $result->fetch_assoc() ?: [];
$stmt->close();

$stmt = $conn->prepare('SELECT * FROM aics_addresses WHERE application_id = ? LIMIT 1');
$stmt->bind_param('i', $application_id);
$stmt->execute();
$result = $stmt->get_result();
$address = $result->fetch_assoc() ?: [];
$stmt->close();

$stmt = $conn->prepare('SELECT * FROM aics_representatives WHERE application_id = ? LIMIT 1');
$stmt->bind_param('i', $application_id);
$stmt->execute();
$result = $stmt->get_result();
$rep = $result->fetch_assoc() ?: [];
$stmt->close();

// compute age if missing
if (empty($applicant['age']) && !empty($applicant['birthdate'])) {
    $dob = new DateTime($applicant['birthdate']);
    $now = new DateTime();
    $applicant['age'] = $now->diff($dob)->y;
}
if (empty($rep['rep_age']) && !empty($rep['rep_birthdate'])) {
    $dob = new DateTime($rep['rep_birthdate']);
    $now = new DateTime();
    $rep['rep_age'] = $now->diff($dob)->y;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AICS Application #<?= $application_id ?> - CALINGA</title>
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
    </style>
</head>
<body>
<div class="action-bar">
    <a href="aics.php" class="btn btn-back">← Back to AICS</a>
    <span class="btn" style="background:#3498db; color:#fff;">Application #<?= $application_id ?></span>
</div>

<div class="form-container">
    <h1>AICS Application Details</h1>
    <p class="subtitle">Status: <span class="status-badge status-<?= htmlspecialchars($application['status'] ?? 'pending') ?>"><?= strtoupper(htmlspecialchars($application['status'] ?? 'pending')) ?></span> | Date: <?= htmlspecialchars(date('F d, Y', strtotime($application['created_at'] ?? 'now'))) ?></p>

    <div class="form-section">
        <h3>Beneficiary Information</h3>
        <div class="info-grid">
            <div class="info-item"><label>Name</label><span><?= htmlspecialchars(trim(($applicant['first_name'] ?? '') . ' ' . ($applicant['middle_name'] ?? '') . ' ' . ($applicant['last_name'] ?? ''))) ?></span></div>
            <div class="info-item"><label>Address</label><span><?= htmlspecialchars(trim(($address['house_no'] ?? '') . ', ' . ($address['barangay'] ?? '') . ', ' . ($address['city'] ?? '') . ', ' . ($address['province'] ?? ''))) ?></span></div>
            <div class="info-item"><label>Phone</label><span><?= htmlspecialchars($applicant['phone'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Birthdate / Age</label><span><?= !empty($applicant['birthdate']) ? date('M d, Y', strtotime($applicant['birthdate'])) : 'N/A' ?> / <?= number_format($applicant['age'] ?? 0) ?></span></div>
            <div class="info-item"><label>Gender</label><span><?= htmlspecialchars($applicant['gender'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Civil Status</label><span><?= htmlspecialchars($applicant['civil_status'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Occupation</label><span><?= htmlspecialchars($applicant['occupation'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Monthly Salary</label><span>PHP <?= number_format($applicant['monthly_salary'] ?? 0, 2) ?></span></div>
        </div>
    </div>

    <div class="form-section">
        <h3>Assistance Details</h3>
        <div class="info-grid">
            <div class="info-item"><label>Type</label><span><?= htmlspecialchars($application['assistance_type'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Subtype</label><span><?= htmlspecialchars($application['assistance_subtype'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Financial Assistance</label><span><?= htmlspecialchars($application['financial_assistance_type'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Cash Amount</label><span>PHP <?= number_format($application['cash_amount'] ?? 0, 2) ?></span></div>
            <div class="info-item"><label>Schedule Date</label><span><?= !empty($application['schedule_date']) ? htmlspecialchars(date('M d, Y', strtotime($application['schedule_date']))) : 'Not scheduled yet' ?></span></div>
            <div class="info-item"><label>Schedule Time</label><span><?= !empty($application['schedule_time']) ? htmlspecialchars(date('g:i A', strtotime($application['schedule_time']))) : 'Not scheduled yet' ?></span></div>
            <div class="info-item" style="grid-column: span 2;"><label>Schedule Location</label><span><?= htmlspecialchars($application['schedule_location'] ?? 'MSWD Office, Cainta') ?></span></div>
            <div class="info-item" style="grid-column: span 2;"><label>Description of Need</label><span><?= nl2br(htmlspecialchars($application['description_of_need'] ?? 'N/A')) ?></span></div>
        </div>
    </div>

    <?php $family_composition = json_decode($application['family_composition'] ?? '[]', true); if (!empty($family_composition) && is_array($family_composition)): ?>
    <div class="form-section">
        <h3>Family Composition</h3>
        <table class="family-table">
            <thead><tr><th>Name</th><th>Relationship</th><th>Age</th><th>Occupation</th><th>Income</th></tr></thead>
            <tbody>
            <?php foreach ($family_composition as $member): ?>
            <tr>
                <td><?= htmlspecialchars($member['name'] ?? '') ?></td>
                <td><?= htmlspecialchars($member['relationship'] ?? '') ?></td>
                <td><?= htmlspecialchars($member['age'] ?? '') ?></td>
                <td><?= htmlspecialchars($member['occupation'] ?? '') ?></td>
                <td>PHP <?= number_format($member['income'] ?? 0, 2) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <?php if (!empty($rep)): ?>
    <div class="form-section">
        <h3>Representative</h3>
        <div class="info-grid">
            <div class="info-item"><label>Name</label><span><?= htmlspecialchars(trim(($rep['rep_first_name'] ?? '') . ' ' . ($rep['rep_middle_name'] ?? '') . ' ' . ($rep['rep_last_name'] ?? '') . ' ' . ($rep['rep_name_ext'] ?? ''))) ?></span></div>
            <div class="info-item"><label>Relationship</label><span><?= htmlspecialchars($rep['rep_relationship'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Phone</label><span><?= htmlspecialchars($rep['rep_phone'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Occupation</label><span><?= htmlspecialchars($rep['rep_occupation'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Birthdate / Age</label><span><?= !empty($rep['rep_birthdate']) ? date('M d, Y', strtotime($rep['rep_birthdate'])) : 'N/A' ?> / <?= number_format($rep['rep_age'] ?? 0) ?></span></div>
            <div class="info-item"><label>Gender</label><span><?= htmlspecialchars($rep['rep_gender'] ?? 'N/A') ?></span></div>
            <div class="info-item"><label>Civil Status</label><span><?= htmlspecialchars($rep['rep_civil_status'] ?? 'N/A') ?></span></div>
        </div>
    </div>
    <?php endif; ?>
</div>
</body>
</html>

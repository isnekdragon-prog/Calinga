<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT fullname,email,aics_cooldown_until FROM users WHERE id=?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$userName = $user['fullname'];
$cooldownUntil = $user['aics_cooldown_until'] ?? null;
$isOnCooldown = !empty($cooldownUntil) && strtotime($cooldownUntil) > time();
$stmt->close();

if ($isOnCooldown) {
    header('Location: aics.php?error=' . urlencode('You may submit another AICS request after ' . date('F d, Y', strtotime($cooldownUntil)) . '.'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>AICS Application - CALINGA MSWD</title>
<link rel="stylesheet" href="../style1.css">
<style>
body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 24px 14px; }
.container { max-width: 950px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);}
h2 { color: #2c3e50; margin-bottom: 5px; }
h3 { color: #2c3e50; margin-top: 20px; margin-bottom: 15px; border-bottom: 2px solid #3498db; padding-bottom: 5px;}
.form-row, .form-row-2, .form-row-3 { display: grid; gap: 15px; margin-bottom: 15px; }
.form-row { grid-template-columns: repeat(4,1fr); }
.form-row-3 { grid-template-columns: repeat(3,1fr); }
.form-row-2 { grid-template-columns: repeat(2,1fr); }
.form-group label { display: block; font-size: 14px; color: #555; margin-bottom: 5px;}
.form-group label span { color: #e74c3c;}
.form-group input, .form-group select, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; }
.form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: #3498db; }
.checkbox-group { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-top: 10px;}
.checkbox-group label { display: flex; align-items: center; gap: 8px; cursor: pointer; background: #f8f9fa; padding: 8px; border-radius: 5px; font-size: 13px; }
.checkbox-group input[type="checkbox"] { width: 16px; height: 16px; }
.table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }
table { width: 100%; border-collapse: collapse; margin-top: 15px; min-width: 640px; }
table th { background: #2c3e50; color: white; padding: 10px; font-size: 13px; text-align: left; }
table td { padding: 8px; border: 1px solid #ddd; }
table td input { width: 100%; padding: 6px; border: 1px solid #ccc; border-radius: 4px; font-size: 13px; }
.btn { padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 14px; font-weight: 500; border: none;}
.add-btn { background: #27ae60; color: #fff; margin-top: 10px; }
.remove-btn { background: #e74c3c; color: #fff; padding: 5px 10px; font-size: 12px; }
.btn-submit { width: 100%; background: #3498db; color: #fff; padding: 12px; font-size: 16px; font-weight: 600; margin-top: 20px; border-radius: 5px; cursor: pointer;}
.btn-submit:hover { background: #2980b9; }
.btn-back { display: inline-block; margin-bottom: 15px; color: #3498db; text-decoration: none; }
.page-nav { display: flex; gap: 18px; flex-wrap: wrap; align-items: center; }
.home-link { color: #2c3e50; font-weight: 600; }
.user-welcome { background: #e8f4fd; padding: 12px; border-radius: 5px; margin-bottom: 20px; font-size: 14px;}
@media (max-width: 900px) {
    .form-row, .form-row-2, .form-row-3 { grid-template-columns: 1fr 1fr; }
    .checkbox-group { grid-template-columns: repeat(2, 1fr) !important; }
}
@media (max-width: 640px) {
    body { padding: 16px 10px; }
    .container { padding: 18px 14px; border-radius: 14px; }
    .form-row, .form-row-2, .form-row-3 { grid-template-columns: 1fr; }
    .checkbox-group { grid-template-columns: 1fr !important; }
    .btn, .btn-submit, .add-btn { width: 100%; }
    .remove-btn { width: auto; }
    h2 { font-size: 1.45rem; }
    h3 { font-size: 1rem; }
    .form-group input, .form-group select, .form-group textarea { font-size: 16px; }
}
</style>
</head>
<body>

<nav class="page-nav" aria-label="Page navigation">
    <a href="aics.php" class="btn-back">← Back to AICS</a>
    <a href="../index.php" class="btn-back home-link">Return Home</a>
</nav>

<div class="container">
<h2>DSWD General Intake Sheet (GIS)</h2>
<p>AICS - Assistance to Individuals in Crisis Situation</p>

<div class="user-welcome">
    Welcome, <strong><?= htmlspecialchars($userName) ?></strong>!
</div>

<form action="aics_process.php" method="POST">

<!-- Beneficiary Information -->
<h3>Beneficiary Information</h3>
<div class="form-row">
    <div class="form-group"><label>Last Name <span>*</span></label><input type="text" name="last_name" required></div>
    <div class="form-group"><label>First Name <span>*</span></label><input type="text" name="first_name" required></div>
    <div class="form-group"><label>Middle Name</label><input type="text" name="middle_name"></div>
    <div class="form-group"><label>Extension</label><input type="text" name="name_extension" placeholder="Jr, Sr"></div>
</div>

<div class="form-row-3">
    <div class="form-group"><label>House No./Street/Purok</label><input type="text" name="house_no"></div>
    <div class="form-group"><label>Barangay <span>*</span></label><input type="text" name="barangay" required></div>
    <div class="form-group"><label>City/Municipality <span>*</span></label><input type="text" name="city_municipality" required></div>
</div>

<div class="form-row-2">
    <div class="form-group"><label>Province <span>*</span></label><input type="text" name="province" required></div>
    <div class="form-group"><label>Region</label><input type="text" name="region" placeholder="e.g., CALABARZON"></div>
</div>

<div class="form-row-2">
    <div class="form-group"><label>Phone Number <span>*</span></label><input type="text" name="phone" required></div>
    <div class="form-group"><label>Birthdate <span>*</span></label><input type="date" name="birthdate" onchange="calculateAge(this)" required></div>
</div>

<div class="form-row-2">
    <div class="form-group"><label>Age</label><input type="number" name="age" id="age" readonly></div>
    <div class="form-group"><label>Gender <span>*</span></label>
        <select name="gender" required>
            <option value="">-- Select --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>
</div>

<div class="form-row-2">
    <div class="form-group"><label>Civil Status</label>
        <select name="civil_status">
            <option value="">-- Select --</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widowed">Widowed</option>
            <option value="Separated">Separated</option>
            <option value="Co-habiting">Co-habiting</option>
        </select>
    </div>
    <div class="form-group"><label>Occupation</label><input type="text" name="occupation"></div>
</div>

<div class="form-row-2">
    <div class="form-group"><label>Monthly Income</label><input type="number" name="monthly_salary" step="0.01"></div>
    <div class="form-group"><label>Target Sector</label>
        <select name="target_sector">
            <option value="">-- Select --</option>
            <option value="FHONA">FHONA</option>
            <option value="SC">SC (Senior Citizen)</option>
            <option value="PWD">PWD</option>
            <option value="YNSP">YNSP (Youth)</option>
            <option value="WEDC">WEDC</option>
            <option value="PLHIV">PLHIV</option>
            <option value="CNSP">CNSP</option>
            <option value="Solo Parents">Solo Parents</option>
            <option value="Indigenous People">Indigenous People</option>
            <option value="Recovering Person who used drugs">Recovering Person who used drugs</option>
            <option value="4PS DSWD Beneficiary">4PS DSWD Beneficiary</option>
            <option value="Street Dwellers">Street Dwellers</option>
            <option value="Psychosocial/Mental/Learning Disability">Psychosocial/Mental/Learning Disability</option>
            <option value="Stateless Person/Asylum Seekers/Refugees">Stateless Person/Asylum Seekers/Refugees</option>
            <option value="Others">Others</option>
        </select>
    </div>
</div>

<!-- Representative Information -->
<h3>Representative Information (if different from beneficiary)</h3>
<div class="form-row">
    <div class="form-group"><label>Last Name</label><input type="text" name="rep_last_name"></div>
    <div class="form-group"><label>First Name</label><input type="text" name="rep_first_name"></div>
    <div class="form-group"><label>Middle Name</label><input type="text" name="rep_middle_name"></div>
    <div class="form-group"><label>Extension</label><input type="text" name="rep_extension" placeholder="Jr, Sr"></div>
</div>

<div class="form-row-3">
    <div class="form-group"><label>House No./Street/Purok</label><input type="text" name="rep_house_no"></div>
    <div class="form-group"><label>Barangay</label><input type="text" name="rep_barangay"></div>
    <div class="form-group"><label>City/Municipality</label><input type="text" name="rep_city"></div>
</div>

<div class="form-row-2">
    <div class="form-group"><label>Province</label><input type="text" name="rep_province"></div>
    <div class="form-group"><label>Region</label><input type="text" name="rep_region" placeholder="e.g., CALABARZON"></div>
</div>

<div class="form-row-2">
    <div class="form-group"><label>Phone Number</label><input type="text" name="rep_phone"></div>
    <div class="form-group"><label>Birthdate</label><input type="date" name="rep_birthdate" onchange="calculateRepAge(this)"></div>
</div>

<div class="form-row-2">
    <div class="form-group"><label>Age</label><input type="number" name="rep_age" id="rep_age" readonly></div>
    <div class="form-group"><label>Gender</label>
        <select name="rep_gender">
            <option value="">-- Select --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>
</div>

<div class="form-row-2">
    <div class="form-group"><label>Civil Status</label>
        <select name="rep_civil_status">
            <option value="">-- Select --</option>
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Widowed">Widowed</option>
            <option value="Separated">Separated</option>
            <option value="Co-habiting">Co-habiting</option>
        </select>
    </div>
    <div class="form-group"><label>Occupation</label><input type="text" name="rep_occupation"></div>
</div>

<div class="form-row-2">
    <div class="form-group"><label>Monthly Income</label><input type="number" name="rep_monthly_salary" step="0.01"></div>
    <div class="form-group"><label>Relationship to Beneficiary</label><input type="text" name="rep_relationship" placeholder="e.g., Son, Daughter, Spouse"></div>
</div>

<div class="form-row-2">
    <div class="form-group"><label>Valid ID Type</label>
        <select name="rep_valid_id">
            <option value="">-- Select --</option>
            <option value="Philippine Passport">Philippine Passport</option>
            <option value="Driver's License">Driver's License</option>
            <option value="SSS ID">SSS ID</option>
            <option value="GSIS ID">GSIS ID</option>
            <option value="PhilHealth ID">PhilHealth ID</option>
            <option value="TIN ID">TIN ID</option>
            <option value="Voter's ID">Voter's ID</option>
            <option value="Barangay ID">Barangay ID</option>
            <option value="Senior Citizen ID">Senior Citizen ID</option>
            <option value="PWD ID">PWD ID</option>
            <option value="Other">Other</option>
        </select>
    </div>
    <div class="form-group"><label>Valid ID Number</label><input type="text" name="rep_valid_id_number"></div>
</div>

<!-- Family Composition -->
<h3>Family Composition</h3>
<div class="table-scroll">
<table id="familyTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Relation</th>
            <th>Age</th>
            <th>Occupation</th>
            <th>Income</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><input type="text" name="family_name[]"></td>
            <td><input type="text" name="family_relationship[]"></td>
            <td><input type="number" name="family_age[]"></td>
            <td><input type="text" name="family_occupation[]"></td>
            <td><input type="number" name="family_income[]" step="0.01"></td>
            <td><button type="button" class="remove-btn" onclick="removeRow(this)">X</button></td>
        </tr>
    </tbody>
</table>
</div>
<button type="button" class="btn add-btn" onclick="addFamilyRow()">+ Add Family Member</button>

<!-- Assistance Needed -->
<h3>Type of Assistance Needed</h3>

<!-- Financial Assistance -->
<div class="form-group">
    <label>Financial Assistance</label>
    <div class="checkbox-group" style="grid-template-columns: repeat(3, 1fr);">
        <label><input type="checkbox" name="assistance_category[]" value="Financial-Medical"> Medical</label>
        <label><input type="checkbox" name="assistance_category[]" value="Financial-Funeral"> Funeral</label>
        <label><input type="checkbox" name="assistance_category[]" value="Financial-Transportation"> Transportation</label>
        <label><input type="checkbox" name="assistance_category[]" value="Financial-Educational"> Educational</label>
        <label><input type="checkbox" name="assistance_category[]" value="Financial-Cash Assistance"> Cash Assistance</label>
        <label><input type="checkbox" name="assistance_category[]" value="Financial-Other Support"> Other Support Services</label>
    </div>
</div>

<!-- Material Assistance -->
<div class="form-group" style="margin-top: 15px;">
    <label>Material Assistance</label>
    <div class="checkbox-group" style="grid-template-columns: repeat(2, 1fr);">
        <label><input type="checkbox" name="assistance_category[]" value="Material-Family Food Packs"> Family Food Packs</label>
        <label><input type="checkbox" name="assistance_category[]" value="Material-Other Food Items"> Other Food Items</label>
        <label><input type="checkbox" name="assistance_category[]" value="Material-Hygiene Sleeping Kits"> Hygiene & Sleeping Kits</label>
        <label><input type="checkbox" name="assistance_category[]" value="Material-Assistive Device"> Assistive Device & Technologies</label>
    </div>
</div>

<!-- Psychosocial Support -->
<div class="form-group" style="margin-top: 15px;">
    <label>Psychosocial Support</label>
    <div class="checkbox-group" style="grid-template-columns: repeat(3, 1fr);">
        <label><input type="checkbox" name="assistance_category[]" value="Psychosocial-Referral"> Referral</label>
        <label><input type="checkbox" name="assistance_category[]" value="Psychosocial-Psychological First Aid"> Psychological First Aid</label>
        <label><input type="checkbox" name="assistance_category[]" value="Psychosocial-Social Work Counseling"> Social Work Counseling</label>
    </div>
</div>

<!-- Hidden field for assistance type (required by database) -->
<input type="hidden" name="assistance_type" value="Multiple">

<div class="form-group" style="margin-top: 15px;">
    <label>Description of Need <span>*</span></label>
    <textarea name="description" rows="4" required></textarea>
</div>



<button type="submit" class="btn-submit">Submit Application</button>
</form>
</div>

<script>
function getAgeFromDate(dateValue){
    if (!dateValue) {
        return '';
    }

    const birthdate = new Date(dateValue);
    if (Number.isNaN(birthdate.getTime())) {
        return '';
    }

    const today = new Date();
    let age = today.getFullYear() - birthdate.getFullYear();
    const monthDiff = today.getMonth() - birthdate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
        age--;
    }

    return age >= 0 ? age : '';
}

function calculateAge(dateField){
    document.getElementById("age").value = getAgeFromDate(dateField.value);
}

function calculateRepAge(dateField){
    document.getElementById("rep_age").value = getAgeFromDate(dateField.value);
}

function addFamilyRow(){
    const table = document.querySelector("#familyTable tbody");
    const row = table.insertRow();
    row.innerHTML = `
        <td><input type="text" name="family_name[]"></td>
        <td><input type="text" name="family_relationship[]"></td>
        <td><input type="number" name="family_age[]"></td>
        <td><input type="text" name="family_occupation[]"></td>
        <td><input type="number" name="family_income[]" step="0.01"></td>
        <td><button type="button" class="remove-btn" onclick="removeRow(this)">X</button></td>
    `;
}

function removeRow(btn){
    const row = btn.closest("tr");
    const tbody = document.querySelector("#familyTable tbody");
    if(tbody.rows.length > 1) row.remove();
}

document.addEventListener("DOMContentLoaded", function () {
    const birthdateField = document.querySelector('input[name="birthdate"]');
    const repBirthdateField = document.querySelector('input[name="rep_birthdate"]');

    if (birthdateField && birthdateField.value) {
        calculateAge(birthdateField);
    }

    if (repBirthdateField && repBirthdateField.value) {
        calculateRepAge(repBirthdateField);
    }
});
</script>

</body>
</html>

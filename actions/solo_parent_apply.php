<?php
session_start();
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT fullname, email FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$userName = $user['fullname'] ?? '';
$userEmail = $user['email'] ?? '';
$error = $_GET['error'] ?? '';

function old(string $key, string $default = ''): string
{
    return htmlspecialchars($_POST[$key] ?? $default, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Solo Parent Application - CALINGA MSWD</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #eaf3ff;
    margin: 0;
    padding: 18px 10px;
    color: #1f2d55;
}

.container {
    max-width: 1100px;
    margin: auto;
    background: #fff;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(31, 45, 85, 0.1);
    border: 1px solid #dce8ff;
}

h1, h2, h3 {
    color: #1d4bd8;
}

h1 {
    margin-bottom: 0.25rem;
}

.subtitle {
    margin-top: 0;
    color: #51607f;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    padding: 0.85rem 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
}

.section {
    margin-top: 28px;
    padding-top: 8px;
    border-top: 2px solid #dce8ff;
}

.section h3 {
    margin-bottom: 12px;
}

.form-row,
.form-row-2,
.form-row-3 {
    display: grid;
    gap: 15px;
    margin-bottom: 15px;
}

.form-row,
.form-row-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}

.form-row-3 {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

.form-row-4 {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 15px;
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-size: 14px;
    margin-bottom: 6px;
    color: #42506f;
    font-weight: 600;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    box-sizing: border-box;
    min-height: 42px;
    border: 1px solid #c5d6ff;
    border-radius: 6px;
    padding: 10px;
    background: #fff;
    font-size: 14px;
}

.form-group textarea {
    min-height: 110px;
    resize: vertical;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #1d4bd8;
    box-shadow: 0 0 0 3px rgba(29, 75, 216, 0.12);
    outline: none;
}

.choice-group {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    padding: 11px 12px;
    border: 1px solid #c5d6ff;
    border-radius: 6px;
    min-height: 42px;
    align-items: center;
}

.choice-group label {
    margin: 0;
    font-weight: 500;
    color: #1f2d55;
}

.table-scroll {
    overflow-x: auto;
}

table {
    width: 100%;
    min-width: 980px;
    border-collapse: collapse;
    margin-top: 12px;
}

table th {
    background: #3498db;
    color: #fff;
    padding: 10px;
    font-size: 13px;
    text-align: left;
}

table td {
    padding: 8px;
    border: 1px solid #ddd;
    vertical-align: top;
}

table td input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.btn,
.btn-submit,
.btn-back {
    border-radius: 6px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
}

.btn-back {
    color: #3498db;
    margin-bottom: 12px;
}

.page-nav {
    display: flex;
    gap: 18px;
    flex-wrap: wrap;
    align-items: center;
}

.home-link {
    color: #1f2d55;
    font-weight: 600;
}

.btn {
    background: #1d4bd8;
    color: #fff;
    padding: 10px 18px;
    margin-top: 10px;
}

.remove-btn {
    background: #c0392b;
    color: #fff;
    padding: 6px 10px;
}

.btn-submit {
    width: 100%;
    background: #3498db;
    color: #fff;
    padding: 13px;
    font-size: 16px;
    font-weight: 700;
    margin-top: 24px;
}

.muted {
    color: #63718f;
    font-size: 13px;
}

@media (max-width: 900px) {
    .form-row,
    .form-row-2,
    .form-row-3,
    .form-row-4 {
        grid-template-columns: 1fr;
    }
}
</style>
</head>
<body>
<div class="container">
    <nav class="page-nav" aria-label="Page navigation">
        <a href="solo_parent.php" class="btn-back">&larr; Back</a>
        <a href="../index.php" class="btn-back home-link">Return Home</a>
    </nav>

    <h1>Solo Parent Application Form</h1>
    <p class="subtitle">Updated to match the latest Solo Parent form requirements.</p>

    <?php if ($error): ?>
        <div class="alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="solo_parent_process.php" method="POST">
        <div class="section">
            <h3>Beneficiary Information</h3>

            <div class="form-row-4">
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" name="middle_name">
                </div>
                <div class="form-group">
                    <label>Name Extension</label>
                    <input type="text" name="name_extension">
                </div>
            </div>

            <div class="form-row-3">
                <div class="form-group">
                    <label>Age</label>
                    <input type="number" name="age" id="age" readonly>
                </div>
                <div class="form-group">
                    <label>Sex</label>
                    <select name="gender" required>
                        <option value="">Select sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Civil Status</label>
                    <select name="civil_status">
                        <option value="">Select civil status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Separated">Separated</option>
                        <option value="Annulled">Annulled</option>
                        <option value="Live-in">Live-in</option>
                    </select>
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" name="birthdate" onchange="calculateAge(this)">
                </div>
                <div class="form-group">
                    <label>Place of Birth</label>
                    <input type="text" name="place_of_birth">
                </div>
            </div>

            <div class="form-row-4">
                <div class="form-group">
                    <label>House No. / Street</label>
                    <input type="text" name="house_no">
                </div>
                <div class="form-group">
                    <label>Barangay</label>
                    <input type="text" name="barangay" required>
                </div>
                <div class="form-group">
                    <label>City / Municipality</label>
                    <input type="text" name="city_municipality" required>
                </div>
                <div class="form-group">
                    <label>Province / Region</label>
                    <input type="text" name="province" required>
                    <input type="hidden" name="region" value="Region IV-A">
                </div>
            </div>

            <div class="form-row-3">
                <div class="form-group">
                    <label>Highest Educational Attainment</label>
                    <input type="text" name="education">
                </div>
                <div class="form-group">
                    <label>Religion</label>
                    <select name="religion">
                        <option value="">Select religion</option>
                        <option value="Roman Catholic">Roman Catholic</option>
                        <option value="Christian">Christian</option>
                        <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                        <option value="Islam">Islam</option>
                        <option value="Born Again">Born Again</option>
                        <option value="Seventh-day Adventist">Seventh-day Adventist</option>
                        <option value="Jehovah's Witnesses">Jehovah's Witnesses</option>
                        <option value="Buddhism">Buddhism</option>
                        <option value="Hinduism">Hinduism</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Occupation</label>
                    <input type="text" name="occupation">
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label>Monthly Income</label>
                    <input type="number" step="0.01" min="0" name="monthly_income">
                </div>
                <div class="form-group">
                    <label>Employer Address</label>
                    <input type="text" name="employer_address">
                </div>
            </div>

            <div class="form-row-3">
                <div class="form-group">
                    <label>Family Income</label>
                    <input type="number" step="0.01" min="0" name="family_income">
                </div>
                <div class="form-group">
                    <label>Contact No.</label>
                    <input type="text" name="phone" required>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($userEmail) ?>">
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label>Name of Spouse / Partner</label>
                    <input type="text" name="spouse_name">
                </div>
                <div class="form-group">
                    <label>Address (If Known)</label>
                    <input type="text" name="spouse_address">
                </div>
            </div>

            <div class="form-group">
                <label>Housing Status</label>
                <div class="choice-group">
                    <label><input type="radio" name="housing_status" value="Homeowner"> Homeowner</label>
                    <label><input type="radio" name="housing_status" value="Renter"> Renter</label>
                    <label><input type="radio" name="housing_status" value="Sharer"> Sharer</label>
                    <label><input type="radio" name="housing_status" value="Family Extended Unit"> Family Extended Unit</label>
                    <label><input type="radio" name="housing_status" value="Caretaker"> Caretaker</label>
                </div>
            </div>
        </div>

        <div class="section">
            <h3>Emergency and Membership Information</h3>

            <div class="form-row-3">
                <div class="form-group">
                    <label>In Case of Emergency</label>
                    <input type="text" name="emergency_contact_name">
                </div>
                <div class="form-group">
                    <label>Emergency Address</label>
                    <input type="text" name="emergency_contact_address">
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="emergency_contact_number">
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label>4Ps Member?</label>
                    <div class="choice-group">
                        <label><input type="radio" name="member_4ps" value="1"> Yes</label>
                        <label><input type="radio" name="member_4ps" value="0" checked> No</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>LGBTQ Member?</label>
                    <div class="choice-group">
                        <label><input type="radio" name="member_lgbtq" value="1"> Yes</label>
                        <label><input type="radio" name="member_lgbtq" value="0" checked> No</label>
                    </div>
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label>Are you a PhilHealth Member?</label>
                    <div class="choice-group">
                        <label><input type="radio" name="philhealth_member" value="1"> Yes</label>
                        <label><input type="radio" name="philhealth_member" value="0" checked> No</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>PhilHealth No.</label>
                    <input type="text" name="philhealth_number">
                </div>
            </div>

            <div class="form-row-4">
                <div class="form-group">
                    <label>Membership Category</label>
                    <select name="philhealth_membership_category">
                        <option value="">Select membership category</option>
                        <option value="Direct Contributor">Direct Contributor</option>
                        <option value="Indirect Contributor">Indirect Contributor</option>
                        <option value="Sponsored">Sponsored</option>
                        <option value="Indigent">Indigent</option>
                        <option value="Senior Citizen">Senior Citizen</option>
                        <option value="Lifetime Member">Lifetime Member</option>
                        <option value="Formal Economy">Formal Economy</option>
                        <option value="Informal Economy">Informal Economy</option>
                        <option value="OFW">OFW</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Dependent?</label>
                    <select name="philhealth_dependent">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Dependent Name</label>
                    <input type="text" name="philhealth_dependent_name">
                </div>
                <div class="form-group">
                    <label>Dependent Number / Relationship</label>
                    <input type="text" name="philhealth_dependent_number" placeholder="Dependent number">
                    <input type="text" name="philhealth_relationship" placeholder="Relationship" style="margin-top:8px;">
                </div>
            </div>
        </div>

        <div class="section">
            <h3>Family Composition</h3>
            <p class="muted">Include family members and other members of the household.</p>

            <div class="table-scroll">
                <table id="familyTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Birthdate</th>
                            <th>Relationship</th>
                            <th>Age</th>
                            <th>Educational Attainment</th>
                            <th>Occupation</th>
                            <th>Income</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="family_name[]"></td>
                            <td><input type="date" name="family_birthdate[]"></td>
                            <td><input type="text" name="family_relationship[]"></td>
                            <td><input type="number" name="family_age[]"></td>
                            <td><input type="text" name="family_education[]"></td>
                            <td><input type="text" name="family_occupation[]"></td>
                            <td><input type="number" step="0.01" min="0" name="family_member_income[]"></td>
                            <td><button type="button" class="remove-btn" onclick="removeRow(this)">X</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button type="button" class="btn" onclick="addFamily()">Add Member</button>
        </div>

        <div class="section">
            <h3>Narrative Information</h3>

            <div class="form-group">
                <label>Classification / Circumstances of Being a Solo Parent</label>
                <textarea name="solo_parent_classification"></textarea>
            </div>

            <div class="form-group">
                <label>Needs / Problems of Solo Parent</label>
                <textarea name="solo_parent_needs"></textarea>
            </div>

            <div class="form-group">
                <label>Family Resources</label>
                <textarea name="family_resources"></textarea>
            </div>

            <div class="form-group">
                <label>How Did You Know About the Solo Parent Program?</label>
                <textarea name="hear_about_program"></textarea>
            </div>

            <div class="form-group">
                <label>Skills / Potentials / Hobbies</label>
                <textarea name="skills_hobbies"></textarea>
            </div>

            <div class="form-group">
                <label>Purpose of Securing Solo Parent ID</label>
                <textarea name="solo_parent_id_purpose"></textarea>
            </div>
        </div>

        <div class="section">
            <h3>Certification</h3>

            <div class="form-row-2">
                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="certification_date">
                </div>
                <div class="form-group">
                    <label>Signature / Printed Name</label>
                    <input type="text" name="applicant_signature" value="<?= htmlspecialchars($userName) ?>">
                </div>
            </div>
        </div>

        <button type="submit" class="btn-submit">Submit Application</button>
    </form>
</div>

<script>
function calculateAge(dateField) {
    if (!dateField.value) {
        document.getElementById('age').value = '';
        return;
    }

    const birthdate = new Date(dateField.value);
    const today = new Date();
    let age = today.getFullYear() - birthdate.getFullYear();
    const monthDiff = today.getMonth() - birthdate.getMonth();

    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
        age--;
    }

    document.getElementById('age').value = age >= 0 ? age : 0;
}

function addFamily() {
    const tbody = document.querySelector('#familyTable tbody');
    const row = document.createElement('tr');
    row.innerHTML = `
        <td><input type="text" name="family_name[]"></td>
        <td><input type="date" name="family_birthdate[]"></td>
        <td><input type="text" name="family_relationship[]"></td>
        <td><input type="number" name="family_age[]"></td>
        <td><input type="text" name="family_education[]"></td>
        <td><input type="text" name="family_occupation[]"></td>
        <td><input type="number" step="0.01" min="0" name="family_member_income[]"></td>
        <td><button type="button" class="remove-btn" onclick="removeRow(this)">X</button></td>
    `;
    tbody.appendChild(row);
}

function removeRow(button) {
    const row = button.closest('tr');
    const tbody = row.parentElement;
    if (tbody.children.length > 1) {
        row.remove();
    } else {
        row.querySelectorAll('input').forEach((input) => {
            input.value = '';
        });
    }
}
</script>
</body>
</html>

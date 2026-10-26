<?php
$sql="INSERT INTO aics_applications 
    (user_id, last_name, first_name, middle_name, name_extension, house_no, barangay, city_municipality, province, phone, birthdate, age, gender, civil_status, occupation, monthly_salary, target_sector, beneficiary_category, family_composition, assistance_type, financial_assistance_type, cash_amount, material_assistance, psychosocial_support, referring_party, description_of_need, rep_last_name, rep_first_name, rep_middle_name, rep_extension, rep_house_no, rep_barangay, rep_city, rep_province, rep_phone, rep_relationship, status) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
echo "placeholders=" . substr_count($sql,'?') . "\n";
echo "columns=" . preg_match_all('/\(/',$sql) . "\n"; // not accurate
?>
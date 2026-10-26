<?php
session_start();
require_once __DIR__ . '/db.php';

$loggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - CALINGA MSWD</title>
    <link rel="stylesheet" href="../style1.css">
    <style>
        .policy-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .policy-container h1 {
            color: #1a73e8;
            margin-bottom: 0.5rem;
        }
        .policy-container .last-updated {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }
        .policy-container h2 {
            color: #1e293b;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
            font-size: 1.2rem;
        }
        .policy-container p, .policy-container ul {
            color: #475569;
            line-height: 1.7;
        }
        .policy-container ul {
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }
        .policy-container ul li {
            margin-bottom: 0.5rem;
        }
        .btn-back {
            display: inline-block;
            padding: 0.6rem 1.2rem;
            background: #1a73e8;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            margin-bottom: 1rem;
        }
        .btn-back:hover { background: #1557b0; }
    </style>
</head>
<body>

<div class="policy-container">
    <a href="../index.php" class="btn-back">← Back to Home</a>
    
    <h1>Privacy Policy</h1>
    <p class="last-updated">Last Updated: <?= date('F d, Y') ?></p>

    <h2>1. Information We Collect</h2>
    <p>CALINGA - Municipal Social Welfare and Development Office collects the following information:</p>
    <ul>
        <li><strong>Personal Information:</strong> Full name, email address, phone number, date of birth, gender, civil status, and address.</li>
        <li><strong>Application Data:</strong> Information submitted through AICS and Solo Parent application forms including family composition, income details, and supporting documents.</li>
        <li><strong>Account Information:</strong> Login credentials, profile information, and notification preferences.</li>
        <li><strong>Usage Data:</strong> IP address, browser type, pages visited, and timestamps for security and audit purposes.</li>
    </ul>

    <h2>2. How We Use Your Information</h2>
    <p>Your information is used for the following purposes:</p>
    <ul>
        <li>Processing and evaluating AICS and Solo Parent applications</li>
        <li>Generating official documents, tickets, and certificates</li>
        <li>Sending notifications about application status updates</li>
        <li>Maintaining accurate records for government social welfare programs</li>
        <li>Improving our services and user experience</li>
        <li>Ensuring system security and preventing fraud</li>
    </ul>

    <h2>3. Data Protection</h2>
    <p>We implement strict security measures to protect your personal information:</p>
    <ul>
        <li>All data is encrypted during transmission using HTTPS/SSL protocols</li>
        <li>Passwords are securely hashed and never stored in plain text</li>
        <li>Access to personal data is restricted to authorized MSWD personnel only</li>
        <li>Regular security audits and monitoring are conducted</li>
        <li>CSRF tokens and session validation protect against unauthorized access</li>
    </ul>

    <h2>4. Data Sharing</h2>
    <p>We do not sell, trade, or share your personal information with third parties except:</p>
    <ul>
        <li>As required by law or government regulations</li>
        <li>With authorized MSWD personnel for application processing</li>
        <li>With other government agencies involved in social welfare programs</li>
    </ul>

    <h2>5. Data Retention</h2>
    <p>Your personal information and application records are retained for the period required by government record-keeping regulations. Application data is kept for a minimum of 2 years or as mandated by the Department of Social Welfare and Development (DSWD).</p>

    <h2>6. Your Rights</h2>
    <p>Under the Data Privacy Act of 2012 (Republic Act No. 10173), you have the right to:</p>
    <ul>
        <li>Access your personal information</li>
        <li>Request correction of inaccurate data</li>
        <li>Request deletion of your data (subject to legal retention requirements)</li>
        <li>Withdraw consent for data processing</li>
        <li>File a complaint with the National Privacy Commission (NPC)</li>
    </ul>

    <h2>7. Cookies</h2>
    <p>We use essential cookies to:</p>
    <ul>
        <li>Maintain your login session</li>
        <li>Remember your cookie consent preferences</li>
        <li>Ensure proper system functionality</li>
    </ul>
    <p>We do not use cookies for advertising or tracking purposes.</p>

    <h2>8. Audit Logging</h2>
    <p>For security purposes, we maintain audit logs of system activities including:</p>
    <ul>
        <li>Page access records</li>
        <li>Application processing actions (approvals/declines)</li>
        <li>Administrative actions</li>
        <li>IP addresses and timestamps</li>
    </ul>
    <p>These logs are used solely for security monitoring and accountability.</p>

    <h2>9. Contact Us</h2>
    <p>If you have questions about this Privacy Policy or your personal data, please contact:</p>
    <ul>
        <li><strong>MSWD Office Cainta</strong></li>
        <li>Cainta, Rizal, Philippines</li>
        <li>Visit our office during official working hours</li>
    </ul>

    <h2>10. Policy Updates</h2>
    <p>We may update this Privacy Policy from time to time. Users will be notified of significant changes through system announcements or email notifications.</p>
</div>

</body>
</html>

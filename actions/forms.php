<?php
session_start();
require_once __DIR__ . '/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user's AICS applications
$aics_applications = $conn->prepare("
    SELECT a.id, a.status, a.created_at, a.financial_assistance_type AS assistance_type,
           MIN(ap.first_name) AS first_name,
           MIN(ap.middle_name) AS middle_name,
           MIN(ap.last_name) AS last_name,
           MIN(ap.gender) AS gender,
           MIN(ad.barangay) AS barangay
    FROM aics_applications a
    LEFT JOIN aics_applicants ap ON ap.application_id = a.id
    LEFT JOIN aics_addresses ad ON ad.application_id = a.id
    WHERE a.user_id = ?
    GROUP BY a.id
    ORDER BY a.created_at DESC
");
$aics_applications->bind_param("i", $user_id);
$aics_applications->execute();
$aics_result = $aics_applications->get_result();

// Fetch user's Solo Parent applications
$solo_parent_applications = $conn->prepare("
    SELECT sp.id, sp.status, sp.created_at,
           sp_personal.first_name, sp_personal.middle_name, sp_personal.last_name, sp_personal.name_extension, sp_personal.phone as personal_phone,
           sp_address.house_no, sp_address.barangay, sp_address.city_municipality, sp_address.province, sp_address.region
    FROM solo_parent_applications sp
    LEFT JOIN solo_parent_personal sp_personal ON sp_personal.application_id = sp.id
    LEFT JOIN solo_parent_address sp_address ON sp_address.application_id = sp.id
    WHERE sp.user_id = ?
    ORDER BY sp.created_at DESC
");
$solo_parent_applications->bind_param("i", $user_id);
$solo_parent_applications->execute();
$solo_result = $solo_parent_applications->get_result();

$tab = $_GET['tab'] ?? 'aics';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - CALINGA</title>
    <link rel="stylesheet" href="../style1.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f3f2ee;
            margin: 0;
            padding: 2rem 1.5rem;
            color: #2d3d4e;
        }

        .applications-wrapper {
            max-width: 1120px;
            margin: 0 auto;
            padding-top: 0.5rem;
        }

        .applications-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.1rem;
        }

        h1 {
            margin: 0;
            font-size: 2.4rem;
            font-weight: 700;
            color: #2d3d4e;
        }

        .home-btn {
            display: inline-block;
            padding: 0.65rem 1rem;
            border-radius: 6px;
            background: #2d4252;
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            white-space: nowrap;
        }

        .applications-card {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            box-shadow: none;
            overflow: hidden;
        }

        .tab-nav {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .tab-nav a {
            display: inline-block;
            padding: 0.7rem 1.3rem;
            border-radius: 6px;
            text-decoration: none;
            background: #dfe4eb;
            color: #333;
            font-weight: 600;
        }

        .tab-nav a.active {
            background: #2d4252;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        th, td {
            text-align: left;
            padding: 16px 18px;
            border-bottom: 1px solid rgba(36, 51, 61, 0.12);
        }

        th {
            background: #2d4252;
            color: #fff;
            font-size: 1.06rem;
            font-weight: 700;
        }

        tbody tr:nth-child(even) {
            background: rgba(245, 247, 249, 0.7);
        }

        tbody tr:hover {
            background: #edf3f8;
        }

        td {
            color: #2d3d4e;
            font-size: 1.05rem;
        }

        .ellipsis {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 104px;
            height: 28px;
            padding: 0.2rem 0.8rem;
            border-radius: 12px;
            font-size: 0.82rem;
            font-weight: 700;
            text-align: center;
            line-height: 1;
            text-transform: capitalize;
            letter-spacing: 0.01em;
            border: 1px solid transparent;
        }

        .status-pending {
            background: #f2e4ac;
            color: #b8861d;
            border-color: rgba(184, 134, 29, 0.18);
        }

        .status-approved {
            background: #d4f0d8;
            color: #3d7d5d;
            border-color: rgba(61, 125, 93, 0.18);
        }

        .status-declined {
            background: #f8dfe3;
            color: #b55262;
            border-color: rgba(181, 82, 98, 0.16);
        }

        .view-btn {
            display: inline-block;
            background: linear-gradient(180deg, #67b3dd 0%, #4d9bc9 100%);
            color: #fff;
            border-radius: 8px;
            padding: 0.7rem 1.1rem;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.98rem;
            min-width: 80px;
            text-align: center;
            box-shadow: 0 6px 16px rgba(72, 142, 194, 0.24);
        }

        @media (max-width: 720px) {
            body {
                padding: 1.2rem 0.8rem;
            }

            .applications-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .applications-wrapper {
                max-width: 100%;
            }

            table {
                min-width: 760px;
            }

            .table-wrap {
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <div class="applications-wrapper">
        <div class="applications-header">
            <h1>My Applications</h1>
            <a href="../index.php" class="home-btn">Return Home</a>
        </div>

        <div class="tab-nav">
            <a href="?tab=aics" class="<?= $tab == 'aics' ? 'active' : '' ?>">AICS Applications</a>
            <a href="?tab=solo_parent" class="<?= $tab == 'solo_parent' ? 'active' : '' ?>">Solo Parent Applications</a>
        </div>

        <div class="applications-card">
            <section id="aics" class="<?= $tab == 'aics' ? 'active' : '' ?>">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th style="width: 220px;">Type</th>
                                <th style="width: 180px;">Date</th>
                                <th style="width: 170px;">Status</th>
                                <th style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $aics_result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $row['id'] ?></td>
                                <td class="ellipsis" title="<?= htmlspecialchars($row['assistance_type'] ?? '') ?>"><?= htmlspecialchars($row['assistance_type'] ?? '') ?></td>
                                <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                                <td>
                                    <span class="status-badge status-<?= strtolower($row['status'] ?? 'pending') ?>"><?= ucfirst($row['status'] ?? 'Pending') ?></span>
                                </td>
                                <td><a href="aics_view.php?id=<?= $row['id'] ?>" class="view-btn">View</a></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section id="solo_parent" class="<?= $tab == 'solo_parent' ? 'active' : '' ?>">
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th style="width:70px;">ID</th>
                                <th style="width:220px;">Applicant Name</th>
                                <th style="width:260px;">Address</th>
                                <th style="width:140px;">Contact No.</th>
                                <th style="width:160px;">Date</th>
                                <th style="width:120px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $solo_result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $row['id'] ?></td>
                                <td class="ellipsis" title="<?= htmlspecialchars(trim(($row['first_name'] ?? '') . ' ' . ($row['middle_name'] ?? '') . ' ' . ($row['last_name'] ?? '') . ' ' . ($row['name_extension'] ?? ''))) ?>">
                                    <?= htmlspecialchars(trim(($row['first_name'] ?? '') . ' ' . ($row['middle_name'] ?? '') . ' ' . ($row['last_name'] ?? '') . ' ' . ($row['name_extension'] ?? ''))) ?>
                                </td>
                                <td class="ellipsis" title="<?= htmlspecialchars(trim(($row['house_no'] ?? '') . ' ' . ($row['barangay'] ?? '') . ', ' . ($row['city_municipality'] ?? '') . ', ' . ($row['province'] ?? ''))) ?>">
                                    <?= htmlspecialchars(trim(($row['house_no'] ?? '') . ' ' . ($row['barangay'] ?? '') . ', ' . ($row['city_municipality'] ?? '') . ', ' . ($row['province'] ?? ''))) ?>
                                </td>
                                <td><?= htmlspecialchars($row['personal_phone'] ?? '') ?></td>
                                <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                                <td><span class="status-badge status-<?= strtolower($row['status'] ?? 'pending') ?>"><?= ucfirst($row['status'] ?? 'Pending') ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab') || 'aics';
        document.querySelectorAll('section').forEach((section) => section.style.display = 'none');
        const activeSection = document.getElementById(tab);
        if (activeSection) activeSection.style.display = 'block';
    </script>
</body>
</html>
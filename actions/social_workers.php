<?php
session_start();
require_once __DIR__ . '/db.php';

$loggedIn = false;
$userName = "";
$isAdmin = false;

if (isset($_SESSION['user_id'])) {
    $loggedIn = true;

    $stmt = $conn->prepare("SELECT fullname, profile_image, COALESCE(is_admin,0) AS is_admin FROM users WHERE id=?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $userName = $user['fullname'];
        $userProfileImage = $user['profile_image'] ?? '';
        $isAdmin = (bool)$user['is_admin'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MSWD - Social Workers | CALINGA</title>
<link rel="icon" href="../imgs/c4.png" type="image/png">

<style>
/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
html { scroll-behavior: smooth; }
body {
    font-family: 'Segoe UI', sans-serif;
    line-height: 1.6;
    color: #333;
    padding-top: 80px;
    background: #f4f6f9;
}

/* NAVIGATION */
.menu {
    position: fixed;
    width: 100%;
    top: 0;
    display: flex;
    align-items: center;
    padding: 0.8rem 2rem;
    background: #2c3e50;
    z-index: 1000;
}
.menu__logo {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    color: #fff;
    font-weight: bold;
    font-size: 1.2rem;
}
.menu__logo img { height: 50px; }
.menu__links {
    list-style: none;
    display: flex;
    gap: 2rem;
    margin-left: 3rem;
}
.menu__links a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    position: relative;
    transition: 0.3s;
}
.menu__links a::after {
    content: "";
    position: absolute;
    width: 0%;
    height: 2px;
    left: 0;
    bottom: -5px;
    background: #3498db;
    transition: 0.3s;
}
.menu__links a:hover { color: #3498db; }
.menu__links a:hover::after { width: 100%; }

/* MENU AUTH */
.menu__auth {
    margin-left: auto;
    display: flex;
    align-items: center;
    gap: 10px;
}

.menu__auth .page-auth-open {
    margin: 0;
    font: inherit;
    font-weight: 700;
    cursor: pointer;
}

/* Dropdown */
.auth-dropdown {
    position: relative;
    display: inline-block;
    font-family: 'Orbitron', sans-serif;
}
.auth-dropdown .dropdown-toggle {
    position: relative;
    top: 3px;
    background-color: #1e88e5;
    color: #fff;
    border: none;
    padding: 8px 16px;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
    transition: 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.65rem;
}
.auth-dropdown .dropdown-toggle:hover { background-color: #3498db; }
.auth-dropdown .dropdown-menu {
    display: none;
    position: absolute;
    top: 110%;
    right: 0;
    background-color: #fff;
    min-width: 200px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    border-radius: 8px;
    overflow: hidden;
    z-index: 1000;
}
.auth-dropdown .dropdown-menu a,
.auth-dropdown .dropdown-menu .notification-item {
    display: block;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
    border-bottom: 1px solid #eee;
    font-size: 0.9rem;
}
.auth-dropdown .dropdown-menu a:hover,
.auth-dropdown .dropdown-menu .notification-item:hover {
    background-color: #f5f5f5;
}
.auth-dropdown .dropdown-menu .notification-item:last-child,
.auth-dropdown .dropdown-menu a:last-child { border-bottom: none; }

.nav-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    background: rgba(255, 255, 255, 0.18);
    color: #fff;
    font-size: 0.95rem;
    font-weight: 800;
    border: 1px solid rgba(255, 255, 255, 0.18);
}

.nav-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* HERO */
.hero {
    height: 50vh;
    background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                url('../imgs/mon.jpg') center/cover no-repeat;
    background-attachment: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #fff;
}
.hero h1 {
    font-size: 3rem;
    letter-spacing: 3px;
    animation: fadeSlide 1s ease;
}
.hero p { margin-top: 10px; opacity: 0.9; }
@keyframes fadeSlide {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

/* CONTENT */
.container {
    max-width: 1200px;
    margin: auto;
    padding: 4rem 2rem;
}
.section-title {
    text-align: center;
    margin-bottom: 3rem;
    color: #2c3e50;
    font-size: 2rem;
}

/* GRID LAYOUT */
.worker-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit,minmax(260px,1fr));
    gap: 2rem;
}

/* CARD */
.worker-card {
    background: #fff;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    transition: all 0.35s ease;
    position: relative;
    overflow: hidden;
    opacity: 0;
    transform: translateY(40px);
    animation: cardReveal 0.8s forwards;
}
.worker-card::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 0%;
    background: #3498db;
    transition: 0.35s;
}
.worker-card:hover::before { height: 100%; }
.worker-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}
.worker-card h3 {
    margin-bottom: 0.8rem;
    color: #2c3e50;
}
.worker-card span {
    display: block;
    font-size: 0.95rem;
    color: #3498db;
    margin-bottom: 0.7rem;
}

.worker-card:nth-child(1){animation-delay:0.05s;}
.worker-card:nth-child(2){animation-delay:0.1s;}
.worker-card:nth-child(3){animation-delay:0.15s;}
.worker-card:nth-child(4){animation-delay:0.2s;}
.worker-card:nth-child(5){animation-delay:0.25s;}
.worker-card:nth-child(6){animation-delay:0.3s;}

@keyframes cardReveal { to { opacity:1; transform: translateY(0); } }

/* FOOTER */
.footer { background: #2c3e50; color: #fff; text-align: center; padding: 1.5rem; margin-top: 40px; }

/* RESPONSIVE */
@media(max-width:900px) {
    .menu { flex-direction: column; align-items: flex-start; }
    .menu__links { margin-left: 0; margin-top: 10px; flex-wrap: wrap; gap: 1rem; }
    .hero h1 { font-size: 2.3rem; }
}
@media(max-width:600px) {
    .hero { height: 40vh; }
    .hero h1 { font-size: 1.8rem; }
    .container { padding: 3rem 1.5rem; }
}
</style>
<link rel="stylesheet" href="../style1.css">
</head>

<body>

<!-- NAVIGATION -->
<nav class="menu scrolled">
    <div class="menu__logo">
        <img src="../imgs/c4.png" alt="CALINGA Logo">
        <span>CALINGA</span>
    </div>

    <button class="menu__toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <ul class="menu__links">
        <li><a href="../index.php">Home</a></li>
        <li class="dropdown">
            <a href="../index.php#about">About</a>
            <div class="dropdown__menu">
                <a href="municipal_history.php">Municipal History</a>
                <a href="municipal_officials.php">Municipal Officials</a>
                <a href="social_workers.php">Social Workers</a>
            </div>
        </li>
        <li class="dropdown">
            <a href="../index.php#services">Services</a>
            <div class="dropdown__menu">
                <a href="solo_parent.php">Solo Parent</a>
                <a href="aics.php">AICS</a>
            </div>
        </li>
        <li><a href="../index.php#contact">Contact</a></li>
        <li><a href="../index.php#announcements">Announcements</a></li>
    </ul>

    <!-- AUTH / LOGIN / NOTIFICATIONS -->
    <div class="menu__auth" aria-label="Account actions">
        <?php if ($loggedIn): ?>
            <!-- Hi User Dropdown -->
            <div class="auth-dropdown">
                <button class="dropdown-toggle">
                    <span class="nav-avatar">
                        <?php if (!empty($userProfileImage)): ?>
                            <img src="../uploads/<?= htmlspecialchars($userProfileImage) ?>" alt="Profile picture">
                        <?php else: ?>
                            <?= htmlspecialchars(function_exists('mb_substr') ? mb_substr($userName, 0, 1) : substr($userName, 0, 1)) ?>
                        <?php endif; ?>
                    </span>
                    <span>Hi, <?= htmlspecialchars($userName) ?> &#9662;</span>
                </button>

                <div class="dropdown-menu">
                    <?php if ($isAdmin): ?>
                        <a href="admin_panel.php" class="dropdown-item">Admin Panel</a>
                    <?php endif; ?>

                    <!-- ✅ ADDED MY FORMS -->
                    <a href="profile.php" class="dropdown-item">My Profile</a>
                    <a href="forms.php" class="dropdown-item">My Forms</a>

                    <a href="logout.php" class="dropdown-item">Logout</a>
                </div>
            </div>

            <!-- Notifications -->
            <div class="nav-notifications">
                <?php include "notification_dropdown.php"; ?>
            </div>
        <?php else: ?>
            <button type="button" class="btn btn-outline page-auth-open" data-page-auth-target="login">Login</button>
            <button type="button" class="btn btn-primary page-auth-open" data-page-auth-target="signup">Sign Up</button>
        <?php endif; ?>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div>
        <h1>MSWD Social Workers</h1>
        <p>Cainta, Rizal</p>
    </div>
</section>

<!-- CONTENT -->
<main class="container">
    <h2 class="section-title">Municipal Social Welfare and Development Office</h2>

    <div class="worker-grid">
        <div class="worker-card">
            <h3>Maria L. Santos, RSW</h3>
            <span>Municipal Social Welfare and Development Officer</span>
            <p>Leads the MSWD and manages welfare programs for families, children, senior citizens, and persons with disabilities.</p>
        </div>
        <div class="worker-card">
            <h3>Ana Patricia Dela Cruz, RSW</h3>
            <span>Social Welfare Officer II</span>
            <p>Supervises social welfare case management and ensures proper implementation of community services.</p>
        </div>
        <div class="worker-card">
            <h3>John Michael Reyes, RSW</h3>
            <span>Social Welfare Officer I</span>
            <p>Handles case studies, outreach programs, and coordinates with barangay social workers.</p>
        </div>
        <div class="worker-card">
            <h3>Carla Mae Villanueva</h3>
            <span>Social Welfare Assistant</span>
            <p>Assists in delivering welfare programs including financial aid and disaster response services.</p>
        </div>
        <div class="worker-card">
            <h3>Mark Anthony Bautista</h3>
            <span>Community Development Worker</span>
            <p>Supports livelihood initiatives, youth development programs, and barangay projects.</p>
        </div>
        <div class="worker-card">
            <h3>Liza May Fernandez</h3>
            <span>Administrative Aide</span>
            <p>Provides administrative support and manages documentation for MSWD services.</p>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const navMenu = document.querySelector('.menu');
    const menuToggle = document.querySelector('.menu__toggle');
    const dropdown = document.querySelector('.auth-dropdown');

    if (menuToggle && navMenu) {
        menuToggle.addEventListener('click', () => {
            const isOpen = navMenu.classList.toggle('menu--open');
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.querySelectorAll('.menu__links a, .menu__auth a').forEach((link) => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('menu--open');
                menuToggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    if(dropdown){
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');

        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        });

        window.addEventListener('click', () => {
            menu.style.display = 'none';
        });
    }
});
</script>

<!-- FOOTER -->
<footer class="footer">
    © 2026 CALINGA - MSWD | Cainta, Rizal
</footer>

<?php if (!$loggedIn) include __DIR__ . '/auth_modal.php'; ?>

</body>
</html>
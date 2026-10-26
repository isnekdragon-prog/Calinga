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
<title>Municipal History - CALINGA</title>
<link rel="icon" href="../imgs/c4.png" type="image/png">

<style>
/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

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

.menu__logo img {
    height: 50px;
}

.menu__links {
    list-style: none;
    display: flex;
    gap: 2rem;
    margin-left: 3rem;
}

.menu__links li {
    position: relative;
}

.menu__links a {
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    position: relative;
    display: inline-block;
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

.menu__links a:hover {
    color: #3498db;
}

.menu__links a:hover::after {
    width: 100%;
}

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

.auth-dropdown .dropdown-toggle:hover {
    background-color: #3498db;
}

.auth-dropdown .dropdown-menu {
    display: none;
    position: absolute;
    top: 110%;
    right: 0;
    background-color: #fff;
    min-width: 200px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

.auth-dropdown .dropdown-menu a:last-child {
    border-bottom: none;
}

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
    background:
        linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
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
}

.hero p {
    margin-top: 10px;
    opacity: 0.9;
}

/* CONTENT */
.container {
    max-width: 1100px;
    margin: auto;
    padding: 4rem 2rem;
}

.section-title {
    text-align: center;
    margin-bottom: 3rem;
    color: #2c3e50;
    font-size: 2rem;
}

.history-card {
    background: #fff;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, color 0.3s ease;
}

/* Hover Animation on Text */
.history-card:hover h3,
.history-card:hover p {
    transform: translateY(-5px);
    color: #3498db;
}

/* FOOTER */
.footer {
    background: #2c3e50;
    color: #fff;
    text-align: center;
    padding: 1.5rem;
    margin-top: 40px;
}
</style>
<link rel="stylesheet" href="../style1.css">
</head>

<body>

<nav class="menu scrolled">
    <div class="menu__logo">
        <img src="../imgs/c4.png" alt="Logo">
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

    <div class="menu__auth" aria-label="Account actions">
        <?php if ($loggedIn): ?>
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
                    <a href="profile.php" class="dropdown-item">My Profile</a>
                    <a href="forms.php" class="dropdown-item">My Forms</a>
                    <a href="logout.php" class="dropdown-item">Logout</a>
                </div>
            </div>

            <div class="nav-notifications">
                <?php include "notification_dropdown.php"; ?>
            </div>
        <?php else: ?>
            <button type="button" class="btn btn-outline page-auth-open" data-page-auth-target="login">Login</button>
            <button type="button" class="btn btn-primary page-auth-open" data-page-auth-target="signup">Sign Up</button>
        <?php endif; ?>
    </div>
</nav>

<section class="hero">
    <div>
        <h1>Municipal History</h1>
        <p>Cainta, Rizal</p>
    </div>
</section>

<main class="container">
    <h2 class="section-title">History of Cainta</h2>

    <div class="history-card">
        <h3>Pre-Colonial Period</h3>
        <p>Cainta was already a thriving settlement before Spanish colonization.</p>
    </div>

    <div class="history-card">
        <h3>Spanish Era (1571)</h3>
        <p>Spanish forces led by Juan de Salcedo attempted to conquer Cainta.</p>
    </div>

    <div class="history-card">
        <h3>American & Japanese Period</h3>
        <p>Infrastructure improved and WWII occupation occurred.</p>
    </div>

    <div class="history-card">
        <h3>Modern Development</h3>
        <p>Cainta is now a major residential and economic hub.</p>
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

    if (dropdown) {
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

<footer class="footer">
    © 2026 CALINGA - MSWD | Cainta, Rizal
</footer>

<?php if (!$loggedIn) include __DIR__ . '/auth_modal.php'; ?>

</body>
</html>
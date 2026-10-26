<?php
session_start();
require_once "actions/db.php"; // include database connection

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
$authTarget = $_GET['auth'] ?? '';
$showActivationNotice = !empty($_SESSION['activation_notice'])
    || ($success === '1' && $authTarget === 'signup');
unset($_SESSION['activation_notice']);
if ($showActivationNotice) {
    $authTarget = '';
}
$hasPendingMfa = isset($_SESSION['mfa_user_id']);
if ($authTarget === 'verify' && !$hasPendingMfa) {
    $authTarget = '';
}

$loggedIn = false;
$userName = "";
$isAdmin = false;
$isStaff = false; // new variable for staff role

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $loggedIn = true;

    // Fetch user fullname, profile image, and both admin/staff flags
    $stmt = $conn->prepare("SELECT fullname, profile_image, COALESCE(is_admin,0) AS is_admin, COALESCE(is_staff,0) AS is_staff FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $userName = $user['fullname'];
        $userProfileImage = $user['profile_image'] ?? '';
        $isAdmin = (bool)$user['is_admin'];
        $isStaff = (bool)($user['is_staff'] ?? 0); // set staff flag
    } else {
        // User not found, logout just in case
        session_unset();
        session_destroy();
        $loggedIn = false;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>CALINGA - MSWD</title>
    <link rel="icon" href="imgs/c4.png" type="image/png">
    <link rel="stylesheet" href="style1.css?v=<?= filemtime(__DIR__ . '/style1.css') ?>" />
</head>
<body>

<!-- NAVIGATION -->
<nav class="menu">
    <div class="menu__logo">
        <img src="imgs/c4.png" alt="Logo">
        <span>CALINGA</span>
    </div>

    <button class="menu__toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <ul class="menu__links">
        <li><a href="#home">Home</a></li>

        <li class="dropdown">
            <a href="#about">About</a>
            <div class="dropdown__menu">
                <a href="actions/municipal_history.php">Municipal History</a>
                <a href="actions/municipal_officials.php">Municipal Officials</a>
                <a href="actions/social_workers.php">Social Workers</a>
            </div>
        </li>

        <li class="dropdown">
            <a href="#services">Services</a>
            <div class="dropdown__menu">
                <a href="actions/solo_parent.php">Solo Parent</a>
                <a href="actions/aics.php">AICS</a>
            </div>
        </li>

        <li><a href="#contact">Contact</a></li>
        <li><a href="#announcements">Announcements</a></li>
    </ul>

    <div class="menu__auth">
<?php if ($loggedIn): ?>
    <div class="auth-dropdown">
        <button class="dropdown-toggle">
            <span class="nav-avatar">
                <?php if (!empty($userProfileImage)): ?>
                    <img src="uploads/<?= htmlspecialchars($userProfileImage) ?>" alt="Profile picture">
                <?php else: ?>
                    <?= htmlspecialchars(function_exists('mb_substr') ? mb_substr($userName, 0, 1) : substr($userName, 0, 1)) ?>
                <?php endif; ?>
            </span>
            <span>Hi, <?= htmlspecialchars($userName) ?> &#9662;</span>
        </button>
        <div class="dropdown-menu">
            <?php if ($isAdmin || $isStaff): ?>
                <a href="actions/admin_panel.php" class="dropdown-item">
                    <?= $isAdmin ? 'Admin Panel' : 'Staff Panel' ?>
                </a>
            <?php endif; ?>
            <a href="actions/profile.php" class="dropdown-item">My Profile</a>
            <a href="actions/forms.php" class="dropdown-item">My Forms</a>
            <a href="actions/logout.php" class="dropdown-item">Logout</a>
        </div>
    </div>

    <div class="nav-notifications">
        <?php include "actions/notification_dropdown.php"; ?>
    </div>
<?php else: ?>
    <button type="button" class="btn btn-outline auth-open" data-auth-target="login">Login</button>
    <button type="button" class="btn btn-primary auth-open" data-auth-target="signup">Sign Up</button>
<?php endif; ?>
</div>
</nav>

<!-- HERO SECTION -->
<header class="hero" id="home">
    <img src="imgs/MSWD.avif" class="hero__bg" alt="Background">
    <div class="hero__overlay"></div>
    <div class="hero__content">
        <div class="hero__logos">
            <img src="imgs/DSWD.png" alt="DSWD">
            <img src="imgs/cainta.png" alt="Cainta">
        </div>
        <span class="hero__eyebrow">MSWD Beneficiary Information Management System</span>
        <h1>CALINGA</h1>
        <p class="hero__lead">
            A digital service hub for faster social welfare coordination, beneficiary assistance,
            and accessible community information for the Municipality of Cainta.
        </p>
        <div class="hero__actions">
            <a href="actions/aics.php" class="btn btn-primary">Apply for AICS</a>
            <a href="actions/solo_parent.php" class="btn btn-ghost">Solo Parent Services</a>
        </div>
        <div class="hero__stats">
            <div class="stat-chip">
                <strong>2</strong>
                <span>Core Online Services</span>
            </div>
            <div class="stat-chip">
                <strong>24/7</strong>
                <span>Information Access</span>
            </div>
            <div class="stat-chip">
                <strong>MSWD</strong>
                <span>Community-Focused Support</span>
            </div>
        </div>
    </div>
</header>

<!-- MAIN CONTENT -->
<main class="container">
    <section class="intro-panel">
        <div class="intro-panel__content">
            <span class="section-kicker">Public Service Platform</span>
            <h2>Designed to make support programs easier to access and easier to manage.</h2>
            <p>
                CALINGA helps residents stay informed about announcements, browse available services,
                and connect with the Municipal Social Welfare and Development Office through a more
                organized digital experience.
            </p>
        </div>
        <div class="intro-panel__highlights">
            <div class="highlight-card">
                <h3>Faster Intake</h3>
                <p>Online forms help reduce walk-in friction and improve data capture for applicants.</p>
            </div>
            <div class="highlight-card">
                <h3>Clear Updates</h3>
                <p>Announcements, news, and notifications keep beneficiaries informed in one place.</p>
            </div>
            <div class="highlight-card">
                <h3>Reliable Records</h3>
                <p>Structured application data supports review, approvals, and document generation.</p>
            </div>
        </div>
    </section>

    <section class="mission">
        <div class="card">
            <h2>Our Mission</h2>
            <p>
                To promote the welfare and development of the people
                through effective and responsive social services.
            </p>
        </div>
        <div class="card">
            <h2>Our Vision</h2>
            <p>
                To be a leading social welfare institution providing
                quality services that enhance the lives of our constituents.
            </p>
        </div>
    </section>

    <section class="about" id="about">
        <h2>About Us</h2>
        <p>
            CALINGA - Municipal Social Welfare and Development Office
            is committed to delivering inclusive and accessible
            programs for families, children, senior citizens,
            persons with disabilities, and other vulnerable sectors.
        </p>
    </section>

    <section class="services-overview" id="services">
        <div class="section-heading">
            <span class="section-kicker">Core Services</span>
            <h2 class="section-title">Programs currently available through the system</h2>
            <p>
                Start your request online, review important requirements, and stay updated with the latest
                office announcements.
            </p>
        </div>

        <div class="service-grid">
            <article class="service-card">
                <div class="service-card__icon">A</div>
                <h3>AICS Assistance</h3>
                <p>
                    Submit and manage applications for Assistance to Individuals in Crisis Situation with a
                    more structured intake process.
                </p>
                <a href="actions/aics.php" class="service-link">Open AICS Service</a>
            </article>

            <article class="service-card">
                <div class="service-card__icon">S</div>
                <h3>Solo Parent Support</h3>
                <p>
                    Apply for solo parent-related services and keep your submitted information organized for
                    office review.
                </p>
                <a href="actions/solo_parent.php" class="service-link">Open Solo Parent Service</a>
            </article>

            <article class="service-card service-card--accent">
                <div class="service-card__icon">I</div>
                <h3>Information and Updates</h3>
                <p>
                    Stay informed through official announcements, office updates, and guidance from the MSWD team.
                </p>
                <a href="#announcements" class="service-link">View Public Updates</a>
            </article>
        </div>
    </section>

    <div id="announcements"></div>
    <section class="announcements">
        <div class="section-heading">
            <span class="section-kicker">Announcements</span>
            <h2 class="section-title">Latest announcements from the office</h2>
        </div>
        <div class="announcement-grid">
            <?php
            $sql = "SELECT * FROM announcements ORDER BY date_posted DESC";
            $result = $conn->query($sql);
            if($result && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
            ?>
            <div class="announcement-card">
                <span class="announcement-date">
                    <?= date("F d, Y", strtotime($row['date_posted'])) ?>
                </span>
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p>
                    <?= substr(htmlspecialchars($row['content']),0,120) ?>...
                </p>
                <a href="<?= htmlspecialchars($row['link']) ?>" class="read-more">Read More</a>
            </div>
            <?php
                }
            }else{
                echo "<p>No announcements yet.</p>";
            }
            ?>
        </div>
    </section>

    <section class="news">
    <div class="section-heading">
        <span class="section-kicker">Community Updates</span>
        <h2 class="section-title">News and recent activities</h2>
    </div>
    <div class="news-grid">
        <?php
        $sql = "SELECT * FROM news ORDER BY date_posted DESC";
        $result = $conn->query($sql);
        if($result && $result->num_rows > 0){
            while($row = $result->fetch_assoc()){
        ?>
        <div class="news-card" onclick="openModal('<?= $row['id'] ?>')">
            <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="news">
            <div class="news-content">
                <span><?= date("F d, Y", strtotime($row['date_posted'])) ?></span>
                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p><?= substr(htmlspecialchars($row['content']),0,100) ?>...</p>
            </div>
        </div>

        <!-- Modal content -->
        <div id="modal-<?= $row['id'] ?>" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('<?= $row['id'] ?>')">&times;</span>
                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['title']) ?>">
                <h2><?= htmlspecialchars($row['title']) ?></h2>
                <p><?= nl2br(htmlspecialchars($row['content'])) ?></p>
            </div>
        </div>
        <?php
            }
        }else{
            echo "<p>No news available.</p>";
        }
        ?>
    </div>
</section>

    <section class="contact-panel" id="contact">
        <div class="contact-panel__info">
            <span class="section-kicker">Contact</span>
            <h2>Coordinate with the Municipal Social Welfare and Development Office</h2>
            <p>
                For application concerns, eligibility questions, and beneficiary support, residents may
                contact or visit the MSWD office during official working hours.
            </p>
        </div>

        <div class="contact-panel__cards">
            <div class="contact-card">
                <h3>Office</h3>
                <p>Municipal Social Welfare and Development Office</p>
            </div>
            <div class="contact-card">
                <h3>Location</h3>
                <p>Cainta, Rizal, Philippines</p>
            </div>
            <div class="contact-card">
                <h3>Online Access</h3>
                <p>Announcements, forms, and notifications are available through CALINGA.</p>
            </div>
        </div>
    </section>
</main>

<?php
$assistantEndpoint = 'actions/inquiry_assistant.php';
$assistantTitle = 'CALINGA Inquiry Assistant';
$assistantIntro = 'Ask about AICS, Solo Parent, requirements, eligibility, or application steps.';
include __DIR__ . '/actions/inquiry_widget.php';
?>

<div class="auth-modal<?= $authTarget === 'verify' && $hasPendingMfa ? ' auth-modal--verify' : '' ?>" id="authModal" aria-hidden="true">
    <div class="auth-modal__backdrop" data-auth-close="true"></div>
    <div class="auth-modal__panel<?= $authTarget === 'verify' ? ' auth-modal__panel--otp' : '' ?>">
        <button type="button" class="auth-modal__close" aria-label="Close login form" data-auth-close="true">&times;</button>

        <div class="auth-modal__body">
            <div class="auth-modal__form is-active" data-auth-form="login">
                <div class="auth-container auth-container--floating">
                    <h2>Login</h2>
                    <?php if ($error && $authTarget === 'login'): ?>=
                        <div class="auth-error" role="alert" style="color:red;margin-bottom:1rem; text-align:left;">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
                    <form action="actions/login_process.php" method="POST">
                        <input type="hidden" name="auth_context" value="home">
                        <label for="home-login-email">Email</label>
                        <input type="email" name="email" id="home-login-email" placeholder="Enter your email" required>

                        <label for="home-login-password">Password</label>
                        <input type="password" name="password" id="home-login-password" placeholder="Enter your password" required>

                        <button type="submit" class="btn-primary">Login</button>

                        <p class="auth-text"><a href="actions/forgot_password.php">Forgot Password?</a></p>
                        <p class="auth-text">Don't have an account? <button type="button" class="auth-toggle-link" data-auth-target="signup">Sign Up</button></p>
                    </form>
                </div>
            </div>

            <div class="auth-modal__form" data-auth-form="signup">
                <div class="auth-container auth-container--floating">
                    <h2>Sign Up</h2>
                    <?php if ($error && $authTarget === 'signup'): ?>
                        <div class="auth-error" style="color:red;margin-bottom:1rem; text-align:left;">
                            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
                    <form action="actions/signup_process.php?source=home" method="POST" id="home-signup-form" enctype="multipart/form-data">
                        <input type="hidden" name="auth_context" value="home">
                        <label for="home-signup-name">Full Name</label>
                        <input type="text" name="fullname" id="home-signup-name" placeholder="Enter your full name" required>

                        <label for="home-signup-email">Email</label>
                        <input type="email" name="email" id="home-signup-email" placeholder="Enter your email" required>

                        <label for="home-signup-password">Password</label>
                        <input type="password" name="password" id="home-signup-password" placeholder="Enter a password" required minlength="8">
                        <div id="home-password-error" class="auth-inline-error"></div>

                        <label for="home-signup-confirm">Confirm Password</label>
                        <input type="password" name="confirm_password" id="home-signup-confirm" placeholder="Confirm your password" required minlength="8">

                        <div class="file-upload-wrapper">
                            <span class="file-upload-label">Identity Verification Document</span>
                            <label for="verification_file" class="file-upload-box" id="home-upload-box">
                                <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                <span class="upload-text">Click to upload or drag &amp; drop</span>
                                <span class="upload-hint">JPG, PNG, or PDF — max 5MB</span>
                                <input type="file" name="verification_file" id="verification_file" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf" required>
                            </label>
                            <div id="home-file-preview-container"></div>
                            <div id="home-file-error" class="file-error"></div>
                        </div>

                        <div class="g-recaptcha" data-sitekey="6LeVwogsAAAAALMfhlTFIYA05-l0APlOX-X3fZ1y"></div>

                        <button type="submit" class="btn-primary">Sign Up</button>

                        <p class="auth-text">Already have an account? <button type="button" class="auth-toggle-link" data-auth-target="login">Login</button></p>
                    </form>
                </div>
            </div>

            <?php if ($hasPendingMfa): ?>
                <div class="auth-modal__form" data-auth-form="verify">
                    <div class="auth-container auth-container--floating auth-container--otp">
                        <div class="otp-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M5 4.75h14A2.25 2.25 0 0 1 21.25 7v10A2.25 2.25 0 0 1 19 19.25H5A2.25 2.25 0 0 1 2.75 17V7A2.25 2.25 0 0 1 5 4.75Z" />
                                <path d="m3.5 6 8.5 6.5L20.5 6M8 16h8" />
                            </svg>
                        </div>
                        <h2>Verify your sign-in</h2>
                        <p class="otp-description">Enter the 6-digit code we emailed you. For your security, it expires after 5 minutes.</p>
                        <?php if ($error && $authTarget === 'verify'): ?>
                            <div class="auth-error" role="alert">
                                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>
                        <form action="actions/verify.php" method="POST" autocomplete="off">
                            <input type="hidden" name="auth_context" value="home">
                            <label for="home-otp">Verification code</label>
                            <input type="text" name="otp" id="home-otp" class="otp-input" placeholder="000000" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9]{6}" minlength="6" maxlength="6" required autofocus>
                            <button type="submit" class="btn-primary">Verify and Continue</button>
                        </form>
                        <p class="auth-text otp-help">Didn't receive the code? Check your spam folder or try signing in again.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($showActivationNotice): ?>
    <div class="activation-notice is-open" id="activationNotice" role="dialog" aria-modal="true" aria-labelledby="activationNoticeTitle" aria-describedby="activationNoticeDescription" aria-hidden="false">
        <button type="button" class="activation-notice__backdrop" data-activation-notice-close aria-label="Close message"></button>
        <section class="activation-notice__card">
            <button type="button" class="activation-notice__close" data-activation-notice-close aria-label="Close message">&times;</button>
            <div class="activation-notice__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M4.75 5.75h14.5A1.75 1.75 0 0 1 21 7.5v9a1.75 1.75 0 0 1-1.75 1.75H4.75A1.75 1.75 0 0 1 3 16.5v-9a1.75 1.75 0 0 1 1.75-1.75Z" />
                    <path d="m4 7 8 6 8-6M8 16h3" />
                    <path d="m16.5 2.8.7 1.5 1.6.7-1.6.7-.7 1.6-.7-1.6-1.6-.7 1.6-.7.7-1.5Z" />
                </svg>
            </div>
            <span class="activation-notice__eyebrow">One last step</span>
            <h2 id="activationNoticeTitle">Check your email</h2>
            <p id="activationNoticeDescription">
                Your account has been created. We sent you an activation link—open it to verify your email before signing in.
            </p>
            <div class="activation-notice__tip">
                <span class="activation-notice__tip-icon" aria-hidden="true">i</span>
                <span>Can't find it? Check your spam or junk folder.</span>
            </div>
            <button type="button" class="btn-primary activation-notice__button" data-activation-notice-close>
                Got it, I'll check my email
            </button>
        </section>
    </div>
<?php endif; ?>

<!-- FOOTER -->
    <footer class="footer">
        <p>&copy; 2026 CALINGA - MSWD | Cainta, Rizal, Philippines</p>
    </footer>

<!-- COOKIE CONSENT BANNER -->
<div id="cookieConsent" class="cookie-consent">
    <div class="cookie-content">
        <p>🔒 We use cookies to enhance your browsing experience, analyze site traffic, and personalize content. By continuing to use our site, you consent to our use of cookies.</p>
        <div class="cookie-buttons">
            <button id="acceptCookies" class="cookie-btn accept">Accept All</button>
            <button id="declineCookies" class="cookie-btn decline">Decline</button>
            <a href="actions/privacy_policy.php" class="cookie-link">Learn More</a>
        </div>
    </div>
</div>

<style>
.cookie-consent {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(135deg, #1a2a3a 0%, #0f1a24 100%);
    color: white;
    padding: 1rem;
    z-index: 10000;
    transform: translateY(100%);
    transition: transform 0.3s ease-in-out;
    box-shadow: 0 -5px 25px rgba(0,0,0,0.2);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.cookie-consent.show {
    transform: translateY(0);
}

.cookie-content {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.cookie-content p {
    margin: 0;
    flex: 1;
    font-size: 0.9rem;
    line-height: 1.4;
}

.cookie-buttons {
    display: flex;
    gap: 0.75rem;
    align-items: center;
    flex-wrap: wrap;
}

.cookie-btn {
    padding: 0.5rem 1.25rem;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.cookie-btn.accept {
    background: #4CAF50;
    color: white;
}

.cookie-btn.accept:hover {
    background: #45a049;
    transform: translateY(-1px);
}

.cookie-btn.decline {
    background: #666;
    color: white;
}

.cookie-btn.decline:hover {
    background: #555;
}

.cookie-link {
    color: #66bb6a;
    text-decoration: none;
    font-size: 0.85rem;
}

.cookie-link:hover {
    text-decoration: underline;
    color: #81c784;
}

@media (max-width: 768px) {
    .cookie-content {
        flex-direction: column;
        text-align: center;
    }
    
    .cookie-buttons {
        justify-content: center;
    }
}
</style>

<script>
// Cookie consent handling
(function() {
    const COOKIE_NAME = 'calinga_cookie_consent';
    const CONSENT_VALUE = 'accepted';
    const BANNER_ID = 'cookieConsent';
    
    function setCookie(name, value, days = 365) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        const expires = "; expires=" + date.toUTCString();
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }
    
    function getCookie(name) {
        const nameEQ = name + "=";
        const ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
    
    function hasConsented() {
        return getCookie(COOKIE_NAME) === CONSENT_VALUE;
    }
    
    function showBanner() {
        const banner = document.getElementById(BANNER_ID);
        if (banner) {
            banner.classList.add('show');
        }
    }
    
    function hideBanner() {
        const banner = document.getElementById(BANNER_ID);
        if (banner) {
            banner.classList.remove('show');
        }
    }
    
    function acceptCookies() {
        setCookie(COOKIE_NAME, CONSENT_VALUE);
        hideBanner();
        console.log('Cookies accepted - you can now track user preferences');
    }
    
    function declineCookies() {
        setCookie(COOKIE_NAME, 'declined');
        hideBanner();
        console.log('Cookies declined - limited functionality');
    }
    
    // Check if user has already consented
    if (!hasConsented()) {
        // Show banner after a short delay
        setTimeout(showBanner, 500);
    }
    
    // Add event listeners when DOM is ready
    document.addEventListener('DOMContentLoaded', function() {
        const acceptBtn = document.getElementById('acceptCookies');
        const declineBtn = document.getElementById('declineCookies');
        
        if (acceptBtn) {
            acceptBtn.addEventListener('click', acceptCookies);
        }
        if (declineBtn) {
            declineBtn.addEventListener('click', declineCookies);
        }
    });
})();
</script>

<script>
        const menu = document.querySelector('.menu');
        const menuToggle = document.querySelector('.menu__toggle');

        window.addEventListener('scroll', () => {
            menu.classList.toggle('scrolled', window.scrollY > 50);
        });

        if (menuToggle) {
            menuToggle.addEventListener('click', () => {
                const isOpen = menu.classList.toggle('menu--open');
                menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        }

        document.querySelectorAll('.menu__links a, .menu__auth a').forEach((link) => {
            link.addEventListener('click', () => {
                menu.classList.remove('menu--open');
                if (menuToggle) {
                    menuToggle.setAttribute('aria-expanded', 'false');
                }
            });
        });

        const authModal = document.getElementById('authModal');
        const authForms = document.querySelectorAll('[data-auth-form]');
        const initialAuthTarget = <?= json_encode(in_array($authTarget, ['login', 'signup', 'verify'], true) ? $authTarget : '') ?>;
        const activationNotice = document.getElementById('activationNotice');

        function openAuthModal(target) {
            if (!authModal) return;
            authModal.classList.add('is-open');
            authModal.classList.toggle('auth-modal--verify', target === 'verify');
            authModal.setAttribute('aria-hidden', 'false');

            authForms.forEach((form) => {
                const isMatch = form.dataset.authForm === target;
                form.classList.toggle('is-active', isMatch);
            });
        }

        function closeAuthModal() {
            if (!authModal) return;
            authModal.classList.remove('is-open');
            authModal.setAttribute('aria-hidden', 'true');
        }

        document.querySelectorAll('.auth-open').forEach((button) => {
            button.addEventListener('click', () => {
                openAuthModal(button.dataset.authTarget);
            });
        });

        document.querySelectorAll('[data-auth-close="true"]').forEach((element) => {
            element.addEventListener('click', closeAuthModal);
        });

        document.querySelectorAll('.auth-toggle-link').forEach((button) => {
            button.addEventListener('click', () => {
                openAuthModal(button.dataset.authTarget);
            });

        });

        if (initialAuthTarget) {
            openAuthModal(initialAuthTarget);
        }

        function closeActivationNotice() {
            if (!activationNotice) return;
            activationNotice.classList.remove('is-open');
            activationNotice.setAttribute('aria-hidden', 'true');

            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.delete('success');
            currentUrl.searchParams.delete('notice');
            currentUrl.searchParams.delete('auth');
            window.history.replaceState({}, '', currentUrl);
        }

        if (activationNotice) {
            activationNotice.querySelectorAll('[data-activation-notice-close]').forEach((button) => {
                button.addEventListener('click', closeActivationNotice);
            });
            activationNotice.querySelector('.activation-notice__button').focus();
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && authModal && authModal.classList.contains('is-open')) {
                closeAuthModal();
            }
            if (event.key === 'Escape' && activationNotice && activationNotice.classList.contains('is-open')) {
                closeActivationNotice();
            }
        });

        const homeSignupForm = document.getElementById('home-signup-form');
        const homePasswordInput = document.getElementById('home-signup-password');
        const homePasswordError = document.getElementById('home-password-error');
        const homeFileInput = document.getElementById('verification_file');
        const homeUploadBox = document.getElementById('home-upload-box');
        const homePreviewContainer = document.getElementById('home-file-preview-container');
        const homeFileError = document.getElementById('home-file-error');

        const HOME_MAX_FILE_SIZE = 5 * 1024 * 1024;
        const HOME_ALLOWED_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];

        function validateHomeFile() {
            homeFileError.textContent = '';

            if (!homeFileInput.files || homeFileInput.files.length === 0) {
                homeFileError.textContent = 'Please upload a verification document.';
                return false;
            }

            const file = homeFileInput.files[0];

            if (!HOME_ALLOWED_TYPES.includes(file.type)) {
                homeFileError.textContent = 'Only JPG, PNG, or PDF files are allowed.';
                return false;
            }

            if (file.size > HOME_MAX_FILE_SIZE) {
                homeFileError.textContent = 'File is too large. Maximum size is 5MB.';
                return false;
            }

            return true;
        }

        function renderHomePreview(file) {
            homePreviewContainer.innerHTML = '';

            if (!file) return;

            const preview = document.createElement('div');
            preview.className = 'file-preview';

            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.onload = () => URL.revokeObjectURL(img.src);
                preview.appendChild(img);
            } else {
                const icon = document.createElement('div');
                icon.className = 'file-icon';
                icon.textContent = '📄';
                preview.appendChild(icon);
            }

            const name = document.createElement('span');
            name.className = 'file-name';
            name.textContent = file.name;
            preview.appendChild(name);

            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-file';
            removeBtn.innerHTML = '&times;';
            removeBtn.title = 'Remove file';
            removeBtn.addEventListener('click', () => {
                homeFileInput.value = '';
                homePreviewContainer.innerHTML = '';
                homeFileError.textContent = '';
            });
            preview.appendChild(removeBtn);

            homePreviewContainer.appendChild(preview);
        }

        if (homeSignupForm) {
            homeSignupForm.addEventListener('submit', (e) => {
                const password = homePasswordInput.value;
                const specialChar = /[!@#$%^&*(),.?":{}|<>]/;
                let valid = true;

                if (password.length < 8) {
                    homePasswordError.textContent = 'Password must be at least 8 characters.';
                    valid = false;
                } else if (!specialChar.test(password)) {
                    homePasswordError.textContent = 'Password must include at least one special character (!@#$%^&* etc.).';
                    valid = false;
                } else {
                    homePasswordError.textContent = '';
                }

                if (!validateHomeFile()) {
                    valid = false;
                }

                if (!valid) {
                    e.preventDefault();
                }
            });
        }

        if (homeFileInput) {
            homeFileInput.addEventListener('change', function() {
                homeFileError.textContent = '';
                if (this.files && this.files[0]) {
                    if (!validateHomeFile()) {
                        homePreviewContainer.innerHTML = '';
                        return;
                    }
                    renderHomePreview(this.files[0]);
                } else {
                    homePreviewContainer.innerHTML = '';
                }
            });

            ['dragenter', 'dragover'].forEach((eventName) => {
                homeUploadBox.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    homeUploadBox.classList.add('dragover');
                });
            });

            ['dragleave', 'drop'].forEach((eventName) => {
                homeUploadBox.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    homeUploadBox.classList.remove('dragover');
                });
            });

            homeUploadBox.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                if (files && files.length > 0) {
                    const dt = new DataTransfer();
                    dt.items.add(files[0]);
                    homeFileInput.files = dt.files;
                    homeFileInput.dispatchEvent(new Event('change'));
                }
            });
        }

        function openModal(id){
    document.getElementById('modal-' + id).style.display = 'block';
}

function closeModal(id){
    document.getElementById('modal-' + id).style.display = 'none';
}

// Close modal on click outside
window.onclick = function(event) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    });
}

// AUTH DROPDOWN TOGGLE
document.addEventListener('DOMContentLoaded', () => {
    const dropdown = document.querySelector('.auth-dropdown');
    if(dropdown){
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');

        toggle.addEventListener('click', (e) => {
            e.stopPropagation();
            menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
        });

        // Close dropdown if clicking outside
        window.addEventListener('click', () => {
            menu.style.display = 'none';
        });
    }
});
</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>
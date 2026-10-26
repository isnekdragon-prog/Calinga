<div class="auth-modal" id="pageAuthModal" aria-hidden="true">
    <div class="auth-modal__backdrop" data-page-auth-close></div>
    <div class="auth-modal__panel">
        <button type="button" class="auth-modal__close" aria-label="Close form" data-page-auth-close>&times;</button>
        <div class="auth-modal__body">
            <div class="auth-modal__form is-active" data-page-auth-form="login">
                <div class="auth-container auth-container--floating">
                    <h2>Login</h2>
                    <form action="login_process.php" method="POST">
                        <input type="hidden" name="auth_context" value="home">
                        <label for="page-login-email">Email</label>
                        <input type="email" name="email" id="page-login-email" placeholder="Enter your email" required>
                        <label for="page-login-password">Password</label>
                        <input type="password" name="password" id="page-login-password" placeholder="Enter your password" required>
                        <button type="submit" class="btn-primary">Login</button>
                        <p class="auth-text"><a href="forgot_password.php">Forgot Password?</a></p>
                        <p class="auth-text">Don't have an account? <button type="button" class="auth-toggle-link" data-page-auth-target="signup">Sign Up</button></p>
                    </form>
                </div>
            </div>
            <div class="auth-modal__form" data-page-auth-form="signup">
                <div class="auth-container auth-container--floating">
                    <h2>Sign Up</h2>
                    <form action="signup_process.php?source=home" method="POST" enctype="multipart/form-data" class="page-signup-form">
                        <input type="hidden" name="auth_context" value="home">
                        <label for="page-signup-name">Full Name</label>
                        <input type="text" name="fullname" id="page-signup-name" placeholder="Enter your full name" required>
                        <label for="page-signup-email">Email</label>
                        <input type="email" name="email" id="page-signup-email" placeholder="Enter your email" required>
                        <label for="page-signup-password">Password</label>
                        <input type="password" name="password" id="page-signup-password" placeholder="At least 8 characters and one special character" required minlength="8">
                        <label for="page-signup-confirm">Confirm Password</label>
                        <input type="password" name="confirm_password" id="page-signup-confirm" placeholder="Confirm your password" required minlength="8">
                        <div class="file-upload-wrapper">
                            <span class="file-upload-label">Identity Verification Document</span>
                            <label for="page-verification-file" class="file-upload-box">
                                <span class="upload-text">Click to upload your verification document</span>
                                <span class="upload-hint">JPG, PNG, or PDF — max 5MB</span>
                                <input type="file" name="verification_file" id="page-verification-file" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf" required>
                            </label>
                            <div class="file-error" data-page-signup-error role="alert"></div>
                        </div>
                        <div class="g-recaptcha" data-sitekey="6LeVwogsAAAAALMfhlTFIYA05-l0APlOX-X3fZ1y"></div>
                        <button type="submit" class="btn-primary">Sign Up</button>
                        <p class="auth-text">Already have an account? <button type="button" class="auth-toggle-link" data-page-auth-target="login">Login</button></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('pageAuthModal');
    if (!modal) return;

    const forms = modal.querySelectorAll('[data-page-auth-form]');
    const openModal = (target) => {
        forms.forEach((form) => form.classList.toggle('is-active', form.dataset.pageAuthForm === target));
        modal.classList.add('is-open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    };
    const closeModal = () => {
        modal.classList.remove('is-open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    };

    document.querySelectorAll('.page-auth-open').forEach((button) => {
        button.addEventListener('click', () => openModal(button.dataset.pageAuthTarget));
    });
    modal.querySelectorAll('[data-page-auth-target]').forEach((button) => {
        button.addEventListener('click', () => openModal(button.dataset.pageAuthTarget));
    });
    modal.querySelectorAll('[data-page-auth-close]').forEach((element) => {
        element.addEventListener('click', closeModal);
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
    });

    const signupForm = modal.querySelector('.page-signup-form');
    signupForm.addEventListener('submit', (event) => {
        const password = signupForm.elements.password.value;
        const confirmPassword = signupForm.elements.confirm_password.value;
        const file = signupForm.elements.verification_file.files[0];
        const error = signupForm.querySelector('[data-page-signup-error]');
        let message = '';

        if (password.length < 8 || !/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
            message = 'Password must be at least 8 characters and include a special character.';
        } else if (password !== confirmPassword) {
            message = 'Passwords do not match.';
        } else if (file && file.size > 5 * 1024 * 1024) {
            message = 'The verification document must be 5MB or smaller.';
        }

        error.textContent = message;
        if (message) event.preventDefault();
    });
});
</script>

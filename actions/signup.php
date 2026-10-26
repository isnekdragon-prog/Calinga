<?php
session_start();
require_once __DIR__ . '/db.php';
// already registered? redirect
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$error = $_GET['error'] ?? '';
$success = isset($_GET['success']);
$successMessage = $_GET['notice'] ?? 'Registration successful! Please check your email to activate your account.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - CALINGA MSWD</title>
    <link rel="stylesheet" href="../style1.css">
    <style>
        .file-upload-wrapper {
            margin-top: 0.5rem;
            margin-bottom: 1rem;
        }

        .file-upload-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.4rem;
            color: #333;
        }

        .file-upload-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 1.5rem 1rem;
            border: 2px dashed #b0b8c4;
            border-radius: 10px;
            background-color: #f9fafc;
            cursor: pointer;
            transition: border-color 0.25s ease, background-color 0.25s ease;
            text-align: center;
        }

        .file-upload-box:hover,
        .file-upload-box.dragover {
            border-color: #007bff;
            background-color: #eef5ff;
        }

        .file-upload-box svg {
            width: 36px;
            height: 36px;
            stroke: #007bff;
            fill: none;
            stroke-width: 1.8;
        }

        .file-upload-box .upload-text {
            font-size: 0.95rem;
            color: #555;
        }

        .file-upload-box .upload-hint {
            font-size: 0.8rem;
            color: #888;
        }

        .file-upload-box input[type="file"] {
            display: none;
        }

        .file-preview {
            margin-top: 0.6rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.8rem;
            background: #fff;
            border: 1px solid #d0d7e0;
            border-radius: 8px;
            font-size: 0.9rem;
            color: #333;
        }

        .file-preview img {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .file-preview .file-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef5ff;
            border-radius: 6px;
            font-size: 1.4rem;
        }

        .file-preview .file-name {
            flex: 1;
            word-break: break-all;
        }

        .file-preview .remove-file {
            background: none;
            border: none;
            color: #c00;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0 0.3rem;
            line-height: 1;
        }

        .file-error {
            color: red;
            font-size: 0.9rem;
            margin-top: 0.3rem;
        }
    </style>
</head>
<body>

<div class="auth-page">
    <div class="auth-container">
        <h2>Sign Up</h2>
        <?php if ($error): ?>
            <div class="auth-error" style="color:red;margin-bottom:1rem;">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="auth-success" style="color:green;margin-bottom:1rem;">
                <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?> <a href="login.php">Login</a>.
            </div>
        <?php endif; ?>
        <form action="signup_process.php" method="POST" id="signup-form" enctype="multipart/form-data">
            <label for="fullname">Full Name</label>
            <input type="text" name="fullname" id="fullname" placeholder="Enter your full name" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" required>

            <label for="password">Password</label>
            <input 
                type="password" 
                name="password" 
                id="password" 
                placeholder="Enter a password" 
                required
                minlength="8"
            >
            <!-- Inline password error message -->
            <div id="password-error" style="color:red; font-size:0.9rem; margin-top:0.3rem;"></div>

            <label for="confirm_password">Confirm Password</label>
            <input 
                type="password" 
                name="confirm_password" 
                id="confirm_password" 
                placeholder="Confirm your password" 
                required
                minlength="8"
            >

            <!-- ===== ID VERIFICATION ATTACHMENT ===== -->
            <div class="file-upload-wrapper">
                <span class="file-upload-label">Identity Verification Document</span>
                <label for="verification_file" class="file-upload-box" id="upload-box">
                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    <span class="upload-text">Click to upload or drag &amp; drop</span>
                    <span class="upload-hint">JPG, PNG, or PDF — max 5MB</span>
                    <input 
                        type="file" 
                        name="verification_file" 
                        id="verification_file" 
                        accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                        required
                    >
                </label>
                <div id="file-preview-container"></div>
                <div id="file-error" class="file-error"></div>
            </div>

            <div class="g-recaptcha" data-sitekey="6LeVwogsAAAAALMfhlTFIYA05-l0APlOX-X3fZ1y"></div>

            <button type="submit" class="btn-primary">Sign Up</button>

            <p class="auth-text">Already have an account? <a href="login.php">Login</a></p>
        </form>
        <a href="../index.php" class="btn btn-outline" style="margin-top: 1rem; display: inline-block;">Return to Homepage</a>
    </div>
</div>

<script>
const form = document.getElementById("signup-form");
const passwordInput = document.getElementById("password");
const passwordError = document.getElementById("password-error");

// ===== File upload elements =====
const fileInput = document.getElementById("verification_file");
const uploadBox = document.getElementById("upload-box");
const previewContainer = document.getElementById("file-preview-container");
const fileError = document.getElementById("file-error");

const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
const ALLOWED_TYPES = ["image/jpeg", "image/png", "application/pdf"];

// ===== Password validation =====
form.addEventListener("submit", function(e) {
    const password = passwordInput.value;
    const specialChar = /[!@#$%^&*(),.?":{}|<>]/;

    let valid = true;

    if (password.length < 8) {
        passwordError.textContent = "Password must be at least 8 characters.";
        valid = false;
    } else if (!specialChar.test(password)) {
        passwordError.textContent = "Password must include at least one special character (!@#$%^&* etc.).";
        valid = false;
    } else {
        passwordError.textContent = ""; // valid password
    }

    // ===== File validation =====
    if (!validateFile()) {
        valid = false;
    }

    if (!valid) {
        e.preventDefault();
    }
});

// ===== File validation function =====
function validateFile() {
    fileError.textContent = "";

    if (!fileInput.files || fileInput.files.length === 0) {
        fileError.textContent = "Please upload a verification document.";
        return false;
    }

    const file = fileInput.files[0];

    if (!ALLOWED_TYPES.includes(file.type)) {
        fileError.textContent = "Only JPG, PNG, or PDF files are allowed.";
        return false;
    }

    if (file.size > MAX_FILE_SIZE) {
        fileError.textContent = "File is too large. Maximum size is 5MB.";
        return false;
    }

    return true;
}

// ===== Render file preview =====
function renderPreview(file) {
    previewContainer.innerHTML = "";

    if (!file) return;

    const preview = document.createElement("div");
    preview.className = "file-preview";

    if (file.type.startsWith("image/")) {
        const img = document.createElement("img");
        img.src = URL.createObjectURL(file);
        img.onload = () => URL.revokeObjectURL(img.src);
        preview.appendChild(img);
    } else {
        const icon = document.createElement("div");
        icon.className = "file-icon";
        icon.textContent = "📄";
        preview.appendChild(icon);
    }

    const name = document.createElement("span");
    name.className = "file-name";
    name.textContent = file.name;
    preview.appendChild(name);

    const removeBtn = document.createElement("button");
    removeBtn.type = "button";
    removeBtn.className = "remove-file";
    removeBtn.innerHTML = "&times;";
    removeBtn.title = "Remove file";
    removeBtn.addEventListener("click", function() {
        fileInput.value = "";
        previewContainer.innerHTML = "";
        fileError.textContent = "";
    });
    preview.appendChild(removeBtn);

    previewContainer.appendChild(preview);
}

// ===== Handle file selection =====
fileInput.addEventListener("change", function() {
    fileError.textContent = "";
    if (this.files && this.files[0]) {
        if (!validateFile()) {
            // If invalid, still show error but don't preview
            previewContainer.innerHTML = "";
            return;
        }
        renderPreview(this.files[0]);
    } else {
        previewContainer.innerHTML = "";
    }
});

// ===== Drag & drop support =====
["dragenter", "dragover"].forEach(eventName => {
    uploadBox.addEventListener(eventName, function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadBox.classList.add("dragover");
    });
});

["dragleave", "drop"].forEach(eventName => {
    uploadBox.addEventListener(eventName, function(e) {
        e.preventDefault();
        e.stopPropagation();
        uploadBox.classList.remove("dragover");
    });
});

uploadBox.addEventListener("drop", function(e) {
    const files = e.dataTransfer.files;
    if (files && files.length > 0) {
        // Assign dropped file to the input using DataTransfer
        const dt = new DataTransfer();
        dt.items.add(files[0]);
        fileInput.files = dt.files;

        // Trigger change event manually
        fileInput.dispatchEvent(new Event("change"));
    }
});
</script>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</body>
</html>
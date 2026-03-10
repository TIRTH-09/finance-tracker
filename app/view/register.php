<!-- ============================================================
     REGISTRATION PAGE — register.php
     ============================================================
     1. This page displays the registration form for new users.
     1.1 Same split-screen layout as the login page.
     1.2 Includes client-side validation (inline script) + server-side validation.
     ============================================================ -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- 2. HEAD SECTION -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - FinanceTrack</title>
    <!-- 2.1 Main stylesheet + Ionicons -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body>

<!-- 3. REGISTRATION CONTAINER (same split layout as login) -->
<div class="login-container">

    <!-- ============================
         4. LEFT SIDE: Art Panel
         ============================ -->
    <div class="login-art">
        <!-- 4.1 Dot texture overlay -->
        <div class="texture-overlay"></div>
        <!-- 4.2 Headline and tagline for new users -->
        <div class="art-content">
            <h1>Join Us.</h1>
            <p>Start your journey towards financial clarity today.</p>
        </div>
        <!-- 4.3 Animated geometric shards -->
        <div class="abstract-shard shard-1"></div>
        <div class="abstract-shard shard-2"></div>
    </div>

    <!-- ============================
         5. RIGHT SIDE: Registration Form
         ============================ -->
    <div class="login-form-wrapper">
        <div class="login-card">
            <!-- 5.1 Header -->
            <div class="login-header">
                <h2>Create Account</h2>
                <p>Enter your details below to register.</p>
            </div>

            <!-- 5.2 SERVER-SIDE ERROR BANNER -->
            <!-- 5.2.1 Shown when controller sets $error (e.g. "Username taken") -->
            <?php if(isset($error)): ?>
                <div class="error-banner">
                    <ion-icon name="warning-outline"></ion-icon> <?= $error ?>
                </div>
            <?php endif; ?>

            <!-- 5.3 REGISTRATION FORM -->
            <!-- 5.3.1 POSTs to index.php?action=storeUser → HomeController->storeUser() -->
            <form id="registerForm" method="POST" action="index.php?action=storeUser" novalidate>

                <!-- 5.4 USERNAME FIELD -->
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="input-icon-wrap">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="username" id="reg-user" class="form-input" placeholder="Choose a username">
                    </div>
                    <!-- 5.4.1 Client-side error message (hidden by default) -->
                    <small class="input-error-msg">Username is required</small>
                </div>

                <!-- 5.5 PASSWORD FIELD -->
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-icon-wrap">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" id="reg-pass" class="form-input" placeholder="Create a password">
                    </div>
                    <small class="input-error-msg">Password is required</small>
                </div>

                <!-- 5.6 CONFIRM PASSWORD FIELD -->
                <div class="form-group" style="margin-bottom: 30px;">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-icon-wrap">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="confirm_password" id="reg-confirm" class="form-input" placeholder="Repeat password">
                    </div>
                    <small class="input-error-msg">Passwords do not match</small>
                </div>

                <!-- 5.7 SUBMIT BUTTON -->
                <button type="submit" class="btn-primary">Create Account</button>
            </form>
            
            <!-- 5.8 LOGIN LINK: Navigate back to login page -->
            <a href="index.php?action=login" class="auth-link">
                Already have an account? <strong>Sign In</strong>
            </a>
        </div>
    </div>
</div>

<!-- ============================
     6. CLIENT-SIDE VALIDATION SCRIPT
     6.1 Validates all three fields before allowing form submission.
     6.2 Shows inline error messages and shakes invalid inputs.
     ============================ -->
<script>
    // 6.3 Attach submit event listener to the registration form
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        let valid = true;
        
        // 6.4 VALIDATE USERNAME: Cannot be empty
        const user = document.getElementById('reg-user');
        if (user.value.trim() === '') {
            showError(user, "Username is required");
            valid = false;
        } else {
            clearError(user);
        }

        // 6.5 VALIDATE PASSWORD: Cannot be empty
        const pass = document.getElementById('reg-pass');
        if (pass.value.trim() === '') {
            showError(pass, "Password is required");
            valid = false;
        } else {
            clearError(pass);
        }

        // 6.6 VALIDATE CONFIRM PASSWORD: Must match and not be empty
        const confirm = document.getElementById('reg-confirm');
        if (confirm.value.trim() === '') {
            showError(confirm, "Please confirm your password");
            valid = false;
        } else if (confirm.value !== pass.value) {
            // 6.6.1 Passwords don't match
            showError(confirm, "Passwords do not match");
            valid = false;
        } else {
            clearError(confirm);
        }

        // 6.7 If any field is invalid, prevent form submission
        if (!valid) e.preventDefault();
    });

    /**
     * 6.8 SHOW ERROR: Add error styling and display the error message
     * 6.8.1 Adds 'error' class to input (triggers red border + shake in CSS)
     * 6.8.2 Shows the <small> error message below the input
     */
    function showError(input, msg) {
        input.classList.add('error');
        const msgEl = input.closest('.form-group').querySelector('.input-error-msg');
        if(msgEl) {
            msgEl.innerText = msg;
            msgEl.classList.add('visible');
        }
    }

    /**
     * 6.9 CLEAR ERROR: Remove error styling and hide the error message
     */
    function clearError(input) {
        input.classList.remove('error');
        const msgEl = input.closest('.form-group').querySelector('.input-error-msg');
        if(msgEl) msgEl.classList.remove('visible');
    }

    // 6.10 CLEAR ON TYPE: Remove error state as user starts typing
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('input', () => clearError(input));
    });
</script>

</body>
</html>
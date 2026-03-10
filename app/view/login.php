<!-- ============================================================
     LOGIN PAGE — login.php
     ============================================================
     1. This page displays the login form for the Finance Tracker app.
     1.1 Has a split-screen layout: left side is decorative, right side is the form.
     1.2 Shows field-specific error messages when credentials are wrong.
     1.3 Forces light-mode colors regardless of dark mode preference.
     ============================================================ -->
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- 2. HEAD SECTION -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 2.1 Page title for browser tab -->
    <title>Sign In - FinanceTrack</title>
    <!-- 2.2 Main stylesheet with cache-busting -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <!-- 2.3 Ionicons for the input field icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body>

<!-- 3. LOGIN CONTAINER: Split-screen layout -->
<!-- 3.1 Forces light mode CSS variables locally via .login-container in style.css -->
<div class="login-container">

    <!-- ============================
         4. LEFT SIDE: Decorative Art Panel
         4.1 Rich indigo gradient background with floating shard animations.
         ============================ -->
    <div class="login-art">
        <!-- 4.2 Dot-pattern texture overlay for depth -->
        <div class="texture-overlay"></div>
        <!-- 4.3 Text content: headline and tagline -->
        <div class="art-content">
            <h1>Precision<br>Finance.</h1>
            <p>Experience clarity in your financial journey with our advanced tracking suite.</p>
        </div>
        <!-- 4.4 Animated geometric shards for visual interest -->
        <div class="abstract-shard shard-1"></div>
        <div class="abstract-shard shard-2"></div>
    </div>

    <!-- ============================
         5. RIGHT SIDE: Login Form
         ============================ -->
    <div class="login-form-wrapper">
        <div class="login-card">
            <!-- 5.1 Header text -->
            <div class="login-header">
                <h2>Welcome back</h2>
                <p>Please enter your credentials to access your dashboard.</p>
            </div>

            <!-- 5.2 SUCCESS BANNER: Shown after successful registration -->
            <!-- 5.2.1 The session variable is set by HomeController->storeUser() -->
            <?php if (isset($_SESSION['register_success'])): ?>
                <div class="error-banner" style="background: #dcfce7; border-color: #86efac; color: #166534;">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                    <span><?= htmlspecialchars($_SESSION['register_success']) ?></span>
                </div>
                <!-- 5.2.2 Clear the flash message so it doesn't persist on refresh -->
                <?php unset($_SESSION['register_success']); ?>
            <?php endif; ?>

            <!-- 5.3 LOGIN FORM -->
            <!-- 5.3.1 POSTs to index.php?action=auth which calls HomeController->auth() -->
            <!-- 5.3.2 novalidate = we handle validation ourselves (JS + server-side) -->
            <form id="loginForm" method="POST" action="index.php?action=auth" novalidate>

                <!-- 5.4 USERNAME FIELD -->
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <!-- 5.4.1 input-icon-wrap positions the icon inside the input -->
                    <!-- 5.4.2 If server returned an error, add 'input-error shake' classes -->
                    <div class="input-icon-wrap <?= !empty($usernameError) ? 'input-error shake' : '' ?>">
                        <ion-icon name="person-outline"></ion-icon>
                        <!-- 5.4.3 Preserve the entered username value on error -->
                        <input type="text" name="username" id="username" class="form-input <?= !empty($usernameError) ? 'input-error' : '' ?>" placeholder="admin" autocomplete="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                    </div>
                    <!-- 5.4.4 Field-specific error message (e.g. "Username not found") -->
                    <span class="error-text <?= !empty($usernameError) ? 'visible' : '' ?>" role="alert" <?= !empty($usernameError) ? 'style="display:block"' : '' ?>><?= !empty($usernameError) ? htmlspecialchars($usernameError) : '' ?></span>
                </div>

                <!-- 5.5 PASSWORD FIELD -->
                <div class="form-group" style="margin-bottom: 30px;">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-icon-wrap <?= !empty($passwordError) ? 'input-error shake' : '' ?>">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" id="password" class="form-input <?= !empty($passwordError) ? 'input-error' : '' ?>" placeholder="••••••••" autocomplete="current-password">
                    </div>
                    <!-- 5.5.1 Password-specific error (e.g. "Incorrect password") -->
                    <span class="error-text <?= !empty($passwordError) ? 'visible' : '' ?>" role="alert" <?= !empty($passwordError) ? 'style="display:block"' : '' ?>><?= !empty($passwordError) ? htmlspecialchars($passwordError) : '' ?></span>
                </div>

                <!-- 5.6 SUBMIT BUTTON -->
                <button type="submit" class="btn-primary">Sign In</button>
            </form>

            <!-- 5.7 REGISTER LINK: Navigate to registration page -->
            <a href="index.php?action=register" class="auth-link">
                Don't have an account? <strong>Create Account</strong>
            </a>

            <!-- 5.8 Footer text -->
            <div style="margin-top: 20px; text-align: center; color: var(--text-muted); font-size: 0.8rem;">
                Protected by 256-bit SSL Encryption
            </div>
        </div>
    </div>
</div>

<!-- 6. JAVASCRIPT -->
<!-- 6.1 app.js handles client-side validation for the login form -->
<script src="js/app.js?v=<?php echo time(); ?>"></script>
</body>
</html>

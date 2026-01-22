<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - FinanceTrack</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body>

<div class="login-container">
    <div class="login-art">
        <div class="texture-overlay"></div>
        <div class="art-content">
            <h1>Join Us.</h1>
            <p>Start your journey towards financial clarity today.</p>
        </div>
        <div class="abstract-shard shard-1"></div>
        <div class="abstract-shard shard-2"></div>
    </div>

    <div class="login-form-wrapper">
        <div class="login-card">
            <div class="login-header">
                <h2>Create Account</h2>
                <p>Enter your details below to register.</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="error-banner">
                    <ion-icon name="warning-outline"></ion-icon> <?= $error ?>
                </div>
            <?php endif; ?>

            <form id="registerForm" method="POST" action="index.php?action=storeUser" novalidate>
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="input-icon-wrap">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="username" id="reg-user" class="form-input" placeholder="Choose a username">
                    </div>
                    <small class="input-error-msg">Username is required</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-icon-wrap">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" id="reg-pass" class="form-input" placeholder="Create a password">
                    </div>
                    <small class="input-error-msg">Password is required</small>
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <label class="form-label">Confirm Password</label>
                    <div class="input-icon-wrap">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="confirm_password" id="reg-confirm" class="form-input" placeholder="Repeat password">
                    </div>
                    <small class="input-error-msg">Passwords do not match</small>
                </div>

                <button type="submit" class="btn-primary">Create Account</button>
            </form>
            
            <a href="index.php?action=login" class="auth-link">
                Already have an account? <strong>Sign In</strong>
            </a>
        </div>
    </div>
</div>

<script>
    // Industry Standard Client-Side Validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        let valid = true;
        
        // 1. Validate Username
        const user = document.getElementById('reg-user');
        if (user.value.trim() === '') {
            showError(user, "Username is required");
            valid = false;
        } else {
            clearError(user);
        }

        // 2. Validate Password
        const pass = document.getElementById('reg-pass');
        if (pass.value.trim() === '') {
            showError(pass, "Password is required");
            valid = false;
        } else {
            clearError(pass);
        }

        // 3. Validate Confirm
        const confirm = document.getElementById('reg-confirm');
        if (confirm.value.trim() === '') {
            showError(confirm, "Please confirm your password");
            valid = false;
        } else if (confirm.value !== pass.value) {
            showError(confirm, "Passwords do not match");
            valid = false;
        } else {
            clearError(confirm);
        }

        if (!valid) e.preventDefault();
    });

    function showError(input, msg) {
        input.classList.add('error');
        // Find the small tag in the same parent group
        const msgEl = input.closest('.form-group').querySelector('.input-error-msg');
        if(msgEl) {
            msgEl.innerText = msg;
            msgEl.classList.add('visible');
        }
    }

    function clearError(input) {
        input.classList.remove('error');
        const msgEl = input.closest('.form-group').querySelector('.input-error-msg');
        if(msgEl) msgEl.classList.remove('visible');
    }

    // Clear error on input typing
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('input', () => clearError(input));
    });
</script>

</body>
</html>
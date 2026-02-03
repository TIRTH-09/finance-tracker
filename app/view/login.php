<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - FinanceTrack</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body>

<div class="login-container">
    <div class="login-art">
        <div class="texture-overlay"></div>
        <div class="art-content">
            <h1>Precision<br>Finance.</h1>
            <p>Experience clarity in your financial journey with our advanced tracking suite.</p>
        </div>
        <div class="abstract-shard shard-1"></div>
        <div class="abstract-shard shard-2"></div>
    </div>

    <div class="login-form-wrapper">
        <div class="login-card">
            <div class="login-header">
                <h2>Welcome back</h2>
                <p>Please enter your credentials to access your dashboard.</p>
            </div>

            <?php if(isset($_GET['registered'])): ?>
                <div style="background:#dcfce7; color:#166534; padding:12px; border-radius:12px; margin-bottom:20px; font-size:0.9rem; border:1px solid #bbf7d0;">
                    Account created! Please sign in.
                </div>
            <?php endif; ?>

            <?php if(isset($error)): ?>
                <div class="error-banner">
                    <ion-icon name="warning-outline"></ion-icon> <?= $error ?>
                </div>
            <?php endif; ?>

<<<<<<< HEAD
            <form id="loginForm" method="POST" action="index.php?action=auth" novalidate>
=======
            <form method="POST" action="index.php?action=auth" id="loginForm">
>>>>>>> c4fb70f (ready3)
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="input-icon-wrap <?= isset($error) ? 'input-error shake' : '' ?>">
                        <ion-icon name="person-outline"></ion-icon>
<<<<<<< HEAD
                        <input type="text" name="username" id="login-user" class="form-input" 
                               value="<?= isset($username_value) ? htmlspecialchars($username_value) : '' ?>" 
                               placeholder="admin">
                    </div>
                    <small class="input-error-msg">Username is required</small>
=======
                        <input type="text" name="username" id="username" class="form-input <?= isset($error) ? 'input-error' : '' ?>" placeholder="admin" autocomplete="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                    </div>
                    <span class="error-text" id="username-error" role="alert"></span>
>>>>>>> c4fb70f (ready3)
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <label class="form-label">Password</label>
                    <div class="input-icon-wrap <?= isset($error) ? 'input-error shake' : '' ?>">
                        <ion-icon name="lock-closed-outline"></ion-icon>
<<<<<<< HEAD
                        <input type="password" name="password" id="login-pass" class="form-input" placeholder="••••••••">
                    </div>
                    <small class="input-error-msg">Password is required</small>
=======
                        <input type="password" name="password" id="password" class="form-input <?= isset($error) ? 'input-error' : '' ?>" placeholder="••••••••" autocomplete="current-password">
                    </div>
                    <span class="error-text" id="password-error" role="alert"></span>
>>>>>>> c4fb70f (ready3)
                </div>

                <button type="submit" class="btn-primary">Sign In</button>
            </form>
            
            <a href="index.php?action=register" class="auth-link">
                Don't have an account? <strong>Create One</strong>
            </a>

            <div style="margin-top: 30px; text-align: center; color: var(--text-muted); font-size: 0.8rem;">
                Protected by 256-bit SSL Encryption
            </div>
        </div>
    </div>
</div>

<<<<<<< HEAD
<script>
    // Validation Logic
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        let valid = true;
        const user = document.getElementById('login-user');
        const pass = document.getElementById('login-pass');

        // Check Username
        if (user.value.trim() === '') {
            showError(user);
            valid = false;
        } else {
            clearError(user);
        }

        // Check Password
        if (pass.value.trim() === '') {
            showError(pass);
            valid = false;
        } else {
            clearError(pass);
        }

        if (!valid) e.preventDefault();
    });

    function showError(input) {
        input.classList.add('error'); // Adds Red Border
        const msg = input.closest('.form-group').querySelector('.input-error-msg');
        if(msg) msg.classList.add('visible'); // Shows red text
    }

    function clearError(input) {
        input.classList.remove('error');
        const msg = input.closest('.form-group').querySelector('.input-error-msg');
        if(msg) msg.classList.remove('visible');
    }

    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('input', () => clearError(input));
    });
</script>

=======
<script src="js/app.js?v=<?php echo time(); ?>"></script>
>>>>>>> c4fb70f (ready3)
</body>
</html>
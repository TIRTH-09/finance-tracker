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

            <?php if (isset($_SESSION['register_success'])): ?>
                <div class="error-banner" style="background: #dcfce7; border-color: #86efac; color: #166534;">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                    <span><?= htmlspecialchars($_SESSION['register_success']) ?></span>
                </div>
                <?php unset($_SESSION['register_success']); ?>
            <?php endif; ?>

            <form id="loginForm" method="POST" action="index.php?action=auth" novalidate>
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <div class="input-icon-wrap <?= !empty($usernameError) ? 'input-error shake' : '' ?>">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="username" id="username" class="form-input <?= !empty($usernameError) ? 'input-error' : '' ?>" placeholder="admin" autocomplete="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                    </div>
                    <span class="error-text <?= !empty($usernameError) ? 'visible' : '' ?>" role="alert" <?= !empty($usernameError) ? 'style="display:block"' : '' ?>><?= !empty($usernameError) ? htmlspecialchars($usernameError) : '' ?></span>
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-icon-wrap <?= !empty($passwordError) ? 'input-error shake' : '' ?>">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" id="password" class="form-input <?= !empty($passwordError) ? 'input-error' : '' ?>" placeholder="••••••••" autocomplete="current-password">
                    </div>
                    <span class="error-text <?= !empty($passwordError) ? 'visible' : '' ?>" role="alert" <?= !empty($passwordError) ? 'style="display:block"' : '' ?>><?= !empty($passwordError) ? htmlspecialchars($passwordError) : '' ?></span>
                </div>

                <button type="submit" class="btn-primary">Sign In</button>
            </form>

            <a href="index.php?action=register" class="auth-link">
                Don't have an account? <strong>Create Account</strong>
            </a>

            <div style="margin-top: 20px; text-align: center; color: var(--text-muted); font-size: 0.8rem;">
                Protected by 256-bit SSL Encryption
            </div>
        </div>
    </div>
</div>

<script src="js/app.js?v=<?php echo time(); ?>"></script>
</body>
</html>

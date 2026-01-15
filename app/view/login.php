<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Finance Tracker</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</head>
<body>

<div class="login-container">
    <div class="login-art">
        <div class="art-content">
            <h1>FinanceTrack</h1>
            <p>Master your money with elegance.</p>
            <div class="floating-shape shape-1"></div>
            <div class="floating-shape shape-2"></div>
        </div>
    </div>

    <div class="login-form-wrapper">
        <div class="login-card">
            <div class="login-header">
                <h2>Welcome Back</h2>
                <p>Please enter your details to sign in.</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="error-banner">
                    <ion-icon name="alert-circle"></ion-icon> <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=auth">
                <div class="form-group">
                    <label class="form-label">Username</label>
                    <div class="input-icon-wrap">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="username" class="form-input" placeholder="e.g. admin" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <div class="input-icon-wrap">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" class="form-input" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary" style="margin-top: 10px;">
                    Sign In
                    <ion-icon name="arrow-forward-outline" style="margin-left:8px;"></ion-icon>
                </button>
            </form>

            <div style="margin-top: 24px; text-align: center; font-size: 0.9rem; color: var(--text-muted);">
                <p>Default credentials: <strong>admin</strong> / <strong>password</strong></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
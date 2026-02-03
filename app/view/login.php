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

            <?php if (isset($error)): ?>
                <div class="error-banner">
                    <ion-icon name="warning-outline"></ion-icon>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>

            <form id="loginForm" method="POST" action="index.php?action=auth" novalidate>
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <div class="input-icon-wrap <?= isset($error) ? 'input-error shake' : '' ?>">
                        <ion-icon name="person-outline"></ion-icon>
                        <input type="text" name="username" id="username" class="form-input <?= isset($error) ? 'input-error' : '' ?>" placeholder="admin" autocomplete="username" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
                    </div>
                    <span class="error-text" role="alert"></span>
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-icon-wrap <?= isset($error) ? 'input-error shake' : '' ?>">
                        <ion-icon name="lock-closed-outline"></ion-icon>
                        <input type="password" name="password" id="password" class="form-input <?= isset($error) ? 'input-error' : '' ?>" placeholder="••••••••" autocomplete="current-password">
                    </div>
                    <span class="error-text" role="alert"></span>
                </div>

                <button type="submit" class="btn-primary">Sign In</button>
            </form>

            <div style="margin-top: 30px; text-align: center; color: var(--text-muted); font-size: 0.8rem;">
                Protected by 256-bit SSL Encryption
            </div>
        </div>
    </div>
</div>

<script src="js/app.js?v=<?php echo time(); ?>"></script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Alpha Core Markets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-logo">
                <h1>Alpha Core Markets</h1>
            </div>
            
            <h2 class="auth-title">Create New Password</h2>
            <p class="auth-subtitle">Enter a new password for your account</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="/reset-password">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                
                <div class="form-group">
                    <label class="form-label" for="password">New Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter new password" minlength="8" required>
                    <small style="color: var(--text-secondary);">Minimum 8 characters</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm password" minlength="8" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
            </form>

            <p class="text-center mt-4" style="color: var(--text-secondary);">
                Remember your password? <a href="/login">Sign in</a>
            </p>
        </div>
    </div>
</body>
</html>

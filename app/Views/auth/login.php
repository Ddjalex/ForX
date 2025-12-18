<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Alpha Core Markets</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-logo">
                <img src="/assets/logo.png" alt="Alpha Core Markets Logo" class="auth-logo-img">
                <h1>Alpha Core Markets</h1>
            </div>
            
            <h2 class="auth-title">Welcome back</h2>
            <p class="auth-subtitle">Sign in to your trading account</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="/login">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                
                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>

                <div class="form-group" style="text-align: right;">
                    <a href="/forgot-password">Forgot password?</a>
                </div>

                <div class="form-group">
                    <div class="cf-turnstile" data-sitekey="<?= $turnstile_site_key ?? '' ?>" data-theme="dark"></div>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </form>

            <p class="text-center mt-4" style="color: var(--text-secondary);">
                Don't have an account? <a href="/register">Create account</a>
            </p>
        </div>
    </div>
</body>
</html>

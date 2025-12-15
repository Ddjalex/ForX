<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - TradePro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .auth-box-wide {
            max-width: 700px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 4px;
        }
        .password-toggle:hover {
            color: var(--text-primary);
        }
        .captcha-row {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .captcha-image {
            background: linear-gradient(45deg, #1a1a2e, #16213e);
            padding: 12px 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 24px;
            font-weight: bold;
            color: #FFD700;
            letter-spacing: 8px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            user-select: none;
        }
        .captcha-input {
            flex: 1;
        }
        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            .captcha-row {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-box auth-box-wide">
            <div class="auth-logo">
                <h1>TradePro</h1>
            </div>
            
            <h2 class="auth-title">Sign Up for Free</h2>
            <p class="auth-subtitle">It's Free to Sign up and only takes a minute.</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="/register">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?? '' ?>">
                <input type="hidden" name="captcha_answer" id="captcha_answer" value="">
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your Name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter Preferred Username" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="Enter your phone">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="gender">Gender</label>
                        <select id="gender" name="gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="country">Country</label>
                        <select id="country" name="country" class="form-control">
                            <option value="">Select Country</option>
                            <option value="AF">Afghanistan</option>
                            <option value="AL">Albania</option>
                            <option value="DZ">Algeria</option>
                            <option value="AU">Australia</option>
                            <option value="BD">Bangladesh</option>
                            <option value="BR">Brazil</option>
                            <option value="CA">Canada</option>
                            <option value="CN">China</option>
                            <option value="EG">Egypt</option>
                            <option value="FR">France</option>
                            <option value="DE">Germany</option>
                            <option value="IN">India</option>
                            <option value="ID">Indonesia</option>
                            <option value="IR">Iran</option>
                            <option value="IQ">Iraq</option>
                            <option value="IT">Italy</option>
                            <option value="JP">Japan</option>
                            <option value="KE">Kenya</option>
                            <option value="MY">Malaysia</option>
                            <option value="MX">Mexico</option>
                            <option value="NG">Nigeria</option>
                            <option value="PK">Pakistan</option>
                            <option value="PH">Philippines</option>
                            <option value="RU">Russia</option>
                            <option value="SA">Saudi Arabia</option>
                            <option value="SG">Singapore</option>
                            <option value="ZA">South Africa</option>
                            <option value="KR">South Korea</option>
                            <option value="ES">Spain</option>
                            <option value="TH">Thailand</option>
                            <option value="TR">Turkey</option>
                            <option value="AE">United Arab Emirates</option>
                            <option value="GB">United Kingdom</option>
                            <option value="US">United States</option>
                            <option value="VN">Vietnam</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required minlength="8">
                            <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="confirm_password">Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', this)">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Captcha</label>
                    <div class="captcha-row">
                        <div class="captcha-image" id="captchaDisplay"></div>
                        <input type="text" id="captcha" name="captcha" class="form-control captcha-input" placeholder="Enter Captcha" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block mt-4">Sign Up</button>
            </form>

            <p class="text-center mt-4" style="color: var(--text-secondary);">
                Already have an account? <a href="/login">Sign in</a>
            </p>
        </div>
    </div>

    <script>
        function generateCaptcha() {
            const chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            let captcha = '';
            for (let i = 0; i < 6; i++) {
                captcha += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('captchaDisplay').textContent = captcha;
            document.getElementById('captcha_answer').value = captcha;
        }

        function togglePassword(fieldId, btn) {
            const field = document.getElementById(fieldId);
            if (field.type === 'password') {
                field.type = 'text';
            } else {
                field.type = 'password';
            }
        }

        generateCaptcha();
    </script>
</body>
</html>

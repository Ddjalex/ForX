<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Alpha Core Markets</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .verification-code-inputs {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 20px 0;
        }
        .verification-code-inputs input {
            width: 50px;
            height: 60px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-radius: 8px;
            border: 2px solid var(--border-color);
            background: var(--bg-tertiary);
            color: var(--text-primary);
        }
        .verification-code-inputs input:focus {
            border-color: var(--primary);
            outline: none;
        }
        .resend-link {
            color: var(--primary);
            cursor: pointer;
            text-decoration: underline;
        }
        .resend-link:hover {
            opacity: 0.8;
        }
        .timer {
            color: var(--text-secondary);
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-logo">
                <h1>Alpha Core Markets</h1>
            </div>
            
            <h2 class="auth-title">Verify Your Email</h2>
            <p class="auth-subtitle">We've sent a 6-digit verification code to<br><strong><?= htmlspecialchars($email) ?></strong></p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST" action="/verify-email" id="verifyForm">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?>">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                <input type="hidden" name="code" id="fullCode">
                
                <div class="verification-code-inputs">
                    <input type="text" maxlength="1" class="code-input" data-index="0" autofocus>
                    <input type="text" maxlength="1" class="code-input" data-index="1">
                    <input type="text" maxlength="1" class="code-input" data-index="2">
                    <input type="text" maxlength="1" class="code-input" data-index="3">
                    <input type="text" maxlength="1" class="code-input" data-index="4">
                    <input type="text" maxlength="1" class="code-input" data-index="5">
                </div>

                <button type="submit" class="btn btn-primary btn-block">Verify Email</button>
            </form>

            <p class="text-center mt-4" style="color: var(--text-secondary);">
                Didn't receive the code? 
                <span id="resendTimer" class="timer">Wait <span id="countdown">60</span>s</span>
                <a href="/resend-verification?email=<?= urlencode($email) ?>" id="resendLink" class="resend-link" style="display: none;">Resend Code</a>
            </p>

            <p class="text-center mt-2" style="color: var(--text-secondary);">
                <a href="/register">Back to Registration</a>
            </p>
        </div>
    </div>

    <script>
        const codeInputs = document.querySelectorAll('.code-input');
        const fullCodeInput = document.getElementById('fullCode');
        const verifyForm = document.getElementById('verifyForm');
        const resendTimer = document.getElementById('resendTimer');
        const resendLink = document.getElementById('resendLink');
        const countdownEl = document.getElementById('countdown');
        
        let countdown = 60;
        const timer = setInterval(() => {
            countdown--;
            countdownEl.textContent = countdown;
            if (countdown <= 0) {
                clearInterval(timer);
                resendTimer.style.display = 'none';
                resendLink.style.display = 'inline';
            }
        }, 1000);

        codeInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                const value = e.target.value;
                if (value.length === 1 && index < 5) {
                    codeInputs[index + 1].focus();
                }
                updateFullCode();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && index > 0) {
                    codeInputs[index - 1].focus();
                }
            });

            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pastedData = e.clipboardData.getData('text').slice(0, 6);
                [...pastedData].forEach((char, i) => {
                    if (codeInputs[i]) {
                        codeInputs[i].value = char;
                    }
                });
                updateFullCode();
                if (pastedData.length === 6) {
                    codeInputs[5].focus();
                }
            });
        });

        function updateFullCode() {
            let code = '';
            codeInputs.forEach(input => {
                code += input.value;
            });
            fullCodeInput.value = code;
        }

        verifyForm.addEventListener('submit', (e) => {
            updateFullCode();
            if (fullCodeInput.value.length !== 6) {
                e.preventDefault();
                alert('Please enter the complete 6-digit code');
            }
        });
    </script>
</body>
</html>

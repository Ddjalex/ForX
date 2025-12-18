<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found | Alpha Core Markets</title>
    <link rel="icon" type="image/png" href="/assets/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #0a1628 0%, #0f2847 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .error-container {
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        
        .error-box {
            background: rgba(30, 41, 59, 0.8);
            border: 1px solid rgba(16, 185, 129, 0.2);
            border-radius: 16px;
            padding: 60px 40px;
            backdrop-filter: blur(10px);
        }
        
        .error-code {
            font-size: 120px;
            font-weight: 700;
            background: linear-gradient(135deg, #10b981, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 20px;
            line-height: 1;
        }
        
        .error-title {
            font-size: 28px;
            font-weight: 600;
            color: #f8fafc;
            margin-bottom: 12px;
        }
        
        .error-message {
            font-size: 16px;
            color: #94a3b8;
            margin-bottom: 40px;
            line-height: 1.6;
        }
        
        .error-button {
            display: inline-block;
            padding: 12px 32px;
            background: linear-gradient(135deg, #10b981, #06b6d4);
            color: #0a1628;
            text-decoration: none;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }
        
        .error-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(16, 185, 129, 0.3);
        }
        
        .error-icon {
            margin-bottom: 30px;
        }
        
        .error-icon svg {
            width: 60px;
            height: 60px;
            opacity: 0.6;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-box">
            <div class="error-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: #10b981;">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <div class="error-code">404</div>
            <h1 class="error-title">Page Not Found</h1>
            <p class="error-message">The page you're looking for doesn't exist. It might have been moved or deleted.</p>
            <a href="/" class="error-button">Go Home</a>
        </div>
    </div>
</body>
</html>
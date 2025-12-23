<?php

namespace App\Services;

class EmailService
{
    private static string $apiKey;
    private static string $apiUrl = 'https://api.brevo.com/v3/smtp/email';

    public static function init(): void
    {
        self::$apiKey = getenv('BREVO_API_KEY') ?: '';
    }

    public static function sendVerificationCode(string $email, string $name, string $code): bool
    {
        self::init();
        
        if (empty(self::$apiKey)) {
            error_log('Brevo API key not configured');
            return false;
        }

        $senderEmail = getenv('BREVO_SENDER_EMAIL') ?: 'noreply@tradepro.com';
        $senderName = getenv('BREVO_SENDER_NAME') ?: 'TradePro';
        
        $data = [
            'sender' => [
                'name' => $senderName,
                'email' => $senderEmail
            ],
            'to' => [
                [
                    'email' => $email,
                    'name' => $name
                ]
            ],
            'subject' => 'Your TradePro Verification Code',
            'htmlContent' => self::getVerificationEmailTemplate($name, $code)
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . self::$apiKey,
            'content-type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        error_log('Brevo API error: ' . $response);
        return false;
    }

    private static function getVerificationEmailTemplate(string $name, string $code): string
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background-color: #1a1a1a; color: #ffffff; padding: 20px; }
                .container { max-width: 600px; margin: 0 auto; background-color: #252525; border-radius: 10px; padding: 40px; }
                .logo { color: #f5a623; font-size: 28px; font-weight: bold; text-align: center; margin-bottom: 30px; }
                .code { background-color: #f5a623; color: #000000; font-size: 32px; font-weight: bold; text-align: center; padding: 20px; border-radius: 8px; letter-spacing: 8px; margin: 30px 0; }
                .message { color: #999999; text-align: center; line-height: 1.6; }
                .footer { color: #666666; font-size: 12px; text-align: center; margin-top: 30px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="logo">TradePro</div>
                <p class="message">Hello ' . htmlspecialchars($name) . ',</p>
                <p class="message">Welcome to TradePro! Please use the verification code below to complete your registration:</p>
                <div class="code">' . $code . '</div>
                <p class="message">This code will expire in 15 minutes.</p>
                <p class="message">If you did not request this code, please ignore this email.</p>
                <div class="footer">© ' . date('Y') . ' TradePro. All rights reserved.</div>
            </div>
        </body>
        </html>';
    }

    public static function sendPasswordReset(string $email, string $name, string $token): bool
    {
        self::init();
        
        if (empty(self::$apiKey)) {
            error_log('Brevo API key not configured');
            return false;
        }

        $senderEmail = getenv('BREVO_SENDER_EMAIL') ?: 'noreply@alphacoremarkets.com';
        $senderName = getenv('BREVO_SENDER_NAME') ?: 'Alpha Core Markets';
        $resetLink = getenv('APP_URL') ?: 'https://alphacoremarkets.com';
        $resetLink .= '/reset-password?token=' . urlencode($token) . '&email=' . urlencode($email);
        
        $data = [
            'sender' => [
                'name' => $senderName,
                'email' => $senderEmail
            ],
            'to' => [
                [
                    'email' => $email,
                    'name' => $name
                ]
            ],
            'subject' => 'Reset Your Alpha Core Markets Password',
            'htmlContent' => self::getPasswordResetEmailTemplate($name, $resetLink)
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . self::$apiKey,
            'content-type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return true;
        }

        error_log('Brevo API error: ' . $response);
        return false;
    }

    private static function getPasswordResetEmailTemplate(string $name, string $resetLink): string
    {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; background-color: #1a1a1a; color: #ffffff; padding: 20px; }
                .container { max-width: 600px; margin: 0 auto; background-color: #252525; border-radius: 10px; padding: 40px; }
                .logo { color: #1abc9c; font-size: 28px; font-weight: bold; text-align: center; margin-bottom: 30px; }
                .button { background-color: #1abc9c; color: #000000; text-align: center; padding: 15px; border-radius: 8px; margin: 30px 0; }
                .button a { color: #000000; text-decoration: none; font-weight: bold; font-size: 16px; }
                .message { color: #999999; text-align: center; line-height: 1.6; }
                .footer { color: #666666; font-size: 12px; text-align: center; margin-top: 30px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="logo">Alpha Core Markets</div>
                <p class="message">Hello ' . htmlspecialchars($name) . ',</p>
                <p class="message">We received a request to reset your password. Click the button below to create a new password:</p>
                <div class="button"><a href="' . htmlspecialchars($resetLink) . '">Reset Password</a></div>
                <p class="message">This link will expire in 1 hour.</p>
                <p class="message">If you did not request a password reset, please ignore this email and your password will remain unchanged.</p>
                <div class="footer">© ' . date('Y') . ' Alpha Core Markets. All rights reserved.</div>
            </div>
        </body>
        </html>';
    }

    public static function generateVerificationCode(): string
    {
        return str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}

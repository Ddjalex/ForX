<?php

namespace App\Services;

class EmailService
{
    private static string $apiKey;
    private static string $apiUrl = 'https://api.brevo.com/v3/smtp/email';

    public static function init(): void
    {
        self::$apiKey = getenv('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') ?: '';
    }

    public static function sendVerificationCode(string $email, string $name, string $code): bool
    {
        self::init();
        
        if (empty(self::$apiKey)) {
            error_log('Brevo API key not configured');
            return false;
        }

        $senderEmail = getenv('BREVO_SENDER_EMAIL') ?: 'almesagadw@gmail.com';
        $senderName = getenv('BREVO_SENDER_NAME') ?: 'alphacoremarkets';
        
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
            'subject' => 'Your alpha core market Verification Code',
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
                <div class="logo">alpha core market</div>
                <p class="message">Hello ' . htmlspecialchars($name) . ',</p>
                <p class="message">Welcome to alpha core market! Please use the verification code below to complete your registration:</p>
                <div class="code">' . $code . '</div>
                <p class="message">This code will expire in 15 minutes.</p>
                <p class="message">If you did not request this code, please ignore this email.</p>
                <div class="footer">© ' . date('Y') . ' TradePro. All rights reserved.</div>
            </div>
        </body>
        </html>';
    }

    public static function generateVerificationCode(): string
    {
        return str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}

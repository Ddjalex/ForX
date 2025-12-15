<?php

namespace App\Services;

class TurnstileService
{
    private static string $verifyUrl = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';

    public static function getSiteKey(): string
    {
        return $_ENV['TURNSTILE_SITE_KEY'] ?? '';
    }

    public static function verify(string $token, ?string $remoteIp = null): bool
    {
        $secretKey = $_ENV['TURNSTILE_SECRET_KEY'] ?? '';
        
        if (empty($secretKey) || empty($token)) {
            return false;
        }

        $remoteIp = $remoteIp ?? ($_SERVER['REMOTE_ADDR'] ?? '');

        $data = [
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => $remoteIp,
        ];

        $ch = curl_init(self::$verifyUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        }

        curl_close($ch);

        $result = json_decode($response, true);

        return isset($result['success']) && $result['success'] === true;
    }
}

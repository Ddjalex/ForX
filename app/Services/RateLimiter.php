<?php

namespace App\Services;

class RateLimiter
{
    private static string $storageDir = __DIR__ . '/../../storage/rate_limits/';

    public static function attempt(string $key, int $maxAttempts, int $decaySeconds): bool
    {
        self::ensureStorageDir();
        
        $file = self::$storageDir . md5($key) . '.json';
        $data = self::getData($file);
        
        $now = time();
        $data['attempts'] = array_filter($data['attempts'] ?? [], fn($t) => $t > $now - $decaySeconds);
        
        if (count($data['attempts']) >= $maxAttempts) {
            return false;
        }
        
        $data['attempts'][] = $now;
        file_put_contents($file, json_encode($data));
        
        return true;
    }

    public static function tooManyAttempts(string $key, int $maxAttempts, int $decaySeconds): bool
    {
        self::ensureStorageDir();
        
        $file = self::$storageDir . md5($key) . '.json';
        $data = self::getData($file);
        
        $now = time();
        $attempts = array_filter($data['attempts'] ?? [], fn($t) => $t > $now - $decaySeconds);
        
        return count($attempts) >= $maxAttempts;
    }

    public static function clear(string $key): void
    {
        $file = self::$storageDir . md5($key) . '.json';
        if (file_exists($file)) {
            unlink($file);
        }
    }

    public static function hit(string $key): void
    {
        self::ensureStorageDir();
        
        $file = self::$storageDir . md5($key) . '.json';
        $data = self::getData($file);
        
        $data['attempts'][] = time();
        file_put_contents($file, json_encode($data));
    }

    private static function getData(string $file): array
    {
        if (!file_exists($file)) {
            return ['attempts' => []];
        }
        
        $content = file_get_contents($file);
        return json_decode($content, true) ?: ['attempts' => []];
    }

    private static function ensureStorageDir(): void
    {
        if (!is_dir(self::$storageDir)) {
            mkdir(self::$storageDir, 0755, true);
        }
    }
}

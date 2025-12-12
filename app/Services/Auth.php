<?php

namespace App\Services;

class Auth
{
    public static function attempt(string $email, string $password): bool
    {
        $user = Database::fetch(
            "SELECT * FROM users WHERE email = ? AND status = 'active'",
            [$email]
        );

        if ($user && password_verify($password, $user['password'])) {
            self::login($user);
            self::logLogin($user['id'], true);
            return true;
        }

        if ($user) {
            self::logLogin($user['id'], false);
        }

        return false;
    }

    public static function login(array $user): void
    {
        Session::set('user_id', $user['id']);
        Session::set('user_email', $user['email']);
        Session::set('user_role', $user['role'] ?? 'user');
        Session::set('logged_in', true);
        
        Database::update('users', ['last_login' => date('Y-m-d H:i:s')], 'id = ?', [$user['id']]);
    }

    public static function logout(): void
    {
        Session::destroy();
    }

    public static function check(): bool
    {
        return Session::get('logged_in', false) === true;
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return Database::fetch(
            "SELECT * FROM users WHERE id = ?",
            [Session::get('user_id')]
        );
    }

    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    public static function isAdmin(): bool
    {
        $role = Session::get('user_role', 'user');
        return in_array($role, ['admin', 'superadmin', 'finance_admin', 'support_admin']);
    }

    public static function isSuperAdmin(): bool
    {
        return Session::get('user_role') === 'superadmin';
    }

    private static function logLogin(int $userId, bool $success): void
    {
        Database::insert('login_logs', [
            'user_id' => $userId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'success' => $success ? 1 : 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public static function register(array $data): int
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        return Database::insert('users', [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'status' => 'active',
            'role' => 'user',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}

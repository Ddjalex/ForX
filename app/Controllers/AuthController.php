<?php

namespace App\Controllers;

use App\Services\Auth;
use App\Services\Database;
use App\Services\Router;
use App\Services\Session;
use App\Services\AuditLog;
use App\Services\RateLimiter;

class AuthController
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_SECONDS = 900;

    public function showLogin(): void
    {
        echo Router::render('auth/login', [
            'csrf_token' => Session::generateCsrfToken(),
            'error' => Session::getFlash('error'),
            'success' => Session::getFlash('success'),
        ]);
    }

    public function login(): void
    {
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $rateLimitKey = 'login_' . $ip . '_' . $email;

        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request. Please try again.');
            Router::redirect('/login');
            return;
        }

        if (RateLimiter::tooManyAttempts($rateLimitKey, self::MAX_LOGIN_ATTEMPTS, self::LOCKOUT_SECONDS)) {
            Session::flash('error', 'Too many login attempts. Please try again in 15 minutes.');
            Router::redirect('/login');
            return;
        }

        if (empty($email) || empty($password)) {
            Session::flash('error', 'Please fill in all fields.');
            Router::redirect('/login');
            return;
        }

        if (Auth::attempt($email, $password)) {
            RateLimiter::clear($rateLimitKey);
            AuditLog::log('login', 'user', Auth::id());
            
            if (Auth::isAdmin()) {
                Router::redirect('/admin');
            } else {
                Router::redirect('/dashboard');
            }
        } else {
            RateLimiter::hit($rateLimitKey);
            Session::flash('error', 'Invalid email or password.');
            Router::redirect('/login');
        }
    }

    public function showRegister(): void
    {
        echo Router::render('auth/register', [
            'csrf_token' => Session::generateCsrfToken(),
            'error' => Session::getFlash('error'),
        ]);
    }

    public function register(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request. Please try again.');
            Router::redirect('/register');
            return;
        }

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            Session::flash('error', 'Please fill in all fields.');
            Router::redirect('/register');
            return;
        }

        if ($password !== $confirmPassword) {
            Session::flash('error', 'Passwords do not match.');
            Router::redirect('/register');
            return;
        }

        if (strlen($password) < 8) {
            Session::flash('error', 'Password must be at least 8 characters.');
            Router::redirect('/register');
            return;
        }

        $existing = Database::fetch("SELECT id FROM users WHERE email = ?", [$email]);
        if ($existing) {
            Session::flash('error', 'Email already registered.');
            Router::redirect('/register');
            return;
        }

        $userId = Auth::register([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        Database::insert('wallets', [
            'user_id' => $userId,
            'balance' => 0,
            'margin_used' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        AuditLog::log('register', 'user', $userId);

        Session::flash('success', 'Registration successful! Please log in.');
        Router::redirect('/login');
    }

    public function logout(): void
    {
        AuditLog::log('logout', 'user', Auth::id());
        Auth::logout();
        Router::redirect('/login');
    }

    public function showForgotPassword(): void
    {
        echo Router::render('auth/forgot-password', [
            'csrf_token' => Session::generateCsrfToken(),
            'error' => Session::getFlash('error'),
            'success' => Session::getFlash('success'),
        ]);
    }

    public function forgotPassword(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/forgot-password');
            return;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
        $user = Database::fetch("SELECT id FROM users WHERE email = ?", [$email]);
        
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            Database::update('users', [
                'reset_token' => $token,
                'reset_expires' => $expires,
            ], 'id = ?', [$user['id']]);
            
            AuditLog::log('password_reset_request', 'user', $user['id']);
        }

        Session::flash('success', 'If the email exists, a password reset link will be sent.');
        Router::redirect('/forgot-password');
    }
}

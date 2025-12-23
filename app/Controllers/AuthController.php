<?php

namespace App\Controllers;

use App\Services\Auth;
use App\Services\Database;
use App\Services\Router;
use App\Services\Session;
use App\Services\AuditLog;
use App\Services\RateLimiter;
use App\Services\EmailService;
use App\Services\TurnstileService;

class AuthController
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_SECONDS = 900;

    public function showLogin(): void
    {
        $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
        echo Router::render('auth/login', [
            'csrf_token' => Session::generateCsrfToken(),
            'error' => Session::getFlash('error'),
            'success' => Session::getFlash('success'),
            'turnstile_site_key' => TurnstileService::getSiteKey(),
            'email' => $email ?? '',
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

        $turnstileToken = $_POST['cf-turnstile-response'] ?? '';
        if (!TurnstileService::verify($turnstileToken)) {
            Session::flash('error', 'Please complete the captcha verification.');
            Router::redirect('/login');
            return;
        }

        if (empty($email) || empty($password)) {
            Session::flash('error', 'Please fill in all fields.');
            Router::redirect('/login');
            return;
        }

        $result = Auth::attempt($email, $password);
        
        if ($result === 'unverified') {
            $user = Database::fetch("SELECT name FROM users WHERE email = ?", [$email]);
            if ($user) {
                $verificationCode = EmailService::generateVerificationCode();
                $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                
                Database::update('users', [
                    'verification_code' => $verificationCode,
                    'verification_expires' => $expires,
                ], 'email = ?', [$email]);
                
                $emailSent = EmailService::sendVerificationCode($email, $user['name'], $verificationCode);
                
                if ($emailSent) {
                    Session::flash('error', 'Please verify your email first. A new code has been sent.');
                } else {
                    Session::flash('error', 'Please verify your email. Unable to send verification code, please try again later.');
                }
                Router::redirect('/verify-email?email=' . urlencode($email));
                return;
            }
        }

        if ($result === true) {
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
        // Extract referral code from URL parameter
        $referralCode = '';
        if (!empty($_GET['ref'])) {
            // Sanitize: only keep alphanumeric, hyphens, underscores
            $referralCode = preg_replace('/[^a-zA-Z0-9_-]/', '', trim($_GET['ref']));
        }
        
        echo Router::render('auth/register', [
            'csrf_token' => Session::generateCsrfToken(),
            'error' => Session::getFlash('error'),
            'referral_code' => $referralCode,
            'turnstile_site_key' => TurnstileService::getSiteKey(),
        ]);
    }

    public function register(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request. Please try again.');
            Router::redirect('/register');
            return;
        }

        $turnstileToken = $_POST['cf-turnstile-response'] ?? '';
        if (!TurnstileService::verify($turnstileToken)) {
            Session::flash('error', 'Please complete the captcha verification.');
            Router::redirect('/register');
            return;
        }

        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $referralLink = filter_input(INPUT_POST, 'referral_link', FILTER_SANITIZE_SPECIAL_CHARS);

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

        $existing = Database::fetch("SELECT id, email_verified FROM users WHERE email = ?", [$email]);
        if ($existing) {
            if (!$existing['email_verified']) {
                $verificationCode = EmailService::generateVerificationCode();
                $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                
                Database::update('users', [
                    'verification_code' => $verificationCode,
                    'verification_expires' => $expires,
                ], 'id = ?', [$existing['id']]);
                
                EmailService::sendVerificationCode($email, $name, $verificationCode);
                
                Router::redirect('/verify-email?email=' . urlencode($email));
                return;
            }
            Session::flash('error', 'Email already registered.');
            Router::redirect('/register');
            return;
        }

        $verificationCode = EmailService::generateVerificationCode();
        $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        $userId = Auth::register([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'verification_code' => $verificationCode,
            'verification_expires' => $expires,
            'email_verified' => false,
        ]);

        Database::insert('wallets', [
            'user_id' => $userId,
            'balance' => 0,
            'margin_used' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        if (!empty($referralLink)) {
            $referralData = Database::fetch(
                "SELECT user_id FROM referral_links WHERE code = ? OR id = ?",
                [$referralLink, $referralLink]
            );
            
            if ($referralData) {
                Database::insert('referrals', [
                    'referral_code' => $referralLink,
                    'referrer_user_id' => $referralData['user_id'],
                    'referred_user_id' => $userId,
                    'status' => 'pending',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        AuditLog::log('register', 'user', $userId);

        $emailSent = EmailService::sendVerificationCode($email, $name, $verificationCode);
        
        Router::redirect('/verify-email?email=' . urlencode($email));
    }

    public function showVerifyEmail(): void
    {
        $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);
        
        if (empty($email)) {
            Router::redirect('/register');
            return;
        }

        echo Router::render('auth/verify-email', [
            'csrf_token' => Session::generateCsrfToken(),
            'email' => $email,
            'error' => Session::getFlash('error'),
            'success' => Session::getFlash('success'),
        ]);
    }

    public function verifyEmail(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request. Please try again.');
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            Router::redirect('/verify-email?email=' . urlencode($email));
            return;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $code = filter_input(INPUT_POST, 'code', FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($email) || empty($code)) {
            Session::flash('error', 'Please enter the verification code.');
            Router::redirect('/verify-email?email=' . urlencode($email));
            return;
        }

        $user = Database::fetch(
            "SELECT id, verification_code, verification_expires FROM users WHERE email = ? AND email_verified = false",
            [$email]
        );

        if (!$user) {
            Session::flash('error', 'Invalid email or already verified.');
            Router::redirect('/login');
            return;
        }

        if ($user['verification_code'] !== $code) {
            Session::flash('error', 'Invalid verification code.');
            Router::redirect('/verify-email?email=' . urlencode($email));
            return;
        }

        if (strtotime($user['verification_expires']) < time()) {
            Session::flash('error', 'Verification code has expired. Please request a new one.');
            Router::redirect('/verify-email?email=' . urlencode($email));
            return;
        }

        Database::update('users', [
            'email_verified' => true,
            'verification_code' => null,
            'verification_expires' => null,
        ], 'id = ?', [$user['id']]);

        AuditLog::log('email_verified', 'user', $user['id']);

        Session::flash('success', 'Email verified successfully! You can now log in.');
        Router::redirect('/login?email=' . urlencode($email));
    }

    public function resendVerification(): void
    {
        $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);

        if (empty($email)) {
            Router::redirect('/register');
            return;
        }

        $user = Database::fetch(
            "SELECT id, name FROM users WHERE email = ? AND email_verified = false",
            [$email]
        );

        if (!$user) {
            Session::flash('error', 'Invalid email or already verified.');
            Router::redirect('/login');
            return;
        }

        $verificationCode = EmailService::generateVerificationCode();
        $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));

        Database::update('users', [
            'verification_code' => $verificationCode,
            'verification_expires' => $expires,
        ], 'id = ?', [$user['id']]);

        $emailSent = EmailService::sendVerificationCode($email, $user['name'], $verificationCode);

        if ($emailSent) {
            Session::flash('success', 'A new verification code has been sent to your email.');
        } else {
            Session::flash('error', 'Failed to send verification email. Please try again.');
        }

        Router::redirect('/verify-email?email=' . urlencode($email));
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
        
        $user = Database::fetch("SELECT id, name FROM users WHERE email = ?", [$email]);
        
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            Database::update('users', [
                'reset_token' => $token,
                'reset_expires' => $expires,
            ], 'id = ?', [$user['id']]);
            
            EmailService::sendPasswordReset($email, $user['name'], $token);
            
            AuditLog::log('password_reset_request', 'user', $user['id']);
        }

        Session::flash('success', 'If the email exists, a password reset link will be sent.');
        Router::redirect('/forgot-password');
    }

    public function showResetPassword(): void
    {
        $token = filter_input(INPUT_GET, 'token', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_EMAIL);

        if (empty($token) || empty($email)) {
            Session::flash('error', 'Invalid reset link.');
            Router::redirect('/login');
            return;
        }

        $user = Database::fetch(
            "SELECT id, reset_token, reset_expires FROM users WHERE email = ? AND reset_token = ?",
            [$email, $token]
        );

        if (!$user) {
            Session::flash('error', 'Invalid reset link.');
            Router::redirect('/login');
            return;
        }

        if (strtotime($user['reset_expires']) < time()) {
            Session::flash('error', 'Reset link has expired. Please request a new one.');
            Router::redirect('/forgot-password');
            return;
        }

        echo Router::render('auth/reset-password', [
            'csrf_token' => Session::generateCsrfToken(),
            'token' => $token,
            'email' => $email,
            'error' => Session::getFlash('error'),
        ]);
    }

    public function updatePassword(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/login');
            return;
        }

        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($token) || empty($email)) {
            Session::flash('error', 'Invalid reset link.');
            Router::redirect('/login');
            return;
        }

        if (empty($password) || empty($confirmPassword)) {
            Session::flash('error', 'Please fill in all fields.');
            Router::redirect('/reset-password?token=' . urlencode($token) . '&email=' . urlencode($email));
            return;
        }

        if ($password !== $confirmPassword) {
            Session::flash('error', 'Passwords do not match.');
            Router::redirect('/reset-password?token=' . urlencode($token) . '&email=' . urlencode($email));
            return;
        }

        if (strlen($password) < 8) {
            Session::flash('error', 'Password must be at least 8 characters.');
            Router::redirect('/reset-password?token=' . urlencode($token) . '&email=' . urlencode($email));
            return;
        }

        $user = Database::fetch(
            "SELECT id, reset_token, reset_expires FROM users WHERE email = ? AND reset_token = ?",
            [$email, $token]
        );

        if (!$user) {
            Session::flash('error', 'Invalid reset link.');
            Router::redirect('/login');
            return;
        }

        if (strtotime($user['reset_expires']) < time()) {
            Session::flash('error', 'Reset link has expired.');
            Router::redirect('/forgot-password');
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        Database::update('users', [
            'password' => $hashedPassword,
            'reset_token' => null,
            'reset_expires' => null,
        ], 'id = ?', [$user['id']]);

        AuditLog::log('password_reset', 'user', $user['id']);

        Session::flash('success', 'Password reset successfully! You can now log in.');
        Router::redirect('/login');
    }
}

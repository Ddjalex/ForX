<?php

namespace App\Controllers;

use App\Services\Database;
use App\Services\Session;
use App\Services\Router;
use App\Services\Auth;
use App\Services\AuditLog;

class AccountsController
{
    public function index(): void
    {
        if (!Auth::check()) {
            Router::redirect('/login');
            return;
        }

        $user = Auth::user();
        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [$user['id']]);
        
        $deposits = Database::fetchAll("SELECT * FROM deposits WHERE user_id = ?", [$user['id']]);
        $withdrawals = Database::fetchAll("SELECT * FROM withdrawals WHERE user_id = ?", [$user['id']]);

        echo Router::render('user/accounts', [
            'user' => $user,
            'wallet' => $wallet,
            'deposits' => $deposits,
            'withdrawals' => $withdrawals,
            'csrf_token' => Session::generateCsrfToken(),
            'error' => Session::getFlash('error'),
            'success' => Session::getFlash('success'),
        ]);
    }

    public function updateProfile(): void
    {
        if (!Auth::check()) {
            Router::redirect('/login');
            return;
        }

        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/accounts');
            return;
        }

        $user = Auth::user();
        
        $updateData = [
            'name' => filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS),
            'username' => filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS),
            'phone' => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_SPECIAL_CHARS),
            'country' => filter_input(INPUT_POST, 'country', FILTER_SANITIZE_SPECIAL_CHARS),
            'state' => filter_input(INPUT_POST, 'state', FILTER_SANITIZE_SPECIAL_CHARS),
            'zip' => filter_input(INPUT_POST, 'zip', FILTER_SANITIZE_SPECIAL_CHARS),
            'address' => filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        Database::update('users', $updateData, 'id = ?', [$user['id']]);
        
        AuditLog::log('profile_updated', 'user', $user['id']);
        
        Session::flash('success', 'Profile updated successfully.');
        Router::redirect('/accounts?tab=profile');
    }

    public function changePassword(): void
    {
        if (!Auth::check()) {
            Router::redirect('/login');
            return;
        }

        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/accounts?tab=settings');
            return;
        }

        $user = Auth::user();
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!password_verify($oldPassword, $user['password'])) {
            Session::flash('error', 'Current password is incorrect.');
            Router::redirect('/accounts?tab=settings');
            return;
        }

        if ($newPassword !== $confirmPassword) {
            Session::flash('error', 'New passwords do not match.');
            Router::redirect('/accounts?tab=settings');
            return;
        }

        if (strlen($newPassword) < 8) {
            Session::flash('error', 'Password must be at least 8 characters.');
            Router::redirect('/accounts?tab=settings');
            return;
        }

        Database::update('users', [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = ?', [$user['id']]);

        AuditLog::log('password_changed', 'user', $user['id']);
        
        Session::flash('success', 'Password changed successfully.');
        Router::redirect('/accounts?tab=settings');
    }

    public function updateAvatar(): void
    {
        if (!Auth::check()) {
            Router::redirect('/login');
            return;
        }

        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/accounts?tab=settings');
            return;
        }

        $user = Auth::user();

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . '/storage/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = $user['id'] . '_' . time() . '.' . $extension;
            $filepath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $filepath)) {
                Database::update('users', [
                    'avatar' => '/storage/avatars/' . $filename,
                    'updated_at' => date('Y-m-d H:i:s'),
                ], 'id = ?', [$user['id']]);

                Session::flash('success', 'Profile image updated successfully.');
            } else {
                Session::flash('error', 'Failed to upload image.');
            }
        } else {
            Session::flash('error', 'Please select an image to upload.');
        }

        Router::redirect('/accounts?tab=settings');
    }

    public function create(): void
    {
        if (!Auth::check()) {
            Router::redirect('/login');
            return;
        }

        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/accounts');
            return;
        }

        $user = Auth::user();
        $accountType = $_POST['account_type'] ?? 'demo';
        $accountName = $_POST['account_name'] ?? 'Standard';
        $leverage = (int)($_POST['leverage'] ?? 100);
        $initialBalance = $accountType === 'demo' ? 100000.00 : 0.00;

        $accountNumber = $this->generateAccountNumber();

        Database::insert('trading_accounts', [
            'user_id' => $user['id'],
            'account_number' => $accountNumber,
            'account_type' => $accountType,
            'account_name' => $accountName,
            'platform' => 'MT5',
            'balance' => $initialBalance,
            'equity' => $initialBalance,
            'free_margin' => $initialBalance,
            'leverage' => $leverage,
            'currency' => 'USD',
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        AuditLog::log('account_created', 'trading_account', null, "Created {$accountType} account #{$accountNumber}");

        Session::flash('success', ucfirst($accountType) . ' account created successfully!');
        Router::redirect('/accounts?tab=' . $accountType);
    }

    public function setBalance(): void
    {
        if (!Auth::check()) {
            Router::redirect('/login');
            return;
        }

        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/accounts?tab=demo');
            return;
        }

        $user = Auth::user();
        $accountId = (int)($_POST['account_id'] ?? 0);
        $newBalance = (float)($_POST['balance'] ?? 0);

        $account = Database::fetch(
            "SELECT * FROM trading_accounts WHERE id = ? AND user_id = ? AND account_type = 'demo'",
            [$accountId, $user['id']]
        );

        if (!$account) {
            Session::flash('error', 'Account not found or not a demo account.');
            Router::redirect('/accounts?tab=demo');
            return;
        }

        if ($newBalance < 0 || $newBalance > 10000000) {
            Session::flash('error', 'Invalid balance amount. Must be between 0 and 10,000,000.');
            Router::redirect('/accounts?tab=demo');
            return;
        }

        Database::update('trading_accounts', [
            'balance' => $newBalance,
            'equity' => $newBalance,
            'free_margin' => $newBalance,
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = ?', [$accountId]);

        AuditLog::log('balance_set', 'trading_account', $accountId, "Set balance to {$newBalance}");

        Session::flash('success', 'Balance updated successfully!');
        Router::redirect('/accounts?tab=demo');
    }

    public function archive(): void
    {
        if (!Auth::check()) {
            Router::redirect('/login');
            return;
        }

        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/accounts');
            return;
        }

        $user = Auth::user();
        $accountId = (int)($_POST['id'] ?? 0);

        $account = Database::fetch(
            "SELECT * FROM trading_accounts WHERE id = ? AND user_id = ?",
            [$accountId, $user['id']]
        );

        if (!$account) {
            Session::flash('error', 'Account not found.');
            Router::redirect('/accounts');
            return;
        }

        Database::update('trading_accounts', [
            'status' => 'archived',
            'archived_at' => date('Y-m-d H:i:s'),
        ], 'id = ?', [$accountId]);

        AuditLog::log('account_archived', 'trading_account', $accountId);

        Session::flash('success', 'Account archived successfully.');
        Router::redirect('/accounts');
    }

    public function restore(): void
    {
        if (!Auth::check()) {
            Router::redirect('/login');
            return;
        }

        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/accounts?tab=archived');
            return;
        }

        $user = Auth::user();
        $accountId = (int)($_POST['id'] ?? 0);

        $account = Database::fetch(
            "SELECT * FROM trading_accounts WHERE id = ? AND user_id = ? AND status = 'archived'",
            [$accountId, $user['id']]
        );

        if (!$account) {
            Session::flash('error', 'Account not found.');
            Router::redirect('/accounts?tab=archived');
            return;
        }

        Database::update('trading_accounts', [
            'status' => 'active',
            'archived_at' => null,
        ], 'id = ?', [$accountId]);

        AuditLog::log('account_restored', 'trading_account', $accountId);

        Session::flash('success', 'Account restored successfully.');
        Router::redirect('/accounts?tab=' . $account['account_type']);
    }

    public function trade(): void
    {
        if (!Auth::check()) {
            Router::redirect('/login');
            return;
        }

        $user = Auth::user();
        $accountId = (int)($_GET['id'] ?? 0);

        $account = Database::fetch(
            "SELECT * FROM trading_accounts WHERE id = ? AND user_id = ? AND status = 'active'",
            [$accountId, $user['id']]
        );

        if (!$account) {
            Session::flash('error', 'Account not found.');
            Router::redirect('/accounts');
            return;
        }

        $markets = Database::fetchAll("SELECT m.*, p.price, p.change_24h FROM markets m LEFT JOIN prices p ON m.id = p.market_id WHERE m.status = 'active' ORDER BY m.symbol");

        echo Router::render('trading/terminal', [
            'user' => $user,
            'account' => $account,
            'markets' => $markets,
            'csrf_token' => Session::generateCsrfToken(),
        ]);
    }

    private function generateAccountNumber(): string
    {
        do {
            $number = mt_rand(100000000, 999999999);
        } while (Database::fetch("SELECT id FROM trading_accounts WHERE account_number = ?", [$number]));
        
        return (string)$number;
    }
}

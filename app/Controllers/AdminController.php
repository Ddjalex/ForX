<?php

namespace App\Controllers;

use App\Services\Auth;
use App\Services\Database;
use App\Services\Router;
use App\Services\Session;
use App\Services\AuditLog;

class AdminController
{
    public function dashboard(): void
    {
        $stats = [
            'total_users' => Database::fetch("SELECT COUNT(*) as count FROM users")['count'],
            'pending_deposits' => Database::fetch("SELECT COUNT(*) as count FROM deposits WHERE status = 'pending'")['count'],
            'pending_withdrawals' => Database::fetch("SELECT COUNT(*) as count FROM withdrawals WHERE status = 'pending'")['count'],
            'total_balance' => Database::fetch("SELECT COALESCE(SUM(balance), 0) as total FROM wallets")['total'],
            'open_positions' => Database::fetch("SELECT COUNT(*) as count FROM positions WHERE status = 'open'")['count'],
        ];

        $recentDeposits = Database::fetchAll(
            "SELECT d.*, u.name, u.email FROM deposits d 
             JOIN users u ON d.user_id = u.id 
             ORDER BY d.created_at DESC LIMIT 5"
        );

        $recentWithdrawals = Database::fetchAll(
            "SELECT w.*, u.name, u.email FROM withdrawals w 
             JOIN users u ON w.user_id = u.id 
             ORDER BY w.created_at DESC LIMIT 5"
        );

        $recentLogs = Database::fetchAll(
            "SELECT a.*, u.name, u.email FROM audit_logs a 
             LEFT JOIN users u ON a.user_id = u.id 
             ORDER BY a.created_at DESC LIMIT 10"
        );

        echo Router::render('admin/dashboard', [
            'stats' => $stats,
            'recentDeposits' => $recentDeposits,
            'recentWithdrawals' => $recentWithdrawals,
            'recentLogs' => $recentLogs,
            'csrf_token' => Session::generateCsrfToken(),
        ]);
    }

    public function users(): void
    {
        $users = Database::fetchAll(
            "SELECT u.*, w.balance, w.margin_used 
             FROM users u 
             LEFT JOIN wallets w ON u.id = w.user_id 
             ORDER BY u.created_at DESC"
        );

        echo Router::render('admin/users', [
            'users' => $users,
            'csrf_token' => Session::generateCsrfToken(),
            'success' => Session::getFlash('success'),
            'error' => Session::getFlash('error'),
        ]);
    }

    public function updateUser(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/admin/users');
            return;
        }

        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS);

        $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$userId]);
        if (!$user) {
            Session::flash('error', 'User not found.');
            Router::redirect('/admin/users');
            return;
        }

        switch ($action) {
            case 'suspend':
                Database::update('users', ['status' => 'suspended'], 'id = ?', [$userId]);
                AuditLog::log('suspend_user', 'user', $userId);
                Session::flash('success', 'User suspended.');
                break;
            case 'activate':
                Database::update('users', ['status' => 'active'], 'id = ?', [$userId]);
                AuditLog::log('activate_user', 'user', $userId);
                Session::flash('success', 'User activated.');
                break;
            case 'make_admin':
                Database::update('users', ['role' => 'admin'], 'id = ?', [$userId]);
                AuditLog::log('make_admin', 'user', $userId);
                Session::flash('success', 'User is now an admin.');
                break;
            case 'remove_admin':
                Database::update('users', ['role' => 'user'], 'id = ?', [$userId]);
                AuditLog::log('remove_admin', 'user', $userId);
                Session::flash('success', 'Admin rights removed.');
                break;
        }

        Router::redirect('/admin/users');
    }

    public function editBalance(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/admin/users');
            return;
        }

        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
        $newBalance = filter_input(INPUT_POST, 'balance', FILTER_VALIDATE_FLOAT);
        $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_SPECIAL_CHARS);

        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [$userId]);
        if (!$wallet) {
            Session::flash('error', 'Wallet not found.');
            Router::redirect('/admin/users');
            return;
        }

        $oldBalance = $wallet['balance'];
        Database::update('wallets', ['balance' => $newBalance], 'user_id = ?', [$userId]);

        AuditLog::log('edit_balance', 'wallet', $userId, [
            'old_balance' => $oldBalance,
            'new_balance' => $newBalance,
            'reason' => $reason,
        ]);

        Session::flash('success', 'Balance updated successfully.');
        Router::redirect('/admin/users');
    }

    public function deposits(): void
    {
        $deposits = Database::fetchAll(
            "SELECT d.*, u.name, u.email FROM deposits d 
             JOIN users u ON d.user_id = u.id 
             ORDER BY d.created_at DESC"
        );

        echo Router::render('admin/deposits', [
            'deposits' => $deposits,
            'csrf_token' => Session::generateCsrfToken(),
            'success' => Session::getFlash('success'),
            'error' => Session::getFlash('error'),
        ]);
    }

    public function approveDeposit(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/admin/deposits');
            return;
        }

        $depositId = filter_input(INPUT_POST, 'deposit_id', FILTER_VALIDATE_INT);
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS);

        $deposit = Database::fetch("SELECT * FROM deposits WHERE id = ?", [$depositId]);
        if (!$deposit || $deposit['status'] !== 'pending') {
            Session::flash('error', 'Deposit not found or already processed.');
            Router::redirect('/admin/deposits');
            return;
        }

        if ($action === 'approve') {
            Database::update('deposits', [
                'status' => 'approved',
                'approved_at' => date('Y-m-d H:i:s'),
                'approved_by' => Auth::id(),
            ], 'id = ?', [$depositId]);

            $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [$deposit['user_id']]);
            Database::update('wallets', [
                'balance' => $wallet['balance'] + $deposit['amount'],
            ], 'user_id = ?', [$deposit['user_id']]);

            AuditLog::log('approve_deposit', 'deposit', $depositId, [
                'amount' => $deposit['amount'],
                'user_id' => $deposit['user_id'],
            ]);

            Session::flash('success', 'Deposit approved and balance updated.');
        } else {
            $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_SPECIAL_CHARS);
            
            Database::update('deposits', [
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'approved_at' => date('Y-m-d H:i:s'),
                'approved_by' => Auth::id(),
            ], 'id = ?', [$depositId]);

            AuditLog::log('reject_deposit', 'deposit', $depositId, [
                'reason' => $reason,
                'user_id' => $deposit['user_id'],
            ]);

            Session::flash('success', 'Deposit rejected.');
        }

        Router::redirect('/admin/deposits');
    }

    public function withdrawals(): void
    {
        $withdrawals = Database::fetchAll(
            "SELECT w.*, u.name, u.email FROM withdrawals w 
             JOIN users u ON w.user_id = u.id 
             ORDER BY w.created_at DESC"
        );

        echo Router::render('admin/withdrawals', [
            'withdrawals' => $withdrawals,
            'csrf_token' => Session::generateCsrfToken(),
            'success' => Session::getFlash('success'),
            'error' => Session::getFlash('error'),
        ]);
    }

    public function approveWithdrawal(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/admin/withdrawals');
            return;
        }

        $withdrawalId = filter_input(INPUT_POST, 'withdrawal_id', FILTER_VALIDATE_INT);
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS);

        $withdrawal = Database::fetch("SELECT * FROM withdrawals WHERE id = ?", [$withdrawalId]);
        if (!$withdrawal || $withdrawal['status'] !== 'pending') {
            Session::flash('error', 'Withdrawal not found or already processed.');
            Router::redirect('/admin/withdrawals');
            return;
        }

        if ($action === 'approve') {
            $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [$withdrawal['user_id']]);
            $availableBalance = $wallet['balance'] - $wallet['margin_used'];
            
            if ($withdrawal['amount'] > $availableBalance) {
                Session::flash('error', 'User has insufficient available balance for this withdrawal.');
                Router::redirect('/admin/withdrawals');
                return;
            }
            
            Database::update('withdrawals', [
                'status' => 'approved',
                'approved_at' => date('Y-m-d H:i:s'),
                'approved_by' => Auth::id(),
            ], 'id = ?', [$withdrawalId]);

            Database::update('wallets', [
                'balance' => $wallet['balance'] - $withdrawal['amount'],
            ], 'user_id = ?', [$withdrawal['user_id']]);

            AuditLog::log('approve_withdrawal', 'withdrawal', $withdrawalId, [
                'amount' => $withdrawal['amount'],
                'address' => $withdrawal['wallet_address'],
            ]);

            Session::flash('success', 'Withdrawal approved. Please process the payment manually.');
        } elseif ($action === 'paid') {
            Database::update('withdrawals', [
                'status' => 'paid',
                'paid_at' => date('Y-m-d H:i:s'),
            ], 'id = ?', [$withdrawalId]);

            AuditLog::log('mark_withdrawal_paid', 'withdrawal', $withdrawalId);

            Session::flash('success', 'Withdrawal marked as paid.');
        } else {
            $reason = filter_input(INPUT_POST, 'reason', FILTER_SANITIZE_SPECIAL_CHARS);
            
            Database::update('withdrawals', [
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'approved_at' => date('Y-m-d H:i:s'),
                'approved_by' => Auth::id(),
            ], 'id = ?', [$withdrawalId]);

            AuditLog::log('reject_withdrawal', 'withdrawal', $withdrawalId, ['reason' => $reason]);

            Session::flash('success', 'Withdrawal rejected.');
        }

        Router::redirect('/admin/withdrawals');
    }

    public function markets(): void
    {
        $markets = Database::fetchAll("SELECT * FROM markets ORDER BY type, symbol");

        echo Router::render('admin/markets', [
            'markets' => $markets,
            'csrf_token' => Session::generateCsrfToken(),
            'success' => Session::getFlash('success'),
            'error' => Session::getFlash('error'),
        ]);
    }

    public function updateMarket(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/admin/markets');
            return;
        }

        $marketId = filter_input(INPUT_POST, 'market_id', FILTER_VALIDATE_INT);
        $spread = filter_input(INPUT_POST, 'spread', FILTER_VALIDATE_FLOAT);
        $maxLeverage = filter_input(INPUT_POST, 'max_leverage', FILTER_VALIDATE_INT);
        $minTradeSize = filter_input(INPUT_POST, 'min_trade_size', FILTER_VALIDATE_FLOAT);
        $fee = filter_input(INPUT_POST, 'fee', FILTER_VALIDATE_FLOAT);
        $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

        Database::update('markets', [
            'spread' => $spread,
            'max_leverage' => $maxLeverage,
            'min_trade_size' => $minTradeSize,
            'fee' => $fee,
            'status' => $status,
        ], 'id = ?', [$marketId]);

        AuditLog::log('update_market', 'market', $marketId, [
            'spread' => $spread,
            'max_leverage' => $maxLeverage,
        ]);

        Session::flash('success', 'Market updated successfully.');
        Router::redirect('/admin/markets');
    }

    public function settings(): void
    {
        $settings = Database::fetchAll("SELECT * FROM settings");
        $settingsArray = [];
        foreach ($settings as $setting) {
            $settingsArray[$setting['key']] = $setting['value'];
        }

        echo Router::render('admin/settings', [
            'settings' => $settingsArray,
            'csrf_token' => Session::generateCsrfToken(),
            'success' => Session::getFlash('success'),
        ]);
    }

    public function updateSettings(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/admin/settings');
            return;
        }

        unset($_POST['_csrf_token']);

        foreach ($_POST as $key => $value) {
            $existing = Database::fetch("SELECT * FROM settings WHERE key = ?", [$key]);
            if ($existing) {
                Database::update('settings', ['value' => $value], "key = ?", [$key]);
            } else {
                Database::insert('settings', ['key' => $key, 'value' => $value]);
            }
        }

        AuditLog::log('update_settings', 'settings', null, $_POST);

        Session::flash('success', 'Settings updated successfully.');
        Router::redirect('/admin/settings');
    }

    public function auditLogs(): void
    {
        $logs = Database::fetchAll(
            "SELECT a.*, u.name, u.email FROM audit_logs a 
             LEFT JOIN users u ON a.user_id = u.id 
             ORDER BY a.created_at DESC LIMIT 500"
        );

        echo Router::render('admin/audit-logs', [
            'logs' => $logs,
        ]);
    }

    public function positions(): void
    {
        $positions = Database::fetchAll(
            "SELECT p.*, u.name, u.email, m.symbol 
             FROM positions p 
             JOIN users u ON p.user_id = u.id 
             JOIN markets m ON p.market_id = m.id 
             ORDER BY p.created_at DESC"
        );

        echo Router::render('admin/positions', [
            'positions' => $positions,
            'csrf_token' => Session::generateCsrfToken(),
        ]);
    }
}

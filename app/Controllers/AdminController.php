<?php

namespace App\Controllers;

use App\Services\Auth;
use App\Services\Database;
use App\Services\Router;
use App\Services\Session;
use App\Services\AuditLog;
use App\Services\NotificationService;

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

        $recentUsers = Database::fetchAll(
            "SELECT * FROM users ORDER BY created_at DESC LIMIT 5"
        );

        echo Router::render('admin/dashboard', [
            'stats' => $stats,
            'recentDeposits' => $recentDeposits,
            'recentWithdrawals' => $recentWithdrawals,
            'recentLogs' => $recentLogs,
            'recentUsers' => $recentUsers,
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

    public function verifyUser(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/admin/users');
            return;
        }

        $userId = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

        $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$userId]);
        if (!$user) {
            Session::flash('error', 'User not found.');
            Router::redirect('/admin/users');
            return;
        }

        Database::update('users', ['email_verified' => true], 'id = ?', [$userId]);
        AuditLog::log('verify_user', 'user', $userId, ['verified_by_admin' => true]);
        Session::flash('success', 'User has been verified successfully.');

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
            if ($wallet) {
                Database::update('wallets', [
                    'balance' => $wallet['balance'] + $deposit['amount'],
                ], 'user_id = ?', [$deposit['user_id']]);
            } else {
                Database::insert('wallets', [
                    'user_id' => $deposit['user_id'],
                    'balance' => $deposit['amount'],
                    'margin_used' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }

            // Process Referral Bonus
            $this->processReferralBonus($deposit);

            // Send notification to user
            NotificationService::notifyDepositApproved($deposit['user_id'], (float)$deposit['amount']);

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

            // Send notification to user
            NotificationService::notifyDepositRejected($deposit['user_id'], $reason);

            AuditLog::log('reject_deposit', 'deposit', $depositId, [
                'reason' => $reason,
                'user_id' => $deposit['user_id'],
            ]);

            Session::flash('success', 'Deposit rejected.');
        }

        Router::redirect('/admin/deposits');
    }

    private function processReferralBonus(array $deposit): void
    {
        // Check if user has pending referral earnings
        $referralEarning = Database::fetch(
            "SELECT * FROM referral_earnings WHERE referred_id = ? AND status = 'pending' LIMIT 1",
            [$deposit['user_id']]
        );

        if (!$referralEarning) {
            return;
        }

        $referrerId = $referralEarning['referrer_id'];
        $bonusAmount = (float)$referralEarning['amount'];

        // Update referral earning to paid status
        Database::update('referral_earnings', [
            'status' => 'paid',
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = ?', [$referralEarning['id']]);

        // Add bonus to referrer's wallet
        $referrerWallet = Database::fetch(
            "SELECT * FROM wallets WHERE user_id = ?",
            [$referrerId]
        );

        if ($referrerWallet) {
            Database::update('wallets', [
                'balance' => $referrerWallet['balance'] + $bonusAmount,
            ], 'user_id = ?', [$referrerId]);
        } else {
            Database::insert('wallets', [
                'user_id' => $referrerId,
                'balance' => $bonusAmount,
                'margin_used' => 0,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        AuditLog::log('referral_bonus_awarded', 'referral', $referrerId, [
            'bonus_amount' => $bonusAmount,
            'deposit_id' => $deposit['id'],
            'referred_user_id' => $deposit['user_id'],
        ]);

        // Get referred user name for notification
        $referredUser = Database::fetch("SELECT name FROM users WHERE id = ?", [$deposit['user_id']]);
        $referredName = $referredUser['name'] ?? 'A user';

        // Send notification to referrer about bonus
        NotificationService::notifyReferralBonus($referrerId, $bonusAmount, $referredName);
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
            
            if (!$wallet) {
                Session::flash('error', 'User wallet not found.');
                Router::redirect('/admin/withdrawals');
                return;
            }
            
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
        try {
            $settings = Database::fetchAll("SELECT * FROM settings");
            $settingsArray = [];
            foreach ($settings as $setting) {
                if (isset($setting['setting_key'])) {
                    $settingsArray[$setting['setting_key']] = $setting['value'];
                }
            }

            // Check if table exists before querying
            $tableExists = Database::fetch("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_schema = 'public' AND table_name = 'deposit_networks')");
            $depositNetworks = [];
            if ($tableExists && ($tableExists['exists'] === true || $tableExists['exists'] === 't')) {
                $depositNetworks = Database::fetchAll("SELECT * FROM deposit_networks ORDER BY id ASC");
            }

            echo Router::render('admin/settings', [
                'settings' => $settingsArray,
                'depositNetworks' => $depositNetworks,
                'csrf_token' => Session::generateCsrfToken(),
                'success' => Session::getFlash('success'),
                'error' => Session::getFlash('error'),
            ]);
        } catch (\Throwable $e) {
            error_log("Admin Settings View Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
            Session::flash('error', 'Unable to load settings at this time. ' . $e->getMessage());
            Router::redirect('/admin');
        }
    }

    public function manageDepositNetworks(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/admin/settings');
            return;
        }

        $action = $_POST['action'] ?? '';
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $symbol = filter_input(INPUT_POST, 'symbol', FILTER_SANITIZE_SPECIAL_CHARS);
        $wallet_address = filter_input(INPUT_POST, 'wallet_address', FILTER_SANITIZE_SPECIAL_CHARS);
        $network_type = filter_input(INPUT_POST, 'network_type', FILTER_SANITIZE_SPECIAL_CHARS);

        switch ($action) {
            case 'add':
                Database::insert('deposit_networks', [
                    'name' => $name,
                    'symbol' => $symbol,
                    'wallet_address' => $wallet_address,
                    'network_type' => $network_type
                ]);
                Session::flash('success', 'Deposit network added.');
                break;
            case 'update':
                Database::update('deposit_networks', [
                    'name' => $name,
                    'symbol' => $symbol,
                    'wallet_address' => $wallet_address,
                    'network_type' => $network_type
                ], 'id = ?', [$id]);
                Session::flash('success', 'Deposit network updated.');
                break;
            case 'delete':
                Database::query("DELETE FROM deposit_networks WHERE id = ?", [$id]);
                Session::flash('success', 'Deposit network deleted.');
                break;
        }

        Router::redirect('/admin/settings');
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
            $existing = Database::fetch("SELECT * FROM settings WHERE setting_key = ?", [$key]);
            if ($existing) {
                Database::update('settings', ['value' => $value], "setting_key = ?", [$key]);
            } else {
                Database::insert('settings', ['setting_key' => $key, 'value' => $value]);
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

    public function changeAdminPassword(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/admin/settings');
            return;
        }

        $admin = Auth::user();
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!password_verify($oldPassword, $admin['password'])) {
            Session::flash('error', 'Current password is incorrect.');
            Router::redirect('/admin/settings');
            return;
        }

        if ($newPassword !== $confirmPassword) {
            Session::flash('error', 'New passwords do not match.');
            Router::redirect('/admin/settings');
            return;
        }

        if (strlen($newPassword) < 8) {
            Session::flash('error', 'Password must be at least 8 characters.');
            Router::redirect('/admin/settings');
            return;
        }

        Database::update('users', [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s'),
        ], 'id = ?', [$admin['id']]);

        AuditLog::log('admin_password_changed', 'user', $admin['id']);

        Session::flash('success', 'Password changed successfully.');
        Router::redirect('/admin/settings');
    }

    public function kyc(): void
    {
        $kycVerifications = Database::fetchAll(
            "SELECT kv.*, u.name as user_name, u.email as user_email,
                    (SELECT GROUP_CONCAT(CONCAT(document_type, ':', file_path) SEPARATOR '|') 
                     FROM kyc_documents WHERE kyc_id = kv.id) as docs
             FROM kyc_verifications kv
             JOIN users u ON kv.user_id = u.id
             ORDER BY kv.created_at DESC"
        );

        // Parse documents
        foreach ($kycVerifications as &$kyc) {
            $kyc['documents'] = [];
            if ($kyc['docs']) {
                $docPairs = explode('|', $kyc['docs']);
                foreach ($docPairs as $pair) {
                    [$type, $path] = explode(':', $pair);
                    $kyc['documents'][] = [
                        'document_type' => $type,
                        'file_path' => $path
                    ];
                }
            }
            unset($kyc['docs']);
        }

        $stats = [
            'pending_count' => Database::fetch("SELECT COUNT(*) as count FROM kyc_verifications WHERE status = 'pending'")['count'],
            'approved_count' => Database::fetch("SELECT COUNT(*) as count FROM kyc_verifications WHERE status = 'approved'")['count'],
            'rejected_count' => Database::fetch("SELECT COUNT(*) as count FROM kyc_verifications WHERE status = 'rejected'")['count'],
        ];

        echo Router::render('admin/kyc-approvals', [
            'kyc_verifications' => $kycVerifications,
            'pending_count' => $stats['pending_count'],
            'approved_count' => $stats['approved_count'],
            'rejected_count' => $stats['rejected_count'],
            'csrf_token' => Session::generateCsrfToken(),
        ]);
    }
}

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
        try {
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
        } catch (\Throwable $e) {
            error_log("Admin Users View Error: " . $e->getMessage());
            Session::flash('error', 'Unable to load users.');
            Router::redirect('/admin');
        }
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
        try {
            $markets = Database::fetchAll("SELECT * FROM markets ORDER BY type, symbol");

            echo Router::render('admin/markets', [
                'markets' => $markets,
                'csrf_token' => Session::generateCsrfToken(),
                'success' => Session::getFlash('success'),
                'error' => Session::getFlash('error'),
            ]);
        } catch (\Throwable $e) {
            error_log("Admin Markets View Error: " . $e->getMessage());
            Session::flash('error', 'Unable to load markets.');
            Router::redirect('/admin');
        }
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
            // Re-generate CSRF token
            $csrf_token = Session::generateCsrfToken();
            
            $settings = Database::fetchAll("SELECT * FROM settings") ?: [];
            $settingsArray = [];
            foreach ($settings as $setting) {
                if (isset($setting['key'])) {
                    $settingsArray[$setting['key']] = $setting['value'];
                }
            }

            $depositNetworks = [];
            try {
                $depositNetworks = Database::fetchAll("SELECT * FROM deposit_networks ORDER BY id ASC") ?: [];
            } catch (\Throwable $e) {
                error_log("Deposit Networks Fetch Error: " . $e->getMessage());
            }

            $success = Session::getFlash('success');
            $error = Session::getFlash('error');
            
            $viewData = [
                'settings' => $settingsArray,
                'depositNetworks' => $depositNetworks,
                'csrf_token' => $csrf_token,
                'success' => $success,
                'error' => $error,
                'pageTitle' => 'Platform Settings',
            ];

            // Re-render
            echo Router::render('admin/settings', $viewData);
        } catch (\Throwable $e) {
            error_log("Admin Settings Global Error: " . $e->getMessage());
            http_response_code(500);
            echo "<h2>System Error</h2><p>" . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    public function manageDepositNetworks(): void
    {
        try {
            error_log("=== DEPOSIT NETWORKS REQUEST ===");
            error_log("POST data: " . json_encode($_POST));
            error_log("FILES data: " . json_encode($_FILES));
            
            if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
                error_log("CSRF Token validation failed");
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
                    Router::json(['success' => false, 'error' => 'Invalid request.'], 403);
                    return;
                }
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

            error_log("Action: $action | Name: $name | Symbol: $symbol | Wallet: $wallet_address | Type: $network_type");

            $qrCodePath = null;
            if (isset($_FILES['qr_code']) && $_FILES['qr_code']['error'] === UPLOAD_ERR_OK) {
                error_log("QR Code file detected, uploading...");
                $uploadDir = 'uploads/qr_codes/';
                $fullUploadDir = __DIR__ . '/../../public/' . $uploadDir;
                
                if (!is_dir($fullUploadDir)) {
                    @mkdir($fullUploadDir, 0755, true);
                    error_log("Created upload directory: $fullUploadDir");
                }
                
                $extension = strtolower(pathinfo($_FILES['qr_code']['name'], PATHINFO_EXTENSION));
                $fileName = uniqid('qr_') . '.' . $extension;
                $targetPath = $fullUploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['qr_code']['tmp_name'], $targetPath)) {
                    $qrCodePath = '/uploads/qr_codes/' . $fileName;
                    error_log("QR Code uploaded: $qrCodePath");
                } else {
                    error_log("Failed to move uploaded QR code file");
                }
            }

            switch ($action) {
                case 'add':
                    error_log("Adding new network: name=$name, symbol=$symbol, wallet=$wallet_address, type=$network_type, qr=$qrCodePath");
                    try {
                        $result = Database::insert('deposit_networks', [
                            'name' => $name,
                            'symbol' => $symbol,
                            'wallet_address' => $wallet_address,
                            'network_type' => $network_type,
                            'qr_code' => $qrCodePath,
                            'status' => 'active'
                        ]);
                        error_log("Network insert result: " . json_encode($result));
                        error_log("Network added successfully");
                    } catch (\Throwable $e) {
                        error_log("NETWORK INSERT ERROR: " . $e->getMessage());
                        throw $e;
                    }
                    break;
                case 'update':
                    error_log("Updating network ID: $id");
                    $data = [
                        'name' => $name,
                        'symbol' => $symbol,
                        'wallet_address' => $wallet_address,
                        'network_type' => $network_type
                    ];
                    if ($qrCodePath) {
                        $data['qr_code'] = $qrCodePath;
                    }
                    Database::update('deposit_networks', $data, 'id = ?', [$id]);
                    error_log("Network updated successfully");
                    break;
                case 'delete':
                    error_log("Deleting network ID: $id");
                    Database::query("DELETE FROM deposit_networks WHERE id = ?", [$id]);
                    error_log("Network deleted successfully");
                    break;
                default:
                    error_log("No valid action specified. Action was: '$action'");
            }

            Session::flash('success', 'Operation successful.');
            Router::redirect('/admin/settings');
        } catch (\Throwable $e) {
            error_log("=== MANAGE NETWORKS CRITICAL ERROR ===");
            error_log("Error Message: " . $e->getMessage());
            error_log("Error Code: " . $e->getCode());
            error_log("Stack Trace: " . $e->getTraceAsString());
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false) {
                Router::json(['success' => false, 'error' => $e->getMessage()], 500);
                return;
            }
            
            Session::flash('error', 'Critical Error: ' . $e->getMessage());
            Router::redirect('/admin/settings');
        }
    }

    public function updateSettings(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/admin/settings');
            return;
        }

        // List of valid setting keys that can be updated
        $validSettings = [
            'min_deposit', 'min_withdrawal', 'withdrawal_fee',
            'referral_commission', 'referral_min_deposit',
            'global_max_leverage', 'max_position_size',
            'profit_control_percent', 'tawkto_property_id', 'tawkto_widget_id'
        ];
        
        try {
            foreach ($validSettings as $key) {
                if (isset($_POST[$key])) {
                    $value = is_numeric($_POST[$key]) ? $_POST[$key] : trim($_POST[$key]);
                    
                    error_log("Updating setting: $key = $value");
                    
                    // Check if setting exists
                    $existing = Database::fetch("SELECT id FROM settings WHERE key = ?", [$key]);
                    
                    if ($existing) {
                        Database::update('settings', ['value' => $value], 'key = ?', [$key]);
                    } else {
                        Database::insert('settings', [
                            'key' => $key,
                            'value' => $value
                        ]);
                    }
                }
            }

            error_log("Settings updated successfully");
            AuditLog::log('update_settings', 'settings', 0);
            Session::flash('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            error_log("Update Settings Error: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            Session::flash('error', 'Error updating settings: ' . $e->getMessage());
        }

        Router::redirect('/admin/settings');
    }

    public function kyc(): void
    {
        $kycList = Database::fetchAll(
            "SELECT k.*, u.name as user_name, u.email as user_email 
             FROM kyc_verifications k 
             JOIN users u ON k.user_id = u.id 
             ORDER BY k.created_at DESC"
        );

        // For each KYC, get documents
        foreach ($kycList as &$kyc) {
            $kyc['documents'] = Database::fetchAll(
                "SELECT * FROM kyc_documents WHERE kyc_id = ?",
                [$kyc['id']]
            );
        }

        echo Router::render('admin/kyc', [
            'kycList' => $kycList,
            'csrf_token' => Session::generateCsrfToken(),
            'success' => Session::getFlash('success'),
            'error' => Session::getFlash('error'),
        ]);
    }

    public function approveKyc(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/admin/kyc');
            return;
        }

        $kycId = filter_input(INPUT_POST, 'kyc_id', FILTER_VALIDATE_INT);
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS);

        $kyc = Database::fetch("SELECT * FROM kyc_verifications WHERE id = ?", [$kycId]);
        if (!$kyc) {
            Session::flash('error', 'KYC record not found.');
            Router::redirect('/admin/kyc');
            return;
        }

        if ($action === 'approve') {
            Database::update('kyc_verifications', [
                'status' => 'approved',
                'approved_at' => date('Y-m-d H:i:s')
            ], 'id = ?', [$kycId]);

            Database::update('users', [
                'kyc_verified' => true,
                'kyc_verified_at' => date('Y-m-d H:i:s')
            ], 'id = ?', [$kyc['user_id']]);

            AuditLog::log('approve_kyc', 'kyc', $kycId, ['user_id' => $kyc['user_id']]);
            Session::flash('success', 'KYC approved successfully.');
        } else {
            $reason = filter_input(INPUT_POST, 'rejection_reason', FILTER_SANITIZE_SPECIAL_CHARS);
            Database::update('kyc_verifications', [
                'status' => 'rejected',
                'rejection_reason' => $reason
            ], 'id = ?', [$kycId]);

            AuditLog::log('reject_kyc', 'kyc', $kycId, ['reason' => $reason]);
            Session::flash('success', 'KYC rejected.');
        }

        Router::redirect('/admin/kyc');
    }
}

<?php

namespace App\Controllers;

use App\Services\Auth;
use App\Services\Database;
use App\Services\Router;
use App\Services\Session;
use App\Services\AuditLog;

class WalletController
{
    public function index(): void
    {
        $user = Auth::user();
        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [$user['id']]);
        
        $deposits = Database::fetchAll(
            "SELECT * FROM deposits WHERE user_id = ? ORDER BY created_at DESC",
            [$user['id']]
        );

        $withdrawals = Database::fetchAll(
            "SELECT * FROM withdrawals WHERE user_id = ? ORDER BY created_at DESC",
            [$user['id']]
        );

        echo Router::render('user/wallet', [
            'user' => $user,
            'wallet' => $wallet,
            'deposits' => $deposits,
            'withdrawals' => $withdrawals,
            'csrf_token' => Session::generateCsrfToken(),
            'error' => Session::getFlash('error'),
            'success' => Session::getFlash('success'),
        ]);
    }

    public function showDeposit(): void
    {
        $allSettings = Database::fetchAll("SELECT * FROM settings");
        $settings = [];
        foreach ($allSettings as $setting) {
            if (isset($setting['setting_key']) && isset($setting['value'])) {
                $settings[$setting['setting_key']] = $setting['value'];
            }
        }
        
        $networks = Database::fetchAll("SELECT * FROM deposit_networks ORDER BY name ASC");
        
        echo Router::render('user/deposit', [
            'settings' => $settings,
            'networks' => $networks,
            'csrf_token' => Session::generateCsrfToken(),
            'error' => Session::getFlash('error'),
            'success' => Session::getFlash('success'),
        ]);
    }

    public function deposit(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/wallet/deposit');
            return;
        }

        $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
        $txid = filter_input(INPUT_POST, 'txid', FILTER_SANITIZE_SPECIAL_CHARS);
        $userId = Auth::id();

        if (!$amount || $amount <= 0) {
            Session::flash('error', 'Please enter a valid amount.');
            Router::redirect('/wallet/deposit');
            return;
        }

        if (empty($txid)) {
            Session::flash('error', 'Please enter the transaction ID.');
            Router::redirect('/wallet/deposit');
            return;
        }

        $existingTxid = Database::fetch(
            "SELECT id FROM deposits WHERE txid = ?",
            [$txid]
        );

        if ($existingTxid) {
            Session::flash('error', 'This transaction ID has already been used.');
            Router::redirect('/wallet/deposit');
            return;
        }

        $proofPath = null;
        if (isset($_FILES['proof']) && $_FILES['proof']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $maxSize = 5 * 1024 * 1024;
            
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($_FILES['proof']['tmp_name']);
            $ext = strtolower(pathinfo($_FILES['proof']['name'], PATHINFO_EXTENSION));
            
            if (!in_array($mimeType, $allowedTypes) || !in_array($ext, $allowedExtensions)) {
                Session::flash('error', 'Invalid file type. Only JPG, PNG, GIF, and WebP images are allowed.');
                Router::redirect('/wallet/deposit');
                return;
            }
            
            if ($_FILES['proof']['size'] > $maxSize) {
                Session::flash('error', 'File too large. Maximum size is 5MB.');
                Router::redirect('/wallet/deposit');
                return;
            }
            
            $uploadDir = 'uploads/proofs/';
            $fullUploadDir = ROOT_PATH . '/public/' . $uploadDir;
            if (!is_dir($fullUploadDir)) {
                mkdir($fullUploadDir, 0755, true);
            }
            
            $filename = bin2hex(random_bytes(16)) . '.' . $ext;
            $proofPath = '/' . $uploadDir . $filename;
            
            move_uploaded_file($_FILES['proof']['tmp_name'], $fullUploadDir . $filename);
        }

        Database::insert('deposits', [
            'user_id' => $userId,
            'amount' => $amount,
            'txid' => $txid,
            'proof_image' => $proofPath,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        AuditLog::log('deposit_request', 'deposit', null, [
            'amount' => $amount,
            'txid' => $txid,
        ]);

        Session::flash('success', 'Deposit request submitted successfully. Awaiting admin approval.');
        Router::redirect('/wallet');
    }

    public function showWithdraw(): void
    {
        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [Auth::id()]);
        
        echo Router::render('user/withdraw', [
            'wallet' => $wallet,
            'csrf_token' => Session::generateCsrfToken(),
            'error' => Session::getFlash('error'),
            'success' => Session::getFlash('success'),
        ]);
    }

    public function withdraw(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Session::flash('error', 'Invalid request.');
            Router::redirect('/wallet/withdraw');
            return;
        }

        $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_SPECIAL_CHARS);
        $userId = Auth::id();

        if (!$amount || $amount <= 0) {
            Session::flash('error', 'Please enter a valid amount.');
            Router::redirect('/wallet/withdraw');
            return;
        }

        if (empty($address)) {
            Session::flash('error', 'Please enter your wallet address.');
            Router::redirect('/wallet/withdraw');
            return;
        }

        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [$userId]);
        $availableBalance = $wallet['balance'] - $wallet['margin_used'];

        if ($amount > $availableBalance) {
            Session::flash('error', 'Insufficient available balance.');
            Router::redirect('/wallet/withdraw');
            return;
        }

        $lastWithdrawal = Database::fetch(
            "SELECT created_at FROM withdrawals WHERE user_id = ? ORDER BY created_at DESC LIMIT 1",
            [$userId]
        );

        if ($lastWithdrawal) {
            $cooldown = strtotime($lastWithdrawal['created_at']) + 3600;
            if (time() < $cooldown) {
                Session::flash('error', 'Please wait 1 hour between withdrawal requests.');
                Router::redirect('/wallet/withdraw');
                return;
            }
        }

        Database::insert('withdrawals', [
            'user_id' => $userId,
            'amount' => $amount,
            'wallet_address' => $address,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        AuditLog::log('withdrawal_request', 'withdrawal', null, [
            'amount' => $amount,
            'address' => $address,
        ]);

        Session::flash('success', 'Withdrawal request submitted. Awaiting admin approval.');
        Router::redirect('/wallet');
    }
}

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
        $tab = $_GET['tab'] ?? 'real';
        $sort = $_GET['sort'] ?? 'newest';

        $orderBy = $sort === 'oldest' ? 'ASC' : 'DESC';

        if ($tab === 'archived') {
            $accounts = Database::fetchAll(
                "SELECT * FROM trading_accounts WHERE user_id = ? AND status = 'archived' ORDER BY created_at {$orderBy}",
                [$user['id']]
            );
        } else {
            $accountType = $tab === 'demo' ? 'demo' : 'real';
            $accounts = Database::fetchAll(
                "SELECT * FROM trading_accounts WHERE user_id = ? AND account_type = ? AND status = 'active' ORDER BY created_at {$orderBy}",
                [$user['id'], $accountType]
            );
        }

        echo Router::render('user/accounts', [
            'user' => $user,
            'accounts' => $accounts,
            'tab' => $tab,
            'sort' => $sort,
            'csrf_token' => Session::generateCsrfToken(),
            'error' => Session::getFlash('error'),
            'success' => Session::getFlash('success'),
        ]);
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

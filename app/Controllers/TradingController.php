<?php

namespace App\Controllers;

use App\Services\Auth;
use App\Services\Database;
use App\Services\Router;
use App\Services\Session;
use App\Services\AuditLog;

class TradingController
{
    public function markets(): void
    {
        $markets = Database::fetchAll(
            "SELECT m.*, p.price, p.change_24h 
             FROM markets m 
             LEFT JOIN prices p ON m.id = p.market_id 
             WHERE m.status = 'active'
             ORDER BY m.type, m.symbol"
        );

        echo Router::render('trading/markets', [
            'markets' => $markets,
            'csrf_token' => Session::generateCsrfToken(),
        ]);
    }

    public function trade(string $symbol): void
    {
        $market = Database::fetch(
            "SELECT m.*, p.price, p.change_24h, p.high_24h, p.low_24h, p.volume_24h
             FROM markets m 
             LEFT JOIN prices p ON m.id = p.market_id 
             WHERE m.symbol = ? AND m.status = 'active'",
            [$symbol]
        );

        if (!$market) {
            Router::redirect('/markets');
            return;
        }

        $user = Auth::user();
        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [$user['id']]);

        $allMarkets = Database::fetchAll(
            "SELECT m.*, p.price FROM markets m LEFT JOIN prices p ON m.id = p.market_id WHERE m.status = 'active' ORDER BY m.symbol"
        );

        $openPositions = Database::fetchAll(
            "SELECT p.*, m.symbol FROM positions p JOIN markets m ON p.market_id = m.id WHERE p.user_id = ? AND p.status = 'open' ORDER BY p.created_at DESC",
            [$user['id']]
        );

        $closedPositions = Database::fetchAll(
            "SELECT p.*, m.symbol FROM positions p JOIN markets m ON p.market_id = m.id WHERE p.user_id = ? AND p.status = 'closed' ORDER BY p.closed_at DESC LIMIT 50",
            [$user['id']]
        );

        $pendingOrders = Database::fetchAll(
            "SELECT * FROM orders WHERE user_id = ? AND market_id = ? AND status = 'pending'",
            [$user['id'], $market['id']]
        );

        $priceHistory = Database::fetchAll(
            "SELECT price, created_at FROM prices_history 
             WHERE market_id = ? 
             ORDER BY created_at DESC LIMIT 100",
            [$market['id']]
        );

        echo Router::render('trading/trade', [
            'user' => $user,
            'market' => $market,
            'wallet' => $wallet,
            'allMarkets' => $allMarkets,
            'openPositions' => $openPositions,
            'closedPositions' => $closedPositions,
            'pendingOrders' => $pendingOrders,
            'priceHistory' => array_reverse($priceHistory),
            'csrf_token' => Session::generateCsrfToken(),
            'error' => Session::getFlash('error'),
            'success' => Session::getFlash('success'),
        ]);
    }

    public function placeOrder(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Router::json(['error' => 'Invalid request'], 403);
            return;
        }

        $userId = Auth::id();
        $marketId = filter_input(INPUT_POST, 'market_id', FILTER_VALIDATE_INT);
        $orderType = filter_input(INPUT_POST, 'order_type', FILTER_SANITIZE_SPECIAL_CHARS);
        $side = filter_input(INPUT_POST, 'side', FILTER_SANITIZE_SPECIAL_CHARS);
        $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_FLOAT);
        $leverage = filter_input(INPUT_POST, 'leverage', FILTER_VALIDATE_INT) ?: 1;
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);
        $stopLoss = filter_input(INPUT_POST, 'stop_loss', FILTER_VALIDATE_FLOAT);
        $takeProfit = filter_input(INPUT_POST, 'take_profit', FILTER_VALIDATE_FLOAT);

        if (!$marketId || !$orderType || !$side || !$amount) {
            Session::flash('error', 'Please fill in all required fields.');
            Router::redirect($_SERVER['HTTP_REFERER'] ?? '/markets');
            return;
        }

        $market = Database::fetch("SELECT * FROM markets WHERE id = ? AND status = 'active'", [$marketId]);
        if (!$market) {
            Session::flash('error', 'Invalid market.');
            Router::redirect('/markets');
            return;
        }

        if ($leverage > $market['max_leverage']) {
            Session::flash('error', "Maximum leverage for this market is {$market['max_leverage']}x.");
            Router::redirect($_SERVER['HTTP_REFERER'] ?? '/markets');
            return;
        }

        if ($amount < $market['min_trade_size']) {
            Session::flash('error', "Minimum trade size is {$market['min_trade_size']}.");
            Router::redirect($_SERVER['HTTP_REFERER'] ?? '/markets');
            return;
        }

        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [$userId]);
        $currentPrice = Database::fetch("SELECT price FROM prices WHERE market_id = ?", [$marketId]);
        $entryPrice = $currentPrice['price'] ?? 0;

        if ($orderType === 'limit' && $price) {
            $entryPrice = $price;
        }

        $marginRequired = ($amount * $entryPrice) / $leverage;
        $availableBalance = $wallet['balance'] - $wallet['margin_used'];

        if ($marginRequired > $availableBalance) {
            Session::flash('error', 'Insufficient margin. Required: $' . number_format($marginRequired, 2));
            Router::redirect($_SERVER['HTTP_REFERER'] ?? '/markets');
            return;
        }

        if ($orderType === 'market') {
            $spread = $market['spread'] ?? 0;
            $executionPrice = $side === 'buy' ? $entryPrice * (1 + $spread) : $entryPrice * (1 - $spread);

            $positionId = Database::insert('positions', [
                'user_id' => $userId,
                'market_id' => $marketId,
                'side' => $side,
                'amount' => $amount,
                'entry_price' => $executionPrice,
                'leverage' => $leverage,
                'margin_used' => $marginRequired,
                'stop_loss' => $stopLoss ?: null,
                'take_profit' => $takeProfit ?: null,
                'status' => 'open',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            Database::update('wallets', [
                'margin_used' => $wallet['margin_used'] + $marginRequired,
            ], 'user_id = ?', [$userId]);

            Database::insert('trades', [
                'user_id' => $userId,
                'position_id' => $positionId,
                'market_id' => $marketId,
                'side' => $side,
                'amount' => $amount,
                'price' => $executionPrice,
                'fee' => $amount * $executionPrice * ($market['fee'] ?? 0.001),
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            AuditLog::log('open_position', 'position', $positionId, [
                'market' => $market['symbol'],
                'side' => $side,
                'amount' => $amount,
                'price' => $executionPrice,
                'leverage' => $leverage,
            ]);

            Session::flash('success', 'Position opened successfully.');
        } else {
            Database::insert('orders', [
                'user_id' => $userId,
                'market_id' => $marketId,
                'type' => $orderType,
                'side' => $side,
                'amount' => $amount,
                'price' => $price,
                'leverage' => $leverage,
                'stop_loss' => $stopLoss ?: null,
                'take_profit' => $takeProfit ?: null,
                'margin_reserved' => $marginRequired,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
            ]);

            Database::update('wallets', [
                'margin_used' => $wallet['margin_used'] + $marginRequired,
            ], 'user_id = ?', [$userId]);

            AuditLog::log('place_order', 'order', null, [
                'market' => $market['symbol'],
                'type' => $orderType,
                'side' => $side,
                'amount' => $amount,
                'price' => $price,
            ]);

            Session::flash('success', 'Order placed successfully.');
        }

        Router::redirect("/trade/{$market['symbol']}");
    }

    public function closePosition(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Router::json(['error' => 'Invalid request'], 403);
            return;
        }

        $userId = Auth::id();
        $positionId = filter_input(INPUT_POST, 'position_id', FILTER_VALIDATE_INT);

        $position = Database::fetch(
            "SELECT p.*, m.symbol, m.spread FROM positions p 
             JOIN markets m ON p.market_id = m.id 
             WHERE p.id = ? AND p.user_id = ? AND p.status = 'open'",
            [$positionId, $userId]
        );

        if (!$position) {
            Session::flash('error', 'Position not found.');
            Router::redirect('/dashboard');
            return;
        }

        $currentPrice = Database::fetch("SELECT price FROM prices WHERE market_id = ?", [$position['market_id']]);
        $exitPrice = $currentPrice['price'] ?? $position['entry_price'];

        $spread = $position['spread'] ?? 0;
        $exitPrice = $position['side'] === 'buy' 
            ? $exitPrice * (1 - $spread) 
            : $exitPrice * (1 + $spread);

        if ($position['side'] === 'buy') {
            $pnl = ($exitPrice - $position['entry_price']) * $position['amount'];
        } else {
            $pnl = ($position['entry_price'] - $exitPrice) * $position['amount'];
        }

        Database::update('positions', [
            'exit_price' => $exitPrice,
            'realized_pnl' => $pnl,
            'status' => 'closed',
            'closed_at' => date('Y-m-d H:i:s'),
        ], 'id = ?', [$positionId]);

        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [$userId]);
        Database::update('wallets', [
            'balance' => $wallet['balance'] + $pnl,
            'margin_used' => $wallet['margin_used'] - $position['margin_used'],
        ], 'user_id = ?', [$userId]);

        Database::insert('trades', [
            'user_id' => $userId,
            'position_id' => $positionId,
            'market_id' => $position['market_id'],
            'side' => $position['side'] === 'buy' ? 'sell' : 'buy',
            'amount' => $position['amount'],
            'price' => $exitPrice,
            'fee' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        AuditLog::log('close_position', 'position', $positionId, [
            'market' => $position['symbol'],
            'pnl' => $pnl,
            'exit_price' => $exitPrice,
        ]);

        Session::flash('success', 'Position closed. PnL: $' . number_format($pnl, 2));
        Router::redirect("/trade/{$position['symbol']}");
    }

    public function cancelOrder(): void
    {
        if (!Session::validateCsrfToken($_POST['_csrf_token'] ?? '')) {
            Router::json(['error' => 'Invalid request'], 403);
            return;
        }

        $userId = Auth::id();
        $orderId = filter_input(INPUT_POST, 'order_id', FILTER_VALIDATE_INT);

        $order = Database::fetch(
            "SELECT o.*, m.symbol FROM orders o 
             JOIN markets m ON o.market_id = m.id 
             WHERE o.id = ? AND o.user_id = ? AND o.status = 'pending'",
            [$orderId, $userId]
        );

        if (!$order) {
            Session::flash('error', 'Order not found.');
            Router::redirect('/dashboard');
            return;
        }

        Database::update('orders', ['status' => 'cancelled'], 'id = ?', [$orderId]);

        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [$userId]);
        Database::update('wallets', [
            'margin_used' => $wallet['margin_used'] - $order['margin_reserved'],
        ], 'user_id = ?', [$userId]);

        AuditLog::log('cancel_order', 'order', $orderId, ['market' => $order['symbol']]);

        Session::flash('success', 'Order cancelled successfully.');
        Router::redirect("/trade/{$order['symbol']}");
    }

    public function positions(): void
    {
        $userId = Auth::id();

        $openPositions = Database::fetchAll(
            "SELECT p.*, m.symbol, m.name as market_name 
             FROM positions p 
             JOIN markets m ON p.market_id = m.id 
             WHERE p.user_id = ? AND p.status = 'open'
             ORDER BY p.created_at DESC",
            [$userId]
        );

        $closedPositions = Database::fetchAll(
            "SELECT p.*, m.symbol, m.name as market_name 
             FROM positions p 
             JOIN markets m ON p.market_id = m.id 
             WHERE p.user_id = ? AND p.status = 'closed'
             ORDER BY p.closed_at DESC LIMIT 50",
            [$userId]
        );

        echo Router::render('trading/positions', [
            'openPositions' => $openPositions,
            'closedPositions' => $closedPositions,
            'csrf_token' => Session::generateCsrfToken(),
        ]);
    }

    public function orders(): void
    {
        $userId = Auth::id();

        $pendingOrders = Database::fetchAll(
            "SELECT o.*, m.symbol, m.name as market_name 
             FROM orders o 
             JOIN markets m ON o.market_id = m.id 
             WHERE o.user_id = ? AND o.status = 'pending'
             ORDER BY o.created_at DESC",
            [$userId]
        );

        $orderHistory = Database::fetchAll(
            "SELECT o.*, m.symbol, m.name as market_name 
             FROM orders o 
             JOIN markets m ON o.market_id = m.id 
             WHERE o.user_id = ? AND o.status != 'pending'
             ORDER BY o.created_at DESC LIMIT 50",
            [$userId]
        );

        echo Router::render('trading/orders', [
            'pendingOrders' => $pendingOrders,
            'orderHistory' => $orderHistory,
            'csrf_token' => Session::generateCsrfToken(),
        ]);
    }
}

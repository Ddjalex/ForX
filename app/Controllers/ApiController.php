<?php

namespace App\Controllers;

use App\Services\Auth;
use App\Services\Database;
use App\Services\Router;

class ApiController
{
    public function prices(): void
    {
        $prices = Database::fetchAll(
            "SELECT m.id, m.symbol, m.name, m.type, p.price, p.change_24h, p.high_24h, p.low_24h, p.volume_24h 
             FROM markets m 
             LEFT JOIN prices p ON m.id = p.market_id 
             WHERE m.status = 'active'"
        );

        Router::json(['success' => true, 'data' => $prices]);
    }

    public function marketPrice(string $symbol): void
    {
        $price = Database::fetch(
            "SELECT m.*, p.price, p.change_24h, p.high_24h, p.low_24h, p.volume_24h 
             FROM markets m 
             LEFT JOIN prices p ON m.id = p.market_id 
             WHERE m.symbol = ?",
            [$symbol]
        );

        if (!$price) {
            Router::json(['success' => false, 'error' => 'Market not found'], 404);
            return;
        }

        Router::json(['success' => true, 'data' => $price]);
    }

    public function priceHistory(string $symbol): void
    {
        $market = Database::fetch("SELECT id FROM markets WHERE symbol = ?", [$symbol]);
        
        if (!$market) {
            Router::json(['success' => false, 'error' => 'Market not found'], 404);
            return;
        }

        $history = Database::fetchAll(
            "SELECT price, created_at FROM prices_history 
             WHERE market_id = ? 
             ORDER BY created_at DESC LIMIT 100",
            [$market['id']]
        );

        Router::json(['success' => true, 'data' => array_reverse($history)]);
    }

    public function userPositions(): void
    {
        if (!Auth::check()) {
            Router::json(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }

        $positions = Database::fetchAll(
            "SELECT p.*, m.symbol, m.name as market_name 
             FROM positions p 
             JOIN markets m ON p.market_id = m.id 
             WHERE p.user_id = ? AND p.status = 'open'",
            [Auth::id()]
        );

        foreach ($positions as &$position) {
            $currentPrice = Database::fetch("SELECT price FROM prices WHERE market_id = ?", [$position['market_id']]);
            $price = $currentPrice['price'] ?? $position['entry_price'];
            
            if ($position['side'] === 'buy') {
                $position['unrealized_pnl'] = ($price - $position['entry_price']) * $position['amount'];
            } else {
                $position['unrealized_pnl'] = ($position['entry_price'] - $price) * $position['amount'];
            }
            $position['current_price'] = $price;
        }

        Router::json(['success' => true, 'data' => $positions]);
    }

    public function walletBalance(): void
    {
        if (!Auth::check()) {
            Router::json(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }

        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [Auth::id()]);

        Router::json(['success' => true, 'data' => [
            'balance' => (float) $wallet['balance'],
            'margin_used' => (float) $wallet['margin_used'],
            'available' => (float) ($wallet['balance'] - $wallet['margin_used']),
        ]]);
    }

    public function executeTrade(): void
    {
        if (!Auth::check()) {
            Router::json(['success' => false, 'message' => 'Please log in to trade'], 401);
            return;
        }

        $userId = Auth::id();
        $assetType = $_POST['asset_type'] ?? 'crypto';
        $assetName = $_POST['asset_name'] ?? '';
        $side = $_POST['side'] ?? '';
        $amount = (float) ($_POST['amount'] ?? 0);
        $leverage = (int) ($_POST['leverage'] ?? 5);
        $duration = (int) ($_POST['duration'] ?? 1);
        $takeProfit = (float) ($_POST['take_profit'] ?? 0);
        $stopLoss = (float) ($_POST['stop_loss'] ?? 0);

        if (empty($assetName) || empty($side) || $amount <= 0) {
            Router::json(['success' => false, 'message' => 'Invalid trade parameters']);
            return;
        }

        if (!in_array($side, ['buy', 'sell'])) {
            Router::json(['success' => false, 'message' => 'Invalid trade side']);
            return;
        }

        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [$userId]);
        if (!$wallet) {
            Database::insert('wallets', [
                'user_id' => $userId,
                'balance' => 0,
                'margin_used' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $wallet = ['balance' => 0, 'margin_used' => 0];
        }

        $availableBalance = ($wallet['balance'] ?? 0) - ($wallet['margin_used'] ?? 0);
        $marginRequired = $amount / $leverage;

        if ($marginRequired > $availableBalance) {
            Router::json(['success' => false, 'message' => 'Insufficient balance. Required margin: $' . number_format($marginRequired, 2)]);
            return;
        }

        $market = Database::fetch("SELECT * FROM markets WHERE symbol = ?", [$assetName]);
        if (!$market) {
            Database::insert('markets', [
                'symbol' => $assetName,
                'name' => $assetName,
                'type' => $assetType,
                'status' => 'active',
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $market = Database::fetch("SELECT * FROM markets WHERE symbol = ?", [$assetName]);
        }

        $entryPrice = $this->getAssetPrice($assetName) ?? 0;
        if ($entryPrice <= 0) {
            $entryPrice = $this->getDefaultPrice($assetName);
        }

        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$duration} minutes"));

        $marginRequired = $amount / $leverage;
        
        Database::insert('positions', [
            'user_id' => $userId,
            'market_id' => $market['id'],
            'side' => $side,
            'amount' => $amount,
            'leverage' => $leverage,
            'entry_price' => $entryPrice,
            'margin_used' => $marginRequired,
            'take_profit' => $takeProfit > 0 ? $takeProfit : null,
            'stop_loss' => $stopLoss > 0 ? $stopLoss : null,
            'duration' => $duration,
            'expires_at' => $expiresAt,
            'unrealized_pnl' => 0,
            'realized_pnl' => 0,
            'status' => 'open',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        Database::update('wallets', [
            'margin_used' => ($wallet['margin_used'] ?? 0) + $marginRequired,
            'updated_at' => date('Y-m-d H:i:s')
        ], 'user_id = ?', [$userId]);

        Router::json([
            'success' => true,
            'message' => ucfirst($side) . ' order executed successfully',
            'data' => [
                'asset' => $assetName,
                'side' => $side,
                'amount' => $amount,
                'leverage' => $leverage,
                'entry_price' => $entryPrice,
                'expires_at' => $expiresAt
            ]
        ]);
    }

    private function getAssetPrice(string $symbol): ?float
    {
        $market = Database::fetch("SELECT id FROM markets WHERE symbol = ?", [$symbol]);
        if ($market) {
            $price = Database::fetch("SELECT price FROM prices WHERE market_id = ?", [$market['id']]);
            if ($price) {
                return (float) $price['price'];
            }
        }
        return null;
    }

    private function getDefaultPrice(string $symbol): float
    {
        $defaultPrices = [
            'BTCUSD' => 43500.00,
            'ETHUSD' => 2280.00,
            'XRPUSD' => 0.62,
            'SOLUSD' => 98.50,
            'BNBUSD' => 315.00,
            'EURUSD' => 1.0875,
            'GBPUSD' => 1.2650,
            'USDJPY' => 149.50,
            'AUDUSD' => 0.6580,
            'AAPL' => 195.00,
            'GOOGL' => 140.00,
            'AMZN' => 155.00,
            'TSLA' => 248.00,
            'MSFT' => 378.00,
            'XAUUSD' => 2025.00,
            'XAGUSD' => 24.50,
            'USOIL' => 72.50
        ];
        return $defaultPrices[$symbol] ?? 100.00;
    }
}

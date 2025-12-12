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
}

<?php

namespace App\Controllers;

use App\Services\Auth;
use App\Services\Database;
use App\Services\Router;
use App\Services\Session;
use App\Services\AssetService;

class DashboardController
{
    public function index(): void
    {
        $user = Auth::user();
        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [$user['id']]);
        
        $openPositions = Database::fetchAll(
            "SELECT p.*, m.symbol, m.name as market_name 
             FROM positions p 
             JOIN markets m ON p.market_id = m.id 
             WHERE p.user_id = ? AND p.status = 'open' 
             ORDER BY p.created_at DESC",
            [$user['id']]
        );

        $closedPositions = Database::fetchAll(
            "SELECT p.*, m.symbol, m.name as market_name 
             FROM positions p 
             JOIN markets m ON p.market_id = m.id 
             WHERE p.user_id = ? AND p.status = 'closed' 
             ORDER BY p.created_at DESC",
            [$user['id']]
        );

        $recentTransactions = Database::fetchAll(
            "SELECT 'deposit' as type, amount, status, created_at FROM deposits WHERE user_id = ?
             UNION ALL
             SELECT 'withdrawal' as type, amount, status, created_at FROM withdrawals WHERE user_id = ?
             ORDER BY created_at DESC LIMIT 10",
            [$user['id'], $user['id']]
        );

        $totalPnL = array_sum(array_column($openPositions, 'unrealized_pnl'));
        
        $totalTrades = count($openPositions) + count($closedPositions);
        $openTrades = count($openPositions);
        $closedTrades = count($closedPositions);
        
        $wins = array_filter($closedPositions, function($p) {
            return ($p['pnl'] ?? 0) > 0;
        });
        $winCount = count($wins);
        $lossCount = $closedTrades - $winCount;
        $winLossRatio = $lossCount > 0 ? round($winCount / $lossCount, 1) : $winCount;

        $profit = array_sum(array_column($closedPositions, 'pnl'));
        if (!isset($wallet['profit'])) {
            $wallet['profit'] = $profit;
        }
        if (!isset($wallet['bonus'])) {
            $wallet['bonus'] = 0;
        }

        $stats = [
            'total_trades' => $totalTrades,
            'open_trades' => $openTrades,
            'closed_trades' => $closedTrades,
            'win_loss_ratio' => $winLossRatio,
        ];

        $cryptoMarkets = Database::fetchAll(
            "SELECT m.*, p.price, p.change_24h, p.high_24h, p.low_24h, p.volume_24h 
             FROM markets m 
             LEFT JOIN prices p ON m.id = p.market_id 
             WHERE m.type = 'crypto' AND m.status = 'active'
             ORDER BY p.volume_24h DESC"
        );

        $stockMarkets = Database::fetchAll(
            "SELECT m.*, p.price, p.change_24h, p.high_24h, p.low_24h, p.volume_24h 
             FROM markets m 
             LEFT JOIN prices p ON m.id = p.market_id 
             WHERE m.type IN ('forex', 'stock', 'indices') AND m.status = 'active'
             ORDER BY m.symbol"
        );

        $assetService = new AssetService();
        $assetTypes = $assetService->getAssetTypes();
        
        $allMarkets = Database::fetchAll(
            "SELECT m.*, m.display_name, m.symbol_tradingview, p.price, at.name as asset_type 
             FROM markets m 
             LEFT JOIN prices p ON m.id = p.market_id 
             LEFT JOIN asset_types at ON m.asset_type_id = at.id
             WHERE m.status = 'active' 
             ORDER BY at.sort_order, m.name"
        );

        echo Router::render('user/dashboard', [
            'user' => $user,
            'wallet' => $wallet,
            'openPositions' => $openPositions,
            'closedPositions' => $closedPositions,
            'recentTransactions' => $recentTransactions,
            'totalPnL' => $totalPnL,
            'stats' => $stats,
            'cryptoMarkets' => $cryptoMarkets,
            'stockMarkets' => $stockMarkets,
            'assetTypes' => $assetTypes,
            'allMarkets' => $allMarkets,
            'csrf_token' => Session::generateCsrfToken(),
        ]);
    }
}

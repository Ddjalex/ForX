<?php
/**
 * SYNC FIX FOR TRADE CONTROLLER
 * 
 * This is the updated history() method that syncs asset type, asset name, and price with user balance.
 * Replace the history() method in app/Controllers/TradingController.php with this code.
 */

namespace App\Controllers;

use App\Services\Auth;
use App\Services\Database;

class TradingController
{
    public function history(): void
    {
        $userId = Auth::id();

        // Fixed query: Uses LEFT JOIN to show all trades even if market is deleted
        // Includes asset_type, asset name (display_name), and current price synced with balance
        $positions = Database::fetchAll(
            "SELECT p.*, 
                    COALESCE(m.symbol, 'DELETED') as symbol, 
                    COALESCE(m.name, 'Deleted Market') as market_name,
                    COALESCE(m.display_name, m.name) as display_name,
                    COALESCE(at.name, 'Unknown') as asset_type,
                    COALESCE(pr.price, 0) as current_price,
                    COALESCE(pr.change_24h, 0) as change_24h
             FROM positions p 
             LEFT JOIN markets m ON p.market_id = m.id 
             LEFT JOIN asset_types at ON m.asset_type_id = at.id
             LEFT JOIN prices pr ON m.id = pr.market_id
             WHERE p.user_id = ?
             ORDER BY p.created_at DESC LIMIT 500",
            [$userId]
        );

        // Also fetch current wallet balance to show synced with trade history
        $wallet = Database::fetch("SELECT * FROM wallets WHERE user_id = ?", [$userId]);

        echo Router::render('trading/history', [
            'positions' => $positions,
            'wallet' => $wallet,  // Pass wallet for sync display
            'csrf_token' => Session::generateCsrfToken(),
            'success' => Session::getFlash('success'),
            'error' => Session::getFlash('error'),
        ]);
    }
}
?>

/**
 * KEY CHANGES:
 * 
 * 1. SYNC ASSET TYPE: Added LEFT JOIN asset_types at ON m.asset_type_id = at.id
 *    - Returns asset_type (e.g., "Crypto", "Forex", "Stocks")
 * 
 * 2. SYNC ASSET NAME: Added COALESCE(m.display_name, m.name) as display_name
 *    - Returns proper display name for each asset
 * 
 * 3. SYNC PRICE: Added LEFT JOIN prices pr ON m.id = pr.market_id
 *    - Returns current_price synced with real-time market data
 *    - Returns change_24h for price change
 * 
 * 4. SYNC WITH BALANCE: Pass wallet data to view
 *    - User can see their balance alongside their trade history
 *    - All data is live-synced from database
 */

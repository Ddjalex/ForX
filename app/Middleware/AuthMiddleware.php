<?php

namespace App\Middleware;

use App\Services\Auth;
use App\Services\Database;
use App\Services\Router;

class AuthMiddleware
{
    public function handle(): bool
    {
        if (!Auth::check()) {
            Router::redirect('/login');
            return false;
        }

        $user = Auth::user();
        
        // Check if user has approved KYC verification
        $kycApproved = Database::fetch(
            "SELECT * FROM kyc_verifications WHERE user_id = ? AND status = 'approved'",
            [$user['id']]
        );
        
        // Allowed paths for unverified users
        $allowedForUnverified = [
            '/dashboard', 
            '/kyc/verify', 
            '/logout', 
            '/accounts', 
            '/api/notifications/unread', 
            '/api/notifications',
            '/api/positions/close-expired',
            '/api/positions/close'
        ];
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $currentPath = rtrim($currentPath, '/') ?: '/';
        
        // If user doesn't have approved KYC, restrict access to trading and advanced features
        if (!$kycApproved) {
            // These are trading/restricted routes that require KYC approval
            $restrictedPaths = [
                '/wallet',
                '/wallet/deposit',
                '/wallet/withdraw',
                '/markets',
                '/dashboard/trade',
                '/positions',
                '/orders',
                '/dashboard/trades/history',
                '/copy-experts',
                '/news',
                '/nfts',
                '/signals',
                '/loans',
                '/referrals',
                '/live-analysis'
            ];
            
            // Check if current path is restricted
            foreach ($restrictedPaths as $path) {
                if (strpos($currentPath, $path) === 0) {
                    Router::redirect('/kyc/verify');
                    return false;
                }
            }
            
            // Also check API routes that require KYC
            if (strpos($currentPath, '/api/') === 0 && !in_array($currentPath, $allowedForUnverified)) {
                Router::json(['error' => 'KYC verification required'], 403);
                return false;
            }
        }
        
        return true;
    }
}

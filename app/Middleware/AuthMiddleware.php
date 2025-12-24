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
        $isKycRequired = !in_array($_SERVER['REQUEST_URI'], ['/kyc/verify', '/logout', '/api/notifications/unread', '/api/notifications', '/accounts']);
        
        // Check if KYC verification is required and user is not verified
        if ($isKycRequired && !$user['email_verified']) {
            // Allow only to specific pages
            $allowedPaths = ['/dashboard', '/kyc/verify', '/logout', '/accounts'];
            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $currentPath = rtrim($currentPath, '/') ?: '/';
            
            if (!in_array($currentPath, $allowedPaths)) {
                Router::redirect('/kyc/verify');
                return false;
            }
        }
        
        return true;
    }
}

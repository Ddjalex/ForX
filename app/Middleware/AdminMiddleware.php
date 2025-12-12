<?php

namespace App\Middleware;

use App\Services\Auth;
use App\Services\Database;
use App\Services\Router;
use App\Services\Session;

class AdminMiddleware
{
    public function handle(): bool
    {
        if (!Auth::check()) {
            Router::redirect('/login');
            return false;
        }
        
        $user = Database::fetch(
            "SELECT role, status FROM users WHERE id = ?",
            [Session::get('user_id')]
        );
        
        if (!$user || $user['status'] !== 'active') {
            Auth::logout();
            Router::redirect('/login');
            return false;
        }
        
        $adminRoles = ['admin', 'superadmin', 'finance_admin', 'support_admin'];
        if (!in_array($user['role'], $adminRoles)) {
            Router::redirect('/dashboard');
            return false;
        }
        
        return true;
    }
}

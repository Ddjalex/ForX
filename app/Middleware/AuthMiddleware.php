<?php

namespace App\Middleware;

use App\Services\Auth;
use App\Services\Router;

class AuthMiddleware
{
    public function handle(): bool
    {
        if (!Auth::check()) {
            Router::redirect('/login');
            return false;
        }
        return true;
    }
}

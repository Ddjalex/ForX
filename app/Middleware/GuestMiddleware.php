<?php

namespace App\Middleware;

use App\Services\Auth;
use App\Services\Router;

class GuestMiddleware
{
    public function handle(): bool
    {
        if (Auth::check()) {
            Router::redirect('/dashboard');
            return false;
        }
        return true;
    }
}

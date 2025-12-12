<?php

namespace App\Middleware;

use App\Services\Session;
use App\Services\Router;

class CsrfMiddleware
{
    public function handle(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['_csrf_token'] ?? '';
            
            if (!Session::validateCsrfToken($token)) {
                http_response_code(403);
                echo 'CSRF token validation failed';
                return false;
            }
        }
        
        return true;
    }
}

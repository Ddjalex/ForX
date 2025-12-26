<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// app/, routes/, config/ are inside public_html
define('ROOT_PATH', __DIR__);

// Create logs directory
@mkdir(ROOT_PATH . '/storage/logs', 0755, true);
ini_set('error_log', ROOT_PATH . '/storage/logs/error.log');

// Custom error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $errorLog = ROOT_PATH . '/storage/logs/php-errors.log';
    $message = date('[Y-m-d H:i:s] ') . "[$errno] $errstr in $errfile:$errline\n";
    error_log($message, 3, $errorLog);
    return false;
});

// Log request information
$requestLog = ROOT_PATH . '/storage/logs/requests.log';
$requestData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'path' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
    'script_name' => $_SERVER['SCRIPT_NAME'],
];
error_log(json_encode($requestData) . "\n", 3, $requestLog);

// Load .env file
$envFile = ROOT_PATH . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        
        list($key, $value) = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        
        if (!getenv($key)) {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

/**
 * Simple PSR-4 style autoloader for App\*
 */
spl_autoload_register(function ($class) {
    $prefix  = 'App\\';
    $baseDir = ROOT_PATH . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    } else {
        error_log("Autoloader: Class not found: $file (Class: $class)\n", 3, ROOT_PATH . '/storage/logs/autoloader.log');
    }
});

use App\Services\Session;
use App\Services\Router;
use App\Services\Translation;

try {
    // Start session early
    Session::start();

    // Set language from request or cookie
    $lang = $_GET['lang'] ?? ($_COOKIE['language'] ?? 'en');
    Translation::setLanguage($lang);

    /**
     * Translation helper used in views: t('key')
     * This MATCHES your Translation.php implementation
     */
    if (!function_exists('t')) {
        function t(string $key, string $default = ''): string
        {
            return \App\Services\Translation::t($key, $default);
        }
    }

// Load routes and dispatch
require_once ROOT_PATH . '/routes/web.php';
\App\Services\Router::dispatch();
    
} catch (Throwable $e) {
    $errorMsg = "EXCEPTION: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\nTrace: " . $e->getTraceAsString() . "\n";
    error_log($errorMsg, 3, $requestLog);
    
    http_response_code(500);
    echo "<!-- Error logged. Check storage/logs/requests.log for details -->";
}
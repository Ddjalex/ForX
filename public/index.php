<?php

error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Create logs directory if it doesn't exist
$logsDir = dirname(__DIR__) . '/storage/logs';
if (!is_dir($logsDir)) {
    @mkdir($logsDir, 0755, true);
}

// Set error log location
ini_set('error_log', $logsDir . '/error.log');

// Custom error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $errorLog = dirname(__DIR__) . '/storage/logs/php-errors.log';
    $message = date('[Y-m-d H:i:s] ') . "[$errno] $errstr in $errfile:$errline\n";
    error_log($message, 3, $errorLog);
    return false;
});

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

define('ROOT_PATH', dirname(__DIR__));
define('DEBUG_MODE', getenv('APP_ENV') === 'development');

// Log request information
@mkdir(dirname(__DIR__) . '/storage/logs', 0755, true);
$requestLog = dirname(__DIR__) . '/storage/logs/requests.log';
$requestData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'path' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
    'script_name' => $_SERVER['SCRIPT_NAME'],
    'script_filename' => $_SERVER['SCRIPT_FILENAME'],
    'php_self' => $_SERVER['PHP_SELF'],
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

// PSR-4 Autoloader
spl_autoload_register(function ($class) use ($logsDir) {
    $prefix = 'App\\';
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
        $errorMsg = "Autoloader: Class file not found: $file (for class: $class)\n";
        error_log($errorMsg, 3, $logsDir . '/autoloader.log');
    }
});

use App\Services\Session;
use App\Services\Router;
use App\Services\Translation;

try {
    Session::start();

    // Set language from request or session (before layout loads)
    $lang = $_GET['lang'] ?? $_COOKIE['language'] ?? 'en';
    Translation::setLanguage($lang);

    // Global translation helper function
    if (!function_exists('t')) {
        function t($key, $default = '') {
            return Translation::t($key, $default);
        }
    }

    require ROOT_PATH . '/routes/web.php';

    Router::dispatch();
} catch (Throwable $e) {
    error_log("EXCEPTION: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine(), 3, $requestLog);
    error_log($e->getTraceAsString(), 3, $requestLog);
    
    if (DEBUG_MODE) {
        echo "<pre>";
        echo "ERROR: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        echo $e->getTraceAsString();
        echo "</pre>";
    } else {
        http_response_code(500);
        echo "Internal Server Error. Please contact support.";
    }
}

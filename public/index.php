<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

define('ROOT_PATH', dirname(__DIR__));

spl_autoload_register(function ($class) {
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
    }
});

use App\Services\Session;
use App\Services\Router;
use App\Services\Translation;

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

<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// app/, routes/, config/ are inside public_html
define('ROOT_PATH', __DIR__);

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
    }
});

use App\Services\Session;
use App\Services\Router;
use App\Services\Translation;

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
require ROOT_PATH . '/routes/web.php';
Router::dispatch();

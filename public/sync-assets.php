<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

use App\Services\AssetSyncService;

header('Content-Type: application/json');

if (php_sapi_name() !== 'cli') {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Admin access required']);
        exit;
    }
}

try {
    $syncService = new AssetSyncService();
    
    $syncService->clearOldAssets();
    
    $results = $syncService->syncAllAssets();
    
    echo json_encode([
        'success' => true,
        'message' => 'Assets synced successfully',
        'synced' => [
            'crypto' => count($results['crypto']),
            'forex' => count($results['forex']),
            'stock' => count($results['stock'])
        ],
        'details' => $results
    ], JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

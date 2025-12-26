<?php

namespace App\Services;

class Router
{
    private static array $routes = [];
    private static array $middleware = [];
    private static bool $debug = false;

    public static function get(string $path, $handler, array $middleware = []): void
    {
        self::addRoute('GET', $path, $handler, $middleware);
    }

    public static function post(string $path, $handler, array $middleware = []): void
    {
        self::addRoute('POST', $path, $handler, $middleware);
    }

    private static function addRoute(string $method, string $path, $handler, array $middleware): void
    {
        self::$routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $middleware,
        ];
    }

    public static function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $rawUri = $_SERVER['REQUEST_URI'];
        
        // Clean up URI - remove query string and trailing slash
        $uri = parse_url($rawUri, PHP_URL_PATH);
        $uri = rtrim($uri, '/') ?: '/';
        
        // Remove known deployment prefixes
        $prefixes = ['/public_html', '/public'];
        foreach ($prefixes as $prefix) {
            $prefix = rtrim($prefix, '/');
            if (!empty($prefix) && strpos($uri, $prefix) === 0) {
                $uri = substr($uri, strlen($prefix));
                $uri = '/' . ltrim($uri, '/');
                $uri = rtrim($uri, '/') ?: '/';
            }
        }
        
        // Log dispatch attempt
        self::logDispatch($method, $rawUri, $uri);
        
        // Try to match routes
        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = self::convertToRegex($route['path']);
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                
                try {
                    // Execute middleware
                    foreach ($route['middleware'] as $middlewareClass) {
                        $middlewareInstance = new $middlewareClass();
                        if (!$middlewareInstance->handle()) {
                            return;
                        }
                    }

                    // Execute handler
                    $handler = $route['handler'];
                    
                    if (is_array($handler)) {
                        [$class, $method] = $handler;
                        $controller = new $class();
                        call_user_func_array([$controller, $method], $matches);
                    } elseif (is_callable($handler)) {
                        call_user_func_array($handler, $matches);
                    }
                    
                    return;
                } catch (Throwable $e) {
                    self::logError("Route handler error: " . $e->getMessage());
                    http_response_code(500);
                    echo self::render('errors/500', ['error' => $e->getMessage()]);
                    return;
                }
            }
        }

        // No route matched - log available routes and return 404
        self::logNotFound($method, $uri);
        http_response_code(404);
        echo self::render('errors/404');
    }

    private static function convertToRegex(string $path): string
    {
        $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    public static function render(string $view, array $data = []): string
    {
        extract($data);
        
        ob_start();
        $viewPath = __DIR__ . '/../Views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            try {
                include $viewPath;
            } catch (\Throwable $e) {
                error_log("View rendering error in {$view}: " . $e->getMessage());
                echo "<!-- View Error: " . htmlspecialchars($e->getMessage()) . " -->";
            }
        } else {
            error_log("View not found: {$viewPath}");
            echo "<!-- View not found: {$view} -->";
        }
        
        return ob_get_clean();
    }

    public static function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }

    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private static function logDispatch(string $method, string $rawUri, string $cleanUri): void
    {
        $logDir = dirname(dirname(__DIR__)) . '/storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        
        $message = date('[Y-m-d H:i:s] ') . "Dispatch: $method $rawUri (cleaned: $cleanUri)\n";
        error_log($message, 3, $logDir . '/routing.log');
    }

    private static function logNotFound(string $method, string $uri): void
    {
        $logDir = dirname(dirname(__DIR__)) . '/storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        
        $message = date('[Y-m-d H:i:s] ') . "404 Not Found: $method $uri\n";
        error_log($message, 3, $logDir . '/routing.log');
    }

    private static function logError(string $message): void
    {
        $logDir = dirname(dirname(__DIR__)) . '/storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0755, true);
        }
        
        error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, $logDir . '/routing.log');
    }

    private static function getAvailableRoutes(string $method): array
    {
        $available = [];
        foreach (self::$routes as $route) {
            if ($route['method'] === $method) {
                $available[] = $route['path'];
            }
        }
        return array_unique($available);
    }
}

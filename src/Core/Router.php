<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function add($method, $uri, $controller, $action, $middleware = [])
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => "App\\Controllers\\{$controller}",
            'action' => $action,
            'middleware' => $middleware
        ];
    }

    public function get($uri, $controller, $action, $middleware = [])
    {
        $this->add('GET', $uri, $controller, $action, $middleware);
    }

    public function post($uri, $controller, $action, $middleware = [])
    {
        $this->add('POST', $uri, $controller, $action, $middleware);
    }

    public function dispatch($uri, $method)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        if (strpos($uri, BASE_URL) === 0) {
            $uri = substr($uri, strlen(BASE_URL));
        }
        $uri = '/' . ltrim($uri, '/');

        foreach ($this->routes as $route) {
            if ($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                // Check middleware
                foreach ($route['middleware'] as $middleware) {
                    $middlewareClass = "App\\Middleware\\{$middleware}";
                    (new $middlewareClass)->handle();
                }

                $controller = $route['controller'];
                $action = $route['action'];

                if (class_exists($controller)) {
                    $controllerInstance = new $controller();
                    if (method_exists($controllerInstance, $action)) {
                        return $controllerInstance->$action();
                    } else {
                        die("Method {$action} not found in {$controller}");
                    }
                } else {
                    die("Controller {$controller} not found");
                }
            }
        }

        // 404 Route Not Found
        http_response_code(404);
        require __DIR__ . '/../../views/errors/404.php';
    }
}

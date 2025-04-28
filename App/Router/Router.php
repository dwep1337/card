<?php
namespace App\Router;

class Router
{
    private array $routes = [];

    public function add($method, $route, $action): void
    {
        $this->routes[] = compact('method', 'route', 'action');
    }

    public function dispatch($requestUri, $requestMethod): void
    {
        $uri = parse_url($requestUri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['route'] === $uri && $route['method'] === $requestMethod) {
                [$controller, $method] = explode('@', $route['action']);
                $controller = "App\\Controller\\$controller";

                if (class_exists($controller) && method_exists($controller, $method)) {
                    (new $controller)->$method();
                } else {
                    http_response_code(500);
                    echo "Controller or method not found!";
                }
                return;
            }
        }
        // 404
        http_response_code(404);
        echo "Sorry, page not found!";
    }
}
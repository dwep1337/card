<?php

namespace App\Router;

class Router
{
    private array $routes = [];

    public function add(string $method, string $route, array $middlewares, string $action): void
    {
        $this->routes[] = compact('method', 'route', 'middlewares', 'action');
    }

    public function dispatch(string $requestUri, string $requestMethod): void
    {
        $uri = parse_url($requestUri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['route'] === $uri && $route['method'] === $requestMethod) {
                foreach ($route['middlewares'] as $middleware) {
                    if (class_exists($middleware)) {
                        $instance = new $middleware();
                        if (method_exists($instance, 'handle')) {
                            $continue = $instance->handle();
                            if ($continue === false) {
                                return;
                            }
                        }
                    }
                }

                [$controller, $method] = explode('@', $route['action']);
                $controller = "App\\Controller\\$controller";

                if (class_exists($controller) && method_exists($controller, $method)) {
                    (new $controller())->$method();
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

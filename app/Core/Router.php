<?php

namespace App\Core;

class Router
{
    protected array $routes = [];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function resolve(?string $path = null): void
    {
        $path = $path ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            $controllerClass = "\\App\\Controllers\\" . $callback['controller'];
            $methodName = $callback['method'];

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                $controller->$methodName();
                return;
            }
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }
}

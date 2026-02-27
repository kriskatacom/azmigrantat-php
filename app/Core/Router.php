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

        foreach ($this->routes[$method] as $routePath => $handler) {
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_]+)', $routePath);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);

                $controllerClass = $handler[0];
                $methodName = $handler[1];

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();

                    call_user_func_array([$controller, $methodName], $matches);
                    return;
                }
            }
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }
}

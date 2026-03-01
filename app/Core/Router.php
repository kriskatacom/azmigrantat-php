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
        $path = rtrim($path, '/'); // Премахваме наклонената черта в края за консистентност
        $path = $path === '' ? '/' : $path;

        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo "404 - Method Not Allowed";
            return;
        }

        foreach ($this->routes[$method] as $routePath => $handler) {
            // 1. Първо подменяме "catch-all" параметрите (тези със *), за да позволяват всичко, включително /
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\*\}/', '(.+)', $routePath);

            // 2. След това подменяме стандартните параметри (без /)
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $pattern);

            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);

                $controllerClass = $handler[0];
                $methodName = $handler[1];

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    // Декодираме параметрите, в случай че има интервали или специални символи в URL
                    $params = array_map('urldecode', $matches);
                    call_user_func_array([$controller, $methodName], $params);
                    return;
                }
            }
        }

        http_response_code(404);
        echo "404 - Page Not Found";
    }
}

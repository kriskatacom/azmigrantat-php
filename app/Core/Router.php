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

        // 2. Премахваме "/public" (за cPanel)
        if (strpos($path, '/public') === 0) {
            $path = substr($path, 7);
        }

        // --- НОВО: Премахване на езиковия префикс ---
        // Взимаме списъка с поддържани езици от HelperService
        $supportedLangs = array_keys(\App\Services\HelperService::AVAILABLE_LANGUAGES);
        $langsPattern = implode('|', $supportedLangs);

        // Регулярен израз, който маха /en, /de и т.н. само ако са в началото
        $path = preg_replace("#^/($langsPattern)(\b|/|$)#", '/', $path);
        // --------------------------------------------

        // 3. Нормализираме наклонените черти
        $path = rtrim($path, '/');
        $path = $path === '' ? '/' : $path;

        $method = $_SERVER['REQUEST_METHOD'];

        if (!isset($this->routes[$method])) {
            http_response_code(405);
            echo "405 - Method Not Allowed";
            return;
        }

        foreach ($this->routes[$method] as $routePath => $handler) {
            // Превръщаме маршрута в регулярен израз
            // Поддържаме {param}, {param*} и директни (\d+)
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\*\}/', '(.+)', $routePath);
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $pattern);

            // Ако вече имаш (\d+) в маршрута, това ще го остави както е
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Махаме пълното съвпадение

                $controllerClass = $handler[0];
                $methodName = $handler[1];

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    $params = array_map('urldecode', $matches);

                    if (method_exists($controller, $methodName)) {
                        call_user_func_array([$controller, $methodName], $params);
                        return;
                    }
                }
            }
        }

        http_response_code(404);
        echo "404 - Page Not Found (Path: " . htmlspecialchars($path) . ")";
    }
}

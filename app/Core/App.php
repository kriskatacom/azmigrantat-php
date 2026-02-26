<?php

namespace App\Core;

class App
{
    private array $supportedLangs = ['en'];
    private string $defaultLang = 'bg';

    public function initSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Обработва езика и връща изчистения път за рутиране
     */
    public function initLanguage(): string
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', trim($requestUri, '/'));

        if (isset($parts[0]) && in_array($parts[0], $this->supportedLangs)) {
            $_SESSION['lang'] = $parts[0];
            // Махаме езика от пътя: /en/travel -> /travel
            $routePath = '/' . implode('/', array_slice($parts, 1));
        } else {
            $_SESSION['lang'] = $this->defaultLang;
            $routePath = $requestUri ?: '/';
        }

        return rtrim($routePath, '/') ?: '/';
    }

    /**
     * Извиква съответния контролер
     */
    public function dispatch(string $routePath): void
    {
        $routes = require_once __DIR__ . '/../../routes/web.php';

        if (isset($routes[$routePath])) {
            $controllerName = $routes[$routePath]['controller'];
            $method = $routes[$routePath]['method'];
            $controllerClass = "App\\Controllers\\" . $controllerName;

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                $controller->$method();
                exit;
            }
        }

        $this->abort(404);
    }

    private function abort(int $code = 404): void
    {
        http_response_code($code);
        echo "$code Not Found";
        exit;
    }
}

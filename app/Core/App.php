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

    public function initLanguage(): string
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', trim($requestUri, '/'));

        if (isset($parts[0]) && in_array($parts[0], $this->supportedLangs)) {
            $_SESSION['lang'] = $parts[0];
            $routePath = '/' . implode('/', array_slice($parts, 1));
        } else {
            $_SESSION['lang'] = $this->defaultLang;
            $routePath = $requestUri;
        }

        return rtrim($routePath, '/') ?: '/';
    }

    public function dispatch(string $routePath): void
    {
        $router = require_once __DIR__ . '/../../routes/web.php';

        if ($router instanceof \App\Core\Router) {
            $router->resolve($routePath);
        } else {
            $this->abort(500);
        }
    }

    private function abort(int $code = 404): void
    {
        http_response_code($code);
        echo "<h1>$code Not Found</h1>";
        exit;
    }
}
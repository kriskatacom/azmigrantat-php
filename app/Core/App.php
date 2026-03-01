<?php

namespace App\Core;

class App
{
    private array $supportedLangs = ['en'];
    private string $defaultLang = 'bg';

    public function initSession(): void
    {
        // Първо зареждаме конфигурацията от .env файла
        $this->loadEnv();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Зарежда системните променливи от .env файла в корена на проекта.
     */
    private function loadEnv(): void
    {
        // Използваме BASE_PATH, който дефинирахме в public/index.php
        $envPath = defined('BASE_PATH') ? BASE_PATH . '/.env' : __DIR__ . '/../../.env';

        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                // Пропускаме коментари
                if (strpos(trim($line), '#') === 0) continue;

                // Разделяме на КЛЮЧ=СТОЙНОСТ
                if (strpos($line, '=') !== false) {
                    list($name, $value) = explode('=', $line, 2);
                    
                    $name = trim($name);
                    $value = trim($value);

                    // Задаваме променливата в средата
                    putenv(sprintf('%s=%s', $name, $value));
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }

    public function initLanguage(): string
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // ВАЖНО: Махаме /public от пътя, ако хостингът го добавя
        if (strpos($requestUri, '/public') === 0) {
            $requestUri = substr($requestUri, 7);
        }

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
        // Пътят до routes/web.php спрямо текущия файл
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
        echo "<h1>$code - Системна грешка</h1>";
        exit;
    }
}
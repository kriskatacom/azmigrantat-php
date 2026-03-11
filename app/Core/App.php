<?php

namespace App\Core;

use App\Services\HelperService;

class App
{
    private string $defaultLang = 'bg';

    public function initSession(): void
    {
        $this->loadEnv();

        $constantsPath = BASE_PATH . '/app/Config/constants.php';
        if (file_exists($constantsPath)) {
            require_once $constantsPath;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function initLanguage(): string
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (strpos($requestUri, '/public') === 0) {
            $requestUri = substr($requestUri, 7);
        }

        $parts = explode('/', trim($requestUri, '/'));
        $firstPart = $parts[0] ?? '';

        $supportedLangs = array_keys(HelperService::AVAILABLE_LANGUAGES);

        if ($firstPart !== '' && in_array($firstPart, $supportedLangs)) {
            $_SESSION['lang'] = $firstPart;

            $routePath = '/' . implode('/', array_slice($parts, 1));
        } else {
            $_SESSION['lang'] = $this->defaultLang;
            $routePath = $requestUri;
        }

        $routePath = '/' . trim($routePath, '/');

        return $routePath;
    }

    public function dispatch(string $routePath): void
    {
        $router = require_once BASE_PATH . '/routes/web.php';

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

    private function loadEnv(): void
    {
        $envPath = BASE_PATH . '/.env';
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                if (strpos($line, '=') !== false) {
                    list($name, $value) = explode('=', $line, 2);
                    putenv(sprintf('%s=%s', trim($name), trim($value)));
                    $_ENV[trim($name)] = trim($value);
                }
            }
        }
    }
}

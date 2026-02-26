<?php

namespace App\Services;

class HelperService
{
    public static function icon(string $name, string $class = "w-6 h-6"): void
    {
        $filePath = dirname(__DIR__, 2) . "/app/icons/{$name}.php";

        if (file_exists($filePath)) {
            echo '<span class="' . htmlspecialchars($class) . ' inline-flex items-center justify-center">';
            include $filePath;
            echo '</span>';
        } else {
            echo "";
        }
    }

    public static function navLinkClasses(string $path): string
    {
        $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base = "whitespace-nowrap md:text-lg transition-colors duration-200 px-5 py-2 rounded-full text-primary-light";
        if ($currentUri === $path) {
            return "{$base} bg-primary-dark";
        }
        return "{$base} hover:bg-primary-dark";
    }

    public static function isHome(): bool
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = rtrim($path, '/');

        return $path === '' || $path === '/en';
    }

    public static function trans(string $key): string
    {
        $lang = $_SESSION['lang'] ?? 'bg';
        $path = dirname(__DIR__, 2) . "/app/Languages/{$lang}.php";

        if (file_exists($path)) {
            $translations = include $path;
            return $translations[$key] ?? $key;
        }

        return $key;
    }

    public static function initLanguage(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_GET['lang'])) {
            $requestedLang = $_GET['lang'];

            if (in_array($requestedLang, ['bg', 'en'])) {
                $_SESSION['lang'] = $requestedLang;
            }

            $cleanUrl = strtok($_SERVER['REQUEST_URI'], '?');
            header("Location: " . $cleanUrl);
            exit;
        }

        if (!isset($_SESSION['lang'])) {
            $_SESSION['lang'] = 'bg';
        }
    }

    public static function url(string $path): string
    {
        $lang = $_SESSION['lang'] ?? 'bg';
        $path = ltrim($path, '/');

        if ($lang === 'en') {
            return "/en/{$path}";
        }

        return "/{$path}";
    }

    public static function getLangFromUrl(): string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', trim($uri, '/'));

        if (isset($parts[0]) && in_array($parts[0], ['bg', 'en'])) {
            return $parts[0];
        }

        return 'bg';
    }
}
<?php

namespace App\Services;

class HelperService
{
    /**
     * Рендира SVG икона директно в HTML.
     *
     * @param string $name Името на файла в public/assets/icons/
     * @param string $class Tailwind класове
     * @return string
     */
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

    /**
     * Генерира класове за линк в зависимост от това дали е активен.
     * * @param string $path Пътят, който проверяваме (напр. '/travel')
     * @return string Tailwind класове
     */
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
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === '/';
    }
}

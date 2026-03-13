<?php

namespace App\Core;

class Response
{
    public static function json($data, int $status = 200): void
    {
        if (ob_get_level() > 0) {
            ob_clean();
        }

        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        exit;
    }

    public static function send(string $content, int $status = 200): void
    {
        http_response_code($status);
        echo $content;
        exit;
    }

    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}

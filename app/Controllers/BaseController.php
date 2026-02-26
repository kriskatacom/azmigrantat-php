<?php

namespace App\Controllers;

use App\Core\View;

abstract class BaseController
{
    protected function json(mixed $data, int $code = 200): void
    {
        header('Content-Type: application/json');
        http_response_code($code);

        echo json_encode([
            'status' => $code < 400 ? 'success' : 'error',
            'data'   => $data
        ]);
        exit;
    }

    protected function redirect(string $url): void
    {
        header("Location: " . $url);
        exit;
    }

    protected function render(string $view, array $data = []): void
    {
        View::render($view, $data);
    }
}

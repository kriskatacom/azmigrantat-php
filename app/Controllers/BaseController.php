<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;

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

    protected function middleware(string $type = 'auth', array $except = [])
    {
        $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        foreach ($except as $method) {
            if (str_contains($currentUri, $method)) {
                return;
            }
        }

        $user = User::auth();

        if ($type === 'guest') {
            if ($user) {
                header('Location: /');
                exit;
            }
            return;
        }

        if (!$user) {
            header('Location: /login');
            exit;
        }

        if ($type !== 'auth' && $user['role'] !== $type) {
            $_SESSION['error'] = "Нямате достъп до тази секция!";
            header('Location: /');
            exit;
        }
    }

    protected function flash(string $type, string $message)
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
}
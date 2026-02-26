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

        // 1. Първо проверяваме изключенията. Ако методът е изключение, спираме веднага.
        foreach ($except as $method) {
            if (str_contains($currentUri, $method)) {
                return;
            }
        }

        $user = User::auth();

        // 2. Логика за ГOСТИ (guest)
        if ($type === 'guest') {
            if ($user) {
                header('Location: /');
                exit;
            }
            return; // Ако е гост и не е логнат, всичко е наред
        }

        // 3. Логика за ОТОРИЗАЦИЯ (auth или специфична роля)
        // Ако не е логнат, а се изисква каквато и да е роля или просто 'auth'
        if (!$user) {
            header('Location: /login');
            exit;
        }

        // 4. Проверка за конкретна РОЛЯ (ако $type не е 'auth', а нещо друго, напр. 'admin')
        if ($type !== 'auth' && $user['role'] !== $type) {
            $_SESSION['error'] = "Нямате достъп до тази секция!";
            header('Location: /');
            exit;
        }
    }
}

<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;
use App\Services\FileService;

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

    protected function handleOrderUpdate($model)
    {
        $data = json_decode(file_get_contents('php://input'), true);

        header('Content-Type: application/json');
        if (isset($data['items'])) {
            $success = $model->updateOrder($data['items']);
            echo json_encode(['success' => $success]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    protected function paginate($model, $perPage = 10)
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = (int)($_GET['per_page'] ?? $perPage);
        $offset = ($page - 1) * $limit;

        $total = $model->count();

        return [
            'limit'      => $limit,
            'offset'     => $offset,
            'pagination' => [
                'current'  => $page,
                'total'    => ceil($total / $limit),
                'per_page' => $limit,
                'total_records' => $total
            ]
        ];
    }

    protected function handleDelete($model, int $id, string|null $redirectUrl = null, array $fileFields = ['image_url'], array $jsonFileFields = [], callable|null $callback = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $item = $model->find($id);
        $redirectUrl = $redirectUrl ?? $_SERVER['HTTP_REFERER'] ?? '/admin/dashboard';

        if ($item) {
            if ($callback) {
                $callback($item);
            }

            foreach ($fileFields as $field) {
                if (!empty($item[$field])) {
                    FileService::delete($item[$field]);
                }
            }

            foreach ($jsonFileFields as $field) {
                if (!empty($item[$field])) {
                    $images = json_decode($item[$field], true);
                    if (is_array($images)) {
                        foreach ($images as $img) \App\Services\FileService::delete($img);
                    }
                }
            }

            $model->delete($id);
            $this->flash('success', 'Изтриването беше успешно!');
        } else {
            $this->flash('error', 'Записът не съществува.');
        }

        $this->redirect($redirectUrl);
    }
}

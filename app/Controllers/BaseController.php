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
        // Вземаме текущия URL път
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $currentPath = preg_replace('/^(\/public)/', '', $currentPath); // Махаме /public за хостинга
        $currentPath = rtrim($currentPath, '/') ?: '/';

        // Проверяваме дали текущият път съвпада с някой от изключенията
        // Това е по-сигурно от debug_backtrace в конструктора
        foreach ($except as $excludedMethod) {
            // Ако сме в show($slug), трябва да проверим дали пътят не съвпада с динамичния маршрут
            // За по-просто: ако сме в CountryController и методът е 'show', 
            // просто проверяваме дали пътят НЕ започва с /admin или /auth
            if ($excludedMethod === 'show' && strpos($currentPath, '/admin') === false && strpos($currentPath, '/auth') === false) {
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
            header('Location: /auth/login');
            exit;
        }

        if ($type !== 'auth' && (!isset($user['role']) || $user['role'] !== $type)) {
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
                        foreach ($images as $img) FileService::delete($img);
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

    protected function handleStore($model, string $baseRoute, array $fileFields = ['image_url'], string $uploadFolder = 'images')
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $data = $model->prepareData($_POST);

        foreach ($fileFields as $field) {
            if (!empty($_FILES[$field]['name'])) {
                $data[$field] = \App\Services\FileService::upload($_FILES[$field], $uploadFolder);
            }
            unset($data["remove_$field"]);
        }

        unset($data['return_url'], $data['existing_images']);

        $newId = $model->create($data);

        if ($newId) {
            $this->flash('success', 'Записът беше създаден успешно!');
            $this->redirect($baseRoute . '/edit/' . $newId);
        } else {
            $this->flash('error', 'Възникна грешка при създаването.');
            $this->redirect($baseRoute);
        }
    }

    protected function handleUpdate($model, int $id, string $baseRoute, array $fileFields = ['image_url'], string $uploadFolder = 'images')
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $item = $model->find($id);
        if (!$item) {
            $this->flash('error', 'Записът не е намерен.');
            $this->redirect($baseRoute);
        }

        $data = $model->prepareData($_POST);

        foreach ($fileFields as $field) {
            if (!empty($_FILES[$field]['name'])) {
                if (!empty($item[$field])) {
                    FileService::delete($item[$field]);
                }
                $data[$field] = FileService::upload($_FILES[$field], $uploadFolder);
            } elseif (isset($_POST["remove_$field"]) && $_POST["remove_$field"] == '1') {
                if (!empty($item[$field])) {
                    FileService::delete($item[$field]);
                }
                $data[$field] = null;
            }

            unset($data["remove_$field"]);
        }

        unset($data['return_url'], $data['existing_images']);

        if ($model->update($id, $data)) {
            $this->flash('success', 'Данните бяха обновени успешно!');
        }

        $this->redirect($baseRoute . '/edit/' . $id);
    }

    protected function handleGalleryUpdate(array $currentItem, array $postData, string $field = 'additional_images', string $folder = 'gallery'): string
    {
        $currentGallery = json_decode($currentItem[$field] ?? '[]', true);
        $remainingImages = $postData['existing_images'] ?? [];

        $removedImages = array_diff($currentGallery, $remainingImages);
        foreach ($removedImages as $img) {
            FileService::delete($img);
        }

        if (!empty($_FILES[$field]['name'][0])) {
            foreach ($_FILES[$field]['name'] as $key => $val) {
                if ($_FILES[$field]['error'][$key] === UPLOAD_ERR_OK) {
                    $fileData = [
                        'name'     => $_FILES[$field]['name'][$key],
                        'type'     => $_FILES[$field]['type'][$key],
                        'tmp_name' => $_FILES[$field]['tmp_name'][$key],
                        'error'    => $_FILES[$field]['error'][$key],
                        'size'     => $_FILES[$field]['size'][$key]
                    ];
                    $newPath = FileService::upload($fileData, $folder);
                    if ($newPath) $remainingImages[] = $newPath;
                }
            }
        }

        return json_encode(array_values($remainingImages));
    }
}

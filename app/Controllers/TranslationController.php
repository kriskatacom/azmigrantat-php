<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Translation;
use App\Core\View;
use App\Core\Response;

class TranslationController extends BaseController
{
    private Translation $translationModel;
    private string $baseRoute = '/admin/translations';

    public function __construct()
    {
        $this->translationModel = new Translation();
    }

    public function index()
    {
        $this->checkAccess('admin');

        $incomplete = isset($_GET['incomplete']);

        $currentLang = $_GET['lang'] ?? 'bg';
        $search = $_GET['search'] ?? null;
        $perPage = (int)($_GET['per_page'] ?? 15);

        $pageData = $this->paginate($this->translationModel, [
            'search' => $search,
            'incomplete' => $incomplete,
        ], $perPage);

        $translations = $this->translationModel->getUniqueKeys([
            'where'  => ['lang_code' => $currentLang],
            'search' => $search,
            'incomplete' => $incomplete,
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset'],
            'order'  => 'translation_key ASC'
        ]);

        View::render('admin/translations/index', [
            'title'        => 'Езикови преводи',
            'translations' => $translations,
            'pagination'   => $pageData['pagination'],
            'layout'       => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');

        return View::render('admin/translations/form', [
            'title'  => 'Добавяне на нов ключ (всички езици)',
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $key = trim($_POST['translation_key'] ?? '');
            $translations = $_POST['translations'] ?? [];

            if (empty($key)) {
                $this->flash('error', 'Ключът е задължителен!');
                $this->redirect($this->baseRoute . '/create');
            }

            $success = $this->translationModel->addFullTranslation($key, $translations);

            if ($success) {
                $this->flash('success', "Успешно добавен ключ: {$key}");
                $this->redirect($this->baseRoute);
            } else {
                $this->flash('error', 'Грешка при запис на преводите.');
                $this->redirect($this->baseRoute . '/create');
            }
        }
    }

    public function edit($id)
    {
        $this->checkAccess('admin');

        $current = $this->translationModel->find((int)$id);
        if (!$current) exit('Не е намерен');

        $allTranslations = $this->translationModel->all([
            'where' => ['translation_key' => $current['translation_key']]
        ]);

        $mapped = [];
        foreach ($allTranslations as $tr) {
            $mapped[$tr['lang_code']] = $tr['translation_value'];
        }

        return View::render('admin/translations/form', [
            'title'        => 'Масова редакция: ' . $current['translation_key'],
            'translation'  => $current,
            'mapped'       => $mapped,
            'isEdit'       => true,
            'layout'       => 'admin'
        ]);
    }

    public function update()
    {
        $this->checkAccess('admin');

        $key = $_POST['translation_key'];
        $translations = $_POST['translations'];

        $this->translationModel->addFullTranslation($key, $translations);

        $this->redirect('/admin/translations?incomplete=1');
    }

    public function confirmTranslations($entity, $id)
    {
        $this->checkAccess('admin');

        $json = file_get_contents('php://input');
        $input = json_decode($json, true);

        if (!$input) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Невалидни данни']);
            exit;
        }

        $firstLangData = current($input) ?: [];
        $fields = array_keys($firstLangData);

        try {
            foreach ($fields as $field) {
                $key = "{$entity}_{$id}_{$field}";

                $fieldTranslations = [];
                foreach ($input as $langCode => $values) {
                    if (isset($values[$field])) {
                        $fieldTranslations[$langCode] = $values[$field];
                    }
                }
                
                $this->translationModel->addFullTranslation($key, $fieldTranslations);
            }

            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            exit;
        }
    }

    public function delete(int $id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->translationModel, (int)$id, $this->baseRoute, []);
    }
}

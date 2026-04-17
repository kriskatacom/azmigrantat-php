<?php

namespace App\Controllers;

use App\Models\Village;
use App\Services\MetaTagsService;

class VillageController extends BaseController
{
    private Village $villageModel;
    private string $baseRoute = '/admin/villages';

    public function __construct()
    {
        $this->villageModel = new Village();
    }

    public function show($countrySlug, $citySlug, $villageSlug)
    {
        $village = $this->villageModel->findByColumn('slug', $villageSlug);

        if (!$village) {
            $this->abort(404, "Селото не е намерено.");
        }

        $descriptionSections = json_decode($village['description_sections'] ?? '[]', true);
        $gallery = json_decode($village['gallery_urls'] ?? '[]', true);

        $seo = new MetaTagsService([
            'title'       => $village['title'] . " - " . ($village['heading'] ?? 'Информация'),
            'description' => mb_substr(strip_tags($village['location'] ?? ''), 0, 160),
            'image'       => $village['image_url'] ?? null
        ]);

        $this->render('villages/show/index', [
            'village'             => $village,
            'descriptionSections' => $descriptionSections,
            'gallery'             => $gallery,
            'seo'                 => $seo,
            'layout'              => 'secondary',
        ]);
    }

    public function index()
    {
        $this->checkAccess(['admin', 'editor']);

        $filters = $this->getFilters();
        $pagination = $this->paginate($this->villageModel, $filters, ['title', 'location']);

        $villages = $this->villageModel->getFiltered(
            array_merge($filters, $pagination),
            ['title', 'location']
        );

        $this->render('admin/villages/index', [
            'villages'   => $villages,
            'pagination' => $pagination['pagination'],
            'filters'    => $filters,
            'title'      => 'Управление на села',
            'layout'    => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess(['admin', 'editor']);
        $this->render('admin/villages/form', [
            'title' => 'Добави ново село',
            'layout'    => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess(['admin', 'editor']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['slug'] = $this->villageModel->generateSlug($_POST['title']);

            $_POST['description_sections'] = $this->prepareDescriptionSections($_POST);

            if (!empty($_FILES['gallery_urls']['name'][0])) {
                $_POST['gallery_urls'] = $this->handleGalleryUpdate([], $_POST, 'gallery_urls', 'villages/gallery');
            }

            $this->handleStore($this->villageModel, $this->baseRoute, ['image_url'], 'villages');
        }
    }

    public function edit(int $id)
    {
        $this->checkAccess(['admin', 'editor']);

        $village = $this->villageModel->find($id);
        if (!$village) $this->abort(404);

        $this->render('admin/villages/form', [
            'village' => $village,
            'title'   => 'Редакция на ' . $village['title'],
            'layout'    => 'admin'
        ]);
    }

    public function update(int $id)
    {
        $this->checkAccess(['admin', 'editor']);

        $village = $this->villageModel->find($id);
        if (!$village) $this->abort(404);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST['slug'] = $this->villageModel->generateSlug($_POST['title']);

            $_POST['description_sections'] = $this->prepareDescriptionSections($_POST);

            $_POST['gallery_urls'] = $this->handleGalleryUpdate(
                $village,
                $_POST,
                'gallery_urls',
                'villages/gallery'
            );

            $this->handleUpdate($this->villageModel, $id, $this->baseRoute, ['image_url'], 'villages');
        }
    }

    public function delete(int $id)
    {
        $this->checkAccess(['admin']);

        $this->handleDelete(
            $this->villageModel,
            $id,
            $this->baseRoute,
            ['image_url'],
            ['gallery_urls']
        );
    }

    private function prepareDescriptionSections(array $post): array
    {
        $sections = [];
        if (!empty($post['section_title'])) {
            foreach ($post['section_title'] as $index => $title) {
                $content = $post['section_content'][$index] ?? '';
                if (!empty($title)) {
                    $sections[$title] = $content;
                }
            }
        }
        return $sections;
    }
}
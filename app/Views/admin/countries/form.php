<?php

use App\Core\View;

$isEdit = isset($country);
$action = $isEdit ? "/admin/countries/update/{$country['id']}" : "/admin/countries/store";
?>

<div class="">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg"><?= $title ?></h3>
            <a href="/admin/countries" class="text-sm text-gray-500 hover:text-primary-dark">← Назад</a>
        </div>

        <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Име на държавата (за списъка)</label>
                    <input type="text" name="name" value="<?= $country['name'] ?? '' ?>" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-light outline-none transition">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Заглавие на страницата (H1)</label>
                    <input type="text" name="heading" value="<?= $country['heading'] ?? '' ?>"
                        placeholder="напр. Добре дошли в България"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-light outline-none transition">
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-sm font-semibold text-gray-600">URL Slug (попълва се автоматично)</label>
                <input type="text"
                    name="slug"
                    value="<?= $country['slug'] ?? '' ?>"
                    placeholder="напр. bulgaria"
                    class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-light outline-none transition-all">
                <p class="text-gray-400 italic">Ако оставите празно, ще се генерира от името на латиница.</p>
            </div>

            <?php View::component('editor', 'admin/components', [
                'name'  => 'excerpt',
                'label' => 'Описание',
                'value' => $country['excerpt'] ?? ''
            ]); ?>

            <?php View::component('image-upload', 'admin/components', [
                'name'  => 'image_url',
                'label' => 'Изображение на държавата',
                'value' => $country['image_url'] ?? null,
                'id'    => 'country-image'
            ]); ?>

            <?php View::component('lightbox', 'admin/components'); ?>

            <div class="flex items-center gap-3 pt-4">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" <?= ($country['is_active'] ?? true) ? 'checked' : '' ?>>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-dark"></div>
                    <span class="ml-3 text-sm font-medium text-gray-600">Активна за показване</span>
                </label>
            </div>

            <div class="pt-5 border-t border-gray-100 flex gap-4">
                <button type="submit" class="bg-primary-dark text-white px-10 py-3 rounded-xl font-bold shadow-lg hover:bg-opacity-90 transition">
                    Запазване
                </button>
            </div>
        </form>
    </div>
</div>

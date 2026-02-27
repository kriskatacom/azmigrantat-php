<?php

use App\Core\View;

$isEdit = isset($landmark);
$action = $isEdit ? "/admin/landmarks/update/{$landmark['id']}" : "/admin/landmarks/store";
?>
<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800"><?= $title ?></h1>
            <p class="text-gray-500 text-sm">Попълнете детайлите за новия обект</p>
        </div>
        <a href="/admin/landmarks" class="text-gray-600 hover:text-gray-800 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Назад към списъка
        </a>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-6">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center text-sm">1</span>
                Основна информация
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Име на забележителността</label>
                    <input type="text" name="name" id="landmark-name" value="<?= htmlspecialchars($landmark['name'] ?? '') ?>" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-light focus:ring-4 focus:ring-primary-light/10 transition-all outline-none">
                </div>

                <?php View::component('slug-input', 'admin/components', [
                    'name'   => 'slug',
                    'value'  => $landmark['slug'] ?? '',
                    'source' => 'landmark-name'
                ]); ?>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Държава</label>
                    <select name="country_id" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-light focus:ring-4 focus:ring-primary-light/10 outline-none">

                        <option value="">Изберете държава...</option>

                        <?php foreach ($countries as $country): ?>
                            <option value="<?= $country['id'] ?>"
                                <?= (isset($landmark['country_id']) && $landmark['country_id'] == $country['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($country['name']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Подзаглавие (Heading)</label>
                    <input type="text"
                        name="heading"
                        value="<?= htmlspecialchars($landmark['heading'] ?? '') ?>"
                        placeholder="напр. Перлата на Родопите"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-light focus:ring-4 focus:ring-primary-light/10 outline-none transition-all">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center text-sm">2</span>
                Изображение
            </h3>

            <?php View::component('image-upload', 'admin/components', [
                'name'  => 'image_url',
                'label' => 'Изображение на забележителността',
                'value' => $landmark['image_url'] ?? null,
                'id'    => 'landmark-image'
            ]); ?>

            <?php View::component('lightbox', 'admin/components'); ?>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center text-sm">3</span>
                Съдържание
            </h3>

            <div class="space-y-6">
                <?php View::component('editor', 'admin/components', [
                    'name'  => 'excerpt',
                    'label' => 'Кратко описание (Excerpt)',
                    'value' => $landmark['excerpt'] ?? null,
                ]); ?>

                <?php View::component('editor', 'admin/components', [
                    'name'  => 'content',
                    'label' => 'Пълно описание на обекта',
                    'value' => $landmark['content'] ?? null,
                ]); ?>

                <?php View::component('editor', 'admin/components', [
                    'name'  => 'working_time',
                    'label' => 'Работно време',
                    'value' => $landmark['working_time'] ?? null,
                ]); ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center text-sm">4</span>
                Локация и Контакти
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Адрес</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($landmark['address'] ?? '') ?>" placeholder="ул. Примерна 123..."
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-light outline-none transition">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Телефон</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($landmark['phone'] ?? '') ?>" placeholder="+359..."
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-light outline-none transition">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Уебсайт</label>
                    <input type="text" name="website_link" value="<?= htmlspecialchars($landmark['website_link'] ?? '') ?>" placeholder="https://..."
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-light outline-none transition">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Такса за билет</label>
                    <input type="text" name="ticket_tax" value="<?= htmlspecialchars($landmark['ticket_tax'] ?? '') ?>" placeholder="напр. 10 лв."
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-light outline-none transition">
                </div>
            </div>

            <div class="mt-5">
                <?php View::component('maps-input', 'admin/components', [
                    'name'  => 'google_map',
                    'label' => 'Локация на обекта',
                    'value' => $landmark['google_map'] ?? ''
                ]); ?>
            </div>
        </div>

        <?php
        $gallery = !empty($landmark['additional_images']) ? json_decode($landmark['additional_images'], true) : [];

        View::component('multi-image-upload', 'admin/components', [
            'name'   => 'additional_images[]',
            'label'  => 'Фотогалерия на обекта',
            'images' => $gallery
        ]);
        ?>

        <div class="pt-5 border-t border-gray-100 flex gap-4">
            <button type="submit" class="bg-primary-dark text-white px-10 py-3 rounded-xl font-bold shadow-lg hover:bg-opacity-90 transition">
                Запазване
            </button>
        </div>

    </form>
</div>
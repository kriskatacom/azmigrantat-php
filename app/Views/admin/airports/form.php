<?php

use App\Core\View;

$isEdit = isset($airport);
$action = $isEdit ? "/admin/airports/update/{$airport['id']}" : "/admin/airports/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Летища', 'url' => '/admin/airports'],
            ['label' => $isEdit ? 'Редактиране' : 'Ново летище']
        ]
    ]); ?>
</div>

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-5 space-y-5">
        <div class="border-b border-gray-100 pb-4 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg"><?= $title ?></h3>
            <a href="/admin/airports" class="text-gray-500 hover:text-primary-dark">← Назад</a>
        </div>

        <?php View::component('image-upload', 'admin/components', [
            'name'  => 'image_url',
            'label' => 'Снимка на летището',
            'value' => $airport['image_url'] ?? null,
            'id'    => 'airport-image'
        ]); ?>

        <div class="grid md:grid-cols-2 gap-5">
            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="font-semibold text-gray-600">Име на летището</label>
                    <input type="text" name="name" id="airport-name" value="<?= $airport['name'] ?? '' ?>" required
                        placeholder="напр. Летище София" class="form-control">
                </div>
            </div>
            <div class="space-y-2">
                <label class="font-semibold text-gray-600">Държава</label>
                <select name="country_id" required class="form-control">
                    <option value="">-- Изберете държава --</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?= $country['id'] ?>" <?= (isset($airport) && $airport['country_id'] == $country['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($country['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <?php View::component('slug-input', 'admin/components', [
            'name'   => 'slug',
            'value'  => $airport['slug'] ?? '',
            'source' => 'airport-name'
        ]); ?>

        <div class="space-y-4 border-t border-gray-50">
            <div class="mt-2 p-3 bg-blue-50 border-l-4 border-blue-400 rounded-r-md">
                <p class="text-sm text-blue-700">
                    <strong>💡 Как работи:</strong><br>
                    • <strong>Координати:</strong> Попълват се автоматично от всеки линк на Google Maps.<br>
                    • <strong>Преглед (Карта):</strong> Показва се само ако поставите <strong>"Embed" код</strong> (от бутона Споделяне -> Вграждане на карта).<br>
                    • <strong>Обикновен линк:</strong> Ако поставите линк от адресната лента, ще се извлекат само координатите, без визуализация.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="font-semibold text-gray-600">
                        Google Maps Локация (URL)
                    </label>
                    <div class="relative">
                        <input type="text" id="google-maps-url" name="location_link"
                            value="<?= $airport['location_link'] ?? '' ?>"
                            placeholder="Поставете линк от Google Maps..."
                            class="form-control pr-10">
                        <div id="status-icon" class="absolute right-3 top-1/2 -translate-y-1/2 hidden"></div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="font-semibold text-gray-600">Latitude</label>
                        <input type="text" name="latitude" id="lat-field" value="<?= $airport['latitude'] ?? '' ?>" class="form-control bg-gray-50" readonly>
                    </div>
                    <div class="space-y-2">
                        <label class="font-semibold text-gray-600">Longitude</label>
                        <input type="text" name="longitude" id="lng-field" value="<?= $airport['longitude'] ?? '' ?>" class="form-control bg-gray-50" readonly>
                    </div>
                </div>
            </div>

            <?php $hasEmbedLink = !empty($airport['location_link']) && strpos($airport['location_link'], '/embed/') !== false; ?>

            <div id="map-preview-container" class="mt-5 <?= $hasEmbedLink ? '' : 'hidden' ?>">
                <label class="font-semibold text-gray-600 mb-2 block">Преглед на картата:</label>
                <div class="rounded-xl overflow-hidden border border-gray-200 shadow-sm min-h-100 h-full">
                    <iframe
                        id="map-iframe"
                        src="<?= $airport['location_link'] ?? '' ?>"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            <p class="text-xs text-gray-400 italic">Координатите се извличат автоматично от линка.</p>
        </div>

        <?php View::component('editor', 'admin/components', [
            'name'  => 'description',
            'label' => 'Основно съдържание',
            'value' => $airport['description'] ?? ''
        ]); ?>

        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $airport['is_active'] ?? true
        ]); ?>
    </div>

        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $airport['is_active'],
        ]); ?>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const googleMapsInput = document.getElementById('google-maps-url');
        const latField = document.getElementById('lat-field');
        const lngField = document.getElementById('lng-field');
        const mapIframe = document.getElementById('map-iframe');
        const mapContainer = document.getElementById('map-preview-container');

        googleMapsInput.addEventListener('input', function() {
            let value = this.value.trim();

            if (value) {
                extractCoordinates(value);

                let isEmbed = false;
                let finalSrc = "";

                if (value.includes('<iframe')) {
                    const srcMatch = value.match(/src=["']([^"']+)["']/);
                    if (srcMatch) {
                        finalSrc = srcMatch[1];
                        isEmbed = true;
                        this.value = finalSrc;
                    }
                } else if (value.includes('/embed/')) {
                    finalSrc = value;
                    isEmbed = true;
                }

                if (isEmbed) {
                    mapIframe.src = finalSrc;
                    mapContainer.classList.remove('hidden');
                } else {
                    mapIframe.src = "";
                    mapContainer.classList.add('hidden');
                }

            } else {
                mapContainer.classList.add('hidden');
                latField.value = '';
                lngField.value = '';
                mapIframe.src = '';
            }
        });

        function extractCoordinates(url) {
            const latMatch = url.match(/!3d(-?\d+\.\d+)/) || url.match(/@(-?\d+\.\d+)/);
            const lngMatch = url.match(/!4d(-?\d+\.\d+)/) || url.match(/!2d(-?\d+\.\d+)/) || url.match(/@(?:.*),(-?\d+\.\d+)/);

            if (latMatch) latField.value = latMatch[1];
            if (lngMatch) {
                lngField.value = lngMatch[2] || lngMatch[1];
            }
        }
    });
</script>
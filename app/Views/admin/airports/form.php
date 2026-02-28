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

<form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
    
    <?php ob_start(); ?>
        <div class="max-w-xl">
            <?php View::component('image-upload', 'admin/components', [
                'name'  => 'image_url',
                'label' => 'Снимка на летището',
                'value' => $airport['image_url'] ?? null,
                'id'    => 'airport-image'
            ]); ?>
        </div>
    <?php View::component('card', 'admin/components', ['title' => 'Визуализация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="font-semibold text-gray-600">Име на летището</label>
                <input type="text" name="name" id="airport-name" value="<?= htmlspecialchars($airport['name'] ?? '') ?>" required
                    placeholder="напр. Летище София" class="form-control">
            </div>

            <div class="space-y-2">
                <label class="font-semibold text-gray-600">Държава</label>
                <select name="country_id" required class="form-control bg-white">
                    <option value="">-- Изберете държава --</option>
                    <?php foreach ($countries as $country): ?>
                        <option value="<?= $country['id'] ?>" <?= (isset($airport) && $airport['country_id'] == $country['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($country['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mt-4">
            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $airport['slug'] ?? '',
                'source' => 'airport-name'
            ]); ?>
        </div>
    <?php View::component('card', 'admin/components', ['title' => 'Основна информация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <div class="space-y-4">
            <div class="p-4 bg-blue-50/50 border-l-4 border-blue-400 rounded-r-xl">
                <p class="text-sm text-blue-800 leading-relaxed">
                    <strong>💡 Как работи:</strong> Поставете линк от Google Maps (или Embed код) в полето по-долу. Системата автоматично ще извлече <strong>Latitude</strong> и <strong>Longitude</strong>.
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="font-semibold text-gray-600">Google Maps Локация (URL)</label>
                    <div class="relative">
                        <input type="text" id="google-maps-url" name="location_link"
                            value="<?= htmlspecialchars($airport['location_link'] ?? '') ?>"
                            placeholder="Поставете линк или iframe..."
                            class="form-control pr-10">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="font-semibold text-gray-600 italic opacity-70">Latitude</label>
                        <input type="text" name="latitude" id="lat-field" value="<?= $airport['latitude'] ?? '' ?>" class="form-control bg-gray-50 cursor-not-allowed" readonly>
                    </div>
                    <div class="space-y-2">
                        <label class="font-semibold text-gray-600 italic opacity-70">Longitude</label>
                        <input type="text" name="longitude" id="lng-field" value="<?= $airport['longitude'] ?? '' ?>" class="form-control bg-gray-50 cursor-not-allowed" readonly>
                    </div>
                </div>
            </div>

            <?php $hasEmbedLink = !empty($airport['location_link']) && strpos($airport['location_link'], '/embed/') !== false; ?>
            <div id="map-preview-container" class="mt-4 <?= $hasEmbedLink ? '' : 'hidden' ?>">
                <label class="font-semibold text-gray-600 mb-2 block">Преглед на картата:</label>
                <div class="rounded-xl overflow-hidden border border-gray-200 shadow-inner aspect-video bg-gray-50">
                    <iframe id="map-iframe" src="<?= $airport['location_link'] ?? '' ?>" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    <?php View::component('card', 'admin/components', ['title' => 'Географска локация', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <?php View::component('editor', 'admin/components', [
            'name'  => 'description',
            'label' => 'Допълнителна информация за летището',
            'value' => $airport['description'] ?? ''
        ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Описание', 'slot' => ob_get_clean()]); ?>

    <?php ob_start(); ?>
        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $airport['is_active'] ?? true
        ]); ?>
    <?php View::component('card', 'admin/components', ['title' => 'Статус', 'slot' => ob_get_clean()]); ?>

    <div class="mb-3 pt-2">
        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $airport['is_active'] ?? true,
        ]); ?>
    </div>
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
            if (lngMatch) lngField.value = lngMatch[2] || lngMatch[1];
        }
    });
</script>
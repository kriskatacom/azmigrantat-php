<?php

use App\Core\View;

$isEdit = isset($autobus);
$action = $isEdit ? "/admin/autobuses/update/{$autobus['id']}" : "/admin/autobuses/store";
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Автобуси', 'url' => '/admin/autobuses'],
            ['label' => $isEdit ? 'Редактиране' : 'Нова компания']
        ]
    ]); ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
        <h3 class="font-bold text-gray-800 text-lg"><?= $title ?></h3>
        <a href="/admin/autobuses" class="text-sm text-gray-500 hover:text-primary-dark">← Назад</a>
    </div>

    <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-10">

        <div class="space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2 text-indigo-500">Медия</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <?php View::component('image-upload', 'admin/components', [
                    'name'  => 'image_url',
                    'label' => 'Изображение',
                    'value' => $autobus['image_url'] ?? null,
                    'id'    => 'bus-logo'
                ]); ?>
            </div>

            <?php View::component('lightbox', 'admin/components'); ?>
        </div>

        <div class="space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2 text-indigo-500">Обща информация</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Име на компанията</label>
                    <input type="text" name="name" id="bus-name" value="<?= $autobus['name'] ?? '' ?>" required
                        placeholder="напр. Union Ivkoni"
                        class="form-control">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Официален уебсайт</label>
                    <input type="url" name="website_url" value="<?= $autobus['website_url'] ?? '' ?>"
                        placeholder="https://www.example.com"
                        class="form-control">
                </div>
            </div>

            <?php View::component('slug-input', 'admin/components', [
                'name'   => 'slug',
                'value'  => $autobus['slug'] ?? '',
                'source' => 'bus-name'
            ]); ?>
        </div>

        <div class="space-y-5">
            <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2 text-indigo-500">Локация (Централа)</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Държава</label>
                    <select name="country_id" id="country-select" required class="form-control bg-white">
                        <option value="">-- Изберете държава --</option>
                        <?php foreach ($countries as $country): ?>
                            <option value="<?= $country['id'] ?>" <?= (isset($autobus) && $autobus['country_id'] == $country['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($country['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Град</label>
                    <select name="city_id" id="city-select" required class="form-control bg-white">
                        <option value="">-- Първо изберете държава --</option>
                        <?php if ($isEdit && isset($cities)): ?>
                            <?php foreach ($cities as $city): ?>
                                <option value="<?= $city['id'] ?>" <?= $autobus['city_id'] == $city['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($city['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>
        </div>

        <?php View::component('toggle', 'admin/components', [
            'name'  => 'is_active',
            'label' => 'Показвай в сайта',
            'value' => $autobus['is_active'] ?? true
        ]); ?>

        <?php View::component('submit-button', 'admin/components', [
            'text' => !$isEdit ? 'Създаване' : 'Запазване',
            'is_active' => $autobus['is_active'] ?? true
        ]); ?>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countrySelect = document.getElementById('country-select');
        const citySelect = document.getElementById('city-select');

        if (countrySelect) {
            countrySelect.addEventListener('change', function() {
                const countryId = this.value;

                citySelect.innerHTML = '<option value="">Зареждане...</option>';

                if (!countryId) {
                    citySelect.innerHTML = '<option value="">-- Първо изберете държава --</option>';
                    return;
                }

                fetch(`/api/cities-by-country/${countryId}`)
                    .then(res => res.json())
                    .then(cities => {
                        citySelect.innerHTML = '<option value="">-- Изберете град --</option>';
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.name;
                            citySelect.appendChild(option);
                        });
                    })
                    .catch(err => {
                        console.error('Error fetching cities:', err);
                        citySelect.innerHTML = '<option value="">Грешка при зареждане</option>';
                    });
            });
        }
    });
</script>

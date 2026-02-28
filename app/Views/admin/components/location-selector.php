<?php
/**
 * @var array $countries Списък с всички държави
 * @var array|null $cities Списък с градове (само при редакция)
 * @var int|null $selectedCountry ID на избраната държава
 * @var int|null $selectedCity ID на избрания град
 */
$uid = uniqid('loc_');
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-5" id="container-<?= $uid ?>">
    <div class="space-y-2">
        <label class="text-sm font-semibold text-gray-600">Държава</label>
        <select name="country_id" 
                class="form-control bg-white country-select" 
                required>
            <option value="">-- Изберете държава --</option>
            <?php foreach ($countries as $country): ?>
                <option value="<?= $country['id'] ?>" <?= ($selectedCountry == $country['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($country['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="space-y-2">
        <label class="text-sm font-semibold text-gray-600">Град</label>
        <select name="city_id" 
                class="form-control bg-white city-select" 
                required>
            <?php if ($selectedCountry && $cities): ?>
                <option value="">-- Изберете град --</option>
                <?php foreach ($cities as $city): ?>
                    <option value="<?= $city['id'] ?>" <?= ($selectedCity == $city['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($city['name']) ?>
                    </option>
                <?php endforeach; ?>
            <?php else: ?>
                <option value="">-- Първо изберете държава --</option>
            <?php endif; ?>
        </select>
    </div>
</div>

<script>
(function() {
    const container = document.getElementById('container-<?= $uid ?>');
    const countrySelect = container.querySelector('.country-select');
    const citySelect = container.querySelector('.city-select');

    countrySelect.addEventListener('change', function() {
        const countryId = this.value;

        if (!countryId) {
            citySelect.innerHTML = '<option value="">-- Първо изберете държава --</option>';
            return;
        }

        citySelect.innerHTML = '<option value="">Зареждане...</option>';

        fetch(`/api/cities-by-country/${countryId}`)
            .then(res => res.json())
            .then(data => {
                const cities = data.data || data; 
                
                citySelect.innerHTML = '<option value="">-- Изберете град --</option>';
                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.id;
                    option.textContent = city.name;
                    citySelect.appendChild(option);
                });
            })
            .catch(err => {
                console.error('Error:', err);
                citySelect.innerHTML = '<option value="">Грешка при зареждане</option>';
            });
    });
})();
</script>
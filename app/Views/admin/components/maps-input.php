<?php
$name = $name ?? 'location_link';
$value = $value ?? '';
$label = $label ?? 'Google Maps Локация';
$latValue = $latValue ?? '';
$lngValue = $lngValue ?? '';
$isEmbed = !empty($value) && (strpos($value, '/embed/') !== false || strpos($value, 'google.com/maps/embed') !== false);
?>

<div class="mt-5">
    <div class="space-y-4">
        <div class="p-4 bg-blue-50/50 border-l-4 border-blue-400 rounded-r-xl">
            <p class="text-sm text-blue-800 leading-relaxed">
                <strong>💡 Инструкция:</strong> Поставете <strong>Embed код</strong> (от споделяне на картата) или <strong>директен линк</strong>. Системата ще запази точния адрес за вграждане и координатите.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-2">
                <label class="font-semibold text-gray-600"><?= $label ?> (URL)</label>
                <div class="relative">
                    <input type="text" id="google-maps-url" name="<?= $name ?>"
                        value="<?= htmlspecialchars($value) ?>"
                        placeholder="Поставете <iframe... или линк"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-primary-light focus:ring-4 focus:ring-primary-light/10 transition-all outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="font-semibold text-gray-600 italic opacity-70">Latitude</label>
                    <input type="text" name="latitude" id="lat-field" value="<?= $latValue ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 cursor-not-allowed outline-none" readonly>
                </div>
                <div class="space-y-2">
                    <label class="font-semibold text-gray-600 italic opacity-70">Longitude</label>
                    <input type="text" name="longitude" id="lng-field" value="<?= $lngValue ?>"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 cursor-not-allowed outline-none" readonly>
                </div>
            </div>
        </div>

        <div id="map-preview-container" class="mt-4 <?= $isEmbed ? '' : 'hidden' ?>">
            <label class="font-semibold text-gray-600 mb-2 block">Преглед на картата:</label>
            <div class="rounded-xl overflow-hidden border border-gray-200 shadow-inner bg-gray-50 h-80">
                <iframe id="map-iframe"
                    src="<?= $isEmbed ? htmlspecialchars($value) : '' ?>"
                    width="100%" height="100%"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"></iframe>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const googleMapsInput = document.getElementById('google-maps-url');
            const latField = document.getElementById('lat-field');
            const lngField = document.getElementById('lng-field');
            const mapIframe = document.getElementById('map-iframe');
            const mapContainer = document.getElementById('map-preview-container');

            if (!googleMapsInput) return;

            function processMap() {
                let value = googleMapsInput.value.trim();

                if (!value) {
                    mapContainer.classList.add('hidden');
                    mapIframe.src = '';
                    return;
                }

                const latMatch = value.match(/!3d(-?\d+\.\d+)/) || value.match(/@(-?\d+\.\d+)/);
                const lngMatch = value.match(/!4d(-?\d+\.\d+)/) || value.match(/!2d(-?\d+\.\d+)/) || value.match(/@(?:.*),(-?\d+\.\d+)/);

                if (latMatch) latField.value = latMatch[1];
                if (lngMatch) lngField.value = lngMatch[2] || lngMatch[1];

                let embedUrl = "";

                if (value.includes('<iframe')) {
                    const srcMatch = value.match(/src=["']([^"']+)["']/);
                    if (srcMatch) {
                        embedUrl = srcMatch[1];
                        googleMapsInput.value = embedUrl;
                    }
                } else if (value.includes('/embed/') || value.includes('google.com/maps/embed')) {
                    embedUrl = value;
                }

                if (embedUrl) {
                    mapIframe.src = embedUrl;
                    mapContainer.classList.remove('hidden');
                } else {
                    mapIframe.src = '';
                    mapContainer.classList.add('hidden');
                }
            }

            googleMapsInput.addEventListener('input', processMap);

            if (googleMapsInput.value) {
                processMap();
            }
        });
    </script>
</div>

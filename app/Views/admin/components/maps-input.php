<?php
$name = $name ?? 'google_map';
$value = $value ?? '';
$label = $label ?? 'Google Maps Локация';
$id = $id ?? 'maps-' . uniqid();
?>

<div class="maps-component-container space-y-3" data-id="<?= $id ?>">
    <label class="text-sm font-semibold text-gray-600 flex justify-between">
        <span><?= $label ?></span>
        <span class="status-badge text-[10px] uppercase tracking-wider px-2 py-0.5 rounded bg-gray-100 text-gray-400">Въведете код</span>
    </label>

    <textarea
        name="<?= $name ?>"
        id="input-<?= $id ?>"
        rows="3"
        placeholder='Поставете <iframe src="..."></iframe> или директен линк тук'
        class="form-control maps-input w-full px-4 py-2 border rounded-xl outline-none focus:ring-2 focus:ring-blue-100 transition"><?= $value ?></textarea>

    <div class="relative mt-4 rounded-2xl overflow-hidden border border-gray-200 bg-gray-50 max-w-full h-75 shadow-inner flex items-center justify-center">
        <div class="maps-placeholder text-center space-y-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <p class="text-gray-400 text-sm italic font-medium">Картата ще се зареди тук...</p>
        </div>
        <div class="active-map absolute inset-0 hidden"></div>
    </div>
</div>

<script>
    (function() {
        const initMaps = function() {
            const mapContainers = document.querySelectorAll('.maps-component-container');

            mapContainers.forEach(container => {
                const input = container.querySelector('.maps-input');
                const activeMap = container.querySelector('.active-map');
                const placeholder = container.querySelector('.maps-placeholder');
                const badge = container.querySelector('.status-badge');

                // Защита: Ако някой елемент липсва, не продължавай
                if (!input || !activeMap) return;

                function processMap() {
                    let val = input.value.trim();
                    let finalUrl = '';

                    if (val.includes('<iframe')) {
                        const match = val.match(/src=["']([^"']+)["']/);
                        if (match && match[1]) {
                            finalUrl = match[1];
                            input.value = finalUrl; // Почистваме инпута да остане само URL-а
                        }
                    } else if (val.startsWith('http')) {
                        finalUrl = val;
                    }

                    if (finalUrl) {
                        placeholder.classList.add('hidden');
                        activeMap.classList.remove('hidden');
                        activeMap.innerHTML = `<iframe src="${finalUrl}" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>`;

                        badge.textContent = "Валидна локация";
                        badge.className = "status-badge text-[10px] uppercase tracking-wider px-2 py-0.5 rounded bg-green-100 text-green-600 font-bold";
                    } else {
                        placeholder.classList.remove('hidden');
                        activeMap.classList.add('hidden');
                        activeMap.innerHTML = '';

                        badge.textContent = "Чакане на код";
                        badge.className = "status-badge text-[10px] uppercase tracking-wider px-2 py-0.5 rounded bg-gray-100 text-gray-400";
                    }
                }

                input.addEventListener('input', processMap);
                if (input.value.length > 5) processMap();
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMaps);
        } else {
            initMaps();
        }
    })();
</script>

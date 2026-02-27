<?php

use App\Services\HelperService;

// Параметри, които компонентът приема
$name = $name ?? 'image_url';
$value = $value ?? null;
$label = $label ?? 'Снимка';
$default = $default ?? '/assets/images/no-image.png';
$id = $id ?? 'image-input';
?>

<div class="space-y-3 image-upload-container">
    <label class="text-sm font-semibold text-gray-600 block"><?= $label ?></label>
    <div class="flex items-center gap-6 p-4 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">

        <div class="relative shrink-0 group/img">
            <img id="preview-<?= $id ?>"
                src="<?= HelperService::getImage($value) ?>"
                data-default="<?= $default ?>"
                class="lightbox-trigger w-40 h-28 object-cover rounded-xl border-2 border-white shadow-md transition-transform hover:scale-105 cursor-zoom-in">

            <button type="button"
                id="remove-btn-<?= $id ?>"
                class="<?= empty($value) ? 'hidden' : '' ?> absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 transition-colors z-10 remove-image-btn">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <input type="hidden" name="remove_<?= $name ?>" id="remove-input-<?= $id ?>" value="0">
        </div>

        <div class="flex flex-col gap-1">
            <label class="cursor-pointer group">
                <input type="file" name="<?= $name ?>" id="<?= $id ?>" accept="image/*" class="hidden image-file-input">
                <div class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg shadow-sm text-sm font-bold text-gray-700 group-hover:bg-gray-50 group-hover:border-primary-light transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    Изберете изображение
                </div>
            </label>
            <p class="text-[11px] file-name-display" id="file-name-<?= $id ?>">
                <span class="text-gray-400 italic">Максимален размер: 10MB</span>
            </p>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Делегиране на събития за всички инпути за файлове
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('image-file-input')) {
                const input = e.target;
                const container = input.closest('.image-upload-container');
                const preview = container.querySelector('img[id^="preview-"]');
                const removeBtn = container.querySelector('.remove-image-btn');
                const removeInput = container.querySelector('input[id^="remove-input-"]');
                const fileNameDisplay = container.querySelector('.file-name-display');
                const file = input.files[0];
                const MAX_SIZE = 10 * 1024 * 1024;

                if (file) {
                    if (file.size > MAX_SIZE) {
                        alert('Файлът е твърде голям! Максималният размер е 10MB.');
                        input.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        removeBtn.classList.remove('hidden');
                        removeInput.value = "0";
                    }
                    reader.readAsDataURL(file);
                    fileNameDisplay.innerHTML = `<span class="text-primary-dark font-bold text-xs uppercase tracking-wider">Избрано: ${file.name}</span>`;
                }
            }
        });

        // Делегиране на събития за бутона за премахване
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-image-btn');
            if (btn) {
                e.preventDefault();
                const container = btn.closest('.image-upload-container');
                const input = container.querySelector('.image-file-input');
                const preview = container.querySelector('img[id^="preview-"]');
                const removeInput = container.querySelector('input[id^="remove-input-"]');
                const fileNameDisplay = container.querySelector('.file-name-display');

                input.value = "";
                preview.src = preview.getAttribute('data-default');
                removeInput.value = "1";
                btn.classList.add('hidden');
                fileNameDisplay.innerHTML = '<span class="text-red-500 font-bold italic animate-pulse">Снимката ще бъде премахната при запис</span>';
            }
        });
    });
</script>

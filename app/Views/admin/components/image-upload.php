<?php
use App\Services\HelperService;

$name = $name ?? 'image_url';
$value = $value ?? null;
$label = $label ?? 'Снимка';
$default = $default ?? '/assets/images/no-image.png';
$id = $id ?? 'image-input';
$fullPath = HelperService::getImage($value);
$hasImage = !empty($value) && !str_contains($fullPath, 'no-image.png');
?>

<div class="space-y-3 image-upload-container">
    <label class="text-sm font-bold text-gray-700 block tracking-tight"><?= $label ?></label>
    
    <div class="flex flex-col sm:flex-row items-center gap-6 p-5 bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-200 transition-colors hover:bg-gray-50/80">

        <div class="relative shrink-0 group/img">
            <div class="relative w-44 h-32 rounded-2xl overflow-hidden shadow-sm border-2 border-white bg-white transition-all duration-300 group-hover/img:shadow-xl group-hover/img:-translate-y-1">
                <img id="preview-<?= $id ?>"
                    src="<?= $fullPath ?>"
                    data-default="<?= $default ?>"
                    class="w-full h-full object-cover transition-transform duration-700 group-hover/img:scale-110">

                <div id="overlay-<?= $id ?>" class="absolute inset-0 bg-linear-to-t from-black/50 via-transparent to-black/20 opacity-0 group-hover/img:opacity-100 transition-opacity duration-300 <?= !$hasImage ? 'hidden' : '' ?>"></div>

                <div id="actions-<?= $id ?>" class="absolute top-2 right-2 flex gap-1.5 translate-y-2.5 opacity-0 group-hover/img:translate-y-0 group-hover/img:opacity-100 transition-all duration-300 z-10 <?= !$hasImage ? 'hidden' : '' ?>">
                    
                    <button type="button" 
                       id="zoom-btn-<?= $id ?>"
                       data-src="<?= $fullPath ?>"
                       class="lightbox-trigger w-8 h-8 flex items-center justify-center bg-white/90 backdrop-blur-sm text-amber-500 rounded-xl shadow-lg hover:bg-amber-500 hover:text-white transition-all duration-200" 
                       title="Преглед">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                        </svg>
                    </button>

                    <a id="download-btn-<?= $id ?>" 
                       href="<?= $fullPath ?>" 
                       download 
                       target="_blank" 
                       class="w-8 h-8 flex items-center justify-center bg-white/90 backdrop-blur-sm text-indigo-600 rounded-xl shadow-lg hover:bg-indigo-600 hover:text-white transition-all duration-200" 
                       title="Изтегли">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12 a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                    </a>

                    <button type="button"
                        class="remove-image-btn w-8 h-8 flex items-center justify-center bg-white/90 backdrop-blur-sm text-red-500 rounded-xl shadow-lg hover:bg-red-500 hover:text-white transition-all duration-200"
                        title="Премахни">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <input type="hidden" name="remove_<?= $name ?>" id="remove-input-<?= $id ?>" value="0">
        </div>

        <div class="flex flex-col gap-3 flex-1">
            <label class="cursor-pointer group w-fit">
                <input type="file" name="<?= $name ?>" id="<?= $id ?>" accept="image/*" class="hidden image-file-input">
                <div class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 rounded-xl shadow-sm text-sm font-bold text-gray-700 group-hover:border-indigo-400 group-hover:text-indigo-600 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <?= $hasImage ? 'Смени снимката' : 'Качи снимка' ?>
                </div>
            </label>
            
            <div class="file-name-display">
                <p class="text-[11px] text-gray-400 font-medium uppercase tracking-wider italic">
                    JPG, PNG, WebP (Макс. 10MB)
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Централизирана функция за показване/скриване на екстрите
    function toggleImageActions(container, show, src = '') {
        const actions = container.querySelector('[id^="actions-"]');
        const overlay = container.querySelector('[id^="overlay-"]');
        const zoomBtn = container.querySelector('.lightbox-trigger');
        const downloadBtn = container.querySelector('a[download]');
        
        if (show) {
            actions.classList.remove('hidden');
            overlay.classList.remove('hidden');
            if(zoomBtn) zoomBtn.setAttribute('data-src', src);
            if(downloadBtn) downloadBtn.setAttribute('href', src);
        } else {
            actions.classList.add('hidden');
            overlay.classList.add('hidden');
        }
    }

    // Промяна на файл
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('image-file-input')) {
            const input = e.target;
            const container = input.closest('.image-upload-container');
            const preview = container.querySelector('img[id^="preview-"]');
            const removeInput = container.querySelector('input[id^="remove-input-"]');
            const fileNameDisplay = container.querySelector('.file-name-display');
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    preview.src = ev.target.result;
                    removeInput.value = "0";
                    toggleImageActions(container, true, ev.target.result);
                    
                    fileNameDisplay.innerHTML = `
                        <div class="flex items-center gap-1.5 animate-fade-in text-indigo-600">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span class="font-black text-[10px] uppercase tracking-widest">Ново избрано:</span>
                            <span class="text-gray-600 text-[10px] font-bold truncate max-w-[150px]">${file.name}</span>
                        </div>
                    `;
                }
                reader.readAsDataURL(file);
            }
        }
    });

    // Премахване на снимка
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
            
            // Скриваме всички бутони и овърлея
            toggleImageActions(container, false);
            
            fileNameDisplay.innerHTML = `
                <div class="flex items-center gap-1.5 text-red-500 italic">
                    <span class="font-bold text-[10px] uppercase tracking-tighter animate-pulse">Ще бъде премахната при запис</span>
                </div>
            `;
        }
    });
});
</script>

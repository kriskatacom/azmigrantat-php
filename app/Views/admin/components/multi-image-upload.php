<?php
$name = $name ?? 'additional_images[]';
$label = $label ?? 'Галерия със снимки';
$images = $images ?? [];
?>

<div class="space-y-4 multi-image-container">
    <label class="text-sm font-semibold text-gray-600 block"><?= $label ?></label>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4" id="image-preview-grid">
        <label class="cursor-pointer group aspect-video rounded-xl border-2 border-dashed border-gray-200 hover:border-primary-light hover:bg-primary-light/5 transition-all flex flex-col items-center justify-center gap-2">
            <input type="file" name="<?= $name ?>" multiple accept="image/*" class="hidden multi-image-input">
            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
            </div>
            <span class="text-[11px] font-bold text-gray-500 uppercase tracking-wider">Добави снимки</span>
        </label>

        <?php foreach ($images as $index => $imgUrl): ?>
            <div class="relative group aspect-video rounded-xl overflow-hidden border border-gray-100 shadow-sm bg-gray-100">
                <img src="<?= \App\Services\HelperService::getImage($imgUrl) ?>" class="w-full h-full object-cover">
                <input type="hidden" name="existing_images[]" value="<?= $imgUrl ?>">
                <button type="button" class="remove-existing absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        <?php endforeach; ?>
    </div>
    <p class="text-xs text-gray-400 italic">Можете да изберете няколко файла едновременно (Ctrl + клик).</p>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const grid = document.getElementById('image-preview-grid');

        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('multi-image-input')) {
                const files = Array.from(e.target.files);

                files.forEach(file => {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const div = document.createElement('div');
                        div.className = "relative group aspect-video rounded-xl overflow-hidden border border-gray-100 shadow-sm animate-fade-in";
                        div.innerHTML = `
                        <img src="${event.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-[10px] text-white font-bold uppercase">Нова</span>
                        </div>
                    `;
                        grid.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            }
        });

        grid.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-existing');
            if (removeBtn) {
                const container = removeBtn.closest('div');
                container.classList.add('scale-0', 'opacity-0');
                setTimeout(() => container.remove(), 300);
            }
        });
    });
</script>

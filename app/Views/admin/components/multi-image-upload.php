<?php
$name = $name ?? 'additional_images[]';
$label = $label ?? 'Галерия със снимки';
$images = $images ?? [];
?>

<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4" id="image-preview-grid">
    <label class="cursor-pointer group aspect-video rounded-xl border-2 border-dashed border-gray-200 hover:border-primary-light hover:bg-primary-light/5 transition-all flex flex-col items-center justify-center gap-2">
        <input type="file" name="<?= $name ?>" multiple accept="image/*" class="hidden multi-image-input">
        <span class="text-[11px] font-bold text-gray-500 uppercase">Добави снимки</span>
    </label>

    <?php foreach ($images as $imgUrl): ?>
        <div class="relative group aspect-video rounded-xl overflow-hidden border border-gray-100 shadow-sm bg-gray-100">
            <img src="<?= \App\Services\HelperService::getImage($imgUrl) ?>"
                class="lightbox-trigger w-full h-full object-cover cursor-zoom-in group-hover:scale-105 transition-transform duration-500">

            <input type="hidden" name="existing_images[]" value="<?= $imgUrl ?>">

            <button type="button" class="remove-existing absolute top-1 right-1 bg-red-500/90 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity z-10 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    <?php endforeach; ?>
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
                        div.className = "relative group aspect-video rounded-xl overflow-hidden border border-gray-100 shadow-sm animate-fade-in bg-gray-100";

                        div.innerHTML = `
                            <img src="${event.target.result}" 
                                class="lightbox-trigger w-full h-full object-cover cursor-zoom-in">
                            <div class="absolute bottom-1 left-1 bg-black/50 px-1.5 py-0.5 rounded text-[8px] text-white font-bold uppercase pointer-events-none">
                                Нова
                            </div>
                            <button type="button" class="remove-new absolute top-1 right-1 bg-red-500/90 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity z-10 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
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
                e.preventDefault();
                const container = removeBtn.closest('div');
                container.remove();
            }
        });
    });
</script>

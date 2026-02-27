<?php

use App\Core\View;
use App\Services\HelperService;

$isEdit = isset($country);
$action = $isEdit ? "/admin/countries/update/{$country['id']}" : "/admin/countries/store";
?>

<div class="">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800 text-lg"><?= $title ?></h3>
            <a href="/admin/countries" class="text-sm text-gray-500 hover:text-primary-dark">← Назад</a>
        </div>

        <form action="<?= $action ?>" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Име на държавата (за списъка)</label>
                    <input type="text" name="name" value="<?= $country['name'] ?? '' ?>" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-light outline-none transition">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-600">Заглавие на страницата (H1)</label>
                    <input type="text" name="heading" value="<?= $country['heading'] ?? '' ?>"
                        placeholder="напр. Добре дошли в България"
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-light outline-none transition">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600">Описание</label>
                <textarea name="excerpt" rows="4"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-primary-light outline-none transition"><?= $country['excerpt'] ?? '' ?></textarea>
            </div>

            <div class="space-y-3">
                <label class="text-sm font-semibold text-gray-600 block">Снимка на държавата</label>
                <div class="flex items-center gap-6 p-4 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200">
                    <div class="relative shrink-0 group/img">
                        <img id="preview-image"
                            src="<?= HelperService::getImage($country['image_url']) ?>"
                            data-default="<?= '/assets/img/no-image.png' ?>"
                            class="lightbox-trigger w-50 h-30 object-cover rounded-xl border-2 border-white shadow-md cursor-zoom-in hover:scale-105 transition-transform">

                        <button type="button" id="remove-image-btn"
                            class="<?= empty($country['image_url']) ? 'hidden' : '' ?> absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 transition-colors z-10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        <input type="hidden" name="remove_image" id="remove-image-input" value="0">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="cursor-pointer group">
                            <input type="file" name="image_url" id="image-input" accept="image/*" class="hidden">
                            <div class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg shadow-sm text-sm font-bold text-gray-700 group-hover:bg-gray-50 group-hover:border-primary-light transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Изберете изображение
                            </div>
                        </label>
                        <p class="text-[11px] transition-colors duration-200" id="file-name">
                            <span class="text-gray-400 italic">Максимален размер: 10MB</span>
                        </p>
                    </div>
                </div>
            </div>

            <?php View::component('lightbox', 'admin/components'); ?>

            <div class="flex items-center gap-3 pt-4">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="sr-only peer" <?= ($country['is_active'] ?? true) ? 'checked' : '' ?>>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-dark"></div>
                    <span class="ml-3 text-sm font-medium text-gray-600">Активна за показване</span>
                </label>
            </div>

            <div class="pt-5 border-t border-gray-100 flex gap-4">
                <button type="submit" class="bg-primary-dark text-white px-10 py-3 rounded-xl font-bold shadow-lg hover:bg-opacity-90 transition">
                    Запазване
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('image-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview-image');
        const fileNameDisplay = document.getElementById('file-name');
        const submitBtn = document.querySelector('button[type="submit"]');

        const MAX_SIZE = 10 * 1024 * 1024;

        if (file) {
            if (file.size > MAX_SIZE) {
                alert('Файлът е твърде голям! Максималният размер е 10MB.');

                this.value = '';
                fileNameDisplay.textContent = 'Грешка: Файлът надвишава 10MB';
                fileNameDisplay.classList.replace('text-gray-400', 'text-red-500');

                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return;
            }

            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

            fileNameDisplay.textContent = 'Избрано: ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
            fileNameDisplay.classList.remove('text-red-500');
            fileNameDisplay.classList.add('text-primary-dark');

            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('image-input');
    const previewImage = document.getElementById('preview-image');
    const removeBtn = document.getElementById('remove-image-btn');
    const removeInput = document.getElementById('remove-image-input');
    const fileNameDisplay = document.getElementById('file-name');
    const defaultImg = previewImage.getAttribute('data-default');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                removeBtn.classList.remove('hidden');
                removeInput.value = "0";
            }
            reader.readAsDataURL(file);
            fileNameDisplay.innerHTML = `<span class="text-primary-dark font-bold">Избрано: ${file.name}</span>`;
        }
    });

    removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        previewImage.src = defaultImg;
        imageInput.value = '';
        removeInput.value = "1";
        this.classList.add('hidden');
        fileNameDisplay.innerHTML = '<span class="text-gray-400 italic">Снимката ще бъде премахната при запис</span>';
    });
});
</script>

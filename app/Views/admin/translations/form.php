<?php

use App\Core\View;
use App\Services\HelperService;

$isEdit = isset($translation);
$action = $isEdit ? "/admin/translations/update/{$translation['id']}" : "/admin/translations/store";
$languages = HelperService::AVAILABLE_LANGUAGES;
?>

<div class="mb-5">
    <?php View::component('breadcrumbs', 'admin/components', [
        'items' => [
            ['label' => 'Преводи', 'url' => '/admin/translations'],
            ['label' => $isEdit ? 'Редактиране' : 'Нов превод']
        ]
    ]); ?>
</div>

<form action="<?= $action ?>" method="POST" class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        <div class="lg:col-span-1">
            <?php ob_start(); ?>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Системен ключ (Slug)</label>
                    <input type="text"
                        name="translation_key"
                        value="<?= htmlspecialchars($translation['translation_key'] ?? '') ?>"
                        class="form-control"
                        placeholder="home_welcome_title"
                        <?= $isEdit ? 'readonly' : 'required' ?>>
                    <?php if ($isEdit): ?>
                        <p class="text-xs text-gray-400 mt-1">Ключът не може да бъде променян.</p>
                    <?php endif; ?>

                    <div class="mt-6 p-5 bg-linear-to-br from-indigo-50 to-purple-50 border border-indigo-100 rounded-2xl shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="shrink-0 w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-indigo-600">
                                <i class="fa-solid fa-wand-magic-sparkles text-lg animate-pulse"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-indigo-900">AI Асистент</h4>
                                <p class="text-[11px] text-indigo-500 leading-tight">Автоматичен превод чрез Google Translate</p>
                            </div>
                        </div>

                        <button type="button"
                            id="magic-translate"
                            class="relative w-full group overflow-hidden px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl shadow-md transition-all duration-300 hover:bg-indigo-700 hover:shadow-indigo-200 active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed">

                            <div class="absolute inset-0 w-full h-full bg-linear-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>

                            <div class="flex items-center justify-center gap-2">
                                <i class="fa-solid fa-language text-lg group-hover:rotate-12 transition-transform"></i>
                                <span id="btn-text">Преведи празните полета</span>
                            </div>
                        </button>

                        <p class="mt-3 text-[10px] text-center text-indigo-400">
                            *Използва Български като източник
                        </p>
                    </div>
                </div>
            </div>
            <?php View::component('card', 'admin/components', ['title' => 'Настройки', 'slot' => ob_get_clean()]); ?>

            <div class="mt-6">
                <?php View::component('submit-button', 'admin/components', [
                    'text' => $isEdit ? 'Обнови всички езици' : 'Създай за всички езици',
                ]); ?>
            </div>
        </div>

        <div class="lg:col-span-3">
            <?php ob_start(); ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <?php foreach ($languages as $code => $lang): ?>
                    <div class="p-4 border border-gray-100 rounded-xl hover:bg-gray-50 transition">
                        <label class="flex items-center gap-2 text-sm font-bold text-gray-700 mb-2">
                            <span class="text-lg"><?= $lang['flag'] ?></span>
                            <?= $lang['name'] ?> (<?= strtoupper($code) ?>)
                        </label>

                        <textarea
                            name="translations[<?= $code ?>]"
                            rows="2"
                            class="form-control"
                            placeholder="Въведете превод на <?= $lang['name'] ?>..."><?= htmlspecialchars($mapped[$code] ?? '') ?></textarea>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php View::component('card', 'admin/components', [
                'title' => 'Езикови версии',
                'slot' => ob_get_clean()
            ]); ?>
        </div>
    </div>
</form>

<script>
    document.getElementById('magic-translate').addEventListener('click', async function() {
        const btn = this;
        const sourceText = document.querySelector('textarea[name="translations[bg]"]').value;

        if (!sourceText) {
            alert('Моля, въведете текст на Български първо!');
            return;
        }

        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner animate-spin"></i> Превеждам...';

        const textareas = document.querySelectorAll('textarea[name^="translations["]');

        const translationPromises = Array.from(textareas).map(async (area) => {
            const langCode = area.name.match(/\[(.*?)\]/)[1];

            if (langCode !== 'bg' && area.value.trim() === '') {
                try {
                    area.classList.add('animate-pulse', 'border-primary-light');

                    const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=bg&tl=${langCode}&dt=t&q=${encodeURIComponent(sourceText)}`;

                    const response = await fetch(url);
                    const data = await response.json();

                    if (data && data[0] && data[0][0] && data[0][0][0]) {
                        area.value = data[0][0][0];
                    }
                } catch (e) {
                    console.error(`Грешка при превод на ${langCode}:`, e);
                } finally {
                    area.classList.remove('animate-pulse', 'border-primary-light');
                }
            }
        });

        await Promise.all(translationPromises);

        btn.disabled = false;
        btn.innerHTML = originalContent;
    });
</script>
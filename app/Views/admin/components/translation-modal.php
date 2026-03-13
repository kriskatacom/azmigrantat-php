<?php
$fields = $fields ?? [];
$languages = $languages ?? [];
$langCodes = array_keys($languages);

if (!in_array('bg', $langCodes)) {
    array_unshift($langCodes, 'bg');
}

$firstTargetLang = !empty($languages) ? array_key_first($languages) : 'en';
?>

<div x-data="translationModal()"
    @open-modal-translation.window="open($event.detail)"
    x-show="isShow"
    x-cloak
    class="fixed inset-0 z-100 flex items-center justify-center bg-slate-900/60 backdrop-blur-md">

    <div class="bg-white rounded-3xl w-full max-w-5xl max-h-[95vh] flex flex-col overflow-hidden shadow-2xl">
        <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-white">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg">
                    <i class="fa-solid fa-language text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight">Локализация</h3>
                    <p class="text-xs font-bold text-slate-400">Редактиране на: <span x-text="entityName" class="text-indigo-600"></span></p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="button" @click="magicTranslate" :disabled="loading"
                    class="px-5 py-2.5 bg-indigo-50 text-indigo-600 font-bold rounded-xl hover:bg-indigo-100 transition-all flex items-center gap-2 text-sm disabled:opacity-50 cursor-pointer">
                    <i class="fa-solid fa-wand-magic-sparkles" :class="loading && 'animate-spin'"></i>
                    <span>AI Авто-превод</span>
                </button>
                <button @click="isShow = false" class="text-slate-400 hover:text-red-500 cursor-pointer">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>
        </div>

        <div x-ref="tabsContainer" class="bg-white p-5 border-b border-slate-100 flex items-center gap-3 overflow-x-auto shadow-sm">
            <?php foreach ($languages as $code => $info): ?>
                <?php $flagCode = ($code === 'en') ? 'us' : $code; ?>
                <button @click="switchLanguage('<?= $code ?>')"
                    id="tab-btn-<?= $code ?>"
                    type="button"
                    :class="activeLang === '<?= $code ?>' 
                        ? 'bg-indigo-600 text-white shadow-lg ring-2 ring-indigo-600 ring-offset-2' 
                        : 'bg-slate-50 text-slate-500 hover:bg-slate-100 border-slate-200'"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-[11px] transition-all border uppercase tracking-wide cursor-pointer shrink-0">
                    <div class="w-7 h-5 shrink-0 overflow-hidden rounded-sm border border-black/5">
                        <img src="https://flagcdn.com/w80/<?= strtolower($flagCode) ?>.png" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col items-start leading-tight">
                        <span class="font-black text-[10px]"><?= htmlspecialchars($info['name']) ?></span>
                        <span :class="activeLang === '<?= $code ?>' ? 'text-indigo-200' : 'text-slate-400'" class="text-[9px] font-medium uppercase"><?= $code ?></span>
                    </div>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="p-5 overflow-y-auto flex-1 bg-slate-50">
            <div class="space-y-5">
                <?php foreach ($fields as $key => $field): ?>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <div class="flex justify-between items-end mb-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <?= $field['label'] ?> (<span x-text="activeLang.toUpperCase()"></span>)
                            </label>

                            <div class="bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-100 max-w-[50%]">
                                <span class="text-[9px] font-bold text-indigo-400 uppercase block mb-1">Оригинал (BG):</span>
                                <div class="text-[11px] text-indigo-900 italic line-clamp-2" id="original-bg-<?= $key ?>">—</div>
                            </div>
                        </div>

                        <?php if ($field['type'] === 'text'): ?>
                            <input type="text" data-field="<?= $key ?>" class="translation-input w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        <?php elseif ($field['type'] === 'editor'): ?>
                            <div class="quill-wrapper bg-slate-50 rounded-xl overflow-hidden border border-slate-200">
                                <div id="editor-container-<?= $key ?>" style="height: 250px;" class="bg-white"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="px-8 py-5 bg-white border-t border-slate-100 flex justify-end gap-4">
            <button @click="isShow = false" type="button" class="px-6 py-2 font-black text-slate-400 uppercase text-xs tracking-widest cursor-pointer">Отказ</button>
            <button @click="save" type="button" class="bg-slate-900 text-white px-10 py-3 rounded-2xl font-black shadow-xl active:scale-95 disabled:opacity-50 cursor-pointer" :disabled="loading">
                <span x-show="!loading" class="text-sm">ЗАПАЗИ ПРЕВОДИТЕ</span>
                <i x-show="loading" class="fa-solid fa-spinner animate-spin"></i>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('translationModal', () => ({
            isShow: false,
            loading: false,
            activeLang: '<?= $firstTargetLang ?>',
            entityName: '',
            entityId: null,
            formData: {},
            fields: <?= json_encode($fields) ?>,
            langCodes: <?= json_encode($langCodes) ?>,
            quillInstances: {},

            open(detail) {
                this.entityName = detail.name || 'Обект';
                this.entityId = detail.id;
                this.formData = detail.data || {};

                this.langCodes.forEach(lang => {
                    if (!this.formData[lang]) this.formData[lang] = {};
                });

                this.isShow = true;

                this.$nextTick(() => {
                    this.initEditors();
                    this.updateUI();
                });
            },

            initEditors() {
                Object.keys(this.fields).forEach(key => {
                    if (this.fields[key].type === 'editor') {
                        const container = document.getElementById(`editor-container-${key}`);
                        if (container && !this.quillInstances[key]) {
                            this.quillInstances[key] = new Quill(container, {
                                theme: 'snow',
                                modules: {
                                    toolbar: [
                                        ['bold', 'italic', 'underline'],
                                        [{
                                            list: 'ordered'
                                        }, {
                                            list: 'bullet'
                                        }],
                                        ['link', 'clean']
                                    ]
                                }
                            });
                        }
                    }
                });
            },

            switchLanguage(newLang) {
                this.readCurrentUI();
                this.activeLang = newLang;
                this.updateUI();

                this.$nextTick(() => {
                    const activeTab = document.getElementById(`tab-btn-${newLang}`);

                    if (activeTab) {
                        activeTab.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest',
                            inline: 'center'
                        });
                    }
                });
            },

            updateUI() {
                document.querySelectorAll('.translation-input').forEach(input => {
                    const key = input.getAttribute('data-field');
                    input.value = this.formData[this.activeLang]?.[key] || '';
                });

                Object.keys(this.quillInstances).forEach(key => {
                    const content = this.formData[this.activeLang]?.[key] || '';
                    this.quillInstances[key].root.innerHTML = content;
                });

                Object.keys(this.fields).forEach(key => {
                    const bgEl = document.getElementById(`original-bg-${key}`);
                    if (bgEl) bgEl.innerHTML = this.formData['bg']?.[key] || '—';
                });

                this.langCodes.forEach(lang => {
                    const btn = document.querySelector(`[data-lang-btn="${lang}"]`);
                    if (!btn) return;

                    const hasContent = Object.values(this.formData[lang]).some(val => val && val !== '<p><br></p>');

                    if (hasContent && lang !== 'bg') {
                        btn.classList.add('border-green-400');
                    }
                });
            },

            readCurrentUI() {
                document.querySelectorAll('.translation-input').forEach(input => {
                    const key = input.getAttribute('data-field');
                    this.formData[this.activeLang][key] = input.value;
                });

                Object.keys(this.quillInstances).forEach(key => {
                    this.formData[this.activeLang][key] = this.quillInstances[key].root.innerHTML;
                });
            },

            sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            },

            async translateText(text, targetLang) {
                if (!text || text === '<p><br></p>') return '';

                let langCode = targetLang === 'ie' ? 'ga' : targetLang;

                const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=bg&tl=${langCode}&dt=t&q=${encodeURIComponent(text)}`;

                try {
                    const response = await fetch(url);
                    if (!response.ok) return null;

                    const data = await response.json();
                    return data[0].map(part => part[0]).join('');
                } catch (e) {
                    console.error(`Грешка при превод (${targetLang}):`, e);
                    return null;
                }
            },

            async magicTranslate() {
                this.loading = true;
                this.readCurrentUI();

                const sourceData = this.formData['bg'];
                const targetLanguages = this.langCodes.filter(l => l !== 'bg');

                let requestCount = 0;

                for (const lang of targetLanguages) {
                    this.switchLanguage(lang);

                    await this.sleep(300);

                    for (const fieldKey of Object.keys(this.fields)) {
                        try {
                            const originalText = sourceData[fieldKey];
                            const currentTranslation = this.formData[lang][fieldKey];

                            if (originalText && originalText.trim() !== '' && (!currentTranslation || currentTranslation.trim() === '' || currentTranslation === '<p><br></p>')) {

                                requestCount++;

                                const translated = await this.translateText(originalText, lang);

                                if (translated !== null) {
                                    this.formData[lang][fieldKey] = translated;
                                    this.updateUI();
                                } else {
                                    console.warn(`Полето ${fieldKey} на език ${lang} беше пропуснато поради грешка в API.`);
                                }

                                if (requestCount % 30 === 0) {
                                    await this.sleep(3000);
                                } else {
                                    await this.sleep(200);
                                }
                            }
                        } catch (loopError) {
                            console.error(`Критична грешка при обработка на ${fieldKey}:`, loopError);
                        }
                    }
                }

                this.loading = false;
            },

            async save() {
                this.readCurrentUI();
                this.loading = true;

                try {
                    const response = await fetch(`/admin/translations/confirm/${this.entityName}/${this.entityId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(this.formData)
                    });

                    const contentType = response.headers.get("content-type");
                    if (!contentType || !contentType.includes("application/json")) {
                        const rawText = await response.text();
                        console.error("Сървърът върна грешен формат:", rawText);
                        throw new Error("Сървърът върна грешка (PHP Error). Проверете конзолата.");
                    }

                    const result = await response.json();

                    if (response.ok && result.success) {
                        this.isShow = false;
                        window.location.reload();
                    } else {
                        alert("Грешка при запис: " + (result.error || "Неизвестна грешка"));
                    }
                } catch (e) {
                    console.error("Критична грешка:", e);
                    alert(e.message);
                } finally {
                    this.loading = false;
                }
            }
        }));
    });
</script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('translatableForm', (config) => ({
            id: config.id,
            entity: config.entity,
            storedTranslations: config.translations || {},

            openTranslation() {
                const getEditorContent = (name) => {
                    const el = document.querySelector(`[name="${name}"]`);
                    const editor = el?.parentNode.querySelector('.ql-editor');
                    return (editor && editor.innerHTML !== '<p><br></p>') ? editor.innerHTML : '';
                };

                const bgData = {};

                config.fields.forEach(fieldName => {
                    const content = getEditorContent(fieldName);
                    if (content) {
                        bgData[fieldName] = content;
                    } else {
                        const input = document.querySelector(`[name="${fieldName}"]`) || document.getElementById(`cat-${fieldName}`);
                        bgData[fieldName] = input ? input.value : '';
                    }
                });

                this.$dispatch('open-modal-translation', {
                    id: this.id,
                    name: this.entity,
                    data: {
                        bg: bgData,
                        ...this.storedTranslations
                    }
                });
            }
        }));
    });
</script>

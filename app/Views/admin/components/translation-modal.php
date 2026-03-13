<?php

$languages = $languages ?? [];
$langCodes = array_keys($languages);
$firstTargetLang = $langCodes[1] ?? ($langCodes[0] ?? 'en');

$fields = $fields ?? [];
$saveEndpoint = $saveEndpoint ?? '/admin/translations/confirm';
$redirectBase = $redirectBase ?? '/admin/categories/edit';
$entityType = $entityType ?? 'category';
$nextId = $nextId ?? "";
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
                    <h3 class="text-xl font-black text-slate-800 uppercase tracking-tight leading-none">Локализация</h3>
                    <p class="text-[10px] font-bold text-slate-400 mt-1">Редактиране на: <span x-text="entityName" class="text-indigo-600"></span></p>
                </div>

                <div class="hidden md:flex items-center gap-3 pl-4 ml-4 border-l border-slate-100">
                    <div class="flex flex-col">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Готови езици</span>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-black text-slate-700" x-text="stats.translated + ' / ' + stats.total"></span>
                            <div class="flex gap-1">
                                <template x-for="i in stats.total">
                                    <div class="w-1.5 h-1.5 rounded-full" :class="i <= stats.translated ? 'bg-green-500' : 'bg-slate-200'"></div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div x-show="loading || isPaused" class="flex items-center gap-4 px-4 py-2 bg-slate-50 rounded-2xl border border-slate-200 shadow-inner">
                    <div class="relative flex items-center justify-center">
                        <svg class="w-8 h-8 transform -rotate-90">
                            <circle cx="16" cy="16" r="14" stroke="currentColor" stroke-width="3" fill="transparent" class="text-slate-200" />
                            <circle cx="16" cy="16" r="14" stroke="currentColor" stroke-width="3" fill="transparent"
                                class="text-indigo-600 transition-all duration-500"
                                stroke-dasharray="88"
                                :stroke-dashoffset="88 - (88 * progress / 100)" />
                        </svg>
                        <i x-show="!isPaused" class="fa-solid fa-wand-magic-sparkles text-[10px] text-indigo-600 absolute animate-pulse"></i>
                        <i x-show="isPaused" class="fa-solid fa-pause text-[10px] text-amber-500 absolute"></i>
                    </div>
                    <div class="flex flex-col min-w-30">
                        <span class="text-[10px] font-black text-slate-700 uppercase leading-none" x-text="currentAction"></span>
                        <span class="text-[9px] font-bold text-slate-400 mt-1"><span x-text="progress"></span>% завършени</span>
                    </div>
                    <div class="flex gap-1">
                        <button type="button" x-show="isPaused" @click="isPaused = false; magicTranslate()" class="w-7 h-7 flex items-center justify-center bg-green-500 text-white rounded-lg cursor-pointer hover:bg-green-600 transition-colors"><i class="fa-solid fa-play text-[10px]"></i></button>
                        <button type="button" x-show="loading && !isPaused" @click="isPaused = true" class="w-7 h-7 flex items-center justify-center bg-amber-500 text-white rounded-lg cursor-pointer hover:bg-amber-600 transition-colors"><i class="fa-solid fa-pause text-[10px]"></i></button>
                    </div>
                </div>

                <button type="button" x-show="!loading && !isPaused" @click="magicTranslate()" class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all flex items-center gap-2 text-sm shadow-lg cursor-pointer active:scale-95">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> AI Авто-превод
                </button>

                <div class="w-px h-8 bg-slate-100 mx-2"></div>

                <button @click="isShow = false" class="text-slate-300 hover:text-red-500 cursor-pointer p-1 transition-colors"><i class="fa-solid fa-xmark text-2xl"></i></button>
            </div>
        </div>

        <div x-ref="tabsContainer" class="bg-white p-5 border-b border-slate-100 flex items-center gap-3 overflow-x-auto">
            <?php foreach ($languages as $code => $info): ?>
                <?php $flagCode = ($code === 'en') ? 'us' : $code; ?>
                <button @click="switchLanguage('<?= $code ?>')" id="tab-btn-<?= $code ?>" type="button"
                    :class="activeLang === '<?= $code ?>' ? 'bg-indigo-600 text-white shadow-lg ring-2 ring-indigo-600 ring-offset-2' : 'bg-slate-50 text-slate-500 border-slate-200'"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl font-bold text-[11px] transition-all border uppercase cursor-pointer shrink-0">
                    <img src="https://flagcdn.com/w80/<?= strtolower($flagCode) ?>.png" class="w-7 h-5 object-cover rounded-sm">
                    <div class="flex flex-col items-start leading-tight">
                        <span class="font-black text-[10px]"><?= htmlspecialchars($info['name']) ?></span>
                        <span class="text-[9px] opacity-70 uppercase"><?= $code ?></span>
                    </div>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="p-5 overflow-y-auto flex-1 bg-slate-50">
            <div class="space-y-5">
                <?php foreach ($fields as $key => $field): ?>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <div class="flex justify-between items-end mb-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest"><?= $field['label'] ?> (<span x-text="activeLang.toUpperCase()"></span>)</label>
                            <div class="bg-indigo-50 px-3 py-1 rounded-lg border border-indigo-100 max-w-[50%]">
                                <span class="text-[9px] font-bold text-indigo-400 uppercase block">Оригинал (BG):</span>
                                <div class="text-[11px] text-indigo-900 italic line-clamp-2" id="original-bg-<?= $key ?>">—</div>
                            </div>
                        </div>
                        <?php if ($field['type'] === 'text'): ?>
                            <input type="text" data-field="<?= $key ?>" class="translation-input w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">
                        <?php elseif ($field['type'] === 'editor'): ?>
                            <div class="quill-wrapper bg-white rounded-xl overflow-hidden border border-slate-200">
                                <div id="editor-container-<?= $key ?>" style="height: 200px;"></div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="px-8 py-5 bg-white border-t border-slate-100 flex justify-end gap-4">
            <button @click="isShow = false" type="button" class="px-6 py-2 font-black text-slate-400 uppercase text-xs cursor-pointer">Отказ</button>
            <button @click="save" type="button" class="bg-slate-900 text-white px-10 py-3 rounded-2xl font-black shadow-xl disabled:opacity-50 cursor-pointer" :disabled="loading">
                <span x-show="!loading">ЗАПАЗИ ПРЕВОДИТЕ</span>
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
            isPaused: false,
            progress: 0,
            currentLangIndex: 0,
            activeLang: '<?= $firstTargetLang ?>',
            currentAction: '',
            entityName: '',
            entityId: null,
            formData: {},
            fields: <?= json_encode($fields) ?>,
            langCodes: <?= json_encode($langCodes) ?>,
            quillInstances: {},
            nextId: '<?= $nextId ?>',
            saveEndpoint: '<?= $saveEndpoint ?>',
            redirectBase: '<?= $redirectBase ?>',
            entityType: '<?= $entityType ?>',

            // 1. Инициализация и Наблюдател (Watcher)
            init() {
                // Следим кога се затваря диалога
                this.$watch('isShow', value => {
                    // Ако се затвори (value е false) и в момента превеждаме
                    if (!value && this.loading && !this.isPaused) {
                        this.isPaused = true;
                        this.currentAction = 'Паузирано';
                    }
                });
            },

            get stats() {
                const targetLangs = this.langCodes.filter(l => l !== 'bg');
                const total = targetLangs.length;
                let translated = 0;

                targetLangs.forEach(lang => {
                    const hasData = Object.keys(this.fields).some(key => {
                        const val = this.formData[lang]?.[key];
                        return val && val !== '' && val !== '<p><br></p>';
                    });

                    if (hasData) translated++;
                });

                return {
                    translated,
                    total,
                    percent: total > 0 ? Math.round((translated / total) * 100) : 0
                };
            },

            open(detail) {
                // АКО ВЕЧЕ ИМА ЗАПОЧНАТ ПРОЦЕС: просто отваряме без рестарт
                if (this.currentLangIndex > 0 || this.loading) {
                    this.isShow = true;
                    this.$nextTick(() => {
                        this.updateUI();
                        this.switchLanguage(this.activeLang);
                    });
                    return;
                }

                // НОВО ОТВАРЯНЕ (рестарт)
                this.currentLangIndex = 0;
                this.progress = 0;
                this.isPaused = false;
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
                    if (new URLSearchParams(window.location.search).get('live') === '1' && !this.loading) {
                        this.magicTranslate();
                    }
                });
            },

            initEditors() {
                Object.keys(this.fields).forEach(key => {
                    if (this.fields[key].type === 'editor' && !this.quillInstances[key]) {
                        const el = document.getElementById(`editor-container-${key}`);
                        if (el) this.quillInstances[key] = new Quill(el, {
                            theme: 'snow',
                            modules: {
                                toolbar: [
                                    ['bold', 'italic'],
                                    [{
                                        list: 'ordered'
                                    }, {
                                        list: 'bullet'
                                    }],
                                    ['clean']
                                ]
                            }
                        });
                    }
                });
            },

            switchLanguage(newLang) {
                this.readCurrentUI();
                this.activeLang = newLang;
                this.updateUI();

                this.$nextTick(() => {
                    const activeTab = document.getElementById(`tab-btn-${newLang}`);
                    const container = this.$refs.tabsContainer;

                    if (activeTab && container) {
                        const containerRect = container.getBoundingClientRect();
                        const tabRect = activeTab.getBoundingClientRect();
                        const scrollOffset = (tabRect.left - containerRect.left) - (containerRect.width / 2) + (tabRect.width / 2);

                        container.scrollBy({
                            left: scrollOffset,
                            behavior: 'smooth'
                        });
                    }
                });
            },

            updateUI() {
                document.querySelectorAll('.translation-input').forEach(input => {
                    input.value = this.formData[this.activeLang]?.[input.getAttribute('data-field')] || '';
                });
                Object.keys(this.quillInstances).forEach(key => {
                    this.quillInstances[key].root.innerHTML = this.formData[this.activeLang]?.[key] || '';
                });
                Object.keys(this.fields).forEach(key => {
                    const bgEl = document.getElementById(`original-bg-${key}`);
                    if (bgEl) bgEl.innerHTML = this.formData['bg']?.[key] || '—';
                });
            },

            readCurrentUI() {
                if (!this.formData[this.activeLang]) this.formData[this.activeLang] = {};

                document.querySelectorAll('.translation-input').forEach(input => {
                    this.formData[this.activeLang][input.getAttribute('data-field')] = input.value;
                });
                Object.keys(this.quillInstances).forEach(key => {
                    this.formData[this.activeLang][key] = this.quillInstances[key].root.innerHTML;
                });
            },

            async magicTranslate() {
                if (this.progress === 100) {
                    this.currentLangIndex = 0;
                    this.progress = 0;
                }

                this.isPaused = false;
                this.loading = true;
                this.readCurrentUI();

                const targetLangs = this.langCodes.filter(l => l !== 'bg');

                for (let i = this.currentLangIndex; i < targetLangs.length; i++) {
                    // Проверка за пауза (ръчна или от затваряне на прозореца)
                    if (this.isPaused) {
                        this.loading = false;
                        return;
                    }

                    this.currentLangIndex = i;
                    const lang = targetLangs[i];
                    this.progress = Math.round((i / targetLangs.length) * 100);
                    this.currentAction = `Превод: ${lang.toUpperCase()}`;

                    // Превключваме таба визуално само ако прозорецът е отворен
                    if (this.isShow) {
                        this.switchLanguage(lang);
                    } else {
                        this.activeLang = lang; // Тихо превключване на заден план
                    }

                    for (const key of Object.keys(this.fields)) {
                        // Отново проверка за пауза преди всяко поле
                        if (this.isPaused) {
                            this.loading = false;
                            return;
                        }

                        const original = this.formData['bg']?.[key];
                        if (original && (!this.formData[lang][key] || this.formData[lang][key] === '<p><br></p>')) {
                            const translated = await this.translateText(original, lang);
                            if (translated) {
                                this.formData[lang][key] = translated;
                                if (this.isShow) this.updateUI();
                            }
                            await new Promise(r => setTimeout(r, 800));
                        }
                    }
                }

                this.loading = false;
                this.progress = 100;
                this.currentAction = 'Готово';
                if (new URLSearchParams(window.location.search).get('live') === '1') this.save();
            },

            async translateText(text, targetLang) {
                try {
                    const res = await fetch(`https://translate.googleapis.com/translate_a/single?client=gtx&sl=bg&tl=${targetLang}&dt=t&q=${encodeURIComponent(text)}`);
                    const data = await res.json();
                    return data[0].map(part => part[0]).join('');
                } catch (e) {
                    return null;
                }
            },

            async save() {
                this.readCurrentUI();
                this.loading = true;
                try {
                    const res = await fetch(`${this.saveEndpoint}/${this.entityType}/${this.entityId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(this.formData)
                    });
                    const result = await res.json();
                    if (res.ok && result.success) {
                        const urlParams = new URLSearchParams(window.location.search);
                        if (urlParams.get('live') === '1' && this.nextId) {
                            window.location.href = `${this.redirectBase}/${this.nextId}?live=1`;
                        } else {
                            window.location.reload();
                        }
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    this.loading = false;
                }
            }
        }));

        Alpine.data('translatableForm', (config) => ({
            id: config.id,
            entity: config.entity,
            // Подсигуряваме, че fields винаги е масив, дори ако config е празен
            fieldKeys: config.fields || [],
            storedTranslations: config.translations || {},

            init() {
                if (new URLSearchParams(window.location.search).get('live') === '1') {
                    setTimeout(() => this.openTranslation(), 500);
                }
            },

            openTranslation() {
                const bgData = {};

                // Използваме защитения fieldKeys
                this.fieldKeys.forEach(key => {
                    // Търсим елемента по name атрибут
                    const el = document.querySelector(`[name="${key}"]`);

                    if (el) {
                        // Търсим Quill редактор в същия контейнер
                        const parent = el.closest('.space-y-2, .space-y-6, .mt-4') || el.parentElement;
                        const editor = parent ? parent.querySelector('.ql-editor') : null;

                        if (editor) {
                            bgData[key] = (editor.innerHTML !== '<p><br></p>') ? editor.innerHTML : '';
                        } else {
                            bgData[key] = el.value;
                        }
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
<?php
$text = $text ?? 'Запазване';
$isActive = $is_active ?? true;
$uid = uniqid();
?>

<div class="fixed bottom-0 left-0 right-0 md:left-64 bg-white/90 backdrop-blur-md border-t border-gray-200 p-4 mb-0 z-40 shadow-[0_-5px_20px_rgba(0,0,0,0.05)]">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        
        <div class="hidden sm:flex items-center gap-3">
            <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-100 rounded-full border border-gray-200">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full <?= $isActive ? 'bg-green-400' : 'bg-red-400' ?> opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 <?= $isActive ? 'bg-green-500' : 'bg-red-500' ?>"></span>
                </span>
                <span class="text-xs font-medium text-gray-600 uppercase tracking-wider">
                    <?= $isActive ? 'Активен' : 'Скрит' ?>
                </span>
            </div>
            <span class="text-xs text-gray-400 hidden lg:inline">Използвайте <kbd class="px-1 py-0.5 bg-white border border-gray-300 rounded shadow-sm">Ctrl + S</kbd> за бързо записване</span>
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <button type="submit" id="submit-btn-<?= $uid ?>" class="flex-1 sm:flex-none bg-primary-dark hover:brightness-110 text-white px-10 py-3 rounded-xl text-lg font-semibold shadow-lg shadow-primary/20 transition flex items-center justify-center min-w-50 disabled:opacity-70">
                <svg class="btn-spinner hidden animate-spin mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="btn-text"><?= $text ?></span>
            </button>
        </div>
    </div>
</div>

<div class="h-24"></div>

<script>
    (function() {
        const btn = document.getElementById('submit-btn-<?= $uid ?>');
        if (!btn) return;
        const form = btn.closest('form');

        form.addEventListener('submit', function() {
            if (!form.checkValidity()) return;
            btn.disabled = true;
            btn.querySelector('.btn-text').innerText = 'Записване...';
            btn.querySelector('.btn-spinner').classList.remove('hidden');
        });

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                form.requestSubmit();
            }
        });
    })();
</script>
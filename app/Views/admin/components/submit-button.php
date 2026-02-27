<?php
$text = $text ?? 'Запазване';
$class = $class ?? 'bg-blue-600 hover:bg-opacity-90';
$uid = uniqid();
?>

<div class="pt-5 border-t border-gray-100 flex gap-4">
    <button type="submit" id="submit-btn-<?= $uid ?>" class="<?= $class ?> text-white px-10 py-3 rounded-xl text-lg shadow-lg transition flex items-center justify-center min-w-40 disabled:opacity-70 disabled:cursor-not-allowed">
        <svg class="btn-spinner hidden animate-spin mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>

        <span class="btn-text"><?= $text ?></span>
    </button>
</div>

<script>
(function() {
    const btn = document.getElementById('submit-btn-<?= $uid ?>');
    if (!btn) return;

    const form = btn.closest('form');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                return;
            }

            const textSpan = btn.querySelector('.btn-text');
            const spinner = btn.querySelector('.btn-spinner');

            btn.disabled = true;

            if (textSpan) textSpan.innerText = 'Обработка...';
            if (spinner) spinner.classList.remove('hidden');

            btn.classList.add('brightness-90');
        });
    }
})();
</script>

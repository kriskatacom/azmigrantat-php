<?php if (isset($_SESSION['flash'])): 
    $flash = $_SESSION['flash'];
    $bgColor = $flash['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-red-50 border-red-200 text-red-800';
    $iconColor = $flash['type'] === 'success' ? 'text-emerald-400' : 'text-red-400';
    unset($_SESSION['flash']);
?>
<div id="flash-message" class="fixed bottom-5 left-5 z-200 max-w-md transform transition-all duration-500 translate-x-0">
    <div class="<?= $bgColor ?> border rounded-2xl p-4 shadow-xl flex items-start gap-3">
        <div class="<?= $iconColor ?> shrink-0 mt-0.5">
            <?php if ($flash['type'] === 'success'): ?>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <?php else: ?>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <?php endif; ?>
        </div>
        
        <div class="flex-1">
            <p class="text-sm font-bold leading-tight">
                <?= $flash['type'] === 'success' ? 'Готово!' : 'Грешка!' ?>
            </p>
            <p class="text-xs mt-1 opacity-90"><?= $flash['message'] ?></p>
        </div>

        <button onclick="document.getElementById('flash-message').remove()" class="text-gray-400 hover:text-gray-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
</div>

<script>
    setTimeout(() => {
        const msg = document.getElementById('flash-message');
        if (msg) {
            msg.style.opacity = '0';
            msg.style.transform = 'translateX(-100px)';
            setTimeout(() => msg.remove(), 500);
        }
    }, 5000);
</script>
<?php endif; ?>
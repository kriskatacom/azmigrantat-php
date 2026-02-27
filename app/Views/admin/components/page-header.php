<?php
$title = $title ?? 'Списък';
$button_label = $button_label ?? null;
$button_url = $button_url ?? '#';
?>

<div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
    <h3 class="font-bold text-gray-800 text-lg"><?= htmlspecialchars($title) ?></h3>
    
    <?php if ($button_label): ?>
        <a href="<?= $button_url ?>" 
           class="bg-[#1e293b] text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-700 transition flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <?= htmlspecialchars($button_label) ?>
        </a>
    <?php endif; ?>
</div>

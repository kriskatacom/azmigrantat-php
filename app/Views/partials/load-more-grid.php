<?php

use App\Core\View;
use App\Services\HelperService;

$limit = $limit ?? 8;
$card_path = $card_path ?? 'partials';
$base_url = $base_url ?? '';
$containerId = 'grid-' . uniqid();
$btnId = 'btn-' . uniqid();
?>

<section class="mx-auto py-5">
    <?php if (!empty($title)): ?>
        <h2 class="text-3xl font-bold text-center mb-6"><?= htmlspecialchars($title) ?></h2>
    <?php endif; ?>

    <div id="<?= $containerId ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4">
        <?php foreach ($items as $index => $item): ?>
            <div class="grid-item <?= $index >= $limit ? 'hidden' : '' ?>">
                <?php 
                $itemKey = str_replace('-card', '', $card_name);
                
                View::component($card_name, $card_path, [
                    $itemKey   => $item,
                    'base_url' => $base_url
                ]); 
                ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (count($items) > $limit): ?>
        <div class="flex justify-center mt-5 md:mt-10">
            <button id="<?= $btnId ?>" class="bg-white border border-gray-200 text-gray-800 font-semibold px-10 py-3 rounded-lg shadow-sm hover:bg-gray-50 transition-all cursor-pointer">
                <?= HelperService::trans('load_more') ?? 'Зареждане на още' ?>
            </button>
        </div>
    <?php endif; ?>
</section>

<script>
    (function() {
        const btn = document.getElementById('<?= $btnId ?>');
        const limit = <?= $limit ?>;
        
        if (btn) {
            btn.addEventListener('click', function() {
                const container = document.getElementById('<?= $containerId ?>');
                const hiddenItems = container.querySelectorAll('.grid-item.hidden');

                for (let i = 0; i < limit && i < hiddenItems.length; i++) {
                    hiddenItems[i].classList.remove('hidden');
                    hiddenItems[i].classList.add('animate-fade-in');
                }

                if (container.querySelectorAll('.grid-item.hidden').length === 0) {
                    btn.parentElement.remove();
                }
            });
        }
    })();
</script>

<style>
    @keyframes gridFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: gridFadeIn 0.5s ease forwards;
    }
</style>

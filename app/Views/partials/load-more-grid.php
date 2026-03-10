<?php

use App\Core\View;
use App\Services\HelperService;

$limit = $limit ?? 8;
$card_path = $card_path ?? 'partials';
$card_name = $card_name ?? 'item-card';
$style = $style ?? 'grid';
$base_url = $base_url ?? '';
$link_key = $link_key ?? 'slug';

$show_search = $show_search ?? true;

$containerId = 'grid-' . uniqid();
$btnId = 'btn-' . uniqid();
$searchId = 'search-' . uniqid();

$gridCols = ($style === 'list') ? 'lg:grid-cols-2 xl:grid-cols-3' : 'lg:grid-cols-4';
?>

<section class="mx-auto py-5">
    <?php if (!empty($title)): ?>
        <h2 class="text-3xl font-bold text-center mb-6"><?= htmlspecialchars($title) ?></h2>
    <?php endif; ?>

    <?php if ($show_search): ?>
        <div class="max-w-md mx-auto mb-5 px-4">
            <div class="relative">
                <input type="text"
                    id="<?= $searchId ?>"
                    placeholder="<?= HelperService::trans('search_placeholder') ?? 'Търсене...' ?>"
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div id="<?= $containerId ?>" class="grid grid-cols-1 md:grid-cols-2 <?= $gridCols ?> gap-6 px-4">
        <?php foreach ($items as $index => $item): ?>
            <?php
            $rawLink = trim($item['link'] ?? $item['slug'] ?? '');
            $isExternal = preg_match('/^(http|https|\/\/)/i', $rawLink);
            $finalUrl = $isExternal ? $rawLink : rtrim($base_url, '/') . '/' . ltrim($rawLink, '/');
            $searchLabel = htmlspecialchars(mb_strtolower(($item['name'] ?? '') . ' ' . $rawLink));
            ?>

            <div class="grid-item <?= $index >= $limit ? 'hidden' : '' ?>" data-label="<?= $searchLabel ?>">
                <?php
                View::component($card_name, $card_path, [
                    'item'        => array_merge($item, ['final_url' => $finalUrl]),
                    'is_external' => $isExternal,
                    'style'       => $style,
                    'link_key'    => $link_key
                ]);
                ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (count($items) > $limit): ?>
        <div id="btn-container-<?= $btnId ?>" class="flex justify-center mt-5 md:mt-10">
            <button id="<?= $btnId ?>" class="bg-white border border-gray-200 text-gray-800 font-semibold px-10 py-3 rounded-lg shadow-sm hover:bg-gray-50 transition-all cursor-pointer">
                <?= HelperService::trans('load_more') ?? 'Зареждане на още' ?>
            </button>
        </div>
    <?php endif; ?>
</section>

<script>
    (function() {
        const container = document.getElementById('<?= $containerId ?>');
        const btn = document.getElementById('<?= $btnId ?>');
        const searchInput = document.getElementById('<?= $searchId ?>');
        const limit = <?= (int)$limit ?>;

        if (btn) {
            btn.addEventListener('click', function() {
                const hiddenItems = container.querySelectorAll('.grid-item.hidden');

                for (let i = 0; i < limit && i < hiddenItems.length; i++) {
                    hiddenItems[i].classList.remove('hidden');
                    hiddenItems[i].style.opacity = '0';
                    hiddenItems[i].animate([{
                        opacity: 0
                    }, {
                        opacity: 1
                    }], {
                        duration: 300,
                        fill: 'forwards'
                    });
                }

                if (container.querySelectorAll('.grid-item.hidden').length === 0) {
                    btn.parentElement.style.display = 'none';
                }
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const term = e.target.value.toLowerCase().trim();
                const allItems = container.querySelectorAll('.grid-item');
                const btnContainer = document.getElementById('btn-container-<?= $btnId ?>');

                if (term.length > 0) {
                    if (btnContainer) {
                        btnContainer.classList.add('opacity-0', 'pointer-events-none');
                        setTimeout(() => btnContainer.style.display = 'none', 300);
                    }
                    allItems.forEach(item => {
                        const label = item.getAttribute('data-label');
                        item.classList.toggle('hidden', !label.includes(term));
                    });
                } else {
                    allItems.forEach((item, index) => {
                        item.classList.toggle('hidden', index >= limit);
                    });
                    if (btnContainer && allItems.length > limit) btnContainer.style.display = 'flex';
                }
            });
        }
    })();
</script>

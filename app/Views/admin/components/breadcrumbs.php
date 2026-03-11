<?php

use App\Services\HelperService;

$items = $items ?? [];
?>

<nav class="flex mb-2" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2 text-sm">
        <li>
            <a href="/admin/dashboard" class="text-gray-400 hover:text-primary-dark transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                </svg>
                
                <?= HelperService::trans('home') ?>
            </a>
        </li>

        <?php foreach ($items as $index => $item): ?>
            <li class="flex items-center">
                <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>

                <?php if (isset($item['url']) && $index < count($items) - 1): ?>
                    <a href="<?= $item['url'] ?>" class="ml-2 text-gray-400 hover:text-primary-dark transition-colors font-medium">
                        <?= htmlspecialchars($item['label']) ?>
                    </a>
                <?php else: ?>
                    <span class="ml-2 text-gray-600 font-semibold select-none">
                        <?= htmlspecialchars($item['label']) ?>
                    </span>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>

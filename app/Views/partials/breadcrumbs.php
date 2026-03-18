<?php

use App\Services\HelperService;
$hasLinkClasses = $hasLinkClasses ?? 'text-primary-light';
$noLinkClasses = $noLinkClasses ?? 'text-white';
?>

<div class="px-4 md:mt-2 flex flex-wrap gap-2 text-sm justify-center items-center">
    <a href="<?= HelperService::url('/') ?>" class="md:text-lg hover:underline <?= $hasLinkClasses ?>">
        <?= HelperService::trans('home') ?>
    </a>
    
    <?php foreach ($items as $index => $item): ?>
        <?php if (empty($item['label'])) continue; ?>

        <span class="md:text-lg <?= $hasLinkClasses ?>">/</span>
        
        <?php 
            $hasLink = !empty($item['href']) && $item['href'] !== '#';
        ?>

        <?php if (!$hasLink): ?>
            <span class="md:text-lg font-medium <?= $noLinkClasses ?>">
                <?= htmlspecialchars($item['label']) ?>
            </span>
        <?php else: ?>
            <a href="<?= htmlspecialchars(HelperService::url($item['href'])) ?>" class="md:text-lg hover:underline <?= $hasLinkClasses ?>">
                <?= htmlspecialchars($item['label']) ?>
            </a>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
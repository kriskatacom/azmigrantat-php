<div class="px-4 md:mt-2 flex flex-wrap gap-2 text-sm justify-center items-center">
    <a href="/" class="text-primary-light md:text-lg hover:underline">Начало</a>
    
    <?php foreach ($items as $index => $item): ?>
        <?php if (empty($item['label'])) continue; ?>

        <span class="text-primary-light md:text-lg">/</span>
        
        <?php 
            $hasLink = !empty($item['href']) && $item['href'] !== '#';
        ?>

        <?php if (!$hasLink): ?>
            <span class="md:text-lg text-white font-medium">
                <?= htmlspecialchars($item['label']) ?>
            </span>
        <?php else: ?>
            <a href="<?= htmlspecialchars($item['href']) ?>" class="text-primary-light md:text-lg hover:underline">
                <?= htmlspecialchars($item['label']) ?>
            </a>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<div class="px-4 flex flex-wrap gap-2 text-sm justify-center items-center">
    <a href="/" class="text-primary-light md:text-lg hover:underline">Начало</a>
    <?php foreach ($items as $index => $item): ?>
        <span class="text-primary-light md:text-lg">/</span>
        <?php if ($index === array_key_last($items)): ?>
            <span class="md:text-lg"><?= htmlspecialchars($item['label']) ?></span>
        <?php else: ?>
            <a href="<?= $item['url'] ?>" class="text-primary-light md:text-lg hover:underline">
                <?= htmlspecialchars($item['label']) ?>
            </a>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-5 <?= $attributes ?? '' ?>">
    <?php if (isset($title)): ?>
        <h4 class="text-xs font-bold uppercase tracking-wider border-b pb-2 text-primary-dark">
            <?= htmlspecialchars($title) ?>
        </h4>
    <?php endif; ?>

    <div class="card-content">
        <?= $slot ?>
    </div>
</div>

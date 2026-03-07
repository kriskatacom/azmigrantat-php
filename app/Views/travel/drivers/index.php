<?php

use App\Core\View;
?>

<main class="min-h-screen bg-gray-100">
    <?= View::component('show-banner', 'partials', ['banner' => $banner]) ?>

    <div class="max-w-5xl mx-auto my-5 md:my-10">
        <?php if (!empty($drivers)): ?>
            <div class="grid md:grid-cols-2 gap-5">
                <?php foreach ($drivers as $driver): ?>
                    <?= View::component('driver-card', 'travel/shared-travel/components', ['driver' => $driver]) ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="col-span-full text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                <i class="fas fa-car-side text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 italic">В момента няма активни обяви за пътувания.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

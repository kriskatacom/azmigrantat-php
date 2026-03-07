<?php

use App\Core\View;
?>

<main class="min-h-screen bg-gray-100">
    <?= View::component('show-banner', 'partials', ['banner' => $banner]) ?>
    <?= View::component('city-search', 'travel/shared-travel/components', ['citiesJson' => $citiesJson]) ?>

    <section class="mt-5 md:mt-10">
        <h2 class="text-center text-xl md:text-2xl font-semibold">Шофьори</h2>

        <div class="max-w-5xl mx-auto px-5 mt-5 md:mt-10 md:px-0 grid md:grid-cols-2 gap-5">
            <?php if (!empty($drivers)): ?>
                <?php foreach ($drivers as $driver): ?>
                    <?= View::component('driver-card', 'travel/shared-travel/components', ['driver' => $driver]) ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200">
                    <i class="fas fa-car-side text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500 italic">В момента няма активни обяви за пътувания.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
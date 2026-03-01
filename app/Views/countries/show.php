<?php

use App\Core\View;
?>

<section class="relative h-125 md:h-150 flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?= $country['image_url'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($country['name']) ?>">
        <div class="absolute inset-0 bg-black/60"></div>
    </div>

    <div class="relative z-10 container mx-auto px-5 text-center text-white">
        <h1 class="text-4xl md:text-6xl font-bold mb-6 italic">
            <?= htmlspecialchars($country['name']) ?>
        </h1>
        <div class="max-w-3xl mx-auto text-lg md:text-xl font-medium leading-relaxed mb-8">
            <?= $country['excerpt'] ?>
        </div>
        <a href="#elements-grid" class="inline-block px-8 py-3 bg-white text-gray-900 rounded-lg font-bold hover:bg-gray-100 transition-colors">
            Научете повече
        </a>
    </div>
</section>

<main>
    <?php
    View::component('load-more-grid', 'partials', [
        'items'     => $elements,
        'base_url'  => $country['slug'] . '/',
        'card_name' => 'country-card',
        'limit'     => 8,
    ]); 
    ?>
</main>
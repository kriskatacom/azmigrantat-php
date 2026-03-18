<?php

use App\Core\View;
use App\Services\HelperService;
?>

<section class="relative h-180 md:h-200 flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <picture>
            <?php if (!empty($country['image_mobile_url'])): ?>
                <source srcset="<?= $country['image_mobile_url'] ?>" media="(max-width: 767px)">
            <?php endif; ?>

            <img src="<?= $country['image_url'] ?>"
                class="w-full h-full object-cover"
                alt="<?= htmlspecialchars($country['name']) ?>">
        </picture>
    </div>

    
    <div class="w-full h-full flex flex-col justify-between relative z-10">
        <div class="w-full h-full flex justify-center items-center relative z-10 container mx-auto px-5 text-center text-white">
            <h1 class="text-[55px] md:text-6xl lg:text-7xl font-bold mb-15 py-3 px-5 w-fit mx-auto mt-50 italic rounded-xl font-serif [text-shadow:0_4px_8px_rgba(0,0,0,0.8)] bg-gray-200/30">
                <?= HelperService::getTranslation($country, 'name') ?>
            </h1>
        </div>
        
        <div class="mt-10 mb-5 ml-5">
            <a href="#" class="inline-block border-2 border-white text-white bg-gray-100/20 font-semibold px-5 py-2 rounded-md text-xl [text-shadow:0_2px_4px_rgba(0,0,0,0.8)] hover:bg-white hover:text-black hover:text-shadow-none transition-all duration-300 shadow-2xl">
                Научете повече
            </a>
        </div>
    </div>
</section>

<div class="bg-blue-100 w-full">
    <?php View::component('load-more-grid', 'partials', [
        'items'     => $elements,
        'base_url'  => $country['slug'] . '/',
        'card_name' => 'item-card',
        'limit'     => 8,
        'show_search' => false,
    ]); ?>
</div>
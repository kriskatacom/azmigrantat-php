<?php

use App\Services\HelperService;
use App\Core\View;
?>

<div class="mt-5">
    <?php if (!empty($offers)): ?>
        <div class="md:container md:mx-auto mt-5">
            <h2 class="text-2xl font-bold uppercase max-md:border-l-4 max-md:border-primary max-md:pl-4 mb-2 md:text-center">
                <?= HelperService::trans('promotions_and_ads_in') ?> <?= htmlspecialchars($category['name']) ?>
            </h2>

            <?php ob_start(); ?>
            <?php foreach ($offers as $offer): ?>
                <div class="w-screen md:w-[320px] shrink-0 px-2 py-4">
                    <div class="bg-primary-dark rounded-2xl overflow-hidden border border-white/10 shadow-lg flex flex-col h-full">
                        <div class="relative h-40 overflow-hidden">
                            <img src="<?= HelperService::getImage($offer['image_url']) ?>" class="w-full h-full object-cover grayscale-30 hover:grayscale-0 transition-all duration-500">
                        </div>
                        <div class="p-5 flex flex-col grow text-white">
                            <h3 class="font-bold text-base mb-1 line-clamp-1"><?= htmlspecialchars($offer['name']) ?></h3>
                            <p class="text-primary-light mb-3 uppercase text-xs font-semibold tracking-wider">
                                <?= htmlspecialchars($offer['company_name']) ?>
                                <?php if (!empty($offer['city_name'])): ?>
                                    <span class="text-white/30 ml-1 font-normal">| <?= htmlspecialchars($offer['city_name']) ?></span>
                                <?php endif; ?>
                            </p>
                            <a href="<?= $base_url . $offer['company_slug'] ?>" class="btn-primary hover:bg-primary-light hover:text-primary-dark text-center block">Детайли</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php $my_content = ob_get_clean();
            View::component('ping-pong-slider', 'partials', ['unique_id' => 'offers_slider', 'content' => $my_content]); ?>
        </div>
    <?php endif; ?>
</div>

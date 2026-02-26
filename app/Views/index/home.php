<?php

use App\Services\HelperService;
?>
<section class="relative h-screen min-h-125 w-full flex items-center justify-center">
    <img src="/assets/images/azmigrantat-hero-background.webp"
        class="absolute inset-0 w-full h-full object-cover"
        alt="<?= HelperService::trans('hero_alt_text') ?>"
        title="<?= HelperService::trans('hero_alt_text') ?>"
    >

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="relative z-10 text-center px-4">
        <h1 class="text-white text-3xl md:text-5xl lg:text-6xl font-bold uppercase tracking-wider leading-tight">
            <?= HelperService::trans('hero_title_part1') ?> <br>
            <?= HelperService::trans('hero_title_part2') ?>
            <span class="text-primary-light">"<?= HelperService::trans('home') ?>"</span>!
        </h1>

        <div class="mt-8">
            <a href="/travel" class="bg-primary-light text-primary-dark font-bold px-8 py-3 rounded-full hover:bg-white transition-all">
                <?= HelperService::trans('hero_button') ?>
            </a>
        </div>
    </div>
</section>
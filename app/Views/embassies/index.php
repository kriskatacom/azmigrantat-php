<?php

use App\Core\View;
use App\Services\HelperService;

$translatedCountryName = HelperService::getTranslation($country, 'name', 'country');
?>

<section class="relative w-full h-120 md:h-140 lg:h-140 overflow-hidden">
    <img src="<?= $embassyElement['image_url'] ?>"
        class="absolute inset-0 w-full h-full object-cover object-center"
        alt="<?= htmlspecialchars($translatedCountryName) ?>">

    <div class="relative h-full flex items-end pb-5 justify-center text-center text-white">
        <div class="container mx-auto px-4 z-20">

            <h1 class="text-2xl md:text-4xl xl:text-5xl font-bold uppercase tracking-wide mb-2 drop-shadow-lg bg-gray-200/30 py-2 px-5 w-fit mx-auto font-serif italic">
                <?= HelperService::trans('embassies_in') ?> <?= htmlspecialchars($translatedCountryName) ?>
            </h1>

            <div class="w-full max-w-2xl mx-auto mt-2 px-4">
                <form action="<?= '/' . $country['slug'] . '/embassies' ?>" method="GET" class="relative group">
                    <input type="text"
                        name="search"
                        placeholder="<?= HelperService::trans('search_embassies') ?>..."
                        class="w-full 
                      /* Мобилни настройки */
                      pl-4 pr-12 py-2 text-sm rounded-md 
                      /* Десктоп настройки (md:) */
                      md:py-4 md:pl-6 md:pr-20 md:text-xl md:rounded-lg
                      bg-white/90 backdrop-blur-md text-gray-900 shadow-2xl focus:outline-none focus:ring-4 focus:ring-primary-light/50 transition-all placeholder:text-gray-500">
                    <button type="submit"
                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-primary-dark hover:bg-black text-white 
                       /* Мобилен бутон */
                       p-1.5 rounded-md 
                       /* Десктоп бутон */
                       md:p-3 md:right-3 md:rounded-lg
                       transition-colors shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 md:h-7 md:w-7"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

<div class="flex justify-center mt-5">
    <?php View::component('breadcrumbs', 'partials', [
        'items' => [
            [
                'label' => $translatedCountryName,
                'href' => '/' . $country['slug']
            ],
            [
                'label' => HelperService::trans('embassies'),
                'href' => ''
            ]
        ],
        'hasLinkClasses' => 'text-primary-dark',
        'noLinkClasses' => 'text-black'
    ]); ?>
</div>

<main>
    <?php View::component('load-more-grid', 'partials', [
        'items'     => $embassies,
        'card_name' => 'item-card',
        'base_url'  => '/' . $country['slug'] . '/embassies/',
        'limit'     => 8,
        'show_search'     => false,
    ]); ?>
</main>
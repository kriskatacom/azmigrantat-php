<?php

use App\Core\View;
use App\Services\HelperService;

$translatedCountryName = HelperService::getTranslation($country, 'name', 'country');
?>

<section class="relative w-full h-110 md:h-130 lg:h-140 overflow-hidden group">
    <img src="<?= $backgroundImage ?>"
        class="absolute inset-0 w-full h-full object-cover object-center transition-transform duration-700 group-hover:scale-105"
        alt="<?= htmlspecialchars($translatedCountryName) ?>">

    <div class="absolute inset-0 bg-linear-to-t from-black/70 via-transparent to-transparent z-10"></div>

    <div class="relative h-full flex items-end pb-6 justify-center z-20">
        <div class="container mx-auto px-4">

            <div class="flex flex-col items-center">

                <div class="backdrop-blur-md bg-black/30 border border-white/10 px-4 py-2 rounded-lg shadow-xl md:px-6 md:py-2 mb-1">
                    <h1 class="text-lg md:text-2xl lg:text-3xl text-center font-bold uppercase text-white font-serif italic drop-shadow-md">
                        <?= $title ?>
                    </h1>
                </div>

                <?php if (isset($slot)): ?>
                    <?= $slot ?>
                <?php endif; ?>

                <div class="w-full max-w-sm md:max-w-lg lg:max-w-xl px-4">
                    <form action="<?= $formAction ?>" method="GET" class="relative group/form">
                        <input type="text"
                            name="search"
                            value="<?= htmlspecialchars($searchValue ?? '') ?>"
                            placeholder="<?= HelperService::trans($placeholderKey) ?>..."
                            class="w-full pl-4 pr-12 py-2 text-sm rounded-md md:py-4 md:pl-6 md:pr-20 md:text-xl md:rounded-lg bg-white/90 backdrop-blur-md text-gray-900 shadow-2xl focus:outline-none focus:ring-4 focus:ring-primary-light/50 transition-all placeholder:text-gray-500">
                        <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 bg-primary-dark hover:bg-black text-white p-1.5 rounded-md md:p-2 md:right-2 transition-all duration-300 shadow-md">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4 md:h-5 md:w-5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<div class="flex justify-center -mt-4 relative z-30">
    <div class="bg-white px-4 py-1.5 rounded-lg shadow-lg border border-gray-100 scale-90 md:scale-100">
        <?php View::component('breadcrumbs', 'partials', [
            'items' => $breadcrumbs,
            'hasLinkClasses' => 'text-primary-dark text-base font-medium hover:underline',
            'noLinkClasses' => 'text-gray-400 text-base italic',
            'containerClasses' => 'md:mt-2'
        ]); ?>
    </div>
</div>
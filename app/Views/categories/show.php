<?php

use App\Core\View;
use App\Services\HelperService;

?>


<?php $activeScope = $_GET['scope'] ?? ''; ?>

<?php if ($category): ?>
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
            <?php
            $my_content = ob_get_clean();

            View::component('ping-pong-slider', 'partials', [
                'unique_id' => 'offers_slider',
                'content'   => $my_content
            ]);
            ?>
        </div>
    <?php else: ?>
        <div class="md:container md:mx-auto my-5 px-5">
            <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-3xl p-10 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-calendar-day text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">
                    <?= $activeScope ? 'Няма активни обяви за избрания филтър' : 'Няма нови обяви за днес' ?>
                </h3>
                <p class="text-gray-500 max-w-md mx-auto">
                    <?php if ($activeScope): ?>
                        Опитайте да изчистите филтрите или проверете друга локация, за да видите актуалните предложения.
                    <?php else: ?>
                        В момента няма нови промоции в категория <strong><?= htmlspecialchars($category['name']) ?></strong> за последните 24 часа. Проверете филтрите за целия месец по-долу.
                    <?php endif; ?>
                </p>
            </div>
        </div>
    <?php endif; ?>

    <div class="flex flex-wrap items-center justify-center gap-5 px-5 mb-5">
        <?php
        $cityBtnClasses = "relative group flex items-center gap-3 md:gap-5 px-5 py-4 md:px-8 md:py-3 rounded-2xl border-2 transition-all duration-500 no-underline w-full md:w-auto ";
        $cityBtnClasses .= ($activeScope === 'city') ? "bg-primary-dark border-primary text-white shadow-lg" : "bg-white/5 border-gray-200 text-gray-600 hover:border-primary hover:text-primary hover:bg-white";

        $countryBtnClasses = "relative group flex items-center gap-3 md:gap-5 px-5 py-4 md:px-8 md:py-3 rounded-2xl border-2 transition-all duration-500 no-underline w-full md:w-auto ";
        $countryBtnClasses .= ($activeScope === 'country') ? "bg-primary-dark border-primary text-white shadow-lg" : "bg-white/5 border-gray-200 text-gray-600 hover:border-primary hover:text-primary hover:bg-white";
        ?>

        <a href="?scope=city" class="<?= $cityBtnClasses ?>">
            <div class="flex items-center justify-center w-12 h-12 md:w-10 md:h-10 shrink-0 rounded-full <?= $activeScope === 'city' ? 'bg-white/20' : 'bg-gray-100 group-hover:bg-primary/10' ?>">
                <i class="fa-solid fa-location-dot <?= $activeScope === 'city' ? 'text-white' : 'text-gray-500 group-hover:text-primary' ?>"></i>
            </div>
            <div class="flex flex-col text-left">
                <span class="text-[10px] md:text-[9px] uppercase tracking-[0.15em] font-bold opacity-70">Филтър: Месец</span>
                <span class="text-base md:text-sm lg:text-base font-extrabold whitespace-nowrap"><?= htmlspecialchars($cityName) ?></span>
            </div>
        </a>

        <a href="?scope=country" class="<?= $countryBtnClasses ?>">
            <div class="flex items-center justify-center w-12 h-12 md:w-10 md:h-10 shrink-0 rounded-full <?= $activeScope === 'country' ? 'bg-white/10' : 'bg-gray-100 group-hover:bg-primary-dark/10' ?>">
                <i class="fa-solid fa-globe <?= $activeScope === 'country' ? 'text-white' : 'text-gray-500 group-hover:text-primary-dark' ?>"></i>
            </div>
            <div class="flex flex-col text-left">
                <span class="text-[10px] md:text-[9px] uppercase tracking-[0.15em] font-bold opacity-70">Филтър: Месец</span>
                <span class="text-base md:text-sm lg:text-base font-extrabold whitespace-nowrap"><?= htmlspecialchars($countryName) ?></span>
            </div>
        </a>

        <?php if ($activeScope): ?>
            <div class="w-full flex justify-center">
                <a href="<?= strtok($_SERVER["REQUEST_URI"], '?') ?>" class="btn-primary bg-red-500 hover:bg-red-600 border-none">
                    <i class="fa-solid fa-xmark mr-2"></i>Изчисти филтрите
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<section>
    <div class="aspect-video max-h-100 w-full overflow-hidden">
        <?php
        $countryName = HelperService::getTranslation($country, 'name', 'country');
        $cityName    = HelperService::getTranslation($city, 'name', 'city');
        $catName     = !empty($category) ? HelperService::getTranslation($category, 'name', 'category') : '';

        $mainTitle = !empty($category)
            ? "{$catName} " . HelperService::trans('in') . " {$cityName} - {$countryName}"
            : HelperService::trans('info_guide_of') . " {$cityName} - {$countryName}";
        ?>

        <?php if (!empty($category)): ?>
            <img src="<?= $category['image_url'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($mainTitle) ?>">
        <?php else: ?>
            <img src="<?= $city['image_url'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($mainTitle) ?>">
        <?php endif; ?>
    </div>

    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div>
                <h1 class="text-xl md:text-2xl xl:text-3xl font-semibold tracking-wide">
                    <?= htmlspecialchars($mainTitle) ?>
                </h1>
                <?php View::component('breadcrumbs', 'partials', ['items' => $breadcrumbs]); ?>
            </div>
            <div>
                <button class="btn-primary"><?= HelperService::trans('municipal_cities') ?></button>
            </div>
        </div>
    </div>
</section>

<main>
    <?php if (!empty($items)): ?>
        <?php View::component('load-more-grid', 'partials', [
            'items'     => $items,
            'card_name' => 'item-card',
            'base_url'  => $base_url,
            'limit'     => 8,
            'show_excerpt' => true,
        ]); ?>
    <?php else: ?>
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="bg-gray-100 rounded-full p-6 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-gray-700 mb-2"><?= HelperService::trans('no_content_yet') ?></h2>
            <p class="text-gray-500 max-w-sm mb-8">
                <?= HelperService::trans('category_empty_notice') ?> <?= htmlspecialchars($city['name']) ?>.
            </p>
            <a href="/<?= $country['slug'] ?>/cities/<?= $city['slug'] ?>" class="inline-flex items-center gap-2 bg-primary-dark text-white px-6 py-3 rounded-lg font-medium hover:bg-opacity-90 transition-all shadow-md">
                <?php HelperService::icon('left-arrow-icon'); ?>
                <?= HelperService::trans('back_to_city') ?>
            </a>
        </div>
    <?php endif; ?>
</main>

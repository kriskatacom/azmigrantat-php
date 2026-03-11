<?php

use App\Core\View;
use App\Services\HelperService;

?>

<?php if (!empty($offers)): ?>
    <div class="md:container md:mx-auto py-3 overflow-hidden">
        <h2 class="text-2xl font-bold uppercase mb-3 border-l-4 border-primary pl-4">
            <?= HelperService::trans('promotions_and_ads_in') ?> <?= htmlspecialchars($category['name']) ?>
        </h2>

        <div class="swiper categoryOffersSwiper overflow-visible">
            <div class="swiper-wrapper flex ease-linear">
                <?php foreach ($offers as $offer): ?>
                    <div class="swiper-slide h-auto">
                        <div class="bg-primary-dark rounded-2xl overflow-hidden border border-white/10 hover:border-primary/50 transition shadow-lg flex flex-col h-full mx-1">
                            <div class="relative h-48 overflow-hidden">
                                <img src="<?= HelperService::getImage($offer['image_url']) ?>"
                                    class="w-full h-full object-cover">
                            </div>

                            <div class="p-5 flex flex-col grow">
                                <h3 class="text-white font-bold text-lg mb-1 line-clamp-1"><?= htmlspecialchars($offer['name']) ?></h3>
                                <p class="text-primary-light text-xs mb-3 uppercase tracking-tighter">
                                    <?= HelperService::trans('from') ?>: <?= htmlspecialchars($offer['company_name']) ?>
                                </p>

                                <div class="mt-auto">
                                    <a href="<?= $base_url . $offer['company_slug'] ?>"
                                        class="inline-block w-full text-center bg-white/10 hover:bg-white/20 text-white py-2 rounded-lg transition text-sm">
                                        <?= HelperService::trans('view_details') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<section>
    <div class="aspect-video max-h-100 w-full">
        <?php if (!empty($category)): ?>
            <img src="<?= $category['image_url'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($country['name']) ?>">
        <?php else: ?>
            <img src="<?= $city['image_url'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($country['name']) ?>">
        <?php endif; ?>
    </div>
    <div class="bg-primary-dark py-2 md:py-5 xl:py-10 text-white text-center">
        <div class="container mx-auto px-4">
            <h1 class="text-xl md:text-2xl xl:text-3xl font-semibold tracking-wide">
                <?php if (!empty($category)): ?>
                    <?= htmlspecialchars($category['name']) ?> в <?= $city['name']; ?> - <?= $country['name'] ?>
                <?php else: ?>
                    <?= HelperService::trans('info_guide_of') ?> <?= htmlspecialchars($city['name']) ?> - <?= $country['name'] ?>
                <?php endif; ?>
            </h1>
            <?php View::component('breadcrumbs', 'partials', ['items' => $breadcrumbs]); ?>
        </div>
    </div>
</section>

<main>
    <?php if (!empty($items)): ?>
        <?php View::component('load-more-grid', 'partials', [
            'items'     => $items,
            'card_name' => 'item-card',
            'base_url'  => $base_url,
            'limit'     => 8
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.categoryOffersSwiper', {
            loop: true,
            spaceBetween: 20,
            grabCursor: true,
            freeMode: true,
            speed: 5000,
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1.2
                },
                640: {
                    slidesPerView: 2.2
                },
                1024: {
                    slidesPerView: 3.5
                },
                1280: {
                    slidesPerView: 4
                }
            },
            allowTouchMove: true,
        });
    });
</script>

<style>
    .categoryOffersSwiper .swiper-wrapper {
        transition-timing-function: linear !important;
    }
</style>

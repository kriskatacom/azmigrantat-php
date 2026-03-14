<?php

use App\Core\View;
use App\Services\HelperService;

$posts = array_filter($drivers, function ($driver) {
    if (!isset($driver['driver_travel_status']) || $driver['driver_travel_status'] === 'not_traveling') return false;
    $isDep = $driver['driver_travel_status'] === 'departure';
    return !empty($isDep ? ($driver['travel_departure_details'] ?? '') : ($driver['travel_return_details'] ?? '')) ||
        !empty($isDep ? ($driver['travel_departure_image'] ?? '') : ($driver['travel_return_image'] ?? ''));
});

$activeDrivers = array_filter($drivers, function ($driver) {
    return !empty($driver['username']) || !empty($driver['name']);
});

$limit = 2;
?>

<main class="min-h-screen bg-gray-100 pb-20">
    <?= View::component('show-banner', 'partials', ['banner' => $banner]) ?>
    <?= View::component('city-search', 'travel/shared-travel/components', ['citiesJson' => $citiesJson]) ?>

    <section class="mt-5 md:mt-10 w-full overflow-hidden group">
        <h2 class="text-center text-xl md:text-2xl lg:text-3xl font-semibold mb-5">
            <?= HelperService::trans('travel_posts_title') ?>
        </h2>

        <div class="marquee-viewport relative w-full overflow-hidden">
            <div class="marquee-content flex animate-marquee group-hover:pause">

                <?php foreach ($posts as $driver): ?>
                    <div class="card-wrapper shrink-0 px-2 w-screen md:w-[50vw] lg:w-[33.333vw] xl:w-[25vw]">
                        <?= View::component('driver-card', 'travel/drivers/components', ['driver' => $driver, 'type' => 'posts']) ?>
                    </div>
                <?php endforeach; ?>

                <?php foreach ($posts as $driver): ?>
                    <div class="card-wrapper shrink-0 px-2 w-screen md:w-[50vw] lg:w-[33.333vw] xl:w-[25vw]">
                        <?= View::component('driver-card', 'travel/drivers/components', ['driver' => $driver, 'type' => 'posts']) ?>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    </section>

    <section class="mt-10 max-w-5xl mx-auto px-5 md:px-0">
        <h2 class="text-center text-xl md:text-2xl lg:text-3xl font-semibold mb-5">
            <?= HelperService::trans('drivers_list_title') ?>
        </h2>
        <div id="drivers-container" class="grid md:grid-cols-2 gap-5">
            <?php $j = 0;
            foreach ($activeDrivers as $driver): ?>
                <div class="grid-item-driver <?= $j >= $limit ? 'hidden' : '' ?>">
                    <?= View::component('driver-card', 'travel/drivers/components', ['driver' => $driver, 'type' => 'drivers']) ?>
                </div>
            <?php $j++;
            endforeach; ?>
        </div>
        <?php if (count($activeDrivers) > $limit): ?>
            <div class="flex justify-center mt-5">
                <button onclick="loadMore('driver')" id="btn-load-driver" class="bg-white border border-gray-200 text-gray-800 font-semibold px-10 py-3 rounded-lg shadow-sm hover:bg-gray-50 transition-all cursor-pointer">
                    <?= HelperService::trans('show_more') ?>
                </button>
            </div>
        <?php endif; ?>
    </section>
</main>

<script>
    function loadMore(type) {
        const containerId = type === 'post' ? 'posts-container' : 'drivers-container';
        const itemClass = type === 'post' ? '.grid-item-post.hidden' : '.grid-item-driver.hidden';
        const btnId = type === 'post' ? 'btn-load-post' : 'btn-load-driver';

        const container = document.getElementById(containerId);
        const hiddenItems = container.querySelectorAll(itemClass);
        const limit = <?= (int)$limit ?>;

        for (let i = 0; i < limit && i < hiddenItems.length; i++) {
            hiddenItems[i].classList.remove('hidden');
            hiddenItems[i].style.opacity = 0;
            hiddenItems[i].style.transition = 'opacity 0.5s ease-in-out';
            setTimeout(() => {
                hiddenItems[i].style.opacity = 1;
            }, 10);
        }

        if (container.querySelectorAll(itemClass).length === 0) {
            document.getElementById(btnId).parentElement.style.display = 'none';
        }
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swiper = new Swiper('.postsSwiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            centeredSlides: true,

            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },

            breakpoints: {
                640: {
                    slidesPerView: 2,
                    centeredSlides: false,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                    centeredSlides: false,
                }
            },

            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    });
</script>

<style>
    @keyframes marquee {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    .animate-marquee {
        display: flex;
        width: max-content;
        animation: marquee 30s linear infinite;
    }

    .group:hover .animate-marquee {
        animation-play-state: paused;
    }

    .marquee-viewport::before {
        left: 0;
        background: linear-gradient(to right, white, transparent);
    }

    .marquee-viewport::after {
        right: 0;
        background: linear-gradient(to left, white, transparent);
    }
</style>

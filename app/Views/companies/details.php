<?php

use App\Core\View;
use App\Models\User;
use App\Services\HelperService;

$user = User::auth();
?>

<?php View::component('search-hero', 'partials', [
    'country'         => $country,
    'backgroundImage' => $company['image_url'] ?? '',
    'title'           => HelperService::getTranslation($company, 'name', 'company'),
    'breadcrumbs'   => $breadcrumbs,
]); ?>

<div class="container mx-auto px-2 md:px-5 mt-2 md:mt-5">
    <div class="grid grid-cols-2 gap-2 md:gap-5">

        <div x-data="{ isOpen: false }" x-cloak class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl">
                <?php HelperService::icon('bank', 'w-5 h-5 md:w-10 md:h-10'); ?> <?= HelperService::trans('about_company') ?>
            </div>
            <div class="p-2 md:p-5 space-y-3 md:space-y-5 text-xs md:text-base">
                <button @click="$dispatch('open-modal-show-description')" class="flex items-center justify-center gap-2 text-xs md:text-sm bg-primary-dark text-white px-4 py-2 rounded-lg hover:bg-black transition-all w-full md:w-fit mt-auto">
                    <?= HelperService::trans('more_info') ?>
                </button>
                <div class="wysiwyg-text text-gray-700 leading-relaxed text-xs md:text-base line-clamp-6 xl:line-clamp-12 2xl:line-clamp-none">
                    <?= $company['description'] ?>
                </div>
            </div>

            <?php View::component("modal", "partials", [
                'id' => 'show-description',
                'title' => $company['name'],
                'content' => $company['description'],
            ]); ?>
        </div>

        <?php View::component('gallery', 'partials', [
            'images' => $company['additional_images'],
            'title'  => HelperService::trans('gallery'),
            'icon'   => 'images'
        ]); ?>

        <div x-data="{ isOpen: false }" x-cloak class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl">
                <?php HelperService::icon('clock', 'w-5 h-5 md:w-10 md:h-10'); ?>
                <?= HelperService::trans('work_time') ?>
            </div>

            <div class="p-2 md:p-5 space-y-3 md:space-y-5 flex-1 flex flex-col justify-between">
                <?php if (!empty($company['working_time'])): ?>
                    <div class="text-gray-700 leading-relaxed text-xs md:text-base line-clamp-5">
                        <?= $company['working_time'] ?>
                    </div>

                    <button @click="$dispatch('open-modal-show-working-time')" class="flex items-center justify-center gap-2 text-xs md:text-sm bg-primary-dark text-white px-4 py-2 rounded-lg hover:bg-black transition-all w-full md:w-fit mt-auto">
                        <?= HelperService::trans('show_info') ?>
                    </button>

                    <?php View::component("modal", "partials", [
                        'id' => 'show-working-time',
                        'title' => HelperService::trans('work_time'),
                        'content' => $company['working_time'],
                    ]); ?>

                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-4 text-gray-400 italic text-center text-sm md:text-base">
                        <p><?= HelperService::trans('no_working_time_available') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl">
                <?php HelperService::icon('location', 'w-5 h-5 md:w-10 md:h-10'); ?> <?= HelperService::trans('contact_info') ?>
            </div>

            <div class="p-2 md:p-5 space-y-3 md:space-y-5 text-xs md:text-base">
                <?php
                $hasContactInfo =
                    !empty($company['address']) ||
                    !empty($company['phone']) ||
                    !empty($company['email']) ||
                    !empty($company['website_link']);
                ?>

                <?php if ($hasContactInfo): ?>

                    <?php if (!empty($company['address'])): ?>
                        <div class="flex items-center gap-3">
                            <?php HelperService::icon('address', 'w-5 h-5 md:w-10 md:h-10'); ?>
                            <span class="break-all"><?= $company['address'] ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($company['phone'])): ?>
                        <div class="flex items-center gap-3">
                            <?php HelperService::icon('phone', 'w-5 h-5 md:w-10 md:h-10'); ?>
                            <a class="break-all hover:text-primary-dark transition-colors" href="tel:<?= $company['phone'] ?>"><?= $company['phone'] ?></a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($company['email'])): ?>
                        <div class="flex items-center gap-3">
                            <?php HelperService::icon('mail', 'w-5 h-5 md:w-10 md:h-10'); ?>
                            <a class="break-all hover:text-primary-dark transition-colors" href="mailto:<?= $company['email'] ?>"><?= $company['email'] ?></a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($company['website_link'])): ?>
                        <div class="flex items-center gap-3">
                            <?php HelperService::icon('globe-icon', 'w-5 h-5 md:w-10 md:h-10'); ?>
                            <a class="break-all hover:text-primary-dark transition-colors" href="<?= $company['website_link'] ?>" target="_blank"><?= $company['website_link'] ?></a>
                        </div>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="flex flex-col items-center justify-center py-4 text-center text-sm text-gray-400 italic">
                        <p><?= HelperService::trans('no_contact_info_available') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-2 md:px-5">
    <div class="grid md:grid-cols-2 gap-x-2 md:gap-x-5">
        <div class="group flex flex-col bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto mt-5">
            <h3 class="text-center font-semibold my-2 md:my-5 text-xl md:text-2xl"><?= HelperService::trans('services') ?></h3>

            <div class="relative h-auto rounded-xl shadow-md overflow-hidden">
                <?php if (!empty($ads)): ?>
                    <div class="swiper adsSwiper h-full w-full">
                        <div class="swiper-wrapper">
                            <?php foreach ($ads as $ad): ?>
                                <div class="swiper-slide relative group overflow-hidden">
                                    <img src="<?= HelperService::getImage($ad['image_url']) ?>"
                                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        alt="<?= htmlspecialchars($ad['name']) ?>">

                                    <?php if ($ad['name']): ?>
                                        <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/20 to-transparent z-10"></div>
                                        
                                        <h3 class="absolute bottom-0 left-0 z-20 p-4 md:p-6 text-white font-bold text-lg md:text-2xl drop-shadow-md">
                                            <?= htmlspecialchars($ad['name']) ?>
                                        </h3>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                <?php else: ?>
                    <img src="<?= HelperService::getImage($company['ads_image_url'] ?: $company['image_url']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center p-6 text-center">
                        <?php if (!empty($user['id'])): ?>
                            <span class="text-white text-2xl md:text-3xl font-black uppercase drop-shadow-md">
                                <?= HelperService::trans('ads_showcase') ?>
                            </span>
                        <?php else: ?>
                            <div class="space-y-5">
                                <h3 class="text-white text-2xl md:text-3xl font-black uppercase drop-shadow-md">Управлявайте услугите си с лекота</h3>
                                <a href="/auth/login" title="Влизане в профила" class="text-white btn-primary">Влизане в профила</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($user['id']) && $user['id'] === $company['user_id']): ?>
                <div class="text-white text-sm md:text-base leading-relaxed mb-4 grow italic">
                    <?= HelperService::trans('ads_corporate_space') ?>
                </div>
                <a href="/admin/ads" class="flex justify-center btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <?= HelperService::trans('ads_management') ?>
                </a>
            <?php endif; ?>
        </div>

        <div class="group flex flex-col bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto mt-5">
            <h3 class="text-center font-semibold my-2 md:my-5 text-xl md:text-2xl"><?= HelperService::trans('live_offers_and_ads') ?></h3>

            <div class="relative h-auto rounded-xl shadow-md overflow-hidden">
                <?php if (!empty($offers)): ?>
                    <div class="swiper offersSwiper h-full w-full">
                        <div class="swiper-wrapper">
                            <?php foreach ($offers as $ad): ?>
                                <div class="swiper-slide">
                                    <img src="<?= HelperService::getImage($ad['image_url']) ?>"
                                        class="w-full h-auto object-contain"
                                        alt="<?= htmlspecialchars($ad['name']) ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                <?php else: ?>
                    <img src="<?= HelperService::getImage($company['offer_image_url'] ?: $company['image_url']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center p-6 text-center">
                        <?php if (!empty($user['id'])): ?>
                            <span class="text-white text-2xl md:text-3xl font-black uppercase drop-shadow-md">
                                <?= HelperService::trans('ad_display_here') ?>
                            </span>
                        <?php else: ?>
                            <div class="space-y-5">
                                <h3 class="text-white text-2xl md:text-3xl font-black uppercase drop-shadow-md">Управлявайте услугите си с лекота</h3>
                                <a href="/auth/login" title="Влизане в профила" class="text-white btn-primary">Влизане в профила</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($user['id']) && $user['id'] === $company['user_id']): ?>
                <div class="text-white text-sm md:text-base leading-relaxed mb-4 grow italic">
                    <?= HelperService::trans('ad_publish_info') ?>
                </div>
                <a href="/admin/companies/edit/<?= $company['id'] ?>" class="flex justify-center btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <?= HelperService::trans('edit_offer') ?>
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($ads) || !empty($offers)): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const swiperOptions = {
                        effect: 'cube',
                        grabCursor: true,
                        loop: true,
                        observer: true,
                        observeParents: true,
                        cubeEffect: {
                            shadow: true,
                            slideShadows: true,
                            shadowOffset: 20,
                            shadowScale: 0.94,
                        },
                        autoplay: {
                            delay: 3500,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            clickable: true,
                        },
                    };

                    if (document.querySelector('.adsSwiper')) {
                        new Swiper('.adsSwiper', {
                            ...swiperOptions,
                            pagination: {
                                el: '.adsSwiper .swiper-pagination',
                                clickable: true
                            }
                        });
                    }

                    if (document.querySelector('.offersSwiper')) {
                        new Swiper('.offersSwiper', {
                            ...swiperOptions,
                            autoplay: {
                                delay: 4000
                            },
                            pagination: {
                                el: '.offersSwiper .swiper-pagination',
                                clickable: true
                            }
                        });
                    }
                });
            </script>

            <style>
                .adsSwiper,
                .offersSwiper {
                    width: 100%;
                    height: 100%;
                    position: relative;
                    overflow: hidden !important;
                    transform: translate3d(0, 0, 0);
                    perspective: 1200px;
                }

                .swiper-slide {
                    width: 100% !important;
                    height: 100% !important;
                    backface-visibility: hidden;
                    -webkit-backface-visibility: hidden;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .swiper-slide img {
                    width: 100% !important;
                    height: 100% !important;
                    object-fit: cover !important;
                    display: block;
                }
            </style>
        <?php endif; ?>
    </div>

    <div class="w-fit my-2 md:my-5">
        <?php View::component("directions-button", "partials", [
            'mapsLink' => $company['your_location'],
            'label' => HelperService::trans('how_to_get_there') . ' ?',
            'variant' => 'primary'
        ]); ?>
    </div>

    <?php if (!empty($company['google_map'])): ?>
        <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm h-100">
            <iframe
                src="<?= $company['google_map'] ?>"
                class="w-full h-full cursor-pointer"
                style="border:0;"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
    <?php endif; ?>

    <?php if (!empty($company['facebook_page_link'])): ?>
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/bg_BG/sdk.js#xfbml=1&version=v19.0"></script>

        <?php $facebook_page_url = $company['facebook_page_link']; ?>

        <div class="md:text-center mt-5">
            <div class="fb-page w-full"
                data-href="<?php echo htmlspecialchars($facebook_page_url); ?>"
                data-tabs="timeline"
                data-width="500"
                data-height="700"
                data-small-header="false"
                data-adapt-container-width="true"
                data-hide-cover="false"
                data-show-facepile="true">
                <blockquote cite="<?php echo htmlspecialchars($facebook_page_url); ?>" class="fb-xfbml-parse-ignore">
                    <a href="<?php echo htmlspecialchars($facebook_page_url); ?>">Facebook</a>
                </blockquote>
            </div>
        </div>
    <?php endif; ?>
</div>
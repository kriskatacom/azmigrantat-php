<?php

use App\Core\View;
use App\Models\User;
use App\Services\HelperService;

$user = User::auth();
?>

<div class="text-white bg-primary-dark py-3 md:py-5">
    <h1 class="uppercase text-xl md:text-2xl lg:text-3xl xl:text-4xl font-bold text-center mb-2">
        <?= $company['name'] ?>
    </h1>
    <?php View::component('breadcrumbs', 'partials', ['items' => $breadcrumbs]); ?>
</div>

<?php if ($category): ?>
    <div class="fixed top-0 left-0 w-full h-full -z-5 inset-0 bg-black/40"></div>
    <img class="fixed top-0 left-0 w-full h-full -z-10" src="<?= $category['companies_background_url'] ?>" alt="">
<?php endif; ?>

<div x-data="{ isOpen: false }" class="relative w-full aspect-video max-h-100 min-h-60 overflow-hidden">
    <img src="<?= HelperService::getImage($company['image_url']) ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($company['name']) ?>">

    <div class="absolute inset-0 bg-black/40"></div>

    <div class="absolute inset-0 flex flex-col items-center justify-center px-4">
        <h1 class="text-white uppercase sm:text-xl text-2xl md:text-5xl lg:text-6xl font-black text-center mb-8 tracking-tighter drop-shadow-2xl">
            <?= htmlspecialchars($company['name']) ?>
        </h1>

        <div class="flex flex-col sm:flex-row sm:justify-center items-center gap-2 md:gap-5 w-full">
            <a href="<?= $_SERVER['REQUEST_URI'] ?>/about" class="btn-primary">
                Научете повече за нас
            </a>
        </div>
    </div>
</div>

<div class="container mx-auto px-2 md:px-5">
    <div class="grid md:grid-cols-2 gap-2 md:gap-5">
        <div class="group flex flex-col">
            <h3 class="text-center font-semibold my-2 md:my-5 text-white text-xl md:text-2xl">Реклами</h3>

            <div class="relative h-64 md:h-80 rounded-xl shadow-md overflow-hidden mb-4">
                <?php if (!empty($ads)): ?>
                    <div class="swiper adsSwiper h-full w-full">
                        <div class="swiper-wrapper">
                            <?php foreach ($ads as $ad): ?>
                                <div class="swiper-slide">
                                    <img src="<?= HelperService::getImage($ad['image_url']) ?>"
                                        class="w-full h-full object-cover"
                                        alt="<?= htmlspecialchars($ad['name']) ?>">
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
                                Вашите реклами се показват тук
                            </span>
                        <?php else: ?>
                            <div class="space-y-5">
                                <h3 class="text-white text-2xl md:text-3xl font-black uppercase drop-shadow-md">Управлявайте рекламите си с лекота</h3>
                                <a href="/auth/login" title="Влизане в профила" class="text-white btn-primary">Влизане в профила</a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($user['id']) && $user['id'] === $company['user_id']): ?>
                <div class="text-white text-sm md:text-base leading-relaxed mb-4 grow italic">
                    Използвайте това пространство за вашите корпоративни банери, имиджови кампании или съобщения за кариерно развитие и свободни работни позиции в структурата на вашата организация.
                </div>
                <a href="/admin/ads" class="flex justify-center btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Управление на реклами
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($ads)): ?>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const swiper = new Swiper('.adsSwiper', {
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
                            delay: 3000,
                            disableOnInteraction: false,
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                    });
                });
            </script>
            <style>
                .adsSwiper {
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

                .swiper-wrapper {
                    box-sizing: border-box;
                }
            </style>
        <?php endif; ?>

        <div class="group flex flex-col">
            <h3 class="text-center font-semibold my-2 md:my-5 text-white text-xl md:text-2xl">Обяви</h3>

            <div class="relative h-64 md:min-h-80 rounded-xl shadow-md overflow-hidden mb-4">
                <img src="<?= HelperService::getImage($company['offer_image_url'] ?: $company['image_url']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center p-6 text-center">
                    <?php if (!empty($user['id'])): ?>
                        <span class="text-white text-2xl md:text-3xl font-black uppercase drop-shadow-md">
                            Вашите обяви се показват тук
                        </span>
                    <?php else: ?>
                        <div class="space-y-5">
                            <h3 class="text-white text-2xl md:text-3xl font-black uppercase drop-shadow-md">Управлявайте обявите си с лекота</h3>
                            <a href="/auth/login" title="Влизане в профила" class="text-white btn-primary">Влизане в профила</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($user['id']) && $user['id'] === $company['user_id']): ?>
                <div class="text-white text-sm md:text-base leading-relaxed mb-4 grow italic">
                    Тук можете да публикувате специфични търговски оферти, нови продукти, сезонни намаления или детайлна информация за услугите, които предлагате на вашите крайни клиенти и партньори.
                </div>
                <a href="/admin/companies/edit/<?= $company['id'] ?>" class="flex justify-center btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Редактирай обявата
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="w-fit my-2 md:my-5">
        <?php View::component("directions-button", "partials", [
            'mapsLink' => $company['your_location'],
            'label' => 'Как да стигнете ?',
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

        <?php $facebook_page_url = $company['facebook_page_link'] ?> ?>

        <div class="md:text-center">
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

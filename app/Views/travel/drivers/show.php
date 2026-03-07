<?php

use App\Core\View;
use App\Models\User;
use App\Services\HelperService;

$breadcrumbs = [
    ['label' => 'Пътуване', 'href' => '/travel'],
    ['label' => 'Споделено пътуване', 'href' => '/travel/shared-travel'],
    ['label' => 'Шофьори', 'href' => '/travel/shared-travel/drivers'],
    ['label' => $driver['name']]
];

$hasContacts = !empty($driver['address']) || !empty($driver['phone']) || !empty($driver['email']) || !empty($driver['website_link']);

$user = User::auth();
?>
<section>
    <div class="flex items-center justify-center overflow-hidden w-full aspect-video md:max-h-100">
        <img src="<?= $driver['cover_image_url'] ?: '/images/default-cover.jpg' ?>" class="w-full h-full object-cover scale-105" alt="<?= htmlspecialchars($driver['name']) ?>">
    </div>

    <div class="bg-primary-dark py-5">
        <h1 class="text-white text-center text-xl md:text-2xl lg:text-3xl font-semibold">
            <?= htmlspecialchars($driver['name']) ?>
        </h1>
        <div class="text-white bg-primary-dark">
            <?php View::component('breadcrumbs', 'partials', ['items' => $breadcrumbs]); ?>
        </div>
    </div>

    <?php if (!empty($user) && (($user['id'] === $driver['user_id']) || ($user['role'] === 'admin'))): ?>
        <div class="m-2 md:m-5">
            <a href="/admin/drivers/edit/<?= $driver['user_id'] ?>" class="btn-primary w-full xs:w-fit mx-auto text-center block">Администрация</a>
        </div>
    <?php endif; ?>

    <div class="container mx-auto px-2 md:px-5 mt-2 md:mt-5">
        <div class="grid grid-cols-2 gap-2 md:gap-5">
            <div x-data="{ isOpen: false }" x-cloak class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">
                <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl">
                    <?php HelperService::icon('user-icon', 'w-8 h-8 z-10'); ?> За шофьора
                </div>
                <div class="p-2 md:p-5 space-y-3 md:space-y-5 text-xs md:text-base">
                    <?php if ($driver['description']): ?>
                        <button @click="$dispatch('open-modal-show-description')" class="flex items-center justify-center gap-2 text-xs md:text-sm bg-primary-dark text-white px-4 py-2 rounded-lg hover:bg-black transition-all w-full md:w-fit mt-auto">
                            Повече информация
                        </button>
                        <div class="text-gray-700 leading-relaxed text-xs md:text-base line-clamp-3 sm:line-clamp-8 md:line-clamp-6 lg:line-clamp-8 xl:line-clamp-12 2xl:line-clamp-none">
                            <?= $driver['description'] ?>
                        </div>
                    <?php else: ?>
                        <div class="text-gray-700">Няма добавено описание за шофьора.</div>
                    <?php endif; ?>
                </div>

                <?php if ($driver['description']): ?>
                    <?php View::component("modal", "partials", [
                        'id' => 'show-description',
                        'title' => $driver['name'],
                        'content' => $driver['description'],
                    ]); ?>
                <?php endif; ?>
            </div>

            <?php View::component('gallery', 'partials', [
                'images' => $driver['gallery_images'],
                'title'  => 'Галерия',
                'icon'   => 'images'
            ]); ?>
        </div>

        <div class="border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto mt-2 md:mt-5">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl">
                <?php HelperService::icon('location', 'w-5 h-5 md:w-10 md:h-10'); ?> Информация за контакти
            </div>
            <?php if ($hasContacts): ?>
                <div class="p-2 md:p-5 space-y-3 md:space-y-5 text-xs md:text-base">
                    <?php if (!empty($driver['address'])): ?>
                        <div class="flex items-center gap-3">
                            <?php HelperService::icon('address', 'w-5 h-5 md:w-10 md:h-10'); ?>
                            <span class="break-all"><?= $driver['address'] ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($driver['phone'])): ?>
                        <div class="flex items-center gap-3">
                            <?php HelperService::icon('phone', 'w-5 h-5 md:w-10 md:h-10'); ?>
                            <a class="break-all" href="tel:<?= $driver['phone'] ?>"><?= $driver['phone'] ?></a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($driver['email'])): ?>
                        <div class="flex items-center gap-3">
                            <?php HelperService::icon('mail', 'w-5 h-5 md:w-10 md:h-10'); ?>
                            <a class="break-all" href="mailto:<?= $driver['email'] ?>"><?= $driver['email'] ?></a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($driver['website_link'])): ?>
                        <div class="flex items-center gap-3">
                            <?php HelperService::icon('globe-icon', 'w-5 h-5 md:w-10 md:h-10'); ?>
                            <a class="break-all" href="<?= $driver['website_link'] ?>" target="_blank" title="Уебсайт на <?= $driver['heading'] ?>"><?= $driver['website_link'] ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="p-2 md:p-5 text-xs md:text-base text-gray-700">Няма добавена информация.</div>
            <?php endif; ?>

        </div>
    </div>

    <?php if ($driver['driver_travel_status'] !== 'not_traveling'): ?>
        <div class="container mx-auto px-2 md:px-5 mt-2 md:mt-5">
            <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">
                <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-2 text-xs md:text-lg lg:text-xl">
                    <?= $driver['driver_travel_status'] === 'departure' ? 'Отпътуване' : 'Връщане' ?>
                </div>

                <div class="p-2 md:p-5">
                    <?php if ($driver['driver_travel_status'] === 'departure'): ?>

                        <?php if (!empty($driver['travel_departure_image'])): ?>
                            <img src="<?= $driver['travel_departure_image'] ?>" alt="Отпътуване" class="w-full aspect-video md:h-100 rounded-lg object-cover">
                        <?php else: ?>
                            <?= $driver['travel_departure_details'] ?>
                        <?php endif; ?>

                    <?php else: ?>

                        <?php if (!empty($driver['travel_return_image'])): ?>
                            <img src="<?= $driver['travel_return_image'] ?>" alt="Връщане" class="w-full aspect-video md:h-100 rounded-lg object-cover">
                        <?php else: ?>
                            <?= $driver['travel_return_details'] ?>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($driver['facebook_page_link'])): ?>
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/bg_BG/sdk.js#xfbml=1&version=v19.0"></script>

        <?php $facebook_page_url = $driver['facebook_page_link'] ?>

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
</section>
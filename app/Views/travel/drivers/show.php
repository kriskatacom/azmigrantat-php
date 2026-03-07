<?php

use App\Core\View;
use App\Models\User;

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
            <a href="/admin/users/edit/<?= $driver['user_id'] ?>" class="btn-primary w-full xs:w-fit mx-auto text-center block">Администрация</a>
        </div>
    <?php endif; ?>

    <div class="container mx-auto px-2 md:px-5 mt-2 md:mt-5">
        <div class="grid grid-cols-2 gap-2 md:gap-5">
            <div x-data="{ isOpen: false }" x-cloak class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">
                <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-3 text-xs md:text-lg lg:text-xl font-semibold text-gray-800">
                    <i class="fas fa-id-card text-primary-dark text-lg md:text-2xl"></i>
                    За шофьора
                </div>
                <div class="p-2 md:p-5 space-y-3 md:space-y-5 text-xs md:text-base">
                    <?php if ($driver['description']): ?>
                        <button @click="$dispatch('open-modal-show-description')" class="btn-primary">Повече информация</button>
                        <div class="text-gray-700 leading-relaxed text-xs md:text-base line-clamp-3 sm:line-clamp-8 md:line-clamp-6 lg:line-clamp-8 xl:line-clamp-12 2xl:line-clamp-none">
                            <?= $driver['description'] ?>
                        </div>
                    <?php else: ?>
                        <div class="text-gray-500 italic">Няма добавено описание за шофьора.</div>
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

        <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto mt-2 md:mt-5">
            <div class="p-2 md:p-5 border-b border-gray-200 flex items-center gap-3 text-xs md:text-lg lg:text-xl font-semibold text-gray-800">
                <i class="fas fa-address-book text-primary-dark text-lg md:text-2xl"></i>
                Информация за контакти
            </div>

            <?php if ($hasContacts): ?>
                <div class="p-2 md:p-5 grid grid-cols-1 md:grid-cols-2 text-xs md:text-base">

                    <?php if (!empty($driver['address'])): ?>
                        <div class="flex items-start gap-4 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full bg-indigo-50 text-primary-dark shrink-0">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Адрес</span>
                                <span class="text-gray-700 break-all font-medium"><?= $driver['address'] ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($driver['phone'])): ?>
                        <div class="flex items-start gap-4 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full bg-green-50 text-green-600 shrink-0">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Телефон</span>
                                <a class="text-gray-700 break-all font-medium hover:text-green-600" href="tel:<?= $driver['phone'] ?>"><?= $driver['phone'] ?></a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($driver['email'])): ?>
                        <div class="flex items-start gap-4 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full bg-blue-50 text-blue-600 shrink-0">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Имейл</span>
                                <a class="text-gray-700 break-all font-medium hover:text-blue-600" href="mailto:<?= $driver['email'] ?>"><?= $driver['email'] ?></a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($driver['website_link'])): ?>
                        <div class="flex items-start gap-4 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center rounded-full bg-purple-50 text-purple-600 shrink-0">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[10px] uppercase text-gray-400 font-bold tracking-wider">Уебсайт</span>
                                <a class="text-gray-700 break-all font-medium hover:text-purple-600" href="<?= $driver['website_link'] ?>" target="_blank" rel="noopener"><?= $driver['website_link'] ?></a>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            <?php else: ?>
                <div class="p-2 md:p-5 text-xs md:text-base text-gray-500 italic text-center">Няма добавена информация за контакти.</div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($driver['driver_travel_status'] !== 'not_traveling'): ?>
        <div class="container mx-auto px-2 md:px-5 mt-2 md:mt-5">
            <div class="bg-white border border-gray-200 rounded md:rounded-xl shadow-sm overflow-hidden h-auto">

                <div class="p-2 md:p-5 border-b border-gray-200 flex items-center justify-between text-xs md:text-lg lg:text-xl font-semibold text-gray-800">
                    <div class="flex items-center gap-2">
                        <i class="fas <?= $driver['driver_travel_status'] === 'departure' ? 'fa-plane-departure text-blue-500' : 'fa-plane-arrival text-emerald-500' ?>"></i>
                        <?= $driver['driver_travel_status'] === 'departure' ? 'Отпътуване' : 'Връщане' ?>
                    </div>
                </div>

                <div class="p-2 md:p-5">
                    <?php
                    $is_departure = ($driver['driver_travel_status'] === 'departure');
                    $img_src = $is_departure ? $driver['travel_departure_image'] : $driver['travel_return_image'];
                    $details = $is_departure ? $driver['travel_departure_details'] : $driver['travel_return_details'];
                    ?>

                    <?php if (!empty($img_src)): ?>
                        <div class="relative group cursor-zoom-in overflow-hidden rounded-xl shadow-lg bg-gray-100">

                            <img src="<?= $img_src ?>"
                                alt="Travel Image"
                                class="lightbox-trigger w-full aspect-video md:h-100 object-cover transition-all duration-700 group-hover:scale-110">

                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center pointer-events-none">

                                <div class="transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    <i class="fas fa-expand-alt text-white text-4xl drop-shadow-lg"></i>
                                </div>

                                <span class="mt-3 text-white font-medium tracking-wide text-sm md:text-base opacity-0 group-hover:opacity-100 transition-opacity delay-100">
                                    Преглед на цял екран
                                </span>
                            </div>

                            <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-md p-2 rounded-lg border border-white/30 group-hover:hidden transition-all">
                                <i class="fas fa-search-plus text-white"></i>
                            </div>

                        </div>
                    <?php else: ?>
                        <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-indigo-500 text-gray-700 italic">
                            <?= $details ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php View::component('lightbox', 'admin/components'); ?>

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
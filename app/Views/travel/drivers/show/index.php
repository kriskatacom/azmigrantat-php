<?php

use App\Core\View;
use App\Models\User;
use App\Services\HelperService;

$breadcrumbs = [
    ['label' => 'Пътуване', 'url' => '/travel'],
    ['label' => 'Споделено пътуване', 'url' => '/travel/shared-travel'],
    ['label' => 'Шофьори', 'url' => '/travel/shared-travel/drivers'],
];

$hasContacts = !empty($landmark['address']) || !empty($landmark['phone']) || !empty($landmark['email']) || !empty($landmark['website_link']);

$user = User::auth();
?>
<section>
    <div class="relative h-125 md:h-150 flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="<?= $driver['cover_image_url'] ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($driver['name']) ?>">
            <div class="absolute inset-0 bg-black/60"></div>
        </div>
        <div class="max-w-5xl mx-auto flex justify-between items-center">
            <div class="relative z-10 px-5 text-center">
                <div class="relative w-30 h-30 md:w-40 md:h-40 rounded-full border-4 border-white overflow-hidden">
                    <img src="" alt="" class="absolute top-0 left-0 object-cover w-full h-full">
                </div>
            </div>
            <?php HelperService::icon('chevron-right-icon', 'text-white w-8 h-8 z-10'); ?>
            <div class="relative z-10 px-5 text-center">
                <div class="relative w-30 h-30 md:w-40 md:h-40 rounded-full border-4 border-white overflow-hidden">
                    <img src="" alt="" class="absolute top-0 left-0 object-cover w-full h-full">
                </div>
            </div>
        </div>
    </div>

    <div class="bg-primary-dark py-5">
        <h1 class="text-white text-center text-xl md:text-2xl lg:text-3xl font-semibold">
            <?= htmlspecialchars($driver['name']) ?>
        </h1>
        <div class="text-white bg-primary-dark">
            <?php View::component('breadcrumbs', 'partials', ['items' => $breadcrumbs]); ?>
        </div>
    </div>

    <?php if (!empty($user) && $user['id'] === $driver['user_id']): ?>
        <div class="m-2 md:m-5">
            <a href="/drivers/dashboard" class="btn-primary w-full xs:w-fit mx-auto text-center block">Администрация</a>
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
                    <?php if (!empty($landmark['address'])): ?>
                        <div class="flex items-center gap-3">
                            <?php HelperService::icon('address', 'w-5 h-5 md:w-10 md:h-10'); ?>
                            <span class="break-all"><?= $landmark['address'] ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($landmark['phone'])): ?>
                        <div class="flex items-center gap-3">
                            <?php HelperService::icon('phone', 'w-5 h-5 md:w-10 md:h-10'); ?>
                            <a class="break-all" href="tel:<?= $landmark['phone'] ?>"><?= $landmark['phone'] ?></a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($landmark['email'])): ?>
                        <div class="flex items-center gap-3">
                            <?php HelperService::icon('mail', 'w-5 h-5 md:w-10 md:h-10'); ?>
                            <a class="break-all" href="mailto:<?= $landmark['email'] ?>"><?= $landmark['email'] ?></a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($landmark['website_link'])): ?>
                        <div class="flex items-center gap-3">
                            <?php HelperService::icon('globe-icon', 'w-5 h-5 md:w-10 md:h-10'); ?>
                            <a class="break-all" href="<?= $landmark['website_link'] ?>" target="_blank" title="Уебсайт на <?= $landmark['heading'] ?>"><?= $landmark['website_link'] ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="p-2 md:p-5 text-xs md:text-base text-gray-700">Няма добавена информация.</div>
            <?php endif; ?>

        </div>
    </div>
</section>

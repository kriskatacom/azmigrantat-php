<?php

use App\Core\View;
use App\Services\HelperService;
?>

<div class="relative w-full aspect-video md:h-100 overflow-hidden">
    <?php if (!empty($company['image_url'])): ?>
        <img src="<?= HelperService::getImage($company['image_url']) ?>" alt="<?= htmlspecialchars($company['name']) ?>" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-x-0 bottom-0 h-32 bg-linear-to-t from-white to-transparent z-10"></div>
    <?php endif; ?>
</div>

<div x-data="{ tab: 'description' }" class="container mx-auto px-4 md:px-10 py-10">

    <h2 class="text-3xl md:text-4xl font-bold text-center text-primary-dark mb-8">
        <?= HelperService::trans('learn_more_about_us') ?>
    </h2>

    <!-- Бутони -->
    <div class="flex justify-center flex-wrap gap-5 mb-5">
        <button
            @click="tab = 'description'"
            class="btn-primary max-sm:w-full"
            :class="tab === 'description' ? 'text-primary-dark bg-primary-light' : 'text-white bg-primary-darken'">
            <?= HelperService::trans('description') ?>
        </button>

        <button
            @click="tab = 'services'"
            class="btn-primary max-sm:w-full"
            :class="tab === 'services' ? 'text-primary-dark bg-primary-light' : 'text-white bg-primary-darken'">
            <?= HelperService::trans('services') ?>
        </button>

        <?php View::component('gallery', 'partials', [
            'images' => $company['additional_images'],
            'title' => 'Нашата галерия',
            'variant' => 'button',
        ]); ?>
    </div>

    <!-- Съдържание -->
    <div class="relative">

        <template x-if="tab === 'description'">
            <div
                x-transition:enter="transition ease-out duration-400"
                x-transition:enter-start="opacity-0 translate-y-3"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <?php if (!empty($company['description'])): ?>
                    <div class="text-gray-800 leading-relaxed max-w-4xl mx-auto html-content">
                        <?= $company['description'] ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center">
                        <?= HelperService::trans('company_description_empty') ?>
                    </p>
                <?php endif; ?>
            </div>
        </template>

        <template x-if="tab === 'services'">
            <div
                x-transition:enter="transition ease-out duration-400"
                x-transition:enter-start="opacity-0 translate-y-3"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0">
                <?php if (!empty($company['services_description'])): ?>
                    <div class="text-gray-800 leading-relaxed max-w-4xl mx-auto html-content">
                        <?= $company['services_description'] ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 text-center">
                        <?= HelperService::trans('no_services_added') ?>
                    </p>
                <?php endif; ?>
            </div>
        </template>

    </div>

</div>

<div class="relative h-80 overflow-hidden my-5 md:my-10">
    <img src="<?= HelperService::getImage($company['bottom_image_url'] ?: 'default-bg.jpg') ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Company background">

    <div class="absolute inset-0 bg-black/80"></div>

    <div class="absolute inset-0 overflow-y-auto p-5 md:p-10 custom-scrollbar">
        <div class="max-w-4xl mx-auto text-white md:text-lg leading-relaxed">
            <?= $company['contacts_content'] ?>
        </div>
    </div>
</div>
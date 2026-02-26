<?php

use App\Services\HelperService;

$isHome = HelperService::isHome();
$navClasses = $isHome
    ? "absolute top-0 left-0 w-full z-50 bg-transparent"
    : "bg-primary-darken";
?>

<nav class="<?= $navClasses ?> px-6 py-4 flex flex-col gap-4 transition-colors duration-500">
    <div class="container mx-auto flex justify-between items-center w-full">
        <div class="flex items-center">
            <a href="/" title="Аз мигрантът">
                <img src="/assets/images/azmigrantat-website-logo.webp" alt="Лого">
            </a>
        </div>

        <div class="flex items-center gap-6">
            <button class="hover:text-primary-light transition">
                <?php HelperService::icon('user-icon', 'text-white w-8 h-8'); ?>
            </button>
            <button class="hover:text-primary-light transition">
                <?php HelperService::icon('menu-icon', 'text-white w-8 h-8'); ?>
            </button>
            <button class="hover:text-primary-light transition">
                <?php HelperService::icon('globe-icon', 'text-white w-8 h-8'); ?>
            </button>
        </div>
    </div>

    <div class="container mx-auto flex items-center gap-2 overflow-x-auto no-scrollbar">
        <?php
        $links = [
            '/' => 'Аз мигрантът',
            '/travel' => 'Пътуване',
            '/services' => 'Услуги',
            '/jobs' => 'Търся/Предлагам работа',
            '/ads' => 'Обяви',
            '/music' => 'Музика'
        ];

        foreach ($links as $path => $label): ?>
            <a href="<?= $path ?>" class="<?= HelperService::navLinkClasses($path) ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>
</nav>

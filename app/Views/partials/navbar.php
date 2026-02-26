<?php

use App\Services\HelperService;

$isHome = HelperService::isHome();
$navClasses = $isHome
    ? "absolute top-0 left-0 w-full z-50 bg-transparent"
    : "bg-primary-darken";

$currentLang = $_SESSION['lang'] ?? 'bg';
$targetLang = ($currentLang === 'bg') ? 'en' : 'bg';

$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($currentLang === 'bg') {
    // От BG към EN: добавяме префикс /en
    $targetUrl = '/en' . (rtrim($currentPath, '/') ?: '/');
} else {
    // От EN към BG: премахваме префикса /en
    $targetUrl = preg_replace('/^\/en/', '', $currentPath) ?: '/';
}
?>

<nav class="<?= $navClasses ?> px-6 py-4 flex flex-col gap-4 transition-colors duration-500">
    <div class="container mx-auto flex justify-between items-center w-full">
        <div class="flex items-center">
            <div class="text-3xl font-bold flex">
                <a href="<?= HelperService::url('/') ?>" title="<?= HelperService::trans('nav_home') ?>">
                    <img src="/assets/images/azmigrantat-website-logo.webp" alt="Лого">
                </a>
            </div>
        </div>

        <div class="flex items-center gap-6">
            <button class="hover:text-primary-light transition">
                <?php HelperService::icon('user-icon', 'text-white w-8 h-8'); ?>
            </button>
            <button class="hover:text-primary-light transition">
                <?php HelperService::icon('menu-icon', 'text-white w-8 h-8'); ?>
            </button>

            <a href="<?= $targetUrl ?>" class="hover:text-primary-light transition flex items-center gap-1">
                <?php HelperService::icon('globe-icon', 'text-white w-8 h-8'); ?>
                <span class="text-[10px] text-white font-bold uppercase"><?= $targetLang ?></span>
            </a>
        </div>
    </div>

    <div class="container mx-auto flex items-center gap-2 overflow-x-auto no-scrollbar">
        <?php
        $links = [
            '/' => 'home',
            '/travel' => 'travel',
            '/services' => 'services',
            '/jobs' => 'jobs',
            '/ads' => 'ads',
            '/music' => 'music'
        ];

        foreach ($links as $path => $langKey): ?>
            <a href="<?= HelperService::url($path) ?>" class="<?= HelperService::navLinkClasses($path) ?>">
                <?= HelperService::trans($langKey) ?>
            </a>
        <?php endforeach; ?>
    </div>
</nav>

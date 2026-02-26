<?php

use App\Models\User;
use App\Services\HelperService;

$isHome = HelperService::isHome();
$navClasses = $isHome
    ? "absolute top-0 left-0 w-full z-50 bg-transparent"
    : "bg-primary-darken";

$currentLang = $_SESSION['lang'] ?? 'bg';
$targetLang = ($currentLang === 'bg') ? 'en' : 'bg';

$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($currentLang === 'bg') {
    $targetUrl = '/en' . (rtrim($currentPath, '/') ?: '/');
} else {
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
            <?php if ($user = User::auth()): ?>
                <div class="relative group">
                    <button class="flex items-center gap-2 hover:text-primary-light transition text-white">
                        <span class="text-sm font-medium hidden md:block"><?= htmlspecialchars($user['name']) ?></span>
                        <?php HelperService::icon('user-icon', 'text-white w-8 h-8 group-hover:text-primary-light'); ?>
                    </button>

                    <div class="absolute right-0 mt-2 w-48 bg-[#0a1622] border border-white/10 rounded-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">

                        <?php if ($user['role'] === 'admin'): ?>
                            <a href="/admin/dashboard" class="block px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition border-b border-white/5 mb-1">
                                Админ панел
                            </a>
                        <?php endif; ?>

                        <form action="/auth/logout" method="POST" class="w-full">
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-white/5 hover:text-white transition">
                                Изход от профила
                            </button>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <a href="/auth/login" class="hover:text-primary-light transition group" title="Вход">
                    <?php HelperService::icon('user-icon', 'text-white w-8 h-8 group-hover:scale-110 transition-transform'); ?>
                </a>
            <?php endif; ?>

            <button class="hover:text-primary-light transition group">
                <?php HelperService::icon('menu-icon', 'text-white w-8 h-8 group-hover:rotate-90 transition-transform duration-300'); ?>
            </button>

            <a href="<?= $targetUrl ?>" class="hover:text-primary-light transition flex items-center gap-1 group">
                <div class="relative">
                    <?php HelperService::icon('globe-icon', 'text-white w-8 h-8 group-hover:animate-spin-slow'); ?>
                    <span class="absolute -top-1 -right-1 flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-light opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-primary-light text-[8px] items-center justify-center text-black font-bold uppercase">
                            <?= $targetLang ?>
                        </span>
                    </span>
                </div>
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
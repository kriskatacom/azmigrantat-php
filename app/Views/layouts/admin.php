<?php

use App\Core\View;
use App\Models\User;
use App\Services\HelperService;

?>

<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Админ' ?></title>
    <link rel="stylesheet" href="/assets/css/min/tailwind.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
</head>

<body class="bg-gray-50 flex">

    <script src="/assets/js/min/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/min/jquery-ui.min.js"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>

    <aside class="w-80 h-screen bg-[#1e293b] text-white flex flex-col sticky top-0 overflow-hidden">
        <div class="p-6 text-xl font-bold border-b border-gray-700 flex items-center gap-3">
            <span class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-sm">A</span>
            Табло
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto custom-scrollbar">
            <?php
            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            $menu = [
                ['label' => 'Табло', 'url' => '/admin/dashboard', 'icon' => 'dashboard-icon'],
                ['label' => 'Потребители', 'url' => '/admin/users', 'icon' => 'users-icon'],
                ['label' => 'Категории', 'url' => '/admin/categories', 'icon' => 'categories-icon'],
                ['label' => 'Държави', 'url' => '/admin/countries', 'icon' => 'globe-icon'],
                ['label' => 'Градове', 'url' => '/admin/cities', 'icon' => 'city-icon'],
                ['label' => 'Посолства', 'url' => '/admin/embassies', 'icon' => 'embassy-icon'],
                ['label' => 'Забележителности', 'url' => '/admin/landmarks', 'icon' => 'pyramid-icon'],
                ['label' => 'Круизи', 'url' => '/admin/cruises', 'icon' => 'anchor-icon'],
                ['label' => 'Автобуси', 'url' => '/admin/autobuses', 'icon' => 'bus-icon'],
                ['label' => 'Авиокомпании', 'url' => '/admin/airlines', 'icon' => 'plane-icon'],
                ['label' => 'Летища', 'url' => '/admin/airports', 'icon' => 'plane-takeoff-icon'],
                ['label' => 'Влакове', 'url' => '/admin/trains', 'icon' => 'train-front-icon'],
                ['label' => 'Таксита', 'url' => '/admin/taxis', 'icon' => 'car-taxi-front-icon'],
                ['label' => 'Банери', 'url' => '/admin/banners', 'icon' => 'rectangle-horizontal-icon'],
            ];

            foreach ($menu as $item):
                $isActive = (strpos($currentPath, $item['url']) === 0);
                $activeClass = $isActive ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-400 hover:bg-gray-800 hover:text-white';
            ?>
                <a href="<?= $item['url'] ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 group <?= $activeClass ?>">
                    <?php HelperService::icon($item['icon'], 'text-white w-8 h-8 group-hover:animate-spin-slow'); ?>
                    <span class="font-medium"><?= $item['label'] ?></span>

                    <?php if ($isActive): ?>
                        <span class="ml-auto w-1.5 h-1.5 bg-white rounded-full"></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="p-5 border-t border-gray-700/50 bg-[#1e293b]">
            <form action="/auth/logout" method="POST">
                <button class="w-full flex items-center gap-3 px-4 py-2.5 text-red-400 hover:bg-red-500/10 rounded-xl transition duration-200 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-400 opacity-70 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="font-medium">Изход</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1">
        <header class="bg-white shadow-sm p-4 flex items-center justify-between px-8">
            <h1 class="text-xl font-semibold text-gray-800"><?= $title ?></h1>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500"><?= User::auth()['name'] ?></span>
                <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
            </div>
        </header>

        <div class="p-5">
            <?= $content ?>
        </div>

        <?php View::component('alert', 'admin/components'); ?>
    </main>

</body>

</html>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
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
                ['label' => 'Табло', 'url' => '/admin/dashboard', 'icon' => 'fas fa-chart-line'],
                ['label' => 'Потребители', 'url' => '/admin/users', 'icon' => 'fas fa-users'],
                ['label' => 'Категории', 'url' => '/admin/categories', 'icon' => 'fas fa-layer-group'],
                ['label' => 'Държави', 'url' => '/admin/countries', 'icon' => 'fas fa-globe-europe'],
                ['label' => 'Градове', 'url' => '/admin/cities', 'icon' => 'fas fa-city'],
                ['label' => 'Посолства', 'url' => '/admin/embassies', 'icon' => 'fas fa-landmark-flag'],
                ['label' => 'Забележителности', 'url' => '/admin/landmarks', 'icon' => 'fas fa-monument'],
                ['label' => 'Круизи', 'url' => '/admin/cruises', 'icon' => 'fas fa-ship'],
                ['label' => 'Автобуси', 'url' => '/admin/autobuses', 'icon' => 'fas fa-bus'],
                ['label' => 'Авиокомпании', 'url' => '/admin/airlines', 'icon' => 'fas fa-plane'],
                ['label' => 'Компании', 'url' => '/admin/companies', 'icon' => 'fas fa-building'],
                ['label' => 'Летища', 'url' => '/admin/airports', 'icon' => 'fas fa-plane-arrival'],
                ['label' => 'Влакове', 'url' => '/admin/trains', 'icon' => 'fas fa-train'],
                ['label' => 'Таксита', 'url' => '/admin/taxis', 'icon' => 'fas fa-taxi'],
                ['label' => 'Банери', 'url' => '/admin/banners', 'icon' => 'fas fa-ad'],
            ];

            foreach ($menu as $item):
                $isActive = (strpos($currentPath, $item['url']) === 0);

                $activeClass = $isActive
                    ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/20'
                    : 'text-gray-400 hover:bg-gray-800 hover:text-white';

                $iconColorClass = $isActive ? 'text-white' : 'text-gray-200 group-hover:text-white';
            ?>
                <a href="<?= $item['url'] ?>"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 group <?= $activeClass ?>">

                    <div class="w-8 flex justify-center items-center">
                        <i class="<?= $item['icon'] ?> text-lg transition-transform duration-300 group-hover:scale-110 <?= $iconColorClass ?>"></i>
                    </div>

                    <span class="font-medium"><?= $item['label'] ?></span>

                    <?php if ($isActive): ?>
                        <span class="ml-auto w-1.5 h-1.5 bg-white rounded-full shadow-[0_0_8px_rgba(255,255,255,0.8)]"></span>
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
<?php

use App\Core\View;
use App\Models\User;

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

    <aside class="w-64 h-screen bg-[#1e293b] text-white flex flex-col sticky top-0 overflow-hidden">
        <div class="p-6 text-xl font-bold border-b border-gray-700 flex items-center gap-3">
            <span class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-sm">A</span>
            Табло
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto custom-scrollbar">
            <?php
            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            $menu = [
                ['label' => 'Табло', 'url' => '/admin/dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                ['label' => 'Потребители', 'url' => '/admin/users', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                ['label' => 'Държави', 'url' => '/admin/countries', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2 2 2 0 012 2v.627M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Градове', 'url' => '/admin/cities', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                ['label' => 'Забележителности', 'url' => '/admin/landmarks', 'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label' => 'Посолства', 'url' => '/admin/embassies', 'icon' => 'M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9'],
                ['label' => 'Круиз компании', 'url' => '/admin/cruises', 'icon' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9h9'],
                ['label' => 'Автобуси', 'url' => '/admin/autobuses', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                ['label' => 'Авиокомпании', 'url' => '/admin/airlines', 'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
                ['label' => 'Летища', 'url' => '/admin/airports', 'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'],
                ['label' => 'Банери', 'url' => '/admin/banners', 'icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z'],
            ];

            foreach ($menu as $item):
                $isActive = (strpos($currentPath, $item['url']) === 0);
                $activeClass = $isActive ? 'bg-blue-600 text-white shadow-lg' : 'text-gray-400 hover:bg-gray-800 hover:text-white';
            ?>
                <a href="<?= $item['url'] ?>" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 group <?= $activeClass ?>">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 transition-colors <?= $isActive ? 'text-white' : 'text-gray-500 group-hover:text-white' ?>"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $item['icon'] ?>" />
                    </svg>
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
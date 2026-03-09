<?php

use App\Core\View;
use App\Models\User;
use App\Services\HelperService;

$currentUser = User::auth();
$userRole = $currentUser['role'] ?? 'user';
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Админ панел' ?></title>
    <link rel="stylesheet" href="/assets/css/min/tailwind.css">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #1e293b; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    </style>
</head>

<body class="bg-gray-50 flex min-h-screen">

    <script src="/assets/js/min/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/min/jquery-ui.min.js"></script>

    <aside class="w-80 h-screen bg-[#1e293b] text-white flex flex-col sticky top-0 z-50">
        <div class="p-6 text-xl font-bold border-b border-gray-700 flex items-center gap-3 shrink-0">
            <span class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-sm">A</span>
            Админ Панел
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto custom-scrollbar">
            <?php
            $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            $menu = [
                ['label' => 'Табло', 'url' => '/admin/dashboard', 'icon' => 'fas fa-chart-line', 'roles' => ['admin', 'entrepreneur']],
                ['label' => 'Потребители', 'url' => '/admin/users', 'icon' => 'fas fa-users', 'roles' => ['admin']],
                ['label' => 'Категории', 'url' => '/admin/categories', 'icon' => 'fas fa-layer-group', 'roles' => ['admin']],
                ['label' => 'Държави', 'url' => '/admin/countries', 'icon' => 'fas fa-globe-europe', 'roles' => ['admin']],
                ['label' => 'Градове', 'url' => '/admin/cities', 'icon' => 'fas fa-city', 'roles' => ['admin']],
                ['label' => 'Посолства', 'url' => '/admin/embassies', 'icon' => 'fas fa-landmark-flag', 'roles' => ['admin']],
                ['label' => 'Забележителности', 'url' => '/admin/landmarks', 'icon' => 'fas fa-monument', 'roles' => ['admin']],
                ['label' => 'Круизи', 'url' => '/admin/cruises', 'icon' => 'fas fa-ship', 'roles' => ['admin']],
                ['label' => 'Автобуси', 'url' => '/admin/autobuses', 'icon' => 'fas fa-bus', 'roles' => ['admin']],
                ['label' => 'Авиокомпании', 'url' => '/admin/airlines', 'icon' => 'fas fa-plane', 'roles' => ['admin']],
                ['label' => 'Компании', 'url' => '/admin/companies', 'icon' => 'fas fa-building', 'roles' => ['admin']],
                ['label' => 'Реклами', 'url' => '/admin/ads', 'icon' => 'fas fa-rectangle-ad', 'roles' => ['admin', 'entrepreneur']],
                ['label' => 'Обяви', 'url' => '/admin/offers', 'icon' => 'fas fa-tag', 'roles' => ['admin', 'entrepreneur']],
                ['label' => 'Летища', 'url' => '/admin/airports', 'icon' => 'fas fa-plane-arrival', 'roles' => ['admin']],
                ['label' => 'Влакове', 'url' => '/admin/trains', 'icon' => 'fas fa-train', 'roles' => ['admin']],
                ['label' => 'Таксита', 'url' => '/admin/taxis', 'icon' => 'fas fa-taxi', 'roles' => ['admin']],
                ['label' => 'Банери', 'url' => '/admin/banners', 'icon' => 'fas fa-ad', 'roles' => ['admin']],
            ];

            foreach ($menu as $item):
                if (!in_array($userRole, $item['roles'])) continue;

                $isActive = (strpos($currentPath, $item['url']) === 0);
                $activeClass = $isActive 
                    ? 'bg-blue-600 text-white shadow-lg' 
                    : 'text-gray-400 hover:bg-gray-800 hover:text-white';
            ?>
                <a href="<?= $item['url'] ?>" 
                   class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-200 group <?= $activeClass ?>">
                    <div class="w-6 flex justify-center items-center">
                        <i class="<?= $item['icon'] ?> text-lg transition-transform group-hover:scale-110"></i>
                    </div>
                    <span class="font-medium"><?= $item['label'] ?></span>
                    <?php if ($isActive): ?>
                        <span class="ml-auto w-1.5 h-1.5 bg-white rounded-full"></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="p-5 border-t border-gray-700/50 shrink-0">
            <form action="/auth/logout" method="POST">
                <button class="w-full flex items-center gap-3 px-4 py-2.5 text-red-400 hover:bg-red-500/10 rounded-xl transition duration-200 group">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="font-medium">Изход</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0">
        <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8 shrink-0">
            <h1 class="text-xl font-semibold text-gray-800 truncate"><?= $title ?? 'Управление' ?></h1>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-medium text-gray-900"><?= $currentUser['name'] ?></p>
                    <p class="text-xs text-gray-500 capitalize"><?= $userRole ?></p>
                </div>
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                    <?= strtoupper(substr($currentUser['name'] ?? 'U', 0, 1)) ?>
                </div>
            </div>
        </header>

        <div class="p-8 overflow-y-auto">
            <?= $content ?>
        </div>

        <?php View::component('alert', 'admin/components'); ?>
    </main>

</body>
</html>
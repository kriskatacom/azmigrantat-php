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
    <script>$.widget.bridge('uibutton', $.ui.button);</script>

    <aside class="w-64 min-h-screen bg-[#1e293b] text-white flex flex-col sticky top-0">
        <div class="p-6 text-xl font-bold border-b border-gray-700">Табло</div>

        <nav class="flex-1 p-4 space-y-2">
            <?php

            use App\Core\View;
            use App\Models\User;

            $menu = [
                ['label' => 'Табло', 'icon' => 'grid', 'url' => '/admin/dashboard'],
                ['label' => 'Потребители', 'icon' => 'users', 'url' => '/admin/users'],
                ['label' => 'Държави', 'icon' => 'countries', 'url' => '/admin/countries'],
                ['label' => 'Забележителности', 'icon' => 'landmarks', 'url' => '/admin/landmarks'],
            ];

            foreach ($menu as $item): ?>
                <a href="<?= $item['url'] ?>" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-700 transition">
                    <span class="w-5 h-5 opacity-70">📁</span> <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="p-4 border-t border-gray-700">
            <form action="/auth/logout" method="POST">
                <button class="w-full text-left px-4 py-2 text-red-400 hover:bg-red-500/10 rounded-lg">Изход</button>
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

        <div class="p-8">
            <?= $content ?>
        </div>
        
        <?php View::component('alert', 'admin/components'); ?>
    </main>

</body>

</html>

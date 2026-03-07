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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
</head>

<body class="bg-gray-50 flex">

    <script src="/assets/js/min/jquery-3.6.0.min.js"></script>
    <script src="/assets/js/min/jquery-ui.min.js"></script>
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>

    <main class="flex-1">
        <header class="bg-white shadow-sm p-4 flex items-center justify-between px-8">
            <h1 class="text-xl font-semibold text-gray-800"><?= $title ?></h1>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500 hidden md:block"><?= User::auth()['name'] ?></span>
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
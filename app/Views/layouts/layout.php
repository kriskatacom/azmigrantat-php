<?php

use App\Core\View;
use App\Services\HelperService;

?>
<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? "My PHP MVC" ?></title>
    <link rel="stylesheet" href="/assets/css/min/tailwind.css">
</head>

<body>
    <?php View::loadPartial('partials/navbar'); ?>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> My PHP MVC</p>
    </footer>

    <script type="module" src="/assets/js/main.js"></script>
</body>

</html>
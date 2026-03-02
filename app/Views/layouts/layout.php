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
    <script type="module" src="/assets/js/main.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.css" />
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.umd.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            Fancybox.bind("[data-fancybox]", {
                Carousel: {
                    Thumbs: {
                        type: "classic",
                    },
                },
            });
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body>
    <?php View::loadPartial('partials/navbar'); ?>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> My PHP MVC</p>
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    </footer>
</body>

</html>
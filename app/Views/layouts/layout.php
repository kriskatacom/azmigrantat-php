<?php

use App\Core\View;

$currentUri = $_SERVER['REQUEST_URI'];
if ($currentUri == '/' || $currentUri == '/home') {
    $title = $title ?? "Аз Мигрантът | Работа, Пътуване и Услуги за Българи в Чужбина";
    $description = $description ?? "Всичко за българския мигрант на едно място: обяви за работа в Германия и Нидерландия, самолетни билети, застраховки и кредити. Пътувай информирано!";
}
?>
<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'bg' ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title><?= $title ?? "Аз Мигрантът - Твоят портал за живот в чужбина" ?></title>

    <meta name="description" content="<?= $description ?? "Информационен портал за българи в чужбина - работа, билети, застраховки и обяви." ?>">
    <meta name="keywords" content="работа в чужбина, самолетни билети, застраховки, работа нидерландия, работа германия, българи в чужбина, легализация">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?= "https://" . $_SERVER['HTTP_HOST'] . $currentUri ?>">

    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $title ?? "Аз Мигрантът" ?>">
    <meta property="og:description" content="<?= $description ?? "Твоят портал за живот и работа в чужбина." ?>">
    <meta property="og:image" content="<?= "https://" . $_SERVER['HTTP_HOST'] ?>/assets/images/azmigrantat-hero-background.webp">
    <meta property="og:url" content="<?= "https://" . $_SERVER['HTTP_HOST'] . $currentUri ?>">
    <meta property="og:site_name" content="Аз Мигрантът">

    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.ico">

    <link rel="stylesheet" href="/assets/css/min/tailwind.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

    <script type="module" src="/assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@6.1/dist/fancybox/fancybox.umd.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (typeof Fancybox !== "undefined") {
                Fancybox.bind("[data-fancybox]", {
                    Carousel: {
                        Thumbs: {
                            type: "classic"
                        },
                    },
                });
            }
        });
    </script>
</head>

<body class="antialiased text-gray-900 bg-white">
    <?php View::loadPartial('partials/navbar'); ?>

    <main id="main-content" class="min-h-screen">
        <?= $content ?>
    </main>

    <footer class="mt-auto border-t border-gray-100">
        <div class="container mx-auto py-6 text-center">
            <p>&copy; <?= date('Y') ?> <strong>Аз Мигрантът</strong>. Всички права запазени.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="/assets/js/min/alpinejs-intersect.min.js" defer></script>
    <script src="/assets/js/min/alpine.cdn.min.js" defer></script>
</body>

</html>

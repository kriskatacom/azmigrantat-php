<!DOCTYPE html>
<html lang="bg">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? "My PHP MVC" ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body>
    <header>
        <nav>
            <a href="/">Начало</a> |
            <a href="/about">За нас</a> |
            <a href="/users">Потребители</a>
        </nav>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> My PHP MVC</p>
    </footer>

    <script type="module" src="/assets/js/main.js"></script>
</body>

</html>
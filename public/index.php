<?php

define('BASE_PATH', dirname(__DIR__));

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\App;

$app = new App();

$app->initSession();
$routePath = $app->initLanguage();
$app->dispatch($routePath);

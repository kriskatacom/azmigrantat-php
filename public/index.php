<?php

define('BASE_PATH', dirname(__DIR__));

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\App;

$app = new App();

$app->redirect_bulgaria_url();

$app->initSession();
$routePath = $app->initLanguage();
$app->dispatch($routePath);

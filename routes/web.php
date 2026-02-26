<?php

use App\Core\Router;

$router = new Router();

// GET заявки (Показване на страници)
$router->get('/', ['controller' => 'HomeController', 'method' => 'index']);
$router->get('/travel', ['controller' => 'HomeController', 'method' => 'travel']);
$router->get('/auth/login', ['controller' => 'AuthController', 'method' => 'showLogin']);
$router->get('/auth/register', ['controller' => 'AuthController', 'method' => 'showRegister']);

// POST заявки (Обработка на форми)
$router->post('/auth/login', ['controller' => 'AuthController', 'method' => 'login']);
$router->post('/auth/register', ['controller' => 'AuthController', 'method' => 'register']);
$router->post('/auth/logout', ['controller' => 'AuthController', 'method' => 'logout']);

return $router;

<?php

use App\Controllers\LandmarkController;
use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\UserController;
use App\Controllers\CountryController;
use App\Controllers\EmbassyController;

$router = new Router();

// --- Публични страници ---
$router->get('/', [HomeController::class, 'index']);
$router->get('/travel', [HomeController::class, 'travel']);

// --- Аутентикация ---
$router->get('/auth/login', [AuthController::class, 'showLogin']);
$router->post('/auth/login', [AuthController::class, 'login']);
$router->get('/auth/register', [AuthController::class, 'showRegister']);
$router->post('/auth/register', [AuthController::class, 'register']);
$router->post('/auth/logout', [AuthController::class, 'logout']);

// --- Админ Табло ---
$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);

// --- Потребители ---
$router->get('/admin/users', [UserController::class, 'index']);

// --- Държави (Countries) ---
$router->get('/admin/countries', [CountryController::class, 'index']);
$router->get('/admin/countries/create', [CountryController::class, 'create']);
$router->post('/admin/countries/store', [CountryController::class, 'store']);
$router->get('/admin/countries/edit/{id}', [CountryController::class, 'edit']);
$router->post('/admin/countries/update/{id}', [CountryController::class, 'update']);
$router->post('/admin/countries/update-order', [CountryController::class, 'updateOrder']);
$router->post('/admin/countries/delete/{id}', [CountryController::class, 'delete']);

// --- Забележителности (Landmarks) ---
$router->get('/admin/landmarks', [LandmarkController::class, 'index']);
$router->get('/admin/landmarks/create', [LandmarkController::class, 'create']);
$router->post('/admin/landmarks/store', [LandmarkController::class, 'store']);
$router->get('/admin/landmarks/edit/{id}', [LandmarkController::class, 'edit']);
$router->post('/admin/landmarks/update/{id}', [LandmarkController::class, 'update']);
$router->post('/admin/landmarks/delete/{id}', [LandmarkController::class, 'delete']);
$router->post('/admin/landmarks/update-order', [LandmarkController::class, 'updateOrder']);

// --- Посолства (Embassies) ---
$router->get('/admin/embassies', [EmbassyController::class, 'index']);
$router->get('/admin/embassies/create', [EmbassyController::class, 'create']);
$router->post('/admin/embassies/store', [EmbassyController::class, 'store']);
$router->get('/admin/embassies/edit/{id}', [EmbassyController::class, 'edit']);
$router->post('/admin/embassies/update/{id}', [EmbassyController::class, 'update']);
$router->post('/admin/embassies/delete/{id}', [EmbassyController::class, 'delete']);
$router->post('/admin/embassies/update-order', [EmbassyController::class, 'updateOrder']);

return $router;

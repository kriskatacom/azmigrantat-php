<?php

use App\Core\Router;
use App\Controllers\TrainController;
use App\Controllers\HomeController;
use App\Controllers\CityController;
use App\Controllers\LandmarkController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\AirportController;
use App\Controllers\AirlineController;
use App\Controllers\BannerController;
use App\Controllers\UserController;
use App\Controllers\CountryController;
use App\Controllers\CruiseController;
use App\Controllers\EmbassyController;
use App\Controllers\AutobusController;
use App\Controllers\CategoryController;
use App\Controllers\CompanyController;
use App\Controllers\TaxiController;

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

// --- Круизи (Cruises) ---
$router->get('/admin/cruises', [CruiseController::class, 'index']);
$router->get('/admin/cruises/create', [CruiseController::class, 'create']);
$router->post('/admin/cruises/store', [CruiseController::class, 'store']);
$router->get('/admin/cruises/edit/{id}', [CruiseController::class, 'edit']);
$router->post('/admin/cruises/update/{id}', [CruiseController::class, 'update']);
$router->post('/admin/cruises/delete/{id}', [CruiseController::class, 'delete']);
$router->post('/admin/cruises/update-order', [CruiseController::class, 'updateOrder']);

// --- Банери (Banners) ---
$router->get('/admin/banners', [BannerController::class, 'index']);
$router->get('/admin/banners/create', [BannerController::class, 'create']);
$router->post('/admin/banners/store', [BannerController::class, 'store']);
$router->get('/admin/banners/edit/{id}', [BannerController::class, 'edit']);
$router->post('/admin/banners/update/{id}', [BannerController::class, 'update']);
$router->post('/admin/banners/delete/{id}', [BannerController::class, 'delete']);
$router->post('/admin/banners/update-order', [BannerController::class, 'updateOrder']);

// Градове (Cities)
$router->get('/admin/cities', [CityController::class, 'index']);
$router->get('/admin/cities/create', [CityController::class, 'create']);
$router->post('/admin/cities/store', [CityController::class, 'store']);
$router->get('/admin/cities/edit/(\d+)', [CityController::class, 'edit']);
$router->post('/admin/cities/update/(\d+)', [CityController::class, 'update']);
$router->post('/admin/cities/delete/(\d+)', [CityController::class, 'delete']);
$router->get('/api/cities-by-country/(\d+)', [CityController::class, 'getByCountry']);
$router->post('/admin/cities/update-order', [CityController::class, 'updateOrder']);

// Автобуси (Autobuses / Bus Lines)
$router->get('/admin/autobuses', [AutobusController::class, 'index']);
$router->get('/admin/autobuses/create', [AutobusController::class, 'create']);
$router->post('/admin/autobuses/store', [AutobusController::class, 'store']);
$router->get('/admin/autobuses/edit/(\d+)', [AutobusController::class, 'edit']);
$router->post('/admin/autobuses/update/(\d+)', [AutobusController::class, 'update']);
$router->post('/admin/autobuses/delete/(\d+)', [AutobusController::class, 'delete']);
$router->post('/admin/autobuses/update-order', [AutobusController::class, 'updateOrder']);

// Авиокомпании (Airlines)
$router->get('/admin/airlines', [AirlineController::class, 'index']);
$router->get('/admin/airlines/create', [AirlineController::class, 'create']);
$router->post('/admin/airlines/store', [AirlineController::class, 'store']);
$router->get('/admin/airlines/edit/(\d+)', [AirlineController::class, 'edit']);
$router->post('/admin/airlines/update/(\d+)', [AirlineController::class, 'update']);
$router->post('/admin/airlines/delete/(\d+)', [AirlineController::class, 'delete']);
$router->post('/admin/airlines/update-order', [AirlineController::class, 'updateOrder']);

// Компании (Companies)
$router->get('/admin/companies', [CompanyController::class, 'index']);
$router->get('/admin/companies/create', [CompanyController::class, 'create']);
$router->post('/admin/companies/store', [CompanyController::class, 'store']);
$router->get('/admin/companies/edit/(\d+)', [CompanyController::class, 'edit']);
$router->post('/admin/companies/update/(\d+)', [CompanyController::class, 'update']);
$router->post('/admin/companies/delete/(\d+)', [CompanyController::class, 'delete']);
$router->post('/admin/companies/update-order', [CompanyController::class, 'updateOrder']);

// Летища (Airports)
$router->get('/admin/airports', [AirportController::class, 'index']);
$router->get('/admin/airports/create', [AirportController::class, 'create']);
$router->post('/admin/airports/store', [AirportController::class, 'store']);
$router->get('/admin/airports/edit/(\d+)', [AirportController::class, 'edit']);
$router->post('/admin/airports/update/(\d+)', [AirportController::class, 'update']);
$router->post('/admin/airports/delete/(\d+)', [AirportController::class, 'delete']);
$router->post('/admin/airports/update-order', [airportController::class, 'updateOrder']);

// Влакове (Trains)
$router->get('/admin/trains', [TrainController::class, 'index']);
$router->get('/admin/trains/create', [TrainController::class, 'create']);
$router->post('/admin/trains/store', [TrainController::class, 'store']);
$router->get('/admin/trains/edit/(\d+)', [TrainController::class, 'edit']);
$router->post('/admin/trains/update/(\d+)', [TrainController::class, 'update']);
$router->post('/admin/trains/delete/(\d+)', [TrainController::class, 'delete']);
$router->post('/admin/trains/update-order', [TrainController::class, 'updateOrder']);

// Таксита (Taxis)
$router->get('/admin/taxis', [TaxiController::class, 'index']);
$router->get('/admin/taxis/create', [TaxiController::class, 'create']);
$router->post('/admin/taxis/store', [TaxiController::class, 'store']);
$router->get('/admin/taxis/edit/(\d+)', [TaxiController::class, 'edit']);
$router->post('/admin/taxis/update/(\d+)', [TaxiController::class, 'update']);
$router->post('/admin/taxis/delete/(\d+)', [TaxiController::class, 'delete']);
$router->post('/admin/taxis/update-order', [TaxiController::class, 'updateOrder']);

// Категории (Categories)
$router->get('/admin/categories', [CategoryController::class, 'index']);
$router->get('/admin/categories/create', [CategoryController::class, 'create']);
$router->post('/admin/categories/store', [CategoryController::class, 'store']);
$router->get('/admin/categories/edit/(\d+)', [CategoryController::class, 'edit']);
$router->post('/admin/categories/update/(\d+)', [CategoryController::class, 'update']);
$router->post('/admin/categories/delete/(\d+)', [CategoryController::class, 'delete']);
$router->post('/admin/categories/update-order', [CategoryController::class, 'updateOrder']);

return $router;

<?php

use App\Core\Router;
use App\Controllers\TranslationController;
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
use App\Controllers\BusCompanyController;
use App\Controllers\CategoryController;
use App\Controllers\CompanyController;
use App\Controllers\CompanyAdController;
use App\Controllers\CompanyOfferController;
use App\Controllers\CountryElementController;
use App\Controllers\TaxiController;
use App\Controllers\DriverController;
use App\Controllers\TravelController;
use App\Controllers\VillageController;

$router = new Router();

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
$router->post('/admin/users/update-role', [UserController::class, 'updateRole']);

// --- Държави (Countries) ---
$router->get('/admin/countries', [CountryController::class, 'index']);
$router->get('/admin/countries/create', [CountryController::class, 'create']);
$router->post('/admin/countries/store', [CountryController::class, 'store']);
$router->get('/admin/countries/edit/{id}', [CountryController::class, 'edit']);
$router->post('/admin/countries/update/{id}', [CountryController::class, 'update']);
$router->post('/admin/countries/update-order', [CountryController::class, 'updateOrder']);
$router->post('/admin/countries/delete/{id}', [CountryController::class, 'delete']);

// --- Елементи на държава (Countries elements) ---
$router->get('/admin/countries/country-elements', [CountryElementController::class, 'index']);
$router->get('/admin/countries/country-elements/create', [CountryElementController::class, 'create']);
$router->post('/admin/countries/country-elements/store', [CountryElementController::class, 'store']);
$router->get('/admin/countries/country-elements/edit/{id}', [CountryElementController::class, 'edit']);
$router->post('/admin/countries/country-elements/update/{id}', [CountryElementController::class, 'update']);
$router->post('/admin/countries/country-elements/update-order', [CountryElementController::class, 'updateOrder']);
$router->post('/admin/countries/country-elements/delete/{id}', [CountryElementController::class, 'delete']);

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
$router->get('/admin/cities/edit/{id}', [CityController::class, 'edit']);
$router->post('/admin/cities/update/{id}', [CityController::class, 'update']);
$router->post('/admin/cities/delete/{id}', [CityController::class, 'delete']);
$router->get('/api/cities-by-country/{id}', [CityController::class, 'getByCountry']);
$router->post('/admin/cities/update-order', [CityController::class, 'updateOrder']);

// Села (Villages)
$router->get('/admin/villages', [VillageController::class, 'index']);
$router->get('/admin/villages/create', [VillageController::class, 'create']);
$router->post('/admin/villages/store', [VillageController::class, 'store']);
$router->get('/admin/villages/edit/{id}', [VillageController::class, 'edit']);
$router->post('/admin/villages/update/{id}', [VillageController::class, 'update']);
$router->post('/admin/villages/delete/{id}', [VillageController::class, 'delete']);
$router->get('/api/villages-by-country/{id}', [VillageController::class, 'getByCountry']);
$router->get('/api/villages-by-city/{id}', [VillageController::class, 'getByCity']);
$router->post('/admin/villages/update-order', [VillageController::class, 'updateOrder']);

// Автобуси (Autobuses)
$router->get('/admin/autobuses', [AutobusController::class, 'index']);
$router->get('/admin/autobuses/create', [AutobusController::class, 'create']);
$router->post('/admin/autobuses/store', [AutobusController::class, 'store']);
$router->get('/admin/autobuses/edit/{id}', [AutobusController::class, 'edit']);
$router->post('/admin/autobuses/update/{id}', [AutobusController::class, 'update']);
$router->post('/admin/autobuses/delete/{id}', [AutobusController::class, 'delete']);
$router->post('/admin/autobuses/update-order', [AutobusController::class, 'updateOrder']);

// Автобусни компании (Bus companies)
$router->get('/admin/bus-companies', [BusCompanyController::class, 'adminIndex']);
$router->get('/admin/bus-companies/create', [BusCompanyController::class, 'create']);
$router->post('/admin/bus-companies/store', [BusCompanyController::class, 'store']);
$router->get('/admin/bus-companies/edit/{id}', [BusCompanyController::class, 'edit']);
$router->post('/admin/bus-companies/update/{id}', [BusCompanyController::class, 'update']);
$router->post('/admin/bus-companies/delete/{id}', [BusCompanyController::class, 'delete']);
$router->post('/admin/bus-companies/update-order', [BusCompanyController::class, 'updateOrder']);

// Авиокомпании (Airlines)
$router->get('/admin/airlines', [AirlineController::class, 'index']);
$router->get('/admin/airlines/create', [AirlineController::class, 'create']);
$router->post('/admin/airlines/store', [AirlineController::class, 'store']);
$router->get('/admin/airlines/edit/{id}', [AirlineController::class, 'edit']);
$router->post('/admin/airlines/update/{id}', [AirlineController::class, 'update']);
$router->post('/admin/airlines/delete/{id}', [AirlineController::class, 'delete']);
$router->post('/admin/airlines/update-order', [AirlineController::class, 'updateOrder']);

// Компании (Companies)
$router->get('/admin/companies', [CompanyController::class, 'index']);
$router->get('/admin/companies/create', [CompanyController::class, 'create']);
$router->post('/admin/companies/store', [CompanyController::class, 'store']);
$router->get('/admin/companies/edit/{id}', [CompanyController::class, 'edit']);
$router->post('/admin/companies/update/{id}', [CompanyController::class, 'update']);
$router->post('/admin/companies/delete/{id}', [CompanyController::class, 'delete']);
$router->post('/admin/companies/update-order', [CompanyController::class, 'updateOrder']);

// Услуги на компании (Company Ads)
$router->get('/admin/ads', [CompanyAdController::class, 'index']);
$router->get('/admin/ads/create', [CompanyAdController::class, 'create']);
$router->post('/admin/ads/store', [CompanyAdController::class, 'store']);
$router->get('/admin/ads/edit/{id}', [CompanyAdController::class, 'edit']);
$router->post('/admin/ads/update/{id}', [CompanyAdController::class, 'update']);
$router->post('/admin/ads/delete/{id}', [CompanyAdController::class, 'delete']);
$router->post('/admin/ads/update-order', [CompanyAdController::class, 'updateOrder']);

// Обяви на компании (Company Offers)
$router->get('/admin/offers', [CompanyOfferController::class, 'index']);
$router->get('/admin/offers/create', [CompanyOfferController::class, 'create']);
$router->post('/admin/offers/store', [CompanyOfferController::class, 'store']);
$router->get('/admin/offers/edit/{id}', [CompanyOfferController::class, 'edit']);
$router->post('/admin/offers/update/{id}', [CompanyOfferController::class, 'update']);
$router->post('/admin/offers/delete/{id}', [CompanyOfferController::class, 'delete']);
$router->post('/admin/offers/update-order', [CompanyOfferController::class, 'updateOrder']);

// Летища (Airports)
$router->get('/admin/airports', [AirportController::class, 'index']);
$router->get('/admin/airports/create', [AirportController::class, 'create']);
$router->post('/admin/airports/store', [AirportController::class, 'store']);
$router->get('/admin/airports/edit/{id}', [AirportController::class, 'edit']);
$router->post('/admin/airports/update/{id}', [AirportController::class, 'update']);
$router->post('/admin/airports/delete/{id}', [AirportController::class, 'delete']);
$router->post('/admin/airports/update-order', [AirportController::class, 'updateOrder']);

// Влакове (Trains)
$router->get('/admin/trains', [TrainController::class, 'index']);
$router->get('/admin/trains/create', [TrainController::class, 'create']);
$router->post('/admin/trains/store', [TrainController::class, 'store']);
$router->get('/admin/trains/edit/{id}', [TrainController::class, 'edit']);
$router->post('/admin/trains/update/{id}', [TrainController::class, 'update']);
$router->post('/admin/trains/delete/{id}', [TrainController::class, 'delete']);
$router->post('/admin/trains/update-order', [TrainController::class, 'updateOrder']);

// Таксита (Taxis)
$router->get('/admin/taxis', [TaxiController::class, 'index']);
$router->get('/admin/taxis/create', [TaxiController::class, 'create']);
$router->post('/admin/taxis/store', [TaxiController::class, 'store']);
$router->get('/admin/taxis/edit/{id}', [TaxiController::class, 'edit']);
$router->post('/admin/taxis/update/{id}', [TaxiController::class, 'update']);
$router->post('/admin/taxis/delete/{id}', [TaxiController::class, 'delete']);
$router->post('/admin/taxis/update-order', [TaxiController::class, 'updateOrder']);

// Категории (Categories)
$router->get('/admin/categories', [CategoryController::class, 'index']);
$router->get('/admin/categories/create', [CategoryController::class, 'create']);
$router->post('/admin/categories/store', [CategoryController::class, 'store']);
$router->get('/admin/categories/edit/{id}', [CategoryController::class, 'edit']);
$router->post('/admin/categories/update/{id}', [CategoryController::class, 'update']);
$router->post('/admin/categories/delete/{id}', [CategoryController::class, 'delete']);
$router->post('/admin/categories/update-order', [CategoryController::class, 'updateOrder']);

// Преводи (Translations)
$router->get('/admin/translations', [TranslationController::class, 'index']);
$router->get('/admin/translations/create', [TranslationController::class, 'create']);
$router->post('/admin/translations/store', [TranslationController::class, 'store']);
$router->get('/admin/translations/edit/{id}', [TranslationController::class, 'edit']);
$router->post('/admin/translations/update/{id}', [TranslationController::class, 'update']);
$router->post('/admin/translations/delete/{key}', [TranslationController::class, 'delete']);
$router->post('/admin/translations/confirm/{entity}/{id}', [TranslationController::class, 'confirmTranslations']);

// --- Шофьори (Drivers)
$router->get('/admin/users/edit/{id}', [DriverController::class, 'account']);
$router->post('/admin/users/update/{id}', [DriverController::class, 'updateAccount']);

// --- Начална страница ---
$router->get('/', [HomeController::class, 'index']);

// --- Пътувания (Travel) ---
$router->get('/travel', [HomeController::class, 'travel']);
$router->get('/travel/shared-travel', [HomeController::class, 'sharedTravel']);
$router->get('/travel/shared-travel/drivers', [DriverController::class, 'index']);
$router->get('/travel/shared-travel/drivers/{username}', [DriverController::class, 'show']);

// --- Транспортни услуги ---
$router->get('/travel/air-tickets', [TravelController::class, 'airTickets']);
$router->get('/travel/air-tickets/airports', [AirportController::class, 'showCountries']);
$router->get('/travel/air-tickets/airports/{countrySlug}', [AirportController::class, 'showByCountry']);
$router->get('/travel/air-tickets/airlines', [AirlineController::class, 'all']);

$router->get('/travel/autobuses', [TravelController::class, 'autobuses']);
$router->get('/travel/autobuses/countries', [AutobusController::class, 'showCountries']);
$router->get('/travel/autobuses/countries/{countrySlug}', [AutobusController::class, 'showCitiesByCountry']);
$router->get('/travel/autobuses/countries/{countrySlug}/{citySlug}', [AutobusController::class, 'showByCityAndCountry']);

$router->get('/travel/autobuses/bus-companies-countries', [BusCompanyController::class, 'showCountries']);
$router->get('/travel/autobuses/bus-companies-countries/{countrySlug}', [BusCompanyController::class, 'showByCountry']);

$router->get('/travel/trains', [TravelController::class, 'trains']);
$router->get('/travel/trains/countries', [TrainController::class, 'showCountries']);
$router->get('/travel/trains/countries/{countrySlug}', [TrainController::class, 'showCitiesByCountry']);
$router->get('/travel/trains/countries/{countrySlug}/{citySlug}', [TrainController::class, 'showByCityAndCountry']);

$router->get('/travel/taxis', [TravelController::class, 'taxis']);
$router->get('/travel/taxis/countries', [TaxiController::class, 'showCountries']);
$router->get('/travel/taxis/countries/{countrySlug}', [TaxiController::class, 'showCitiesByCountry']);
$router->get('/travel/taxis/countries/{countrySlug}/{citySlug}', [TaxiController::class, 'showByCityAndCountry']);

$router->get('/travel/cruises', [TravelController::class, 'cruises']);
$router->get('/travel/cruises/cruise-companies', [CruiseController::class, 'show']);

// --- Динамични страници (ВАЖНО: Трябва да са накрая) ---
$router->get('/{countrySlug}', [CountryController::class, 'show']);

$router->get('/{countrySlug}/embassies', [EmbassyController::class, 'indexByCountry']);
$router->get('/{countrySlug}/embassies/{embassySlug}', [EmbassyController::class, 'show']);

$router->get('/{countrySlug}/landmarks', [LandmarkController::class, 'indexByCountry']);
$router->get('/{countrySlug}/landmarks/{landmarkSlug}', [LandmarkController::class, 'show']);

$router->get('/{countrySlug}/cities', [CityController::class, 'indexByCountry']);
$router->get('/{countrySlug}/cities/{citySlug}', [CategoryController::class, 'categoriesShow']);
$router->get('/{countrySlug}/cities/{citySlug}/{categories*}', [CategoryController::class, 'categoriesShow']);

$router->get('/{countrySlug}/{citySlug}/villages/{vallageSlug}', [VillageController::class, 'show']);

return $router;

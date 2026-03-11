<?php

namespace App\Controllers;

use App\Models\Driver;
use App\Models\City;
use App\Core\View;
use App\Models\Banner;
use App\Models\Country;
use App\Models\User;
use App\Services\HelperService;

class DriverController extends BaseController
{
    protected Driver $driverModel;
    protected Banner $bannerModel;
    protected City $cityModel;
    protected Country $countryModel;

    public function __construct()
    {
        $this->driverModel = new Driver();
        $this->bannerModel = new Banner();
        $this->cityModel = new City();
        $this->countryModel = new Country();
    }

    public function index()
    {
        $cities = $this->cityModel->all(['order' => 'name ASC']);
        $citiesJson = json_encode($cities);

        $fromSlug = $_GET['from'] ?? null;
        $toSlug = $_GET['to'] ?? null;

        $drivers = $this->driverModel->searchByCityInDetails($fromSlug, $toSlug);

        $mainBanner = $this->bannerModel->where('link', '/travel/shared-travel/drivers')[0] ?? null;

        return View::render('travel/drivers/index', [
            'drivers' => $drivers,
            'citiesJson' => $citiesJson,
            'banner' => $mainBanner,
            'filters' => [
                'from' => $fromSlug,
                'to' => $toSlug
            ],
            'title' => HelperService::trans('drivers_search')
        ]);
    }

    public function show(string $username)
    {
        $driver = $this->driverModel->findByUsername($username);

        if (!$driver) {
            http_response_code(404);
            return View::render('errors/404');
        }

        $driver = $this->driverModel->formatJsonFields($driver);
        $fromCity = $this->cityModel->find($driver['from_city_id']);
        $toCity = $this->cityModel->find($driver['to_city_id']);

        return View::render('travel/drivers/show', [
            'driver' => $driver,
            'fromCity' => $fromCity,
            'toCity' => $toCity,
            'title' => HelperService::trans('travel_with') . " {$driver['name']}"
        ]);
    }

    public function account(string $userId)
    {
        $this->checkAccess(['admin', 'driver']);
        $sessionUser = User::auth();

        if ($sessionUser['role'] !== 'admin' && (int)$userId !== (int)$sessionUser['id']) {
            $this->flash('error', 'Нямате достъп до този профил.');
            $this->redirect('/admin/dashboard');
        }

        $driver = $this->driverModel->where('user_id', $userId)[0] ?? null;

        if (!$driver) {
            $this->flash('error', 'Шофьорският профил не е намерен.');
            $this->redirect('/admin/dashboard');
        }

        $driver = $this->driverModel->formatJsonFields($driver);
        $countries = $this->countryModel->all(['order' => 'name ASC']);

        $fromCities = [];
        if (!empty($driver['from_country_id'])) {
            $fromCities = $this->cityModel->where('country_id', $driver['from_country_id'], 'name ASC');
        }

        $toCities = [];
        if (!empty($driver['to_country_id'])) {
            $toCities = $this->cityModel->where('country_id', $driver['to_country_id'], 'name ASC');
        }

        return View::render('admin/users/account', [
            'driver'    => $driver,
            'countries' => $countries,
            'fromCities' => $fromCities,
            'toCities'  => $toCities,
            'user' => $sessionUser,
            'title'     => "Редактиране на профил: " . htmlspecialchars($driver['name']),
            'layout'    => $sessionUser['role'] === 'admin' ? 'admin' : 'drivers'
        ]);
    }

    public function updateAccount(string $userId)
    {
        $this->checkAccess(['admin', 'driver']);
        $sessionUser = User::auth();

        if ($sessionUser['role'] !== 'admin' && $userId !== $sessionUser['id']) {
            $this->flash('error', 'Нямате права за тази операция.');
            $this->redirect('/admin/dashboard');
        }

        $driver = $this->driverModel->where('user_id', $userId)[0] ?? null;

        if (!$driver) {
            $this->flash('error', 'Записът не съществува.');
            $this->redirect('/admin/dashboard');
        }

        if ($sessionUser['role'] !== 'admin') {
            unset($_POST['verified'], $_POST['status'], $_POST['user_id'], $_POST['slug']);
        }

        $_POST['gallery_images'] = $this->handleGalleryUpdate($driver, $_POST, 'gallery_images', 'drivers/gallery');

        $this->handleUpdate(
            $this->driverModel,
            $driver['id'],
            '/admin/users',
            ['profile_image_url', 'cover_image_url', 'post_image_url', 'travel_departure_image', 'travel_return_image'],
            'drivers',
            '/admin/users/edit/' . $userId
        );
    }

    public function delete($id)
    {
        $this->checkAccess(['admin', 'driver']);
        $this->handleDelete($this->driverModel, (int)$id, null, ['profile_image_url', 'cover_image_url', 'post_image_url']);
    }
}
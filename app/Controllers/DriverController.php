<?php

namespace App\Controllers;

use App\Models\Driver;
use App\Models\City;
use App\Core\View;

class DriverController
{
    protected Driver $driverModel;
    protected City $cityModel;

    public function __construct()
    {
        $this->driverModel = new Driver();
        $this->cityModel = new City();
    }

    public function index()
    {
        $cities = $this->cityModel->all(['order' => 'name ASC']);
        $citiesJson = json_encode($cities);

        $fromSlug = $_GET['from'] ?? null;
        $toSlug = $_GET['to'] ?? null;

        $drivers = $this->driverModel->searchByCitySlugs($fromSlug, $toSlug);

        return View::render('travel/drivers/index', [
            'drivers' => $drivers,
            'citiesJson' => $citiesJson,
            'filters' => [
                'from' => $fromSlug,
                'to' => $toSlug
            ],
            'title' => 'Споделено пътуване - Търсене на шофьори'
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

        return View::render('travel/drivers/show/index', [
            'driver' => $driver,
            'title' => "Пътувай с {$driver['name']}"
        ]);
    }
}
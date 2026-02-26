<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Country;

class HomeController
{
    private Country $countryModel;

    public function __construct()
    {
        $this->countryModel = new Country();
    }

    public function index()
    {
        $countries = $this->countryModel->getAllSorted();

        View::render('index/home/index', [
            'title' => 'Начало',
            'countries' => $countries
        ]);
    }

    public function travel()
    {
        View::render('index/travel', ['title' => 'Пътуване']);
    }
}

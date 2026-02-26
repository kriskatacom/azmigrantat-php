<?php

namespace App\Controllers;

use App\Core\View;

class HomeController
{
    public function index()
    {
        View::render('index/home', ['title' => 'Начало']);
    }

    public function about()
    {
        View::render('index/about', ['title' => 'За нас']);
    }
}

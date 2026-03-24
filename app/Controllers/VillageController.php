<?php

namespace App\Controllers;

use App\Core\View;
use App\Services\HelperService;
use App\Services\MetaTagsService;

class VillageController
{
    public function __construct()
    {
    }

    public function show($countrySlug, $citySlug, $vallageSlug)
    {
        $seo = new MetaTagsService([
            'title'       => HelperService::trans('home_meta_title'),
            'description' => HelperService::trans('home_meta_description'),
        ]);

        View::render('villages/show/index', [
            'title'     => HelperService::trans('home_meta_title'),
            'seo'   => $seo,
            'layout'   => 'secondary',
        ]);
    }
}

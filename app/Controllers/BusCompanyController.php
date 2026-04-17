<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Banner;
use App\Models\BusCompany;
use App\Models\City;
use App\Models\Country;
use App\Services\HelperService;
use App\Services\MetaTagsService;

class BusCompanyController extends BaseController
{
    private BusCompany $companyModel;
    private Country $countryModel;
    private City $cityModel;
    private Banner $bannerModel;

    public function __construct()
    {
        $this->companyModel = new BusCompany();
        $this->countryModel = new Country();
        $this->cityModel = new City();
        $this->bannerModel = new Banner();
    }

    public function showCountries()
    {
        $banner = $this->bannerModel->findByColumn('link', '/travel/autobuses/bus-companies-countries');
        $autobusesBanner = $this->bannerModel->findByColumn('link', '/travel/autobuses');
        $countries = $this->countryModel->all(['order' => 'name ASC']);

        if ($banner) $banner['entity_type'] = 'banner';
        if ($autobusesBanner) $autobusesBanner['entity_type'] = 'banner';

        foreach ($countries as &$c) {
            $c['entity_type'] = 'country';
        }

        $seo = new MetaTagsService([
            'title'       => HelperService::trans($banner['name'] ?? ''),
            'description' => HelperService::trans($banner['description'] ?? ''),
        ]);

        $this->render('travel/autobuses/bus-companies-countries/index', [
            'banner' => $banner,
            'autobusesBanner' => $autobusesBanner,
            'countries' => $countries,
            'seo' => $seo,
            'layout'   => 'secondary',
        ]);
    }

    public function showByCountry($countrySlug)
    {
        $country = $this->countryModel->findByColumn('slug', $countrySlug);
        if (!$country) return $this->abort(404);

        $country['entity_type'] = 'country';

        $busCompaniesBanner = $this->bannerModel->findByColumn('link', '/travel/autobuses/bus-companies-countries');
        $autobusesBanner = $this->bannerModel->findByColumn('link', '/travel/autobuses');

        $banner = $this->bannerModel->findByColumn('link', '/travel/autobuses/bus-companies/' . $countrySlug) ?? $country;

        if (isset($banner['group_key'])) {
            $banner['entity_type'] = 'banner';
        } else {
            $banner['entity_type'] = 'country';
        }

        if ($busCompaniesBanner) $busCompaniesBanner['entity_type'] = 'banner';
        if ($autobusesBanner) $autobusesBanner['entity_type'] = 'banner';

        $companies = $this->companyModel->where('country_id', $country['id']);

        foreach ($companies as &$company) {
            $company['entity_type'] = 'bus_company';
            if (!empty($company['website_url'])) {
                $company['website_url'] = HelperService::formatUrl($company['website_url']);
            }
        }

        $seo = new MetaTagsService([
            'title'       => HelperService::trans($banner['name']),
            'description' => HelperService::trans($banner['description'] ?? ''),
        ]);

        $this->render('travel/autobuses/bus-companies-countries/companies/index', [
            'banner'             => $banner,
            'busCompaniesBanner' => $busCompaniesBanner,
            'autobusesBanner'    => $autobusesBanner,
            'country'            => $country,
            'companies'          => $companies,
            'seo'                => $seo,
            'layout'             => 'secondary',
        ]);
    }

    public function adminIndex()
    {
        $this->checkAccess('admin');

        $pageData = $this->paginate($this->companyModel);
        $companies = $this->companyModel->getFiltered([
            'per_page' => $pageData['limit'],
            'offset' => $pageData['offset'],
            'search' => $_GET['search'] ?? null
        ]);

        View::render('admin/bus-companies/index', [
            'title' => 'Управление на компании',
            'companies' => $companies,
            'pagination' => $pageData['pagination'],
            'layout' => 'admin'
        ]);
    }

    public function create()
    {
        $this->checkAccess('admin');

        View::render('admin/bus-companies/form', [
            'title' => 'Добавяне на компания',
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'layout' => 'admin'
        ]);
    }

    public function store()
    {
        $this->checkAccess('admin');
        $this->handleStore(
            $this->companyModel,
            '/admin/bus-companies',
            ['logo_url'],
            'companies'
        );
    }

    public function edit($id)
    {
        $this->checkAccess('admin');
        $company = $this->companyModel->find((int)$id);

        if (!$company) {
            $this->flash('error', 'Компанията не е намерена.');
            $this->redirect('/admin/bus-companies');
        }

        View::render('admin/bus-companies/form', [
            'title' => 'Редактиране: ' . $company['name'],
            'company' => $company,
            'countries' => $this->countryModel->all(['order' => 'name ASC']),
            'cities'    => $this->cityModel->getByCountry($company['country_id'] ?? 0),
            'layout' => 'admin'
        ]);
    }

    public function update($id)
    {
        $this->checkAccess('admin');
        $this->handleUpdate(
            $this->companyModel,
            (int)$id,
            '/admin/bus-companies',
            ['logo_url'],
            'companies'
        );
    }

    public function delete($id)
    {
        $this->checkAccess('admin');
        $this->handleDelete($this->companyModel, (int)$id, '/admin/bus-companies', ['logo_url']);
    }
}

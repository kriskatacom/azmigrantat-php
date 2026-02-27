<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Country;
use App\Models\Landmark;
use App\Models\User;

class AdminController extends BaseController
{
    private Country $countryModel;
    private Landmark $landmarkModel;

    public function __construct()
    {
        $this->middleware('auth');

        $user = User::auth();
        if ($user['role'] !== 'admin') {
            header('Location: /');
            exit;
        }

        $this->countryModel = new Country();
        $this->landmarkModel = new Landmark();
    }

    public function dashboard()
    {
        $this->middleware('admin');

        $userModel = new User();

        $countryModel = new Country();
        $landmarkModel = new Landmark();

        $totalUsers = $userModel->count();
        $newUsersThisMonth = count($userModel->where('created_at >=', date('Y-m-d', strtotime('-30 days'))));
        $growthPercentage = ($totalUsers > 0) ? round(($newUsersThisMonth / $totalUsers) * 100) : 0;

        $totalCountries = $countryModel->count();
        $totalLandmarks = $landmarkModel->count();

        $activeLandmarks = count($landmarkModel->where('is_active =', 1));

        $recentUsers = $userModel->all([
            'limit' => 5,
            'order' => 'created_at DESC'
        ]);

        $recentLandmarks = $landmarkModel->all([
            'limit' => 5,
            'order' => 'created_at DESC'
        ]);

        View::render('admin/dashboard', [
            'title' => 'Админ Табло',
            'layout' => 'admin',
            'stats' => [
                'total_users'      => number_format($totalUsers),
                'growth'           => $growthPercentage,
                'total_countries'  => number_format($totalCountries),
                'total_landmarks'  => number_format($totalLandmarks),
                'active_landmarks' => $activeLandmarks
            ],
            'recentUsers'      => $recentUsers,
            'recentLandmarks'  => $recentLandmarks
        ]);
    }
}

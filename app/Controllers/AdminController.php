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
    private User $userModel;

    public function __construct()
    {
        $user = User::auth();
        if ($user['role'] !== 'admin') {
            header('Location: /');
            exit;
        }

        $this->countryModel = new Country();
        $this->landmarkModel = new Landmark();
        $this->userModel = new User();
    }

    public function dashboard()
    {
        $this->checkAccess('admin');

        $totalUsers = $this->userModel->count();
        $newUsersThisMonth = count($this->userModel->where('created_at >=', date('Y-m-d', strtotime('-30 days'))));
        $growthPercentage = ($totalUsers > 0) ? round(($newUsersThisMonth / $totalUsers) * 100) : 0;

        $totalCountries = $this->countryModel->count();
        $totalLandmarks = $this->landmarkModel->count();

        $activeLandmarks = count($this->landmarkModel->where('is_active =', 1));

        $recentUsers = $this->userModel->all([
            'limit' => 5,
            'order' => 'created_at DESC'
        ]);

        $recentLandmarks = $this->landmarkModel->all([
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

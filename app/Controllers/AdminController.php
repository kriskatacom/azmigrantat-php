<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Country;
use App\Models\User;

class AdminController extends BaseController
{
    private Country $countryModel;
    
    public function __construct()
    {
        $this->middleware('auth');

        $user = User::auth();
        if ($user['role'] !== 'admin') {
            header('Location: /');
            exit;
        }

        $this->countryModel = new Country();
    }

    public function dashboard()
    {
        $this->middleware('admin');

        $userModel = new \App\Models\User();

        $totalUsers = $userModel->count();

        $newUsersThisMonth = count($userModel->where('created_at >=', date('Y-m-d', strtotime('-30 days'))));
        $growthPercentage = ($totalUsers > 0) ? round(($newUsersThisMonth / $totalUsers) * 100) : 0;

        $recentUsers = $userModel->all([
            'limit' => 5,
            'order' => 'created_at DESC'
        ]);

        View::render('admin/dashboard', [
            'title' => 'Админ Табло',
            'layout' => 'admin',
            'stats' => [
                'total_users' => number_format($totalUsers),
                'growth' => $growthPercentage,
                'active_countries' => $this->countryModel->count(),
            ],
            'recentActivities' => $recentUsers
        ]);
    }
}

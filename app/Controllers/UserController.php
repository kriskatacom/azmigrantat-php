<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;

class UserController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->middleware('admin');
        $this->userModel = new User();
    }

    public function index()
    {
        $this->middleware('admin');

        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['per_page'] ?? 5);

        if (!in_array($perPage, [5, 10, 20, 50, 100])) {
            $perPage = 10;
        }

        $offset = ($page - 1) * $perPage;

        $users = $this->userModel->all([
            'limit' => $perPage,
            'offset' => $offset,
            'order' => 'created_at DESC'
        ]);

        $totalUsers = $this->userModel->count();
        $totalPages = ceil($totalUsers / $perPage);

        View::render('admin/users/index', [
            'title' => 'Потребители',
            'users' => $users,
            'pagination' => [
                'current' => $page,
                'total' => $totalPages,
                'per_page' => $perPage,
            ],
            'layout' => 'admin'
        ]);
    }
}

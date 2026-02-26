<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;

class AdminController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        
        $user = User::auth();
        if ($user['role'] !== 'admin') {
            header('Location: /');
            exit;
        }
    }

    public function dashboard()
    {
        View::render('admin/dashboard', [
            'title' => 'Админ Табло',
            'layout' => 'admin'
        ]);
    }
}

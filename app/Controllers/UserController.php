<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;

class UserController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showLogin()
    {
        View::render('auth/login', [
            'pageTitle' => 'Вход в профила - Аз мигрантът'
        ]);
    }

    public function showRegister()
    {
        View::render('auth/register', [
            'pageTitle' => 'Създаване на профил - Аз мигрантът'
        ]);
    }

    public function index(): void
    {
        $users = $this->userModel->all();

        $this->json($users);
    }

    public function show(int $id): void
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            $this->json(['message' => 'User not found'], 404);
        }

        $this->json($user);
    }
}

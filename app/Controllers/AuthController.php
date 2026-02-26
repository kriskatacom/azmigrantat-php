<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;

class AuthController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->middleware('guest', ['logout']);
        $this->userModel = new User();
    }

    public function showLogin()
    {
        View::render('auth/login', [
            'title' => 'Вход в профила - Аз мигрантът'
        ]);
    }

    public function showRegister()
    {
        View::render('auth/register', [
            'title' => 'Създаване на профил - Аз мигрантът'
        ]);
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->login($email, $password);

        if ($user) {
            if (isset($user['role']) && $user['role'] === 'admin') {
                header('Location: /admin/dashboard');
            } else {
                header('Location: /');
            }
            exit;
        } else {
            $_SESSION['error'] = "Невалиден имейл или парола!";
            header('Location: /auth/login');
            exit;
        }
    }

    public function logout()
    {
        User::logout();
        header('Location: /auth/login');
        exit;
    }
}
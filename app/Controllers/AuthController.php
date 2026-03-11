<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;
use App\Services\HelperService;

class AuthController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showLogin()
    {
        $this->allowOnlyGuests();
        View::render('auth/login', [
            'title' => HelperService::trans('login') . ' - ' . HelperService::trans('i_the_migrant')
        ]);
    }

    public function showRegister()
    {
        $this->allowOnlyGuests();
        View::render('auth/register', [
            'title' => HelperService::trans('register') . ' - ' . HelperService::trans('i_the_migrant')
        ]);
    }

    public function login()
    {
        $this->allowOnlyGuests();
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->login($email, $password);

        if ($user) {
            $this->redirectByUserRole($user);
        } else {
            $_SESSION['error'] = HelperService::trans('invalid_credentials') . "!";
            header('Location: /auth/login');
            exit;
        }
    }

    public function register()
    {
        $this->allowOnlyGuests();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        $_SESSION['old'] = ['name' => $name, 'email' => $email];

        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['error'] = HelperService::trans('all_fields_are_required') . "!";
            header('Location: /auth/register');
            exit;
        }

        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = HelperService::trans('incorrect_passwords') . '!';
            header('Location: /auth/register');
            exit;
        }

        $userId = $this->userModel->register($name, $email, $password);

        if ($userId) {
            $user = $this->userModel->login($email, $password);
            unset($_SESSION['old']);
            $this->redirectByUserRole($user);
        } else {
            $_SESSION['error'] = HelperService::trans('something_went_wrong') . ".";
            header('Location: /auth/register');
            exit;
        }
    }

    public function logout()
    {
        $this->checkAccess();
        User::logout();
        header('Location: /auth/login');
        exit;
    }

    private function redirectByUserRole(array $user)
    {
        $role = $user['role_name'] ?? $user['role'] ?? 'user';

        switch ($role) {
            case 'admin':
            case 'moderator':
                header('Location: /admin/dashboard');
                break;
            case 'driver':
                $username = strtolower($user['username']);
                header("Location: /travel/shared-travel/drivers/{$username}");
                break;
            default:
                header('Location: /users/account');
                break;
        }
        exit;
    }
}
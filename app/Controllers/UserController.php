<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;
use App\Models\Role;

class UserController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        $this->checkAccess('admin');

        $paginationData = $this->paginate($this->userModel, 10);

        $users = $this->userModel->getPaginatedWithRoles(
            $paginationData['limit'],
            $paginationData['offset']
        );

        View::render('admin/users/index', [
            'title' => 'Потребители',
            'users' => $users,
            'pagination' => $paginationData['pagination'],
            'layout' => 'admin'
        ]);
    }

    public function updateRole()
    {
        $this->checkAccess('admin');

        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/admin/users';

        $userId = $_POST['user_id'] ?? null;
        $roleId = (int)($_POST['role_id'] ?? 0);
        $currentUser = User::auth();

        if ($userId === $currentUser['id']) {
            $this->flash('error', 'Не можете да променяте собствената си роля!');
            $this->redirect($redirectUrl);
        }

        if ($userId && $roleId) {
            $this->userModel->updateRole($userId, $roleId);
            $this->flash('success', 'Ролята беше актуализирана успешно!');
        }

        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/admin/users';
        $this->redirect($redirectUrl);
    }
}
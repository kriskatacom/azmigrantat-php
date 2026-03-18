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

    public function index()
    {
        $this->checkAccess('admin');

        $filters = $this->getFilters();
        $searchColumns = ['first_name', 'last_name', 'email'];

        $pageData = $this->paginate($this->userModel, $filters, $searchColumns);

        $users = $this->userModel->getPaginatedWithRoles(array_merge($filters, [
            'limit'  => $pageData['limit'],
            'offset' => $pageData['offset']
        ]), $searchColumns);

        View::render('admin/users/index', [
            'title'      => 'Потребители',
            'users'      => $users,
            'filters'    => $filters,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
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

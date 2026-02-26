<?php

namespace App\Controllers;

use App\Models\User;

class UserController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
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

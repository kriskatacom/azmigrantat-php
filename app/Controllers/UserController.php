<?php

namespace App\Models;

require_once __DIR__ . '/../Models/User.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        header('Content-Type: application/json');
        echo json_encode($this->userModel->all());
    }
}

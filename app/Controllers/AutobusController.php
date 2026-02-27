<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Autobus;

class CruiseController extends BaseController
{
    private Autobus $autobusModel;

    public function __construct()
    {
        $this->autobusModel = new Autobus();
    }

    public function index()
    {
        $page = (int)($_GET['page'] ?? 1);
        $perPage = (int)($_GET['per_page'] ?? 10);
        $offset = ($page - 1) * $perPage;

        $autobuses = $this->autobusModel->getWithRelations($perPage, $offset);
        $total = $this->autobusModel->count();

        View::render('admin/autobuses/index', [
            'title' => 'Автобусни компании',
            'autobuses' => $autobuses,
            'layout' => 'admin',
            'pagination' => [
                'current' => $page,
                'total' => ceil($total / $perPage),
                'per_page' => $perPage,
            ],
        ]);
    }
}
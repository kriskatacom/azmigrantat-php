<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Autobus;

class CruiseController extends BaseController
{
    private Autobus $autobusModel;

    public function __construct()
    {
        $this->middleware('admin', ['index']);
        
        $this->autobusModel = new Autobus();
    }

    public function index()
    {
        $pageData = $this->paginate($this->autobusModel);

        $autobuses = $this->autobusModel->getWithRelations(
            $pageData['limit'],
            $pageData['offset']
        );

        View::render('admin/autobuses/index', [
            'title'      => 'Автобусни компании',
            'autobuses'  => $autobuses,
            'pagination' => $pageData['pagination'],
            'layout'     => 'admin'
        ]);
    }
}

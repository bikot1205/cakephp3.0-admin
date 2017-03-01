<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

class EmailTemplatesController extends AppController {

	public $paginate = [
        'limit' => 100,
        'order' => [
            'title' => 'asc'
        ]
    ];

    public function initialize() {
        parent::initialize();
    }

    public function edit($id) {
        $this->Crud->action()->view('add');
        return $this->Crud->execute();
    }

}

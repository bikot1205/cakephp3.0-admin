<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

class PagesController extends AppController {

    public function initialize() {
        parent::initialize();
    }

    public function edit($id) {
        $this->Crud->action()->view('add');
        return $this->Crud->execute();
    }

}

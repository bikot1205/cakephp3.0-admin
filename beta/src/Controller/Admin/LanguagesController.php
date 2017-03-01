<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;

class LanguagesController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Crud->addListener('Crud.Search');
    }
    
    public function index() {
        $this->Crud->on('beforePaginate', function(\Cake\Event\Event $event) {
            $this->paginate['order'] = ['Languages.name' => 'ASC'];
        });
        $this->Crud->on('beforeRender', function(\Cake\Event\Event $event) {
            $this->request->data = $this->request->query;
        });
        
        
        return $this->Crud->execute();
    }
    
    public function edit($id) {
        $this->Crud->action()->view('add');
        
        $this->Crud->on('beforeRender', function(\Cake\Event\Event $event) {
            $this->set(['title' => 'Edit Country']);
        });
        return $this->Crud->execute();
    }

}

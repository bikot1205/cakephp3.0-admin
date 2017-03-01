<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

class SmilesController extends AppController {

    public function initialize() {
        parent::initialize();
        
    }

    public function index2() {

        $this->Crud->on('beforePaginate', function(\Cake\Event\Event $event) {
            $this->paginate['order'] = ['Smiles.id' => 'DESC'];
        });

        return $this->Crud->execute();
    }

    public function index() {

        $this->Crud->on('beforePaginate', function(\Cake\Event\Event $event) {
            $this->paginate['order'] = ['Smiles.id' => 'DESC'];
        });
        
        $session = $this->request->session();
        if ($session->check('Smiles.error')) {
            $this->Flash->set($session->read('Avatars.error'), [
                'params' => ['class' => 'alert alert-success alert-dismissible']
            ]);
            $session->delete('Smiles.error');
        }

        $avatar = TableRegistry::get('Smiles');

        $avatar = $avatar->newEntity();

        if (!empty($this->request->data)) {
            $avatar = TableRegistry::get('Smiles');
            $message = null;
            foreach ($this->request->data['image'] as $key => $value) {
                $data['image'] = $value;
                $entities = $avatar->newEntity($data, [ 'validate' => true]);
                //$entities->clean();
                if (!$result = $avatar->save($entities)) {
                    $error = $entities->errors('image');
                    $message = $error[key($error)];
                }
            }
            if (!$message) {
                $message = 'Avatar uploaded successfully.';
            }
            $session->write('Smiles.error', $message);
            $this->redirect([ 'action' => 'index']);

           
        }
        $this->set(['avatar' => $avatar]);

        return $this->Crud->execute();
    }

    public function edit($id) {
        if(empty($this->request->data['image']['name'])){
            unset($this->request->data['image']);
        }
        $this->Crud->action()->view('add');
        return $this->Crud->execute();
    }



}

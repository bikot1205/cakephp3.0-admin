<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

class AvatarsController extends AppController {

    public function initialize() {
        parent::initialize();
    }

    public function index() {

        $this->Crud->on('beforePaginate', function(\Cake\Event\Event $event) {
            $this->paginate['order'] = ['Avatars.id' => 'ASC'];
            $this->paginate['limit'] = 36;
        });
        $session = $this->request->session();
        if ($session->check('Avatars.error')) {
            $this->Flash->set($session->read('Avatars.error'), [
                'params' => ['class' => 'alert alert-success alert-dismissible']
            ]);
            $session->delete('Avatars.error');
        }

        $avatar = TableRegistry::get('Avatars');

        $avatar = $avatar->newEntity();

        if (!empty($this->request->data)) {
            $avatar = TableRegistry::get('Avatars');
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
            $session->write('Avatars.error', $message);
            $this->redirect([ 'action' => 'index']);

           
        }
        $this->set(['avatar' => $avatar]);

        return $this->Crud->execute();
    }

}

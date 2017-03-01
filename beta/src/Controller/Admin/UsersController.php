<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use Cake\Core\Configure;
use \GameNet\Jabber\RpcClient;

class UsersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Crud->addListener('Crud.Search');
    }



    public function index() {

        $this->Crud->on('beforePaginate', function(\Cake\Event\Event $event) {
            $this->paginate['conditions']['is_admin'] = 0;
            $this->paginate['contain'] = ['Countries','Avatars'];
            $this->paginate['order'] = ['Users.id' => 'DESC'];
            $this->paginate['fields'] = ['Users.id', 'Users.first_name', 'Users.last_name', 'Users.slug', 'Users.email', 'Users.created', 'Users.age', 'Users.status','Users.gender', 'Users.muslim_since', 'Users.image','Users.new_avatar','Users.avatar_status', 'Countries.name','Avatars.id','Avatars.image'];
        });
        $this->Crud->on('beforeRender', function(\Cake\Event\Event $event) {
            $this->request->data = $this->request->query;
            $user = TableRegistry::get('Users');

            $user = $user->newEntity();

            $countries = $this->{$this->modelClass}->Countries->find('list');
            $languages = $this->{$this->modelClass}->Languages->find('list');

            $this->loadModel('Rooms');
            $rooms = $this->Rooms->find('list');

            $this->Set(['user' => $user, 'countries' => $countries, 'languages' => $languages,'rooms' => $rooms]);
        });

        return $this->Crud->execute();
    }


    public function add() {
        $this->Crud->action()->disable();
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            if (empty($this->request->data['image']['name'])) {
                unset($this->request->data['image']);
            }
            $this->request->data['muslim_since'] = $this->request->data['muslim_since']['year'];
            $this->request->data['avatar_status'] = 'Image';
            $user->role_id = 1;
            $user->status = 1;

            $user = $this->Users->patchEntity($user, $this->request->data);

            if ($this->Users->save($user)) {

                $rpc = new \GameNet\Jabber\RpcClient([
                'server' => Configure::read('XamppServer.server'),
                'host' => Configure::read('XamppServer.host'),
                'debug' => Configure::read('debug'),
                ]);

                $xamppPassword = Configure::read('XamppServer.passwordPrefix').$user->id;
                $userName = $user->slug;
                $rpc->createUser($userName, $xamppPassword,$user->id);

                $this->Flash->success(__('User has been created successfully.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to add your user.'));
        }

        $countries = $this->{$this->modelClass}->Countries->find('list');
        $languages = $this->{$this->modelClass}->Languages->find('list');
        $this->Set(['countries' => $countries, 'languages' => $languages, 'user' => $user]);
    }


    public function view($slug) {


        $this->Crud->action()->disable();
        $user = $this->Users->find('all', [
            'conditions' => ['Users.slug' => $slug],
            'contain' =>
            [
            'Languages',
            'Countries' => ['fields' => ['Countries.name']],
            'Avatars',
            ]
            ]
            )->first();

        if (empty($user)) {
            return $this->redirect(['action' => 'index']);
        }
        if (!empty($this->request->data)) {
            if(isset($_POST['changePassword'])){

                $userEntity = $this->Users->patchEntity($user, $this->request->data, ['validate' => 'forgotPassword']);
                if ($this->Users->save($userEntity)) {
                    $this->Flash->set(__('PasswordReset'), [
                        'params' => ['class' => 'alert alert-success alert-dismissible']
                        ]);
                    return $this->redirect(['action' => 'view', $slug]);
                }
                $this->Flash->error(__('Unable change password.'));
            } else{
                if (empty($this->request->data['image']['name'])) {
                    unset($this->request->data['image']);
                }
                $this->request->data['avatar_status'] = 'Image';

                if (empty($this->request->data['password'])) {
                    unset($this->request->data['password']);
                }

                $this->request->data['muslim_since'] = $this->request->data['muslim_since']['year'];

                $userEntity = $this->Users->patchEntity($user, $this->request->data);
                if ($this->Users->save($userEntity)) {
                    $this->Flash->success(__('User has been created successfully.'));
                    return $this->redirect(['action' => 'view', $slug]);
                }
                $this->Flash->error(__('Unable to add your user.'));
            }
            
        }

        $countries = $this->{$this->modelClass}->Countries->find('list');
        $languages = $this->{$this->modelClass}->Languages->find('list');
        $this->Set(['countries' => $countries, 'languages' => $languages, 'user' => $user]);

        $this->loadModel('RoomUsers');
        $joinedRooms = $this->RoomUsers->find('all')->where(['RoomUsers.user_id' => $user->id])->contain(['Rooms']);

        $this->set(['user' => $user,'joinedRooms' => $joinedRooms]);
    }


    public function avatars() {
        $this->paginate['conditions']['is_admin'] = 0;
        $this->paginate['contain'] = ['Avatars'];
        $this->paginate['fields'] = ['Users.id', 'Users.first_name', 'Users.last_name','Users.image','Users.new_avatar','Users.avatar_status','Avatars.id','Avatars.image'];
        $avatars = $this->paginate();

        $this->set(['avatars' => $avatars]);
        
    }

    public function uploadAvatar($id){
        $user = $this->Users->find('all', ['conditions' => ['Users.id' => $id]])->first();
        
        if($this->request->is(['post','put'])){
            $this->request->data['avatar_status'] = 'Pending';
            $userEntity = $this->Users->patchEntity($user, $this->request->data, ['validate' => 'avatar']);
            if ($this->Users->save($userEntity)) {
                $this->Flash->success(__('User avatar has been updated successfully.'));
                if($this->request->data['referer'] != '/'){
                   return $this->redirect($this->request->data['referer']); 
                }
                return $this->redirect(['action' => 'avatars']);
            }
            $this->Flash->error(__('Unable to add your user.'));
        } else{
            $referer = $this->referer();
        }

        $this->set(['user' => $user,'referer' => $referer]);
        
    }

    public function changeStatus($id, $status = 0) {
        $user = $this->Users->find('all', [
            'conditions' => ['Users.id' => $id, 'Users.status' => $status],
            ]
            )->first();

        if (empty($user)) {
            throw new NotFoundException('404 error.');
        }
        $status = ($status) ? 0 : 1;
        $users = TableRegistry::get('Users');
        $query = $users->query();
        $result = $query->update()
        ->set(['status' => $status])
        ->where(['id' => $id])
        ->execute();

        if ($result) {
            if ($status) {
                $this->Flash->success(__('User has been un blocked successfully.'));
            } else {
                $this->Flash->success(__('User has been blocked successfully.'));
            }
        }

        $this->redirect($this->referer());
    }

    public function login() {
        $this->viewBuilder()->layout('login');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(__('Username or password is incorrect'), [
                    'key' => 'auth'
                    ]);
            }
        }
        
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    public function search() {
        $response = null;
        if (!empty($this->request->query['query'])) {
            $data = $this->Users->find('list', [
                'keyField' => 'id',
                'valueField' => function ($e) {
                    return $e->get('name');
                }
                ])->where(['Users.first_name like ' => "%" . trim($this->request->query['query']) . "%", 'Users.is_admin' => 0, 'Users.role_id' => 1])->toArray();

            foreach ($data as $key => $name) {
                $response[] = array('id' => $key, 'value' => $name .' - '.$key);
            }
        }
        
        echo json_encode(array('suggestions' => $response));
        exit;
    }

    

}

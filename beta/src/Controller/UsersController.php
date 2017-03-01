<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Network\Http\Client;
use Cake\Http\Client\Response;
use Cake\Utility\Hash;
use Cake\Core\Configure;
use \GameNet\Jabber\RpcClient;

class UsersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['add', 'activate', 'forgot', 'resetPassword', 'google']);
        $this->Crud->addListener('Crud.Search');
    }


    public function index() {
        $this->viewBuilder()->layout(false);
        $this->Crud->action()->disable();
        $user_id = $this->loggedIn['id'];
        // $query->select('Friends.id');
        $customFinderOptions = array('user_id' => $user_id, 'searchQueries' => $this->request->query);

        $this->paginate = [
        'finder' => [
        'searched' => $customFinderOptions
        ]
        ];

        $users = $this->paginate($this->Users);

        $this->set(['users' => $users]);

        $this->request->data = $this->request->query;
    }

    public function suggested() {
        $user_id = $this->loggedIn['id'];
        // $query->select('Friends.id');
        $customFinderOptions = array('user_id' => $user_id, 'searchQueries' => $this->request->query);

        $this->paginate = [
        'finder' => [
        'searched' => $customFinderOptions
        ]
        ];

        $users = $this->paginate($this->Users);
        $this->set(['users' => $users]);

        $this->request->data = $this->request->query;

        $this->render('index');
    }




    public function add() {
        if (!Configure::read('Site.Registration.InvitationOnly')) {
            $this->Flash->set(__('Inviated users can registered at this movement.'), [
                'params' => ['class' => 'alert alert-success alert-dismissible']
                ]);
            //$this->redirect(['action' => 'login']);
        }
        if($this->loggedIn['id'] && $this->loggedIn['role_id'] != 0){
            $this->redirect(['action' => 'index']);
        }
        if(!empty($this->loggedIn['id'])){
            $user = $this->Users->find('all', [
                'conditions' => ['Users.id' => $this->loggedIn['id']],
                'contain' =>
                [
                'Countries' => ['fields' => ['Countries.name']],
                'Avatars'
                ]
                ]
                )->first();
            $user['muslim_since'] = '';
            
        } else {
            $user = $this->Users->newEntity();
        }
        


        $this->Crud->action()->disable();
        $this->viewBuilder()->layout('front');
        
        if ($this->request->is(['Post', 'Put'])) {
            if (empty($this->request->data['image']['name'])) {
                unset($this->request->data['image']);
            }
            if(!empty($this->request->data['image']['name'])){
                $this->request->data['avatar_id'] = null;
                $this->request->data['avatar_status'] = 'Image';
            }

            if(!empty($this->request->data['avatar_id'])){
                $this->request->data['avatar_status'] = 'Avatar';
            }

            $this->request->data['muslim_since'] = $this->request->data['muslim_since']['year'];

            
            $userEntity = $this->Users->patchEntity($user, $this->request->data, ['validate' => 'register']);
            $userEntity->role_id = 1;
            if(!empty($this->loggedIn['id'])){
                $userEntity->status = 1;
            }
            // For testing only
            $userEntity->status = 1;
            
            // check image uploaded or avatar selected.
            $continue = true;
            if(empty($this->request->data['image']['name']) && empty($this->request->data['avatar_id'])){
               $continue = false;
               $this->Flash->set(__('Please upload image or select a avatar image.'), [
                'params' => ['class' => 'alert alert-danger alert-dismissible']
                ]);
           }
           if ($continue && $result = $this->Users->save($userEntity)) {

             // Add xampp user

            $rpc = new \GameNet\Jabber\RpcClient([
                'server' => Configure::read('XamppServer.server'),
                'host' => Configure::read('XamppServer.host'),
                'debug' => Configure::read('debug'),
                ]);

            $xamppPassword = Configure::read('XamppServer.passwordPrefix').$result->id;
            $userName = $result->slug;
            $rpc->createUser($userName, $xamppPassword,$result->id);

            // $rpc = new \GameNet\Jabber\RpcClient([
            //     'server' => Configure::read('XamppServer.server'),
            //     'host' => Configure::read('XamppServer.host'),
            //     'debug' => false,
            // ]);

            //$xamppPassword = Configure::read('XamppServer.passwordPrefix').$result->id;

            //$rpc->createUser($result->id, $xamppPassword);

            if(empty($this->loggedIn['id'])){
                $userId = $result->id;
                    //Send Notification to admin via email
                $to = $this->request->data['email'];
                $template = 'Account-activation';
                $activationCode = md5(microtime() . $userId);
                $now = Time::now();
                $now->modify('+3 days');
                $userEntity->token = $activationCode;
                $userEntity->token_expiry = $now;
                $this->Users->save($userEntity);

                $link = \Cake\Routing\Router::url(['controller' => 'users', 'action' => 'activate', 'code' => $activationCode], true);

                $replace = array($this->request->data['nick_name'], $link);
                $this->send_mail($to, $template, $replace);
                    // Send thank you mail to contacting person

                $this->Flash->set(__('Thank you for registering with us. Please check your e-mail inbox as your e-mail confirmation has just been sent.'), [
                    'params' => ['class' => 'alert alert-success alert-dismissible']
                    ]);
                return $this->redirect(['action' => 'login']);
            } else{
                $user = $this->Users->find('all', [
                    'conditions' => ['Users.id' => $this->loggedIn['id']],
                    'contain' =>
                    [
                    'Countries' => ['fields' => ['Countries.name']],
                    'Avatars'
                    ]
                    ]
                    )->first();
                $this->request->session()->write('Auth.User', $user->toArray());

                $this->Flash->set(__('Thank you for registering with us.'), [
                    'params' => ['class' => 'alert alert-success alert-dismissible']
                    ]);
                return $this->redirect(['action' => 'index']);
            }

        } 


    }
    //pr($user);die;
    if(!empty($this->request->data['muslim_since'])){
        $this->request->data = $user['muslim_since'] = array('year' => $this->request->data['muslim_since']);
    }

    // Remove Password
    $user['password'] = '';
    
    $countries = $this->{$this->modelClass}->Countries->find('list');
    $languages = $this->{$this->modelClass}->Languages->find('list');
    $this->Set(['countries' => $countries, 'languages' => $languages, 'user' => $user, 'title_for_layout' => 'Create An Account']);
}

public function edit() {

    $session = $this->request->session();
    $loggedInAs = $session->read('Auth.User');
    $this->Crud->action()->disable();
    $user = $this->Users->find('all', [
        'conditions' => ['Users.id' => $loggedInAs['id']],
        'contain' =>
        [
        'Countries' => ['fields' => ['Countries.name']],
        'Avatars'
        ]
        ]
        )->first();


    if ($this->request->is(['post', 'put'])) {
        if (empty($this->request->data['image']['name'])) {
            unset($this->request->data['image']);
        }

        if(!empty($this->request->data['image']['name'])){
            $this->request->data['avatar_id'] = null;
            $this->request->data['avatar_status'] = 'Image';
        }

        if(!empty($this->request->data['avatar_id'])){
            $this->request->data['avatar_status'] = 'Avatar';
        }

        $this->request->data['muslim_since'] = $this->request->data['muslim_since']['year'];

        $userEntity = $this->Users->patchEntity($user, $this->request->data);

        if ($result = $this->Users->save($userEntity)) {
            $user = $this->Users->find('all', [
                'conditions' => ['Users.id' => $loggedInAs['id']],
                'contain' =>
                [
                'Countries' => ['fields' => ['Countries.name']],
                'Avatars'
                ]
                ]
                )->first();
            $session->write('Auth.User', $user->toArray());
            $this->Flash->success(__('Profile has been updated successfully.'));
            return $this->redirect(['action' => 'view']);
        } else {
            $this->request->data['muslim_since'] = array("year" => $this->request->data['muslim_since']);
        }
        $this->Flash->error(__('Unable to update your profile.'));
    }
    $user['muslim_since'] = array('year' => $user['muslim_since']);
        // pr($user);die;
    $countries = $this->{$this->modelClass}->Countries->find('list');

    $this->viewBuilder()->layout(false);
    $this->Set(['countries' => $countries, 'user' => $user]);

    $this->set(['user' => $user]);
}

public function view($slug = null) {

    $session = $this->request->session();
    $loggedInAs = $session->read('Auth.User');
    if (!$slug) {
        $slug = $loggedInAs['slug'];
    }
    $this->Crud->action()->disable();
    $user = $this->Users->find('all', [
        'conditions' => ['Users.slug' => $slug],
        'contain' =>
        [
        'Languages',
        'Countries',
        'Avatars'
        ]
        ]
        )->first();

    if (empty($user)) {
        return $this->redirect(['action' => 'index']);
    }
        //pr($user);die;
    $friend = null;

    if ($slug != $loggedInAs['slug']) {
        $this->loadModel('Friends');
        $this->Friends->loggedInUser = $this->loggedIn;
        $friend = $this->Friends->isFriend($user);
    }

    $this->set(['user' => $user, 'friend' => $friend]);
}

public function getdetail(){
    $this->viewBuilder()->layout(false);
    $user = [];
    if($this->request->is('post')){
        $slug = $this->request->data['slug'];

        $user = $this->Users->find()
        ->contain(['Countries','Avatars'])
        ->where(['Users.slug' => $slug])
        ->select(['Users.id','Users.gender','Users.age', 'Users.first_name', 'Users.last_name', 'Users.slug', 'Users.image', 'Users.avatar_id', 'Users.avatar_status', 'Users.new_avatar', 'Countries.id', 'Countries.flag', 'Avatars.id', 'Avatars.image'])
        ->first();

    }

    $this->set(['user' => $user]);
}

public function login() {
        //echo (new DefaultPasswordHasher)->hash('123456');die;

    if ($this->loggedIn['id']) {
        $this->redirect(['action' => 'index']);
    }
    
    if ($this->request->is('post')) {
        $user = $this->Auth->identify();
        if ($user) {
            $this->Auth->setUser($user);
            $this->loadModel('RoomUsers');
            $room = $this->RoomUsers->getMostAccupantRoom();
            return $this->redirect($this->Auth->redirectUrl(['controller' => 'rooms','action' => 'view','slug' => $room]));
        } else {
            $this->Flash->error(__('Username or password is incorrect'), [
                'key' => 'auth'
                ]);
        }
    }
    $this->viewBuilder()->layout('front');
}

public function logout() {
    return $this->redirect($this->Auth->logout());
}

public function activate($token) {
    $user = $this->Users->find('all', ['conditions' => ['Users.token' => $token]])->first();

    if ($user) {
        $updateData = $this->Users->newEntity();
        $updateData->status = 1;
        $updateData->id = $user->id;
        $updateData->token = null;
        $updateData->token_expiry = null;
        if ($data = $this->Users->save($updateData)) {
            $this->Flash->set(__('ConfirmationAccount'), [
                'params' => ['class' => 'alert alert-success alert-dismissible']
                ]);
        }
    } else {
        $this->Flash->set(__('InvalidActCode'), [
            'params' => ['class' => 'alert alert-danger alert-dismissible']
            ]);
    }

    $this->redirect(['action' => 'login']);
}

public function forgot() {
    if ($this->request->is('post')) {

        if (empty($this->request->data['email'])) {
            $this->Flash->set(__('Enter your registered email address.'), [
                'params' => ['class' => 'alert alert-danger alert-dismissible']
                ]);
            return $this->redirect(['action' => 'forgot']);
        }
        $user = $this->Users->find('all', ['conditions' => ['Users.email' => $this->request->data['email']]])->first();

        if (!empty($user['token'])) {
            $this->Flash->set(__('InactiveAccount'), [
                'params' => ['class' => 'alert alert-danger alert-dismissible']
                ]);
            return $this->redirect(array('controller' => 'users', 'action' => 'forgot'));
        } else if (!isset($user['id'])) {
            $this->Flash->set(__('EmailDBCheck'), [
                'params' => ['class' => 'alert alert-danger alert-dismissible']
                ]);
        } else {

            if (count($user) > 0) {
                $to = $user['email'];
                $template = 'Reset-Password';
                $activationCode = md5(microtime() . $user['id']);
                $now = Time::now();
                $now->modify('+3 days');
                $user->token = $activationCode;
                $user->token_expiry = $now;
                $this->Users->save($user);

                $link = \Cake\Routing\Router::url(['controller' => 'users', 'action' => 'resetPassword', 'code' => $activationCode], true);
                $replace = array($user['name'], $link);
                $this->send_mail($to, $template, $replace);


                $this->Flash->set(__('PasswordEmail'), [
                    'params' => ['class' => 'alert alert-success alert-dismissible']
                    ]);
            } else {
                $this->Flash->set(__('CommonError'), [
                    'params' => ['class' => 'alert alert-danger alert-dismissible']
                    ]);
            }
        }
        return $this->redirect(['action' => 'login']);
    }

    $this->viewBuilder()->layout('home');
}

public function resetPassword($token) {
    $user = $this->Users->find('all', ['conditions' => ['Users.token' => $token]])->first();

    if (!$user) {
        $this->Flash->set(__('CommonError'), [
            'params' => ['class' => 'alert alert-danger alert-dismissible']
            ]);
        return $this->redirect(['action' => 'login']);
    }

    if ($this->request->is(['post', 'put'])) {

        $user = $this->Users->patchEntity($user, $this->request->data, ['validate' => 'forgotPassword']);
        $user->token = null;
        $user->token_expiry = null;

        if ($this->Users->save($user)) {
            $this->Flash->set(__('PasswordReset'), [
                'params' => ['class' => 'alert alert-success alert-dismissible']
                ]);
            return $this->redirect(['action' => 'login']);
        }
    }

    $this->set(['user' => $user]);
    $this->viewBuilder()->layout('home');
}

public function actionAvatar($action){
    $user_id = $this->loggedIn['id'];
    if($this->loggedIn['avatar_status'] == 'Pending' && $action == 'accept'){
        $user = $this->Users->get($user_id);
        $user->avatar_id = null;
        $user->avatar_status = 'Accepted';
        if($this->Users->save($user)){
            $this->request->session()->write('Auth.User', $user->toArray());
        }
    }
    if($this->loggedIn['avatar_status'] == 'Pending' && $action == 'decline'){
        $user = $this->Users->get($user_id);
        $user->avatar_status = 'Declined';
        if($this->Users->save($user)){
            $this->request->session()->write('Auth.User', $user->toArray());
        }
    }
    $this->redirect($this->referer());
}

}

<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use \GameNet\Jabber\RpcClient;

class FriendsController extends AppController {

    public function initialize() {
        parent::initialize();
    }

    public function index() {
        $this->Crud->action()->disable();
        $user_id = $this->loggedIn['id'];

        $this->viewBuilder()->layout('ajax');

        $this->loadModel('Users');

        $customFinderOptions = array('user_id' => $user_id);

        $this->paginate = [
            'finder' => [
                'friends' => $customFinderOptions
            ]
        ];
        $friends = $this->paginate($this->Users);

        $this->set(['friends' => $friends]);
    }

    public function addFriendRequest() {
        if ($this->request->is('post')) {

            $friend = $this->request->query('friend');
            $this->loadModel('Users');
            $friend = $this->Users->findById($friend)->first()->toArray();
            
            $this->{$this->modelClass}->loggedInUser = $this->loggedIn;

            $isFriend = $this->{$this->modelClass}->isFriend($friend);

            if (!$isFriend) {
                if ($this->{$this->modelClass}->addFriendRequest($friend)) {

                    $rpc = new \GameNet\Jabber\RpcClient([
                        'server' => Configure::read('XamppServer.server'),
                        'host' => Configure::read('XamppServer.host'),
                        'debug' => Configure::read('debug'),
                    ]);

                    $rpc->addRosterItem($this->loggedIn['slug'], $friend['slug'], $friend['first_name'].' '.$friend['last_name'], 'Friend');
                    $rpc->addRosterItem($friend['slug'], $this->loggedIn['slug'], $this->loggedIn['first_name'].' '.$this->loggedIn['last_name'], 'Friend');

                    $this->Flash->set(__('Your friend request has been sent.'), [
                        'params' => ['class' => 'alert alert-success alert-dismissible']
                    ]);
                }
            } else {
                $this->Flash->set(__('%s is alredy in your friend list.',$friend['first_name'] . ' ' . $friend['last_name']), [
                    'params' => ['class' => 'alert alert-danger alert-dismissible']
                ]);
            }

            $this->redirect($this->referer());
        }
    }

    public function block() {
        if ($this->request->is('post')) {

            $friend = $this->request->query('friend');
            $this->loadModel('Users');
            $friend = $this->Users->findById($friend)->first()->toArray();

            $this->{$this->modelClass}->loggedInUser = $this->loggedIn;

            $isFriend = $this->{$this->modelClass}->isFriend($friend);

            if ($isFriend) {
                if ($this->{$this->modelClass}->block($friend)) {
                    $message = __('%s has been blocked.',$friend['first_name']);
                    $status = true;
                } else {
                    $status = false;
                    $message = __('You have not send friend request yet.');
                }
            } else {
                $status = false;
                $message = __('You have not send friend request yet.');
            }
            echo json_encode(["status" => $status, 'message' => $message]);
            exit;
            
        }
    }
    
    
    public function unfriend() {
        if ($this->request->is('post')) {

            $friend = $this->request->query('friend');
            $this->loadModel('Users');
            $friend = $this->Users->findById($friend)->first()->toArray();

            $this->{$this->modelClass}->loggedInUser = $this->loggedIn;

            $isFriend = $this->{$this->modelClass}->isFriend($friend);

            if ($isFriend) {
                if ($this->{$this->modelClass}->unfriend($friend)) {
                    $message = __('%s has been blocked.',$friend['first_name']);
                    $status = true;
                } else {
                    $status = false;
                    $message = __('You have not send friend request yet.');
                }
            } else {
                $status = false;
                $message = __('You have not send friend request yet.');
            }
            echo json_encode(["status" => $status, 'message' => $message]);
            exit;
            
        }
    }

    public function acceptFriendRequest() {
        if ($this->request->is('post')) {

            $friend = $this->request->query('friend');
            $this->loadModel('Users');
            $friend = $this->Users->findById($friend)->first()->toArray();

            $this->{$this->modelClass}->loggedInUser = $this->loggedIn;

            $isFriend = $this->{$this->modelClass}->isFriend($friend);

            if ($isFriend) {
                if ($this->{$this->modelClass}->acceptFriendRequest($friend)) {
                    $this->Flash->set(__('Friend request has been accepted.'), [
                        'params' => ['class' => 'alert alert-success alert-dismissible']
                    ]);
                }
            } else {
                $this->Flash->set(__('You have not send friend request yet.'), [
                    'params' => ['class' => 'alert alert-danger alert-dismissible']
                ]);
            }

            $this->redirect($this->referer());
        }
    }

    public function declineFriendRequest() {
        if ($this->request->is('post')) {

            $friend = $this->request->query('friend');
            $this->loadModel('Users');
            $friend = $this->Users->findById($friend)->first()->toArray();

            $this->{$this->modelClass}->loggedInUser = $this->loggedIn;

            $isFriend = $this->{$this->modelClass}->isFriend($friend);

            if ($isFriend) {
                if ($this->{$this->modelClass}->declineFriendRequest($friend)) {
                    $this->Flash->set(__('Friend request has been declineed.'), [
                        'params' => ['class' => 'alert alert-success alert-dismissible']
                    ]);
                }
            } else {
                $this->Flash->set(__('You have not send friend request yet.'), [
                    'params' => ['class' => 'alert alert-danger alert-dismissible']
                ]);
            }

            $this->redirect($this->referer());
        }
    }

    public function request() {
        $user_id = $this->loggedIn['id'];

        $this->loadModel('Users');

        // $query->select('Friends.id');
        $customFinderOptions = array('user_id' => $user_id);

        $this->paginate = [
            'finder' => [
                'friendReuests' => $customFinderOptions
            ]
        ];
        $friends = $this->paginate($this->Users);

        $this->set(['friends' => $friends]);
    }

}

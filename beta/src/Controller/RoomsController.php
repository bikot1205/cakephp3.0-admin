<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use \GameNet\Jabber\RpcClient;
use Cake\I18n\Time;
use Cake\Datasource\ConnectionManager;

class RoomsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Crud->addListener('Crud.Search');
    }

    public function join($slug){
        $room = $this->Rooms->find('all', ['conditions' => ['Rooms.slug' => $slug]])->first();
        $user = $this->loggedIn['slug'];
        $user_id = $this->loggedIn['id'];
        $response = array('status' => true,'message' => __('Please try again.'));
        if (! empty($room)) {

            // Add User As Room Member 
            $this->loadModel('RoomUsers');
            $isAdded  =$this->RoomUsers->find()->where(['RoomUsers.room_id' => $room->id,'RoomUsers.user_id' => $user_id ]);
            
            if($isAdded->count() == 0 ){

                $newUser = $this->RoomUsers->newEntity();
                $newUser->user_id = $user_id;
                $newUser->room_id = $room->id;
                if($this->RoomUsers->save($newUser)){

                }
            }
            
            // Add a xmpp User
            $rpc = new \GameNet\Jabber\RpcClient([
                'server' => Configure::read('XamppServer.server'),
                'host' => Configure::read('XamppServer.host'),
                'debug' => Configure::read('debug'),
                ]);
            
            // User's real JID
            $userJID = $user.'@'.Configure::read('XamppServer.host');

            // Get room total accupant users
            $noOfAccupants = $rpc->getNoRoomOccupants($slug);

            if($noOfAccupants < $room->occupancy){
                $noOfAccupants = $rpc->setRoomAffiliation($slug, $userJID, 'member');
                $response = array('status' => true,'message' => __('Your request has been sent.'));
            } 
            else{
                $response = array('status' => true,'message' => __('There is no space in this chat room, please re check after sometimes.'));
            }  
            $response['url'] = \Cake\Routing\Router::url(['controller' => 'rooms','action' => 'view','slug' => $slug],true);

            echo json_encode($response);
            exit;  
            
        }
    }

    public function view($slug = null) {
         // $this->viewBuilder()->layout('chat');
        $this->request->session()->write('room',$slug);
        $this->set(['slug' => $slug,'activeTab' => 'Chatbox']);
    }

    public function view_old($slug = null) {
        if(empty($slug)){
            $this->loadModel('RoomUsers');

            $result = $this->RoomUsers->find()->contain('Rooms')->where(['RoomUsers.user_id' => $this->loggedIn['id']])->select('Rooms.slug')->first();

            $slug = $result->Rooms->slug;
        }
        $user_id = $this->loggedIn['id'];
        $this->Crud->action()->disable();
        $room = $this->Rooms->find('all', [
            'conditions' => ['Rooms.slug' => $slug],
            'contain' =>
            [
            'Moderator',
            ]
            ]
            )->first();

        if (empty($room)) {
            return $this->redirect(['action' => 'index']);
        }
        $this->viewBuilder()->layout('chat');


         // Get Current Active Users
        $rpc = new \GameNet\Jabber\RpcClient([
            'server' => Configure::read('XamppServer.server'),
            'host' => Configure::read('XamppServer.host'),
            'debug' => Configure::read('debug'),
            ]);
        
        // User's real JID

        // Get room total accupant users
        $accupants = $rpc->getNoRoomOccupants($room->slug);
        
        if($accupants >= $room->occupancy){
            $this->set(['room' => $room,'accupants' => $accupants]);
            $this->render('notspance');
        }

        $this->loadModel('RoomUsers');

        // Add User As Room Member 
        $this->loadModel('RoomUsers');
        $isAdded  =$this->RoomUsers->find()->where(['RoomUsers.room_id' => $room->id,'RoomUsers.user_id' => $user_id ]);
        
        if($isAdded->count() == 0 ){

            $newUser = $this->RoomUsers->newEntity();
            $newUser->user_id = $user_id;
            $newUser->room_id = $room->id;
            if(!$this->RoomUsers->save($newUser)){
                return $this->redirect($this->referer());                  
            }
        }

        $members  = $this->RoomUsers->find('all');
        $members->contain(['Users' => ['fields' => [
            'Users.id','Users.gender','Users.age', 'Users.first_name', 'Users.last_name', 'Users.slug', 'Users.image', 'Users.avatar_status', 'Users.new_avatar', 'Users.avatar_id', 'Users.muslim_since', 'Countries.id', 'Countries.flag', 'Avatars.id', 'Avatars.image'
            ],'Countries','Avatars']]);
        $members->where(['room_id' => $room->id]);
        
        $members = $members->toArray();

        $this->loadModel('Smiles');

        $smiles = $this->Smiles->find()->where(['status' => 1])->select(['name','code','image'])->toArray();

        $this->set(['room' => $room,'members' => $members,'accupants' => $accupants,'smiles' => $smiles]);
    }

    public function roomData($slug = null) {

        $user_id = $this->loggedIn['id'];

        if(empty($slug)){
            $room = $this->Rooms->find('all', [
            // 'conditions' => ['Rooms.slug' => $slug],
                'contain' =>
                [
                'Moderator',
                ]
                ]
                )->first();
        }
        else{
           $room = $this->Rooms->find('all', [
            'conditions' => ['Rooms.slug' => $slug],
            'contain' =>
            [
            'Moderator',
            ]
            ]
            )->first();
       }


       $this->loadModel('RoomUsers');

       $members  = $this->RoomUsers->find('all');
       $members->contain(['Users' => ['fields' => [
        'Users.id','Users.gender','Users.age', 'Users.first_name', 'Users.last_name', 'Users.slug', 'Users.image', 'Users.avatar_status', 'Users.new_avatar', 'Users.avatar_id', 'Users.muslim_since', 'Countries.id', 'Countries.flag', 'Avatars.id', 'Avatars.image'
        ],'Countries','Avatars']]);
       $members->where(['room_id' => $room->id]);

       $members = $members->toArray();

       $this->loadModel('Smiles');

       $smiles = $this->Smiles->find()->where(['status' => 1])->select(['name','code','image'])->toArray();
       $this->viewBuilder()->layout(false);
       $this->set(['room' => $room,'members' => $members,'smiles' => $smiles]);
   }

   public function recentChat(){
        // $this->viewBuilder()->layout('chat');
        //pr($this->loggedIn);die;
        $user = $this->loggedIn['slug'];//'admin';//
        $connection = ConnectionManager::get('ejabberd');
        $recentChats = $connection->execute('SELECT SUBSTRING_INDEX(bare_peer, "@", 1) as room, SUBSTRING_INDEX(peer, "/", -1) as userSlug,txt, timestamp, created_at
            FROM archive
            WHERE id IN (
            SELECT MAX(id)
            FROM archive
            WHERE username = "'.$user.'"
            AND kind = "chat"
            GROUP BY username, bare_peer
            );')->fetchAll('assoc');

        $this->loadModel('Users');

        foreach ($recentChats as $key => $chat) {


            $conditions = ['Users.slug =' => $chat['userSlug']];
            
            $query = $this->Users->find();
            $query
            ->contain(['Avatars'])
            ->select(['friend.status', 'friend.user_one_id', 'friend.user_two_id', 'friend.action_user_id', 'Users.id', 'Users.first_name', 'Users.last_name', 'Users.slug', 'Users.image', 'Users.avatar_id', 'Users.muslim_since', 'Users.avatar_status', 'Users.new_avatar', 'Avatars.id', 'Avatars.image'])
            ->join(['friend' => [
                'table' => 'friends',
                'type' => 'LEFT',
                'conditions' => 'CASE WHEN friend.user_one_id = Users.id THEN friend.user_two_id = Users.id WHEN friend.user_two_id= Users.id THEN friend.user_one_id= Users.id END',
                ]
                ])
            ->where($conditions);
            if($query->count() > 0 ) {
                $recentChats[$key]['user'] = $query->first()->toArray();
                $rooms = $this->Rooms->find()->where(['slug' => $chat['room']])->select(['id','name','slug'])->first();
                $recentChats[$key]['room']  = $rooms->toArray();
            }
            else{
                unset($recentChats[$key]);
            }

            
            
        }
        $this->viewBuilder()->layout(false);
        $this->set(['recentChats' => $recentChats]);
    }

    public function myChat() {
        // $this->viewBuilder()->layout('chat');
        //pr($this->loggedIn);die;
        $user = $this->loggedIn['slug'];//'admin';//
        $connection = ConnectionManager::get('ejabberd');
        $recentChats = $connection->execute('SELECT SUBSTRING_INDEX(bare_peer, "@", 1) as room, SUBSTRING_INDEX(peer, "/", -1) as userSlug,txt, timestamp, created_at
            FROM archive
            WHERE id IN (
            SELECT MAX(id)
            FROM archive
            WHERE username = "'.$user.'"
            AND kind = "chat"
            GROUP BY username, bare_peer
            );')->fetchAll('assoc');

        $this->loadModel('Users');

        foreach ($recentChats as $key => $chat) {


            $conditions = ['Users.slug =' => $chat['userSlug']];
            
            $query = $this->Users->find();
            $query
            ->contain(['Avatars'])
            ->select(['friend.status', 'friend.user_one_id', 'friend.user_two_id', 'friend.action_user_id', 'Users.id', 'Users.first_name', 'Users.last_name', 'Users.slug', 'Users.image', 'Users.avatar_id', 'Users.muslim_since', 'Users.avatar_status', 'Users.new_avatar', 'Avatars.id', 'Avatars.image'])
            ->join(['friend' => [
                'table' => 'friends',
                'type' => 'LEFT',
                'conditions' => 'CASE WHEN friend.user_one_id = Users.id THEN friend.user_two_id = Users.id WHEN friend.user_two_id= Users.id THEN friend.user_one_id= Users.id END',
                ]
                ])
            ->where($conditions);
            if($query->count() > 0 ) {
                $recentChats[$key]['user'] = $query->first()->toArray();
                $rooms = $this->Rooms->find()->where(['slug' => $chat['room']])->select(['id','name','slug'])->first();
                $recentChats[$key]['room']  = $rooms->toArray();
            }
            else{
                unset($recentChats[$key]);
            }

            
            
        }

        $this->set(['recentChats' => $recentChats]);
    }

    public function generateImageToken(){
        $this->loadModel('Attachments');
        $newEntity = $this->Attachments->newEntity($this->request->data);
        $token = false;
        if($this->Attachments->save($newEntity)){
            $token = $newEntity->file;

        }
        echo json_encode(array('token' => $token));
        die;
    }

    public function download($file){
        $file_path = WWW_ROOT.'files'.DS.'Attachments'.DS.'file'.DS.$file;
        $this->response->file($file_path, array(
            'download' => true,
            'name' => $file,
            ));
        return $this->response;
    }

    public function sendInvitation(){
        if($this->request->is('post')){
            $emails = $this->request->data['emails'];
            $emails = explode(',', $emails);
            $room = $this->request->session()->read('room');
            $room = $this->Rooms->findBySlug($room);
            $room = $room->first();

            $this->loadModel('Invitations');
            foreach($emails as $email){
                $data['email_address'] = $email;
                $data['user_id'] = $this->loggedIn['id'];
                if($room){
                    $data['room_id'] = $room->id;
                }
                $data['code'] = md5(microtime() . $email);
                $data['created'] = Time::now();
                $now = Time::now();
                $now->modify('+3 days');
                $data['expired'] = Time::now();
                $data['modified'] = $now;
                $newEntity = $this->Invitations->newEntity($data);
                if($this->Invitations->save($newEntity)){
                    // send Invitations email 

                    $template = 'Send-Invitation';
                    
                    $link = \Cake\Routing\Router::url(['controller' => 'users', 'action' => 'add', 'code' => $data['code']], true);

                    $replace = array($email, $link);
                    $this->send_mail($email, $template, $replace);
                }

            }
        }
        echo json_encode(array('status' => true,'message' => 'Invitations has been sent to entered email addresses.'));
        die;
    }

}

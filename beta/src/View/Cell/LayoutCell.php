<?php

namespace App\View\Cell;

use Cake\View\Cell;
use Cake\Core\Configure;
use \GameNet\Jabber\RpcClient;

class LayoutCell extends Cell {

    public function avatars() {
        $this->loadModel('Avatars');
        
        $avatars = $this->Avatars->find('all');
        
        $this->set('avatars',$avatars);
    }

    public function rooms($display = false){
    	$this->loadModel('Rooms');
        
        $rooms = $this->Rooms->find('all',['conditions' => ['Rooms.status' => 1]]);
        
        $this->set('rooms',$rooms);
        $this->set('display',$display);
    }

    public function roomDetails($slug = null, $loggedIn){
        if(empty($slug)){
            $this->loadModel('RoomUsers');

            $result = $this->RoomUsers->find()->contain('Rooms')->where(['RoomUsers.user_id' => $loggedIn['id']])->select('Rooms.slug')->first();

            $slug = $result->Rooms->slug;
        }

        $this->loadModel('Rooms');

        $user_id = $loggedIn['id'];
       
        $room = $this->Rooms->find('all', [
            'conditions' => ['Rooms.slug' => $slug],
            'contain' =>
            [
            'Moderator',
            ]
            ]
            )->first();

        $this->loadModel('RoomUsers');

         // Get Current Active Users
        $rpc = new \GameNet\Jabber\RpcClient([
            'server' => Configure::read('XamppServer.server'),
            'host' => Configure::read('XamppServer.host'),
            'debug' => Configure::read('debug'),
            ]);
        
        // User's real JID

        // Get room total accupant users
        $accupants = $rpc->getNoRoomOccupants($room->slug);
        

        // Add User As Room Member 
        $this->loadModel('RoomUsers');
        $isAdded  =$this->RoomUsers->find()->where(['RoomUsers.room_id' => $room->id,'RoomUsers.user_id' => $user_id ]);
        
        if($isAdded->count() == 0 ){

            $newUser = $this->RoomUsers->newEntity();
            $newUser->user_id = $user_id;
            $newUser->room_id = $room->id;
            if($room->type == 2){
                $newUser->status = 0;
            }
            if(!$this->RoomUsers->save($newUser)){
                return $this->redirect($this->referer());                  
            }
        }
        $this->set(['room' => $room,'accupants' => $accupants]);
    }

}

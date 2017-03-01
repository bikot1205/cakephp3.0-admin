<?php

namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;
use \GameNet\Jabber\RpcClient;
use Cake\Core\Configure;

class RoomsController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Crud->addListener('Crud.Search');
    }

    public function index() {
        $this->Crud->on('beforePaginate', function(\Cake\Event\Event $event) {
            $this->paginate['contain'] = ['Moderator'];
            $this->paginate['group'] = ['Rooms.id'];
        });
        $this->Crud->on('beforeRender', function(\Cake\Event\Event $event) {
            $this->request->data = $this->request->query;
            $room = $this->{$this->modelClass}->newEntity();
            $this->Set(['room' => $room]);
        });

        return $this->Crud->execute();
    }

    public function add() {

        if (empty($this->request->data['image']['name'])) {
            unset($this->request->data['image']);
        }

        if (empty($this->request->data['icon']['name'])) {
            unset($this->request->data['icon']);
        }

        $this->Crud->on('afterSave', function(\Cake\Event\Event $event) {
            if ($event->subject()->created) {
                $rpc = new \GameNet\Jabber\RpcClient([
                    'server' => Configure::read('XamppServer.server'),
                    'host' => Configure::read('XamppServer.host'),
                    'debug' => Configure::read('debug'),
                ]);
                $roomName = $event->subject()->entity->slug;
                $Rooms = $rpc->createRoom($roomName);

                $rpc->setRoomOption($roomName,'max_users',$event->subject()->entity->occupancy);

                $public = ($event->subject()->entity->type == 1) ? true : false;

                $rpc->setRoomOption($roomName,'public',$public);

            } 
        });

        return $this->Crud->execute();
    }

    public function edit($id) {
        if (empty($this->request->data['image']['name'])) {
            unset($this->request->data['image']);
        }

        if (empty($this->request->data['icon']['name'])) {
            unset($this->request->data['icon']);
        }
        $this->Crud->action()->view('add');

        return $this->Crud->execute();
    }

    public function view($slug) {

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

        if (!empty($this->request->data)) {
            if (empty($this->request->data['icon']['name'])) {
                unset($this->request->data['icon']);
            }
            $this->request->data['id'] = $room->id;
            $this->request->data['moderator'] = $room->moderator;
            
            $userEntity = $this->Rooms->patchEntity($room, $this->request->data, ['associated' => []]);

            if ($this->Rooms->save($userEntity)) {

                // Update Xmpp Room

                $rpc = new \GameNet\Jabber\RpcClient([
                    'server' => Configure::read('XamppServer.server'),
                    'host' => Configure::read('XamppServer.host'),
                    'debug' => Configure::read('debug'),
                ]);

                $roomName = $room->slug;
                $rpc->setRoomOption($roomName,'max_users',$room->occupancy);

                $public = ($room->type == 1) ? true : false;

                $rpc->setRoomOption($roomName,'public',$public);

                $this->Flash->success(__('Room has been created successfully.'));
                return $this->redirect(['action' => 'view', $slug]);
            } 

            $this->Flash->error(__('Unable to update room.'));
        }

        $this->loadModel('RoomUsers');
        $members = $this->RoomUsers->find('all')->where(['RoomUsers.room_id' => $room->id])->contain(['Users','Users.Countries','Users.Avatars']);

        $this->set(['room' => $room,'members' => $members]);
    }

    public function updateDesign($id) {
        $room = $this->Rooms->find('all', [
                    'conditions' => ['Rooms.id' => $id]]
                )->first();

        $status = false;
        $message = 'Unable to update room design.';
        if (!empty($room) && !empty($this->request->data)) {
            if (empty($this->request->data['image']['name'])) {
                unset($this->request->data['image']);
            }

            $userEntity = $this->Rooms->patchEntity($room,$this->request->data, ['validate' => false]);
            if ($this->Rooms->save($userEntity)) {
                $status = true;
                $message = 'Room Design has been updated successfully.';
            } else {
                $status = false;
                $message = 'Unable to update room design.';
            }
        }
        echo json_encode(array('status' => $status, 'message' => $message,'room' => $userEntity));
        exit;
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

    public function delete($id) {
        $this->Crud->on('beforeDelete', function(\Cake\Event\Event $event) {
            $rpc = new \GameNet\Jabber\RpcClient([
                'server' => Configure::read('XamppServer.server'),
                'host' => Configure::read('XamppServer.host'),
                'debug' => Configure::read('debug'),
            ]);

            $roomName = $event->subject()->entity->slug;
            $Rooms = $rpc->deleteRoom($roomName);
        });

        return $this->Crud->execute();
    }

}

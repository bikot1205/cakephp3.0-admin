<?php
namespace App\Controller\Admin;

use App\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;


class DashboardController extends AppController
{
    public function index()
    {
        
    }

    public function updateStatus(){
    	$model = $this->request->data['model'];
    	$value = $this->request->data['value'];
    	$id = $this->request->data['id'];
    	$field = $this->request->data['field'];
    	$status = false;

		$modelClass = TableRegistry::get($model);
		$entity = $modelClass->get($id); 

		$entity->$field = $value;
		if($modelClass->save($entity)){
			$status = true;
		}
		echo json_encode(['status' => $status]);
		exit;
    }

    public function totalUsers(){
        $this->loadModel('Users');
        $query = $this->Users->find();
        $query->select(['count' => $query->func()->count('*'),'created'])->group('DATE(created)')->order(['DATE(created)' => 'ASC']);
        $query->where(['Users.role_id' => 1]);
        

        foreach ($query->toArray() as $data) {
            $result[] = array(
                'date' => date('Y-m-d', strtotime($data->created)),
                'value' => $data->count
            );
            
        }

        echo json_encode($result);
        exit;
    }

    public function topCountries(){
        $conditions = array('Users.role_id' => 1);
        $this->loadModel('Users');
        $query = $this->Users->find();
        $query->contain(['Countries']);
        $query->select(['count' => $query->func()->count('*'),'country_id','Countries.name'])->group('country_id')->order(['count' => 'DESC']);
        $query->where(['Users.role_id' => 1]);
        $query->limit(10);

        if($query->count() <= 0 ){
            $result[] = array(
                'country' => 'None',
                'color' => '#B0DE09',
                'visits' => 0
            );
        }

        foreach ($query->toArray() as $data) {
            $result[] = array(
                'country' => $data->country->name,
                'color' => '#B0DE09',
                'visits' => $data->count
            );
            
        }

        echo json_encode($result);
        exit;
    }

     public function topLanguages(){
        $conditions = array('Users.role_id' => 1);
        $this->loadModel('UsersLanguages');
        $query = $this->UsersLanguages->find();
        $query->contain(['Languages']);
        $query->select(['count' => $query->func()->count('*'),'user_id','language_id','Languages.name'])->group('language_id')->order(['count' => 'DESC']);
        $query->limit(10);

        if($query->count() <= 0 ){
            $result[] = array(
                'country' => 'None',
                'color' => '#B0DE09',
                'visits' => 0
            );
        }

        foreach ($query->toArray() as $data) {
            $result[] = array(
                'country' => $data->language->name,
                'color' => '#B0DE09',
                'visits' => $data->count
            );
            
        }

        echo json_encode($result);
        exit;
    }

    public function topRooms(){
        $conditions = array('Users.role_id' => 1);
        $this->loadModel('RoomUsers');
        $query = $this->RoomUsers->find();
        $query->contain(['Rooms']);
        $query->select(['count' => $query->func()->count('*'),'user_id','room_id','Rooms.name'])->group('room_id')->order(['count' => 'DESC']);
        $query->limit(10);
        $query->where(['Rooms.name != ' => '']);

        if($query->count() <= 0 ){
            $result[] = array(
                'country' => 'None',
                'color' => '#B0DE09',
                'visits' => 0
            );
        }
        
        foreach ($query->toArray() as $data) {
           
            $result[] = array(
                'country' => $data->room->name,
                'color' => '#B0DE09',
                'visits' => $data->count
            );
            
        }

        echo json_encode($result);
        exit;
    }

    public function ageGroup(){
        $conditions = array('Users.role_id' => 1);
        $this->loadModel('Users');
        

        $userEntity = $this->Users->newEntity();
        
        foreach ($userEntity->ageRange as $key => $ageRange) {
            if($key == 0 ){
                continue;
            }
            $age = explode('-', $ageRange['value']);
            
            $query = $this->Users->find();
            $query->select(['id']);
            $query->where(['Users.role_id' => 1,'Users.age >= ' => $age[0],'Users.age <= ' => $age[1]]);
            $count = $query->count();
            if($count){
                $result[] = array(
                'country' => $ageRange['text'],
                'color' => '#B0DE09',
                'visits' => $query->count()
                );
            }
            

        }
        echo json_encode($result);
        exit;
    }

    public function genderGroup(){
        $this->loadModel('Users');
        $query = $this->Users->find();
        $query->select(['count' => $query->func()->count('*'),'gender'])->group('gender')->order(['count' => 'DESC']);
        $query->where(['Users.role_id' => 1]);
        

        foreach ($query->toArray() as $data) {
            $result[] = array(
                'country' => ($data->gender == 'M') ? 'Male' : 'Female',
                'color' => '#B0DE09',
                'visits' => $data->count
            );
            
        }

        echo json_encode($result);
        exit;
    }



}
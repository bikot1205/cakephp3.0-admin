<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class RoomUsersTable extends Table {

	public function initialize(array $config) {
		$this->addBehavior('Timestamp');
		$this->belongsTo('Rooms');
		$this->belongsTo('Users');
	}

	public function getMostAccupantRoom(){
		$query = $this->find();
		$query->group(['room_id']);
		$query->contain(['Rooms']);
		$query->select(['users' => $query->func()->count('user_id'),'room_id','Rooms.id','Rooms.slug']);
		$query->order(['users' => 'DESC']);
		$result = $query->first();

		if($query->count() > 0){
			return $result->room->slug;
		}
		else{
			$query = $this->Rooms->find();
			$query->select(['id','slug']);
			$result = $query->first();
			if($query->count() > 0){
				return $result->slug;
			}
		}
	}


}

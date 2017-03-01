<?php

namespace App\View\Cell;

use Cake\View\Cell;
use Cake\ORM\TableRegistry;

class UserCell extends Cell {

    public function friends($user) {
        $friends = TableRegistry::get('Friends');
        $result = $friends->find()->select(['Users.id', 'Users.first_name', 'Users.last_name', 'Users.image', 'Users.avatar_id', 'Users.slug', 'Users.new_avatar', 'Users.avatar_status'])->hydrate(false)->join([
            'table' => 'users',
            'alias' => 'Users',
            'type' => 'INNER',
            'conditions' => '((Friends.user_one_id = Users.id AND Friends.user_two_id=' . $user . ')
or (Friends.user_two_id = Users.id AND Friends.user_one_id=' . $user . '))',
        ]);
        $result = $result->toArray();
        $this->set(['friends' => $result]);
    }

    public function subscription($user) {
        $friends = TableRegistry::get('RoomModerators');
        $result = $friends->find()->where(['RoomModerators.user_id' => $user]);
        $result = $result->count();
        $this->set(['result' => $result]);
    }

    public function filters() {
        $user = TableRegistry::get('Users');
        $user = $user->newEntity();
        $countries = TableRegistry::get('Users')->Countries->find('list')->toArray();
        $countriesList[0] = ['value' => '', 'text' => __('All'), 'title' => __('By Country')];
        foreach($countries as $key => $value ){
            $countriesList[$key] = ['value' => $key, 'text' => $value, 'title' => __('By Country')];
        }
        $countriesList[0] = ['value' => '', 'text' => __('All'), 'title' => __('By Country')];
        
        $languages = TableRegistry::get('Users')->Languages->find('list')->where(['status' => 1])->toArray();
        $languagesList[0] = ['value' => '', 'text' => __('All'), 'title' => __('By Language')];
        foreach($languages as $key => $value ){
            $languagesList[$key] = ['value' => $key, 'text' => $value, 'title' => __('By Language')];
        }
        
        $this->Set(['user' => $user, 'countries' => $countriesList,'languages' => $languagesList]);
    }
    
    public function contacts() {
        $query = $this->loadModel('Friends');
    }
    public function activeUsers() {
        
    }

}

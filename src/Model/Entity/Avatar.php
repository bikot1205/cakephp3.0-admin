<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Event\Event;
use ArrayObject;

class Avatar extends Entity {

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options) {
        if (isset($data['username'])) {
            $data['username'] = mb_strtolower($data['username']);
        }
    }

}

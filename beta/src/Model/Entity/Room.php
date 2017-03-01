<?php

namespace App\Model\Entity;
use Cake\ORM\Entity;

class Room extends Entity {

   
    protected function _getTypes() {
        $ypes = [1 => 'Public',2 => 'Private'];
        return $ypes;
    }
    
    protected function _getOccupancyLevels() {
        
        for($start = 10; $start <= 50; $start = $start + 5){
            $ypes[$start] = $start;
        }
        return $ypes;
    }

}

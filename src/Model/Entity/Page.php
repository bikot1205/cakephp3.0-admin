<?php

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class Page extends Entity {

    protected function _getTitleEn_us() {
        return $this->_properties['title_eng'];
               
    }

    protected function _getTitleEs_ar() {
        
        return $this->_properties['title_spa'];
    }

    protected function _getDescriptionEn_us() {
        return $this->_properties['description_eng'];
               
    }

    protected function _getDescriptionEs_ar() {
        return $this->_properties['description_spa'];
    }

}

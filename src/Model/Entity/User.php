<?php

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class User extends Entity {

    protected $_accessible = [
        'first_name' => true,
        'last_name' => true,
        'image' => true,
        'email' => true,
        'password' => true,
        'languages' => true,
        'nick_name' => true,
        'age' => true,
        'gender' => true,
        'muslim_since' => true,
        'country_id' => true,
        'new_avatar' => true,
        'avatar_status' => true,
        'avatar_id' => true,
        'slug' => true,
        'status' => true,
    ];

    protected function _setPassword($password) {
        if (strlen($password) > 0) {
            return (new DefaultPasswordHasher)->hash($password);
        }
    }

    protected function _getName() {
        return $this->_properties['first_name'] . '  ' .
                $this->_properties['last_name'];
    }

    protected function _getGenders() {
        return ['Male' => 'Male', 'Female' => 'Female'];
    }

    protected function _getGenderTxt() {
        return ($this->_properties['gender'] == 'M') ? 'Male' : 'Female';
    }

    protected function _getMuslimYear() {
        $years = date('Y') - $this->_properties['muslim_since'];
        $years = ($years > 1) ? $years . ' years' : $years . ' year';
        return $years;
    }

    protected function _getMuslimYears() {
        $years = date('Y') - $this->_properties['muslim_since'];
        $char = strlen($years);

        switch ($char) {
            case 1:
                $years = '00' . $years;
                break;
            case 2:
                $years = '0' . $years;
                break;

            default:
                $years =  $years;
        }

        return $years;
    }

    protected function _getAgeList() {
        $age = [];
        for ($ageStart = 16; $ageStart < 40; $ageStart++) {
            $age[$ageStart] = ($ageStart == 1) ? $ageStart . ' Year' : $ageStart . ' Years';
        }
        $age['40'] = '40+ Years';
        return $age;
    }

    protected function _getAgeRange() {
        $age[] = ['value' => '', 'text' => 'All', 'title' => __('By Age')];
        $age[] = ['value' => '16-20', 'text' => '16 Years to 20 Years', 'title' => __('By Age')];
        $age[] = ['value' => '21-25', 'text' => '21 Years to 25 Years', 'title' => __('By Age')];
        $age[] = ['value' => '26-30', 'text' => '26 Years to 30 Years', 'title' => __('By Age')];
        $age[] = ['value' => '31-35', 'text' => '31 Years to 35 Years', 'title' => __('By Age')];
        $age[] = ['value' => '36-40', 'text' => '36 Years to 40+ Years', 'title' => __('By Age')];
        return $age;
    }

}

<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;

class FeedbacksTable extends Table {

    public function initialize(array $config) {
        $this->addBehavior('Timestamp');
        $this->belongsTo('Users');

    }

    public function validationDefault(Validator $validator) {
        $validator->provider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);

        $validator
        ->requirePresence('feedback')
        ->add('feedback', [
            'maxLength' => [
            'rule' => ['maxLength', 500],
            'message' => 'Feedback  can\'t be more than 500 characters.'
            ]
            ]);
        return $validator;
    }
    

}

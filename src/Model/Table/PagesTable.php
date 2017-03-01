<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;

class PagesTable extends Table {


    public function initialize(array $config) {
        $this->addBehavior('Timestamp');
        
        $this->addBehavior('Muffin/Slug.Slug');
    }

    public function validationDefault(Validator $validator) {
        $validator
                ->requirePresence('title_eng')
                ->add('title_eng', [
                    'maxLength' => [
                        'rule' => ['maxLength', 100],
                        'message' => 'Title cannot be too long.'
                    ]
                ])
                ->requirePresence('description_eng')
                
                ->requirePresence('title_spa')
                ->add('title_spa', [
                    'maxLength' => [
                        'rule' => ['maxLength', 100],
                        'message' => 'Title cannot be too long.'
                    ]
                ])
                ->requirePresence('description_spa');

        return $validator;
    }

    // In a table class
    public function buildRules(RulesChecker $rules) {

        $rules->add(new isUnique(['email']), 'isUniqueEmail', [
            'errorField' => 'email',
            'message' => 'This is already used.'
        ]);


        return $rules;
    }
    
    
    public function findslug($query, $slug) {
        $result = $this->find('all', ['conditions' => ['Pages.slug' => $slug['slug']]])->first();
        
        return $result;
    }

}

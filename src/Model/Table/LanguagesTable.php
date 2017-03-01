<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;

class LanguagesTable extends Table {

    public function initialize(array $config) {
        $this->addBehavior('Timestamp');

        $this->addBehavior('Search.Search');
        // Setup search filter using search manager
        $this->searchManager()->add('q', 'Search.Like', [
            'before' => true,
            'after' => true,
            'mode' => 'or',
            'comparison' => 'LIKE',
            'wildcardAny' => '*',
            'wildcardOne' => '?',
            'field' => [$this->aliasField('name')]
        ]);
    }

    public function validationDefault(Validator $validator) {
        $validator
                ->requirePresence('name')
                ->notEmpty('name', 'Please enter name.')
                ->allowEmpty('code')
                ->add('name', [
                    'maxLength' => [
                        'rule' => ['maxLength', 100],
                        'message' => 'Name can\'t be more than 100 characters.'
                    ]
        ]);

        return $validator;
    }

    // In a table class
    public function buildRules(RulesChecker $rules) {

        $rules->add(new isUnique(['name']), 'isUniqueCountryName', [
            'errorField' => 'name',
            'message' => 'This Language already added.'
        ]);

        return $rules;
    }

}

<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;

class UsersTable extends Table {

    public function initialize(array $config) {
        $this->addBehavior('Timestamp');

        $this->belongsTo('Countries');
        $this->belongsTo('Avatars');

        $this->belongsTo('Friend', [
            'className' => 'Friends',
            'foreignKey' => false,
                //'conditions' => ['Friend.user_one_id' => 'Uses.id']
            ]);

        $this->addBehavior('Muffin/Slug.Slug', [
            'displayField' => 'first_name',
            ]);

        $this->belongsToMany('Languages', [
            'joinTable' => 'users_languages',
            ]);

        $this->belongsToMany('Rooms', [
            'joinTable' => 'room_users',
            ]);
        
        // Add the behaviour to your table
        $this->addBehavior('Search.Search');
        // Setup search filter using search manager
        $this->searchManager()
        ->value('age')
        ->value('country_id')
        ->value('muslim_since')
        ->add('q', 'Search.Like', [
            'before' => true,
            'after' => true,
            'mode' => 'or',
            'comparison' => 'LIKE',
            'wildcardAny' => '*',
            'wildcardOne' => '?',
            'field' => [$this->aliasField('first_name'), $this->aliasField('last_name')]
            ])
        ->add('ageRange', 'Search.Callback', [
            'callback' => function ($query, $args, $filter) {
                if(!empty($args['ageRange'])){
                    $age = explode('-', $args['ageRange']);
                    return $query->where(['Users.age >= ' => $age[0],'Users.age <= ' => $age[1]]);
                }
                return $query;
                
            }
            ])
        ->add('lanaguage', 'Search.Callback', [
            'callback' => function ($query, $args, $filter) {
                $query->innerJoinWith('Languages', function ($q ) use ($args) {
                    return $q->where(['Languages.id' => $args['lanaguage']]);
                });
            }
            ])
        ->add('room_id', 'Search.Callback', [
            'callback' => function ($query, $args, $filter) {
                //return $query;
                $query->innerJoinWith('Rooms', function ($q ) use ($args) {
                    return $q->where(['Rooms.id' => $args['room_id']]);
                });
            }
            ]);
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'image' => [
            'keepFilesOnDelete' => false,
            'transformer' => function (\Cake\Datasource\RepositoryInterface $table, \Cake\Datasource\EntityInterface $entity, $data, $field, $settings) {
                $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                $result = [
                $data['tmp_name'] => $data['name'],
                ];
                return $result;
            },
            'nameCallback' => function($data, $settings) {
                $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                return md5(microtime() . '-' . time()) . '-' . time() . '.' . $extension;
            },
            ],
            'new_avatar' => [
            'keepFilesOnDelete' => false,
            'transformer' => function (\Cake\Datasource\RepositoryInterface $table, \Cake\Datasource\EntityInterface $entity, $data, $field, $settings) {
                $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                $result = [
                $data['tmp_name'] => $data['name'],
                ];
                return $result;
            },
            'nameCallback' => function($data, $settings) {
                $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                return md5(microtime() . '-' . time()) . '-' . time() . '.' . $extension;
            },
            ]
            ]);
    }

    public function validationDefault(Validator $validator) {
        $validator->provider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);

        $validator
        ->requirePresence('first_name')
        ->notEmpty('first_name', 'Please enter first name.')
        ->allowEmpty('last_name')
        ->add('first_name', [
            'maxLength' => [
            'rule' => ['maxLength', 100],
            'message' => 'First name can\'t be more than 100 characters.'
            ]
            ])
        ->add('last_name', [
            'maxLength' => [
            'rule' => ['maxLength', 100],
            'message' => 'Last name can\'t be more than 100 characters.'
            ]
            ])
        ->requirePresence('email')
        ->notEmpty('email', 'Please enter email address.')
        ->add('email', [
            'validFormat' => [
            'rule' => 'email',
            'message' => 'Please enter valid email address.'
            ]
            ])
        ->requirePresence('nick_name')
        ->notEmpty('nick_name', 'Please enter nick name.')
        ->add('nick_name', [
            'maxLength' => [
            'rule' => ['maxLength', 100],
            'message' => 'Nick name can\'t be more than 100 characters.'
            ]
            ])
        ->notEmpty('country_id', __('You need to select country.'))
        ->notEmpty('age', __('Please enter age.'))
        ->notEmpty('gender', __('Please select gender.'))
        ->notEmpty('muslim_since', __('Please enter muslim since.'));
        

        $validator->add('image', 'fileUnderPhpSizeLimit', [
            'rule' => 'isUnderPhpSizeLimit',
            'message' => 'This file is too large',
            'provider' => 'upload'
            ]);

        $validator->add('image', 'fileUnderFormSizeLimit', [
            'rule' => 'isUnderFormSizeLimit',
            'message' => 'This file is too large',
            'provider' => 'upload'
            ]);
        $validator->add('image', 'fileCompletedUpload', [
            'rule' => 'isCompletedUpload',
            'message' => 'This file could not be uploaded completely',
            'provider' => 'upload'
            ]);

        return $validator;
    }

    public function validationRegister($validator) {

        $validator->provider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);

        $validator
        ->requirePresence('first_name')
        ->notEmpty('first_name', 'Please enter first name.')
        ->allowEmpty('last_name')
        ->add('first_name', [
            'maxLength' => [
            'rule' => ['maxLength', 100],
            'message' => 'First name can\'t be more than 100 characters.'
            ]
            ])
        ->add('last_name', [
            'maxLength' => [
            'rule' => ['maxLength', 100],
            'message' => 'Last name can\'t be more than 100 characters.'
            ]
            ])
        ->requirePresence('email')
        ->notEmpty('email', 'Please enter email address.')
        ->add('email', [
            'validFormat' => [
            'rule' => 'email',
            'message' => 'Please enter valid email address.'
            ]
            ])
        ->requirePresence('nick_name')
        ->notEmpty('nick_name', 'Please enter nick name.')
        ->add('nick_name', [
            'maxLength' => [
            'rule' => ['maxLength', 100],
            'message' => 'Nick name can\'t be more than 100 characters.'
            ]
            ])
        ->notEmpty('country_id', __('You need to select country.'))
        ->notEmpty('age', __('Please enter age.'))
        ->notEmpty('gender', __('Please select gender.'))
        ->notEmpty('muslim_since', __('Please enter muslim since.'));

          $validator
        ->requirePresence('password')
        ->notEmpty('password', 'Please enter password.')
        ->requirePresence('confirm_password')
        ->notEmpty('confirm_password', 'Please enter confirm password.')
        ->add('confirm_password', 'no-misspelling', [
            'rule' => ['compareWith', 'password'],
            'message' => 'Passwords are not equal',
            ]);

        $validator->add('image', 'file', [
            'rule' => ['mimeType', ['image/jpeg', 'image/png', 'image/gif']],
            ]);

        $validator->add('image', 'fileUnderPhpSizeLimit', [
            'rule' => 'isUnderPhpSizeLimit',
            'message' => 'This file is too large',
            'provider' => 'upload'
            ]);

        $validator->add('image', 'fileUnderFormSizeLimit', [
            'rule' => 'isUnderFormSizeLimit',
            'message' => 'This file is too large',
            'provider' => 'upload'
            ]);
        $validator->add('image', 'fileCompletedUpload', [
            'rule' => 'isCompletedUpload',
            'message' => 'This file could not be uploaded completely',
            'provider' => 'upload'
            ]);

      

        return $validator;
    }

    public function validationAvatar($validator) {

        $validator->provider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);
        
        $validator->add('new_avatar', 'file', [
            'rule' => ['mimeType', ['image/jpeg', 'image/png', 'image/gif']],
            ]);

        $validator->add('new_avatar', 'fileUnderPhpSizeLimit', [
            'rule' => 'isUnderPhpSizeLimit',
            'message' => 'This file is too large',
            'provider' => 'upload'
            ]);

        $validator->add('new_avatar', 'fileUnderFormSizeLimit', [
            'rule' => 'isUnderFormSizeLimit',
            'message' => 'This file is too large',
            'provider' => 'upload'
            ]);
        $validator->add('new_avatar', 'fileCompletedUpload', [
            'rule' => 'isCompletedUpload',
            'message' => 'This file could not be uploaded completely',
            'provider' => 'upload'
            ]);

        
        return $validator;
    }



    public function validationForgotPassword($validator) {
        $validator
        ->requirePresence('password')
        ->requirePresence('confirm_password')
        ->add('confirm_password', 'no-misspelling', [
            'rule' => ['compareWith', 'password'],
            'message' => 'Password does not match the confirm password.',
            ]);

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

    public function findAuthAdmin(\Cake\ORM\Query $query, array $options) {
        $query
        ->select(['id', 'first_name', 'last_name', 'slug', 'email', 'password', 'image', 'is_admin'])
        ->where(['Users.is_admin' => 1]);

        return $query;
    }

    public function findAuthUser(\Cake\ORM\Query $query, array $options) {
        $query
        ->contain('Avatars')
        ->select(['Users.id',
            'Users.role_id',
            'Users.password',
            'Users.avatar_id',
            'Users.first_name',
            'Users.last_name',
            'Users.slug',
            'Users.nick_name',
            'Users.email', 'age',
            'Users.gender',
            'Users.muslim_since',
            'Users.image',
            'Users.avatar_status',
            'Users.new_avatar',
            'Users.facebook_id',
            'Users.google_id',
            'Users.twitter_id',
            'Users.is_admin',
            'Users.status',
            'Avatars.id',
            'Avatars.image',
            ])
        ->where(['Users.is_admin' => 0, 'Users.status' => 1, 'Users.role_id' => 1]);

        return $query;
    }

    
    public function findSearched(\Cake\ORM\Query $query, array $options) {
        $user_id = $options['user_id'];

        $conditions = ['Users.is_admin' => 0, 'Users.status' => 1, 'Users.role_id' => 1, 'Users.id !=' => $user_id];
        if (!empty($options['searchQueries']['q'])) {
            $conditions['Users.first_name like'] = "%" . $options['searchQueries']['q'] . "%";
        }

        if (!empty($options['searchQueries']['country_id'])) {
            $conditions['Users.country_id'] = $options['searchQueries']['country_id'];
        }
        if (!empty($options['searchQueries']['age'])) {
            $conditions['Users.age'] = $options['searchQueries']['age'];
        }
        $query
        ->contain(['Avatars', 'Countries'])
        ->select(['friend.status', 'friend.user_one_id', 'friend.user_two_id', 'friend.action_user_id', 'Users.id', 'Users.first_name', 'Users.last_name', 'Users.slug', 'Users.image', 'Users.avatar_id', 'Users.muslim_since', 'Users.avatar_status', 'Users.new_avatar', 'Countries.id', 'Countries.flag', 'Avatars.id', 'Avatars.image'])
        ->join(['friend' => [
            'table' => 'friends',
            'type' => 'LEFT',
            'conditions' => 'CASE WHEN friend.user_one_id = ' . $user_id . ' THEN friend.user_two_id = Users.id WHEN friend.user_two_id= ' . $user_id . ' THEN friend.user_one_id= Users.id END',
            ]
            ])
        ->where($conditions);
        if (!empty($options['searchQueries']['language'])) {
            $query->innerJoinWith('Languages', function ($q ) use($options) {
                return $q->where(['Languages.id' => $options['searchQueries']['language']]);
            });
        }

        return $query;
    }

    public function findFriendReuests(\Cake\ORM\Query $query, array $options) {
        $user_id = $options['user_id'];
        $query
        ->contain(['Avatars', 'Countries'])
        ->select(['friend.status', 'friend.user_one_id', 'friend.user_two_id', 'friend.action_user_id', 'Users.id', 'Users.first_name', 'Users.last_name', 'Users.slug', 'Users.image', 'Users.avatar_status', 'Users.new_avatar', 'Users.avatar_id', 'Users.muslim_since', 'Countries.id', 'Countries.flag', 'Avatars.id', 'Avatars.image'])
        ->join(['friend' => [
            'table' => 'friends',
            'type' => 'INNER',
            'conditions' => 'CASE WHEN friend.user_one_id = ' . $user_id . ' THEN friend.user_two_id = Users.id WHEN friend.user_two_id= ' . $user_id . ' THEN friend.user_one_id= Users.id END',
            ]
            ])
        ->where(['Users.is_admin' => 0, 'Users.status' => 1, 'Users.role_id' => 1, 'friend.status' => 0]);

        return $query;
    }


    public function findFriends(\Cake\ORM\Query $query, array $options) {
        $user_id = $options['user_id'];
        $query
        ->contain(['Avatars', 'Countries'])
        ->select(['Users.id', 'Users.first_name', 'Users.last_name', 'Users.slug', 'Users.image', 'Users.avatar_status', 'Users.new_avatar', 'Users.gender', 'Users.age', 'Users.avatar_id', 'Users.muslim_since', 'Countries.id', 'Countries.flag', 'Avatars.id', 'Avatars.image'])
        ->join(['friend' => [
            'table' => 'friends',
            'type' => 'INNER',
            'conditions' => 'CASE WHEN friend.user_one_id = ' . $user_id . ' THEN friend.user_two_id = Users.id WHEN friend.user_two_id= ' . $user_id . ' THEN friend.user_one_id= Users.id END',
            ]
            ])
        ->where(['Users.is_admin' => 0, 'Users.status' => 1, 'Users.role_id' => 1, 'friend.status' => 1]);


        return $query;
    }

    

}

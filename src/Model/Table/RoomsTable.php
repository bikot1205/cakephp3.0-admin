<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;

class RoomsTable extends Table {

    public function initialize(array $config) {
        $this->addBehavior('Timestamp');

        $this->belongsTo('Moderator', [
            'className' => 'Users',
            'foreignKey' => 'moderator_id',
            ]);

        $this->addBehavior('Muffin/Slug.Slug', [
            'displayField' => 'name',
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
            'field' => [$this->aliasField('name'), $this->aliasField('name')]
            ])
        ->add('lanaguage', 'Search.Callback', [
            'callback' => function ($query, $args, $filter) {
                $query->innerJoinWith('Languages', function ($q ) use ($args) {
                    return $q->where(['Languages.id' => $args['lanaguage']]);
                });
            }
            ]);
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'image' => [
            'keepFilesOnDelete' => false,
                                //'path' => 'webroot{DS}files{DS}{model}{DS}{field-value:unique_id}{DS}',
            'transformer' => function (\Cake\Datasource\RepositoryInterface $table, \Cake\Datasource\EntityInterface $entity, $data, $field, $settings) {
                                    // get the extension from the file
                                    // there could be better ways to do this, and it will fail
                                    // if the file has no extension
                $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                                    // Store the thumbnail in a temporary file
                $tmpLarge = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;

                                    // Use the Imagine library to DO THE THING

                $size = new \Imagine\Image\Box(50, 50);
                $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
                $imagine = new \Imagine\Gd\Imagine();
                                    // Save that modified file to our temp file
                $imagine->open($data['tmp_name'])
                ->thumbnail($size, $mode)
                ->save($tmpLarge);


                                    // Now return the original *and* the thumbnail
                $result = [
                $data['tmp_name'] => $data['name'],
                $tmpLarge => 'thumbnail-' . $data['name'],
                ];
                return $result;
            },
            'nameCallback' => function($data, $settings) {
                $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                return md5(microtime() . '-' . time()) . '-' . time() . '.' . $extension;
            },
            ],
            'icon' => [
            'keepFilesOnDelete' => false,
                                        //'path' => 'webroot{DS}files{DS}{model}{DS}{field-value:unique_id}{DS}',
            'transformer' => function (\Cake\Datasource\RepositoryInterface $table, \Cake\Datasource\EntityInterface $entity, $data, $field, $settings) {
                                            // get the extension from the file
                                            // there could be better ways to do this, and it will fail
                                            // if the file has no extension
                $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                                            // Store the thumbnail in a temporary file
                $tmpLarge = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;

                                            // Use the Imagine library to DO THE THING

                $size = new \Imagine\Image\Box(50, 50);
                $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
                $imagine = new \Imagine\Gd\Imagine();
                                            // Save that modified file to our temp file
                $imagine->open($data['tmp_name'])
                ->thumbnail($size, $mode)
                ->save($tmpLarge);


                                            // Now return the original *and* the thumbnail
                $result = [
                $data['tmp_name'] => $data['name'],
                $tmpLarge => 'thumbnail-' . $data['name'],
                ];
                return $result;
            },
            'nameCallback' => function($data, $settings) {
                $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                return md5(microtime() . '-' . time()) . '-' . time() . '.' . $extension;
            },
            ],
            ]);
    }

    public function validationDefault(Validator $validator) {

        $validator
        ->requirePresence('name')
        ->notEmpty('name', 'Please enter room name.')
        ->add('name', [
            'maxLength' => [
            'rule' => ['maxLength', 100],
            'message' => 'Room name can\'t be more than 100 characters.'
            ]
            ])
        ->requirePresence('occupancy')
        ->requirePresence('moderator_id')
        ->notEmpty('occupancy', 'Please select occupancy level.')
        ->notEmpty('country_id', __('You need to select country.'))
        ->notEmpty('type', __('Please select room type.'));



        return $validator;
    }


                                    // In a table class
    public function buildRules(RulesChecker $rules) {

        $rules->add(new isUnique(['name']), 'isUniqueEmail', [
            'errorField' => 'name',
            'message' => 'This is already used.'
            ]);

        return $rules;
    }

    public function findRoomMembers(\Cake\ORM\Query $query, array $options) {
        $room_id = $options['room_id'];
        $query
        // ->select(['friend.status', 'friend.user_one_id', 'friend.user_two_id', 'friend.action_user_id', 'Users.id', 'Users.first_name', 'Users.last_name', 'Users.slug', 'Users.image', 'Users.avatar_status', 'Users.new_avatar', 'Users.avatar_id', 'Users.muslim_since', 'Countries.id', 'Countries.flag', 'Avatars.id', 'Avatars.image'])
        ->join(['roomusers' => [
            'table' => 'room_users',
            'type' => 'INNER',
            'conditions' => 'roomusers.room_id = Rooms.id',
            ]
            ])
        ->where(['Rooms.id' => $room_id]);

        return $query;
    }

}

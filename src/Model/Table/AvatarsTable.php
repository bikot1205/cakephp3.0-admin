<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;
use Cake\Event\Event;
use ArrayObject;

class AvatarsTable extends Table {

    public function initialize(array $config) {
        $this->addBehavior('Timestamp');

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'image' => [
                'keepFilesOnDelete' => false,
                'transformer' => function (\Cake\Datasource\RepositoryInterface $table, \Cake\Datasource\EntityInterface $entity, $data, $field, $settings) {
                    $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                    // Store the thumbnail in a temporary file
                    $tmpThumbnail = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;

                    // Now return the original *and* the thumbnail
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
                $validator->provider('image', \Josegonzalez\Upload\Validation\ImageValidation::class);

                $validator->add('image', 'file', [
                    'rule' => ['mimeType', ['image/png']],
                    'message' => 'Avatar should be a png image.',
                ]);

                $validator->add('image', 'fileBelowMaxWidth', [
                    'rule' => ['isBelowMaxWidth', 550],
                    'message' => 'This image should not be wider than 100px',
                    'provider' => 'image'
                ]);

                $validator->add('image', 'fileBelowMaxHeight', [
                    'rule' => ['isBelowMaxHeight', 550],
                    'message' => 'This image should not be higher than 100px',
                    'provider' => 'image'
                ]);

                $validator->add('image', 'fileBelowMaxWidth', [
                    'rule' => ['isAboveMinWidth', 60],
                    'message' => 'This image should not be narrow than 60px',
                    'provider' => 'image'
                ]);

                $validator->add('image', 'fileBelowMaxHeight', [
                    'rule' => ['isAboveMinHeight', 60],
                    'message' => 'This image should not be smaller than 60px',
                    'provider' => 'image'
                ]);


                return $validator;
            }

        }
        
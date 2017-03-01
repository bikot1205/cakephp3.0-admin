<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;

class CountriesTable extends Table {

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
            'field' => [$this->aliasField('name'), $this->aliasField('code')]
        ]);

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'flag' => [
                'keepFilesOnDelete' => false,
                'transformer' => function (\Cake\Datasource\RepositoryInterface $table, \Cake\Datasource\EntityInterface $entity, $data, $field, $settings) {
                    $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                    // Store the thumbnail in a temporary file
                    $tmpThumbnail = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;

                    // Use the Imagine library to DO THE THING

                    $size = new \Imagine\Image\Box(40, 40);
                    $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
                    $imagine = new \Imagine\Gd\Imagine();
                    // Save that modified file to our temp file
                    $imagine->open($data['tmp_name'])
                            ->thumbnail($size, $mode)
                            ->save($tmpThumbnail);

                    // Now return the original *and* the thumbnail
                    $result = [
                        //$data['tmp_name'] => $data['name'],
                        $tmpThumbnail => $data['name'],
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

                $validator->add('flag', 'file', [
                    'rule' => ['mimeType', ['image/png']],
                    'message' => 'Avatar should be a png image.',
                ]);

                $validator->add('flag', 'fileBelowMaxWidth', [
                    'rule' => ['isBelowMaxWidth', 550],
                    'message' => 'This image should not be wider than 100px',
                    'provider' => 'image'
                ]);

                $validator->add('flag', 'fileBelowMaxHeight', [
                    'rule' => ['isBelowMaxHeight', 550],
                    'message' => 'This image should not be higher than 100px',
                    'provider' => 'image'
                ]);

                $validator->add('flag', 'fileBelowMaxWidth', [
                    'rule' => ['isAboveMinWidth', 60],
                    'message' => 'This image should not be narrow than 60px',
                    'provider' => 'image'
                ]);

                $validator->add('flag', 'fileBelowMaxHeight', [
                    'rule' => ['isAboveMinHeight', 60],
                    'message' => 'This image should not be smaller than 60px',
                    'provider' => 'image'
                ]);


                $validator
                        ->requirePresence('name')
                        ->notEmpty('name', 'Please enter country name.')
                        ->allowEmpty('code')
                        ->add('name', [
                            'maxLength' => [
                                'rule' => ['maxLength', 100],
                                'message' => 'Country name can\'t be more than 100 characters.'
                            ]
                        ])
                        ->add('code', [
                            'maxLength' => [
                                'rule' => ['maxLength', 4],
                                'message' => 'Country code can\'t be more than 4 characters.'
                            ]
                ]);

                return $validator;
            }

            // In a table class
            public function buildRules(RulesChecker $rules) {

                $rules->add(new isUnique(['name']), 'isUniqueCountryName', [
                    'errorField' => 'name',
                    'message' => 'This country already added.'
                ]);

                return $rules;
            }

        }
        
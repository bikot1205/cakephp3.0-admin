<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\Rule\IsUnique;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

class SmilesTable extends Table {

    public function initialize(array $config) {
        $this->addBehavior('Timestamp');

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'image' => [
            'keepFilesOnDelete' => false,
            'transformer' => function (\Cake\Datasource\RepositoryInterface $table, \Cake\Datasource\EntityInterface $entity, $data, $field, $settings) {
                $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                    // Store the thumbnail in a temporary file
                $tmpThumbnail = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;

                $size = new \Imagine\Image\Box(40, 40);
                $mode = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
                $imagine = new \Imagine\Gd\Imagine();
                                    // Save that modified file to our temp file
                $imagine->open($data['tmp_name'])
                ->thumbnail($size, $mode)
                ->save($tmpThumbnail);

                    // Now return the original *and* the thumbnail
                $result = [
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
        $validator->provider('upload', \Josegonzalez\Upload\Validation\UploadValidation::class);
        
        $validator->provider('image', \Josegonzalez\Upload\Validation\ImageValidation::class);
        
        $validator->requirePresence('name')
        ->notEmpty('name', 'Please enter smile name.')
        ->add('name', [
            'maxLength' => [
            'rule' => ['maxLength', 20],
            'message' => 'Smile name can\'t be more than 20 characters.'
            ]
            ])
        ->requirePresence('code')
        ->add('code', [
            'maxLength' => [
            'rule' => ['maxLength', 10],
            'message' => 'Smile code can\'t be more than 10 characters.'
            ]
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

        $validator->add('image', 'fileBelowMaxWidth', [
            'rule' => ['isBelowMaxWidth', 100],
            'message' => 'This image should not be wider than 100px',
            'provider' => 'image'
            ]);

        $validator->add('image', 'fileBelowMaxHeight', [
            'rule' => ['isBelowMaxHeight', 100],
            'message' => 'This image should not be higher than 100px',
            'provider' => 'image'
            ]);


        return $validator;
    }

    public function buildRules(RulesChecker $rules) {

        $rules->add(new isUnique(['code']), 'isUniqueEmail', [
            'errorField' => 'code',
            'message' => 'This is already used.'
            ]);

        return $rules;
    }

}

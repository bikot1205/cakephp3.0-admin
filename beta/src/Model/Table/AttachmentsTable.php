<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\RulesChecker;
use Cake\Event\Event;
use ArrayObject;

class AttachmentsTable extends Table {

    public function initialize(array $config) {
        $this->addBehavior('Timestamp');

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'file' => [
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
            ]
            ]);
    }


    public function validationDefault(Validator $validator) {
        $validator->provider('image', \Josegonzalez\Upload\Validation\ImageValidation::class);

        $validator->add('file', 'file', [
            'rule' => ['mimeType', ['image/jpeg','image/jpg','image/gif','image/png']],
            'message' => 'File should be a png image.',
            ]);

        return $validator;
    }

}

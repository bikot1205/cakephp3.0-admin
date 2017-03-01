<?php

$this->extend('/Common/Admin/add');

$this->Html->addCrumb($modelClass, array('controller' => 'index'));
$this->Html->addCrumb($title, null);


$this->start('form');
echo $this->Form->create($smile, [
    'novalidate' => true,
    'type' => 'file',
    'align' => [
        'sm' => [
            'left' => 4,
            'middle' => 8,
            'right' => 12
        ],
        'md' => [
            'left' => 4,
            'middle' => 4,
            'right' => 4
        ]
    ]
]);

echo $this->Form->input('name');
echo $this->Form->input('code');

$image = 
'<div class="thumbnail" style="width:80px;">' . $this->Awesome->image($modelClass . '/image', $smile['image'], ['class' => 'img-responsive clearfix']) . '
                                                                                                                
                                                                                                        </div>';
echo $this->Form->input('image', [
    'templates' => ['file' => $image . '<input type="file" name="{{name}}"{{attrs}}><span>Image dimension 40px x 40px.</span>'],
    'class' => 'filestyle', 'data-buttontext' => 'Select file', 'data-buttonname' => 'btn-white', 'type' => 'file']);


$this->end();
$submitBtn = $this->Form->button('Upload Smile', array('class' => 'btn btn-success'));

$this->assign('btn-submit', $submitBtn);

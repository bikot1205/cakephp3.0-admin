<?php

$this->extend('/Common/Admin/add');

$this->start('form');
echo $this->Form->create($avatar, [
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


$image = '<div class="thumbnail" style="width:200px;">' . $this->Awesome->image($modelClass . '/image', $avatar['image'], ['class' => 'img-responsive clearfix']) . '
                                                                                                                
                                                                                                        </div>';
echo $this->Form->input('image', [
    //'templates' => ['file' => $image . '<input type="file" name="{{name}}"{{attrs}}>'],
    'class' => 'filestyle', 'data-buttontext' => 'Select file', 'data-buttonname' => 'btn-white', 'type' => 'file']);


$this->end();
$submitBtn = $this->Form->button('Upload Avator', array('class' => 'btn btn-success'));

$this->assign('btn-submit', $submitBtn);

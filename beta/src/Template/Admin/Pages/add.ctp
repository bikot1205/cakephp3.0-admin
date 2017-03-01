<?php

$this->extend('/Common/Admin/add');
$this->Html->addCrumb($modelClass, array('controller' => 'index'));
$this->Html->addCrumb($title, null);

$this->start('form');
echo $this->Form->create($page, [
    'novalidate' => true,
    'align' => [
        'sm' => [
            'left' => 4,
            'middle' => 8,
            'right' => 12
        ],
        'md' => [
            'left' => 2,
            'middle' => 8,
            'right' => 2
        ]
    ]
]);

echo $this->Form->input('title_eng',['label' => 'English Title']);

echo $this->Form->input('description_eng', ['class' => 'editor','label' => 'English Description']);


echo $this->Form->input('title_spa',['label' => 'Spanish Title']);

echo $this->Form->input('description_spa', ['class' => 'editor','label' => 'Spanish Description']);


//echo $this->Form->button('Add');

$this->end();



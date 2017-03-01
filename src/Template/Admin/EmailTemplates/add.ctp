<?php

$this->extend('/Common/Admin/add');
$this->Html->addCrumb('Email Templates', ['action' => 'index']);
if ($emailTemplate->id) {
    $this->Html->addCrumb('Edit Email Template', null);
} else {
    $this->Html->addCrumb('Add Add Template', null);
}

$this->start('form');
echo $this->Form->create($emailTemplate, [
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

echo $this->Form->input('title');
echo $this->Form->input('subject');

echo $this->Form->input('message', ['class' => 'editor']);

//echo $this->Form->button('Add');

$this->end();



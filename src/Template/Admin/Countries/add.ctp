<?php

$this->extend('/Common/Admin/add');

$this->Html->addCrumb($modelClass, array('action' => 'index'));

$title = 'Edit Country';

if (empty($country['id'])) {
    $title = 'Add Country';
}
$this->assign('title', $title);
$this->Html->addCrumb($title, null);

$this->start('form');
echo $this->Form->create($country, [
    'novalidate' => true,
    'type' => 'file',
    'align' => [
        'sm' => [
            'left' => 8,
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

echo $this->Form->input('name', ['label' => 'Country Name']);
echo $this->Form->input('code', ['label' => 'Country Code']);

$image = null;
if (!empty($country['flag'])) {
    $image = '<div class="thumbnail" style="width:200px;">' . $this->Awesome->image($modelClass . '/flag', $country['flag'], ['class' => 'img-responsive clearfix']) . '</div>';
}
echo $this->Form->input('flag', [
    'label' => 'Country Flag',
    'templates' => ['file' => $image . '<input type="file" name="{{name}}"{{attrs}}>'],
    'class' => 'filestyle', 'data-buttontext' => 'Select file', 'data-buttonname' => 'btn-white', 'type' => 'file']);
// Textarea
echo '<div class="form-group">
            <label class="control-label col-sm-8 col-md-4">Status</label>
            <div class="switchery-demo col-sm-8 col-md-4">
                ' . $this->Form->checkbox('status', [
                'data-plugin' => "switchery",
                'data-color' => "#5d9cec",
                //'data-size'=>"medium"
            ]) .
            '</div>
        </div>';
$submitBtn = $this->Form->button('Save Country', array('class' => 'btn btn-success'));

$this->assign('btn-submit', $submitBtn);

$this->end();



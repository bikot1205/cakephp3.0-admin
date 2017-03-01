<?php

$this->extend('/Common/Admin/index');
$this->Html->addCrumb($modelClass, null);

$tableHeaders[] = array($this->Paginator->sort(__('id')) => array('style' => 'width:5%'));
$tableHeaders[] = array($this->Paginator->sort(__('flag'), 'Country Flag') => array('style' => 'width:15%'));
$tableHeaders[] = array($this->Paginator->sort(__('name'), 'Country Name') => array('style' => 'width:40%'));

$tableHeaders[] = array($this->Paginator->sort(__('code'), 'Country Code') => array('style' => 'width:20%'));
$tableHeaders[] = array($this->Paginator->sort(__('status')) => array('style' => 'width:10%'));

$tableHeaders[] = array('Actions' => array('class' => 'action-btn-2 text-center', 'style' => 'width:10%'));

$this->append('table_head', $this->Html->tableHeaders($tableHeaders, array('class' => 'heading'), array('class' => 'sorting')));

$tableHeaders = array();


$this->append('form-start', $this->Form->create($modelClass, array(
            'type' => 'post',
            'class' => 'form-horizontal list_data_form',
            'novalidate' => true,
)));



$rows = null;
if ($success && $$viewVar->count() > 0) {
    $entities = $$viewVar->toArray();
    foreach ($entities as $key => $listOne) {
        
        $row = array();
        $row[] = $listOne->id;
        $row[] = $this->Awesome->image('Countries/flag', $listOne->flag, ['class' => 'img-circle1 thumb-md clearfix','title' => 'click to view full image']);
        $row[] = $listOne->name;
        $row[] = $listOne->code;
        $row[] = $this->Form->checkbox('status', [
            'checked' => $listOne->status,
            'class' => "switchery_with_action",
            'data-size'=>"small",
            'data-model' => $modelClass,
            'data-id' => $listOne->id,
            'data-field' => 'status'
            ]);

        $links = $this->Html->link(__('<i class="fa fa-edit"></i>'), array('action' => 'edit', $listOne->id) , array('class' => 'btn btn-xs green tooltips', 'data-placement' => "top", 'data-original-title' => "Delete", 'title' => 'Edit', 'escape' => false));
        $links .= $this->Form->postLink(
                '<i class="fa fa-times"></i>', ['action' => 'delete', $listOne->id], ['escape' => false, 'class' => 'btn btn-xs red delete_btn tooltips', 'confirm' => __('Are you sure you want to delete this ? ')]);


        $row[] = array($links, array('class' => 'text-center'));
        $rows[] = $row;
    }
}
if (!empty($rows)) {
    $this->append('table_row', $this->Html->tableCells($rows));
}


$this->start('search');

echo $this->Form->create($countries, array(
    'novalidate' => true,
    'type' => 'get',
    'class' => 'form-inline',
));
echo $this->Form->input('q', [
    'templates' => ['inputContainer' => '<div class="form-group col-md-9">{{content}}</div>', 'label' => null],
    'placeholder' => 'Country Name, Code'
]);



$this->end();

$this->assign('searchActionRow', "col-md-3");
$btn = $this->Form->button('Filter', array('class' => 'btn btn-default pull-right'));
$this->assign('btn', $btn);

$actionBtns = $this->Html->link('Add New', ['action' => 'add'], ['class' => 'btn btn-default']);
$this->assign('actionBtns', $actionBtns);




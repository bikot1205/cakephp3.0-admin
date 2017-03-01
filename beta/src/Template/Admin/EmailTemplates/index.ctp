<?php

$this->extend('/Common/index');
$this->Html->addCrumb($modelClass, null);


// $tableHeaders[] = array($this->Paginator->sort(__('id'), 'ID') => array('class' => 'id_class text-center'));
$tableHeaders[] = $this->Paginator->sort(__('title'));
$tableHeaders[] = $this->Paginator->sort(__('slug'),'Template Name');
$tableHeaders[] = array($this->Paginator->sort(__('created'), 'Modified Date') => array('class' => 'date-class'));
$tableHeaders[] = array('Actions' => array('class' => 'action-btn-2 text-center'));

$this->append('table_head', $this->Html->tableHeaders($tableHeaders, array('class' => 'heading'), array('class' => 'sorting')));

$tableHeaders = array();


$this->append('form-start', $this->Form->create($modelClass, array(
        'type' => 'post',
        'class' => 'form-horizontal list_data_form',
        'novalidate' => true,
)));



$rows = array();
if (!empty($$viewVar)) {
    $entities = $$viewVar;
    foreach ($entities->toArray() as $key => $listOne) {
        $listOne = $listOne->toArray();
        $row = array();
        // $row[] = array($listOne['id'], array('class' => 'text-center'));
        $row[] = $listOne['title'];
        $row[] = $listOne['slug'];
        $row[] = $listOne['modified'];

        $links = $this->Html->link(__('<i class="fa fa-edit"></i>'), array('action' => 'edit', $listOne['id']), array('class' => 'btn btn-xs green tooltips', 'data-placement' => "top", 'data-original-title' => "Delete", 'title' => 'Edit', 'escape' => false));
        // $links .= $this->Form->postLink(
        //         '<i class="fa fa-times"></i>',
        //         ['action' => 'delete', $listOne['id']],
        //         ['escape' => false,'class' => 'btn btn-xs red delete_btn tooltips','confirm' =>  __('Are you sure you want to delete this ? ')]);



        $row[] = array($links, array('class' => 'text-center'));
        $rows[] = $row;
    }
} else {
    $row[] = array(__('NoResult', $modelClass), array('class' => 'text-center noresult', 'colspan' => 9));
    $rows[] = $row;
}


$this->append('table_row', $this->Html->tableCells($rows));




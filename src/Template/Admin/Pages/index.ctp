<?php

$this->extend('/Common/Admin/index');
$this->Html->addCrumb($modelClass, null);

$tableHeaders[] = array($this->Paginator->sort(__('title')) => array('style' => 'width:30%'));
$tableHeaders[] = $this->Paginator->sort(__('slug'));
$tableHeaders[] = array($this->Paginator->sort(__('modified'), 'Last Modified') => array('class' => 'date-class'));
$tableHeaders[] = array('Actions' => array('class' => 'action-btn-2 text-center'));

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
        $row[] = $listOne->title;
        $row[] = $listOne->slug;
        
        $row[] = $this->Awesome->date($listOne->modified);

        $links = $this->Html->link(__('<i class="fa fa-edit"></i>'), array('action' => 'edit', $listOne->id), array('class' => 'btn btn-xs green tooltips', 'data-placement' => "top", 'data-original-title' => "Delete", 'title' => 'Edit', 'escape' => false));
//        $links .= $this->Form->postLink(
//                '<i class="fa fa-times"></i>',
//                ['action' => 'delete', $listOne->id],
//                ['escape' => false,'class' => 'btn btn-xs red delete_btn tooltips','confirm' =>  __('Are you sure you want to delete this ? ')]);


        $row[] = array($links, array('class' => 'text-center'));
        $rows[] = $row;
    }
}
if(!empty($rows)){
    $this->append('table_row', $this->Html->tableCells($rows));
}





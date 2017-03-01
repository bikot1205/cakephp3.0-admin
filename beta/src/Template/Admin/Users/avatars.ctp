<?php

$this->extend('/Common/Admin/index');
$this->Html->addCrumb($modelClass, null);

//$tableHeaders[] = array($this->Form->checkbox($modelClass . '.id.', array('class' => 'group-checkable', 'data-set' => "#psdata_table .checkboxes", 'hiddenField' => false)) => array('class' => 'check_box'));
//$tableHeaders[] = '';
$tableHeaders[] = array($this->Paginator->sort(__('id'), 'User ID') => array('class' => 'id_class text-center','style' => 'width:10%'));
$tableHeaders[] = array('Avatar' => array('style' => 'width:60%'));


$tableHeaders[] = array($this->Paginator->sort(__('status')) => array('style' => 'width:20%'));

$tableHeaders[] = array('Action' => array('style' => 'width:10%','class' => 'text-center'));

$this->append('table_head', $this->Html->tableHeaders($tableHeaders, array('class' => 'heading'), array('class' => 'sorting')));



$this->append('form-start', $this->Form->create($modelClass, array(
    'type' => 'post',
    'class' => 'form-horizontal list_data_form',
    'novalidate' => true,
    )));



$rows = array();
if ($avatars->count() > 0) {
    foreach ($avatars as $key => $listOne) {
        $row = array();
        $row[] = array($listOne['id'], array('class' => 'text-center'));
        $new_avatar = null;
        if($listOne->new_avatar && $listOne->avatar_status == 'Pending'){
            $new_avatar = ' <i class="ti-arrow-right"></i> '.$this->Awesome->image('Users/new_avatar', $listOne->new_avatar, ['class' => 'img-circle thumb-md clearfix']);
        }
        $row[] = $this->Awesome->userImage($listOne).$new_avatar;

        $status = null;
        if($listOne->avatar_status == 'Image' ){
            $status = $this->Html->tag('span','Uploaded',['class' => 'text-warning']);
        } else if($listOne->avatar_status == 'Avatar'){
            $status = $this->Html->tag('span','Avatar',['class' => 'text-success']);
        }
        else if($listOne->avatar_status == 'Pending'){
            $status = $this->Html->tag('span','New avatar request pending',['class' => 'text-info']);
        }
        else if($listOne->avatar_status == 'Accepted'){
            $status = $this->Html->tag('span','Accepted',['class' => 'text-success']);
        }
        else if($listOne->avatar_status == 'Declined'){
            $status = $this->Html->tag('span','Declined',['class' => 'text-danger']);
        }

        $link = $this->Html->link(__('Upload New'), ['action' => 'uploadAvatar',$listOne->id], array('class' => 'btn btn-link waves-effect waves-light',  'escape' => false,'data-animation' => 'fadein','data-plugin' => 'custommodal','data-overlayspeed' => 200,'data-overlaycolor' => '#36404a'));

        $row[] = $status;
        $row[] = $link;
        

        $rows[] = $row;
    }
}



$this->assign('searchActionRow', "col-md-2");
$btn = $this->Form->button('Search', array('class' => 'btn btn-default pull-right'));
$this->assign('btn', $btn);
if (!empty($rows)) {
    $this->append('table_row', $this->Html->tableCells($rows));
}


$actionBtns .= $this->Html->link('Add New', ['action' => 'add'], ['class' => 'btn btn-default']);
$this->assign('actionBtns', $actionBtns);



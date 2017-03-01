<?php
use Cake\Core\Configure;
use \GameNet\Jabber\RpcClient;

$this->extend('/Common/Admin/index');
$this->Html->addCrumb($modelClass, null);


$tableHeaders[] = array($this->Paginator->sort(__('id'), 'Room ID') => array('class' => 'id_class text-center','style2' => 'width:10%'));
$tableHeaders[] = array('Icon' => array('style2' => 'width:10%'));
$tableHeaders[] = array($this->Paginator->sort(__('name'), 'Room Name') => array('style2' => 'width:20%'));

$tableHeaders[] = array($this->Paginator->sort(__('type'), 'Room Type') => array('style2' => 'width:10%'));

$tableHeaders[] = array($this->Paginator->sort(__('moderator')) => array('style2' => 'width:20%'));

$tableHeaders[] = array($this->Paginator->sort(__('occupancy')) => array('style2' => 'width:15%'));
$tableHeaders[] = array($this->Paginator->sort(__('file_sharing')) => array('style2' => 'width:10%'));
$tableHeaders[] = array($this->Paginator->sort(__('status')) => array('style2' => 'width:10%'));
// $tableHeaders[] = array('Actions' => array('class' => 'action-btn-2 text-center', 'style' => 'width:5%'));

$this->append('table_head', $this->Html->tableHeaders($tableHeaders, array('class' => 'heading'), array('class' => 'sorting')));

$tableHeaders = array();


$this->append('form-start', $this->Form->create($modelClass, array(
            'type' => 'post',
            'class' => 'form-horizontal list_data_form',
            'novalidate' => true,
)));



$rows = array();
if ($success && $rooms->count() > 0) {
    foreach ($rooms->toArray() as $key => $listOne) {


        //  $rpc = new \GameNet\Jabber\RpcClient([
        //             'server' => Configure::read('XamppServer.server'),
        //             'host' => Configure::read('XamppServer.host'),
        //             'debug' => Configure::read('debug'),
        // ]);
        // $roomName = $listOne->slug;
        // $Rooms = $rpc->createRoom($roomName);

        // $rpc->setRoomOption($roomName,'max_users',$listOne->occupancy);

        // $public = ($listOne->type == 1) ? true : false;

        // $rpc->setRoomOption($roomName,'public',$public);


        $row = array();
        $row[] = array($this->Html->link($listOne->id,['controller' => 'rooms','action' => 'view','slug' => $listOne->slug]), array('class' => 'text-center'));
        $row[] = $this->Html->link($this->Awesome->image('Rooms/icon', $listOne->icon, ['class' => 'img-circle thumb-md clearfix','title' => 'click to view full image']),['controller' => 'rooms','action' => 'view','slug' => $listOne->slug],['escape' => false]);
        $row[] = $this->Html->link($listOne->name,['controller' => 'rooms','action' => 'view','slug' => $listOne->slug]);

        $row[] = $listOne->types[$listOne->type];
        $row[] = $this->Html->link($listOne->moderator->name,['controller' => 'users','action' => 'view','slug' => $listOne->moderator->name]);

        $row[] = $listOne->occupancy;
        $row[] = $this->Form->checkbox('file_sharing', [
            'checked' => $listOne->status,
            'class' => "switchery_with_action",
            'data-size'=>"small",
            'data-model' => $modelClass,
            'data-id' => $listOne->id,
            'data-field' => 'file_sharing'
            ]);
        $row[] = $this->Form->checkbox('status', [
            'checked' => $listOne->status,
            'class' => "switchery_with_action",
            'data-size'=>"small",
            'data-model' => $modelClass,
            'data-id' => $listOne->id,
            'data-field' => 'status'
            ]);
        $links = $this->Html->link(__('<i class="fa fa-edit"></i>'), array('action' => 'edit', $listOne->id), array('class' => 'btn btn-xs green tooltips', 'data-placement' => "top", 'data-original-title' => "Delete", 'title' => 'Edit', 'escape' => false));
        $links = $this->Form->postLink(
                '<i class="fa fa-times"></i>', ['action' => 'delete', $listOne->id], ['escape' => false, 'class' => 'btn btn-xs red delete_btn tooltips', 'confirm' => __('Are you sure you want to delete this ? ')]);


        // $row[] = array($links, array('class' => 'text-center'));
        $rows[] = $row;
    }
}

/*
$this->start('search');

echo $this->Form->create($users, array(
    'novalidate' => true,
    'type' => 'get',
    'class' => 'form-inline',
));
echo $this->Form->input('q', [
    'templates' => ['inputContainer' => '<div class="form-group col-md-2">{{content}}</div>', 'label' => null],
    'placeholder' => 'User Name'
]);

echo $this->Form->input('age', [
    'options' => $user->ageList,
    'class' => 'form-control select2',
    'templates' => ['inputContainer' => '<div class="form-group col-md-2">{{content}}</div>', 'label' => null],
    'empty' => 'Age'
]);

echo $this->Form->input('country_id', [
    'class' => 'form-control select2',
    'templates' => ['inputContainer' => '<div class="form-group col-md-2">{{content}}</div>', 'label' => null],
    'empty' => 'Country'
]);

echo $this->Form->input('lanaguage', [
    'class' => 'form-control select2',
    'options' => $languages,
    'templates' => ['inputContainer' => '<div class="form-group col-md-2">{{content}}</div>', 'label' => null],
    'empty' => 'Lanaguage'
]);

echo $this->Form->input('muslim_since', [
    'class' => 'form-control select2',
    'type' => 'text',
    'templates' => ['dateWidget' => '{{year}}', 'inputContainer' => '<div class="form-group col-md-2">{{content}}</div>', 'label' => null],
    'placeholder' => 'Muslim Since '.date('Y')
]);


$this->end();
 * 
 */
$this->assign('searchActionRow', "col-md-2");
$btn = $this->Form->button('Search', array('class' => 'btn btn-default pull-right'));
$this->assign('btn', $btn);
if (!empty($rows)) {
    $this->append('table_row', $this->Html->tableCells($rows));
}


$actionBtns .= $this->Html->link('Add New', ['action' => 'add'], ['class' => 'btn btn-default']);
$this->assign('actionBtns', $actionBtns);




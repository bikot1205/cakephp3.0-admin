 <?php
$plugin = $params['plugin'];
$controller = strtolower($params['controller']);
$action = $params['action'];

$menus = array();
$menus[] = $this->Html->link('<i class="ti-home"></i> <span> Dashboard </span>',['plugin' => false,'controller' => 'dashboard','action' => 'index'],['escape' => false]);

// User Menu

$activeClass = ($controller == 'users') ? 'active' : null;
$menus[] = $this->Html->link('<i class="ti-user"></i> <span> Users </span>',['plugin' => false,'controller' => 'users','action' => 'index'],['escape' => false,'class' => '']);

$menus[] = $this->Html->link('<i class="ti-user"></i> <span> User Avatars </span>',['plugin' => false,'controller' => 'users','action' => 'avatars'],['escape' => false,'class' => '']);

$menus[] = $this->Html->link('<i class="ti-user"></i> <span> Rooms </span>',['plugin' => false,'controller' => 'rooms','action' => 'index'],['escape' => false,'class' => '']);


$menus[] = $this->Html->link('<i class=" ti-image"></i> <span> Avatars </span>',['plugin' => false,'controller' => 'avatars','action' => 'index'],['escape' => false,'class' => '']);
$menus[] = $this->Html->link('<i class="ti-face-smile"></i> <span> Smiles </span>',['plugin' => false,'controller' => 'smiles','action' => 'index'],['escape' => false,'class' => '']);
$menus[] = $this->Html->link('<i class="ti-world"></i> <span> Countries </span>',['plugin' => false,'controller' => 'countries','action' => 'index'],['escape' => false,'class' => '']);
$menus[] = $this->Html->link('<i class=" ti-view-list"></i> <span> Languages </span>',['plugin' => false,'controller' => 'languages','action' => 'index'],['escape' => false,'class' => '']);
$menus[] = $this->Html->link('<i class=" ti-layout-menu-v"></i><span> Pages </span>',['plugin' => false,'controller' => 'pages','action' => 'index'],['escape' => false]);
$menus[] = $this->Html->link('<i class="ti-settings"></i><span> Settings </span>',['plugin' => 'settings','controller' => 'settings','action' => 'index'],['escape' => false]);
?>
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <?php echo $this->Html->nestedList($menus, [], ['class' => 'has_sub']);?>
            
        </div>
        <div class="clearfix"></div>
    </div>
</div>

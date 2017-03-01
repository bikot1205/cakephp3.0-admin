<?php 
$controller = strtolower($params['controller']);
$action = strtolower($params['action']);
$userSection = ($controller == 'users' || $controller == 'friends') ? true : false;
?>

<?php  $usersCell = $this->cell('User::activeUsers'); ?>

<div class="sidebar" id="list-menu">
    <div class="tabbable" id="tabs-815318">
        <ul class="nav nav-tabs top-header top-b-r">
            <li class="<?php echo ($controller == 'rooms' && $action == 'recentchat') ? 'active' : ""; ?>">
                <?php //echo $this->Html->link('<i class="fa fa-comments-o" aria-hidden="true"></i>',['controller' => 'rooms','action' => 'recentChat'],['escape' => false]);?>
                <a href="#recent-chat" data-toggle="tab"><i class="fa fa-comments-o" aria-hidden="true" ng-click="activeContents('RecentChat')"></i></a>
            </li>
            <li class="<?php echo ($controller == 'rooms' && $action == 'view') ? 'active' : ""; ?>">
            <?php //echo $this->Html->link('<i class="fa fa-users" aria-hidden="true"></i>',['controller' => 'rooms','action' => 'view'],['escape' => false]);?>
                <a href="#group-chat" data-toggle="tab"><i class="fa fa-users" aria-hidden="true" ng-click="activeContents('Chatbox')"></i></a>
            </li>
            <li class="<?php echo ($userSection) ? 'active' : ""; ?>">
            <?php //echo $this->Html->link('<i class="fa fa-user-plus" aria-hidden="true"></i>',['controller' => 'friends','action' => 'request'],['escape' => false]);?>
                <a href="#all-contacts" data-toggle="tab"><i class="fa fa-user-plus" aria-hidden="true" ng-click="activeContents('Friends')"></i></a>
            </li>
            <li class="devider">
            <?php //echo $this->Html->link('<i class="fa fa-commenting" aria-hidden="true"></i>',['controller' => 'rooms','action' => 'myChat'],['escape' => false]);?>
                <a href="#my-chat" data-toggle="tab"><i class="fa fa-commenting" aria-hidden="true" ng-click="activeContents('MyChat')"></i></a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane <?php echo (!$userSection) ? 'active' : ""; ?>" id="recent_chat">
                <div class="user-list">
                    <div id="l1a" class="optiscroll column mid-50">
                        <!-- <div class="list-heading"><img src="<?php echo $this->request->webroot;?>img/active-user-icon.png" alt="" /> Active Users</div> -->
                        <?php echo $usersCell;?>
                        
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="group_chat">
                <div class="user-list">
                    <div id="l2a" class="optiscroll column mid-50">
                        <!-- <div class="list-heading"><img src="<?php echo $this->request->webroot;?>img/active-user-icon.png" alt="" /> Active Users</div> -->
                        <?php echo $usersCell;?>
                    </div>
                </div>
            </div>
            <div class="tab-pane <?php echo ($userSection) ? 'active' : ""; ?>" id="all_contacts">
                <?php echo $cell = $this->cell('User::contacts'); ?>
            </div>
            <div class="tab-pane" id="my_chat">
                <div class="user-list">
                    <div id="l4a" class="optiscroll column mid-50">
                        <?php echo $usersCell;?>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
<div class="close-menu" id="close-menu"><i class="fa fa-times" aria-hidden="true"></i></div>
<div class="overlep-bg-b"></div>

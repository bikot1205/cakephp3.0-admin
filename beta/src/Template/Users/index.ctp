<?php
$this->assign('page_title', '<i class="fa fa-clock-o"></i> '.__('Results Found'));
if ($params['action'] == 'suggested') {
    $this->assign('page_title', '<div class="btn-group"><button data-toggle="dropdown" class="btn btn-default dropdown-toggle text-uppercase">
        <i class="fa fa-user"></i>  '.__('Suggested Friends').'<span class="caret"></span>
    </button>
    <div class="dropdown-menu">
        <div id="os4" class="optiscroll column-container mid-50">
            <ul>
                <li>'.$this->Html->link('<i class="fa fa-user-plus"></i>'.__('Friend Requests'),['controller' => 'friends','action' => 'request'],['escape' => false]).'</li>
            </ul>
        </div>
    </div></div>');
}
?>
<div class="chat-section">
    <div class="top-header">
        <?= $this->element('search_users'); ?>
    </div>
    <div class="detail-area ">
        <div class="p-20">
            <div class="title" id="hide-filter">
                <?= $this->fetch('page_title');?>
            </div>
            <?= $this->element('filters'); ?>

            <div class="content-area">
                <div id="os3" class="optiscroll column-container mid-50">
                    <?php
                    foreach ($users as $user) {
                        ?>
                        <div class="new-user-list">
                            <div class="new-user-photo">
                                <?php echo $this->Html->link($this->Awesome->userImage($user), ['action' => 'view', 'slug' => $user->slug], ['escape' => false]); ?>
                            </div>
                            <?php if (!isset($user->friend['status'])) { ?>
                            <div class="add-user-option-sec">
                                <ul>
                                    <li class="m-t-15">
                                        <?php
                                        echo $this->Form->postLink($this->Html->image('user-add-icon.png'), ['controller' => 'friends', 'action' => 'addFriendRequest', 'friend' => $user->id], ['escape' => false]);
                                        ?> 
                                    </li>
                                </ul>
                            </div>
                            <?php } ?>
                            <div class="new-user-name m-r-0">
                                <div class="user-name"><?php echo $user->name; ?> </div>
                                <div class="country-flag">
                                    <?php if ($user->country) echo $this->Awesome->image('Countries/flag', $user->country->flag, ['default' => 'flag.jpg']); ?>    
                                </div>
                                <br>
                                <div class="since">Muslim Since : <span><?php echo $user->muslimYear; ?></span></div>
                            </div>
                        </div>
                        <?php } ?>

                        <?php if ($users->count() == 0) { ?>
                        <div class="text-center no-records">
                            <h1><i class="fa fa-bell-o"></i></h1>
                            <h5 class="text-uppercase"><?= __('No search result found.'); ?></h5>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


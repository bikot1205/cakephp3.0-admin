<?php
$this->layout = 'default';
$this->assign('title', 'My Profile');
$this->assign('page_title', 'My Profile');
?>
<div class="chat-section">
<div class="top-header">
        <?= $this->element('search_users'); ?>
    </div>
    <div class="detail-area ">
        <div class="p-20">
            <div class="title">
                <?= $this->fetch('page_title');?>
            </div>

            <div class="content-area">
                <div id="os3" class="optiscroll column-container mid-50">
                    <div class="add-user-section">
                        <div class="add-user-photo">
                            <?php echo $this->Awesome->userImage($user); ?>
                        </div>
                        <div class="add-user-name">
                            <?php echo $user->name; ?>
                        </div>
                        <div class="add-user-info">
                            <ul>
                                <li>Gender : <?php echo $user->genderTxt; ?></li>
                                <li>Age: <?php echo $user->age; ?></li>
                                <li>Country: <?php echo $user->country->name; ?> <?php echo $this->Awesome->image('Countries/flag', $user->country->flag, ['default' => 'flag.jpg']); ?></li>
                                <li class="d-block">Email Address : <?php echo $user->email; ?></li>
                                <li>Muslim Since: <?php echo $user->muslimYear; ?></li>
                            </ul>
                        </div>
                        <div class="add-user-btn">
                            <?php
                            $actionButton = null;
                            if (empty($friend) && $loggedInAs['id'] != $user->id) {
                                $actionButton = $this->Form->postLink('<input type="submit" value="Add User" class="blue-btn">', ['controller' => 'friends', 'action' => 'addFriendRequest', 'friend' => $user->id], ['escape' => false]);
                            }

                            if ($loggedInAs['id'] == $user->id) {
                                $actionButton = $this->Html->link('<input type="submit" value="Edit Profile" class="blue-btn">', ['action' => 'edit'], ['escape' => false]);
                            }
                            echo $actionButton;
                            ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

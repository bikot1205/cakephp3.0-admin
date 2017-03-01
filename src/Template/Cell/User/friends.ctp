<h4 class="m-t-0 m-b-20 header-title">
    <b>Friends <span class="text-muted">(<?php echo count($friends); ?>)</span></b>
</h4>
<div class="friend-list">
    <?php
    if (!empty($friends))
        foreach ($friends as $user) {

            echo $this->Html->link($this->Awesome->userImage($user['Users']), ['controller' => 'users', 'action' => 'view', 'slug' => $user['Users']['slug']], ['escape' => false, 'title' => $user['Users']['first_name'] . ' ' . $user['Users']['last_name']]);
        } else {
        echo 'No friends yet!!!';
    }
    ?>
    <!--    <a href="#" class="text-center">
            <span class="extra-number">+89</span>
        </a>-->
</div>

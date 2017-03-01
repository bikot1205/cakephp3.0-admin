<?php 
$friendsList = array();
foreach ($friends as $key => $user) {
$friendsList[$key] =  $user;
$actionButton = '';
            if ($user->friend['status'] == 0 && $user->friend['action_user_id'] == $loggedInAs['id']) {
                $actionButton = ' <li>' . $actionButton = $this->Form->postLink('Approval Pending', '#', ['escape' => false]) . ' </li>';
                $actionButton .= " <li>" . $this->Form->postLink($this->Html->image('cancel-icon.png'), ['controller' => 'friends', 'action' => 'removeFriendRequest', 'friend' => $user->id], ['escape' => false, 'class' => 'action-btn ']) . ' </li>';
            } else if ($user->friend['status'] == 0) {
                $actionButton = " <li>" . $this->Form->postLink($this->Html->image('check-icon.png'), ['controller' => 'friends', 'action' => 'acceptFriendRequest', 'friend' => $user->id], ['escape' => false, 'class' => 'action-btn ']) . ' </li>';
                $actionButton .= " <li>" . $this->Form->postLink($this->Html->image('cancel-icon.png'), ['controller' => 'friends', 'action' => 'declineFriendRequest', 'friend' => $user->id], ['escape' => false, 'class' => 'action-btn ']) . ' </li>';
            }


$friendsList[$key]['image'] = $this->Html->link($this->Awesome->userImage($user), ['controller' => 'users', 'action' => 'view', 'slug' => $user->slug], ['escape' => false]);

$friendsList[$key]['action'] = $actionButton;
$friendsList[$key]['muslimYear'] = $user->muslimYear;
$friendsList[$key]['country']['flag'] = $this->Awesome->image('Countries/flag', $user->country->flag, ['default' => 'flag.jpg']);
}

echo json_encode(array('friends' => $friendsList));
die;

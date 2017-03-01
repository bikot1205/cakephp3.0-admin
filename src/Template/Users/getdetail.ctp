<?php

$user['name'] = $user->name;
$userImage  = $this->Awesome->userImage($user, false);
$user['expand'] = 0;
$user['country_flag'] = $this->Awesome->image('Countries/flag', $user->country->flag, ['default' => 'flag.jpg','class' => 'f-right']);
$user['image'] = $this->Html->image($userImage);
$user['imageUrl'] = $userImage;
$user['busy'] = false;
echo json_encode($user);
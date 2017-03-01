<?php use Cake\I18n\Time;

foreach($recentChats as $key => $chat) {
    $now = new Time($chat['created_at']);
    $recentChats[$key]['created_at'] = $now->timeAgoInWords([
        'accuracy' => 'minute'
        ]);
    $recentChats[$key]['user']['image'] = $this->Awesome->userImage($chat['user']);

} 

echo json_encode(array('recentChats' => $recentChats));
die;


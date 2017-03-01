<?php 
$membersList = array();
$sr = 1;
foreach ($members as $key => $user) {
    $user = $user->user;
    $key = $user->slug;
    $membersList[$key] = $user->toArray();
    $membersList[$key]['busy'] = false;
    $membersList[$key]['name'] = $user->name;
    $membersList[$key]['sr'] = $this->Awesome->formatSrNumber($sr);
    $membersList[$key]['expand'] = 0;
    $userImage  = $this->Awesome->userImage($user, false);
    $membersList[$key]['country_flag'] = $this->Awesome->image('Countries/flag', $user->country->flag, ['default' => 'flag.jpg','class' => 'f-right']);
    
    $membersList[$key]['image'] = $this->Html->image($userImage);
    $membersList[$key]['imageUrl'] = $userImage;
    $sr++;
}

$smilesList = array();
$smilesCode = array();
foreach ($smiles as $key => $smile) {
    $smilesCode[] = $smile['code'];
    $smilesList[$smile['code']] = array(
        'name' => $smile['name'],
        'code' => $smile['code'],
        'image' => $this->Awesome->image('Smiles/image', $smile['image'],['title' => $smile['name'],'tag' => false])
        );
}

echo json_encode(array(
'roomData' => $room->user_meta,
'roomDetails' => $room,
'loggedIn' => $loggedInAs,
'membersList' => $membersList,
'smiles'  => $smilesList,
'smilesCode' => $smilesCode
));

?>
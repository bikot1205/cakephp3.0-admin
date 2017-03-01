<?php

$result = array();
foreach ($friends as $key => $user) {

    $result[$key] = $user->toArray();
    $result[$key]['name'] = $user->name;
    $result[$key]['expand'] = 0;
    $result[$key]['muslimYear'] = $this->Awesome->formatSr($key+1);
    $result[$key]['country_flag'] = $this->Awesome->image('Countries/flag', $user->country->flag, ['default' => 'flag.jpg','class' => 'f-right']);
    $result[$key]['image'] = $this->Awesome->userImage($user);
    
}
echo json_encode(array('contacts' => $result));
?>

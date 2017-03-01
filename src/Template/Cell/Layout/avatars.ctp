<?php
$avatarsList = array();
if ($avatars && $avatars->count() > 0) {
    foreach ($avatars as $avatar) {
        $avatarsList[$avatar->id] = array(
            'id' => $avatar->id,
            'avatar' => $this->Awesome->image('Avatars/image', $avatar->image, ['width' => '80px;'])
        );
    }
}

echo '<script type="text/javascript">';
echo 'var avatars = ' . json_encode($avatarsList) . ';';
echo '</script>';
?>




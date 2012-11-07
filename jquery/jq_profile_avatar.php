<?php
global $USER;

$user_wp = (int)$_SESSION['WP_USER']['user_wp'];
$photo_id = (int)$_POST['photo_id'];
$action = $_POST['action'];
switch ($action) {
    case 'delete':
        delete_avatar($user_wp, $photo_id);
        break;
     case 'set_to_default':
        set_to_default($user_wp, $photo_id);
        break;
    default:
        # code...
        break;
}
function delete_avatar($user_wp,$photo_id)
{
    global $MYSQL;
    $tbl = 'pfx_users_photos';
    $MYSQL -> query("DELETE FROM $tbl WHERE `id`=$photo_id AND `user_wp`=$user_wp");
}
function set_to_default($user_wp,$photo_id)
{
    global $USER;
    global $MYSQL;
    $tbl = 'pfx_users_photos';
    $photo = $MYSQL -> query("SELECT `photo`, `domen` FROM $tbl WHERE `user_wp`=$user_wp AND `id`=$photo_id");
    if (is_array($photo))
        $photo = $photo[0];
    else
        return 0;
    $MYSQL -> query("UPDATE `pfx_users` SET `photo`='{$photo['photo']}', `domen`='{$photo['domen']}'  WHERE `user_wp`=$user_wp");
}
?>
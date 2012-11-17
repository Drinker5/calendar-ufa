<?php
global $MYSQL,$USER;

$GLOBALS['PHP_FILE'] = __FILE__;
$GLOBALS['FUNCTION'] = __FUNCTION__;
function getRecommendUsers($offset,$count)
{
    $GLOBALS['PHP_FILE'] = __FILE__;
    $GLOBALS['FUNCTION'] = __FUNCTION__;
    global $MYSQL,$USER;
    $count = (int)$count;
    $offset = (int)$offset;
    $output = '';
    $curr_user_id = $_SESSION['WP_USER']['user_wp'];
    $user_wp = $_SESSION['WP_USER']['user_wp'];
    $curr_town_id = $_SESSION['WP_USER']['town_id'];
    $filter = $MYSQL -> query("SELECT `user_wp` FROM `pfx_users` AS `u` WHERE `u`.`user_wp` <> {$user_wp} AND `u`.`user_wp` NOT IN (SELECT CASE WHEN  `pfx_users_friends`.`user_wp`= $user_wp
THEN  `pfx_users_friends`.`friend_wp` 
ELSE  `pfx_users_friends`.`user_wp` 
END AS `wp`
FROM  `pfx_users_friends` 
WHERE  (`pfx_users_friends`.`user_wp`=$user_wp
OR  `pfx_users_friends`.`friend_wp` =$user_wp) GROUP BY `wp`) LIMIT $offset,$count");
    if (count($filter) == 0)
        return array();
    $user_wp_list = array();
    foreach ($filter as $key => $value) 
        $user_wp_list[] = $value['user_wp'];
    $users = $USER-> Info_min_group($user_wp_list,70,70);
    return $users;
} 

$offset = (int)$_POST['offset'];
$count = (int)$_POST['count'];           
$response = array();
$users = getRecommendUsers($offset,$count);
$response['stop'] = (count($users)<$count)?1:0;
$response['html'] = $users;
echo json_encode($response);
?>

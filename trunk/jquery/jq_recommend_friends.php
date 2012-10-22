<?php
global $MYSQL,$USER;
$GLOBALS['PHP_FILE'] = __FILE__;
$GLOBALS['FUNCTION'] = __FUNCTION__;

function getRecommendUsers($offset,$count)
{
    global $MYSQL,$USER;
    $count = (int)$count;
    $offset = (int)$offset;
    $output = '';
    $curr_user_id = $_SESSION['WP_USER']['user_wp'];
    $curr_town_id = $_SESSION['WP_USER']['town_id'];
    $users = $MYSQL -> query("SELECT user_wp FROM pfx_users AS u WHERE u.user_wp <> $curr_user_id AND u.user_wp NOT IN (SELECT friend_wp FROM pfx_users_friends AS f WHERE f.user_wp = $curr_user_id) LIMIT $offset,$count");
    if (is_array($users))
    {
        foreach ($users as $key => $user) {
            $output .= userInfoHtml($user['user_wp']); 
        }
    }
    return $output;
} 
function userInfoHtml($user_id)
{
    global $USER;
    $new_usr = $USER->Info_min($user_id,70,70);
    $output ='';
    $output .= '<div class="friend-item fl_l">';
    $output .= '<div class="bordered medium-avatar fl_l">';
    $output .= '<img src="'.$new_usr['photo'].'" alt="">';
    $output .= '</div>';
    $output .= '<div class="content wrapped">';
    $output .= OnlineStatus($new_usr['status_chat']);
    $output .= '<span class="name">'.$new_usr['firstname'].' '.$new_usr['lastname'].'</span>';
    $output .= '<br>';
    $output .= '<span class="place">'.$new_usr['country_name'].', '.$new_usr['town_name'].'</span>';
    $output .= '</div>';
    $output .= '<span class="popover-btn find-friend-actions opacity_link">';
    $output .= '<i class="small-icon icon-action"></i>';
    $output .= 'Действия';
    $output .= '<i class="small-icon icon-grey-arrow"></i>';
    $output .= '</span>';
    $output .= '</div>';
    return $output;
}
$offset = (int)$_POST['offset'];
$count = (int)$_POST['count'];           
$response = array();
$html = getRecommendUsers($offset,$count);
$response['stop'] = (strlen($html)<10)?1:0;
$response['html'] = $html;
echo json_encode($response);
?>

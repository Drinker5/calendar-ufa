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
    $output .= '<a href="/'.$user_id.'"><img src="'.$new_usr['photo'].'" alt=""></a>';
    $output .= '</div>';
    $output .= '<div class="content wrapped">';
    $output .= OnlineStatus($new_usr['status_chat']);
    $output .= '<span class="name"><a href="/'.$user_id.'">'.$new_usr['firstname'].' '.$new_usr['lastname'].'</a></span>';
    $output .= '<br>';
    $output .= '<span class="place">'.$new_usr['country_name'].', '.$new_usr['town_name'].'</span>';
    $output .= '</div>';
    $output .= '<div class="tools_block hide absolute tx_r">
                            <span class="fl_l">
                               <a href="#" class="add_new_friend opacity_link" data-user="'.$user_id.'" data-name="'.$new_usr['firstname'].' '.$new_usr['lastname'].'"><i original-title="Добавить в друзья" class="tipN active small-icon icon-add-friend"></i></a>
                                <a href="#" class="opacity_link"><i original-title="Сделать подарок" class="tipN active small-icon icon-gift"></i></a>
                                <a href="#" class="opacity_link"><i original-title="Написать сообщение" class="tipN active small-icon icon-chat"></i></a>
                                <a href="#" class="opacity_link"><i original-title="Пригласить" class="tipN active small-icon icon-invite"></i></a>
                            </span>
                        </div>';
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

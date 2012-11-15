<?php
if(isset($_POST['name']))         $name=strtolower(addslashes(trim($_POST['name'])));else $name='';
if(isset($_POST['online']) && strlen($_POST['online'])>0)        $online=true;else $online=false;
if(isset($_POST['age_from']))      $age_from=(int)$_POST['age_from'];else $age_from=0;
if(isset($_POST['age_to']))        $age_to=(int)$_POST['age_to'];else $age_to=999;
if(isset($_POST['region']))        $region=(int)$_POST['region'];else $region=-1;
if(isset($_POST['marital_status'])) $marital_status=(int)$_POST['marital_status'];else $marital_status=-1;
if(isset($_POST['search_offset'])) $offset = (int)$_POST['search_offset']; else $offset = 0;
if(isset($_POST['sex']) && strlen($_POST['sex'])>0 ) $sex = (int)$_POST['sex']; else $sex='';
global $MYSQL;
global $USER;
global $COUNTRY;
$GLOBALS['PHP_FILE'] = __FILE__;
$GLOBALS['FUNCTION'] = __FUNCTION__;
function CheckBox($val)
{
    if (isset($val) && strlen($val)>0)
        return true;
    return false;
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


$count = (int)$_POST['count'];
$query = array();
if ($name != '')
    $query[] = " ( CONCAT(u.lastname,' ',u.firstname) LIKE '%".$name."%' OR CONCAT(u.firstname,' ',u.lastname) LIKE '%".$name."%') ";
if ($online)
    $query[] = " (u.status_chat !=0 AND u.online + INTERVAL 10 MINUTE > now()) ";
if ($region != -1)
    $query[] = " ($region = (SELECT parent from pfx_country WHERE pfx_country.id = u.town_id)) ";
if ($age_to > 0)
    $query[] = " ( (YEAR(CURRENT_DATE) - YEAR(`birthday`)) - (RIGHT(CURRENT_DATE,5) < RIGHT(`birthday`,5))  >= $age_from AND (YEAR(CURRENT_DATE) - YEAR(`birthday`)) - (RIGHT(CURRENT_DATE,5) < RIGHT(`birthday`,5)) <= $age_to) ";
if ($marital_status != -1)
    $query[] = " (u.marital_status = $marital_status) ";
if (CheckBox($sex))
{
    $query[] = " (u.sex = $sex) ";
}
$query_string = " AND ".implode(" AND ", $query);
$curr_user_id = $_SESSION['WP_USER']['user_wp'];
$qs = "SELECT user_wp,birthday  FROM pfx_users AS u WHERE u.user_wp <> $curr_user_id AND u.user_wp NOT IN (SELECT friend_wp FROM pfx_users_friends AS f WHERE f.user_wp = $curr_user_id)  $query_string LIMIT $offset,$count";
$users = $MYSQL -> query("SELECT user_wp,birthday  FROM pfx_users AS u WHERE u.user_wp <> $curr_user_id AND u.user_wp NOT IN (SELECT friend_wp FROM pfx_users_friends AS f WHERE f.user_wp = $curr_user_id)  $query_string LIMIT $offset,$count");
$html ='';
if (is_array($users))
{
    foreach ($users as $key => $user) {
    $html .= userInfoHtml($user['user_wp']);
    }
}

$stop = 0;
if (strlen($html)<10)
    $stop = 1;
$response = array();
$response['stop'] = $stop;
$response['html'] = $html;
echo json_encode($response);





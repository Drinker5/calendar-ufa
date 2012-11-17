<?php
global $MYSQL;
global $USER;
$GLOBALS['PHP_FILE'] = __FILE__;
$GLOBALS['FUNCTION'] = __FUNCTION__;

if(isset($_POST['name']) && strlen($_POST['name']) > 0)         $name=strtolower(addslashes(trim($_POST['name'])));else $name='';
if(isset($_POST['online']) && (int)$_POST['online']>0)        $online=true;else $online=false;
if(isset($_POST['age_from']) && (int)$_POST['age_from'] > 0)      $age_from=(int)$_POST['age_from'];else $age_from=0;
if(isset($_POST['age_to']) && (int)$_POST['age_to'] > 0)        $age_to=(int)$_POST['age_to'];else $age_to=999;
if(isset($_POST['region']))        $region=(int)$_POST['region'];else $region=-1;
if(isset($_POST['marital_status'])) $marital_status=(int)$_POST['marital_status'];else $marital_status=-1;
if(isset($_POST['search_offset'])) $offset = (int)$_POST['search_offset']; else $offset = 0;
if(isset($_POST['sex']) && (int)$_POST['sex']>0 ) $sex = (int)$_POST['sex']; else $sex=0;

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
if ($sex>0)
{
    $query[] = " (u.sex = $sex) ";
}
if (count($query) > 0)
    $query_string = " AND ".implode(" AND ", $query);
else
    $query_string = '';
$query_string = (count($query) > 0)?" AND ".implode(" AND ", $query):'';
$curr_user_id = $_SESSION['WP_USER']['user_wp'];
$user_wp = $curr_user_id;

$qs = "SELECT user_wp,birthday  FROM pfx_users AS u WHERE u.user_wp <> $curr_user_id AND u.user_wp NOT IN (SELECT CASE WHEN  `pfx_users_friends`.`user_wp`= $user_wp
        THEN  `pfx_users_friends`.`friend_wp` 
        ELSE  `pfx_users_friends`.`user_wp` 
        END AS `wp`
        FROM  `pfx_users_friends` 
        WHERE  (`pfx_users_friends`.`user_wp`=$user_wp
        OR  `pfx_users_friends`.`friend_wp` =$user_wp) GROUP BY `wp`)  $query_string LIMIT $offset,$count";
$result = $MYSQL -> query($qs);
$users_wp_list = array();
$users = array();
if (count($result) > 0  )
{
    foreach ($result as $value)
        $users_wp_list[] = $value['user_wp'];
    $users = $USER-> Info_min_group($users_wp_list,70,70);
}

$html ='';
$response = array();
//Первый запрос (без скролла) и ничего не нашлось
if (count($users) == 0 && $offset == 0)
    $response['found'] = '0';
else
    $response['found'] = '1';

$response['stop'] = (count($users)<$count)?1:0;
$response['html'] = $users;
echo json_encode($response);





<?php
//$db = mysql_pconnect('localhost', 'root', '');
//mysql_select_db('discount',$db);
//mysql_query("SET NAMES 'utf8'");
//$_SESSION['WP_USER']['user_wp'] - id (user_wp) текущего пользователя

global $MYSQL;
$GLOBALS['PHP_FILE'] = __FILE__;
$GLOBALS['FUNCTION'] = __FUNCTION__;

$id = @$_POST['id'];
$start = @$_POST['start'];
$end = @$_POST['end'];
$after = @$_POST['after'];
$type = @$_POST['type'];
$op = @$_POST['op'];
$repeat = @$_POST['repeat']; 
$finish = @$_POST['finish'];
$remind = @$_POST['remind'];
$b = 1;
$color = 'red';


$tbakcia = 'pfx_akcia';

function DayAdd($old_date,$days){
/*	$date_time = explode(' ',$old_date);
	$date = explode('-',$date_time[0]);
	$time = explode(':',$date_time[1]);
								// mktime(hour, minute, second, month, day, year);
	$new_date = date("Y-m-d H:i:s",mktime($time[0],$time[1],$time[2], $date[1],$date[2]+$days,$date[0]));
*/
	$date_time = strtotime($old_date);
	$new_date = strtotime("+$days day", $date_time);
	return date("Y-m-d H:i:s",$new_date);
}

switch ($op) {
	case 'add':
		$sql = 'INSERT INTO `discount_users_events` (
			`owner_wp`,
			`data_start`, 
			`data_end`, 
			`data_after`,
			`title`,
			`repeat`,
			`finish`) 
			VALUES 
			("' . $_SESSION['WP_USER']['user_wp'] . '",
			 "' . date("Y-m-d H:i:s", strtotime($start)) . '",
			 "' . date("Y-m-d H:i:s", strtotime($end)) . '", 
			 "' . date("Y-m-d", strtotime($after)) . '", 
			 "' . $type . '",
			 "' . $repeat . '",
			 "' . $finish . '")';

		if (mysql_query($sql)) {
			echo mysql_insert_id();
			$USER->AddDeystvie(0,$_SESSION['WP_USER']['user_wp'],10,mysql_insert_id());
		}
		break;
	case 'editdate':
		$sql = 'UPDATE `discount_users_events` SET 	
				`data_start` = "' . date("Y-m-d H:i:s", strtotime($start)) . '",
				`data_end`	  = "' . date("Y-m-d H:i:s", strtotime($end)) . '"
				WHERE `id` = "' . $id . '"';
		if (mysql_query($sql)) { echo $id; }
		break;
	case 'edit':
		$sql = 'UPDATE `discount_users_events` SET 	
				`data_start` = "' . date("Y-m-d H:i:s", strtotime($start)) . '",
				`data_end`	  = "' . date("Y-m-d H:i:s", strtotime($end)) . '",
				`data_after`	  = "' . date("Y-m-d", strtotime($after)) . '",
				`title`  = "' . $type . '",
				`repeat` = "' . $repeat .'",
				`finish` = "' . $finish .'"
				WHERE `id` = "' . $id . '"';
		if (mysql_query($sql)) { echo $id; }
		break;
	case 'source':
		switch($type){
			case 'akcia':
				$sql = 'SELECT DISTINCT discount_akcia.* 
						FROM discount_podpiska_view
						INNER JOIN discount_akcia ON discount_akcia.id = discount_podpiska_view.akcia_id
						INNER JOIN discount_podpiska_ban ON discount_akcia.id <> discount_podpiska_ban.akcia_id
						WHERE discount_akcia.del = 0
						AND discount_akcia.Moderator = 1
						AND adminview = 1
						AND discount_podpiska_view.user_wp = '.$_SESSION['WP_USER']['user_wp'].'
						AND discount_podpiska_ban.user_wp <> '.$_SESSION['WP_USER']['user_wp'].'';
				$result = mysql_query($sql);
				$json = Array();
				
				while ($row = mysql_fetch_assoc($result)) {
					switch($row['idtype'])
					{
						case 1:	$color = '#79CDCD';break;//аква
						case 2:	$color = '#8B4513';break;//шоколад
						case 3:	$color = '#469ad7';break;//синий
						case 4:	$color = '#d3111f';break;//красный
						case 5:	$color = '#9a46d7';break;//фиолетовый
						default:$color = '#94d11f';break;//зеленый	
					}
					$json[] = array(
						'id' => $row['id'],
						'title' => $row['header'],
						'start' => $row['DiscData1'],
						'end' => $row['DiscData2'],
						'color' => $color,
						'textColor' => 'white',
						'editable' => false,
						'allDay' => false,
					);
					
				}

			break;
			case 'user_events':
				$sql = 'SELECT *
						FROM discount_users_events
						WHERE deleted = 0 
						AND owner_wp = '.$_SESSION['WP_USER']['user_wp'];
				$json = Array();
				$result = mysql_query($sql);
				$data_after = date("Y-m-d", strtotime($after));
				$color = '#94d11f'; 
				while ($row = mysql_fetch_assoc($result)) {
					if(isset($row['repeat']) && $row['repeat']!=0){ //Если повторять
						switch ($row['finish']) //Завершение
						{
						case 0: //Не завершать
							$count = 365*2; //На 2 года
							$i=0;
							while($i*$row['repeat'] <= $count){
								$data_start = DayAdd($row['data_start'],$i*$row['repeat']);
								$data_end = DayAdd($row['data_end'],$i*$row['repeat']);
								
								$json[] = array(
									'id' => $row['id'],
									'title' => $row['title'],
									'start' => $data_start,
									'end' => $data_end,
									'color' => $color,
									'textColor' => 'white',
									'editable' => true,
									'allDay' => false,
									
									'after' =>  $data_after,
									'repeat' => $row['repeat'],
									'finish' => $row['finish'],
									'remind' => $row['napominanie'],
									
								);
								$i++;
							}
						break;
						case 2: //После 2-х раз
							for($i=0;$i<=2;$i++){
								$data_start = DayAdd($row['data_start'],$i*$row['repeat']);
								$data_end = DayAdd($row['data_end'],$i*$row['repeat']);
								
								$json[] = array(
									'id' => $row['id'],
									'title' => $row['title'],
									'start' => $data_start,
									'end' => $data_end,
									'color' => $color,
									'textColor' => 'white',
									'editable' => true,
									'allDay' => false,
									
									'after' =>  $data_after,
									'repeat' => $row['repeat'],
									'finish' => $row['finish'],
									'remind' => $row['napominanie'],

								);
							}
							
						break;
						case 3: //После 3-х раз
							for($i=0;$i<=3;$i++){
								$data_start = DayAdd($row['data_start'],$i*$row['repeat']);
								$data_end = DayAdd($row['data_end'],$i*$row['repeat']);
								
								$json[] = array(
									'id' => $row['id'],
									'title' => $row['title'],
									'start' => $data_start,
									'end' => $data_end,
									'color' => $color,
									'textColor' => 'white',
									'editable' => true,
									'allDay' => false,
									
									'after' =>  $data_after,
									'repeat' => $row['repeat'],
									'finish' => $row['finish'],
									'remind' => $row['napominanie'],

								);
							}
						break;
						
						case 1: //После даты
							$data_end = date("Y-m-d",strtotime($row['data_end']));
							$data_after = date("Y-m-d",strtotime($row['data_after']));
							$i=0;
							while($data_end <= $data_after){
								$data_start = DayAdd($row['data_start'],$i*$row['repeat']);
								$data_end = DayAdd($row['data_end'],$i*$row['repeat']);
								$json[] = array(
									'id' => $row['id'],
									'title' => $row['title'],
									'start' => $data_start,
									'end' => $data_end,
									'color' => $color,
									'textColor' => 'white',
									'editable' => true,
									'allDay' => false,
									
									'after' =>  $data_after,
									'repeat' => $row['repeat'],
									'finish' => $row['finish'],
									'remind' => $row['napominanie'],

								);
								$i++;
							}
						break;
						default: 
							$json[] = array(
								'id' => $row['id'],
								'title' => $row['title'],
								'start' => $row['data_start'],
								'end' => $row['data_end'],
								'color' => $color,
								'textColor' => 'white',
								'editable' => true,
								'allDay' => false,
								
								'after' =>  $data_after,
								'repeat' => $row['repeat'],
								'finish' => $row['finish'],
								'remind' => $row['napominanie'],
	
							);
						}
					}
					else {
						$json[] = array(
							'id' => $row['id'],
							'title' => $row['title'],
							'start' => $row['data_start'],
							'end' => $row['data_end'],
							'color' => $color,
							'textColor' => 'white',
							'editable' => true,
							'allDay' => false,
							
							'after' =>  $data_after,
							'repeat' => $row['repeat'],
							'finish' => $row['finish'],
							'remind' => $row['napominanie'],

						);
					}
				}
			break;
			case 'user_friends_events':
				$sql = 'SELECT DISTINCT discount_users_events.*
						FROM discount_users_events
						LEFT OUTER JOIN discount_users_friends ON discount_users_friends.friend_wp = discount_users_events.owner_wp AND discount_users_friends.good = 1
						LEFT OUTER JOIN discount_users_events_visible ON discount_users_events_visible.event_id = discount_users_events.id 
						WHERE discount_users_events.deleted = 0 
						AND (discount_users_events_visible.friend_wp = '.$_SESSION['WP_USER']['user_wp'].'
						OR (discount_users_events.visible_all = 1 AND discount_users_friends.user_wp = '.$_SESSION['WP_USER']['user_wp'].'))';
				$json = Array();
				$result = mysql_query($sql);
				$color = '#469ad7'; 
				
				while ($row = mysql_fetch_assoc($result)) {
					$json[] = array(
						'id' => $row['id'],
						'title' => $row['title'],
						'start' => $row['data_start'],
						'end' => $row['data_end'],
						'color' => $color,
						'textColor' => 'white',
						'editable' => false,
						'allDay' => false,
					);
				}
			break;
			
			case 'birthday':
				$sql = 'SELECT DISTINCT discount_users.*, discount_users_friends.good
						FROM discount_users_friends
						INNER JOIN discount_users ON discount_users.user_wp = discount_users_friends.friend_wp
						WHERE discount_users_friends.user_wp ='.$_SESSION['WP_USER']['user_wp'].'
						AND discount_users_friends.good =1';
				$result = mysql_query($sql);
				$json = Array();
				while ($row = mysql_fetch_assoc($result)) {
					if(!isset($row['birthday'])) continue;
					$year = '2012';  
					$month = substr($row['birthday'],5,2);
					$day = substr($row['birthday'],8,2);
					$json[] = array(
						//'id' => $row['id'],
						'title' => $row['firstname'].' '.$row['lastname'].' празднует день рождения!',
						'start' => $year.'-'.$month.'-'.$day,
						'color' => '#ddd',
						'textColor' => 'black',
						'allDay' => false,
						'editable' => false,
						'url' => ''.$row['user_wp'].''
					);
				}
			break;
		}
		echo json_encode($json);
		break;
	case 'delete':
		/*$sql = 'INSERT INTO discount_podpiska_ban (user_wp, akcia_id) VALUES ("' .$_SESSION['WP_USER']['user_wp']. '", "'.$id.'")';
		if (mysql_query($sql)) {
			echo $id;
		}*/
		$sql = 'UPDATE discount_users_events SET deleted = 1 WHERE id = '.$id;
		if (mysql_query($sql)) {
			echo $id;
		}
		
		break;
}

if(isset($_GET["term"])){
	$param = $_GET["term"];
	
	require_once("include/translit_ru.php");
	$param_en = translit_ru($param);
	/*$param_rus = translit_lat($param);*/
	//Заправшиваем базу данных
	$query = mysql_query("SELECT DISTINCT `discount_users`.* 
						FROM `discount_users_friends` 
						INNER JOIN `discount_users` ON `discount_users`.`user_wp` = `discount_users_friends`.`friend_wp` 
						WHERE `discount_users_friends`.`user_wp` = ".$_SESSION['WP_USER']['user_wp']."
						AND (UPPER(`discount_users`.`firstname`) REGEXP UPPER('^$param') 
						OR UPPER(`discount_users`.`lastname`) REGEXP UPPER('^$param')
						OR UPPER(`discount_users`.`firstname`) REGEXP UPPER('^$param_en') 
						OR UPPER(`discount_users`.`lastname`) REGEXP UPPER('^$param_en'))");/*
						OR UPPER(`discount_users`.`firstname`) REGEXP UPPER('^$param_rus') 
						OR UPPER(`discount_users`.`lastname`) REGEXP UPPER('^$param_rus'))");*/
	
	//строим массив результата/ы
	for ($x = 0, $numrows = mysql_num_rows($query); $x < $numrows; $x++) {
		$row = mysql_fetch_assoc($query);
    
		$friends[$x] = array("friend_wp" => $row["user_wp"],
							"name" => $row["firstname"].' '.$row['lastname']);		
	}
	
	//Выводим JSON на страницу
	$response = $_GET["callback"] . "(" . json_encode($friends) . ")";
	echo $response;
}

?>

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
$title = @$_POST['title'];
$op = @$_POST['op'];
$notes = @$_POST['notes'];
$repeat = @$_POST['repeat']; 
$finish = @$_POST['finish'];
$remind = @$_POST['remind'];
$place = @$_POST['place'];
$textsearch = @$_POST['textsearch'];
$PrivFriends = @$_POST['PrivFriends'];
$privacy = @$_POST['privacy'];

$color = 'red';


$tbakcia = 'pfx_akcia';

function DayAdd($old_date,$days){
	$date_time = strtotime($old_date);
	$new_date = strtotime("+$days day", $date_time);
	return date("Y-m-d H:i:s",$new_date);
}
/*
if(isset($start)){
	$date = explode('/',$start);	$start = @$date[1] .'/'. @$date[0] .'/'. @$date[2];
}
if(isset($_POST['end'])){
	$date = explode('/',$end);		$end = @$date[1] .'/'. @$date[0] .'/'. @$date[2];
}
if(isset($_POST['after'])){
	$date = explode('/',$after);	$after = @$date[1] .'/'. @$date[0] .'/'. @$date[2];
}
*/
switch ($op) {
	case 'add':
			
		$sql = 'INSERT INTO `discount_users_events` (
			`owner_wp`,
			`data_start`, 
			`data_end`, 
			`data_after`,
			`zametki`,
			`title`,
			`repeat`,
			`finish`,
			`visible_all`,
			`place_id`) 
			VALUES 
			("' . $_SESSION['WP_USER']['user_wp'] . '",
			 "' . date("Y-m-d H:i:s", strtotime($start)) . '",
			 "' . date("Y-m-d H:i:s", strtotime($end)) . '", 
			 "' . date("Y-m-d", strtotime($after)) . '",
			 "' . $notes . '",			 
			 "' . $title . '",
			 "' . $repeat . '",
			 "' . $finish . '",
			 "' . $privacy . '",
			 "' . $place . '")';
	
		if (mysql_query($sql)) {
			$event_id = mysql_insert_id();
			echo $event_id;
			if ($privacy==0){
				$values = '';
				$sql =  "INSERT INTO  `discount_users_events_visible` (`event_id` , `friend_wp`) VALUES ";

				foreach  ($PrivFriends as $key=>$value){
					if ($value['added'] == 'true')
						$values.='(' . $event_id . ',' . $value['friend_wp'] . '),';
				}
				//Удаляет запятую с конца
				if($values != ''){ $values = substr($values, 0, strlen($values)-1);
					$sql .= $values;
					mysql_query($sql);
				}
			}	
			$USER->AddDeystvie(0,$_SESSION['WP_USER']['user_wp'],10,$event_id);
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
				`data_start`  = "' . date("Y-m-d H:i:s", strtotime($start)) . '",
				`data_end`	  = "' . date("Y-m-d H:i:s", strtotime($end)) . '",
				`data_after`  = "' . date("Y-m-d", strtotime($after)) . '",
				`title`   	  = "' . $title . '",
				`repeat`  	  = "' . $repeat .'",
				`finish`  	  = "' . $finish .'",
				`napominanie` = "' . $remind . '",
				`visible_all` = "' . $privacy . '",
				`place_id` 	  = "' . $place . '",
				`zametki`	  = "' . $notes . '"
				WHERE `id` 	  = "' . $id . '"';
		if (mysql_query($sql)) { echo $id; }
		
		//Не показывать никому либо Список друзей на просмотр
		if($privacy == 0){		
			//Удалить все старые поля приватности этого эвента
			$sql = "DELETE FROM `discount_users_events_visible` WHERE `event_id` = $id";
			mysql_query($sql);

			//Собираем запрос на вставку новых значений
			$sql =  "INSERT INTO  `discount_users_events_visible` (`event_id` , `friend_wp`) VALUES ";
			foreach  ($PrivFriends as $key=>$value){
				if ($value['added'] == 'true')
					$values.='(' . $id . ',' . $value['friend_wp'] . '),';
			}
			//Если есть кто-то в доступе на приватность добавляем
			if($values != ''){
				$sql .= substr($values, 0, strlen($values)-1);
				mysql_query($sql);
			}
		}
		break;
	case 'source':
		switch($type){
			case 'akcia':
				if(strlen($textsearch) >= 1) $searchtask = " AND `header` LIKE '%".$textsearch."%' ";
					else $searchtask = "";
				$sql = 'SELECT DISTINCT discount_akcia.* 
						FROM discount_podpiska_view
						INNER JOIN discount_akcia ON discount_akcia.id = discount_podpiska_view.akcia_id
						INNER JOIN discount_podpiska_ban ON discount_akcia.id <> discount_podpiska_ban.akcia_id
						WHERE discount_akcia.del = 0
						AND discount_akcia.Moderator = 1
						AND adminview = 1
						AND discount_podpiska_view.user_wp = '.$_SESSION['WP_USER']['user_wp'].'
						AND discount_podpiska_ban.user_wp <> '.$_SESSION['WP_USER']['user_wp'].''
						.$searchtask;
				$result = mysql_query($sql);
				$json = Array();
				
				$textcolor = 'white';
				$editable = false;
				
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
						//'id' => $row['id'],
						'title' => $row['header'],
						'start' => $row['DiscData1'],
						'end' => $row['DiscData2'],
						'color' => $color,
						'textColor' => $textcolor,
						'editable' => $editable,
						'allDay' => false,
					);
					
				}

			break;
			case 'user_events':
				if(strlen($textsearch) >= 1) $searchtask = " AND `title` LIKE '%".$textsearch."%' ";
					else $searchtask = "";
				$sql = "SELECT * FROM `discount_users_events`
						WHERE `deleted` = 0 
						AND `owner_wp` = ".$_SESSION['WP_USER']['user_wp']
						.$searchtask;
				$json = Array();
				$result = mysql_query($sql);
						
				$color = '#94d11f'; 
				$textcolor = 'white';
				$editable = true;
				while ($row = mysql_fetch_assoc($result)) {
					$visible_friends = Array();
					//Если есть приватность
					if($row['visible_all'] == 0){
						//Смотрим id друзей которые могут видеть этот эвент
						$sql = "SELECT * FROM `discount_users_events_visible` WHERE `event_id` = " . $row['id'];
						$res = mysql_query($sql);
						while ($r = mysql_fetch_assoc($res)) {
							$visible_friends[] = $r['friend_wp'];
						}
					}
					$data_after = date("Y-m-d", strtotime($row['data_after']));
					if(isset($row['repeat']) && $row['repeat']!=0){ //Если повторять
						switch ($row['finish']) //Завершение
						{
						case 0: //Не завершать
							$row['finish'] = 365*2; //На 2 года
						case 2: //После 2-х раз
						case 3: //После 3-х раз
							for($i=0;$i<=$row['finish'];$i++){
								$data_start = DayAdd($row['data_start'],$i*$row['repeat']);
								$data_end = DayAdd($row['data_end'],$i*$row['repeat']);
								
								$json[] = array(
									'id' => $row['id'],
									'title' => $row['title'],
									'start' => $data_start,
									'end' => $data_end,
									'color' => $color,
									'textColor' => $textcolor,
									'editable' => $editable,
									'allDay' => false,
									
									'after' =>  $data_after,
									'repeat' => $row['repeat'],
									'finish' => $row['finish'],
									'remind' => $row['napominanie'],
									'notes'  => $row['zametki'],
									'friends' => $visible_friends,
									'place' => $row['place_id']
								);
							}
						break;
												
						case 1: //После даты
							$data_start = date("Y-m-d",strtotime($row['data_end']));
							$i=0;
							while($data_start <= $data_after){
								$data_start = DayAdd($row['data_start'],$i*$row['repeat']);
								$data_end = DayAdd($row['data_end'],$i*$row['repeat']);
								$json[] = array(
									'id' => $row['id'],
									'title' => $row['title'],
									'start' => $data_start,
									'end' => $data_end,
									'color' => $color,
									'textColor' => $textcolor,
									'editable' => $editable,
									'allDay' => false,
									
									'after' =>  $data_after,
									'repeat' => $row['repeat'],
									'finish' => $row['finish'],
									'remind' => $row['napominanie'],
									'notes'  => $row['zametki'],
									'friends' => $visible_friends,
									'place' => $row['place_id']
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
								'textColor' => $textcolor,
								'editable' => $editable,
								'allDay' => false,
								
								'after' =>  $data_after,
								'repeat' => $row['repeat'],
								'finish' => $row['finish'],
								'remind' => $row['napominanie'],
								'notes'  => $row['zametki'],
								'friends' => $visible_friends,
								'place' => $row['place_id']
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
							'textColor' => $textcolor,
							'editable' => $editable,
							'allDay' => false,
							
							'after' =>  $data_after,
							'repeat' => $row['repeat'],
							'finish' => $row['finish'],
							'remind' => $row['napominanie'],
							'notes'  => $row['zametki'],
							'friends' => $visible_friends,
							'place' => $row['place_id']
						);
					}
				}
			break;
			case 'user_friends_events':
				if(strlen($textsearch) >= 1) $searchtask = " AND `discount_users_events`.`title` LIKE '%".$textsearch."%' ";
					else $searchtask = "";
				$sql = 'SELECT DISTINCT discount_users_events.*
						FROM discount_users_events
						LEFT OUTER JOIN discount_users_friends ON discount_users_friends.friend_wp = discount_users_events.owner_wp AND discount_users_friends.good = 1
						LEFT OUTER JOIN discount_users_events_visible ON discount_users_events_visible.event_id = discount_users_events.id 
						WHERE discount_users_events.deleted = 0 
						AND (discount_users_events_visible.friend_wp = '.$_SESSION['WP_USER']['user_wp'].'
						OR (discount_users_events.visible_all = 1 AND discount_users_friends.user_wp = '.$_SESSION['WP_USER']['user_wp'].'))'
						.$searchtask;
				$json = Array();
				$result = mysql_query($sql);
				
				$color = '#469ad7'; 
				$textcolor = 'white';
				$editable = false;
				while ($row = mysql_fetch_assoc($result)) {
					$data_after = date("Y-m-d", strtotime($row['data_after']));
					if(isset($row['repeat']) && $row['repeat']!=0){ //Если повторять
						switch ($row['finish']) //Завершение
						{
						case 0: //Не завершать
							$row['finish'] = 365*2; //На 2 года
						case 2: //После 2-х раз
						case 3: //После 3-х раз
							for($i=0;$i<=$row['finish'];$i++){
								$data_start = DayAdd($row['data_start'],$i*$row['repeat']);
								$data_end = DayAdd($row['data_end'],$i*$row['repeat']);
								
								$json[] = array(
									'id' => $row['id'],
									'title' => $row['title'],
									'start' => $data_start,
									'end' => $data_end,
									'color' => $color,
									'textColor' => $textcolor,
									'editable' => $editable,
									'allDay' => false,
									
									'place' => $row['place_id']
								);
							}
						break;
												
						case 1: //После даты
							$data_start = date("Y-m-d",strtotime($row['data_end']));
							$i=0;
							while($data_start <= $data_after){
								$data_start = DayAdd($row['data_start'],$i*$row['repeat']);
								$data_end = DayAdd($row['data_end'],$i*$row['repeat']);
								$json[] = array(
									'id' => $row['id'],
									'title' => $row['title'],
									'start' => $data_start,
									'end' => $data_end,
									'color' => $color,
									'textColor' => $textcolor,
									'editable' => $editable,
									'allDay' => false,
									
									'place' => $row['place_id']
								);
								$i++;
							}
						default: 
							$json[] = array(
								'id' => $row['id'],
								'title' => $row['title'],
								'start' => $row['data_start'],
								'end' => $row['data_end'],
								'color' => $color,
								'textColor' => $textcolor,
								'editable' => $editable,
								'allDay' => false,
								
								'place' => $row['place_id']
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
							'textColor' => $textcolor,
							'editable' => $editable,
							'allDay' => false,
							
							'place' => $row['place_id']
						);
					}	
				}
			break;
			
			case 'birthday':
				if(strlen($textsearch) >= 1) $searchtask = " AND (`discount_users`.`firstname` LIKE '%".$textsearch."%' 
															OR `discount_users`.`lastname` LIKE '%".$textsearch."%') ";
					else $searchtask = "";
				$sql = 'SELECT DISTINCT discount_users.*, discount_users_friends.good
						FROM discount_users_friends
						INNER JOIN discount_users ON discount_users.user_wp = discount_users_friends.friend_wp
						WHERE discount_users_friends.user_wp ='.$_SESSION['WP_USER']['user_wp'].'
						AND discount_users_friends.good =1'
						.$searchtask;
				$result = mysql_query($sql);
				$color = '#ddd';
				$textcolor = 'black';
				$editable = false;
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
						'color' => $color,
						'textColor' => $textcolor,
						'editable' => $editable,
						'allDay' => false,
						'url' => $row['user_wp']
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

if(isset($_GET["friendlist"])){

	$sql = "SELECT DISTINCT `discount_users`.* 
			FROM `discount_users_friends` 
			INNER JOIN `discount_users` ON `discount_users`.`user_wp` = `discount_users_friends`.`friend_wp` 
			WHERE `discount_users_friends`.`user_wp` = " . $_SESSION['WP_USER']['user_wp'];
	$result = mysql_query($sql);
	$json = Array();
	while ($row = mysql_fetch_assoc($result)) {
		$json[] = array( 
			"friend_wp" => $row["user_wp"],
			"name" => $row["firstname"].' '.$row['lastname'],
			"userpic" => $row['photo'],
			"added" => false
		);		
	}

	$response = json_encode($json);
	echo $response;
}
//Места
if(isset($_GET["placelist"])){
	$sql = "SELECT  `discount_shops`.`name` ,  `discount_shops`.`id`  `shop_id` 
			FROM  `discount_users_places` 
			INNER JOIN  `discount_shops_adress` ON  `discount_shops_adress`.`id` =  `discount_users_places`.`address` 
			INNER JOIN  `discount_shops` ON  `discount_shops_adress`.`shop_id` =  `discount_shops`.`id` 
			WHERE  `discount_users_places`.`user_wp` =" . $_SESSION['WP_USER']['user_wp'];
	$result = mysql_query($sql);
	$json = Array();
	while ($row = mysql_fetch_assoc($result)) {
		$json[] = array( 
			"name" => $row["name"],
			"id" => $row["shop_id"]
		);		
	}

	$response = json_encode($json);
	echo $response;	
}

?>

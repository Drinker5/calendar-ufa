<?php
//$db = mysql_pconnect('localhost', 'root', '');
//mysql_select_db('discount',$db);
//mysql_query("SET NAMES 'utf8'");
//$_SESSION['WP_USER']['user_wp'] - id (user_wp) текущего пользователя

global $MYSQL;
$GLOBALS['PHP_FILE'] = __FILE__;
$GLOBALS['FUNCTION'] = __FUNCTION__;

$start = @$_POST['start'];
$end = @$_POST['end'];
$type = @$_POST['type'];
$op = @$_POST['op'];
$id = @$_POST['id'];

$b = 1;
$color = 'red';


$tbakcia = 'pfx_akcia';


switch ($op) {
	case 'add':
		$sql = 'INSERT INTO discount_akcia (
			DiscData1, 
			DiscData2, 
			header) 
			VALUES 
			("' . date("Y-m-d H:i:s", strtotime($start)) . '",
			"' . date("Y-m-d H:i:s", strtotime($end)) . '", 
			"' . $type . '")';
		if (mysql_query($sql)) {
			echo mysql_insert_id();
		}
		break;
	case 'edit':
		$sql = 'UPDATE discount_akcia SET 	DiscData1 = "' . date("Y-m-d H:i:s", strtotime($start)) . '",
									DiscData2	  = "' . date("Y-m-d H:i:s", strtotime($end)) . '",
									header  = "' . $type . '"
									WHERE id = "' . $id . '"';
		if (mysql_query($sql)) {
			echo $id;
		}
		break;
	case 'source':
		switch($type){
			case 'akcia':
				$sql = 'SELECT DISTINCT discount_akcia . * 
						FROM discount_akcia, discount_podpiska_view, discount_podpiska_ban
						WHERE del =0
						AND (
							discount_akcia.id = discount_podpiska_view.akcia_id
							AND discount_podpiska_view.user_wp ='.$_SESSION['WP_USER']['user_wp'].'
							)
						AND NOT (
							discount_akcia.id = discount_podpiska_ban.akcia_id
							AND discount_podpiska_ban.user_wp ='.$_SESSION['WP_USER']['user_wp'].'
							)';
				$result = mysql_query($sql);
				$json = Array();
				
				while ($row = mysql_fetch_assoc($result)) {
				//********************** цвета ***********************
					switch($row['idtype'])
					{
						case 1:	$color = '#79CDCD';break;//аква
						case 2:	$color = '#8B4513';break;//шоколад
						case 3:	$color = '#469ad7';break;//синий
						case 4:	$color = '#d3111f';break;//красный
						case 5:	$color = '#9a46d7';break;//фиолетовый
						default:$color = '#94d11f';break;//зеленый	
					}
					/*
					if($b%2==1) $color = '#94d11f'; //зелененький
					else $color = '#469ad7'; //сининький
					$b++;*/
				//****************************************************
					$json[] = array(
						'id' => $row['id'],
						'title' => $row['header'],
						'start' => $row['DiscData1'],
						'end' => $row['DiscData2'],
						'color' => $color,
						'textColor' => 'white',
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
		$sql = 'INSERT INTO discount_podpiska_ban (user_wp, akcia_id) VALUES ("' .$_SESSION['WP_USER']['user_wp']. '", "'.$id.'")';
		if (mysql_query($sql)) {
			echo $id;
		}
		break;
}

<?php
	if(isset($_POST['user_wp'])){
		$myNotify=array();

		//Новые друзья
		$newFriends=$MYSQL->query('SELECT `pfx_uvedomlenie`.`id`, `pfx_users`.`user_wp`, `pfx_users`.`firstname`, `pfx_users`.`lastname`
								   FROM `pfx_uvedomlenie`
								   INNER JOIN `pfx_users` ON `pfx_users`.`user_wp`=`pfx_uvedomlenie`.`user_wp`
								   INNER JOIN `pfx_users_deystvie` ON `pfx_users_deystvie`.`id`=`pfx_uvedomlenie`.`deystvie_id`
								   WHERE `pfx_uvedomlenie`.`user_wp`='.varr_int($_POST['user_wp']).' AND `pfx_uvedomlenie`.`tiny`=0 AND `pfx_users_deystvie`.`deystvie`=9');
		if(is_array($newFriends)){
			$oneTime=array();
			foreach($newFriends as $k=>$v){
				$avatar=ShowAvatar(array($v['user_wp']),70,70,true);
				$myNotify['friends'][]=array('fname'=>$v['firstname'],'lname'=>$v['lastname'],'photo'=>$avatar[0]['file']);
				$oneTime[]=$v['id'];
			}

			//Передаем через AJAX
			$myNotify['fCount']=count($myNotify['friends']);

			//Выводим только раз
			$MYSQL->query('UPDATE `pfx_uvedomlenie` SET `pfx_uvedomlenie`.`tiny`=1 WHERE `pfx_uvedomlenie`.`id` IN ('.implode(',',$oneTime).')');
		}

		//Новые друзья
		//$newFriends=$USER->ShowNewFriends();
		//if(is_array($newFriends)){
		//	foreach($newFriends as $k=>$v){
		//		$avatar=ShowAvatar(array($v['user_wp']),70,70,true);
		//		$myNotify['friends'][]=array('fname'=>$v['firstname'],'lname'=>$v['lastname'],'photo'=>$avatar[0]['file']);
		//	}
		//	$myNotify['fCount']=count($myNotify['friends']);
		//}

		//Выводить один раз

		//Комментарии
		//$newComments=$MYSQL->query("SELECT `pfx_users`.`user_wp`,`pfx_users`.`firstname`,`pfx_users`.`lastname`
		//                         	FROM `pfx_users_comments`
		//                         	INNER JOIN `pfx_users` ON `pfx_users`.`user_wp`=`pfx_users_comments`.`user_wp`
	    //                         	WHERE `pfx_users_comments`.`deystvie_id` IN (SELECT `pfx_users_deystvie`.`id` FROM `pfx_users_deystvie` WHERE `pfx_users_deystvie`.`user_wp`=".varr_int($_POST['user_wp'])." AND (`pfx_users_deystvie`.`data_add`> '".mysql_real_escape_string($_POST['time'])."')) AND `pfx_users_comments`.`user_wp`<>".varr_int($_POST['user_wp']));
		//if(is_array($newComments)){
		//	foreach($newFriends as $k=>$v){
		//		$avatar=ShowAvatar(array($v['user_wp']),70,70,true);
		//		$myNotify['comments'][]=array('fname'=>$v['firstname'],'lname'=>$v['lastname'],'photo'=>$avatar[0]['file']);
		//	}
		//	$myNotify['cCount']=count($myNotify['comments']);
		//}

		//Подарки

		echo json_encode($myNotify);
	}
?>
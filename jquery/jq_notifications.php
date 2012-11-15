<?php
	if(isset($_POST['user_wp'])){
		$myNotify=array();

		//Новые друзья
		$newFriends=$USER->ShowNewFriends();
		if(is_array($newFriends)){
			foreach($newFriends as $k=>$v){
				$avatar=ShowAvatar(array($v['user_wp']),70,70,true);
				$myNotify['friends'][]=array('fname'=>$v['firstname'],'lname'=>$v['lastname'],'photo'=>$avatar[0]['file']);
			}
			$myNotify['fCount']=count($myNotify['friends']);
		}

		//Комментарии
		$newComments=$MYSQL->query("SELECT `pfx_users`.`user_wp`,`pfx_users`.`firstname`,`pfx_users`.`lastname`
	                             	FROM `pfx_users_comments`
	                             	INNER JOIN `pfx_users` ON `pfx_users`.`user_wp`=`pfx_users_comments`.`user_wp`
	                             	WHERE `pfx_users_comments`.`deystvie_id` IN (SELECT `pfx_users_deystvie`.`id` FROM `pfx_users_deystvie` WHERE `pfx_users_deystvie`.`user_wp`=".varr_int($_POST['user_wp'])." AND (`pfx_users_deystvie`.`data_add`> '".mysql_real_escape_string($_POST['time'])."')) AND `pfx_users_comments`.`user_wp`<>".varr_int($_POST['user_wp']));
		if(is_array($newComments)){
			foreach($newFriends as $k=>$v){
				$avatar=ShowAvatar(array($v['user_wp']),70,70,true);
				$myNotify['comments'][]=array('fname'=>$v['firstname'],'lname'=>$v['lastname'],'photo'=>$avatar[0]['file']);
			}
			$myNotify['cCount']=count($myNotify['comments']);
		}

		//Подарки

		echo json_encode($myNotify);
	}
?>
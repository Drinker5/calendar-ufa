<?php
	$friend_wp=varr_int(@$_POST['id']);
	if($friend_wp>=10000){
		$circle             =$USER->FriendIsCircle($friend_wp);
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$friend_star        =$MYSQL->query("SELECT IFNULL(`zvezda`,0) `zvezda`, `firstname`, `lastname` FROM `pfx_users` WHERE `user_wp`=".$friend_wp);
		$friend_f           =@$friend_star[0]['lastname'];
		$friend_n           =@$friend_star[0]['firstname'];
		$friend_star        =varr_int(@$friend_star[0]['zvezda']);

		if(is_array($circle)){//Если является другом и присутствует в моих кругах
			echo "<table>
				<tr><td width=\"25\"><img src=\"pic/fr-win-gift.png\" width=\"17\" height=\"18\" /></td><td><a href=\"#\">Сделать подарок</a></td></tr>
				<tr><td width=\"25\"><img src=\"pic/fr-win-chat.png\" width=\"17\" height=\"18\" /></td><td><a href=\"#\">Начать чат</a></td></tr>
				<tr><td width=\"25\"><img src=\"pic/fr-win-del.png\" width=\"17\" height=\"18\" /></td><td><a href=\"#\">Удалить из друзей</a></td></tr>
				<tr><td colspan=\"2\" style=\"background:url('pic/fr-win-hr.png') no-repeat center center; height:15px;\"></td></tr>";

			$GLOBALS['PHP_FILE']=__FILE__;
			$GLOBALS['FUNCTION']=__FUNCTION__;
			if($friend_star==1 && $_SESSION['WP_USER']['zvezda']==1 or $friend_star!=1 && $_SESSION['WP_USER']['zvezda']!=1){
				$where=" AND `krug_id` <> 9 AND `krug_id` <> 10 ";
			}
			elseif($friend_star==1 && $_SESSION['WP_USER']['zvezda']!=1){
				$where=" AND krug_id <> 10 ";//без фанатов
			}

			$result=$MYSQL->query("SELECT `krug_id`, `name_".LANG_SITE."` `name` FROM `pfx_krugi` WHERE `krug_id` <> 1 ".$where." ORDER BY `sort`");
			if(is_array($result)){
				foreach($result as $key=>$value){
					foreach($circle as $key2=>$value2){
						if($value2['krug_id']==$value['krug_id']){$checked='checked'; break;}
						else                                      $checked='';
					}
					echo "<tr><td><input type=\"checkbox\" value=\"".$value['krug_id']."\" class=\"frnchck\" $checked /></td><td class=\"frntd\">".$value['name']."</td></tr>";
				}
			}
			echo "</table>
				<script type=\"text/javascript\">
					$(document).ready(function(){
						$('input.frnchck').change(function(){
							var circles = new Array();
							var i = 0;
							$('input.frnchck').each(function(key,input){
								if(input.checked == true)
									circles[i++] = input.value;
							});
							$.ajax({
								url:'/jquery-friendaction',
								cache:false, type:'POST',
								data: {friend:$friend_wp,circles:circles},
								success:function(){
								
								}
							});
						});
						$('input.frnchck').checkbox({cls:'jquery-safari-checkbox-mini'});
					});
				</script>";
		}

		else{//Если не является другом
			switch($USER->IsFriendAction($friend_wp)){
				case 1: // Могу пригласить
					echo "<table>
						<tr><td width=\"25\"><img src=\"pic/fr-win-friend.png\" width=\"17\" height=\"12\" /></td><td>Добавить в</td></tr>
						<tr><td colspan=\"2\" style=\"background:url('pic/fr-win-hr.png') no-repeat center center; height:15px;\"></td></tr>";
					$GLOBALS['PHP_FILE']=__FILE__;
					$GLOBALS['FUNCTION']=__FUNCTION__;
					if($friend_star==1 && $_SESSION['WP_USER']['zvezda']==1 or $friend_star!=1 && $_SESSION['WP_USER']['zvezda']!=1){
						$where=" AND krug_id <> 9 AND krug_id <> 10 ";
					}
					elseif($friend_star == 1 && $_SESSION['WP_USER']['zvezda'] != 1){
						$where = " AND krug_id <> 10 "; // без фанатов
					}

					$result=$MYSQL->query("SELECT krug_id, name_".LANG_SITE." name FROM pfx_krugi WHERE krug_id <> 1 $where ORDER BY sort");
					if(is_array($result)){
						foreach($result as $key=>$value){
							echo "<tr><td width=\"25\"><input type=\"checkbox\" value=\"".$value['krug_id']."\" class=\"frnchck\" /></td><td class=\"frntd\">".$value['name']."</td></tr>";
						}
					}
					echo "<tr><td colspan=\"2\" style=\"background:url('pic/fr-win-hr.png') no-repeat center center; height:15px;\"></td></tr>
						<tr><td width=\"25\"><img src=\"pic/fr-win-save.png\" width=\"17\" height=\"15\" /></td><td><a href=\"#\" id=\"save_new_friend\" onClick=\"return false;\">Сохранить</a></td></tr>
						</table>
						<script type=\"text/javascript\">
							$(document).ready(function(){
								$('input.frnchck').checkbox({cls:'jquery-safari-checkbox-mini'});
								$('#save_new_friend').click(function(){
									var circles = new Array();
									var i = 0;
									$('input.frnchck').each(function(key,input){
										if(input.checked == true)
											circles[i++] = input.value;
									});
									if(circles.length > 0){
										$.ajax({
											url:'/jquery-friendaction',
											cache:false, type:'POST',
											data: {friend_add:$friend_wp,circles:circles},
											success:function(result){
												if(result == 'ok'){
													alert('Приглашение отправленно пользователю $friend_n $friend_f');
													$('#frndopt').remove();
													location.href='".$_SERVER['HTTP_REFERER']."';
												}
											}
										});
									}
								});
							});
						</script>";
				break;

				case 2: // Приглашение отправленно
					echo "<p style=\"color:white\">Приглашение дружбы отправленно</p>";
				break;

				case 3: // Принять дружбу
					echo "<table>
						<tr><td width=\"25\"><img src=\"pic/fr-win-friend.png\" width=\"17\" height=\"12\" /></td><td>Добавить в</td></tr>
						<tr><td colspan=\"2\" style=\"background:url('pic/fr-win-hr.png') no-repeat center center; height:15px;\"></td></tr>";
					$GLOBALS['PHP_FILE'] = __FILE__;
					$GLOBALS['FUNCTION'] = __FUNCTION__;
					if($friend_star == 1 && $_SESSION['WP_USER']['zvezda'] == 1 or $friend_star != 1 && $_SESSION['WP_USER']['zvezda'] != 1){
						$where = " AND krug_id <> 9 AND krug_id <> 10 ";
					}
					elseif($friend_star == 1 && $_SESSION['WP_USER']['zvezda'] != 1){
						$where = " AND krug_id <> 10 "; // без фанатов
					}

					$result = $MYSQL->query("SELECT krug_id, name_".LANG_SITE." name FROM pfx_krugi WHERE krug_id <> 1 $where ORDER BY sort");
					if(is_array($result)){
						foreach($result as $key=>$value){
							echo "<tr><td width=\"25\"><input type=\"checkbox\" value=\"".$value['krug_id']."\" class=\"frnchck\" /></td><td class=\"frntd\">".$value['name']."</td></tr>";
						}
					}
					echo "<tr><td colspan=\"2\" style=\"background:url('pic/fr-win-hr.png') no-repeat center center; height:15px;\"></td></tr>
						<tr><td width=\"25\"><img src=\"pic/fr-win-save.png\" width=\"17\" height=\"15\" /></td><td><a href=\"#\" id=\"save_new_friend\" onClick=\"return false;\">Принять дружбу</a></td></tr>
						</table>
						<script type=\"text/javascript\">
							$(document).ready(function(){
								$('input.frnchck').checkbox({cls:'jquery-safari-checkbox-mini'});
								$('#save_new_friend').click(function(){
									var circles = new Array();
									var i = 0;
									$('input.frnchck').each(function(key,input){
										if(input.checked == true)
											circles[i++] = input.value;
									});
									if(circles.length > 0){
										$.ajax({
											url:'/jquery-friendaction',
											cache:false, type:'POST',
											data: {friend_ok:$friend_wp,circles:circles},
											success:function(result){
												if(result == 'ok'){
													alert('Теперь вы друзья с пользователем $friend_n $friend_f');
													$('#frndopt').remove();
													location.href='".$_SERVER['HTTP_REFERER']."';
												}
											}
										});
									}
								});
							});
						</script>";
				break;
			}
		}
	}

	//!Смена кругов для уже находящигося в друзьях пользователя
	/*
	elseif(isset($_POST['friend']) && isset($_POST['circles'])){
		if($USER->IsFriend($_POST['friend'])){
			$friend_wp=varr_int($_POST['friend']);
			$circles  =$_POST['circles'];
			$GLOBALS['PHP_FILE']=__FILE__;
			$GLOBALS['FUNCTION']=__FUNCTION__;
			$friend_id          =$MYSQL->query("SELECT `id` FROM `pfx_users_friends` WHERE `user_wp` = ".varr_int($_SESSION['WP_USER']['user_wp'])." AND `friend_wp`=".$friend_wp);

			$MYSQL->query("DELETE FROM `pfx_users_krugi` WHERE `friends_id`=".varr_int($friend_id[0]['id']));

			if(count($_POST['circles'])==0){
				$MYSQL->query("INSERT INTO `pfx_users_krugi` (`friends_id`, `krug_id`) VALUES (".varr_int($friend_id[0]['id']).",2)");
			}
			else{
				foreach($circles as $key=>$value){
					$MYSQL->query("INSERT INTO pfx_users_krugi (friends_id,krug_id) VALUES (".varr_int($friend_id[0]['id']).",".varr_int($value).")");
				}
			}
		}
	}
	*/
	elseif(isset($_POST['friend']) && isset($_POST['circle']) &&  isset($_POST['action'])){
		$MYSQL->query("DELETE FROM `pfx_users_krugi` WHERE `friends_id`=".varr_int($_POST['friend'])." AND `krug_id`=".varr_int($_POST['circle'])."");
		if($_POST['action']=='checked'){
			$MYSQL->query("INSERT INTO `pfx_users_krugi` (`friends_id`, `krug_id`) VALUES (".varr_int($_POST['friend']).", ".varr_int($_POST['circle']).")");
			echo 'added!';
		}
		else{
			echo 'deleted!';
		}
	}


	//!Приглашаем пользователя дружить
	elseif(isset($_POST['friend_add']) && $_POST['friend_add']>=10000){
		if($USER->AddFriend($_POST['friend_add'])){
			echo 'ok';
		}
	}

	//!Подтвердить дружбу
	elseif(isset($_POST['friend_ok']) && $_POST['friend_ok']>=10000){
		if($USER->PodtverditFriend($_POST['friend_ok'])){
			echo 'ok';
		}
	}

	//!Удалить пользователя из друзей
	elseif(isset($_POST['friend_del']) && $_POST['friend_del']>=10000){
		if($USER->DeleteFriend($_POST['friend_del'])){
			echo 'ok';
		}
	}
?>
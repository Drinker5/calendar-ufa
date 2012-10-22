<?php
	//Удаление данных
	//$MYSQL->query("DELETE FROM `discount_chat_mess`");
	//$MYSQL->query("DELETE FROM `discount_chat`");

	function smiles(){
		global $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		$tsmiles="pfx_chat_smiles";
		
		$array=$MYSQL->query("SELECT `smile`, `icon` FROM $tsmiles");
		if(is_array($array)){
			$smiles='';
			$count=count($array);
			for($i=0;$i<$count;$i++){
				$smiles.='<img src="/pic/smiles/'.$array[$i]['icon'].'" alt="'.$array[$i]['smile'].'" title="'.$array[$i]['smile'].'" width="16" height="16" />';
			}
			return $smiles;
		}
		else return 'no';
	}

	$tchats="pfx_chat";
	$tmess ="pfx_chat_mess";

	//unset($_SESSION['WP_USER']);
	if(!isset($_SESSION['WP_USER']['chat']['status']))$_SESSION['WP_USER']['chat']['status']=1;
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="/css/style.css" media="all" />
		<script type="text/javascript" src="/js/jquery.js"></script>
		<script type="text/javascript" src="/js/custom.js"></script>

		<link type="text/css" rel="stylesheet" href="/css/ui-lightness/jquery-ui.custom.css">
		<script type="text/javascript" src="/js/jquery-ui.min.js"></script>

		<link type="text/css" rel="stylesheet" href="/css/tipsy.css">
		<script type="text/javascript" src="/js/jquery.tipsy.js"></script>

		<script type="text/javascript" src="/js/jquery.mousewheel.js"></script>

		<link type="text/css" rel="stylesheet" href="/css/jquery.jscrollpane.css" media="all" />
		<script type="text/javascript" src="/js/jquery.jscrollpane.min.js"></script>

		<script type="text/javascript" src="/js/jquery.popupmenu.js"></script>

		<link type="text/css" rel="stylesheet" href="css/jquery.safari-checkbox.css">
		<script type="text/javascript" src="js/jquery.checkbox.min.js"></script>

		<!--Стиль чата. Потом правила перенести и удалить файл--> 
		<link type="text/css" rel="stylesheet" href="/jquery/chat/gbn-chat.css" media="all" />
		<script type="text/javascript" src="/jquery/chat/gbn-chat.js"></script>

		<title>Чат</title>
	</head>
	<body>
		<table style="padding-left:260px;"><tr><td><pre><?//=print_r($_SESSION)?></pre></td><td><pre><?//=print_r($_COOKIE)?></pre></td><td><pre><?//=print_r($USER->ShowMyFriends($_SESSION['WP_USER']['user_wp'],5,'fio_up'))?></pre></td><td><pre><?//=print_r($MYSQL->query("SELECT * FROM `discount_chat`"))?></pre></td><td><pre><?=print_r($MYSQL->query("SELECT `chat` FROM $tmess WHERE `read`=0 AND `user`!='".$_SESSION["WP_USER"]["user_wp"]."' GROUP BY `chat` ORDER BY `chat` ASC"))?></pre></td></tr></table>

		<!--Блок с чатом-->
		<div id="online-wrap" class="group">

			<!--Панель чата в свернутом виде-->
			<div id="online-bar">
				<div id="online-bar-status">
					<img src="/pic/online-bar-my-<?=$_SESSION['WP_USER']['chat']['status']?>.png" alt="" width="29" height="25" />
					<div id="show-dialog"></div>
				</div>
				<div id="online-bar-people">
					<div class="online-man online-man-cur" rel="ab">
						<img src="/pic/chat-abook.png" width="28" height="28" original-title="Список контактов" />
					</div>
				</div>

				<div id="online-bar-settings">
					<div><a href="#"><img src="/pic/online-bar-settings-gift.png" alt="" width="18" height="19" style="margin-top:10px;" /></a></div>
					<div id="magnifier"><img src="/pic/online-bar-settings-search.png" alt="" width="18" height="19" /></div>
					<div><img src="/pic/online-bar-settings-gear.png" alt="" width="18" height="19" id="chat-settings" /></div>
				</div>
			</div><!--Конец панели чата в свернутом виде-->

			<!--Дополнительный блок при развороте панели чата-->
			<div id="online-bar-max" class="group">
				<div id="online-bar-max-status" class="group">
					<p>Список<br>контактов</p>
					<div id="online-bar-max-right"></div>
					<a href="#" id="close-dialog" title="Закрыть"></a>
					<div id="conference-wrap">
						<a href="#" id="online-conference" title="Конференция"></a>
					</div>
				</div>
				<div id="online-bar-max-people" class="group">
					<div id="chat-contacts-list">
						<div>
<?php
						$friends=$USER->ShowMyFriends($_SESSION['WP_USER']['user_wp'],100,'fio_up');
						$count=count($friends);
						for($i=0;$i<$count;$i++){
							$arr_users[] = $friends[$i]['user_wp'];
						}
						$avatar=ShowAvatar($arr_users,28,28);
						for($i=0;$i<$count;$i++){
							$circle=$USER->FriendIsCircle($friends[$i]['user_wp']);
							$ccount=count($circle);
							$cname='';
							for($c=0;$c<$ccount;$c++){
								$cname.=$circle[$c]['name'];
								if($c+1<$ccount)$cname.=', ';
							}
							echo '<table class="chat-msg"><tr rel="'.$friends[$i]['user_wp'].'"><td class="avatar"><img src="'.$avatar[$i]['avatar'].'" width="28" height="28" /></td><td class="msg"><b>'.$friends[$i]['firstname'].' '.$friends[$i]['lastname'].'</b><br />'.$cname.'</td></tr></table>';
							if($i+1<$count)echo '<p class="chat-hr"><img src="pic/chat-hr.png"></p>';
						}
						$photo=ShowAvatar(array($_SESSION['WP_USER']['user_wp']),28,28);
						if(is_array($photo))$photo=$photo[0]['avatar'];
						//echo '<pre>'; print_r($friends); echo '</pre>';
?>
						</div>
					</div><!--End of chat-contacts-list-->
					<div id="chat-insert-block">
						<div id="chat-messages"></div>
						<div class="container">
							<input type="hidden" value="<?=$photo?>" id="myava" />
							<input type="hidden" value="<?=$_SESSION['WP_USER']['user_wp']?>" id="mywp" />
							<input type="text" class="brdrd" id="sended-text" />
						</div>
						<table id="chat-actions">
							<tr>
								<td>
									<label>
										<input name="press-enter" type="checkbox" class="safari" id="press-enter" checked="checked" />
										<img src="/pic/press-enter.png">
									</label>
								</td>
								<td style="padding-top: 4px;">
									<div id="smiles">
										<img src="/pic/smiles-icon.png">
										<div id="smiles-window">
											<div id="smiles-top"></div>
											<div id="smiles-mid" class="group">
												<?=smiles()?>
											</div>
											<div id="smiles-bottom"></div>
										</div>
									</div>
								</td>
								<td>
									<a href="#" class="chat-send-btn"><sub></sub><span>Отправить</span><sup></sup></a>
								</td>
							</tr>
						</table>
					</div><!--End of chat-insert-block-->
					<div id="online-bar-mid-right"></div>
				</div>
				<div id="online-bar-max-settings">
					<div id="makeagift-text">Сделать подарок</div>
					<div id="makeagift-div"><input type="text" placeholder="Введите имя друга..." id="makeagift-field" /></div>
					<div id="online-bar-max-settings-right"></div>
				</div>
				<div class="ui-icon"></div>
			</div><!--Конец дополнительного блока при развороте чата-->

		</div><!--Конец блока с чатом-->
	</body>
</html>
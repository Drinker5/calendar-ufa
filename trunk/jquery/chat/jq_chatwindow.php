<?php
/*-----isChat-----*/
	//Проверка наличия чата, если есть,.то возвращает его id, если нет, то возвращает 'no'
	function isChat($parties){
		global $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		$tchats="pfx_chat";

		$ischat=$MYSQL->query("SELECT `id` FROM $tchats WHERE `parties`='$parties'");
		if(is_array($ischat))return $ischat;
		else return 'no';
	}

/*-----commentsReturn-----*/
	//Вывод комментариев
	function commentsReturn($chat,$user,$new=''){
		global $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		$tmess ="pfx_chat_mess";

		$mess=$MYSQL->query("SELECT `user`, `mess` FROM $tmess WHERE `chat`='".$chat."'".$new." ORDER BY `id` ASC");
		$count=count($mess);
		for($i=0;$i<$count;$i++){
			$photo=ShowAvatar(array($mess[$i]['user']),28,28);
			if(is_array($photo))$photo=$photo[0]['avatar'];
			if($i==0 and $new=='')echo '';
			else echo '<p class="chat-hr"><img src="pic/chat-hr.png"></p>';
			echo '<table class="chat-msg"><tr><td class="avatar"><img src="'.$photo.'" width="28" height="28"></td><td class="msg">'.ShowSmile($mess[$i]['mess']).'</td></tr></table>';
		}
		$MYSQL->query("UPDATE $tmess SET `read`=1, `rtime`='".date("Y-m-d H:i:s")."' WHERE `chat`='".$chat."' AND `user`!='".$user."'");
	}

	$tmess ="pfx_chat_mess";
	$tchats="pfx_chat";

	//Добавление сообщений
	if($_POST['type']=='newmess'){
		$_POST["mess"]=ShowSmile($_POST["mess"]);
		$MYSQL->query("INSERT INTO $tmess (`chat`, `user`, `mess`, `time`) VALUES('".$_POST["chat"]."', '".$_POST["user"]."', '".$_POST["mess"]."', '".date("Y-m-d H:i:s")."')");
		$MYSQL->query("UPDATE $tchats SET `lastmess`='".date("Y-m-d H:i:s")."' WHERE `id`='".$_POST["chat"]."'");
		if($_POST['first']>0)echo '<p class="chat-hr"><img src="pic/chat-hr.png"></p>';
		echo '<table class="chat-msg"><tr><td class="avatar"><img src="'.$_POST["avatar"].'" width="28" height="28"></td><td class="msg">'.$_POST["mess"].'</td></tr></table>';
	}

	//Обновление комментариев
	elseif($_POST['type']=='reload')commentsReturn($_POST["chat"],$_POST["user"]," AND `read`=0 AND `user`!='".$_POST["user"]."'");

	//Вывод списка комментариев
	else{
		$array=array($_SESSION['WP_USER']['user_wp'],$_POST['id']);//Массив с id юзеров, выбранных для чата
		sort($array);//Сортировка массива
		$count=count($array);//Количество элементов массива с участниками чата
		$parties='';//Объявляем строковую переменную с перечислением всех участников чата
		foreach($array as $i => $value){
			$parties.=$value;
			if($i+1<$count)$parties.='|';
		}
		$parties='|'.$parties.'|';
		$ischat=isChat($parties);

		echo '<div>';
		if($ischat=='no'){
			$result=$MYSQL->query("INSERT INTO $tchats (`group`, `parties`) VALUES('0', '".$parties."')");
			echo '<input type="hidden" id="chatid" value="'.$result.'" />';
		}
		else{
			echo '<input type="hidden" id="chatid" value="'.$ischat[0]['id'].'" />';
			commentsReturn($ischat[0]["id"],$_SESSION['WP_USER']['user_wp']);
		}
		echo '</div>';
	}
?>
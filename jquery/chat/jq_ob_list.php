<?php
	$tchats="pfx_chat";
	$tmess ="pfx_chat_mess";

//Вывод списка чатов с новыми сообщениями
	if($_POST['type']=='reload'){
		$newmess=$MYSQL->query("SELECT `chat` FROM $tmess WHERE `read`=0 AND `user`!='".$_SESSION["WP_USER"]["user_wp"]."' GROUP BY `chat` ORDER BY `chat` ASC");
		if(is_array($newmess)){
			$count=count($newmess);
			echo '{';
			for($i=0;$i<$count;$i++){
				echo '"chat": "'.$newmess[$i]['chat'].'"';
				if($i+1<$count)echo ', ';
			}
			echo '}';
		}
	}

//Вывод списка последних чатов
	else{
		$chats=$MYSQL->query("SELECT * FROM $tchats WHERE `parties` LIKE '%|".$_SESSION["WP_USER"]["user_wp"]."|%' AND `lastmess`>'".date("Y-m-d H:i:s", mktime(0, 0, 0, date("n"), date("j")-4, date("Y")))."'");
		if(is_array($chats)){
			$count=count($chats);
			for($i=0;$i<$count;$i++){
				$newmess=$MYSQL->query("SELECT COUNT(*) FROM $tmess WHERE `read`=0 AND `chat`='".$chats[$i]['id']."' AND `user`!='".$_SESSION["WP_USER"]["user_wp"]."'");
				if($newmess[0]['count']==0)$newmess='';
				elseif($newmess[0]['count']>9)$newmess='<sup>+9</sup>';
				else $newmess='<sup>'.$newmess[0]['count'].'</sup>';
				$chats[$i]['parties']=substr(substr(str_replace('|'.$_SESSION["WP_USER"]["user_wp"],'',$chats[$i]['parties']),1),0,-1);
				if(strpos($chats[$i]['parties'],'|')==false){
					$info=$USER->Info($chats[$i]['parties'],28,28);
					if(is_array($info)){
						$src=$info['photo'];
						$name=$info['firstname'].' '.$info['lastname'];
					}
				}
				else $src='qwe.jpg';
				echo '<div class="online-man" rel="'.$chats[$i]['parties'].'"><span></span><img src="'.$src.'" original-title="'.$name.'" width="28" height="28">'.$newmess.'</div>';
			}
		}
	}
?>
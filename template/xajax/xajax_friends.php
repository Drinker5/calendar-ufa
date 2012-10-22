<?php

function ShowMyFriends($rows){
	global $USER;
	
	$newfriend = $USER->ShowNewFriends();
	$return = "";
	
	if(is_array($newfriend)){
		    
		for($i=0; $i <  count($newfriend); $i++){
			$arr_users[] = $newfriend[$i]['user_wp'];
		}
		$avatar = ShowAvatar($arr_users,80,80);
		    
		for($i=0; $i <  count($newfriend); $i++){
			if($USER->OnLine($newfriend[$i]['user_wp'])){
				$link_chat = "Начать чат";
			    $ico_online = "<div class=\"ic_online_list_my_friends\"></div>";
			} else {
				$ico_online="";
				$link_chat = "Уведомление в чате";
			}
			$return .= "
			 <div class=\"friendslist\">
			   <div class=\"friendimg\">$ico_online<a href=\"".$newfriend[$i]['user_wp']."\"><img src=\"".$avatar[$i]['avatar']."\" width=\"80\" height=\"80\" /></a></div>
               <div class=\"friendname\">".trim($newfriend[$i]['lastname']." ".$newfriend[$i]['firstname']." ".$newfriend[$i]['otchestvo'])."</div>
               <div class=\"friendbuttons\">
			     ".ShowBtnAddFriend($newfriend[$i]['user_wp'])."
			     <div class=\"roundedbutton greenbutton\" onClick=\"javascript:chatWith('".$newfriend[$i]['user_wp']."','".trim($newfriend[$i]['firstname']." ".$newfriend[$i]['lastname'])."','".$avatar[$i]['avatar']."')\"><sub></sub><div>$link_chat</div><sup></sup></div>
		       </div>
	         </div>
			";
		}
	}
	
	unset($arr_users);
	$friends = $USER->ShowMyFriends(0,$rows,'desc');
	
	if(is_array($friends)){
		unset($arr_users);
		for($i=0; $i <  count($friends); $i++){
			$arr_users[] = $friends[$i]['user_wp'];
		}
		$avatar = ShowAvatar($arr_users,80,80);
		    
		for($i=0; $i <  count($friends); $i++){
			if($USER->OnLine($friends[$i]['user_wp'])){
				$link_chat = "Начать чат";
			    $ico_online = "<div class=\"ic_online_list_my_friends\"></div>";
			} else {
				$ico_online="";
				$link_chat = "Уведомление в чате";
			}
			
			$return .= "
			 <div class=\"friendslist\">
			   <div class=\"friendimg\">$ico_online<a href=\"".$friends[$i]['user_wp']."\"><img src=\"".$avatar[$i]['avatar']."\" width=\"80\" height=\"80\" /></a></div>
               <div class=\"friendname\">".trim($friends[$i]['lastname']." ".$friends[$i]['firstname']." ".$friends[$i]['otchestvo'])."</div>
               <div class=\"friendbuttons\">
			     ".ShowBtnAddFriend($friends[$i]['user_wp'])."
			     <div class=\"roundedbutton greenbutton\" onClick=\"javascript:chatWith('".$friends[$i]['user_wp']."','".trim($friends[$i]['firstname']." ".$friends[$i]['lastname'])."','".$avatar[$i]['avatar']."')\"><sub></sub><div>$link_chat</div><sup></sup></div>
		       </div>
	         </div>
			";
		}
		
        $f_page = f_Pages($USER->CountFriens(),page(),"/my-friends.php?page=",$rows);
        if(strlen($f_page) > 5)
         $return .= "<div class=\"navwrap\"><div class=\"navigation\">".$f_page."</div></div>";
	}

	$objResponse = new xajaxResponse();
	$objResponse->assign('ListMyFriends', 'innerHTML', $return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowMyFriends');


function AddFriend($friend_wp,$link=0){
	global $USER;
	
	$objResponse = new xajaxResponse();
	if($USER->AddFriend($friend_wp) > 0){
	 $objResponse->assign('idBtnFriend_'.$friend_wp, 'innerHTML', ShowBtnAddFriend($friend_wp,$link));
	 $objResponse->alert("Приглашение дружбы отправлено пользователю");
	} else 
	$objResponse->alert("Ошибка добавления\nВозможно Вы его уже добавили в свой список друзей");	
	
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'AddFriend');


function PodtverditFriend($friend_wp,$link=0,$refferer=0){
	global $USER;
	
	$objResponse = new xajaxResponse();
	if($USER->PodtverditFriend($friend_wp) > 0){
	 if($refferer == 1)
	  $objResponse->script("location.href='/".(int)$_SESSION['WP_USER']['user_wp']."'");
	 else
	  $objResponse->assign('idBtnFriend_'.$friend_wp, 'innerHTML', ShowBtnAddFriend($friend_wp,$link));
	} else 
	$objResponse->alert("Ошибка добавления");
	
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'PodtverditFriend');


function DeleteFriend($friend_wp,$link=0){
	global $USER;
		
	$USER->DeleteFriend($friend_wp);
	$objResponse = new xajaxResponse();
	$objResponse->assign('idBtnFriend_'.$friend_wp, 'innerHTML', ShowBtnAddFriend($friend_wp,$link));
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'DeleteFriend');


function ShowObshieFriends($user_wp){
	global $USER;
	
	$result = $USER->ShowObshieFriends($user_wp,100);
	if(is_array($result)){
		$return = "<div class=\"block\"><p class=\"header\">Общие друзья&nbsp;&nbsp;&nbsp;</p><ul style=\"width:99%\">";
		
		for($i=0; $i < count($result); $i++){
			$arr_users[] = $result[$i]['user_wp'];
		}
		$avatar = ShowAvatar($arr_users,60,60);
		
		for($i=0; $i < count($result); $i++){
			if($USER->OnLine($result[$i]['user_wp'])) $ico_online = "<div class=\"ic_online_visitor\"></div>"; else $ico_online="";
			$return .= "<li style=\"float:left; width:20%; border-bottom:none;\">
			             <div class=\"imgfriend\">
			               $ico_online<a class=\"personPopupTrigger\" rel=\"".$result[$i]['user_wp']."\" href=\"/".$result[$i]['user_wp']."\"><img width=\"60\" height=\"60\" src=\"".$avatar[$i]['avatar']."\"><br />".trim($result[$i]['firstname']."<br />".$result[$i]['lastname'])."</a>
			             </div>
			            </li>";
		}
		$return .= "</ul></div>";
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('idLenta', 'innerHTML', $return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowObshieFriends');


function ShowMyAllFriends($user_wp){
	global $USER, $varr;
		
	$result = $USER->ShowMyFriends($user_wp,100,'fio_up',@$varr['online']);
	if(is_array($result)){
		if(isset($varr['online']) && $varr['online'] == 1) $online = " в онлайне"; else $online = "";
		$return = "<div class=\"block\"><p class=\"header\">Друзья$online&nbsp;&nbsp;&nbsp;</p><ul style=\"width:99%\">";
		
		for($i=0; $i < count($result); $i++){
			$arr_users[] = $result[$i]['user_wp'];
		}
		$avatar = ShowAvatar($arr_users,60,60);
		
		for($i=0; $i < count($result); $i++){
			if($USER->OnLine($result[$i]['user_wp'])) $ico_online = "<div class=\"ic_online_visitor\"></div>"; else $ico_online="";
			$return .= "<li style=\"float:left; width:20%; border-bottom:none;\">
			             <div class=\"imgfriend\">
			               $ico_online<a class=\"personPopupTrigger\" rel=\"".$result[$i]['user_wp']."\" href=\"/".$result[$i]['user_wp']."\"><img width=\"60\" height=\"60\" src=\"".$avatar[$i]['avatar']."\"><br />".trim($result[$i]['firstname']."<br />".$result[$i]['lastname'])."</a>
			             </div>
			            </li>";
		}
		$return .= "</ul></div>";
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('idLenta', 'innerHTML', $return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowMyAllFriends');


function ShowVisitors(){
	global $USER;
	
	$result = $USER->ShowVisitors();
	$return = "";
	
	if(is_array($result)){
		$j=0;
		
		for($i=0; $i < count($result); $i++){
			$arr_users[] = $result[$i]['user_wp'];
		}
		$avatar = ShowAvatar($arr_users,60,60);
		
		for($i = 0; $i < count($result); $i++){
			
			$value = $result[$i];
			
			if($j == 0){
				$date_prev = MyDataTime($value['data_visitor'],'date');
			    if($date_prev == MyDataTime(@date(),'date')) $now = " <font style=\"color:red\">сегодня</font> "; else $now = $date_prev;
			    $return .= "<div class=\"block\"><p class=\"header\">Мои гости за $now&nbsp;&nbsp;&nbsp;</p><ul style=\"width:99%\">";
		    }
		    
		    $j++;
			
			if($value['view'] == 0)
			 $time = "<b style=\"color:red\">".MyDataTime($value['data_visitor'],'time')."</b>";
			else 
			 $time = MyDataTime($value['data_visitor'],'time');
			
			if($USER->OnLine($value['user_wp'])) $online = "<div class=\"ic_online_visitor\"></div>"; else $online = "";
			 
			$return .= "<li style=\"float:left; width:20%; border-bottom:none;\">
			             <div class=\"imgfriend\">
			               $online<a class=\"personPopupTrigger\" rel=\"".$value['user_wp']."\" href=\"/".$value['user_wp']."\"><img width=\"60\" height=\"60\" src=\"".$avatar[$i]['avatar']."\"><br />".trim($value['firstname']."<br />".$value['lastname'])."</a><br />$time
			             </div>
			            </li>";
			
			if(strtotime($date_prev) > strtotime(MyDataTime(@$result[$i+1]['data_visitor'],'date'))){
		    	$return .= "</ul></div>";
		    	$j=0;
		    }
		}
		$return .= "</ul></div>";
	}
	
	
	$objResponse = new xajaxResponse();
	$objResponse->assign('idLenta', 'innerHTML', $return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowVisitors');
?>
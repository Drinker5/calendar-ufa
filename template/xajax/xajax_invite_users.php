<?php

function InviteMobiles($mobiles){
	global $USER;
	
	$objResponse = new xajaxResponse();
	$return = "<table style=\"width:100%\">";
	
	if(is_array($mobiles))
	 $i=1;
	 foreach($mobiles as $key=>$mobile){
	   $result = $USER->InvitationMobile($mobile);
	   switch($result){
		case  1: $return .= "<tr><td style=\"color:blue\">$i</td><td><b>$mobile</b></td><td><font style=\"color:green\">приглашение отправленно</font></td></tr>"; break; // OK
		case -1: $return .= "<tr><td style=\"color:blue\">$i</td><td><b>$mobile</b></td><td><font style=\"color:red\">пользователь уже приглашен</font></td></tr>"; break; // уже пригласили
		case -2: $return .= "<tr><td style=\"color:blue\">$i</td><td><b>$mobile</b></td><td><font style=\"color:red\">номер указан неверно</font></td></tr>"; break; // Не верный мобильник
		case -3: $return .= "<tr><td style=\"color:blue\">$i</td><td><b>$mobile</b></td><td><font style=\"color:red\">не получилось отправить SMS</font></td></tr>"; break; // не смог отправить sms
		case -4: $return .= "<tr><td style=\"color:blue\">$i</td><td><b>$mobile</b></td><td><font style=\"color:red\">ошибка дарения подарка</font></td></tr>"; break; // не смог подарить подарок
		case -5: $return .= "<tr><td style=\"color:blue\">$i</td><td><b>$mobile</b></td><td><font style=\"color:red\">подарок отсутствует</font></td></tr>"; break; // нет такого подарка
		default:
			if(is_array($result)){
				$return .= "<tr><td style=\"color:blue\">$i</td><td align=\"center\"><a href=\"/".$result['user_wp']."\"><img src=\"".$result['avatar']."\" width=\"70\" height=\"70\"></a><br/><b>$mobile</b></td><td><font style=\"color:red\">пользователь ".$result['firstname']." ".$result['lastname']." уже зарегистрирован</font></td></tr>"; break; // пользователь уже зарегистрирован
			}
		break;
	   }
	   $i++;
	 }
	$return .= "</table>";
	$objResponse->assign('idShowMobiles','innerHTML',$return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'InviteMobiles');


function InviteEmails($emails,$text){
	global $USER;
	
	$objResponse = new xajaxResponse();
	$return = "<table style=\"width:100%\">";
	
	if(is_array($emails))
	 $i=1;
	 foreach($emails as $key=>$email){
	   $result = $USER->InvitationEmails($email,$text);
	   switch($result){
		case  1: $return .= "<tr><td style=\"color:blue\">$i</td><td><b>$email</b></td><td><font style=\"color:green\">приглашение отправленно</font></td></tr>"; break; // OK
		case -2: $return .= "<tr><td style=\"color:blue\">$i</td><td><b>$email</b></td><td><font style=\"color:red\">Email указан неверно</font></td></tr>"; break; // Не верный Email
		case -3: $return .= "<tr><td style=\"color:blue\">$i</td><td><b>$email</b></td><td><font style=\"color:yellow\">пользователь приглашен, но письмо отправить не удалось</font></td></tr>"; break; // OK
		case -4: $objResponse->alert("Напишите текст сообщения для приглашения друзей"); return $objResponse; break;
		case -5: $return .= "<tr><td style=\"color:blue\">$i</td><td><b>$mobile</b></td><td><font style=\"color:red\">ошибка выполнения скрипта</font></td></tr>"; break; // не смог вставить в БД нового пользователя
		case -6: $objResponse->alert("Ваша сессия истекла"); $objResponse->script("location.href='/'"); return $objResponse; break;
		default:
			if(is_array($result)){
				$return .= "<tr><td style=\"color:blue\">$i</td><td align=\"center\"><a href=\"/".$result['user_wp']."\"><img src=\"".$result['avatar']."\" width=\"70\" height=\"70\"></a><br/><b>$email</b></td><td><font style=\"color:red\">пользователь ".$result['firstname']." ".$result['lastname']." уже зарегистрирован</font></td></tr>"; break; // пользователь уже зарегистрирован
			}
		break;
	   }
	   $i++;
	 }
	$return .= "</table>";
	
	$objResponse->assign('idShowEmails','innerHTML',$return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'InviteEmails');
?>
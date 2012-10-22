<?php

function EnterGiftCode($giftcode){
	global $MYSQL, $USER;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$objResponse = new xajaxResponse();
	
	$return = "<div class=\"roundedbutton submit-greybutton\"><sub></sub><input type=\"submit\" value=\"".LANG_BTN_ENTER."\" onClick=\"xajax_EnterGiftCode($('#giftcode').val()); $('#idBtnGiftCode').html('".loading_small_progress."'); return false;\"><sup></sup></div>";
	
	$giftcode = mysql_real_escape_string(strip_tags(trim($giftcode)));
	
	if(strlen($giftcode) >= 6){
		$result = $MYSQL->query("SELECT from_wp, IFNULL(to_wp,0) to_wp, to_mobile FROM pfx_historypay WHERE code='$giftcode' AND statuspay_id=3 AND IFNULL(vidan,0) = 0");
		if(is_array($result)){
			if($result[0]['to_wp'] == 0){
				$session = md5($giftcode."::".date("d.m.Y")."::".$MYSQL->getmicrotime()); // Генерим временную сессию
			    $user_wp = $MYSQL->query("INSERT INTO pfx_users (datareg,mobile,session) VALUES (now(),'".$result[0]['to_mobile']."','$session')");
			    if($user_wp > 0){
			    	$MYSQL->query("INSERT INTO pfx_users_mobile (user_wp,mobile,main) VALUES ($user_wp,'".$result[0]['to_mobile']."',1)");
			    	$MYSQL->query("UPDATE pfx_historypay SET to_wp=$user_wp WHERE to_mobile='".$result[0]['to_mobile']."'");
			    	$MYSQL->query("INSERT INTO pfx_users_friends (data_add,user_wp,friend_wp,good,invitation) VALUES (now(),".(int)$result[0]['from_wp'].",$user_wp,0,1)");
			    	$USER->WPin($session);
			    	$objResponse->script("location.href='/my-profile.php'");
			    } else {
			    	$objResponse->assign('idBtnGiftCode', 'innerHTML', $return);
				    $objResponse->alert("Ошибка добавления нового пользователя");
			    }
			} else {
				$objResponse->assign('idBtnGiftCode', 'innerHTML', $return);
				$objResponse->alert("Вы уже зарегистрированы у нас\nВыполните вход");
			}
		} else {
			$objResponse->assign('idBtnGiftCode', 'innerHTML', $return);
			$objResponse->alert("Код подарка указан не верно");
		}
	} else {
		$objResponse->assign('idBtnGiftCode', 'innerHTML', $return);
		$objResponse->alert(LANG_ENTER_CODE_GIFT);
	}
		
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'EnterGiftCode');
?>
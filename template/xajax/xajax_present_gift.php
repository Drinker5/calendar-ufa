<?php

function PayGift($akcia_id=array(),$users_wp=array(),$msg="",$pin="",$privat=0){
	global $MYSQL, $AKCIA, $USER, $PAYMENT, $mob_info;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$error = 0;
	$objResponse = new xajaxResponse();
	if(!isset($_SESSION['WP_USER']['user_wp'])) return $objResponse;
	
	/*$akcia_id = array('akcia_id'=>1,'kolvo'=>1);*/
	if(is_array($akcia_id)){
		if($akcia_id['kolvo'] <= 0) $akcia_id['kolvo'] = 1;
		$akcia = $AKCIA->Info_min($akcia_id['akcia_id'],160,104);	
		if(is_array($akcia)){
			$summa = $akcia['amount'] * $akcia_id['kolvo'];
			if($akcia['kolvo'] != 0){
			    if($akcia_id['kolvo']*count($users_wp) > $akcia['kolvo']){
			    	 $error = 3;
			    	 break;
			    }
			}
		} else $error = 2;
	} else $error = 1;
		
	
	/*$users_wp[0] = array('user_wp'=>1,'user_email'=>'','user_mobile'=>'');*/	
	if($error == 0 && is_array($users_wp)){
		if(isset($users_wp[0]['user_wp']) && $users_wp[0]['user_wp'] > 0){ // Если это выбрали пользователя
		    for($i=0; $i < count($users_wp); $i++){
		    	$user = $USER->Info_min($users_wp[$i]['user_wp'],0,0);
		    	if(!is_array($user)){$error = 4; break;}
		    	$users_wp[$i]['info'] = $user;
		    	for($j=0; $j < $akcia_id['kolvo']; $j++){ // Добавляем каждому пользователю подарки по кол-ву подареных
		    		$users_wp[$i]['gift_code'][] = GeneralCodePodarok();
		    		$GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
			        $orderId[] = $MYSQL->query("INSERT INTO pfx_historypay (data,code,klient_id,shop_id,akcia_id,type_id,from_wp,to_wp,from_mobile,to_mobile,podarok,amount,currency_id,statuspay_id,msg,town_id,privat) VALUES (now(),'".$users_wp[$i]['gift_code'][$j]."',".$akcia['klient_id'].",".$akcia['shop_id'].",".$akcia['id'].",".$akcia['type_id'].",".(int)$_SESSION['WP_USER']['user_wp'].",".$user['user_wp'].",'".$_SESSION['WP_USER']['mobile']."','".$user['mobile']."','".serialize($akcia)."',".$akcia['amount'].",".$akcia['currency_id'].",1,'".mysql_real_escape_string(strip_tags($msg))."',".$_SESSION['TOWN_ID'].",$privat)");
		    	}
		    }
		    if($error == 0 && is_array($orderId)){
		    	$summa = $summa*count($users_wp);
		    	$result_pay = $PAYMENT->PayGift((int)$_SESSION['WP_USER']['user_wp'],$summa,$akcia['currency_id'],$pin);
		    	if(is_array($result_pay) && $result_pay['Error']['ErrorId'] === '0'){
		    		if($akcia['kolvo'] != 0){
		    			$akcia_id['kolvo'] = (int) $akcia_id['kolvo']*count($users_wp);
		    		    $MYSQL->query("UPDATE pfx_akcia SET kolvo=kolvo-".$akcia_id['kolvo']." WHERE id = ".$akcia_id['akcia_id']);
		    		}
		    		
		    		require_once(path_modules."ini.sms.php");
		    		
		    		for($i=0; $i < count($orderId); $i++){
		    		    $MYSQL->query("UPDATE pfx_historypay SET statuspay_id=3 WHERE id = ".(int)$orderId[$i]);
		    		    $MYSQL->query("INSERT INTO pfx_users_deystvie (data_add,user_wp,deystvie,id_deystvie,privat) VALUES (now(),".(int)$_SESSION['WP_USER']['user_wp'].",2,".$orderId[$i].",$privat)");
		    		    $PAYMENT->IsInvitation((int)$_SESSION['WP_USER']['user_wp'],$users_wp[$i]['info']['user_wp'],$orderId[$i]);
		    		}
		    		
		    		for($i=0; $i < count($users_wp); $i++){
		    		    switch($akcia['type_id']){
		    			  case 5: // подарок
		    			  case 6: // сертификат
		    			    if(is_mobile($users_wp[$i]['info']['mobile'])){
		    			    	$sms_code_gift = "";
		    			    	foreach($users_wp[$i]['gift_code'] as $key=>$value)
		    			    	   $sms_code_gift .= $value."\n";
		    			    	   
		    			    	if($_SESSION['WP_USER']['sex'] == 1) $end = ""; else $end = "а";
		    			    	
		    			    	$sms_msg = trim($_SESSION['WP_USER']['firstname'] ." ".$_SESSION['WP_USER']['lastname'])." сделал$end Вам подарок в ".$akcia['shop_name']." ".$akcia['country']."\nКод подарка: ".$sms_code_gift."Код действителен до ".MyDataTime(date("d.m.y"),'date2','+',day_podarok)."\nТел.:".$akcia['phone'];
		    			        $SMS->SendSMS($users_wp[$i]['info']['mobile'],$sms_msg);
		    			    }
		    			    
		    				$return = "<div class=\"firminfo\"><h1>4. Подарок подарен</h1><p>Оплата прошла успешно<br />Код подарка отправлен <b>получателю(ям)</b></p></div>";
		    			  break;
		    		    }
		    		}
		    		
		    		$objResponse->assign('step3', 'innerHTML', $return);
		    	} else $error=13;
		    } else $error=12;
		}
		/**************************************************************************************************************************************************************************************/
		elseif(isset($users_wp[0]['user_email'])){ // Если это указали email
			if(is_email($users_wp[0]['user_email'])){
				$user = $USER->Info_min($users_wp[0]['user_email'],0,0);
				if(!is_array($user)){
					$session = md5($users_wp[0]['user_email']."::".date("d.m.Y")."::".$MYSQL->getmicrotime()); // Генерим временную сессию
			        $result  = $MYSQL->query("INSERT INTO pfx_users (datareg,email,session) VALUES (now(),'".$users_wp[0]['user_email']."','$session')");
			        $user = $USER->Info_min($result,0,0);
				}
				
				if(is_array($user)){
				  $users_wp[0]['info'] = $user;
		    	  for($j=0; $j < $akcia_id['kolvo']; $j++){ // Добавляем каждому пользователю подарки по кол-ву подареных
		    		  $users_wp[0]['gift_code'][] = GeneralCodePodarok();
		    		  $GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
			          $orderId[] = $MYSQL->query("INSERT INTO pfx_historypay (data,code,klient_id,shop_id,akcia_id,type_id,from_wp,to_wp,from_mobile,to_mobile,podarok,amount,currency_id,statuspay_id,msg,town_id,privat) VALUES (now(),'".$users_wp[0]['gift_code'][$j]."',".$akcia['klient_id'].",".$akcia['shop_id'].",".$akcia['id'].",".$akcia['type_id'].",".(int)$_SESSION['WP_USER']['user_wp'].",".$users_wp[0]['info']['user_wp'].",'".$_SESSION['WP_USER']['mobile']."','".$users_wp[0]['info']['mobile']."','".serialize($akcia)."',".$akcia['amount'].",".$akcia['currency_id'].",1,'".mysql_real_escape_string(strip_tags($msg))."',".$_SESSION['TOWN_ID'].",$privat)");
		    	  }
		    	  
		    	if($error == 0 && is_array($orderId)){
		    	$summa = $summa*count($users_wp);
		    	$result_pay = $PAYMENT->PayGift((int)$_SESSION['WP_USER']['user_wp'],$summa,$akcia['currency_id'],$pin);
		    	if(is_array($result_pay) && $result_pay['Error']['ErrorId'] === '0'){
		    		if($akcia['kolvo'] != 0){
		    			$akcia_id['kolvo'] = (int) $akcia_id['kolvo']*count($users_wp);
		    		    $MYSQL->query("UPDATE pfx_akcia SET kolvo=kolvo-".$akcia_id['kolvo']." WHERE id = ".$akcia_id['akcia_id']);
		    		}
		    		
		    		require_once(path_modules."ini.sms.php");
			        require_once(path_modules."ini.sendmail.lib.php");
		    		
		    		for($i=0; $i < count($orderId); $i++){
		    		    $MYSQL->query("UPDATE pfx_historypay SET statuspay_id=3 WHERE id = ".(int)$orderId[$i]);
		    		    $MYSQL->query("INSERT INTO pfx_users_deystvie (data_add,user_wp,deystvie,id_deystvie,privat) VALUES (now(),".(int)$_SESSION['WP_USER']['user_wp'].",2,".$orderId[$i].",$privat)");
		    		    $PAYMENT->IsInvitation((int)$_SESSION['WP_USER']['user_wp'],$users_wp[0]['info']['user_wp'],$orderId[$i]);
		    		}
		    		
		    		for($i=0; $i < count($users_wp); $i++){
		    		    switch($akcia['type_id']){
		    			  case 5: // подарок
		    			  case 6: // сертификат
		    			    if(is_mobile($users_wp[$i]['info']['mobile'])){
		    			    	$sms_code_gift = "";
		    			    	foreach($users_wp[$i]['gift_code'] as $key=>$value)
		    			    	   $sms_code_gift .= $value."\n";
		    			    	if($_SESSION['WP_USER']['sex'] == 1) $end = ""; else $end = "а";
		    			    	$sms_msg = trim($_SESSION['WP_USER']['firstname'] ." ".$_SESSION['WP_USER']['lastname'])." сделал$end Вам подарок в ".$akcia['shop_name']."  ".$akcia['country']."\nКод подарка: ".$sms_code_gift."Код действителен до ".MyDataTime(date("d.m.y"),'date2','+',day_podarok)."\nТел.:".$akcia['phone'];
		    			        $SMS->SendSMS($users_wp[$i]['info']['mobile'],$sms_msg);
		    			    } 			    
		    			    
		    			    $Email_msg  = "<html><body>";
		    			    $Email_msg .= "Здравствуйте, ".trim($users_wp[$i]['info']['firstname']."".$users_wp[$i]['info']['lastname'])."!<br /><br />";
		                    $Email_msg .= $akcia['header']."<br />";
		                    $Email_msg .= "<table><tr><td valign=\"top\"><img src=\"".$akcia['photo']."\"></td>";
		                    $Email_msg .= "<td>$msg</td></tr></table><br /><br />";
		                    // $session нужно проверять, т.к. создается только для новых пользователей
		                    //$Email_msg .= "--- Ссылка для просмотра Вашего подарка ---\n";
		                    //$Email_msg .= "    ".sys_url."active-$session\n\n";
		                    $Email_msg .= "С Уважением, ".sys_url."</body></html>";
			
		                    if(send_mail($users_wp[$i]['info']['email'], $Email_msg, sys_copy, $_SESSION['WP_USER']['email'], trim($_SESSION['WP_USER']['firstname'] ." ".$_SESSION['WP_USER']['lastname']))){
		                    	$return = "<div class=\"firminfo\"><h1>4. Подарок подарен</h1><p>Оплата прошла успешно<br />Код подарка отправлен получателю(ям)</p></div>";
		                    } else 
		                        $return = "<div class=\"firminfo\"><h1>4. Подарок подарен</h1><p>Оплата прошла успешно<br /><b style=\"color:red\">Код подарка не удалось отправить получателю</b></p></div>";
		    			  break;
		    		    }
		    		}
		    		
		    		$objResponse->assign('step3', 'innerHTML', $return);
		    		
		    	} else $error=13;
		    } else $error=12;
		    	  
		    	  
				} else $error=11;
			} else $error=10;
		}
		/**************************************************************************************************************************************************************************************/
		elseif(isset($users_wp[0]['user_mobile'])){ // Если это указали mobile
			if(is_mobile($users_wp[0]['user_mobile'])){
				$user = $USER->Info_min($mob_info['mobile'],0,0);
				if(is_array($user)){
					$users_wp[0]['info'] = $user;
				} else {
					$users_wp[0]['info']['user_wp'] = 0;
					$users_wp[0]['info']['mobile']  = $mob_info['mobile'];
				}
				
				for($j=0; $j < $akcia_id['kolvo']; $j++){ // Добавляем каждому пользователю подарки по кол-ву подареных
		    		  $users_wp[0]['gift_code'][] = GeneralCodePodarok();
		    		  $GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
			          $orderId[] = $MYSQL->query("INSERT INTO pfx_historypay (data,code,klient_id,shop_id,akcia_id,type_id,from_wp,to_wp,from_mobile,to_mobile,podarok,amount,currency_id,statuspay_id,msg,town_id,privat) VALUES (now(),'".$users_wp[0]['gift_code'][$j]."',".$akcia['klient_id'].",".$akcia['shop_id'].",".$akcia['id'].",".$akcia['type_id'].",".(int)$_SESSION['WP_USER']['user_wp'].",".$users_wp[0]['info']['user_wp'].",'".$_SESSION['WP_USER']['mobile']."','".$users_wp[0]['info']['mobile']."','".serialize($akcia)."',".$akcia['amount'].",".$akcia['currency_id'].",1,'".mysql_real_escape_string(strip_tags($msg))."',".$_SESSION['TOWN_ID'].",$privat)");
				}
				
				
				if($error == 0 && is_array($orderId)){
		    	$summa = $summa*count($users_wp);
		    	$result_pay = $PAYMENT->PayGift((int)$_SESSION['WP_USER']['user_wp'],$summa,$akcia['currency_id'],$pin);
		    	if(is_array($result_pay) && $result_pay['Error']['ErrorId'] === '0'){
		    		if($akcia['kolvo'] != 0){
		    			$akcia_id['kolvo'] = (int) $akcia_id['kolvo']*count($users_wp);
		    		    $MYSQL->query("UPDATE pfx_akcia SET kolvo=kolvo-".$akcia_id['kolvo']." WHERE id = ".$akcia_id['akcia_id']);
		    		}
		    		
		    		require_once(path_modules."ini.sms.php");
			        require_once(path_modules."ini.sendmail.lib.php");
		    		
		    		for($i=0; $i < count($orderId); $i++){
		    		    $MYSQL->query("UPDATE pfx_historypay SET statuspay_id=3 WHERE id = ".(int)$orderId[$i]);
		    		    $MYSQL->query("INSERT INTO pfx_users_deystvie (data_add,user_wp,deystvie,id_deystvie,privat) VALUES (now(),".(int)$_SESSION['WP_USER']['user_wp'].",2,".$orderId[$i].",$privat)");
		    		    $PAYMENT->IsInvitation((int)$_SESSION['WP_USER']['user_wp'],$users_wp[0]['info']['user_wp'],$orderId[$i]);
		    		}
		    		
		    		for($i=0; $i < count($users_wp); $i++){
		    		    switch($akcia['type_id']){
		    			  case 5: // подарок
		    			  case 6: // сертификат
		    			    if(is_mobile($users_wp[$i]['info']['mobile'])){
		    			    	$sms_code_gift = "";
		    			    	foreach($users_wp[$i]['gift_code'] as $key=>$value)
		    			    	   $sms_code_gift .= $value."\n";
		    			    	if($_SESSION['WP_USER']['sex'] == 1) $end = ""; else $end = "а";
		    			    	$sms_msg = trim($_SESSION['WP_USER']['firstname'] ." ".$_SESSION['WP_USER']['lastname'])." сделал$end Вам подарок в ".$akcia['shop_name']."  ".$akcia['country']."\nКод подарка: ".$sms_code_gift."Код действителен до ".MyDataTime(date("d.m.y"),'date2','+',day_podarok)."\nТел.:".$akcia['phone'];
		    			        $SMS->SendSMS($users_wp[$i]['info']['mobile'],$sms_msg);
		    			    } 			    
		    			    
		    			    if(is_email(@$users_wp[$i]['info']['email'])){
		    			       $Email_msg  = "<html><body>";
		    			       $Email_msg .= "Здравствуйте, ".trim($users_wp[$i]['info']['firstname']."".$users_wp[$i]['info']['lastname'])."!<br /><br />";
		                       $Email_msg .= $akcia['header']."<br />";
		                       $Email_msg .= "<table><tr><td valign=\"top\"><img src=\"".$akcia['photo']."\"></td>";
		                       $Email_msg .= "<td>$msg</td></tr></table><br /><br />";
		                       // $session нужно проверять, т.к. создается только для новых пользователей
		                       //$Email_msg .= "--- Ссылка для просмотра Вашего подарка ---\n";
		                       //$Email_msg .= "    ".sys_url."active-$session\n\n";
		                       $Email_msg .= "С Уважением, ".sys_url."</body></html>";
			
		                    if(send_mail($users_wp[$i]['info']['email'], $Email_msg, sys_copy, $_SESSION['WP_USER']['email'], trim($_SESSION['WP_USER']['firstname'] ." ".$_SESSION['WP_USER']['lastname'])))
		                    	$return = "<div class=\"firminfo\"><h1>4. Подарок подарен</h1><p>Оплата прошла успешно<br />Код подарка отправлен получателю(ям)</p></div>";
		                    else 
		                        $return = "<div class=\"firminfo\"><h1>4. Подарок подарен</h1><p>Оплата прошла успешно<br /><b style=\"color:red\">Код подарка не удалось отправить получателю</b></p></div>";
		    			    } else {
		    			    	$return = "<div class=\"firminfo\"><h1>4. Подарок подарен</h1><p>Оплата прошла успешно<br />Код подарка отправлен получателю(ям)</p></div>";
		    			    }
		    			  break;
		    		    }
		    		}
		    		
		    		$objResponse->assign('step3', 'innerHTML', $return);
		    		
		    	} else $error=13;
		    } else $error=12;
				
				
			} else $error=9;
		} else $error=7;
	} else $error=8;
	
	switch($error){
		case 1: case 2:
			$objResponse->alert("ERROR $error \nПодарок не найден");
		break;
		case 3:
			$objResponse->alert("ERROR $error \nКол-во подарков превышает допустимое");
		break;
		case 4: case 11:
			$objResponse->alert("ERROR $error \nПолучатель не найден");
		break;
		case 7: case 9:
			$objResponse->alert("ERROR $error \nНеверно указан номер телефона получателя");
		break;
		case 8:
			$objResponse->alert("ERROR $error \nОшибка передачи данных о получателе");
		break;
		case 13:
			$objResponse->alert("ERROR $error \n".@$result_pay['Error']['ErrorDesc']);
		break;
		case 12:
			$objResponse->alert("ERROR $error \nНе удалось добавить подарок");
		break;
		case 10:
			$objResponse->alert("ERROR $error \nEmail указан неверно");
		break;
	}
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'PayGift');
?>
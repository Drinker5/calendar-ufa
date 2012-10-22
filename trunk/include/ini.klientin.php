<?php

function KlientIN($login,$passw){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$tbklients = "pfx_klients";
	$login     = mysql_escape_string(strip_tags(trim($login)));
	
	if(strlen($login) == 0 or strlen($passw) == 0) return LANG_ERROR_NULL_POLE;
	
	$result = $MYSQL->query("SELECT id, datareg, passw, fio, dogovor FROM $tbklients WHERE login = '$login'");
	if(is_array($result) && count($result) == 1){		
		if(tep_validate_password($passw.pfx_passw,$result[0]['passw'])){
		   $_SESSION['KLIENT'] = array(
		      'id'      => $result[0]['id'],
		      'datareg' => $result[0]['datareg'],
		      'fio'     => $result[0]['fio'],
		      'dogovor' => $result[0]['dogovor'],
		   );
		   @setcookie('shop_login',$login,time()+60*60*24*30,'/',$_SERVER['HTTP_HOST']);
		   return 1;
		} else {
			return LANG_ERROR_NOT_PASSW;
		}
	} else {
		return LANG_ERROR_NOT_USER;
	}
}


function ShopRegistration($email){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$tbklients = "pfx_klients";
	
	if(is_email($email)){
	   $result = $MYSQL->query("SELECT Count(*) FROM $tbklients WHERE email='$email'");
	   if($result[0]['count'] == 0){
	   	   $session = md5($email."::".date("d.m.Y")."::".$MYSQL->getmicrotime()); // Генерим временную сессию
	   	   $result  = $MYSQL->query("INSERT INTO $tbklients (datareg,email,login) VALUES (now(),'$email','$session')");
	   	   if($result > 0){
	   	   	  require_once(path_modules."ini.sendmail.lib.php");
	     
	          $Msg  = "Здравствуйте!<br /><br />";
	          $Msg .= "Это письмо отправленно Вам с сервиса ".sys_copy."<br />";
		      $Msg .= "Этот email адрес был зарегистрирован в нашем сервисе.<br />";
		      $Msg .= "Если Вы не регистрировались на нашем сайте ".sys_url.", то можете удалить данное письмо<br /><br />";
		      $Msg .= "Если регистрацию проходили Вы, то нажмите мышкой на ссылку указанную ниже для активации своего аккаунта<br />";
		      $Msg .= "--- Ссылка для Активации Вашего аккаунта ---<br />";
		      $Msg .= "    <a href=\"".sys_url."activeshop-$session\">".sys_url."activeshop-$session</a><br /><br />";
		      $Msg .= "С Уважением, ".sys_url."<br /><br />";
			
		      if(send_mail($email, $Msg, LANG_BTN_REGISTER.' '.sys_copy, sys_email, sys_copy))
			    return 0; // Регистрация успешна
		       else 
		        return -3; // Регистрация успешна но письмо не отправилось \\ Херово :)
	   	   } else return -2;
	   } else return -4;
	} else return -1;
}



function Activate($session){
	global $MYSQL;
		
	$GLOBALS['PHP_FILE'] = __FILE__;
    $GLOBALS['FUNCTION'] = __FUNCTION__;
	    
    $tbklients = "pfx_klients";
	    
    if(strlen($session) == 32){
    	$session = mysql_real_escape_string(strip_tags(trim($session)));
    	$result = $MYSQL->query("SELECT id, email, fio, dogovor FROM $tbklients WHERE tmp_ses='".$session."'");
    	if(is_array($result) && count($result) == 1){
    		$MYSQL->query("UPDATE $tbklients SET everif=1, tmp_ses=null WHERE id=".(int)$result[0]['id']);
    		$_SESSION['KLIENT'] = array(
		      'id'      => $result[0]['id'],
		      'email'   => $result[0]['email'],
		      'fio'     => $result[0]['fio'],
		      'dogovor' => $result[0]['dogovor'],
		    );
		    return 1;
    	}
    	return -2; // Нет пользователя с такой сессией
    }
    return -1; // Не верная сессия
}



function SendNewPassw($email){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	if(is_email($email)){
		
		$result = $MYSQL->query("SELECT id, fio, login FROM pfx_klients WHERE email='$email'");
		if(is_array($result) && count($result) == 1){
			
			$rand_word = str_shuffle('ABC2zhbk35235DEFGHIdfberhJKL9843zdfhzdfhe2198MNOmnklopphuiPQ23qwez525nbvcmbvxcvbe5266987025RSTUVWXYZ'); // Случайный разброс символов
	        $rand_word = substr($rand_word,1,7);
			
			$new_passw = tep_encrypt_password($rand_word.pfx_passw);
			
			$MYSQL->query("UPDATE pfx_klients SET passw='$new_passw' WHERE email='$email'");
			
			require_once(path_modules."ini.sendmail.lib.php");
			
			$Msg  = "Здравствуйте, ".$result[0]['fio']."<br /><br />";
			$Msg .= "Ваши данные для входа:<br />";
			$Msg .= "Логин:  ".$result[0]['login']." <br />";
			$Msg .= "Пароль: ".$rand_word." <br /><br />";
			$Msg .= "С Уважением, ".sys_copy." ".sys_url." <br />";
			
			if(send_mail($email, $Msg, 'Восстановление пароля', sys_email, sys_copy)){
				return "Ваш новый пароль выслан Вам на ".$email;
			} else {
				return "Не удалось отправить Вам новый пароль на ".$email;
			}
		} else {
			return "Такой email у нас не зарегистрирован";
		}
		
	} else {
		return "Ошибка! Вы указали не корректный email адрес";
	}
}


?>
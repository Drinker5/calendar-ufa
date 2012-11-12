<?php

require_once("ini.api.client.php");
require_once("ini.comments.php");

class T_USERS {

//!Пользователи — добавление нового пользователя
	function Add($userinfo){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;
		$tbusers  = "pfx_users";
		$passw= tep_encrypt_password($userinfo['password'].pfx_passw);
		$date = $userinfo['birthday_year'].'-'.$userinfo['birthday_month'].'-'.$userinfo['birthday_day'];
		$session = md5($userinfo['email']."::".date("d.m.Y")."::".$MYSQL->getmicrotime()); // Генерим временную сессию
		$result  = $MYSQL->query("INSERT INTO $tbusers (datareg,email,session,passw,firstname,lastname,mobile,town_id,sex,birthday) VALUES (now(),'{$userinfo['email']}','$session','$passw','{$userinfo['firstname']}','{$userinfo['lastname']}','{$userinfo['phone']}',{$userinfo['town']},{$userinfo['sex']},STR_TO_DATE('$date','%Y-%m-%d'))");
		if($result > 0){
		$MYSQL->query("INSERT INTO pfx_users_ihere (data,user_wp,address_id,online) VALUES (now(),$result,0,0)");
         $Msg  = "Здравствуйте!<br /><br />";
         $Msg .= "Это письмо отправленно Вам с сервиса ".sys_copy."<br />";
	     $Msg .= "Этот email адрес был зарегистрирован в нашем сервисе.<br />";
	     $Msg .= "Если Вы не регистрировались на нашем сайте ".sys_url.", то можете удалить данное письмо<br /><br />";
	     $Msg .= "Если регистрацию проходили Вы, то нажмите мышкой на ссылку указанную ниже для активации своего аккаунта<br />";
	     $Msg .= "--- Ссылка для Активации Вашего аккаунта ---<br />";
	     $Msg .= "    <a href=\"".sys_url."active-$session\">".sys_url."active-$session</a><br /><br />";
	     $Msg .= "С Уважением, ".sys_copy." ".sys_url."<br /><br />";

	     if(send_mail($userinfo['email'], $Msg, LANG_BTN_REGISTER.' '.sys_copy))
	    {
	    	@setcookie('reg_mail',$userinfo['email'],time()+600,'/');
		  return 0; // Регистрация успешна
		}
	     else
	      return -3; // Регистрация успешна но письмо не отправилось \\ Херово :)
		}
		return -2; // Не удалось вставить строку в БД

	}


//!Пользователи — добавление нового пользователя залогинившийся через Facebook
	function AddFacebook($email,$data){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbusers  = "pfx_users";

		if(is_email($email)){
			$result = $MYSQL->query("SELECT Count(*) FROM $tbusers WHERE email='$email'");
			if($result[0]['count'] > 0) return -4;

			$sex = 0; $foto = ""; $last_name = ""; $first_name = "";
			if(is_array($data) && count($data) > 1){
               if($data['gender'] == 'male') $sex = 1; else if($data['gender'] == 'femal') $sex = 2;
               if(strlen($data['birthday']) > 0){
                  $data['birthday'] = explode("/",$data['birthday']);
                  $data['birthday'] = $data['birthday'][2]."-".$data['birthday'][1]."-".$data['birthday'][0];
               }
               $foto = "https://graph.facebook.com/".$data['id']."/picture";

               if(strlen(@$data['first_name']) > 0 && strlen(@$data['last_name']) > 0){
          	    $first_name = $data['first_name'];
                $last_name  = $data['last_name'];
               } else {
          	    $full_name = explode(" ",@$data['name']);
          	    if(is_array($full_name)){
          	       $first_name = trim($full_name[0]);
          	       $last_name  = trim($full_name[1]);
          	    } else {
          	       $first_name = @$data['name'];
          	       $last_name  = "";
          	    }
              }
            }

			$session = md5($email."::".date("d.m.Y")."::".$MYSQL->getmicrotime()); // Генерим временную сессию
			$result  = $MYSQL->query("INSERT INTO $tbusers (facebook_id,datareg,email,session,sex,photo,firstname,lastname,url,birthday) VALUES ('".@$data['id']."',now(),'$email','$session',$sex,'$foto','$first_name','$last_name','".@$data['link']."','".@$data['birthday']."')");

			if($result > 0){
	         $Msg  = "Здравствуйте!<br /><br />";
	         $Msg .= "Это письмо отправленно Вам с сервиса ".sys_copy."<br />";
		     $Msg .= "Поздравляем Вас с успешной регистрацией на наем сайте.<br />";
		     $Msg .= "Перейти на свою страницу <a href=\"".sys_url.$result."\">".sys_url.$result."</a><br /><br />";
		     $Msg .= "С Уважением, ".sys_copy." ".sys_url."<br /><br />";

		     send_mail($email, $Msg, LANG_BTN_REGISTER.' '.sys_copy);
			 return $this->WPin($session);
			}
			return -2; // Не удалось вставить строку в БД
		}
		return -1; // Не корректный email
	}


	function Activate($session){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbusers  = "pfx_users";

	    if(strlen($session) == 32){
	    	$result = $MYSQL->query("SELECT `user_wp` FROM $tbusers WHERE session='".mysql_real_escape_string($session)."'");
	    	if(is_array($result) && count($result) == 1){
	    		$MYSQL->query("UPDATE $tbusers SET everif=1 WHERE session='".mysql_real_escape_string($session)."'");
	    		$this->CreateAvatarsAlbum($result[0]['user_wp']);
	    		return $this->WPin($session);
	    	}
	    	return -2; // Нет пользователя с такой сессией
	    }
	    return -1; // Не верная сессия
	}


//!Пользователи — авторизация
	function AuthUser($email,$passw){
		global $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		if(strlen($email)==0 or strlen($passw)==0)return LANG_ERROR_NULL_POLE;

		$tbusers="pfx_users";
		$email  =mysql_real_escape_string(strip_tags(trim($email)));

		$result =$MYSQL->query("SELECT `user_wp`, `passw`, `everif` FROM `".$tbusers."` WHERE `email`='".$email."'");
		if(is_array($result) && count($result)==1){
			if($result[0]['everif']==1){
				if(tep_validate_password($passw.pfx_passw,$result[0]['passw'])){
					$session=md5($result[0]['user_wp']."::".$MYSQL->getmicrotime());//Генерим временную сессию
					$MYSQL->query("UPDATE `".$tbusers."` SET `session`='".mysql_real_escape_string($session)."' WHERE `user_wp`=".$result[0]['user_wp']);
					return $this->WPin($session);
				}
				else return LANG_ERROR_NOT_PASSW;
			}
			else return LANG_ERROR_NOT_USER;
		}
		else return LANG_ERROR_NOT_USER;
	}


//!Пользователи — данные пользователя в сессию
	function WPin($session){
		global $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbusers            ="pfx_users";
		$tbcookies          ="pfx_users_cookies";

		$user=$MYSQL->query("SELECT `user_wp`, `email` FROM `".$tbusers."` WHERE `session`='".$session."'");
		if(is_array($user) && count($user)==1){
			$time           =$MYSQL->getmicrotime();
			$cookies_session=md5($_SERVER['HTTP_HOST'].":".$_SERVER['HTTP_USER_AGENT'].":".$time);
			$cookies        =$MYSQL->query("SELECT Count(*) FROM $tbcookies WHERE cookies_ses='$cookies_session'");
			if($cookies[0]['count']>0)return $this->WPin($session);

			$MYSQL->query("UPDATE `".$tbusers."` SET `session2`=session, `session`=null WHERE `session`='".$session."'");
			$MYSQL->query("INSERT INTO `".$tbcookies."` (`data`, `user_wp`, `cookies_ses`, `user_agent`, `ip`) VALUES (now(),'".$user[0]['user_wp']."', '".$cookies_session."', '".$_SERVER['HTTP_USER_AGENT']."', '".$MYSQL->tep_get_ip_address()."')");

			@setcookie('user_email',$user[0]['email'],time()+60*60*24*30,'/',$_SERVER['HTTP_HOST']);
			@setcookie('tmp_time',$time,time()+60*60*24*30,'/',$_SERVER['HTTP_HOST']);
			@setcookie('tmp_ses',$cookies_session,time()+60*60*24*30,'/',$_SERVER['HTTP_HOST']);

			$_SESSION['WP_USER']=array(
				'user_wp' => $user[0]['user_wp'],
				'session' => $session,
			);
			return true;
		}
		return $this->Session();
	}


//!Пользователи — авторизация через Facebook
	function AuthUserFacebook(){
	    global $MYSQL, $facebook_me;

	    $GLOBALS['PHP_FILE'] = __FILE__;
        $GLOBALS['FUNCTION'] = __FUNCTION__;

        $tbusers   = "pfx_users";

        if(!is_array($facebook_me) or !is_email($facebook_me['email'])) return -1;

        $result = $MYSQL->query("SELECT user_wp FROM $tbusers WHERE email = '".$facebook_me['email']."'");
        if(is_array($result) && $result[0]['user_wp'] > 0){
    	    $session = md5($MYSQL->getmicrotime().':'.$result[0]['user_wp'].':'.$facebook_me['email'].':'.date("Y-m-d"));
    	    $MYSQL->query("UPDATE $tbusers SET session='$session' WHERE user_wp=".$result[0]['user_wp']);
    	    return $this->WPin($session);
        } else {
    	   return $this->AddFacebook($facebook_me['email'],$facebook_me);
        }
    }


//!Пользователи — сессия
	function Session(){
		global $MYSQL, $facebook_me;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		$tbusers  ="pfx_users";
		$tbcookies="pfx_users_cookies";

		if(isset($_COOKIE['tmp_ses']) && isset($_COOKIE['tmp_time'])){
			$cookies_session=md5($_SERVER['HTTP_HOST'].":".$_SERVER['HTTP_USER_AGENT'].":".$_COOKIE['tmp_time']);

			if($cookies_session!=$_COOKIE['tmp_ses'])return $this->Out();

			$user=$MYSQL->query("
				SELECT $tbusers.user_wp, $tbusers.session2
				FROM $tbusers
				INNER JOIN $tbcookies ON $tbcookies.user_wp = $tbusers.user_wp
				WHERE $tbcookies.cookies_ses='$cookies_session'
			");

			if(is_array($user) && count($user)==1){
				/*
				@setcookie('tmp_time','',1,'/');
				@setcookie('tmp_ses','',1,'/');

				$time=$MYSQL->getmicrotime();
				$new_cookies_session = md5($_SERVER['HTTP_HOST'].":".$_SERVER['HTTP_USER_AGENT'].":".$time);
				$MYSQL->query("UPDATE $tbcookies SET data=now(), cookies_ses='$new_cookies_session', user_agent='".$_SERVER['HTTP_USER_AGENT']."', ip='".$MYSQL->tep_get_ip_address()."' WHERE cookies_ses = '$cookies_session'");

				@setcookie('tmp_time',$time,time()+60*60*24*30,'/');
				@setcookie('tmp_ses',$new_cookies_session,time()+60*60*24*30,'/');
				*/

				$_SESSION['WP_USER']=array(
					'user_wp' => $user[0]['user_wp'],
					'session' => $user[0]['session2'],
				);
				return true;
			}
		}
		return $this->Out();
	}


//!Пользователи — выход
	function Out(){
		global $MYSQL;

		if(!isset($_SESSION['WP_USER']['user_wp'])) {unset($_SESSION); return false;}

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbcookies          ="pfx_users_cookies";
		$tbihere            ="pfx_users_ihere";
		$tbusers_deystvie   ="pfx_users_deystvie";

		$MYSQL->query("DELETE FROM `".$tbcookies."` WHERE `cookies_ses`='".@$_COOKIE['tmp_ses']."'");
		$MYSQL->query("DELETE FROM `".$tbihere."` WHERE `user_wp`=".(int)@$_SESSION['WP_USER']['user_wp']);
		$MYSQL->query("DELETE FROM `".$tbusers_deystvie."` WHERE `user_wp`=".(int)@$_SESSION['WP_USER']['user_wp']." AND `deystvie`=8");
		//$MYSQL->query("DELETE FROM pfx_users_comments WHERE to_wp=".(int)@$_SESSION['WP_USER']['user_wp']." AND IFNULL(address_id,0) > 0");

		@setcookie('tmp_time','',1,'/');
		@setcookie('tmp_ses','',1,'/');

		unset($_SESSION);
		return false;
	}


	function VerifidEmail($activation=""){
		global $MYSQL, $varr;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbusers = "pfx_users";

	    if(isset($varr['email']) && is_email($varr['email'])){
	    	 $session = md5($varr['email']."::".date("d.m.Y")."::".$MYSQL->getmicrotime()); // Генерим временную сессию

	         $Msg  = "Здравствуйте!<br /><br />";
	         $Msg .= "Это письмо отправленно Вам с сервиса ".sys_copy."<br /><br />";
		     $Msg .= "--- Ссылка для верификации Вашего email адреса ---<br />";
		     $Msg .= "    <a href=\"".sys_url."verifid-$session\">".sys_url."verifid-$session</a><br /><br />";
		     $Msg .= "С Уважением, ".sys_url."<br /><br />";

		     if(send_mail($varr['email'], $Msg, 'Верификация email адреса '.sys_copy)){
		     	$MYSQL->query("UPDATE $tbusers SET session='".mysql_real_escape_string($session)."' WHERE email = '".mysql_real_escape_string($varr['email'])."'");
		     }

	    } elseif(strlen($activation) == 32){
	    	$MYSQL->query("UPDATE $tbusers SET everif=1 WHERE session = '".mysql_real_escape_string($activation)."'");
	    	return true;
	    }
	}


	function UpdateProfile($info){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbusers  = "pfx_users";
		$email    = "";
		$birthday = "";

		if(is_array($info)){

		   if(!is_email($_SESSION['WP_USER']['email'])) $email = ", email='".$info['email']."' ";

		   if(strlen($info['year']) > 0 && strlen($info['month']) > 0 && strlen($info['day']) > 0){
		      $birthday = $info['year']."-".$info['month']."-".$info['day'];
		      $birthday = "birthday='$birthday',";
		   } else {
		   	  $birthday = "birthday=null,";
		   }

		   if(strlen($email) > 0){
		   	$result = $MYSQL->query("SELECT Count(*) FROM $tbusers WHERE email='".$info['email']."'");
		   	if($result[0]['count'] > 0) return -1;
		   }

	       $MYSQL->query("UPDATE $tbusers SET $birthday birthdayview=".varr_int($info['year_view']).", otchestvo='".$info['otchestvo']."',lastname='".$info['sname']."',firstname='".$info['fname']."',sex=".varr_int($info['sex']).",town_id=".varr_int($info['town_id']).",skype='".$info['skype']."',icq='".$info['icq']."',url='".$info['url']."',education='".$info['education']."',career='".$info['career']."',about='".$info['about']."',marital_status='".$info['marital_status']."' $email WHERE user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
	       return 1;
		}
		return -2;
	}


	function ChangePassword($passw1,$passw2,$passw3){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbusers  = "pfx_users";

	    if(strlen($passw1) > 0 && strlen($passw2) > 0){
	    	$result = $MYSQL->query("SELECT IFNULL(passw,'') passw FROM $tbusers WHERE user_wp=".(int)$_SESSION['WP_USER']['user_wp']);
	    	if(is_array($result)){
	    		$result = $result[0];
	    		if($result['passw'] != ''){
	    			if(!tep_validate_password($passw3.pfx_passw,$result['passw'])) return -1; // Пароль старый не совпал
	    		}
	    		if($passw1 != $passw2) return -2; // Новые пароли не совпали друг с другом
	    		$passw = tep_encrypt_password($passw1.pfx_passw);
	    		$MYSQL->query("UPDATE $tbusers SET passw='$passw' WHERE user_wp=".(int)$_SESSION['WP_USER']['user_wp']);
	    		return 1;
	    	} else return -3;
	    } else return -4;
	}


	function SendNewPassw($email){
	   global $MYSQL;

	   $GLOBALS['PHP_FILE'] = __FILE__;
	   $GLOBALS['FUNCTION'] = __FUNCTION__;

	   $result = $this->Info_min($email,0,0);

	   if(is_array($result)){

		$rand_word = str_shuffle('ABC2zhbk35235DEFGHIdfberhJKL9843zdfhzdfhe2198MNOmnklopphuiPQ23qwez525nbvcmbvxcvbe5266987025RSTUVWXYZ'); // Случайный разброс символов
	    $rand_word = substr($rand_word,1,7);

		$new_passw = tep_encrypt_password($rand_word.pfx_passw);

		$MYSQL->query("UPDATE pfx_users SET passw='$new_passw' WHERE user_wp=".(int)$result['user_wp']);

		$Msg  = "Здравствуйте, ".$result['firstname']." ".$result['lastname']."<br /><br />";
		$Msg .= "Ваши данные для входа:<br />";
		$Msg .= "Email:  ".$result['email']."<br />";
		$Msg .= "Пароль: ".$rand_word."<br /><br />";
		$Msg .= "С Уважением, ".sys_copy." ".sys_url."<br /><br />";

		if(send_mail($email, $Msg, 'Восстановление пароля')){
			return 1;
			//return "Ваш новый пароль выслан Вам на ".$email;
		} else {
			return "Не удалось отправить Вам новый пароль на ".$email;
		}
	   } else {
		  return "$email у нас не зарегистрирован";
	   }
    }

	function AddMobile($mobile){
		global $MYSQL, $mob_info;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    if(!isset($_SESSION['WP_USER'])) return false;

		$tbusers  = "pfx_users";
		$tbusers_mobile = "pfx_users_mobile";

		if(is_mobile($mobile)){
			$result = $MYSQL->query("SELECT Count(*) FROM $tbusers_mobile WHERE mobile='".$mob_info['mobile']."'");
			if($result[0]['count'] == 0){
			  $MYSQL->query("INSERT INTO $tbusers_mobile (user_wp,mobile) VALUES (".(int)$_SESSION['WP_USER']['user_wp'].",'".$mob_info['mobile']."')");
			  $MYSQL->query("UPDATE pfx_historypay SET to_wp=".(int)$_SESSION['WP_USER']['user_wp']." WHERE to_mobile='".$mob_info['mobile']."'");
			  $result = $MYSQL->query("SELECT IFNULL(mobile,'') mobile FROM $tbusers WHERE user_wp=".(int)$_SESSION['WP_USER']['user_wp']);
			  if(strlen($result[0]['mobile']) == 0){
			     $MYSQL->query("UPDATE $tbusers SET mobile='".$mob_info['mobile']."' WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);
			     $this->UpdateMobileMain($mob_info['mobile']);
			  }
			  return true;
			}
		}
		return false;
	}

	function ChangeMobile($oldmobile,$newmobile)
	{
		global $MYSQL, $mob_info;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    if(!isset($_SESSION['WP_USER'])) return false;

	    $tbusers  = "pfx_users";
		$tbusers_mobile = "pfx_users_mobile";

		$oldmobile = preg_replace("/[^0-9]/", "", trim($oldmobile));
		$oldmobile = str_replace("3800","380",$oldmobile);
		if ( is_mobile($newmobile) )
		{
			$MYSQL->query("UPDATE $tbusers_mobile SET mobile ='".$mob_info['mobile']."' WHERE mobile ='".$oldmobile."'");
			return true;
		}
		return false;

	}

	function UpdateMobileMain($mobile){
		global $MYSQL, $mob_info;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    if(!isset($_SESSION['WP_USER'])) return false;

		$tbusers  = "pfx_users";
		$tbusers_mobile = "pfx_users_mobile";

		if(is_mobile($mobile)){
			$MYSQL->query("UPDATE $tbusers SET mobile='".$mob_info['mobile']."' WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);
			$MYSQL->query("UPDATE $tbusers_mobile SET main=0 WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);
			$MYSQL->query("UPDATE $tbusers_mobile SET main=1 WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND mobile='".$mob_info['mobile']."'");
			return true;
		}
		return false;
	}

	function DeleteMobile($mobile){
		global $MYSQL, $mob_info;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    if(!isset($_SESSION['WP_USER'])) return false;

		$tbusers  = "pfx_users";
		$tbusers_mobile = "pfx_users_mobile";
		if(is_mobile($mobile)){
			$MYSQL->query("DELETE FROM $tbusers_mobile WHERE user_wp='".varr_int($_SESSION['WP_USER']['user_wp'])."' AND mobile='".$mob_info['mobile']."'");
			$result = $MYSQL->query("SELECT mobile FROM $tbusers_mobile WHERE user_wp='".varr_int($_SESSION['WP_USER']['user_wp'])."' LIMIT 0,1");
			if(is_array($result)){
				return $this->UpdateMobileMain($result[0]['mobile']);
			} else {
				$MYSQL->query("UPDATE $tbusers SET mobile=null WHERE user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
				return true;
			}
		}
	    return false;
	}



	function InvitationMobile($mobile,$msg=""){ // Приглашение по мобильному
		global $MYSQL, $AKCIA, $mob_info;

	    $tbusers   = "pfx_users";
	    $tbfriends = "pfx_users_friends";
	    $tbusers_mobile = "pfx_users_mobile";
	    $tbhistorypay = "pfx_historypay";

	    // Украина Киев
	    $GIFT[1] = array('akcia_id' => gift_free_ua, 'town_id' => 214); //http://mymobigift.com/gift-1028.php
	    // Россия Москва
	    $GIFT[2] = array('akcia_id' => gift_free_ru, 'town_id' => 79); //http://mymobigift.com/gift-1258.php

	    if(is_mobile($mobile)){

	    	$GLOBALS['PHP_FILE'] = __FILE__;
	        $GLOBALS['FUNCTION'] = __FUNCTION__;

	    	$mobile = $mob_info['mobile'];
	    	$friend = $MYSQL->query("SELECT $tbusers.user_wp, $tbusers.lastname, $tbusers.firstname
	    	                          FROM $tbusers
	    	                          INNER JOIN $tbusers_mobile ON $tbusers_mobile.user_wp = $tbusers.user_wp
	    	                         WHERE $tbusers_mobile.mobile='$mobile' OR $tbusers.mobile='$mobile'");
	    	if(is_array($friend)){ // Если этот пользователь уже есть
	    		$photo = ShowAvatar(array($friend[0]['user_wp']),70,70);
		    	if(is_array($photo))
		    	    $photo = $photo[0]['avatar'];
		    	else $photo = no_foto;
		    	return array(
		    	   'user_wp'   => $friend[0]['user_wp'],
		    	   'avatar'    => $photo,
		    	   'firstname' => $friend[0]['firstname'],
		    	   'lastname'  => $friend[0]['lastname'],
		    	);
	    	} else {
	    		$friend = $MYSQL->query("SELECT Count(*) FROM $tbhistorypay WHERE to_mobile='$mobile'");
	    		if($friend[0]['count'] > 0){
	    			return -1; // уже пригласили
	    		} else { // Если пользователь не зарегистрирован
			    	   $akcia = $AKCIA->Info_min($GIFT[$mob_info['country_id']]['akcia_id'],0,0);
			    	   if(is_array($akcia)){
			    	   	$gift_code = GeneralCodePodarok();

			    	   	$GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;

			    	   	$orderId = $MYSQL->query("INSERT INTO pfx_historypay (data,code,klient_id,shop_id,type_id,from_wp,from_mobile,to_mobile,podarok,amount,currency_id,statuspay_id,msg,town_id) VALUES (now(),'$gift_code',".$akcia['klient_id'].",".$akcia['shop_id'].",".$akcia['type_id'].",".(int)$_SESSION['WP_USER']['user_wp'].",'".$_SESSION['WP_USER']['mobile']."','$mobile','".serialize($akcia)."',".$akcia['amount'].",".$akcia['currency_id'].",3,'".mysql_real_escape_string(strip_tags($msg))."',".$GIFT[$mob_info['country_id']]['town_id'].")");

			    	   	if($orderId > 0){
			    	   		$this->AddDeystvie(0,0,2,$orderId);
			    	   		//$MYSQL->query("INSERT INTO pfx_users_deystvie (data_add,user_wp,deystvie,id_deystvie) VALUES (now(),".(int)$_SESSION['WP_USER']['user_wp'].",2,$orderId)");
			    	   		require_once(path_modules."ini.sms.php");
			    	   		if($_SESSION['WP_USER']['sex'] == 1) $end = ""; else $end = "а";
			    	   		$sms_msg = trim($_SESSION['WP_USER']['firstname'] ." ".$_SESSION['WP_USER']['lastname'])." сделал$end Вам подарок в ".$akcia['shop_name']."\nКод подарка: ".$gift_code."\nКод действителен до ".MyDataTime(date("d.m.y"),'date','+',day_podarok);
			    	   		if($SMS->SendSMS($mobile,$sms_msg)){
			    	   			return 1;
			    	   		} else return -3;//не смог отправить sms
			    	   	} else return -4;//не смог подарить подарок
			    	   } else return -5;//нет такого подарка
	    	  }
	    	}
	    } else return -2; // Не верный мобильник
	}



	function InvitationEmails($email,$text){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbusers   = "pfx_users";

	    if(!isset($_SESSION['WP_USER'])) return -6;

	    if(strlen(trim($text)) == 0) return -4;

	    if(is_email($email)){
	    	$friend = $MYSQL->query("SELECT user_wp, lastname, firstname FROM $tbusers WHERE email='$email'");
	    	if(is_array($friend)){ // Если этот пользователь уже есть
	    		$photo = ShowAvatar(array($friend[0]['user_wp']),70,70);
		    	if(is_array($photo))
		    	    $photo = $photo[0]['avatar'];
		    	else $photo = no_foto;
		    	return array(
		    	   'user_wp'   => $friend[0]['user_wp'],
		    	   'avatar'    => $photo,
		    	   'firstname' => $friend[0]['firstname'],
		    	   'lastname'  => $friend[0]['lastname'],
		    	);
	    	} else { // Приглашаем

	    		$session = md5($email."::".date("d.m.Y")."::".$MYSQL->getmicrotime()); // Генерим временную сессию
			    $result  = $MYSQL->query("INSERT INTO $tbusers (datareg,email,session) VALUES (now(),'$email','$session')");
	    		if($result > 0){
	    		   $id  = $MYSQL->query("INSERT INTO pfx_users_friends (data_add,user_wp,friend_wp,good) VALUES (now(),".(int)$_SESSION['WP_USER']['user_wp'].",$result,0)");
	    		   if($id > 0){
	               $Msg  = $text."<br /><br />";
		           $Msg .= "--- Для принятия приглашения нажмите на ссылку указанную ниже ---<br />";
		           $Msg .= "    <a href=\"".sys_url."active-$session\">".sys_url."active-$session</a><br /><br />";
		           $Msg .= "С Уважением, ".sys_copy." ".sys_url."<br /><br />";

		           if(send_mail($email, $Msg, 'Приглашение в друзья от '.$_SESSION['WP_USER']['firstname']." ".$_SESSION['WP_USER']['lastname'], $_SESSION['WP_USER']['email'], $_SESSION['WP_USER']['firstname']." ".$_SESSION['WP_USER']['lastname']))
			         return 1; // Регистрация успешна
		           else
		             return -3; // Регистрация успешна но письмо не отправилось \\ Херово :)
	    		   } else -5;
	    		} else return -5;
	    	}
	    } else return -2;
	}



	function CountInvitation($par=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $where = " AND good=0";
	    if($par == 1) $where = " AND good=1";

	    $result = $MYSQL->query("SELECT Count(*) FROM pfx_users_friends WHERE user_wp=".(int)$_SESSION['WP_USER']['user_wp']." AND invitation=1 $where");
	    return $result[0]['count'];
	}



	function UpdateUserStatus($status){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbusers        = "pfx_users";
	    $tbstatus       = "pfx_users_status";
		$tbuserdeystvie = "pfx_users_deystvie";

	    $MYSQL->query("UPDATE $tbusers SET status='".mysql_real_escape_string($status)."' WHERE user_wp=".(int)$_SESSION['WP_USER']['user_wp']."");
	    $stID=$MYSQL->query("INSERT INTO $tbstatus (`status`,`date`) VALUES ('".mysql_real_escape_string($status)."', NOW())");
	    return $MYSQL->query("INSERT INTO $tbuserdeystvie (`data_add`,`user_wp`,`deystvie`,`id_deystvie`) VALUES (NOW(), ".varr_int($_SESSION['WP_USER']['user_wp']).", 10, ".$stID.")");
	}

/****************************************************************************************************************************************************/


//!OnLine
	function OnLine($user_wp){
		global $MYSQL;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		$online=$MYSQL->query("SELECT Count(*) FROM `pfx_users` WHERE `user_wp`=".(int)$user_wp." AND `status_chat`!=0 AND `online` + INTERVAL 10 MINUTE > now()");
		if(is_array($online) && $online[0]['count']==1)return true;
		return false;
	}


//!Пользователи — информация о пользователе
	function Info($user_wp,$w=0,$h=0){
		global $MYSQL, $COUNTRY, $PAYMENT, $varr, $_URLP;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		$user_wp   =varr_int($user_wp);
		$tbusers   ="pfx_users";
		$tbusersbl ="pfx_users_balance";
		$tbcurrency="pfx_currency";

		//Общие данные пользователя
		$user=$MYSQL->query("
			SELECT IFNULL(`birthday`,'') `birthday`, `birthdayview`, `email`, `everif`, IFNULL(`passw`,'') `passw`, `mobile`, `otchestvo`, `lastname`, `firstname`, `photo`, IFNULL(`pravo`,'') `pravo`, `town_id`, IFNULL(`zvezda`,0) `zvezda`, IFNULL(`sex`,0) `sex`, `skype`, `url`, `icq`, `status`, `education`, `career`, `about`, `marital_status`, `status_chat`, `session2`, UNIX_TIMESTAMP(`online`) `online`
			FROM `".$tbusers."`
			WHERE `user_wp`=".$user_wp
		);

		if(is_array($user) && count($user)==1){
			$country=$COUNTRY->ShowTree2($user[0]['town_id']);
			if(is_array($country)){
				foreach($country as $key=>$value){
					if($value['parent']==0){
						$country_id  =$value['id'];
						$country_name=$value['name'];
					}
					else{
						$town_id  =$value['id'];
						$town_name=$value['name'];
					}
				}
			}

			if(isset($varr['town_id']) && $varr['town_id']>0){
				$result=$MYSQL->query("SELECT `id`, `parent` FROM `pfx_country` WHERE `id`=".varr_int($varr['town_id']));
				if(is_array($result) && $result[0]['id'] > 0){
					$_SESSION['COUNTRY_ID']=$result[0]['parent'];
					$_SESSION['TOWN_ID']   =$result[0]['id'];
				}
			}
			elseif(isset($_URLP[0]) && ($_URLP[0]=='shop' or $_URLP[0]=='gift' or $_URLP[0]=='type')){
				$COUNTRY->Geo();
			}
			elseif(!isset($_SESSION['COUNTRY_ID'])){
				if(isset($country_id))$_SESSION['COUNTRY_ID']=$country_id;
				else                  $_SESSION['COUNTRY_ID']=2;

				if(isset($town_id))   $_SESSION['TOWN_ID']   =$town_id;
				else                  $_SESSION['TOWN_ID']   =79;
			}

			//$balance['balance']=-1;
			//$balance['mask']   ='';

			//$result=$PAYMENT->Balance($user_wp,(int)$_SESSION['COUNTRY_ID']);
			/*
			$result=$PAYMENT->Balance($user_wp,0);
			if(is_array($result) && varr_int($result['Error']['ErrorId'])==0){
				$balance['balance']=array();
				$balance['mask']   =array();
				foreach($result['Balance'] as $key=>$value){
					//if($key==$_SESSION['COUNTRY_ID']){
						$balance['balance'][$value['Currency']]=$value['Amount']/100;
						//$currency=$MYSQL->query("SELECT `mask` FROM `pfx_currency` WHERE `id`=".varr_int($value['Currency']));
						$currency=$MYSQL->query("SELECT `currency` FROM `pfx_currency` WHERE `id`=".varr_int($value['Currency']));
						$balance['mask'][$value['Currency']]=@$currency[0]['currency'];
						//break;
					//}
				}
			}
			*/

			$photo=ShowAvatar(array($user_wp),$w,$h);
			if(is_array($photo))$photo=$photo[0]['avatar'];

			//День рождения
			if(strlen($user[0]['birthday'])>0){
				$birthday      =MyDataTime($user[0]['birthday'],'date');
				$year          =real_date_diff($user[0]['birthday']);
				//Количество времени до дня рождения
				$count_birthday=real_date_diff(MyDataTime($user[0]['birthday'],'dateUSA','+',0,0,$year[0]+1));
				if($user[0]['birthdayview']==0)$year[0]='';
			}
			else{
				$year[0]=''; $birthday=''; $count_birthday='';
			}

			if($user[0]['online']<time()-(60*10) && $user_wp!=$_SESSION['WP_USER']['user_wp'])$user[0]['status_chat']=0;

			return array(
				'user_wp'     => $user_wp,
				'session'     => $user[0]['session2'],
				'birthday'    => $birthday,
				'year'        => @$year[0],
				'countbday'   => $count_birthday,
				'birthdayview'=> $user[0]['birthdayview'],
				//'balance'     => $balance['balance'],
				//'balance_mask'=> $balance['mask'],
				'card_type'   => @$result['CardType']['Type'],
				'email'       => $user[0]['email'],
				'everif'      => $user[0]['everif'],
				'passw'       => $user[0]['passw'],
				'mobile'      => $user[0]['mobile'],
				'sex'         => $user[0]['sex'],
				'lastname'    => htmlspecialchars(stripslashes(trim($user[0]['lastname']))),
				'firstname'   => htmlspecialchars(stripslashes(trim($user[0]['firstname']))),
				'otchestvo'   => htmlspecialchars(stripslashes(trim($user[0]['otchestvo']))),
				'photo'       => $photo,
				'photo2'      => $user[0]['photo'],
				'zvezda'      => varr_int($user[0]['zvezda']),
				'security'    => Security($user_wp,$user[0]['pravo']),
				'country_id'  => @$country_id,
				'country_name'=> @$country_name,
				'town_id'     => @$town_id,
				'town_name'   => @$town_name,
				//'online'      => $this->OnLine($user_wp),
				'status_chat' => $user[0]['status_chat'],
				'skype'       => $user[0]['skype'],
				'icq'         => $user[0]['icq'],
				'url'         => $user[0]['url'],
				'status'      => $user[0]['status'],
				'education'   => $user[0]['education'],
				'career'      => $user[0]['career'],
				'about'       => $user[0]['about'],
				'marital_status' => $user[0]['marital_status'],
			);
		}
	}


//!Пользователи — информация краткая
	function Info_min($user_wp,$w=180,$h=180,$center=false){
		global $MYSQL, $COUNTRY;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbusers            ="pfx_users";
		$tbusrmobile        ="pfx_users_mobile";
		$inner              ="";

		if(is_email($user_wp))$where=" `".$tbusers."`.`email`='".$user_wp."'";
		elseif(is_mobile($user_wp)){
			$inner=" INNER JOIN `".$tbusrmobile."` ON `".$tbusrmobile."`.`user_wp`=`".$tbusers."`.`user_wp` ";
			$where=" `".$tbusrmobile."`.`mobile`='".$user_wp."'";
		}
		else $where=" `".$tbusers."`.`user_wp`=".varr_int($user_wp);

		$user=$MYSQL->query("
			SELECT `".$tbusers."`.`user_wp`, IFNULL(`".$tbusers."`.`birthday`, '') `birthday`, `".$tbusers."`.`birthdayview`, `".$tbusers."`.`email`, `".$tbusers."`.`url`, `".$tbusers."`.`status`, `".$tbusers."`.`marital_status`, `".$tbusers."`.`mobile`, `".$tbusers."`.`lastname`, `".$tbusers."`.`firstname`, `".$tbusers."`.`otchestvo`, IFNULL(`".$tbusers."`.`zvezda`, 0) `zvezda`, IFNULL(`".$tbusers."`.`sex`, 0) `sex`, IFNULL(`".$tbusers."`.`pravo`, '') `pravo`, `".$tbusers."`.`town_id`, `".$tbusers."`.`status_chat`, UNIX_TIMESTAMP(`".$tbusers."`.`online`) `online`
			FROM `".$tbusers."`
			".$inner."
			WHERE ".$where
		);

		if(is_array($user) && count($user)==1){
			$photo=ShowAvatar(array($user[0]['user_wp']),$w,$h,$center);
			if(is_array($photo))$photo=$photo[0]['avatar'];

			if(strlen($user[0]['birthday'])>0){
				$birthday=MyDataTime($user[0]['birthday'],'date');
				$year    =real_date_diff($user[0]['birthday']);
				//Кол-во времени до дня рождения
				$count_birthday=real_date_diff(MyDataTime($user[0]['birthday'],'dateUSA','+',0,0,$year[0]+1));
				if($user[0]['birthdayview'] == 0) $year[0] = "";
			}
			else{
				$year[0]=''; $birthday=''; $count_birthday='';
			}

			$country=$COUNTRY->ShowTree2($user[0]['town_id']);
			if(is_array($country)){
				foreach($country as $key=>$value){
					if($value['parent'] == 0){
						$country_id = $value['id'];
						$country_name = $value['name'];
					}
					else{
						$town_id = $value['id'];
						$town_name = $value['name'];
					}
				}
			}

			if($user[0]['online']<time()-(60*10) && $user_wp!=$_SESSION['WP_USER']['user_wp'])$user[0]['status_chat']=0;

			return array(
				'user_wp'       => $user[0]['user_wp'],
				'birthday'      => $birthday,
				'year'          => @$year[0],
				'countbday'     => $count_birthday,
				'email'         => $user[0]['email'],
				'mobile'        => $user[0]['mobile'],
				'sex'           => $user[0]['sex'],
				'lastname'      => htmlspecialchars(stripslashes(trim($user[0]['lastname']))),
				'firstname'     => htmlspecialchars(stripslashes(trim($user[0]['firstname']))),
				'otchestvo'     => htmlspecialchars(stripslashes(trim($user[0]['otchestvo']))),
				'photo'         => $photo,
				'zvezda'        => (int)$user[0]['zvezda'],
				'security'      => Security($user_wp,$user[0]['pravo']),
				//'online'      => $this->OnLine($user[0]['user_wp']),
				'status_chat'   => $user[0]['status_chat'],
				'country_id'    => @$country_id,
				'country_name'  => @$country_name,
				'town_id'       => @$town_id,
				'town_name'     => @$town_name,
				'url'           => $user[0]['url'],
				'status'        => $user[0]['status'],
				'marital_status'=> $user[0]['marital_status'],
			);
		}
	}

//!Уведомления — количество
	function CountUvedom(){
		global $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbuvedom           ="pfx_uvedomlenie";

		$count=$MYSQL->query("SELECT Count(*) FROM `".$tbuvedom."` WHERE `user_wp`=".varr_int($_SESSION['WP_USER']['user_wp'])." AND `view`=0");
		if($count[0]['count']==0)$count[0]['count']='';
		return $count[0]['count'];
	}

//!Уведомления — список
	function UvedomList(){
		global $MYSQL;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbuvedom           ='pfx_uvedomlenie';
		$tbuserdeystvie     ='pfx_users_deystvie';
		$tbdeystvie         ='pfx_deystvie';

		$list=$MYSQL->query("
			SELECT
				`".$tbuvedom."`.`id`,
				CONCAT(DATE_FORMAT(`".$tbuvedom."`.`data_add`, '%d.%m.%Y %H:%i')) AS `date`,
				`".$tbuvedom."`.`deystvie_id`,

				`".$tbuserdeystvie."`.`user_wp`,
				`".$tbuserdeystvie."`.`deystvie`,
				`".$tbuserdeystvie."`.`id_deystvie`,

				`".$tbdeystvie."`.`name`
			FROM `".$tbuvedom."` JOIN `".$tbuserdeystvie."` JOIN `".$tbdeystvie."`
			ON `".$tbuvedom."`.`deystvie_id`=`".$tbuserdeystvie."`.`id` AND `".$tbuserdeystvie."`.`deystvie`=`".$tbdeystvie."`.`deystvie_id`
			WHERE `".$tbuvedom."`.`user_wp`=".varr_int($_SESSION['WP_USER']['user_wp'])." AND `".$tbuvedom."`.`view`=0
			ORDER BY `".$tbuvedom."`.`data_add` DESC
		");
		return $list;
	}

//!Действия — добавление
	function AddDeystvie($user_from,$user_to,$deystvie,$id_deystvie,$privat=0){
		global $MYSQL;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		if(!isset($_SESSION['WP_USER']['user_wp']) or $_SESSION['WP_USER']['user_wp'] < min_user_wp) return 0;
		
		$tbdeystvie ="pfx_users_deystvie";
		$deystvie   =varr_int($deystvie);
		$id_deystvie=varr_int($id_deystvie);
		$privat     =varr_int($privat);
		$user_from  =varr_int($user_from);
		$user_to    =varr_int($user_to);

		if($user_from==0)$user_from=varr_int($_SESSION['WP_USER']['user_wp']);

		$id=$MYSQL->query("INSERT INTO `".$tbdeystvie."` (`data_add`, `user_wp`, `deystvie`, `id_deystvie`, `privat`) VALUES (now(), ".$user_from.", ".$deystvie.", ".$id_deystvie.", ".$privat.")");
		if($id>0 && $user_to>=min_user_wp && $privat==0){
			//$friends = $MYSQL->query("SELECT friend_wp FROM pfx_friends WHERE user_wp = $user_wp AND good=1");
			//if(is_array($friends)){
				//foreach($friends as $key=>$value){
					$MYSQL->query("INSERT INTO `pfx_uvedomlenie` (`data_add`, `user_wp`, `deystvie_id`) VALUES (now(), ".$user_to.", ".$id.")");
				//}
			//}
		}
		return $id;
	}



	function CountVisitor(){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbvisitor = "pfx_users_visitor";

	    $result = $MYSQL->query("SELECT Count(*) FROM $tbvisitor WHERE user_wp = ".(int)@$_SESSION['WP_USER']['user_wp']." AND IFNULL(view,0)=0");
		return $result[0]['count'];
	}


	function AddVisitor($user_wp){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    if(!isset($_SESSION['WP_USER']['user_wp'])) return '';
	    if($user_wp == $_SESSION['WP_USER']['user_wp']) return '';

	    $tbvisitor = "pfx_users_visitor";

	    $result = $MYSQL->query("SELECT id FROM $tbvisitor WHERE user_wp = '$user_wp' AND visitor_wp = ".(int)@$_SESSION['WP_USER']['user_wp']);
	    if(is_array($result) && $result[0]['id'] > 0){
	    	$MYSQL->query("UPDATE $tbvisitor SET data_visitor = now(), view=0 WHERE id=".$result[0]['id']);
	    } else {
	    	$MYSQL->query("INSERT INTO $tbvisitor (data_visitor,visitor_wp,user_wp) VALUES (now(),".(int)@$_SESSION['WP_USER']['user_wp'].",'$user_wp')");
	    }
	}



	function ShowVisitors(){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbvisitor = "pfx_users_visitor";
	    $tbusers   = "pfx_users";

	    $result = $MYSQL->query("SELECT $tbusers.user_wp, $tbusers.email, $tbusers.lastname, $tbusers.firstname, $tbvisitor.view, $tbvisitor.data_visitor
	                                 FROM $tbvisitor
	                                   INNER JOIN $tbusers ON $tbusers.user_wp = $tbvisitor.visitor_wp
	                                WHERE $tbvisitor.user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND data_visitor + INTERVAL 65 DAY > now()
	                                ORDER BY data_visitor DESC");
	    $MYSQL->query("UPDATE $tbvisitor SET view=1 WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);
	    return $result;
	}


	/*function CountDiscount(){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbusers_discount  = "pfx_users_discount";

		$result = $MYSQL->query("SELECT Count(*) FROM $tbusers_discount WHERE user_mobile='".@$_SESSION['WP_USER']['mobile']."'");
		return $result[0]['count'];
	}


	function Discount($shop_id=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbusers_discount  = "pfx_users_discount";

		$result = $MYSQL->query("SELECT discount FROM $tbusers_discount WHERE user_mobile='".@$_SESSION['WP_USER']['mobile']."' AND shop_id=".(int)$shop_id);
		if(is_array($result) && count($result) == 1){
		   return $result[0]['discount'];
		} else return 0;
	}


	function ShopsDiscount($rows=25){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$rows   = (int) $rows;
		$page   = (int) page()-1;
		$begin  = $page*$rows;

		$tbsk   = "pfx_shops";
		$tbusers_discount = "pfx_users_discount";

		$result = $MYSQL->query("SELECT COUNT(*) FROM $tbusers_discount WHERE user_mobile = '".$_SESSION['WP_USER']['mobile']."'");
        $_SESSION['count_all'] = $result[0]['count'];

        $result = $MYSQL->query("SELECT $tbsk.id, $tbsk.name, $tbsk.URL, $tbsk.logo, $tbusers_discount.discount
		                      FROM $tbsk
		                       INNER JOIN $tbusers_discount ON $tbusers_discount.shop_id = $tbsk.id
		                      WHERE $tbusers_discount.user_mobile = '".@$_SESSION['WP_USER']['mobile']."'
		                      LIMIT $begin,$rows");
		if(is_array($result))
        foreach($result as $key=>$shop){
        	$array[] = array(
        	  'id'       => $shop['id'],
        	  'Name'     => htmlspecialchars(stripslashes(trim($shop['name']))),
        	  'URL'      => htmlspecialchars(stripslashes(trim($shop['url']))),
        	  'Logo'     => $shop['logo'],
        	  'Silver'   => $shop['discount'],
        	  'Gold'     => $shop['discount'],
        	  'Platinum' => $shop['discount'],
        	);
        }
        return @$array;
	}	*/



	function ShowType(){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbtype  = "pfx_type";

		$result = $MYSQL->query("SELECT id, name_".LANG_SITE." name, dogovor FROM $tbtype WHERE active=1 ORDER BY sort");
		if(is_array($result)){
			foreach($result as $key=>$value){
				$ok = 0;
				if($value['dogovor'] == 1){
					$ok++;
				}
				if($ok > 0){
					$array[] = array(
					   'id'      => $value['id'],
					   'name'    => $value['name'],
					   'dogovor' => $value['dogovor'],
					   'count'   => $this->CountPodarki($value['id']),
					);
					$GLOBALS['PHP_FILE'] = __FILE__;
	                $GLOBALS['FUNCTION'] = __FUNCTION__;
				}
			}
		}
		return @$array;
	}


//!Подарки — количество
	function CountPodarki($type_id=0,$to_wp=0,$par=''){
		global $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		$tbhistorypay="pfx_historypay";
		$type_id     =varr_int($type_id);
		$to_wp       =varr_int($to_wp);
		$where       ="";

		if($to_wp==0)$to_wp=$_SESSION['WP_USER']['user_wp'];
		if($type_id>0)$where.=" AND `type_id`=".$type_id." ";

		switch($par){
			case 'recieved'://Выданные
				$where.=" AND IFNULL(`vidan`,0) = 1 "; break;
			case 'new'://Новые
				$where.=" AND `data` + INTERVAL ".day_podarok." DAY > now() AND IFNULL(`vidan`,0) <> 1 "; break;
			case 'expired'://Анулированные
				$where.=" AND `data` + INTERVAL ".day_podarok." DAY < now() AND IFNULL(`vidan`,0) <> 1 "; break;
			default://Все
				$where.="";//" AND (IFNULL(vidan,0) = 1 OR IFNULL(vidan,0) <> 1) ";
			break;
		}

		$count=$MYSQL->query("SELECT Count(*) FROM `".$tbhistorypay."` WHERE `statuspay_id`=3 AND `to_wp`=".$to_wp." ".$where);
		//if($count[0]['count']==0)$count[0]['count']='';
		return $count[0]['count'];//Количество подарков
	}


//!Подарки — список
	function ShowPodarki($type_id=0,$to_wp=0,$par='',$rows=21,$begin=0){
		global $MYSQL, $AKCIANAME;

		$rows        =varr_int($rows);
		$type_id     =varr_int($type_id);
		//$page        =varr_int($page-1);
		//$begin       =$page*$rows;
		$to_wp       =varr_int($to_wp);
		$where       ="";
		$tbhistorypay="pfx_historypay";
		$tbtype      ="pfx_type";
		$tbusers     ="pfx_users";

		//$_SESSION['count_all'] = $this->CountPodarki($type_id,$to_wp,$par);

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		$type_name=$MYSQL->query("SELECT `name_".LANG_SITE."` `name` FROM `".$tbtype."` WHERE `id`=".$type_id);
		if(is_array($type_name))$AKCIANAME=$type_name[0]['name'];
		else                    $AKCIANAME="";

		if($to_wp==0)$to_wp=$_SESSION['WP_USER']['user_wp'];
		if($type_id > 0)$where.=" AND `type_id`=".$type_id." ";

		switch($par){
			case 'recieved':$where.=" AND IFNULL(vidan,0)=1 "; break;
			case 'new'     :$where.=" AND `data` + INTERVAL ".day_podarok." DAY > now() AND IFNULL(vidan,0) <> 1 "; break;
			case 'expired' :$where.=" AND `data` + INTERVAL ".day_podarok." DAY < now() AND IFNULL(vidan,0) <> 1 "; break;
			default        :$where.=""; /*" AND (IFNULL(vidan,0) = 1 OR IFNULL(vidan,0) <> 1) ";*/ break;
		}

		$result=$MYSQL->query("
			SELECT `id`, `akcia_id`, `data`, `podarok`, `amount`, `from_wp`, `from_mobile`, IFNULL(`vidan`, 0) `vidan`, `privat`
			FROM `".$tbhistorypay."`
			WHERE `to_wp`=".$to_wp." AND `statuspay_id`=3 ".$where."
			ORDER BY `data` DESC
			LIMIT ".$begin.", ".$rows
		);

		if(is_array($result) && count($result)>0){
			foreach($result as $key=>$value){
				$from_user=$MYSQL->query("SELECT `lastname`, `firstname` FROM `".$tbusers."` WHERE `user_wp`=".(int)$value['from_wp']);
				$from_fio =trim($from_user[0]['firstname'].' '.$from_user[0]['lastname']);
				$info     =unserialize($value['podarok']);

				switch($type_id){
					case 0://Все
					case 5://Подарки
					case 6://Сертификаты
						$dataend =MyDataTime($value['data'],'date','+',day_podarok);
						$days_end=$dataend;
					break;
				}

				if($value['vidan']==1)                                                    $status='recieved';
				elseif(MyDataTime($value['data'],'dateUSA','+',day_podarok)<date('Y-m-d'))$status='expired';
				else                                                                      $status='new';

				$array[]=array(
					'id'      =>$value['id'],
					'privat'  =>$value['privat'],
					'akcia_id'=>$info['id'], //$value['akcia_id'],
					'header'  =>htmlspecialchars(stripslashes(trim($info['header']))),
					'amount'  =>$value['amount'],
					'currency'=>$info['currency'],
					'datapay' =>MyDataTime($value['data'],'date'),
					'dataend' =>$dataend,
					'days_end'=>real_date_diff(@date_format(date_create($days_end),"Y-m-d")),
					'from_wp' =>$value['from_wp'],
					'from_fio'=>htmlspecialchars(stripslashes(trim($from_fio))),
					'status'  =>$status,
				);
			}
			return @$array;
		}
	}


//!Подписки — количество подписок
	function CountPodpiska($user_wp=0){
		global $MYSQL;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$user_wp            =(int)$user_wp;
		if($user_wp==0)$user_wp=(int)$_SESSION['WP_USER']['user_wp'];
		$tbusers_podpiska   ="pfx_podpiska";
		$result=$MYSQL->query("SELECT Count(*) FROM `".$tbusers_podpiska."` WHERE `user_wp`=".$user_wp);
		return $result[0]['count'];
	}


//!Подписки — список подписок
	function ShowPodpiska($user_wp=0,$rows=21,$w=160,$h=104,$begin=0){
		global $MYSQL, $GROUPS;

		//$_SESSION['count_all']=$this->CountPodpiska($user_wp);
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$rows               =(int)$rows;
		//$page               =(int)page()-1;
		//$begin              =$page*$rows;
		$user_wp            =(int)$user_wp;
		$tbpodpiska         ="pfx_podpiska";
		$tbshop             ="pfx_shops";
		$tb_cat_to_shop     ="pfx_cat_to_shop";
		$tbpodpiska_groups  ="pfx_podpiska_groups";
		$tbpodpiska_type    ="pfx_podpiska_type";
		if($user_wp == 0)$user_wp=(int)$_SESSION['WP_USER']['user_wp'];

		$result=$MYSQL->query("
			SELECT `".$tbpodpiska."`.`id`, `".$tbpodpiska."`.`rss`, `".$tbpodpiska."`.`lenta`, `".$tbpodpiska."`.`email`, `".$tbpodpiska."`.`sms`, `".$tbpodpiska."`.`shop_id`, `".$tbshop."`.`name`
			FROM `".$tbpodpiska."`
			INNER JOIN `".$tbshop."` ON `".$tbshop."`.`id`=`".$tbpodpiska."`.`shop_id`
			WHERE `".$tbpodpiska."`.`user_wp`=".$user_wp."
			ORDER BY `".$tbpodpiska."`.`data` DESC
			LIMIT ".$begin.", ".$rows
		);
		if(is_array($result) && count($result)){
			foreach($result as $key=>$value){
				$value['groups']=$MYSQL->query("SELECT `group_id` FROM `".$tbpodpiska_groups."` WHERE `podpiska_id`=".(int)$value['id']);
				$result2        =$MYSQL->query("
					SELECT `pfx_categories`.`menu_level` FROM `".$tb_cat_to_shop."`
					INNER JOIN `pfx_categories` ON `pfx_categories`.`menu_id`=`".$tb_cat_to_shop."`.`cat_id`
					WHERE `pfx_categories`.`menu_level` <> 0 AND `".$tb_cat_to_shop."`.`shop_id`=".$value['shop_id']."
					GROUP BY `pfx_categories`.`menu_level`
				");

				if(is_array($result2)){
					require_once(path_modules.'ini.groups.php');
					foreach($result2 as $ke2=>$value2){
						$group=$GROUPS->ShowGroup($value2['menu_level']);
						$shop_groups[] = array(
							'id'  =>$value2['menu_level'],
							'name'=>$group[0]['name'],
						);
					}
				}

				$logo=ShowLogo(array($value['shop_id']),$w,$h,true);
				if(is_array($logo))$logo=$logo[0]['logo'];
				$logo_mini=ShowLogo(array($value['shop_id']),85,60,true);
				if(is_array($logo_mini))$logo_mini=$logo_mini[0]['logo'];

				$array[]=array(
					'id'            =>$value['id'],
					'send_email'    =>$value['email'],
					'send_sms'      =>$value['sms'],
					'send_lenta'    =>$value['lenta'],
					'send_rss'      =>$value['rss'],
					'shop_id'       =>$value['shop_id'],
					'shop_name'     =>htmlspecialchars(stripslashes(trim($value['name']))),
					'shop_logo'     =>$logo,
					'shop_logo_mini'=>$logo_mini,
					'shop_groups'   =>@$shop_groups,
				);
				unset($shop_groups);
			}
		}
		return @$array;
	}



	function CountLentaAkciaNew(){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbpodpiska    = "pfx_podpiska";
        $tbakcia       = "pfx_akcia";
        $tbakciaban    = "pfx_podpiska_ban";
        $tbpodpisgroup = "pfx_podpiska_groups";
        $tbakciaview   = "pfx_podpiska_view";

		$count = $MYSQL->query("SELECT Count(*) FROM $tbakcia
                                      INNER JOIN $tbpodpiska ON $tbpodpiska.shop_id = $tbakcia.shop_id
                                      INNER JOIN $tbpodpisgroup ON $tbpodpisgroup.podpiska_id = $tbpodpiska.id
                                    WHERE $tbpodpiska.user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND IFNULL($tbpodpiska.lenta,0) = 1 AND
                                    DATE_FORMAT($tbpodpiska.data - INTERVAL ".month_rss." MONTH,'%Y-%m-%d') <= DATE_FORMAT($tbakcia.adddata,'%Y-%m-%d') AND
                                    $tbakcia.group_id = $tbpodpisgroup.group_id AND
                                    $tbakcia.id NOT IN (SELECT $tbakciaban.akcia_id FROM $tbakciaban WHERE $tbakciaban.user_wp = ".(int)$_SESSION['WP_USER']['user_wp'].") AND
                                    $tbakcia.id NOT IN (SELECT $tbakciaview.akcia_id FROM $tbakciaview WHERE $tbakciaview.user_wp = ".(int)$_SESSION['WP_USER']['user_wp'].")");

		if($count[0]['count'] > 0)
		  return "<b style=\"color:red\">".$count[0]['count']."</b>";
		else
		  return $count[0]['count'];
	}


	function PodpiskaViewAdd($akcia_id){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    if(isset($_SESSION['WP_USER'])){
	    $tbakciaview   = "pfx_podpiska_view";
	    $count = $MYSQL->query("SELECT Count(*) FROM $tbakciaview WHERE user_wp = ".(int)@$_SESSION['WP_USER']['user_wp']." AND akcia_id=".(int)$akcia_id);
	    if($count[0]['count'] == 0){
	    	$MYSQL->query("INSERT INTO $tbakciaview (user_wp,akcia_id) VALUES (".(int)@$_SESSION['WP_USER']['user_wp'].",".(int)$akcia_id.")");
	    }
	    }
	}


	function CountLentaAkcia(){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbpodpiska    = "pfx_podpiska";
        $tbakcia       = "pfx_akcia";
        $tbakciaban    = "pfx_podpiska_ban";
        $tbpodpisgroup = "pfx_podpiska_groups";

		$count = $MYSQL->query("SELECT Count(*) FROM $tbakcia
                                      INNER JOIN $tbpodpiska ON $tbpodpiska.shop_id = $tbakcia.shop_id
                                      INNER JOIN $tbpodpisgroup ON $tbpodpisgroup.podpiska_id = $tbpodpiska.id
                                    WHERE $tbpodpiska.user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND IFNULL($tbpodpiska.lenta,0) = 1 AND
                                    DATE_FORMAT($tbpodpiska.data - INTERVAL ".month_rss." MONTH,'%Y-%m-%d') <= DATE_FORMAT($tbakcia.adddata,'%Y-%m-%d') AND
                                    $tbakcia.group_id = $tbpodpisgroup.group_id AND
                                    $tbakcia.id NOT IN (SELECT $tbakciaban.akcia_id FROM $tbakciaban WHERE $tbakciaban.user_wp = ".(int)$_SESSION['WP_USER']['user_wp'].")");
		return $count[0]['count'];
	}



	function LentaAkcia($rows=21,$page=1){
		global $MYSQL;

		$_SESSION['count_all'] = $this->CountLentaAkcia();

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $rows   = varr_int($rows);
		$page   = varr_int($page-1);
		$begin  = $page*$rows;

		$tbpodpiska    = "pfx_podpiska";
        $tbakcia       = "pfx_akcia";
        $tbtype        = "pfx_type";
        $tbcurrency    = "pfx_currency";
        $tbpodpisgroup = "pfx_podpiska_groups";
        $tbshops       = "pfx_shops";
        $tbakciaban    = "pfx_podpiska_ban";

	    $result = $MYSQL->query("SELECT $tbakcia.id, $tbakcia.header, $tbakcia.adddata, $tbakcia.amount, $tbtype.name_".LANG_SITE." type, $tbakcia.idtype, $tbakcia.discdata1, $tbakcia.discdata2, $tbshops.id shop_id, $tbshops.name shop_name, IFNULL($tbakcia.currency_id,0) currency_id, $tbtype.dogovor, $tbtype.img_small type_img, $tbakcia.mtext
	                               FROM $tbakcia
                                     INNER JOIN $tbpodpiska ON $tbpodpiska.shop_id = $tbakcia.shop_id
                                     INNER JOIN $tbtype ON $tbtype.id = $tbakcia.idtype
                                     INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
                                     INNER JOIN $tbpodpisgroup ON $tbpodpisgroup.podpiska_id = $tbpodpiska.id
                                    WHERE $tbpodpiska.user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND IFNULL($tbpodpiska.lenta,0) = 1 AND
                                          DATE_FORMAT($tbpodpiska.data - INTERVAL ".month_rss." MONTH,'%Y-%m-%d') <= DATE_FORMAT($tbakcia.adddata,'%Y-%m-%d') AND

                                          $tbakcia.id NOT IN (SELECT $tbakciaban.akcia_id FROM $tbakciaban WHERE $tbakciaban.user_wp = ".(int)$_SESSION['WP_USER']['user_wp'].")
                                    ORDER BY $tbakcia.adddata DESC
                                    LIMIT $begin,$rows");

	    if(is_array($result)){
	    	foreach($result as $key=>$value){

	    		$currency = $MYSQL->query("SELECT currency, mask FROM $tbcurrency WHERE id=".$value['currency_id']);
	    		if(is_array($currency) && count($currency) == 1)
        	       $currency = $currency[0]['mask'];
        	    else
        	       $currency = '';

	    		$array[] = array(
	    		   'akcia_id'  => $value['id'],
	    		   'shop_id'   => $value['shop_id'],
	    		   'shop_name' => htmlspecialchars(stripslashes(trim($value['shop_name']))),
	    		   'header'    => htmlspecialchars(stripslashes(trim($value['header']))),
	    		   'mtext'     => substr(htmlspecialchars(stripslashes(trim($value['mtext']))),0,350).' ...<br /><br />',
	    		   'amount'    => $value['amount'],
	    		   'dogovor'   => $value['dogovor'],
	    		   'currency'  => $currency,
	    		   'type'      => $value['type'],
	    		   'type_id'   => $value['idtype'],
	    		   'type_img'  => $value['type_img'],
	    		   'dataadd'   => $value['adddata'],
	    		   'datastart' => MyDataTime($value['discdata1'],'date'),
        	       'datastop'  => MyDataTime($value['discdata2'],'date'),
	    		);
	    	}
	    }
	    return @$array;
	}


	function MneNravitca($akcia_id){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $akcia_id = (int) $akcia_id;
	    $tbusers_deystvie = "pfx_users_deystvie";

	    $result = $MYSQL->query("SELECT Count(*) FROM $tbusers_deystvie WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND deystvie=5 AND id_deystvie = $akcia_id");

	    if($result[0]['count'] == 0)
	     $this->AddDeystvie(0,0,5,$akcia_id);
	     //$MYSQL->query("INSERT INTO $tbusers_deystvie (data_add,user_wp,deystvie,id_deystvie) VALUES (now(),".(int)$_SESSION['WP_USER']['user_wp'].",5,$akcia_id)");
	}


	function MneNeNravitca($akcia_id){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $akcia_id = (int) $akcia_id;
	    $tbusers_deystvie = "pfx_users_deystvie";

	    $result = $MYSQL->query("SELECT id FROM $tbusers_deystvie WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND deystvie=5 AND id_deystvie = $akcia_id");

	    if(is_array($result) && $result[0]['id'] > 0)
	     $MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".(int)$result[0]['id']);
	}

//!Друзья — количество
	function CountFriends($new=0,$user_wp=0,$online=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbfriends          ="pfx_users_friends";
		$tbusers            ="pfx_users";
		$user_wp            =(int)$user_wp;

		if($user_wp==0)$user_wp=(int)$_SESSION['WP_USER']['user_wp'];

		if  ($online==1)$where=" AND (`".$tbusers."`.`online` + INTERVAL 10 MINUTE > now()) AND `".$tbusers."`.`status_chat`!=0";
		else            $where="";

		switch($new){
			case 1:
				$result=$MYSQL->query("SELECT Count(*) FROM `".$tbfriends."` WHERE `friend_wp`=".$user_wp." AND IFNULL(`good`,0)=0");
			break;

			default:
				$result=$MYSQL->query("SELECT Count(*) FROM `".$tbfriends."` INNER JOIN `".$tbusers."` ON `".$tbusers."`.`user_wp`=`".$tbfriends."`.`friend_wp` WHERE `".$tbfriends."`.`user_wp`=".$user_wp." AND `good`=1 ".$where);
			break;
		}

		//if($new==1 && $result[0]['count']>0)return '<b style="color:red">'.$result[0]['count'].'</b>';
		//else                                return $result[0]['count'];
		return $result[0]['count'];
	}

	function CountFriendsInCircle($new=0,$user_wp=0,$online=0,$circle=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbfriends          ="pfx_users_friends";
		$tbkrugi            ="pfx_users_krugi";
		$tbusers            ="pfx_users";
		$user_wp            =(int)$user_wp;

		if($user_wp==0)$user_wp=(int)$_SESSION['WP_USER']['user_wp'];

		if  ($online==1)$where=" AND (`".$tbusers."`.`online` + INTERVAL 10 MINUTE > now()) AND `".$tbusers."`.`status_chat`!=0";
		else            $where="";

		if($circle>0)
			$inner='INNER JOIN `'.$tbkrugi.'` ON `'.$tbkrugi.'`.`friends_id`=`'.$tbfriends.'`.`id` WHERE `'.$tbkrugi.'`.`krug_id`=\''.$circle.'\' AND';
		else
			$inner='WHERE';

		switch($new){
			case 1:
				$result=$MYSQL->query("SELECT Count(*) FROM `".$tbfriends."` ".$inner." `friend_wp`=".$user_wp." AND IFNULL(`good`,0)=0");
			break;

			default:
				$result=$MYSQL->query("SELECT Count(*) FROM `".$tbfriends."` INNER JOIN `".$tbusers."` ON `".$tbusers."`.`user_wp`=`".$tbfriends."`.`friend_wp` ".$inner." `".$tbfriends."`.`user_wp`=".$user_wp." AND `good`=1 ".$where);
			break;
		}

		//if($new==1 && $result[0]['count']>0)return '<b style="color:red">'.$result[0]['count'].'</b>';
		//else                                return $result[0]['count'];
		return $result[0]['count'];
	}

//!Друзья — друг или нет?
	function IsFriend($friend_wp){
		global $MYSQL;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$friend_wp          =varr_int($friend_wp);
		$tbfriends          ="pfx_users_friends";

		$friendship=$MYSQL->query("
			SELECT `id` FROM `".$tbfriends."`
			WHERE (`user_wp`=".(int)$_SESSION['WP_USER']['user_wp']." AND `friend_wp`=".$friend_wp.")
				OR (`user_wp`=".$friend_wp." AND `friend_wp`=".(int)$_SESSION['WP_USER']['user_wp'].")
				AND `good`=1
		");

		if(is_array($friendship) && count($friendship)==2)return true;
		return false;
	}


	function IsFriendAction($friend_wp){ // Могу ли добавить в друзья
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbfriends = "pfx_users_friends";
	    $friend_wp = varr_int($friend_wp);

	    if($_SESSION['WP_USER']['user_wp'] == $friend_wp) return 0; // Я не могу себя добавить в друзья

	    $friendship = $MYSQL->query("SELECT good, user_wp, friend_wp FROM $tbfriends WHERE (user_wp = ".varr_int($_SESSION['WP_USER']['user_wp'])." AND friend_wp = $friend_wp)
	                                 OR (user_wp = $friend_wp AND friend_wp = ".varr_int($_SESSION['WP_USER']['user_wp']).")");

	    if(!is_array($friendship)){ // Если не приглашали ни я ни он, то я могу пригласить
	    	return 1; // Могу пригласить
	    }
	    $friendship = $friendship[0];

	    if($friendship['good'] == 0){ // Приглашение есть, но еще не подтверждено
	    	if($friendship['user_wp'] == varr_int($_SESSION['WP_USER']['user_wp'])){ // Если я пригласил
	    		return 2; // Приглашение отправленно мной
            }
	    elseif($friendship['user_wp'] == $friend_wp){ // Если меня пригласили
	    	    return 3; // Подтвердить приглашение
	        }
	    }
	elseif($friendship['good'] == 1){ // иначе если друзья
	    	return 4; // Могу удалить из друзей
	    }
	}

	function FriendIsCircle($friend_wp){ // Друг в моих кругах
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $friend_wp = varr_int($friend_wp);
	    $tbcircles = "pfx_krugi";
	    $tbfriends = "pfx_users_friends";
	    $tbfriendscircle = "pfx_users_krugi";

	    if($this->IsFriend($friend_wp)){
	    	$circle = $MYSQL->query("SELECT $tbfriendscircle.krug_id, $tbcircles.name_".LANG_SITE." name FROM $tbfriendscircle
	    	                          INNER JOIN $tbfriends ON $tbfriends.id = $tbfriendscircle.friends_id
	    	                          INNER JOIN $tbcircles ON $tbcircles.krug_id = $tbfriendscircle.krug_id
	    	                         WHERE $tbfriends.user_wp = ".varr_int($_SESSION['WP_USER']['user_wp'])." AND $tbfriends.friend_wp = $friend_wp");
	    	if(!is_array($circle)){
	    		$friend_id = $MYSQL->query("SELECT id FROM $tbfriends WHERE user_wp = ".varr_int($_SESSION['WP_USER']['user_wp'])." AND friend_wp = $friend_wp");
	    		if(is_array($friend_id)){
	    		    $MYSQL->query("INSERT INTO $tbfriendscircle (friends_id,krug_id) VALUES (".varr_int($friend_id[0]['id']).",2)");
	    		    return $this->FriendIsCircle($friend_wp);
	    		}
	    	}
	    	return $circle;
	    }
	}

	function IFriendIsCircle($friend_wp){ // Я в кругах друга
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $friend_wp = varr_int($friend_wp);
	    $tbfriends = "pfx_users_friends";
	    $tbfriendscircle = "pfx_users_krugi";

	    if($this->IsFriend($friend_wp)){
	    	$circle = $MYSQL->query("SELECT $tbfriendscircle.krug_id FROM $tbfriendscircle
	    	                          INNER JOIN $tbfriends ON $tbfriends.id = $tbfriendscircle.friends_id
	    	                         WHERE $tbfriends.user_wp = $friend_wp AND $tbfriends.friend_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
	    	if(!is_array($circle)){
	    		$friend_id = $MYSQL->query("SELECT id FROM $tbfriends WHERE user_wp = $friend_wp AND friend_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
	    		if(is_array($friend_id)){
	    		    $MYSQL->query("INSERT INTO $tbfriendscircle (friends_id,krug_id) VALUES (".varr_int($friend_id[0]['id']).",2)");
	    		    return $this->IFriendIsCircle($friend_wp);
	    		}
	    	}
	    	return $circle;
	    }
	}

//!Друзья — список друзей
	function ShowMyFriends($user_wp=0,$rows=0,$order="",$online=0,$begin=0,$circle=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbfriends          ="pfx_users_friends";
		$tbusers            ="pfx_users";
		$tbfriendscircle    ="pfx_users_krugi";
		$rows               =varr_int($rows);
		//$page               =varr_int($page-1); if($page < 0) $page = 0;
		//$begin              =varr_int($page*$rows);
		$online             =varr_int($online);
		$user_wp            =varr_int($user_wp);
		$circle             =varr_int($circle);

		if($rows == 0)$limit = ""; else $limit = " LIMIT ".$begin.", ".$rows;

		switch($order){
			case 'asc'     :$order=" `".$tbfriends."`.`data_add` "; break;
			case 'desc'    :$order=" `".$tbfriends."`.`data_add` DESC"; break;
			case 'fio_up'  :$order=" `".$tbusers."`.`firstname` ASC "; break;
			case 'lastname':$order=" `".$tbusers."`.`lastname` ASC "; break;
			default        :$order=" RAND()"; break;
		}

		if($online==1)$where=" AND (`".$tbusers."`.`online` + INTERVAL 10 MINUTE > now())";
		else          $where="";

		if($user_wp==0)$user_wp=(int)@$_SESSION['WP_USER']['user_wp'];

		$inner="";
		if($circle>=2){
			$inner ="INNER JOIN `".$tbfriendscircle."` ON `".$tbfriendscircle."`.`friends_id`=`".$tbfriends."`.`id`";
			$where.=" AND `".$tbfriendscircle."`.`krug_id`=".$circle." ";
		}

		$result=$MYSQL->query("
			SELECT `".$tbusers."`.`user_wp`, `".$tbfriends."`.`id` `fid` FROM `".$tbfriends."`
			INNER JOIN `".$tbusers."` ON `".$tbusers."`.`user_wp`=`".$tbfriends."`.`friend_wp` ".$inner."
			WHERE `".$tbfriends."`.`user_wp`=".$user_wp." AND `".$tbfriends."`.`good`=1 ".$where."
			ORDER BY ".$order." ".$limit
		);
		if(is_array($result)){
			foreach($result as $key=>$value){
				$array[]=$this->Info_min($value['user_wp'],0,0);
				$array[$key]['fid']  =$value['fid'];//id друга в таблице с друзьями
				$array[$key]['krugi']=$MYSQL->query("SELECT `krug_id` FROM `pfx_users_krugi` WHERE `friends_id`='".$value['fid']."'");
				for($ki=0; $ki<count($array[$key]['krugi']); $ki++){
					$array[$key]['krugi'][$ki]=$array[$key]['krugi'][$ki]['krug_id'];
				}
			}
		}
		return @$array;
	}

//!Друзья — новые друзей
	function ShowNewFriends(){
		global $MYSQL, $COUNTRY;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbfriends          ="pfx_users_friends";
		$tbusers            ="pfx_users";

		$result=$MYSQL->query("
			SELECT `".$tbusers."`.`user_wp`, `".$tbusers."`.`email`, `".$tbusers."`.`lastname`, `".$tbusers."`.`firstname`, `".$tbusers."`.`otchestvo`, `".$tbusers."`.`town_id`, `".$tbusers."`.`status_chat`, UNIX_TIMESTAMP(`".$tbusers."`.`online`) `online`
			FROM `".$tbfriends."`
			INNER JOIN `".$tbusers."` ON `".$tbusers."`.`user_wp`=`".$tbfriends."`.`user_wp`
			WHERE `".$tbfriends."`.`friend_wp`=".(int)$_SESSION['WP_USER']['user_wp']." AND `".$tbfriends."`.`good` <> 1
			ORDER BY `".$tbfriends."`.`data_add`
		");

		if(is_array($result)){
			if($result[0]['online']<time()-(60*10) && $result[0]['user_wp']!=$_SESSION['WP_USER']['user_wp'])$result[0]['status_chat']=0;
	
			$country=$COUNTRY->ShowTree2($result[0]['town_id']);
			if(is_array($country)){
				foreach($country as $key=>$value){
					if($value['parent'] == 0){
						$country_id = $value['id'];
						$country_name = $value['name'];
					}
					else{
						$town_id = $value['id'];
						$town_name = $value['name'];
					}
				}
			}
			$result[0]['country_id']  =@$country_id;
			$result[0]['country_name']=@$country_name;
			$result[0]['town_id']     =@$town_id;
			$result[0]['town_name']   =@$town_name;
	
	
			return $result;
		}
	}

//!Друзья — поиск
	function SearchFriends($fio,$par=0,$rows=21,$page=1,$circle=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbfriends = "pfx_users_friends";
	    $tbusers   = "pfx_users";
	    $tbuserstars = "pfx_users_stars";
	    $tbfriendscircle = "pfx_users_krugi";
	    $where = "";
	    $par     = varr_int($par);
	    $rows    = varr_int($rows);
		$page    = varr_int($page-1);
		$begin   = varr_int($page*$rows);
		$circle  = varr_int($circle);

	    $fio_ru = $fio;
	    //$fio_en = translit_ru($fio_ru);

	    $fio_array = explode(" ",$fio_ru);
	    if(is_array($fio_array)){
	       $i=0;
	       foreach($fio_array as $key=>$value){
	       	 $i++;
	       	 if($i%2){} else @$where .= " OR ";
	       	   $where .= "$tbusers.lastname LIKE '$value%' OR $tbusers.firstname LIKE '$value%'";
	       }
	    }

		if($rows!=0) $limit = " LIMIT $begin,$rows"; else $limit = "";

	    switch($par){
	    	case 0: // Поиск всех пользователей
	    		return $MYSQL->query("SELECT DISTINCT $tbusers.user_wp, $tbusers.email, $tbusers.lastname, $tbusers.firstname, $tbusers.otchestvo, IFNULL($tbusers.zvezda,0) zvezda
	                                         FROM $tbusers
	                                         WHERE $tbusers.user_wp <> ".(int)$_SESSION['WP_USER']['user_wp']." AND
	                                         ($where)
											 $limit
	                                         ");
	    	break;

	    	case 1: // Поиск в списке друзей
	    	    $inner = ""; $where2 = "";
	    	    if($circle >= 2){
	    	    	$inner  = "INNER JOIN $tbfriendscircle ON $tbfriendscircle.friends_id = $tbfriends.id";
	    	    	$where2 = " AND $tbfriendscircle.krug_id = $circle ";
	    	    }
	    		return $MYSQL->query("SELECT DISTINCT $tbusers.user_wp, $tbusers.email, $tbusers.lastname, $tbusers.firstname, $tbusers.otchestvo, $tbfriends.good, IFNULL($tbusers.zvezda,0) zvezda
	                                         FROM $tbfriends
	                                          INNER JOIN $tbusers ON $tbusers.user_wp = $tbfriends.friend_wp
	                                          $inner
	                                         WHERE $tbfriends.user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND $tbfriends.good=1 AND
	                                         ($where) $where2
							                 $limit
	                                         ");
	    	break;

	    	case 2: // Поиск в списке ЗВЕЗД
	    	    return $MYSQL->query("SELECT DISTINCT $tbusers.user_wp, $tbusers.email, $tbusers.lastname, $tbusers.firstname, $tbusers.otchestvo, IFNULL($tbusers.zvezda,0) zvezda
	                                         FROM $tbusers
	                                         WHERE IFNULL($tbusers.zvezda,0) = 1 AND
	                                         ($where)
											 $limit
	                                         ");
	    	break;

	    	default: // Поиск по звезным категориям
	    		return $MYSQL->query("SELECT DISTINCT $tbusers.user_wp, $tbusers.email, $tbusers.lastname, $tbusers.firstname, $tbusers.otchestvo, IFNULL($tbusers.zvezda,0) zvezda
	                                         FROM $tbusers
	    		                            INNER JOIN $tbuserstars ON $tbuserstars.user_wp = $tbusers.user_wp
	                                         WHERE IFNULL($tbusers.zvezda,0) = 1 AND $tbuserstars.star_id = $par AND
	                                         ($where)
											 $limit
	                                         ");
	    	break;

	    }
	}


	function CountObshieFriends($user_wp=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbfriends = "pfx_users_friends";
	    $tbusers   = "pfx_users";
	    $user_wp = (int) $user_wp;

	    if($user_wp == 0) $user_wp = (int)@$_SESSION['WP_USER']['user_wp'];

	    $count = $MYSQL->query("SELECT Count(*)
	                                 FROM $tbfriends
	                                WHERE $tbfriends.user_wp = $user_wp AND $tbfriends.good=1 AND
	                                $tbfriends.friend_wp IN (SELECT $tbfriends.friend_wp FROM $tbfriends WHERE $tbfriends.user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND $tbfriends.good=1)
	                                ");
	    return (int) $count[0]['count'];
	}


	function ShowObshieFriends($user_wp=0,$rows=6){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbfriends = "pfx_users_friends";
	    $tbusers   = "pfx_users";

	    $rows    = (int) $rows;
		$page    = (int) page()-1;
		$begin   = $page*$rows;
		$user_wp = (int) $user_wp;

		if($user_wp == (int)@$_SESSION['WP_USER']['user_wp']) return '';
	    if($user_wp == 0) $user_wp = (int)@$_SESSION['WP_USER']['user_wp'];

	    return $MYSQL->query("SELECT $tbusers.user_wp, $tbusers.email, $tbusers.lastname, $tbusers.firstname, $tbusers.otchestvo
	                                 FROM $tbfriends
	                                   INNER JOIN $tbusers ON $tbusers.user_wp = $tbfriends.friend_wp
	                                WHERE $tbfriends.user_wp = $user_wp AND $tbfriends.good=1 AND
	                                $tbfriends.friend_wp IN (SELECT $tbfriends.friend_wp FROM $tbfriends WHERE $tbfriends.user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND $tbfriends.good=1)
	                                LIMIT $begin,$rows");
	}


//!Друзья — добавление друга
	function AddFriend($friend_wp){
		global $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbfriends          ='pfx_users_friends';
		$friend_wp          =varr_int($friend_wp);

		$friendship=$MYSQL->query("
			SELECT `good`
			FROM `".$tbfriends."`
			WHERE (`user_wp`=".varr_int($_SESSION['WP_USER']['user_wp'])." AND `friend_wp`=".$friend_wp.") OR (`user_wp`=".$friend_wp." AND `friend_wp`=".varr_int($_SESSION['WP_USER']['user_wp']).")
		");

		if(!is_array($friendship)){//Можно пригласить в друзья
			$id=$MYSQL->query("INSERT INTO `".$tbfriends."` (`data_add`, `user_wp`, `friend_wp`, `good`) VALUES (now(), ".varr_int($_SESSION['WP_USER']['user_wp']).", ".$friend_wp.", 0)");
			$this->AddDeystvie(0,$friend_wp,9,$id);

			if($id>0){
				$friend=$this->Info_min($friend_wp,0,0);
				if(is_array($friend)){
					$Msg ='Здравствуйте, '.trim($friend['firstname'].' '.$friend['lastname']).'<br /><br />';
					$Msg.='Пользователь '.trim($_SESSION['WP_USER']['firstname'].' '.$_SESSION['WP_USER']['lastname']).' хочет добавить Вас в свой список друзей<br />';
					$Msg.='Посмотреть профиль пользователя '.sys_url.$_SESSION['WP_USER']['user_wp'].'<br />';
					$Msg.='С Уважением, '.sys_copy.' '.sys_url.'<br />';
					if(send_mail($friend['email'], $Msg, 'У Вас новый друг'))return true;
					return true;
				}
				return true;
			}
		}
		return false;
	}

//!Друзья — подтверждение дружбы
	function PodtverditFriend($friend_wp, $my_wp=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		$tbusers_deystvie="pfx_users_deystvie";
		$tbfriends       ="pfx_users_friends";
		$friend_wp       =varr_int($friend_wp);
		if($my_wp==0)$my_wp=$_SESSION['WP_USER']['user_wp'];

		$id=$MYSQL->query("INSERT INTO `".$tbfriends."` (`data_add`, `user_wp`, `friend_wp`, `good`) VALUES (now(), ".varr_int($my_wp).", ".$friend_wp.", 0)");
		if($id>0){
			$MYSQL->query("
				UPDATE `".$tbfriends."` SET `good`=1
				WHERE (`user_wp`=".$friend_wp." AND `friend_wp`=".varr_int($my_wp).")
					OR (`user_wp`=".varr_int($my_wp)." AND `friend_wp`=".$friend_wp.")");
			//$MYSQL->query("INSERT INTO $tbusers_deystvie (data_add,user_wp,deystvie,id_deystvie) VALUES (now(),$friend_wp,1,".varr_int($_SESSION['WP_USER']['user_wp']).")");
			$this->AddDeystvie($friend_wp,0,1,varr_int($my_wp));

			$friend=$this->Info_min($friend_wp,0,0);
			if(is_array($friend)){
				$Msg ='Здравствуйте, '.trim($friend['firstname'].' '.$friend['lastname']).'<br /><br />';
				$Msg.='Пользователь '.trim(@$_SESSION['WP_USER']['firstname'].' '.@$_SESSION['WP_USER']['lastname']).' подтвердил, что Вы являетесь друзьями<br />';
				$Msg.='Посмотреть профиль пользователя '.sys_url.varr_int($my_wp).'<br /><br />';
				$Msg.='С Уважением, '.sys_copy.' '.sys_url.'<br />';
				/*if(send_mail($friend['email'], $Msg, 'Подтверждение дружбы')){
					return true;
				}*/
				return true;
			}
		}
		return false;
	}


	function DeleteFriend($friend_wp){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbfriends = "pfx_users_friends";
	    $tbusers_deystvie = "pfx_users_deystvie";
	    $tbfriendscircles = "pfx_users_krugi";
	    $friend_wp = varr_int($friend_wp);

	    $friend_id = $MYSQL->query("SELECT id FROM $tbfriends WHERE (user_wp = ".varr_int($_SESSION['WP_USER']['user_wp'])." AND friend_wp = $friend_wp)
	                                 OR (user_wp = $friend_wp AND friend_wp = ".varr_int($_SESSION['WP_USER']['user_wp']).")");

	    if(is_array($friend_id)){
	       foreach($friend_id as $key=>$value){
	       	  $MYSQL->query("DELETE FROM $tbfriendscircles WHERE friends_id = ".$value['id']); // Удаляем круги
	       	  $MYSQL->query("DELETE FROM $tbfriends WHERE id = ".$value['id']); // Удаляем дружбу
	       }
           // Удаляем с ленты событий информацию о дружбе
	       $MYSQL->query("DELETE FROM $tbusers_deystvie WHERE (user_wp = ".varr_int($_SESSION['WP_USER']['user_wp'])." AND id_deystvie = $friend_wp)
	                        OR (id_deystvie = ".varr_int($_SESSION['WP_USER']['user_wp'])." AND user_wp = $friend_wp)
	                        AND deystvie = 1");
	       return true;
	    }
	    return false;
	}


//!Кумиры — количество
	function CountStars(){
		global $MYSQL;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbusers            ="pfx_users";
		$count=$MYSQL->query("SELECT Count(*) FROM `".$tbusers."` WHERE IFNULL(`zvezda`,0)=1");
		return $count[0]['count'];
	}


	/*-----ShowStars-----*/
	function ShowStars($zvezda_group_id=0,$rows=0,$page=1,$order="fio_up",$online=0){
		global $MYSQL;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		$tbusers        ='pfx_users';
		$tbuserstars    ='pfx_users_stars';
		$zvezda_group_id=varr_int($zvezda_group_id);
		$rows           =varr_int($rows);
		$page           =varr_int($page-1);
		$begin          =varr_int($page*$rows);
		$online         =varr_int($online);
		$distinct       ='';

		if($rows==0)$limit="";
		else $limit=" LIMIT ".$begin.", ".$rows;

		switch($order){
			case 'fio_up':$order=" `".$tbusers."`.`lastname` ASC "; break;
			default      :$order=" RAND()"; break;
		}

		if($online==1)$where=" AND (`".$tbusers."`.`online` + INTERVAL 10 MINUTE > now())";
		else          $where="";

		if($zvezda_group_id>0)$where.=" AND `".$tbuserstars."`.`star_id`=`".$zvezda_group_id."`";
		else $distinct="distinct";

		return $MYSQL->query("
			SELECT ".$distinct." `".$tbusers."`.`user_wp`, `".$tbusers."`.`lastname`, `".$tbusers."`.`firstname`
			FROM `".$tbusers."`
			INNER JOIN `".$tbuserstars."` ON `".$tbuserstars."`.`user_wp`=`".$tbusers."`.`user_wp`
			WHERE IFNULL(`".$tbusers."`.`zvezda`, 0)=1 ".$where."
			ORDER BY ".$order."
			".$limit."
		");
	}


	function CountHistoryLenta($user_wp,$circle=1){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbdeystvie       = "pfx_deystvie";
	    $tbuserskrugi     = "pfx_users_krugi";
	    $tbfriends        = "pfx_users_friends";
	    $tbusers_deystvie = "pfx_users_deystvie";
	    $tbsettings_deystvie = "pfx_users_settings_lenta";

	    $user_wp = varr_int($user_wp);
	    $circle  = varr_int($circle);

	    // Заполняем таблицу настроек ленты событий пользователя (например: показывать только подарки, дружбу, фотоальбомы и т.п.)
	    /*$deystvie = $MYSQL->query("SELECT Count(*) FROM $tbsettings_deystvie WHERE user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
	    if(is_array($deystvie) && $deystvie[0]['count'] == 0){
	       $deystvie = $MYSQL->query("SELECT * FROM $tbdeystvie ORDER BY deystvie_id");
	       foreach($deystvie as $key=>$value)
	       	 $MYSQL->query("INSERT INTO $tbsettings_deystvie (deystvie_id,user_wp) VALUES (".$value['deystvie_id'].",".varr_int($_SESSION['WP_USER']['user_wp']).")");
	    }*/

	    // Круги
	    switch($circle){
	    	case 1: // Моя страница
	    		$where = "$tbusers_deystvie.user_wp = $user_wp ";
	    		//OR ($tbusers_deystvie.deystvie = 1 AND $tbusers_deystvie.id_deystvie = $user_wp)
	    	break;

	    	default:
	    		$inner = "INNER JOIN $tbuserskrugi ON $tbuserskrugi.krug_id = $circle";
	    		$where = "$tbusers_deystvie.user_wp IN (SELECT $tbfriends.friend_wp FROM $tbfriends $inner WHERE $tbfriends.user_wp = $user_wp AND $tbfriends.good=1 AND $tbfriends.id = $tbuserskrugi.friends_id)";
	    	break;
	    }

	    $sql = "SELECT Count(*) FROM $tbusers_deystvie
	             /* INNER JOIN $tbsettings_deystvie ON $tbsettings_deystvie.user_wp = ".varr_int($_SESSION['WP_USER']['user_wp'])." */
	            WHERE $where /* AND $tbsettings_deystvie.deystvie_id = $tbusers_deystvie.deystvie */ AND $tbusers_deystvie.privat = 0";

	    $count = $MYSQL->query($sql);
	    return $count[0]['count'];
	}


	function ShowHistoryLenta($user_wp,$circle=1,$rows=15,$begin=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbusers_deystvie = "pfx_users_deystvie";
		$tbphotoalbum     = "pfx_users_photos_album";
		$tbuserphotos     = "pfx_users_photos";
		$tbfriends        = "pfx_users_friends";
		$tbevent          = "pfx_users_event";
		$tbpodpiska       = "pfx_podpiska";
		$tbcountry        = "pfx_country";
		$tbshops          = "pfx_shops";
		$tbshopadres      = "pfx_shops_adress";
		$tbusers          = "pfx_users";
		$tbhistorypay     = "pfx_historypay";
		$tbakcia          = "pfx_akcia";
		$tbhochu          = "pfx_users_hochu";
		$tbdeystvie       = "pfx_deystvie";
		$tbsettings_deystvie = "pfx_users_settings_lenta";
		$tbuserskrugi     = "pfx_users_krugi";

	    $user_wp = varr_int($user_wp);
	    $circle  = varr_int($circle);
	    $rows    = varr_int($rows);
		//$page    = varr_int($page-1);
		//$begin   = varr_int($page*$rows);
		$begin   = varr_int($begin);
	    $inner   = "";
	    $where   = "";

	    // Круги
	    switch($circle){
	    	case 1: // Моя страница
	    		$where = "$tbusers_deystvie.user_wp = $user_wp ";
	    		//OR ($tbusers_deystvie.deystvie = 1 AND $tbusers_deystvie.id_deystvie = $user_wp)
	    	break;

	    	default:
	    		$inner = "INNER JOIN $tbuserskrugi ON $tbuserskrugi.krug_id = $circle";
	    		$where = "$tbusers_deystvie.user_wp IN (SELECT $tbfriends.friend_wp FROM $tbfriends $inner WHERE $tbfriends.user_wp = $user_wp AND $tbfriends.good=1 AND $tbfriends.id = $tbuserskrugi.friends_id)";
	    	break;
	    }

	    $sql = "SELECT $tbusers_deystvie.id, $tbusers_deystvie.data_add, $tbusers_deystvie.deystvie, $tbusers_deystvie.id_deystvie, $tbusers_deystvie.user_wp
	               FROM $tbusers_deystvie
	             /* INNER JOIN $tbsettings_deystvie ON $tbsettings_deystvie.user_wp = ".varr_int($_SESSION['WP_USER']['user_wp'])." */
	            WHERE
	             $where
	             /* AND $tbsettings_deystvie.deystvie_id = $tbusers_deystvie.deystvie */
	             AND $tbusers_deystvie.privat = 0
	            /* GROUP BY $tbusers_deystvie.id_deystvie работает не правильно! */
	            ORDER BY $tbusers_deystvie.data_add DESC
	            LIMIT $begin,$rows";

	    /*echo "<pre>";
        print str_replace("pfx_","discount_",$sql);
        echo "</pre>";*/

	    $type = $MYSQL->query($sql);

	    if(!is_array($type)){
	       return array('error_id' => 1);
	    }

	    if(is_array($type)){
	    	foreach($type as $key=>$value){
	    		switch($value['deystvie']){
	    			case 1: // Друзья

	    			   	  if($user_wp == $value['user_wp']){ $user_first = $user_wp; $user_second = $value['id_deystvie'];}
	    			  elseif($user_wp == $value['id_deystvie']){ $user_first = $user_wp; $user_second = $value['user_wp'];}
	    			  else {
	    			  	if($this->IsFriend($value['user_wp'])){
	    			  		$user_first = $value['user_wp']; $user_second = $value['id_deystvie'];
	    			  	} else {
	    			  		$user_first = $value['id_deystvie']; $user_second = $value['user_wp'];
	    			  	}
	    			  }

	    			  $user1 = $this->Info_min($user_first,40,40);
	    			  $user2 = $this->Info_min($user_second,78,101,true);

	    			  if(isset($user1) && is_array($user1))
	    			  $array[] = array(
	    			    'id'       => $value['id'],
	    			    'deystvie' => 1,
	    			    'data'     => $value['data_add'], //MyDataTime($value['data_add'],'date'),
	    			    'user1'    => $user1,
	    			    'user2'    => $user2,
	    			  );
	    			break;


	    			case 2: // Подарки
	    			 $result = $MYSQL->query("SELECT $tbhistorypay.podarok, $tbhistorypay.from_mobile, $tbhistorypay.to_mobile, $tbcountry.name town
	    			                      FROM $tbhistorypay
	    			                     INNER JOIN $tbcountry ON $tbcountry.id = $tbhistorypay.town_id
	    			                    WHERE $tbhistorypay.id = ".varr_int($value['id_deystvie'])." AND $tbhistorypay.privat = 0");


	    			 if(is_array($result) && count($result) == 1){
	    			 	$podarok = unserialize($result[0]['podarok']);


	    			 	$user1 = $this->Info_min($value['user_wp'],40,40);
	    			 	$user2 = $this->Info_min($result[0]['to_mobile'],78,101,true);

	    			 	if(!is_array($user1) or strlen(@$user1['lastname']) == 0){
	    			 		$user1['firstname'] = "";
	    			 		$user1['lastname'] = "Неизвестный друг";
	    			 		$user1['photo'] = "";
	    			 		$user1['user_wp']  = "";
	    			 		$user1['zvezda']   = 0;
	    			 	}

	    			 	if(!is_array($user2) or strlen(@$user2['lastname']) == 0){
	    			 		$user2['firstname'] = "";
	    			 		$user2['lastname'] = "Неизвестный друг";
	    			 		$user2['photo'] = "";
	    			 		$user2['user_wp']  = "";
	    			 		$user2['zvezda']   = 0;
	    			 	}

	    			 	$photo = ShowFotoAkcia(array($podarok['id']),146,101);
	    			 	if(is_array($photo)) $photo = $photo[0]['foto'];

	    			 	$logo = ShowLogo(array($podarok['shop_id']),146,101,true);
	    			 	if(is_array($logo)) $logo = $logo[0]['logo'];

	    			 	$array[] = array(
	    			 	'id'       => $value['id'],
	    			    'deystvie' => 2,
	    			    'data'     => $value['data_add'], //MyDataTime($value['data_add'],'date'),
	    			    'user1'    => $user1,
	    			    'user2'    => $user2,
	    			    'podarok'  => array(
	    			                    'akcia_id'  => $podarok['id'],
	    			                    'header'    => stripslashes(trim($podarok['header'])),
	    			                    'photo'     => $photo,
	    			                    'shop_id'   => $podarok['shop_id'],
	    			                    'shop_name' => stripslashes(trim($podarok['shop_name'])),
	    			                    'shop_town' => $result[0]['town'],
	    			                    'shop_logo' => $logo,
	    			    ),
	    			  );

	    			 } else {
	    					$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	    return $this->ShowHistoryLenta($user_wp,$circle,$rows,$begin+$rows);
	    				}
	    			break;


	    			/*case 3: // Подписка

	    			  $result = $MYSQL->query("SELECT $tbshops.id shop_id, $tbshops.name shop_name, $tbusers.user_wp, $tbusers.email, $tbusers.lastname, $tbusers.firstname, $tbusers.zvezda, $tbusers.sex
	    			                      FROM $tbshops
	    			                     INNER JOIN $tbpodpiska ON $tbpodpiska.shop_id = $tbshops.id
	    			                     INNER JOIN $tbusers ON $tbusers.user_wp = $tbpodpiska.user_wp
	    			                    WHERE  $tbpodpiska.id = ".varr_int($value['id_deystvie']));

	    			  if(is_array($result)){
	    			  $array[] = array(
	    			    'id'       => $value['id'],
	    			    'deystvie' => 3,
	    			    'data'     => $value['data_add'], //MyDataTime($value['data_add'],'date'),
	    			    'podpiska' => $result[0],
	    			  );
	    			  } else {
	    					$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	    return $this->ShowHistoryLenta($user_wp,$circle,$rows,$begin+$rows);
	    				}
	    			break;*/

	    			case 4: // Фотоальбом
	    			  $result = $MYSQL->query("SELECT id, user_wp, header, opis FROM $tbphotoalbum WHERE id = ".varr_int($value['id_deystvie']));

	    			  if(is_array($result)){
	    			 	$result = $result[0];
	    			    $array[] = array(
	    			       'id'         => $value['id'],
 	    			       'deystvie'   => 4,
	    			       'data'       => $value['data_add'], //MyDataTime($value['data_add'],'date'),
	    			       'user'       => $this->Info_min($value['user_wp'],40,40),
	    			       'photoalbum' => array(
	    			                         'id'     => varr_int($result['id']),
	    			                         'header' => htmlspecialchars(stripslashes(trim($result['header']))),
	    			                         'opis'   => htmlspecialchars(stripslashes(trim($result['opis']))),
	    			                         'photos' => ShowPhotoAlbums($result['user_wp'],array(array('id'=>varr_int($result['id']),'w_logo'=>211,'h_logo'=>148,'w'=>99,'h'=>74,'count'=>7,'center'=>true))),
	    			                       ),
	    			    );

	    			  } else {
	    					$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	    return $this->ShowHistoryLenta($user_wp,$circle,$rows,$begin+$rows);
	    			  }
	    			break;


	    			case 5: // Мне нравиться
	    			case 6: // Хочу себе
	    			  if($value['deystvie'] == 5)
	    			  $result = $MYSQL->query("SELECT id, header
	    				                            FROM $tbakcia
	    			                              WHERE id = ".(int)$value['id_deystvie']);
	    			  else
	    			  $result = $MYSQL->query("SELECT $tbakcia.id, $tbakcia.header, $tbakcia.shop_id, $tbshops.name, $tbshops.logo
	    				                            FROM $tbakcia
	    			                               INNER JOIN $tbhochu ON $tbhochu.akcia_id = $tbakcia.id
	    			                               INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
	    			                              WHERE $tbhochu.id = ".varr_int($value['id_deystvie']));

	    			  if(is_array($result)){

	    			  	$logo = ShowLogo(array(@$result[0]['shop_id']),188,101);
	    			  	if(is_array($logo)) $logo = $logo[0]['logo'];

	    			  	$photo = ShowFotoAkcia(array(@$result[0]['id']),130,91);
	    			  	if(is_array($photo)) $photo = $photo[0]['foto'];

	    			  $array[] = array(
	    			    'id'         => $value['id'],
	    			    'deystvie'   => $value['deystvie'],
	    			    'data'       => $value['data_add'], //MyDataTime($value['data_add'],'date'),
	    			    'akcia_id'   => $result[0]['id'],
	    			    'akcia_photo'=> $photo,
	    			    'user'       => $this->Info_min($value['user_wp'],40,40),
	    			    'header'     => htmlspecialchars(stripslashes(trim($result[0]['header']))),
	    			    'shop_name'  => htmlspecialchars(stripslashes(trim(@$result[0]['name']))),
	    			    'shop_id'    => @$result[0]['shop_id'],
	    			    'shop_logo'  => $logo,
	    			  );
	    			  } else {
	    					$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	    return $this->ShowHistoryLenta($user_wp,$circle,$rows,$begin+$rows);
	    				}
	    			break;

	    			/*case 7: // Комментарии
	    			    $result = $MYSQL->query("SELECT id, msg, shop_id, akcia_id, photo_id, address_id
	    				                            FROM $tbcomments
	    			                              WHERE id = ".varr_int($value['id_deystvie']));

	    			  if(is_array($result)){




	    			  } else { // Комментарий по действию не найден
	    			  	$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	return $this->ShowHistoryLenta($user_wp,$circle,$rows,$begin+$rows);
	    			  }
	    			break;*/

	    			case 8: // Я здесь
	    				$shop=$MYSQL->query("SELECT $tbshops.id shop_id, $tbshops.name shop_name, $tbshopadres.adress, $tbshopadres.id adress_id
		                              FROM $tbshopadres
		                             INNER JOIN $tbshops ON $tbshops.id = $tbshopadres.shop_id
		                            WHERE $tbshopadres.id = ".varr_int($value['id_deystvie']));

						if(is_array($shop)){
							$shop[0]['adress'] = explode("::",$shop[0]['adress']);
							$adressa = array(
								'street' => @$shop[0]['adress'][0],
								'house'  => @$shop[0]['adress'][1],
								'town'   => @$shop[0]['adress'][2],
							);

							$photo = ShowLogo(array($shop[0]['shop_id']),146,101,true);
							if(is_array($photo)) $photo = $photo[0]['logo'];

							$array[] = array(
								'id'           => $value['id'],
								'deystvie'     => 8,
								'data'         => $value['data_add'], //MyDataTime($value['data_add'],'date'),
								'user'         => $this->Info_min($value['user_wp'],40,40),
								'shop_id'      => $shop[0]['shop_id'],
								'shop_name'    => htmlspecialchars(stripslashes(trim($shop[0]['shop_name']))),
								'shop_adress'  => $adressa,
								'shop_photo'   => $photo,
								'shop_url'     => @$shop[0]['URL'],
								'shop_phone'   => @$shop[0]['phones'],
							);
	    				} else {
	    					$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	    return $this->ShowHistoryLenta($user_wp,$circle,$rows,$begin+$rows);
	    				}
	    			break;

	    			/*case 9: // получил подарок
	    			  $result = $MYSQL->query("SELECT $tbakcia.id, $tbakcia.header, $tbakcia.shop_id, $tbshops.name, $tbshops.logo
	    				                            FROM $tbakcia
	    			                               INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
	    			                              WHERE $tbakcia.id = ".(int)$value['id_deystvie']);

	    			  if(is_array($result)){
	    			  	$logo = ShowLogo(array(@$result[0]['shop_id']),100,100);
		                if(is_array($logo)) $logo = $logo[0]['logo'];

	    			  $array[] = array(
	    			    'id'        => $value['id'],
	    			    'deystvie'  => $value['deystvie'],
	    			    'data'      => MyDataTime($value['data_add'],'date'),
	    			    'akcia_id'  => $result[0]['id'],
	    			    'user'      => $this->Info_min($value['user_wp'],100,100),
	    			    'header'    => htmlspecialchars(stripslashes(trim($result[0]['header']))),
	    			    'shop_name' => htmlspecialchars(stripslashes(trim(@$result[0]['name']))),
	    			    'shop_id'   => @$result[0]['shop_id'],
	    			    'shop_logo' => $logo,
	    			  );
	    			  } else {
	    				  $MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	  return $this->ShowHistoryLenta($user_wp,$circle,$rows,$begin+$rows);
	    				}
	    		    break;*/

	    			case 10: // Обновлен статус/Что нового?
	    				$result = $MYSQL->query("SELECT user_wp, event FROM $tbevent WHERE id = ".varr_int($value['id_deystvie']));

	    				if(is_array($result)){
	    			 		$result = $result[0];
	    			    	$array[] = array(
	    			       'id'         => $value['id'],
 	    			       'deystvie'   => 10,
	    			       'data'       => $value['data_add'], //MyDataTime($value['data_add'],'date'),
	    			       'user'       => $this->Info_min($result['user_wp'],40,40),
	    			       'status'     => ToText($result['event']),
	    			    );

	    				} else {
	    					$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  		return $this->ShowHistoryLenta($user_wp,$circle,$rows,$begin+$rows);
	    				}
	    		    break;
	    		}
	    	}
	    }
		return @$array;
	}


	function CountMassHistoryLenta($user_wp,$online=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbdeystvie       = "pfx_deystvie";
	    $tbusers          = "pfx_users";
	    $tbuserskrugi     = "pfx_users_krugi";
	    $tbfriends        = "pfx_users_friends";
	    $tbusers_deystvie = "pfx_users_deystvie";
	    $tbsettings_deystvie = "pfx_users_settings_lenta";

	    $user_wp = varr_int($_SESSION['WP_USER']['user_wp']);
	    $online  = varr_int($online);
	    $nInner  = '';

	    // Заполняем таблицу настроек ленты событий пользователя (например: показывать только подарки, дружбу, фотоальбомы и т.п.)
	    /*$deystvie = $MYSQL->query("SELECT Count(*) FROM $tbsettings_deystvie WHERE user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
	    if(is_array($deystvie) && $deystvie[0]['count'] == 0){
	       $deystvie = $MYSQL->query("SELECT * FROM $tbdeystvie ORDER BY deystvie_id");
	       foreach($deystvie as $key=>$value)
	       	 $MYSQL->query("INSERT INTO $tbsettings_deystvie (deystvie_id,user_wp) VALUES (".$value['deystvie_id'].",".varr_int($_SESSION['WP_USER']['user_wp']).")");
	    }*/

		if($online==1){
			$inner=" INNER JOIN $tbusers ON $tbusers.user_wp = $tbfriends.friend_wp";
			$w="AND ($tbusers.online + INTERVAL 10 MINUTE > now())";
		}else{
			$inner="";
			$w="";
		}

	    $where = "$tbusers_deystvie.user_wp IN (SELECT $tbfriends.friend_wp FROM $tbfriends $inner WHERE $tbfriends.user_wp=$user_wp AND $tbfriends.good=1 $w) AND";

	    $sql = "SELECT Count(*) FROM $tbusers_deystvie
	             /* INNER JOIN $tbsettings_deystvie ON $tbsettings_deystvie.user_wp = ".varr_int($_SESSION['WP_USER']['user_wp'])." */
	             $nInner
	            WHERE $where /* $tbsettings_deystvie.deystvie_id = $tbusers_deystvie.deystvie AND */ $tbusers_deystvie.privat = 0";

	    $count = $MYSQL->query($sql);
	    return $count[0]['count'];
	}


	function ShowMassHistoryLenta($user_wp,$online=0,$rows=15,$begin=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbusers_deystvie = "pfx_users_deystvie";
		$tbphotoalbum     = "pfx_users_photos_album";
		$tbuserphotos     = "pfx_users_photos";
		$tbfriends        = "pfx_users_friends";
		$tbevent          = "pfx_users_event";
		$tbpodpiska       = "pfx_podpiska";
		$tbcountry        = "pfx_country";
		$tbshops          = "pfx_shops";
		$tbusers          = "pfx_users";
		$tbhistorypay     = "pfx_historypay";
		$tbakcia          = "pfx_akcia";
		$tbhochu          = "pfx_users_hochu";
		$tbdeystvie       = "pfx_deystvie";
		$tbsettings_deystvie = "pfx_users_settings_lenta";
		$tbuserskrugi     = "pfx_users_krugi";

	    $user_wp = varr_int($user_wp);
	    $online  = varr_int($online);
	    $rows    = varr_int($rows);
		$begin   = varr_int($begin);

		if($online==1){
			$inner=" INNER JOIN $tbusers ON $tbusers.user_wp = $tbfriends.friend_wp";
			$w="AND ($tbusers.online + INTERVAL 10 MINUTE > now())";
		}else{
			$inner="";
			$w="";
		}

	    $where = "$tbusers_deystvie.user_wp IN (SELECT $tbfriends.friend_wp FROM $tbfriends $inner WHERE $tbfriends.user_wp=$user_wp AND $tbfriends.good=1 $w) AND";

	    $sql = "SELECT $tbusers_deystvie.id, $tbusers_deystvie.data_add, $tbusers_deystvie.deystvie, $tbusers_deystvie.id_deystvie, $tbusers_deystvie.user_wp
	               FROM $tbusers_deystvie
	             /* INNER JOIN $tbsettings_deystvie ON $tbsettings_deystvie.user_wp = ".varr_int($_SESSION['WP_USER']['user_wp'])." */
	             WHERE $where
	             /* $tbsettings_deystvie.deystvie_id = $tbusers_deystvie.deystvie AND */
	             $tbusers_deystvie.privat = 0
	            /* GROUP BY $tbusers_deystvie.id_deystvie работает не правильно! */
	            ORDER BY $tbusers_deystvie.data_add DESC
	            LIMIT $begin,$rows";

	    /*echo "<pre>";
        print str_replace("pfx_","discount_",$sql);
        echo "</pre>";*/

	    $type = $MYSQL->query($sql);

	    if(!is_array($type)){
	       return array('error_id' => 1);
	    }

	    if(is_array($type)){
	    	foreach($type as $key=>$value){
	    		switch($value['deystvie']){
	    			case 1: // Друзья

	    			   	  if($user_wp == $value['user_wp']){ $user_first = $user_wp; $user_second = $value['id_deystvie'];}
	    			  elseif($user_wp == $value['id_deystvie']){ $user_first = $user_wp; $user_second = $value['user_wp'];}
	    			  else {
	    			  	if($this->IsFriend($value['user_wp'])){
	    			  		$user_first = $value['user_wp']; $user_second = $value['id_deystvie'];
	    			  	} else {
	    			  		$user_first = $value['id_deystvie']; $user_second = $value['user_wp'];
	    			  	}
	    			  }

	    			  $user1 = $this->Info_min($user_second,78,101,true);
	    			  $user2 = $this->Info_min($user_first,40,40);

	    			  if(isset($user1) && is_array($user1))
	    			  $array[] = array(
	    			    'id'       => $value['id'],
	    			    'deystvie' => 1,
	    			    'data'     => $value['data_add'], //MyDataTime($value['data_add'],'date'),
	    			    'user1'    => $user1,
	    			    'user2'    => $user2,
	    			  );
	    			break;


	    			case 2: // Подарки
	    			 $result = $MYSQL->query("SELECT $tbhistorypay.podarok, $tbhistorypay.from_mobile, $tbhistorypay.to_mobile, $tbcountry.name town
	    			                      FROM $tbhistorypay
	    			                     INNER JOIN $tbcountry ON $tbcountry.id = $tbhistorypay.town_id
	    			                    WHERE $tbhistorypay.id = ".varr_int($value['id_deystvie'])." AND $tbhistorypay.privat = 0");


	    			 if(is_array($result) && count($result) == 1){
	    			 	$podarok = unserialize($result[0]['podarok']);


	    			 	$user1 = $this->Info_min($value['user_wp'],40,40);
	    			 	$user2 = $this->Info_min($result[0]['to_mobile'],78,101,true);

	    			 	if(!is_array($user1) or strlen(@$user1['lastname']) == 0){
	    			 		$user1['firstname'] = "";
	    			 		$user1['lastname'] = "Неизвестный друг";
	    			 		$user1['photo'] = "";
	    			 		$user1['user_wp']  = "";
	    			 		$user1['zvezda']   = 0;
	    			 	}

	    			 	if(!is_array($user2) or strlen(@$user2['lastname']) == 0){
	    			 		$user2['firstname'] = "";
	    			 		$user2['lastname'] = "Неизвестный друг";
	    			 		$user2['photo'] = "";
	    			 		$user2['user_wp']  = "";
	    			 		$user2['zvezda']   = 0;
	    			 	}

	    			 	$photo = ShowFotoAkcia(array($podarok['id']),146,101);
	    			 	if(is_array($photo)) $photo = $photo[0]['foto'];

	    			 	$logo = ShowLogo(array($podarok['shop_id']),146,101,true);
	    			 	if(is_array($logo)) $logo = $logo[0]['logo'];

	    			 	$array[] = array(
	    			 	'id'       => $value['id'],
	    			    'deystvie' => 2,
	    			    'data'     => $value['data_add'], //MyDataTime($value['data_add'],'date'),
	    			    'user1'    => $user1,
	    			    'user2'    => $user2,
	    			    'podarok'  => array(
	    			                    'akcia_id'  => $podarok['id'],
	    			                    'header'    => stripslashes(trim($podarok['header'])),
	    			                    'photo'     => $photo,
	    			                    'shop_id'   => $podarok['shop_id'],
	    			                    'shop_name' => stripslashes(trim($podarok['shop_name'])),
	    			                    'shop_town' => $result[0]['town'],
	    			                    'shop_logo' => $logo,
	    			    ),
	    			  );

	    			 } else {
	    					$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	    return $this->ShowMassHistoryLenta($user_wp,$online,$rows,$begin+$rows);
	    				}
	    			break;


	    			/*case 3: // Подписка

	    			  $result = $MYSQL->query("SELECT $tbshops.id shop_id, $tbshops.name shop_name, $tbusers.user_wp, $tbusers.email, $tbusers.lastname, $tbusers.firstname, $tbusers.zvezda, $tbusers.sex
	    			                      FROM $tbshops
	    			                     INNER JOIN $tbpodpiska ON $tbpodpiska.shop_id = $tbshops.id
	    			                     INNER JOIN $tbusers ON $tbusers.user_wp = $tbpodpiska.user_wp
	    			                    WHERE  $tbpodpiska.id = ".varr_int($value['id_deystvie']));

	    			  if(is_array($result)){
	    			  $array[] = array(
	    			    'id'       => $value['id'],
	    			    'deystvie' => 3,
	    			    'data'     => $value['data_add'], //MyDataTime($value['data_add'],'date'),
	    			    'podpiska' => $result[0],
	    			  );
	    			  } else {
	    					$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	    return $this->ShowHistoryLenta($user_wp,$online,$rows,$begin+$rows);
	    				}
	    			break;*/

	    			case 4: // Фотоальбом
	    			  $result = $MYSQL->query("SELECT id, user_wp, header, opis FROM $tbphotoalbum WHERE id = ".varr_int($value['id_deystvie']));

	    			  if(is_array($result)){
	    			 	$result = $result[0];
	    			    $array[] = array(
	    			       'id'         => $value['id'],
 	    			       'deystvie'   => 4,
	    			       'data'       => $value['data_add'], //MyDataTime($value['data_add'],'date'),
	    			       'user'       => $this->Info_min($value['user_wp'],40,40),
	    			       'photoalbum' => array(
	    			                         'id'     => varr_int($result['id']),
	    			                         'header' => htmlspecialchars(stripslashes(trim($result['header']))),
	    			                         'opis'   => htmlspecialchars(stripslashes(trim($result['opis']))),
	    			                         'photos' => ShowPhotoAlbums($result['user_wp'],array(array('id'=>varr_int($result['id']),'w_logo'=>211,'h_logo'=>148,'w'=>99,'h'=>74,'count'=>7,'center'=>true))),
	    			                       ),
	    			    );

	    			  } else {
	    					$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	    return $this->ShowMassHistoryLenta($user_wp,$online,$rows,$begin+$rows);
	    			  }
	    			break;


	    			case 5: // Мне нравиться
	    			case 6: // Хочу себе
	    			  if($value['deystvie'] == 5)
	    			  $result = $MYSQL->query("SELECT id, header
	    				                            FROM $tbakcia
	    			                              WHERE id = ".(int)$value['id_deystvie']);
	    			  else
	    			  $result = $MYSQL->query("SELECT $tbakcia.id, $tbakcia.header, $tbakcia.shop_id, $tbshops.name, $tbshops.logo
	    				                            FROM $tbakcia
	    			                               INNER JOIN $tbhochu ON $tbhochu.akcia_id = $tbakcia.id
	    			                               INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
	    			                              WHERE $tbhochu.id = ".varr_int($value['id_deystvie']));

	    			  if(is_array($result)){

	    			  	$logo = ShowLogo(array(@$result[0]['shop_id']),188,101);
	    			  	if(is_array($logo)) $logo = $logo[0]['logo'];

	    			  	$photo = ShowFotoAkcia(array(@$result[0]['id']),130,91);
	    			  	if(is_array($photo)) $photo = $photo[0]['foto'];

	    			  $array[] = array(
	    			    'id'         => $value['id'],
	    			    'deystvie'   => $value['deystvie'],
	    			    'data'       => $value['data_add'], //MyDataTime($value['data_add'],'date'),
	    			    'akcia_id'   => $result[0]['id'],
	    			    'akcia_photo'=> $photo,
	    			    'user'       => $this->Info_min($value['user_wp'],40,40),
	    			    'header'     => htmlspecialchars(stripslashes(trim($result[0]['header']))),
	    			    'shop_name'  => htmlspecialchars(stripslashes(trim(@$result[0]['name']))),
	    			    'shop_id'    => @$result[0]['shop_id'],
	    			    'shop_logo'  => $logo,
	    			  );
	    			  } else {
	    					$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	    return $this->ShowMassHistoryLenta($user_wp,$online,$rows,$begin+$rows);
	    				}
	    			break;

	    			/*case 7: // Комментарии
	    			    $result = $MYSQL->query("SELECT id, msg, shop_id, akcia_id, photo_id, address_id
	    				                            FROM $tbcomments
	    			                              WHERE id = ".varr_int($value['id_deystvie']));

	    			  if(is_array($result)){




	    			  } else { // Комментарий по действию не найден
	    			  	$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	return $this->ShowMassHistoryLenta($user_wp,$online,$rows,$begin+$rows);
	    			  }
	    			break;*/

	    			case 8: // Я здесь
	    				$shop=$MYSQL->query("SELECT $tbshops.id shop_id, $tbshops.name shop_name, $tbshopadres.adress, $tbshopadres.id adress_id
		                              FROM $tbshopadres
		                             INNER JOIN $tbshops ON $tbshops.id = $tbshopadres.shop_id
		                            WHERE $tbshopadres.id = ".varr_int($value['id_deystvie']));

						if(is_array($shop)){
							$shop[0]['adress'] = explode("::",$shop[0]['adress']);
							$adressa = array(
								'street' => @$shop[0]['adress'][0],
								'house'  => @$shop[0]['adress'][1],
								'town'   => @$shop[0]['adress'][2],
							);

							$photo = ShowLogo(array($shop[0]['shop_id']),146,101,true);
							if(is_array($photo)) $photo = $photo[0]['logo'];

							$array[] = array(
								'id'           => $value['id'],
								'deystvie'     => 8,
								'data'         => $value['data_add'], //MyDataTime($value['data_add'],'date'),
								'user'         => $this->Info_min($value['user_wp'],40,40),
								'shop_id'      => $shop[0]['shop_id'],
								'shop_name'    => htmlspecialchars(stripslashes(trim($shop[0]['shop_name']))),
								'shop_adress'  => $adressa,
								'shop_photo'   => $photo,
								'shop_url'     => @$shop[0]['URL'],
								'shop_phone'   => @$shop[0]['phones'],
							);
	    				} else {
	    					$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	    return $this->ShowMassHistoryLenta($user_wp,$online,$rows,$begin+$rows);
	    				}
	    			break;

	    			/*case 9: // получил подарок
	    			  $result = $MYSQL->query("SELECT $tbakcia.id, $tbakcia.header, $tbakcia.shop_id, $tbshops.name, $tbshops.logo
	    				                            FROM $tbakcia
	    			                               INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
	    			                              WHERE $tbakcia.id = ".(int)$value['id_deystvie']);

	    			  if(is_array($result)){
	    			  	$logo = ShowLogo(array(@$result[0]['shop_id']),100,100);
		                if(is_array($logo)) $logo = $logo[0]['logo'];

	    			  $array[] = array(
	    			    'id'        => $value['id'],
	    			    'deystvie'  => $value['deystvie'],
	    			    'data'      => MyDataTime($value['data_add'],'date'),
	    			    'akcia_id'  => $result[0]['id'],
	    			    'user'      => $this->Info_min($value['user_wp'],100,100),
	    			    'header'    => htmlspecialchars(stripslashes(trim($result[0]['header']))),
	    			    'shop_name' => htmlspecialchars(stripslashes(trim(@$result[0]['name']))),
	    			    'shop_id'   => @$result[0]['shop_id'],
	    			    'shop_logo' => $logo,
	    			  );
	    			  } else {
	    				  $MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  	  return $this->ShowMassHistoryLenta($user_wp,$online,$rows,$begin+$rows);
	    				}
	    		    break;*/

	    			case 10: // Обновлен статус/Что нового?
	    				$result = $MYSQL->query("SELECT user_wp, event FROM $tbevent WHERE id = ".varr_int($value['id_deystvie']));

	    				if(is_array($result)){
	    			 		$result = $result[0];
	    			    	$array[] = array(
	    			       'id'         => $value['id'],
 	    			       'deystvie'   => 10,
	    			       'data'       => $value['data_add'], //MyDataTime($value['data_add'],'date'),
	    			       'user'       => $this->Info_min($result['user_wp'],40,40),
	    			       'status'     => ToText($result['event']),
	    			    );

	    				} else {
	    					$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE id=".$value['id']);
	    			  		return $this->ShowMassHistoryLenta($user_wp,$online,$rows,$begin+$rows);
	    				}
	    		    break;
	    		}
	    	}
	    }
		return @$array;
	}


	/** ФОТОАЛЬБОМЫ НАЧАЛО **/
	function CountPhotoAlbums($user_wp){ // Кол-во фотоальбомов
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbphotoalbum = "pfx_users_photos_album";

		$count = $MYSQL->query("SELECT Count(*) FROM $tbphotoalbum WHERE type=0 AND user_wp=".(int)$user_wp);
		return $count[0]['count'];
	}

	function CountPhotosIsAlbum($album_id){ // Кол-во фоток в альбоме
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $album_id = varr_int($album_id);
	    $count = $MYSQL->query("SELECT Count(*) FROM pfx_users_photos WHERE album_id = ".$album_id);
	    return $count[0]['count'];
	}

	function ShowListPhotoAlbums($user_wp,$rows=10,$page=1){ // Выводим все фотоальбомы
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $rows    = varr_int($rows);
		$page    = varr_int($page-1);
		$begin   = $page*$rows;
		$user_wp = varr_int($user_wp);

		$tbphotoalbum = "pfx_users_photos_album";

		$result = $MYSQL->query("SELECT id, user_wp, data_add, header, IFNULL(pravo,'') pravo, IFNULL(`updated`,'') `updated`, type FROM $tbphotoalbum WHERE user_wp = $user_wp AND type = '0' ORDER BY data_add DESC LIMIT $begin,$rows");
		if(is_array($result)){
			foreach($result as $key=>$value){
				$albums[] = array(
				    'id'     => $value['id'],
				    'center' => true,
				    'w_logo' => 70,
				    'h_logo' => 70,
				    'count'  => 1,
				);
			}
			$photos = ShowPhotoAlbums($user_wp,$albums);

			for($i=0; $i < count($result); $i++){
				if($result[$i]['updated']=='')$updated='';
				else                          $updated=MyDataTime($result[$i]['updated'],'date');
				$array[] = array(
				  'album_id' => $result[$i]['id'],
				  'photo_id' => $photos[$i]['photo_id'],
				  'logo'     => $photos[$i]['photo'],
				  'user_wp'  => $result[$i]['user_wp'],
				  'data'     => MyDataTime($result[$i]['data_add'],'date'),
				  'header'   => htmlspecialchars(stripslashes(trim($result[$i]['header']))),
				  'security' => Security($result[$i]['user_wp'],$result[$i]['pravo']),
				  'count_photos' => $this->CountPhotosIsAlbum($result[$i]['id']),
				  'updated'  => $updated,
				  'prava'	 => unserialize($result[$i]['pravo']),
				);
				for($ki=0; $ki<count($array[$i]['prava']); $ki++){
					$array[$i]['prava'][$ki]=$array[$i]['prava'][$ki]['krug_id'];
				}
			}
		}
		return @$array;
	}

	function InfoPhotoAlbum($user_wp=0,$album_id=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbphotosalbum = "pfx_users_photos_album";
		$user_wp  = varr_int($user_wp);
		$album_id = varr_int($album_id);

		if($user_wp == 0){ // Мой альбом
			$where = " AND user_wp=".varr_int($_SESSION['WP_USER']['user_wp']);
		}
	elseif($user_wp >= min_user_wp){
			$where = " AND user_wp=".$user_wp;
		} else {  // Узнаем чей альбом
			$where = "";
		}
		$result = $MYSQL->query("SELECT id, header, opis, user_wp FROM $tbphotosalbum WHERE id=$album_id $where");
		return @$result[0];
	}

	function ShowPhotosIsAlbum($user_wp=0,$album_id=0,$w=70,$h=70){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbphotos = "pfx_users_photos";
		$user_wp  = varr_int($user_wp);
		$album_id = varr_int($album_id);

		if($user_wp == 0) $user_wp = $_SESSION['WP_USER']['user_wp'];

		$result = $MYSQL->query("SELECT id, header, logo FROM $tbphotos WHERE album_id=$album_id AND user_wp=$user_wp ORDER BY logo, id");
		if(is_array($result)){
			$photo = ShowPhotoAlbums($user_wp,array(array('id'=>$album_id,'w_logo'=>$w,'h_logo'=>$h,'w'=>$w,'h'=>$h,'center'=>true)));
			for($i=0; $i < count($result); $i++){
				$array[] = array(
				  'photo_id'       => $result[$i]['id'],
				  'logo'           => $result[$i]['logo'],
				  'header'         => htmlspecialchars(stripslashes(trim($result[$i]['header']))),
				  'photo'          => $photo[$i]['photo'],
				  'photo_original' => $photo[$i]['photo_original'],
				);
			}
		}
		return @$array;
	}

	function ShowLastPhoto($user_wp,$count=5,$w=70,$h=70){ // Выводим n последних фотографий
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
		$GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbphotos      = "pfx_users_photos";
		$tbphotosalbum = "pfx_users_photos_album";
		$user_wp       = varr_int($user_wp);

		if($user_wp == 0) $user_wp = varr_int($_SESSION['WP_USER']['user_wp']);

		$result = $MYSQL->query("SELECT $tbphotos.`id`, $tbphotos.`header`, $tbphotos.`logo` FROM $tbphotos INNER JOIN $tbphotosalbum ON $tbphotos.album_id=$tbphotosalbum.id WHERE $tbphotos.user_wp=$user_wp AND $tbphotosalbum.type<>1 ORDER BY $tbphotos.`id` DESC LIMIT 0,".$count."");
		if(is_array($result)){
			foreach($result as $key2=>$value2){
				$arrPhotos[] = array(
					'id'     => $value2['id'],
					'w'      => $w,
					'h'      => $h,
					'center' => true,
				);
			}
			$photo = ShowPhotoAlbums($user_wp,array(),$arrPhotos);
			for($i=0; $i < count($result); $i++){
				$array[] = array(
				  'photo_id'       => $result[$i]['id'],
				  'logo'           => $result[$i]['logo'],
				  'header'         => htmlspecialchars(stripslashes(trim($result[$i]['header']))),
				  'photo'          => $photo[$i]['photo'],
				  'photo_original' => $photo[$i]['photo_original'],
				);
			}
		}
		return @$array;
	}
	function CreateAvatarsAlbum($user_wp)
	{
		global $MYSQL;
		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;
		$tbl = "pfx_users_photos_album";
		$result = $MYSQL->query("INSERT INTO $tbl (`data_add`, `user_wp`, `header`, `type`) VALUES (NOW(),$user_wp,'Мои аватары',1)");
		return $result;

	}
	function ShowAvatarAlbum($user_wp=0,$count = 0)
	{
		global $MYSQL;
		if($user_wp == 0) $user_wp = (int)$_SESSION['WP_USER']['user_wp'];
		$w = 190;
		$h = 190;
		$album_id = $this->GetAvatarsAlbumId($user_wp);
		$photo = ShowAvatarsAlbum($user_wp);
			return (is_array($photo))?$photo:0;
	}
	function GetAvatarsAlbumId($user_wp)
	{
		global $MYSQL;
		$user_wp = (int)$user_wp;
		$avatar_type = 1;
		$tbl = "pfx_users_photos_album";
		if($user_wp == 0) $user_wp = (int)$_SESSION['WP_USER']['user_wp'];
		$album_id = $MYSQL->query("SELECT `id` FROM $tbl WHERE `user_wp` =$user_wp AND `type` =$avatar_type ");
		return (is_array($album_id))?(int)$album_id[0]['id']:0;

	}
	/** ФОТОАЛЬБОМЫ КОНЕЦ **/

    function CheckHochu($akcia_id){
        global $MYSQL;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$akcia_id           =(int)$akcia_id;
        $tbhochu            = "pfx_users_hochu";

        $result=$MYSQL->query("SELECT Count(*) FROM $tbhochu WHERE akcia_id=$akcia_id AND user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);

        if ($result[0]['count'] == 0) return false;
        else return true;
    }

//!Желания — добавление
	function AddHochu($akcia_id, $shop_id=0){
		global $MYSQL, $USER;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$akcia_id           =(int)$akcia_id;
        $shop_id            =(int)$shop_id;
        $adress_id          =0;

        $tbhochu            = "pfx_users_hochu";
		$tbusers_deystvie   = "pfx_users_deystvie";
        $tbakcia            = "pfx_akcia";
		$tbshops            = "pfx_shops";
        $tbshops_adress     = "pfx_shops_adress";

		if(!($USER->CheckHochu($akcia_id))){
		    $result = $MYSQL->query("SELECT id FROM $tbshops_adress WHERE shop_id=$shop_id LIMIT 1;");
            if (is_array($result) && count($result)){
                $adress_id = $result[0]['id'];
                $hochu_id = $MYSQL->query("INSERT INTO $tbhochu (adddata,user_wp,akcia_id,adress_id) VALUES (now(),".(int)$_SESSION['WP_USER']['user_wp'].",$akcia_id, $adress_id)");
    			//$this->AddDeystvie(0, (int)$_SESSION['WP_USER']['user_wp'], 6, $hochu_id);
    			$MYSQL->query("INSERT INTO `".$tbusers_deystvie."` (`data_add`, `user_wp`, `deystvie`, `id_deystvie`) VALUES (now(), ".(int)$_SESSION['WP_USER']['user_wp'].", 6, ".$hochu_id.")");
            }
		}
        else return false;

	}

//!Желания — удаление
	function DeleteHochu($akcia_id){
		global $MYSQL, $USER;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $akcia_id = (int) $akcia_id;
	    $tbhochu  = "pfx_users_hochu";
	    $tbusers_deystvie = "pfx_users_deystvie";

        if($USER->CheckHochu($akcia_id)){
            $result = $MYSQL->query("SELECT id FROM $tbhochu WHERE akcia_id=$akcia_id AND user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);
	        $MYSQL->query("DELETE FROM $tbhochu WHERE akcia_id=$akcia_id AND user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);
	        $MYSQL->query("DELETE FROM $tbusers_deystvie WHERE deystvie=6 AND id_deystvie=".(int)$result[0]['id']." AND user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);
        }
        else return false;

	}

    function UpdateHochu($akcia_id, $reason){
		global $MYSQL, $USER;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $akcia_id = (int) $akcia_id;
	    $tbhochu  = "pfx_users_hochu";

        if($USER->CheckHochu($akcia_id)){
		    $result=$MYSQL->query("UPDATE $tbhochu SET `reason`='$reason' WHERE `user_wp`=".(int)$_SESSION['WP_USER']['user_wp']." AND `akcia_id`=".$akcia_id);
        }
        else return false;

	}

//!Желания — количество
	function CountIHochu($user_wp=0, $par=''){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

        $performed_wishes  = 0;
        $count = 0;
	    $user_wp = (int) $user_wp;
		$tbhochu  = "pfx_users_hochu";

		if($user_wp == 0) $user_wp = $_SESSION['WP_USER']['user_wp'];

        if ($par == 'all')
            $sql = "SELECT COUNT(*) AS num_rows
		                  FROM $tbhochu
                          WHERE $tbhochu.user_wp = $user_wp";
        else
            $sql = "SELECT $tbhochu.status, COUNT($tbhochu.status) AS num_wishes
		                  FROM $tbhochu
                          WHERE $tbhochu.user_wp = $user_wp AND $tbhochu.akcia_id <> 0
                          GROUP BY $tbhochu.status";

        $result = $MYSQL->query($sql);


        if ($par == 'all'){
            return @$result[0];
        }
        else {
            for($i=0; $i < count($result); $i++){
                if ($result[$i]['status'] == 1)
                    $performed_wishes  = $result[$i]['num_wishes'];

                $count += $result[$i]['num_wishes'];
        	}

            $wish_array = array(
                'all'        => $count,
                'performed'  => $performed_wishes,
            );
            return @$wish_array;
        }
	}

//Список жеданий+вишлист
function FindWLPosition($user_wp, $rows=10,$begin=0, $par=''){
        global $MYSQL;
        $GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

        $user_wp = varr_int($user_wp);
        $rows    = varr_int($rows);
		$begin   = varr_int($begin);
        $tbhochu = "pfx_users_hochu";
        $count = 0;
        $i     = $begin;
        $where = '';
        $array_num = '';

        if ($par == 'performed')
            $where = "AND $tbhochu.status=1";

        $result = $MYSQL->query("SELECT *
    	                         FROM $tbhochu
 	                             WHERE $tbhochu.user_wp = $user_wp $where
    		                     ORDER BY $tbhochu.adddata DESC
                                 LIMIT $begin,$rows
    	");

        if(is_array($result) && count($result)) {
            for ($j=0; $j<count($result);$j++){
                if ($result[$j]['akcia_id'] == 0){
                    $array_num[$count]['num'] = $i;
                    $array_num[$count]['id'] = $result[$j]['id'];
                    $count++;
                    //echo $i."<br />";
                }
                $i++;
            }
            return $array_num;
        }
        else return false;
}

function LoadWishes($user_wp, $id, $begin=0, $rows=0, $type='', $par='', $wish_cnt=0){
    global $MYSQL, $USER;

    $GLOBALS['PHP_FILE'] = __FILE__;
    $GLOBALS['FUNCTION'] = __FUNCTION__;

    $tbhochu    = "pfx_users_hochu";
	$tbakcia    = "pfx_akcia";
	$tbcurrency = "pfx_currency";

    if ($type == 'wishes'){
        if ($par == 'performed'){
            $sql = "SELECT $tbhochu.id, $tbhochu.akcia_id, $tbhochu.status, $tbhochu.reason, $tbhochu.adddata, $tbhochu.adress_id, $tbakcia.header, $tbakcia.shop_id,  $tbakcia.mtext, $tbakcia.amount, $tbcurrency.mask
        		                FROM $tbhochu
        		                INNER JOIN $tbakcia ON $tbakcia.id = $tbhochu.akcia_id
        		                INNER JOIN $tbcurrency ON $tbcurrency.id = $tbakcia.currency_id
        		                WHERE $tbhochu.user_wp = $user_wp AND $tbhochu.status = 1
        		                ORDER BY $tbhochu.adddata DESC
        		                LIMIT $begin,$rows";
        }
        else{
            $sql = "SELECT $tbhochu.id, $tbhochu.akcia_id, $tbhochu.status, $tbhochu.reason, $tbhochu.adddata, $tbhochu.adress_id, $tbakcia.header, $tbakcia.shop_id,  $tbakcia.mtext, $tbakcia.amount, $tbcurrency.mask
        		                FROM $tbhochu
        		                INNER JOIN $tbakcia ON $tbakcia.id = $tbhochu.akcia_id
        		                INNER JOIN $tbcurrency ON $tbcurrency.id = $tbakcia.currency_id
        		                WHERE $tbhochu.user_wp = $user_wp
        		                ORDER BY $tbhochu.adddata DESC
        		                LIMIT $begin,$rows";
        }
    }
    elseif ($type == 'wlist'){
        if ($par == 'performed'){
            $sql = "SELECT * FROM $tbhochu WHERE id=$id AND user_wp=$user_wp AND status=1";
        }
        else{
            $sql = "SELECT * FROM $tbhochu WHERE id=$id AND user_wp=$user_wp";
        }
    }
    $result = $MYSQL->query($sql);
    return $result;

}

//!Желания — список желаний
	function ShowIHochu($user_wp,$rows=21,$begin=0, $par='', $wish_cnt=0){
		global $MYSQL, $USER;

		$wish_array = $this->CountIHochu($user_wp);
		$_SESSION['count_all'] = $wish_array['all'];

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$rows    = varr_int($rows);
		$begin   = varr_int($begin);
		$user_wp = varr_int($user_wp);
        $wish_cnt= varr_int($wish_cnt);


		$tbhochu    = "pfx_users_hochu";
		$tbakcia    = "pfx_akcia";
		$tbcurrency = "pfx_currency";
        $where      = '';
        $pre_pos    = -1;
        $sql        = '';
        $count      = 0;

        $wl_array = $USER->FindWLPosition($user_wp, $rows, $begin, $par);

        if (is_array($wl_array) && count($wl_array)>0){
            for ($i=0; $i<count($wl_array);$i++){
                if ($wl_array[$i]['num'] == $begin){
                    //echo "1--><br />";
                    $res_array = $USER->LoadWishes($user_wp, $wl_array[$i]['id'], 0,0, 'wlist', $par);
                    for ($j=0; $j<count($res_array);$j++){
                        $total_array[$count] = $res_array[$j];
                        $count++;
                    }

                    if (count($wl_array) == 1){
                        $limit = ($begin + $rows) - ($wl_array[$i]['num']+1);
                        $res_array = $USER->LoadWishes($user_wp, 0, $wish_cnt, $limit, 'wishes', $par, $wish_cnt);    //+1
                        for ($j=0; $j<count($res_array);$j++){
                            $total_array[$count] = $res_array[$j];
                            $count++;
                        }
                        $wish_cnt += count($res_array);
                    }
                    $pre_pos = $wl_array[$i]['num'];
                }
                elseif (($wl_array[$i]['num'] > $begin) && ($wl_array[$i]['num'] < ($begin + $rows))){
                  //echo "2--><br />";
                  if ($pre_pos == -1){
                    $limit = $wl_array[$i]['num'] - $begin;
                    $res_array = $USER->LoadWishes($user_wp, 0, $wish_cnt, $limit, 'wishes', $par, $wish_cnt);
                    for ($j=0; $j<count($res_array);$j++){
                        $total_array[$count] = $res_array[$j];
                        $count++;
                    }
                    $wish_cnt += count($res_array);

                    $res_array = $USER->LoadWishes($user_wp, $wl_array[$i]['id'], 0,0, 'wlist', $par);
                    for ($j=0; $j<count($res_array);$j++){
                        $total_array[$count] = $res_array[$j];
                        $count++;
                    }

                    if (count($wl_array) == 1){
                        $limit = ($begin + $rows) - ($wl_array[$i]['num']+1);    //+1
                        $res_array = $USER->LoadWishes($user_wp, 0, $wish_cnt, $limit, 'wishes', $par, $wish_cnt);    //+1
                        for ($j=0; $j<count($res_array);$j++){
                            $total_array[$count] = $res_array[$j];
                            $count++;
                        }
                        $wish_cnt += count($res_array);
                    }
                    $pre_pos = $wl_array[$i]['num'];
                  }
                  else {
                    if ($wl_array[$i]['num'] == ($pre_pos + 1)){
                        $res_array = $USER->LoadWishes($user_wp, $wl_array[$i]['id'], 0,0, 'wlist', $par);
                        for ($j=0; $j<count($res_array);$j++){
                            $total_array[$count] = $res_array[$j];
                            $count++;
                        }
                        $pre_pos = $wl_array[$i]['num'];

                        if (count($wl_array) == ($i+1)){
                            $limit = ($begin + $rows) - ($wl_array[$i]['num']+1);
                            $res_array = $USER->LoadWishes($user_wp, 0, $wish_cnt, $limit, 'wishes', $par, $wish_cnt);  //+1
                            $wish_num += $limit;
                            for ($j=0; $j<count($res_array);$j++){
                                $total_array[$count] = $res_array[$j];
                                $count++;
                            }
                            $wish_cnt += count($res_array);
                        }
                    }
                    else {
                        $limit = $wl_array[$i]['num'] - ($pre_pos + 1);
                        $res_array = $USER->LoadWishes($user_wp, 0, $wish_cnt, $limit, 'wishes', $par, $wish_cnt);
                        for ($j=0; $j<count($res_array);$j++){
                            $total_array[$count] = $res_array[$j];
                            $count++;
                        }
                        $wish_cnt += count($res_array);

                        $res_array = $USER->LoadWishes($user_wp, $wl_array[$i]['id'], 0,0, 'wlist', $par);
                        for ($j=0; $j<count($res_array);$j++){
                            $total_array[$count] = $res_array[$j];
                            $count++;
                        }

                        $pre_pos = $wl_array[$i]['num'];
                        if (count($wl_array) == ($i+1)){
                            $limit = ($begin + $rows) - ($wl_array[$i]['num']+1);
                            $res_array = $USER->LoadWishes($user_wp, 0, $wish_cnt, $limit, 'wishes', $par, $wish_cnt);  //+1
                            for ($j=0; $j<count($res_array);$j++){
                                $total_array[$count] = $res_array[$j];
                                $count++;
                            }
                            $wish_cnt += count($res_array);
                        }
                    }
                  }
                }
                elseif ($wl_array[$i]['num'] == ($begin + $rows)){
                    //echo "3--><br />";
                    $limit = ($begin + $rows) - 1;
                    $res_array = $USER->LoadWishes($user_wp, 0, $wish_cnt, $limit, 'wishes', $par);
                    for ($j=0; $j<count($res_array);$j++){
                        $total_array[$count] = $res_array[$j];
                        $count++;
                    }
                    $wish_cnt += count($res_array);

                    $res_array = $USER->LoadWishes($user_wp, $wl_array[$i]['id'], 0,0, 'wlist', $par);
                    for ($j=0; $j<count($res_array);$j++){
                        $total_array[$count] = $res_array[$j];
                        $count++;
                    }
                }
                else {
                  echo "xxx";
                }

            }

        }
        else{
            if ($par == 'performed')
                $where = "AND $tbhochu.status = 1";

            $sql = "SELECT $tbhochu.id, $tbhochu.akcia_id, $tbhochu.status, $tbhochu.reason, $tbhochu.adddata, $tbhochu.adress_id, $tbakcia.header, $tbakcia.shop_id,  $tbakcia.mtext, $tbakcia.amount, $tbcurrency.mask
        	        FROM $tbhochu
        	        INNER JOIN $tbakcia ON $tbakcia.id = $tbhochu.akcia_id
        	        INNER JOIN $tbcurrency ON $tbcurrency.id = $tbakcia.currency_id
        	        WHERE $tbhochu.user_wp = $user_wp $where
        	        ORDER BY $tbhochu.adddata DESC
        	        LIMIT $wish_cnt,$rows";

            $result = $MYSQL->query($sql);

    		if(is_array($result) && count($result)){
            foreach($result as $key=>$value){
            	$total_array[] = array(
            	   'akcia_id'    => $value['akcia_id'],
            	   'id'          => $value['id'],
                   'shop_id'     => $value['shop_id'],
                   'status'      => $value['status'],
                   'reason'      => htmlspecialchars(stripslashes(trim($value['reason']))),
                   'adddata'     => $value['adddata'],
            	   'header'      => htmlspecialchars(stripslashes(trim($value['header']))),
            	   'amount'      => $value['amount'],
            	   'mtext'       => htmlspecialchars(stripslashes(trim($value['mtext']))),
            	   'currency'    => $value['mask'],
                   'adress_id'   => $value['adress_id']
            	);
            }
    		}
        }

        return @$total_array;
        return true;
	}

     function GetWlistData($id, $user_wp=0){
        global $MYSQL, $USER;

        if ($user_wp == 0)
            $user_wp = $_SESSION['WP_USER']['user_wp'];

        $tbhochu = "pfx_users_hochu";
        $id      = (int)$id;
        $result  = $MYSQL->query("SELECT * FROM $tbhochu WHERE id=".$id." AND user_wp=$user_wp;");

        if(is_array($result) && count($result))
            return $result[0];
        else return false;
    }

    function CountWlist($user_wp=0,$par=''){
        global $MYSQL;

        if ($user_wp == 0)
            $user_wp = $_SESSION['WP_USER']['user_wp'];
        $tbhochu = "pfx_users_hochu";

        if ($par == 'perf')
            $sql = "SELECT COUNT(*) FROM $tbhochu WHERE user_wp=$user_wp AND is_wlist=1;";
        else
            $sql = "SELECT COUNT(*) FROM $tbhochu WHERE user_wp=$user_wp AND is_wlist=1 AND status=1;";

        $result  = $MYSQL->query($sql);
        if(is_array($result) && count($result))
            return $result[0]['count'];
        else return false;
    }

    function ShowPlace($adress_id){
   		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

        $tbhochu        = "pfx_users_hochu";
		$tbakcia        = "pfx_akcia";
		$tbshops        = "pfx_shops";
        $tbshops_adress = "pfx_shops_adress";

        /*
        $result = $MYSQL->query(" SELECT $tbhochu.akcia_id, $tbakcia.shop_id, $tbshops.name, $tbshops_adress.adress
                                  FROM $tbhochu
                                  INNER JOIN $tbakcia ON $tbakcia.id = $tbhochu.akcia_id
                                  INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
                                  INNER JOIN $tbshops_adress ON $tbshops_adress.shop_id = $tbshops.id
                                  WHERE $tbhochu.user_wp = ".(int)@$_SESSION['WP_USER']['user_wp']." AND $tbhochu.akcia_id = ".(int)$akcia_id."
        ");
        */
        /*
        $result = $MYSQL->query(" SELECT $tbhochu.akcia_id, $tbakcia.shop_id, $tbshops.name, $tbshops_adress.adress
                                  FROM $tbhochu
                                  INNER JOIN $tbakcia ON $tbakcia.id = $tbhochu.akcia_id
                                  INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
                                  INNER JOIN $tbshops_adress ON $tbshops_adress.id = $tbhochu.adress_id
                                  WHERE $tbhochu.user_wp = ".(int)@$_SESSION['WP_USER']['user_wp']." AND $tbhochu.akcia_id = ".(int)$akcia_id."
        ");
        */
        $result = $MYSQL->query(" SELECT $tbhochu.adress_id, $tbhochu.akcia_id, $tbshops_adress.shop_id, $tbshops.name, $tbshops_adress.adress
                                  FROM $tbhochu
                                  INNER JOIN $tbshops_adress ON $tbshops_adress.id = $tbhochu.adress_id
                                  INNER JOIN $tbshops ON $tbshops_adress.shop_id = $tbshops.id
                                  WHERE $tbhochu.user_wp = ".(int)@$_SESSION['WP_USER']['user_wp']." AND $tbhochu.adress_id = ".(int)$adress_id."
        ");

        if(is_array($result) && count($result)){
          foreach($result as $key=>$value){
            $value['adress'] = str_replace("::",", ",$value['adress']);
          	$array[] = array(
                 'shop_id'  => $value['shop_id'],
                 'name'     => $value['name'],
                 'adress'   => $value['adress']
          	);
          }
		}
        return @$array[0];

    }


	function CountWhohereShop($shop_id){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbihere = "pfx_users_ihere";
		$tbshops_adress = "pfx_shops_adress";

		$result = $MYSQL->query("SELECT Count(*) FROM $tbihere
		                             INNER JOIN $tbshops_adress ON $tbshops_adress.shop_id
		                            WHERE $tbshops_adress.shop_id = ".(int)$shop_id." AND $tbihere.address_id = $tbshops_adress.id");

		return $result[0]['count'];
	}

	function CountWhohereShopAddress($address_id){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
		$GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbihere = "pfx_users_ihere";

		if($address_id>0){
			$result = $MYSQL->query("SELECT Count(*) FROM $tbihere WHERE address_id = ".(int)$address_id);
			return $result[0]['count'];
		}
		else
			return 0;
	}

	function CountWhohereShopAddressFriends($address_id){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbihere = "pfx_users_ihere";
		$tbfriends = "pfx_users_friends";

		$result = $MYSQL->query("SELECT Count(*) FROM $tbihere
		                             INNER JOIN $tbfriends ON $tbfriends.user_wp = ".(int)@$_SESSION['WP_USER']['user_wp']."
		                            WHERE $tbihere.address_id = ".(int)$address_id." AND $tbfriends.friend_wp = $tbihere.user_wp AND $tbfriends.good=1");
		if($result[0]['count'] > 0){
		   return "<b style=\"color:red\">".$result[0]['count']."</b>";
		}
		else
		  return $result[0]['count'];
	}


	function BtnHere($address_id=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $tbihere   = "pfx_users_ihere";

	    $result = $MYSQL->query("SELECT Count(*) FROM $tbihere
		                            WHERE user_wp = ".(int)@$_SESSION['WP_USER']['user_wp']." AND address_id = ".(int)$address_id);
	    if($result[0]['count'] == 0){
	    	return "<div class=\"roundedbutton greenbutton\"><sub></sub><div><a href=\"#\" id=\"btnIhere\" onClick=\"return false;\">Я здесь</a></div><sup></sup></div>";
	    } else {
	    	return "<div class=\"roundedbutton redbutton\"><sub></sub><div><a href=\"#\" id=\"btnIhere\" onClick=\"return false;\">Уже ушел</a></div><sup></sup></div>";
	    }
	}



	function Whohere($address_id,$friend=0){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$tbihere   = "pfx_users_ihere";
		$tbfriends = "pfx_users_friends";
		$tbshops   = "pfx_shops";
		$tbaddress = "pfx_shops_adress";

		switch($friend){
			case 1:
				$result = $MYSQL->query("SELECT $tbihere.user_wp FROM $tbihere
		                             INNER JOIN $tbfriends ON $tbfriends.user_wp = ".(int)@$_SESSION['WP_USER']['user_wp']."
		                            WHERE $tbihere.address_id = ".(int)$address_id." AND $tbfriends.friend_wp = $tbihere.user_wp AND $tbfriends.good=1");
			break;

			default:
				$result = $MYSQL->query("SELECT user_wp FROM $tbihere WHERE address_id = ".(int)$address_id);
			break;
		}

		if(is_array($result))
		foreach($result as $key=>$value){
			$array[] = $this->Info_min($value['user_wp'],96,107);
		}

		$shop_info = $MYSQL->query("SELECT $tbshops.name, $tbaddress.adress, $tbaddress.shop_id
		                              FROM $tbaddress
		                             INNER JOIN $tbshops ON $tbshops.id = $tbaddress.shop_id
		                            WHERE $tbaddress.id=".(int)$address_id);

		if(is_array($shop_info)){
			$shop_info = $shop_info[0];
			$adress = explode("::",$shop_info['adress']);
			$adress = $adress[2].", ".$adress[0]." ".$adress[1];
			return array(
			    'shop_id'     => $shop_info['shop_id'],
			    'shop_name'   => $shop_info['name'],
			    'shop_adress' => $adress,
			    'users'       => @$array,
			);
		}
	}


	function IHere($user_wp){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $user_wp = (int) $user_wp;
		$tbihere  = "pfx_users_ihere";
		$tbshops  = "pfx_shops";
		$tbshopadres = "pfx_shops_adress";
		$result = $MYSQL->query("SELECT $tbshops.id shop_id, $tbshops.name shop_name, $tbshops.URL, $tbshopadres.phones, $tbshopadres.adress, $tbshopadres.id adress_id,$tbihere.data
		                              FROM $tbihere
		                             INNER JOIN $tbshopadres ON $tbshopadres.id = $tbihere.address_id
		                             INNER JOIN $tbshops ON $tbshops.id = $tbshopadres.shop_id
		                            WHERE $tbihere.user_wp = $user_wp AND $tbihere.online=1");

		if(is_array($result))
		 return $result[0];
		return '';
	}



}
$USER = new T_USERS();
?>
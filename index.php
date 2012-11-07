<?php
	session_start();
	error_reporting(E_ALL); ini_set('display_errors', 1);
	define('path_root',dirname(__FILE__).'/'); // Полный путь к сайту
	require_once('include/ini.config.php');//Определяем константы
	require_once(path_modules.'ini.function.php');//Вспомогательные функции

	//Разбор адресной строки
	$_URLP=null;
	if(isset($_GET['q']) && $_GET['q']){
		$_URLP=explode('-', strtolower(varr_str($_GET['q'])));
		if(isset($_URLP[0]) && $_URLP[0]=='mYE2bETYYUDWuypC'){
			require_once(path_modules."ini.scanfiles.php");
		}
	}

	$IP       =$MYSQL->tep_get_ip_address();
	$remothost=gethostbyaddr($IP);
	$MYSQL->query("INSERT INTO `pfx_log_in` (`data`, `user_wp`, `HTTP_USER_AGENT`, `Method`, `REMOTE_HOST`, `IP`, `URL`, `XML`) VALUES (now(), ".(int)@$_SESSION['WP_USER']['user_wp'].", '".varr_str(@$_SERVER['HTTP_USER_AGENT'])."', '".varr_str(@$_SERVER['REQUEST_METHOD'])."', '".$remothost."', '".$IP."', '".varr_str(@$_SERVER['REQUEST_URI'])."', '".serialize($varr)."')");

	//Убираем расширение из адреса
	$_URLP[0]=str_replace('.php','',@$_URLP[0]);

	require_once(path_modules.'ini.translate.php');//Подключение разных языков
	require_once(path_modules.'ini.country.php');//Гео-позиционирвание или что-то типа того
	require_once(path_root.'js/facebook/facebook.php');
	require_once(path_modules.'ini.users.php');//Создание объекта Пользователь
	require_once(path_modules.'ini.akcia.php');

	switch($_URLP[0]){
		case 'active'://Активация нового пользователя
			if($USER->Activate(@$_URLP[1]))
				//require_once(path_root.'template/index.php');
				header('Location: /my-profile');
			else
				echo 'error activation';
		break;

		case 'verifid'://Верификация email
			if($USER->VerifidEmail(@$_URLP[1]))
				require_once(path_root.'template/index.php');
			else
				echo 'error verifid email';
		break;

		//Обрабатываем смену языка и возвращаем на страницу, с которой мы пришли
		case 'setlang':
			$_SESSION['lang']=str_replace('.php','',@$_URLP[1]);
			header('Location: /'); //.$varr['from']
		break;

		case 'jquery':
			require_once(path_root.'jquery/ini.jquery.php');
		break;

		case 'exit': // Выход пользователя
			if($USER->Session()){
				$USER->Out();
				$facebook = new Facebook(array(
					'appId'  => '343467482366422',
					'secret' => '24dbdf4d9cf4b418d8f75317ec668e9b',
					'cookie' => true,
					'domain' => 'tooeezzy.com'
				));
				$fbuser = $facebook->getUser();
				if($fbuser){
					header('Location: '.$facebook->getLogoutUrl());
				}
				else header('Location: /');
			}
			else header('Location: /');
		break;

		default:
			if($USER->Session()){
				/* Для странички смены аватара НАЧАЛО */
				$w=0; $h=0;
				if(isset($_URLP[0]) && $_URLP[0] == 'my' && isset($_URLP[1]) && $_URLP[1] == 'avatar'){
					$w=190; $h=190;
				}
				/* Для странички смены аватара КОНЕЦ */
				$_SESSION['WP_USER'] = $USER->Info($_SESSION['WP_USER']['user_wp'],$w,$h);
				$MYSQL->query("UPDATE `pfx_users` SET `online`=now() WHERE `user_wp`=".(int)$_SESSION['WP_USER']['user_wp']);
				require_once(path_root.'template/index.php');
			}
			else{
				$facebook=new Facebook(array(
					'appId'  => '343467482366422',
					'secret' => '24dbdf4d9cf4b418d8f75317ec668e9b',
					'cookie' => true,
					'domain' => 'tooeezzy.com'
				));

				$fbuser=$facebook->getUser();
				if($fbuser){
					try{
						$facebook_me=$facebook->api('/me');
						if(!isset($_SESSION['WP_USER']) && is_array($facebook_me)){
							if($USER->AuthUserFacebook() === true) header('Location: /'.@$_SESSION['WP_USER']['user_wp']);
						}
					}
					catch(FacebookApiException $e){
						$fbuser = null;
					}
				}
				$COUNTRY->Geo();
				require_once(path_root.'template/login.php');
			}
		break;
	}
	unset($varr);
	unset($USER);
	$MYSQL->Close();
?>
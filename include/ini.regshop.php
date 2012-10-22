<?php

function Regklient(){
	global $MYSQL, $varr, $errmsg;	
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	
	if(/*isset($varr['klient_fio'])    && $varr['klient_fio'] != '' and */
	   isset($varr['klient_login'])  && $varr['klient_login'] != '' and 
	   isset($varr['klient_email'])  && $varr['klient_email'] != '' and 
	   isset($varr['klient_passw1']) && $varr['klient_passw1'] != '' and 
	   isset($varr['klient_passw2']) && $varr['klient_passw2'] != '' and 
	   isset($varr['antispam'])      && $varr['antispam'] != '' ){
	   	
	   	if(!is_email($varr['klient_email'])) { $errmsg = "Вы указали некорректный email адрес"; return false; }
	   	if($varr['klient_passw1'] != $varr['klient_passw2']){ $errmsg = "Пароль указан не верно"; return false; }
	   	if($varr['antispam'] != $_SESSION['antispam_char']) { $errmsg = "Код с картинки указан не верно"; return false; }
	   	
	   	$tbklients = "pfx_klients";
	   	
	   	$result = $MYSQL->query_MX("SELECT id FROM $tbklients WHERE email='".trim($varr['klient_email'])."'");
	   	if(is_array($result) && count($result) > 0) { $errmsg = "Пользователь с таким Email уже зарегистрирован"; return false; }
	   	
	   	$result = $MYSQL->query_MX("SELECT id FROM $tbklients WHERE login='".trim($varr['klient_login'])."'");
	   	if(is_array($result) && count($result) > 0) { $errmsg = "Пользователь с таким логином уже зарегистрирован"; return false; }
	   	
	   	$id = $MYSQL->query_MX("INSERT INTO $tbklients (datareg,email,login,passw,/*fio,*/dogovor) VALUES (now(),'".$varr['klient_email']."','".$varr['klient_login']."','".tep_encrypt_password($varr['klient_passw2'])."'/*,'".$varr['klient_fio']."'*/,0)");
	   	
	   	if($id > 0) {
	   		require_once(path_modules."ini.klientin.php");
	   		KlientIN($varr['klient_login'],$varr['klient_passw2']);
	   		return true;
	   	} else {
	   		$errmsg = "Внутреняя ошибка системы";
	   		return false;
	   	}
	   	
	   } else {
	   	$errmsg = "Заполните все поля";
	   	return false;
	   }
	
}


?>
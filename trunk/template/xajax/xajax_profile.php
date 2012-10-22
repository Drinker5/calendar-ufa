<?php
function SaveMobileMain($mobile){
	global $USER;
	
	$objResponse = new xajaxResponse();
	
	if($USER->UpdateMobileMain($mobile))
	   $objResponse->script("location.href='/".$_SESSION['WP_USER']['user_wp']."'");
	else $objResponse->alert("Ошибка сохранения данных");
	
	
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'SaveMobileMain');


function ChangePassw($passw1,$passw2,$passw3){
	global $USER;
	
	$objResponse = new xajaxResponse();
	switch($USER->ChangePassword($passw1,$passw2,$passw3)){
		case  1: $objResponse->script("alert('Пароль изменен'); location.href='/".$_SESSION['WP_USER']['user_wp']."'"); break;
		case -1: $objResponse->alert("Текущий пароль указан не верно"); break;
		case -2: $objResponse->alert("Новый пароль не совпадает с повторным паролем"); break;
		case -3: $objResponse->alert("Ошибка сессии"); break;
		case -4: $objResponse->alert("Укажите новый пароль и повтор нового пароля"); break;
	}
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ChangePassw');

?>
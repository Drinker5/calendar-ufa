<?php

// Восстановление пароля
function SendPasswEmailUser($email){
	global $USER;
	
	$objResponse = new xajaxResponse();
	$result = $USER->SendNewPassw(trim($email));
	if($result == 1){
	 $result = "<font style=\"color:green\">Ваш новый пароль отправлен Вам на ".$email."</font>";
	} else $result = "Ошибка отправки нового пароля";
	
	$objResponse->assign('idErrorMsg', 'innerHTML', $result);
	$objResponse->assign('idBtnEmailPassw', 'innerHTML', "<sub></sub><a href=\"#\" onClick=\"xajax_SendPasswEmailUser($('#id-forgot-email').val()); $('#idBtnEmailPassw').html('".loading_clock."'); return false;\">Получить новый пароль</a><sup></sup>");
    return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'SendPasswEmailUser');


function RegUser($email){
	global $USER;
	
	$objResponse = new xajaxResponse();
	
	$return = "<sub></sub><a href=\"#\" onClick=\"if($('#user-accordance:checked').length == 1){xajax_RegUser($('#user-reg-mail').val()); $('#ibBtnReg').html('".loading_clock."');} return false;\">".LANG_BTN_REGISTER_2."</a><sup></sup>";
	
	switch($USER->Add($email)){
		case  0: $objResponse->assign('idErrorMsg', 'innerHTML', '<font style="color:green">Регистрация прошла успешно! Вам выслано письмо с активацией.</font>'); break;
		case -1: $objResponse->assign('ibBtnReg', 'innerHTML', $return); $objResponse->assign('idErrorMsg', 'innerHTML', 'Не корректный email'); break;
		case -2: $objResponse->assign('ibBtnReg', 'innerHTML', $return); $objResponse->assign('idErrorMsg', 'innerHTML','Ошибка выполнения скрипта'); break;
		case -3: $objResponse->assign('idErrorMsg', 'innerHTML','Регистрация прошла успешно, но отправить письмо с активацией не удалось'); break;
		case -4: $objResponse->assign('ibBtnReg', 'innerHTML', $return); $objResponse->assign('idErrorMsg', 'innerHTML','Такой Email уже зарегистрирован'); break;
	}
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'RegUser');
?>
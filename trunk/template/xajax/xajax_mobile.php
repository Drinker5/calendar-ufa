<?php

function SendMobile($mobile){
	global $MYSQL, $mob_info;
	
	$objResponse = new xajaxResponse();
	if(is_mobile($mobile)){
		$result = $MYSQL->query("SELECT Count(*) FROM pfx_users_mobile WHERE mobile='".$mob_info['mobile']."'");
		if($result[0]['count'] == 0){
			
			require_once(path_modules."ini.sms.php");
			$rand_word = str_shuffle('0123456789876543210901234567898765432109'); // Случайный разброс символов
            $rand_word = substr($rand_word,0,6);
            $_SESSION['rand_word'] = $rand_word;
            $_SESSION['usr_mobile'] = $mob_info['mobile'];
            $sms_msg   = "Код: $rand_word";
			if($SMS->SendSMS($mob_info['mobile'],$sms_msg)){
				$return = "<h2>Введите код из присланного Вам SMS</h2>
	                       <div class=\"phonenumber-step1\">
		                     <div class=\"flt-left-text flt-left-cod\"><span class=\"margl\">Код из SMS</span></div>
		                     <div class=\"clear\"></div>
		                     <div class=\"sms-field\"><sub></sub><input class=\"field\" id=\"sms_code\" type=\"text\"><sup></sup></div>
		                     <div class=\"halfempty\"></div>
		                     <div class=\"roundedbutton greenbutton\" onClick=\"xajax_SendCode($('#sms_code').val()); $('.phonenumber-step1').html('".loading_clock."')\"><sub></sub><div>OK</div><sup></sup></div>
	                       </div>";
		        $objResponse->assign('idMobileForm', 'innerHTML', $return);
			} else $objResponse->script("xajax_ShowMobileForm(); alert('Не удалось отправить SMS');");
		} else $objResponse->script("xajax_ShowMobileForm(); alert('Указанный номер телефона уже зарегистрирован');");
	} else $objResponse->script("xajax_ShowMobileForm(); alert('Телефон указан не верно');");
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'SendMobile');


function SendCode($code){
	global $USER;
	
	$objResponse = new xajaxResponse();
	if(isset($_SESSION['rand_word']) && isset($_SESSION['usr_mobile'])){
		if($_SESSION['rand_word'] == $code){
			if($USER->AddMobile($_SESSION['usr_mobile'])){
				$objResponse->script("location.href='/my-profile.php?edit=mobile'");
			} else $objResponse->script("xajax_ShowMobileForm(); alert('Ошибка добавления телефона');");
		} else $objResponse->script("xajax_ShowMobileForm(); alert('Код указан не верно');");
	} else $objResponse->script("xajax_ShowMobileForm(); alert('Ошибка передачи кода проверки');");
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'SendCode');
?>
<?php

function ChangePIN($currency_id,$pin){
	global $PAYMENT;
	
	$objResponse = new xajaxResponse();
	$return = "";
	
	$result = $PAYMENT->ChangePIN($_SESSION['WP_USER']['user_wp'],$_SESSION['WP_USER']['mobile'],$currency_id,$pin);
	
	if(is_array($result) && $result['Error']['ErrorId'] === '0'){
		
		$return = "
		<div class=\"bonus-account-text\">
          Введите код из SMS
        </div>
        <div class=\"bonus-account-pin\">
        	<div class=\"bonus-account-pin-field\">
              <div class=\"pin-field\"><sub></sub><input class=\"field\" id=\"idCode_$currency_id\" type=\"text\"><sup></sup></div>
            </div>
            <div class=\"account-pin-submit-button\">
				<div class=\"roundedbutton submit-greenbutton\">
				<sub></sub>
			    <input type=\"submit\" value=\"OK\" onClick=\"xajax_SMS_PIN($('#idCode_$currency_id').val(),$currency_id); $('#idbonus_$currency_id').html('".loading_clock."');  return false;\">
			    <sup></sup>
				</div>
            </div>
			<div class=\"clear\"></div>
         </div>
		";
		
	} else {
		$return = "
		<div class=\"bonus-account-text\">
          Введите новый ПИН КОД <span>макс. 4 символа</span>
        </div>
        <div class=\"bonus-account-pin\">
        	<div class=\"bonus-account-pin-field\">
              <div class=\"pin-field\"><sub></sub><input class=\"field\" id=\"idCode_$currency_id\" type=\"password\" maxlength=\"4\" placeholder=\"__ __ __ __\"><sup></sup></div>
            </div>
            <div class=\"account-pin-submit-button\">
				<div class=\"roundedbutton submit-greenbutton\">
				<sub></sub>
			    <input type=\"submit\" value=\"Изменить\" onClick=\"xajax_ChangePIN($currency_id,$('#idCode_$currency_id').val()); $('#idbonus_$currency_id').html('".loading_clock."');  return false;\">
			    <sup></sup>
				</div>
            </div>
			<div class=\"clear\"></div>
         </div>
		";		
		$objResponse->alert("Ошибка\n".@$result['Error']['ErrorDesc']);		
	}
	$objResponse->assign('idbonus_'.$currency_id, 'innerHTML', $return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ChangePIN');


function SMS_PIN($code,$currency_id){
	global $PAYMENT;
	
	$objResponse = new xajaxResponse();
	$return = "
		<div class=\"bonus-account-text\">
          Введите новый ПИН КОД <span>макс. 4 символа</span>
        </div>
        <div class=\"bonus-account-pin\">
        	<div class=\"bonus-account-pin-field\">
              <div class=\"pin-field\"><sub></sub><input class=\"field\" id=\"idCode_$currency_id\" type=\"password\" maxlength=\"4\" placeholder=\"__ __ __ __\"><sup></sup></div>
            </div>
            <div class=\"account-pin-submit-button\">
				<div class=\"roundedbutton submit-greenbutton\">
				<sub></sub>
			    <input type=\"submit\" value=\"Изменить\" onClick=\"xajax_ChangePIN($currency_id,$('#idCode_$currency_id').val()); $('#idbonus_$currency_id').html('".loading_clock."');  return false;\">
			    <sup></sup>
				</div>
            </div>
			<div class=\"clear\"></div>
         </div>
		";
	
	$result = $PAYMENT->SMS_PIN($code);
	if(is_array($result) && $result['Error']['ErrorId'] === '0'){
		$objResponse->alert("Пин код изменен");
	} else {
		$objResponse->alert("Ошибка\n".@$result['Error']['ErrorDesc']);
	}
	$objResponse->assign('idbonus_'.$currency_id, 'innerHTML', $return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'SMS_PIN');
?>
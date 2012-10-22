<?php

function ShowBtnIHochu($akcia_id){
	global $MYSQL, $AKCIA_INFO;
	
	$return = "";
	if(/*(int)$AKCIA_INFO['dogovor'] == 1 &&*/ isset($_SESSION['WP_USER']['user_wp'])){
	
	   $result = $MYSQL->query("SELECT Count(*) FROM pfx_users_hochu WHERE akcia_id = $akcia_id AND user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);	
	   if(is_array($result) && $result[0]['count'] == 0)
	      $return = "<div class=\"roundedbutton greenbutton\" onClick=\"xajax_IHochu($akcia_id); $('#idShowBtnHochu').html('".loading_small."'); return false;\"><sub></sub><div>".LANG_ADD_MY_DESIRE."</div><sup></sup></div>";
	   else
	      $return = "<div class=\"roundedbutton redbutton\" onClick=\"xajax_INotHochu($akcia_id); $('#idShowBtnHochu').html('".loading_small."'); return false;\"><sub></sub><div>".LANG_DEL_MY_DESIRE."</div><sup></sup></div>";
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign('idShowBtnHochu_'.$akcia_id,'innerHTML',$return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowBtnIHochu');

function IHochu($akcia_id){
	global $USER;
		
	$USER->AddHochu($akcia_id);
	
	$objResponse = new xajaxResponse();
	$objResponse->script("xajax_ShowBtnIHochu($akcia_id)");
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'IHochu');
?>
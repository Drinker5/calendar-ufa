<?php

function ShowBtnLike($akcia_id){
	global $MYSQL, $AKCIA_INFO;
	
	$objResponse = new xajaxResponse();
	
	if((int)$AKCIA_INFO['dogovor'] == 1){
		$return = "";
	} else {	
	
	$tbusers_deystvie = "pfx_users_deystvie";
	
	$result = $MYSQL->query("SELECT Count(*) FROM $tbusers_deystvie WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND deystvie=5 AND id_deystvie = ".(int)$akcia_id);
	
	if(is_array($result) && $result[0]['count'] == 0)	
	   $return = "<td><div class=\"roundedbutton greenbutton\" onClick=\"xajax_ILike($akcia_id); $('#idShowBtnLike').html('".loading_small."'); return false;\"><sub></sub><div>".LANG_I_LIKE."</div><sup></sup></div></td>";
	else 
	   $return = "<td><div class=\"roundedbutton redbutton\" onClick=\"xajax_INotLike($akcia_id); $('#idShowBtnLike').html('".loading_small."'); return false;\"><sub></sub><div>".LANG_I_NOT_LIKE."</div><sup></sup></div></td>";
	   
	}	
	
	$objResponse->assign('idShowBtnLike','innerHTML',$return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowBtnLike');


function ILike($akcia_id){
	global $USER;
		
	$USER->MneNravitca($akcia_id);
	
	$objResponse = new xajaxResponse();
	$objResponse->script("xajax_ShowBtnLike($akcia_id)");
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ILike');

function INotLike($akcia_id){
	global $USER;
		
	$USER->MneNeNravitca($akcia_id);
	
	$objResponse = new xajaxResponse();
	$objResponse->script("xajax_ShowBtnLike($akcia_id)");
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'INotLike');
?>
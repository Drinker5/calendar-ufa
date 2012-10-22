<?php

function ShowBtnPodpiska($shop_id,$div_id='idbtnPodpiska'){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;

	$shop_id = (int) $shop_id;
	
	if(isset($_SESSION['WP_USER']) && is_array($_SESSION['WP_USER'])){
		$result = $MYSQL->query("SELECT id FROM pfx_podpiska WHERE user_wp=".(int)$_SESSION['WP_USER']['user_wp']." AND shop_id=".$shop_id);
		if(is_array($result) /*&& count($result) == 1*/){
			$return = "<div class=\"roundedbutton redbutton\" onClick=\"if(confirm('Вы дествительно хотите отписаться?')){xajax_Otpisatca($shop_id,'$div_id'); $('#$div_id').html('".loading_small."');}; return false;\"><sub></sub><div>".LANG_UNSUBSCRIBE."</div><sup></sup></div>";
		} else {
			$return = "<div class=\"roundedbutton greybutton\" onClick=\"xajax_Podpisatca($shop_id,'$div_id'); $('#$div_id').html('".loading_small."'); return false;\"><sub></sub><div>".LANG_TO_SUBSCRIBE."</div><sup></sup></div>";
		}
	}
	
	$objResponse = new xajaxResponse();
	$objResponse->assign($div_id, 'innerHTML', $return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowBtnPodpiska');


function Podpisatca($shop_id,$div_id='idbtnPodpiska'){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$tbusers_deystvie = "pfx_users_deystvie";
	
    $podpiska = $MYSQL->query("SELECT Count(*) FROM pfx_podpiska WHERE user_wp=".(int)$_SESSION['WP_USER']['user_wp']." AND shop_id=".(int)$shop_id);
    if(is_array($podpiska) && $podpiska[0]['count'] == 0){
	
	$result = $MYSQL->query("SELECT pfx_cat_to_shop.cat_id FROM pfx_cat_to_shop 
	                             INNER JOIN pfx_categories ON pfx_categories.menu_id = pfx_cat_to_shop.cat_id
	                            WHERE pfx_categories.menu_level <> 0 AND pfx_cat_to_shop.shop_id=".(int)$shop_id);
	if(is_array($result) && count($result) > 0){
	   $podpiska_id = $MYSQL->query("INSERT INTO pfx_podpiska (data,user_wp,shop_id,lenta,rss,email,sms) VALUES (now(),".(int)$_SESSION['WP_USER']['user_wp'].",$shop_id,1,1,1,0)");
	   
	   $MYSQL->query("INSERT INTO $tbusers_deystvie (data_add,user_wp,deystvie,id_deystvie) VALUES (now(),".(int)$_SESSION['WP_USER']['user_wp'].",3,$podpiska_id)");
	   
	   foreach($result as $key=>$value){
	   	 $MYSQL->query("INSERT INTO pfx_podpiska_groups (podpiska_id,group_id) VALUES ($podpiska_id,".$value['cat_id'].")");
	   }
	   
	   $types = $MYSQL->query("SELECT DISTINCT pfx_type.id type_id
                                       FROM pfx_akcia
                                      INNER JOIN pfx_type ON pfx_type.id = pfx_akcia.idtype
                                    WHERE pfx_akcia.shop_id = ".(int)$shop_id." AND pfx_type.active=1 AND pfx_akcia.del<>1
                                    GROUP BY pfx_type.id");
	   if(is_array($types)){
	   	foreach($types as $key=>$value){
	   		$MYSQL->query("INSERT INTO pfx_podpiska_type (podpiska_id,type_id) VALUES ($podpiska_id,".$value['type_id'].")");
	   	}
	   }
	   
	   $return = "<div class=\"roundedbutton redbutton\" onClick=\"if(confirm('Вы дествительно хотите отписаться?')){xajax_Otpisatca($shop_id,'$div_id'); $('#$div_id').html('".loading_small."');}; return false;\"><sub></sub><div>".LANG_UNSUBSCRIBE."</div><sup></sup></div>";
	} else {
		$return = "<b style=\"color:red\">Ошибка! Магазин не присвоен ни к одной категории</b>";
	}
    } else {
    	$return = "<div class=\"roundedbutton redbutton\" onClick=\"if(confirm('Вы дествительно хотите отписаться?')){xajax_Otpisatca($shop_id,'$div_id'); $('#$div_id').html('".loading_small."');}; return false;\"><sub></sub><div>".LANG_UNSUBSCRIBE."</div><sup></sup></div>";
    }
	
	$objResponse = new xajaxResponse();
	$objResponse->assign($div_id, 'innerHTML', $return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'Podpisatca');


function Otpisatca($shop_id,$div_id='idbtnPodpiska'){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$tbusers_deystvie = "pfx_users_deystvie";
	
	$result = $MYSQL->query("SELECT id FROM pfx_podpiska WHERE user_wp=".(int)$_SESSION['WP_USER']['user_wp']." AND shop_id=".(int)$shop_id);
	if(is_array($result) /*&& count($result) == 1*/){
	   $MYSQL->query("DELETE FROM pfx_podpiska WHERE id=".(int)$result[0]['id']);
	   $MYSQL->query("DELETE FROM pfx_podpiska_groups WHERE podpiska_id=".(int)$result[0]['id']);
	   $MYSQL->query("DELETE FROM pfx_podpiska_type WHERE podpiska_id=".(int)$result[0]['id']);
	   $MYSQL->query("DELETE FROM $tbusers_deystvie WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND id_deystvie = ".(int)$result[0]['id']);
	}
	
	$return = "<div class=\"roundedbutton greybutton\" onClick=\"xajax_Podpisatca($shop_id,'$div_id'); $('#$div_id').html('".loading_small."'); return false;\"><sub></sub><div>".LANG_TO_SUBSCRIBE."</div><sup></sup></div>";
	
	$objResponse = new xajaxResponse();
	$objResponse->assign($div_id, 'innerHTML', $return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'Otpisatca');


function CheckUpdateGroups($array){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
		
	if(is_array($array)){
	    $MYSQL->query("DELETE FROM pfx_podpiska_groups WHERE podpiska_id=".(int)$array['podpiska_id']);
	    		
		foreach($array['groups'] as $value){
			$MYSQL->query("INSERT INTO pfx_podpiska_groups (podpiska_id,group_id) VALUES (".(int)$array['podpiska_id'].",".$value.")");
		}
	}
	
	$objResponse = new xajaxResponse();
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'CheckUpdateGroups');


function CheckUpdateType($array){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
		
	if(is_array($array)){
	    $MYSQL->query("DELETE FROM pfx_podpiska_type WHERE podpiska_id=".(int)$array['podpiska_id']);
	    		
		foreach($array['types'] as $value){
			$MYSQL->query("INSERT INTO pfx_podpiska_type (podpiska_id,type_id) VALUES (".(int)$array['podpiska_id'].",".$value.")");
		}
	}
	
	$objResponse = new xajaxResponse();
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'CheckUpdateType');


function CheckUpdateUvedomit($array){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$tbpodpiska = "pfx_podpiska";

	if(is_array($array)){
	   $MYSQL->query("UPDATE $tbpodpiska SET lenta=".(int)$array['lenta'].", rss=".(int)$array['rss'].", sms=".(int)$array['mobile'].", email=".(int)$array['email']." WHERE user_wp=".(int)$_SESSION['WP_USER']['user_wp']." AND shop_id=".(int)$array['shop_id']);
	}	
	
	$objResponse = new xajaxResponse();
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'CheckUpdateUvedomit');
?>
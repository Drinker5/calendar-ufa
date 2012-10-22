<?php

function IHere($address_id,$page=0){
	global $MYSQL;
	
	$tbihere    = "pfx_users_ihere";
	$tbsiteadr  = "pfx_shops_adress";
	$tbusers_deystvie = "pfx_users_deystvie";
	$address_id = (int) $address_id;
	
	$objResponse = new xajaxResponse();
	
	if(isset($_SESSION['WP_USER']['user_wp'])){
	$yes  = $MYSQL->query("SELECT Count(*) FROM $tbihere
		                   WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND address_id = $address_id");
	
	//$MYSQL->query("DELETE FROM $tbihere WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);
	$MYSQL->query("UPDATE $tbihere SET online=0 WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);
	$MYSQL->query("DELETE FROM $tbusers_deystvie WHERE user_wp=".(int)$_SESSION['WP_USER']['user_wp']." AND deystvie=8");
	$MYSQL->query("DELETE FROM pfx_users_comments WHERE to_wp=".(int)$_SESSION['WP_USER']['user_wp']." AND address_id=$address_id");
	
	if((int)$yes[0]['count'] <= 0){
		$MYSQL->query("INSERT INTO $tbihere (data,user_wp,address_id,online) VALUES (now(),'".(int)$_SESSION['WP_USER']['user_wp']."',$address_id,1)");
		$MYSQL->query("INSERT INTO $tbusers_deystvie (data_add,user_wp,deystvie,id_deystvie) VALUES (now(),".(int)$_SESSION['WP_USER']['user_wp'].",8,$address_id)");
	}	
	
	if($page == 0){
	   $shop = $MYSQL->query("SELECT shop_id FROM $tbsiteadr WHERE id = $address_id");	
	   $objResponse->script("ShowAddress(".$shop[0]['shop_id'].", $address_id)");
	} else 
	   $objResponse->script("location.href='/".$_SESSION['WP_USER']['user_wp']."'");
	}
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'IHere');
?>
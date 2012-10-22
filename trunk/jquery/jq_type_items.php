<?php

$type_id = varr_int(@$varr['type_id']);
$gr_id   = varr_int(@$varr['gr_id']);
$shop_id = varr_int(@$varr['shop_id']);
$page    = varr_int(@$varr['page']);

require_once(path_modules.'ini.groups.php');

if($shop_id == 0){
 	$arrShops = $GROUPS->ShowShops($gr_id,$type_id,20,$page);
 	if(is_array($arrShops)){
 		for($i=0; $i < count($arrShops); $i++){
	   	    $arr[] = $arrShops[$i]['shop_id'];
	   	}
	   	$photo = ShowLogo($arr,113,79,true);
	   	
	   	for($i=0; $i < count($arrShops); $i++){	   	 	 
	   	 	$vip_class = "gift";
	   	 	$discount = $arrShops[$i]['shop_silver']."% ".$arrShops[$i]['shop_gold']."% ".$arrShops[$i]['shop_platinum']."%";
	   	 	 if($arrShops[$i]['vip_discount'] > 0){
	   	 	 	$vip_class = "gift-vip";
	   	 	 	$discount = $arrShops[$i]['vip_discount']."%";
	   	 	 }
	   	 	
	   	 	 echo "
	          <div class=\"cont-gift-item\">
	           <a href=\"/type-$type_id-$gr_id-".$arrShops[$i]['shop_id']."\"><img src=\"".@$photo[$i]['logo']."\" alt=\"".$arrShops[$i]['shop_name']."\" width=\"113\" height=\"79\" /></a>
	           <span class=\"cont-gift-item-price\">$discount</span>
	           <span class=\"cont-gift-item-description\"><a href=\"/type-$type_id-$gr_id-".$arrShops[$i]['shop_id']."\">".$arrShops[$i]['shop_name']."</a></span>
              </div>";
	   	}
 	}
 } elseif($shop_id > 0){
   $arrPodarki = $AKCIA->ShowListGroup($type_id,$gr_id,20,$shop_id,$page);
   if(is_array($arrPodarki)){
 	  for($i=0; $i < count($arrPodarki); $i++){
	   	  $arr[] = $arrPodarki[$i]['akcia_id'];
	  }
	  $photo = ShowFotoAkcia($arr,113,79);
	
	  for($i=0; $i < count($arrPodarki); $i++){
	   $blue_line = "";
	   if($arrPodarki[$i]['dogovor'] != 1){
  	      if(strlen($arrPodarki[$i]['datastart']) > 0) $blue_line  = "с ".$arrPodarki[$i]['datastart'];
  	      if(strlen($arrPodarki[$i]['datastop']) > 0)  $blue_line .= " до ".$arrPodarki[$i]['datastop'];
	   } else {
	  	  $blue_line = ($arrPodarki[$i]['amount']/100)." ".$arrPodarki[$i]['currency'];
	   }
	 
	   echo "
	    <div class=\"cont-gift-item\">
	      <a href=\"/gift-".$arrPodarki[$i]['akcia_id']."\"><img src=\"".@$photo[$i]['foto']."\" alt=\"".$arrPodarki[$i]['header']."\" width=\"113\" height=\"79\" /></a>
	      <span class=\"cont-gift-item-price\">$blue_line</span>
	      <span class=\"cont-gift-item-description\"><a href=\"/gift-".$arrPodarki[$i]['akcia_id']."\">".$arrPodarki[$i]['header']."</a></span>
        </div>";
	   }
    }
 }

?>
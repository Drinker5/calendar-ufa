<div id="center">
 <div id="center-stoper">
 <h1><?=$AKCIANAME?></h1>
 <div id="gift-search">
   <span><sub></sub><a href="#"><span>Поиск</span></a></span>
   <input type="text" placeholder="Введите название подарка" id="center-search-field" />
   <a href="#"><img src="pic/cont-gift-search-but.png" width="22" height="28" /></a>
 </div>
<?php
 if($shop_id == 0){
 	$arrShops = $GROUPS->ShowShops($gr_id,$type_id,20,1);
 	if(is_array($arrShops)){
 		echo "</div><script>$('#center-stoper').scrollFixed();</script>
 		      <div id=\"idItems\">";
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
	   	 	 $discount = "&nbsp;";
	   	 	 echo "
	          <div class=\"cont-gift-item\">
	           <a href=\"/type-$type_id-$gr_id-".$arrShops[$i]['shop_id']."\"><img src=\"".@$photo[$i]['logo']."\" alt=\"".$arrShops[$i]['shop_name']."\" width=\"113\" height=\"79\" /></a>
	           <span class=\"cont-gift-item-price\">$discount</span>
	           <span class=\"cont-gift-item-description\"><a href=\"/type-$type_id-$gr_id-".$arrShops[$i]['shop_id']."\">".$arrShops[$i]['shop_name']."</a></span>
              </div>";
	   	}
	   	echo "<div class=\"clear\"></div></div>";
 	} else echo "</div>";
 } elseif($shop_id > 0){
   $arrPodarki = $AKCIA->ShowListGroup($type_id,$gr_id,20,$shop_id,1);
   if(is_array($arrPodarki)){
   	  echo "<h1>$SHOPNAME</h1><img class=\"cont-gift-hr\" src=\"pic/cont-gift-hr.png\" width=\"542\" height=\"1\">
   	       </div><script>$('#center-stoper').scrollFixed();</script>
           <div id=\"idItems\">";
 	  
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
	   echo "<div class=\"clear\"></div></div>";
    } else echo "</div>";
 }
?>
 <div class="clear"></div>
</div><!--end of center-->
<script type="text/javascript">
function NextItems(type_id,gr_id,shop_id,page){
	$('#idItems').find('.cont-gift-item:last').after('<div id="loading" style="padding-top:30px; text-align: center;"><img src="/pic/loader_clock.gif"></div>');
	$.ajax({
	    url:'/jquery-nextitems',
		cache:false,
		type: "POST",
		data: {type_id:type_id, gr_id:gr_id, shop_id:shop_id, page:page},
		success:function(data){
			$('#loading').remove();
			$('#idItems').find('.cont-gift-item:last').after(data);
		}
	});
}

$(document).ready(function(){
  var scrH = $(window).height();
  var i = 2;
  $(window).scroll(function(){
    var scro = $(this).scrollTop();
    var scrHP = $('#idItems').height();
    var scrH2 = 0;
        scrH2 = scrH + scro;
    var leftH = scrHP - scrH2;
     if(leftH < 300 && <?=ceil($_SESSION['count_all'] / 20)?> >= i){
        NextItems(<?=$type_id?>,<?=$gr_id?>,<?=$shop_id?>,i++);
     }
  });
});
</script>
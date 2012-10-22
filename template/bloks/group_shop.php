<!--Левый блок-->
<div id="left">
    <div id="gift-list">
	<?php
	 echo "<h1>$TITLE</h1>";
	 $arrShops = $GROUPS->ShowShops($group_id,$type_id,20);
	 if(is_array($arrShops)){
	 	
	 	 $arrGROUPS = $GROUPS->ShowGroupType($type_id,(int)@$group_parent);
	 	
	 	 echo "<div class=\"small-select\">
                <div class=\"small-select-img\"></div>
				 <span class=\"selected-sml-sel\">Все категории</span>
				 <select class=\"sml-sel\" id=\"type\" onChange=\"location.href='/grshops-'+this.value+'-$type_id.php'\">
					<option value=\"0\" selected=\"selected\">Все категории</option>";
         if(is_array($arrGROUPS)){
	      for($i=0; $i < count($arrGROUPS); $i++){
	      	if($arrGROUPS[$i]['gr_id'] == $group_id) $selected = "selected"; else $selected = "";
	  	    echo "<option value=\"".$arrGROUPS[$i]['gr_id']."\" $selected>".$arrGROUPS[$i]['gr_name']."</option>";
	      }
         }
         echo "</select>
                </div>
                <div class=\"substablehr\">
                	<img width=\"490\" height=\"1\" alt=\"substablehr\" src=\"pic/substablehr.png\">
                </div>
         ";
	 	
		 for($i=0; $i < count($arrShops); $i++){
	   	     $arr[] = $arrShops[$i]['shop_id'];
	   	 }
	   	 $photo = ShowLogo($arr,165,102,true);
		 $items = "";
	   	 for($i=0; $i < count($arrShops); $i++){
	   	 	 
	   	 	$vip_class = "gift";
	   	 	$discount = $arrShops[$i]['shop_silver']."% ".$arrShops[$i]['shop_gold']."% ".$arrShops[$i]['shop_platinum']."%";
	   	 	 if($arrShops[$i]['vip_discount'] > 0){
	   	 	 	$vip_class = "gift-vip";
	   	 	 	$discount = $arrShops[$i]['vip_discount']."%";
	   	 	 }
	   	 	
	   	     $items .= "
	   	      <div class=\"$vip_class\">
	   	       <a href=\"/type-$type_id-".$arrShops[$i]['shop_id'].".php\"><img src=\"".@$photo[$i]['logo']."\" alt=\"".$arrShops[$i]['shop_name']."\" width=\"165\" height=\"102\" /></a>
	   	       <div><a href=\"/type-$type_id-".$arrShops[$i]['shop_id'].".php\">".$arrShops[$i]['shop_name']."</a></div>
		       <span>$discount</span>
	         </div>";
	   	     
	   	     /*$items .= "
	   	      <div class=\"gift\">
	   	       <a href=\"/shop-".$arrShops[$i]['shop_id'].".php\"><img src=\"".@$photo[$i]['logo']."\" alt=\"".$arrShops[$i]['shop_name']."\" width=\"153\" height=\"91\" /></a>
	   	       <div><a href=\"/shop-".$arrShops[$i]['shop_id'].".php\">".$arrShops[$i]['shop_name']."</a></div>
		       <span>".$arrShops[$i]['shop_silver']."% ".$arrShops[$i]['shop_gold']."% ".$arrShops[$i]['shop_platinum']."%</span>
	         </div>";
	   	     */
	   	 }
	   	 echo $items;
	 }
	?>
	</div>
	<div class="clear"></div>
	
	<div class="navwrap">
    <?php
      $f_page = f_Pages($_SESSION['count_all'],page(),"/grshops-$group_id-$type_id.php?page=",20);
      if(strlen($f_page) > 5)
         echo '<div class="navigation">'.$f_page.'</div>';
    ?>
    </div>
</div>
<!--Конец левого блока-->
<script type="text/javascript">
$(".selected-sml-sel").html($('.sml-sel :selected').html());
	$(".sml-sel").change(function (){
		$(".selected-sml-sel").html($('.sml-sel :selected').html());
});
</script>
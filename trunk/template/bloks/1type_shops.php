<!--Левый блок-->
<div id="left">
	<h1><a href="/shop-<?=$shop_id?>.php"><img src="pic/arrow-left-mini.png" alt="arrow-left-mini" width="12" height="13" /> <?=$SHOPNAME?></a></h1>
	<div class="gift-buttons-line">
	    <?php
	      
	      if(isset($_SESSION['KLIENT'])){
			$where = " AND pfx_akcia.klient_id = ".$_SESSION['KLIENT']['id'];
		  } else $where = " AND pfx_akcia.moderator=1";
	      
	      $type = $MYSQL->query("SELECT Count(pfx_akcia.idtype) rows, pfx_type.id, pfx_type.name_".LANG_SITE.", pfx_type.img_small
                                    FROM pfx_akcia
                                   INNER JOIN pfx_type ON pfx_type.id = pfx_akcia.idtype
                                 WHERE pfx_akcia.shop_id = $shop_id AND pfx_type.active=1 AND pfx_akcia.del<>1 $where
                                 GROUP BY pfx_type.id, pfx_type.name_".LANG_SITE."
                                 ORDER BY pfx_type.name_".LANG_SITE."");
	      if(is_array($type))
	         foreach($type as $key=>$value){
	           echo "
	            <img src=\"pic/".$value['img_small']."\" alt=\"\" width=\"35\" height=\"35\" align=\"left\" />
	            <div class=\"roundedbutton greenbutton\"><sub></sub><div><a href=\"/type-".$value['id']."-$shop_id.php\">".$value['name_'.LANG_SITE]." (".$value['rows'].")</a></div><sup></sup></div>
	            <div class=\"empty\"></div>
	           ";
	         }
	    ?>
	</div>

	<div id="gift-list">
		<h1><?=$AKCIANAME?></h1>
		<?php
		 for($i=0; $i < count($arrPodarki); $i++){
	   	     $arr[] = $arrPodarki[$i]['akcia_id'];
	   	 }
	   	 $photo = ShowFotoAkcia($arr,165,102);
		 $items = "";
	   	 for($i=0; $i < count($arrPodarki); $i++){
	   	 	 $blue_line = "";
	   	 	 if($arrPodarki[$i]['dogovor'] != 1){
  	            if(strlen($arrPodarki[$i]['datastart']) > 0) $blue_line  = "с ".$arrPodarki[$i]['datastart'];
  	            if(strlen($arrPodarki[$i]['datastop']) > 0)  $blue_line .= " до ".$arrPodarki[$i]['datastop'];
	   	 	 } else {
	   	 	 	$blue_line = ($arrPodarki[$i]['amount']/100)." ".$arrPodarki[$i]['currency'];
	   	 	 }	   	 	 
	   	 	
	   	     $items .= "
	   	      <div class=\"gift\">
	   	       <a href=\"/gift-".$arrPodarki[$i]['akcia_id'].".php\"><img src=\"".@$photo[$i]['foto']."\" alt=\"".$arrPodarki[$i]['header']."\" width=\"165\" height=\"102\" /></a>
	   	       <div><a href=\"/gift-".$arrPodarki[$i]['akcia_id'].".php\">".$arrPodarki[$i]['header']."</a></div>
		       <span>$blue_line</span>
	         </div>";
	   	 }
	   	 echo $items;
		?>
	</div>
	<div class="clear"></div>
	
	<div class="navwrap">
    <?php
      $f_page = f_Pages($_SESSION['count_all'],page(),"/type-$type_id-$shop_id.php?page=",21);      
      if(strlen($f_page) > 5)
         echo '<div class="navigation">'.$f_page.'</div>';
    ?>
    </div>
</div>
<!--Конец левого блока-->
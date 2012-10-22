<h2 class="gift-h2"><?=$AKCIA_INFO['header']?></h2>
<?=LANG_SECTION?>: <a href="/type-<?=$AKCIA_INFO['type_id']?>-0-<?=$AKCIA_INFO['shop_id']?>" class="section"><?=$AKCIA_INFO['type_name']?></a>

<div id="center-gift">
    <?php
     switch(@$AKCIA_INFO['type_id']){
     	case 5: case 6:
     		echo "<a class=\"discount-but\" href=\"#\"><sub></sub><span>".str_replace("%d",day_podarok,LANG_INFO_PERIOD_GIFT)."</span><sup></sup></a>";
     	break;
     }
    ?>    
    <div id="center-wrap">
      <div id="info">
       <span id="place"><a href="/shop-<?=$AKCIA_INFO['shop_id']?>"><?=$AKCIA_INFO['shopname']?></a></span>
       <span id="phone"><?=LANG_INFO_PHONES?>:<?=str_replace(";"," ",$AKCIA_INFO['phone'])?></span>
       <span id="addr"><a href="#" class="mini-arrow" onclick="ShowAddress(<?=$AKCIA_INFO['shop_id']?>); return false;"><?=$AKCIA_INFO['country']?> / <?=LANG_ADDRESS?></a></span>
      </div>
      <div id="whosthere"><span>Сейчас здесь</span><b><?=$USER->CountWhohereShop($AKCIA_INFO['shop_id'])?></b></div>
      <p>
        <div class="addthis_toolbox addthis_default_style addthis_32x32_style" style="height: 35px">
          <a class="addthis_button_preferred_1"></a><a class="addthis_button_preferred_2"></a>
          <a class="addthis_button_preferred_3"></a><a class="addthis_button_preferred_4"></a>             
          <a class="addthis_button_compact"></a><a class="addthis_counter addthis_bubble_style"></a>
        </div>
      </p>
      <div class="clear"></div>
	  <p class="long-hr" style="margin:0 0 23px 0;"></p>
	  <p class="" style="color: #716D6B; line-height: 18px;">
	    <?=str_replace("\n","<br />",$AKCIA_INFO['mtext'])?> 
	  </p>
    </div>
 <div class="clear"></div>
</div>
<script type="text/javascript">
	$('#bring').live('click', function(){
		$.fn.colorbox({width:862, height:551, arrowKey:false, href:'/jquery-wingift.php?gift=<?=$AKCIA_INFO['id']?>', scrolling:false, onLoad: function() {$('#cboxCurrent').remove();}, onCleanup: function(){$('#country-code1').selectBox('destroy');}})
		return false;
	});
</script>

  <?php
    /*if(is_array($AKCIA_INFO['groups'])){
     $items = "";
 	 for($j = count($AKCIA_INFO['groups'])-1; $j >= 0; $j--){
 	 	 if($j > 0) $zp = " / "; else $zp = "";
 	 	 $items .= "<a href=\"/grshops-".$AKCIA_INFO['groups'][$j]['id']."-".(int)$AKCIA_INFO['type_id'].".php\">".$AKCIA_INFO['groups'][$j]['name']."</a>".$zp;
 	 }
 	 echo "<h2>".$items."</h2>";
    }*/  
      
      /*
	 if((int)$AKCIA_INFO['dogovor'] == 1 && isset($_SESSION['WP_USER']['user_wp'])){
	 	$present_to_user = "";
	 	if(isset($_SESSION['present_to_user']) && is_array($_SESSION['present_to_user'])){
	 	   $present_to_user = "<td align=\"center\" width=\"40\"><img src=\"pic/arrow-right-mini.png\" width=\"12\" height=\"13\" /></td><td align=\"center\" style=\"font-size:10px;\"><div  style=\"position:relative;\"><img src=\"".$_SESSION['present_to_user']['photo']."\" width=\"60\" height=\"60\" /></div>".trim($_SESSION['present_to_user']['firstname']." ".$_SESSION['present_to_user']['lastname'])."</td><td align=\"center\"><a href=\"".@$_SERVER['REDIRECT_URL']."?present=delete\">Удалить<br />пользователя</a></td>";
	 	}
	 	echo "
	 	<table>
		 <tr>
			<td><div class=\"roundedbutton greenbutton\" onClick=\"PresentGift(".$AKCIA_INFO['id']."); return false;\"><sub></sub><div>".LANG_BTN_PRESENT."</div><sup></sup></div></td>
			$present_to_user
		 </tr>
	    </table>
	 	<div id=\"idShowBtnHochu_".$AKCIA_INFO['id']."\"></div>
        <script type=\"text/javascript\">xajax_ShowBtnIHochu(".$AKCIA_INFO['id'].")</script>
	 	";
	 }	 
	 elseif(isset($_SESSION['WP_USER']['user_wp'])){
	 	echo "<div id=\"idShowBtnLike\"></div><script type=\"text/javascript\">xajax_ShowBtnLike(".$AKCIA_INFO['id'].")</script>";
	 }
	 */
	?>	  
    
	<!-- <div id="whosthere"><a href="#" id="wstwindowlink" class="whostherewindow" onclick="ShowAddress(<?=$AKCIA_INFO['shop_id']?>); return false;"></a></div> -->
 


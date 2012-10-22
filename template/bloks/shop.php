<div id="center">
	<h1><?=$SHOP_INFO['name']?></h1>
	<p class="h1-hr"></p>
	<div id="center-checked">
		<table>
			<tr>
				<td width="80%">
					<p><?=$SHOP_INFO['country'].", ".$SHOP_INFO['adressa'][0]['street']." ".$SHOP_INFO['adressa'][0]['house']?></p>
					<p><?=LANG_PHONES?>: <?=str_replace(";",", ",$SHOP_INFO['phone'])?></p>
					<p>&nbsp;</p>
					<p><a href="#" class="mini-arrow" onclick="ShowAddress(<?=$shop_id?>); return false;"><?=LANG_ADDRESS?></a></p>
					<?php
					  if(strlen($SHOP_INFO['URL']) > 10)
					   echo "<p><a href=\"".$SHOP_INFO['URL']."\" class=\"mini-arrow\" target=\"_blank\">".LANG_GO_TO_SITE."</a></p>";
					?>					
					<p>&nbsp;</p>
					<p>
					<!-- AddThis Button BEGIN -->
                     <div class="addthis_toolbox addthis_default_style addthis_32x32_style" style="height: 35px">
                       <a class="addthis_button_preferred_1"></a><a class="addthis_button_preferred_2"></a>
                       <a class="addthis_button_preferred_3"></a><a class="addthis_button_preferred_4"></a>             
                       <a class="addthis_button_compact"></a><a class="addthis_counter addthis_bubble_style"></a>
                     </div>
                    <!-- AddThis Button END -->
					</p>
					<p>&nbsp;</p>
					<p><span class="clr-but clr-but-blue"><sub></sub><a href="#" id="reg-form-but">Отписаться</a><sup></sup></span></p>
				</td>
				<td><div id="whosthere"><span>Сейчас здесь</span><b><?=$USER->CountWhohereShop($shop_id)?></b></td>
			</tr>
		</table>
		<p class="long-hr"></p>
	</div><!--center-checked-->

	<div class="section">
		<ul class="tabs">
			<li class="current"><?=LANG_ABOUT_SHOP?></li>
			<li><?=LANG_PHOTO_ALBUM?></li>
			<li><?=LANG_SHOP_COMMENTS?> (<?=$COMMENTS->CountComments(array('shop_id'=>$shop_id))?>)</li>
		</ul>
		<div class="box visible">
			<p><?=str_replace("\n","<br />",$SHOP_INFO['descbig'])?></p>
		</div>
		<div class="box"><p>Содержимое второго блока</p></div>
		<div class="box"><p>Содержимое третьего блока</p></div>
	</div><!-- end of.section -->
</div>
<script type="text/javascript">
	//Табы
	(function($){
		$(function(){
			$('ul.tabs').delegate('li:not(.current)', 'click', function(){
				$(this).addClass('current').siblings().removeClass('current').parents('div.section').find('div.box').hide().eq($(this).index()).fadeIn(150);
			})
		})
	})(jQuery)

	function FavAction(id){
		$.ajax({
			url:'/jquery-shop',
			type:'POST',
			data:{type:type,id:id},
			cache:false,
			success: function(data){
				if(data){
					if(type=='add'){
						$('.circle-icon-favorite').addClass('active');
						type='delete';
					} else if(type=='delete'){
						if(confirm('Вы уверены что хотите удалить это любимое место?')){
							$('.circle-icon-favorite').removeClass('active');
							type='add';
						}
					}
				}
			}
		});
	}
</script>   
<?php
/*
     switch(@$_SESSION['WP_USER']['card_type']){
 	    case 2:
 		  $class_card = "golddiscount";
 	    break;
 	
 	    case 3:
 		  $class_card = "platindiscount";
 	    break;
 	
 	    default:
 		  $class_card = "silverdiscount";
 	    break;
     }
     
		  if($SHOP_INFO['vip_discount'] > 0){
		  	echo "<div class=\"firmUserVIP\">".$SHOP_INFO['vip_discount']."%</div>";
		  } else {
		  	echo "<div class=\"firmUserDiscount $class_card\">
			       <ul>
				    <li>Silver<br /><span>".$SHOP_INFO['silver']."%</span></li>
				    <li>Gold<br /><span>".$SHOP_INFO['gold']."%</span></li>
				    <li>Platin<br /><span>".$SHOP_INFO['platinum']."%</span></li>
			       </ul>
		          </div>";
		  }
	
		  if(isset($_SESSION['WP_USER'])){
                  echo "<div id=\"idbtnPodpiska\"></div><script type=\"text/javascript\">xajax_ShowBtnPodpiska($shop_id)</script>";
		      }
              }*/
?>
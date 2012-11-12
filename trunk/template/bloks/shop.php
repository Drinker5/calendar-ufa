<!--
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
                     <!--<div class="addthis_toolbox addthis_default_style addthis_32x32_style" style="height: 35px">
                       <a class="addthis_button_preferred_1"></a><a class="addthis_button_preferred_2"></a>
                       <a class="addthis_button_preferred_3"></a><a class="addthis_button_preferred_4"></a>             
                       <a class="addthis_button_compact"></a><a class="addthis_counter addthis_bubble_style"></a>
                     </div>
                    <!-- AddThis Button END -->
					<!--</p>
					<p>&nbsp;</p>
					<p><span class="clr-but clr-but-blue"><sub></sub><a href="#" id="reg-form-but">Отписаться</a><sup></sup></span></p>
				</td>
				<td><div id="whosthere"><span>Сейчас здесь</span><b>--><?//=$USER->CountWhohereShop($shop_id)?><!--</b></div></td>
			<!--</tr>
		</table>
		<p class="long-hr"></p>
	</div><!--center-checked-->

	<!--<div class="section">
		<ul class="tabs">
			<li class="current"><?=LANG_ABOUT_SHOP?></li>
			<li><?=LANG_PHOTO_ALBUM?></li>
			<li><?=LANG_SHOP_COMMENTS?> (--><?//=$COMMENTS->CountComments(array('shop_id'=>$shop_id))?><!--)</li>
		<!--</ul>
		<div class="box visible">
			<p><?=str_replace("\n","<br />",$SHOP_INFO['descbig'])?></p>
		</div>
		<div class="box"><p>Содержимое второго блока</p></div>
		<div class="box"><p>Содержимое третьего блока</p></div>
	</div><!-- end of.section -->
<!--</div>
-->

<?php
  $pinlat = $SHOP_INFO['adressa'][0]['latitude'];
  $pinlon = $SHOP_INFO['adressa'][0]['longitude'];
  $zoom   = 15;
?>

<div id="content" class="fl_r page-cafe">
        <div class="fl_l">
            <div class="title">
                <h2><?=$SHOP_INFO['name']?></h2>
            </div> <!-- /.title -->

            <ul class="tx-l">
                <li><i class="small-icon icon-address"></i><?=$SHOP_INFO['adressa'][0]['street'].", ".$SHOP_INFO['adressa'][0]['house'].". ".$SHOP_INFO['country']?></li>
                <li><b><?=str_replace(";",", ",$SHOP_INFO['phone'])?></b></li>
                <li>
                    <div class="title">
                        <a href="<?=$SHOP_INFO['URL']?>" class="mini-arrow" target="_blank">
                            <h2>
                                <?=$SHOP_INFO['URL']?>
                            <?php
            					  //if(strlen($SHOP_INFO['URL']) > 10)
            					  // echo "<p><a href=\"".$SHOP_INFO['URL']."\" class=\"mini-arrow\" target=\"_blank\">".LANG_GO_TO_SITE."</a></p>";
            				?>
                            </h2>
                        </a>
                    </div>
                </li>
            </ul>
        </div>

        <div class="fl_r">
                    <ul class="tx-r">
                        <li>
                            <div class="counter group">
                                <div class="y_counter">
                                    <span class="c_name">Сейчас здесь</span>
                                    <span class="c_digit"><?=$USER->CountWhohereShop($shop_id)?></span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="tx-r fl_r">
                                <a href="#" class="make-gift show opacity_link group">
                                    <span class="big-circle-icon circle-icon-make-favorite fl_r tx_c"></span>
                                    <div class="wrapped">
                                        Пригласи друга<br>в это заведение
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
        </div>

        <div class="cleared"></div>
                <!--
                <div class="inform">
                    <div class="separator"></div>
                    <div>
                        <div class="fl_l">
                            <p class="name">О нас</p>
                            <p class="text">
                                <?php
                                    if ($SHOP_INFO['desc']=='')
                                        echo "В базе нет описания :((";
                                    else
                                        echo str_replace("\n","<br />",$SHOP_INFO['desc']);
                                ?>
                                <br />
                                <a href="#" class="next">далее <i class="small-icon icon-green-arrow"></i></a>
                            </p>
                        </div>
                        <div class="fl_r">
                            <img src="pic/big-avatar.png" class="what" alt=""/>
                            <p class="name">Анонс</p>
                            <div class="data">26.09</div>
                            <p class="text">
                                VIP-зал, Выездное обслуживание, Еда на вынос, Зона для некурящих, Летняя терраса, Он-лайн бронирование. собственная пивоварня. одежды: свободная. Дисконтные карты: есть. Обмен валют: есть. Владение персона...
                                <a href="#" class="next">далее <i class="small-icon icon-green-arrow"></i></a>
                            </p>

                        </div>
                        <div class="cleared"></div>
                    </div>
                </div>
                -->
        <div class="read-info toggle-stop">
            <span class="toggle-comments tx_c pointer toggle-control toggle-change no-margin-bottom no-padding-bottom">
                <span class="c-control-text">
                    Показать подробную информацию
                    <br>
                    <i class="small-icon icon-green-arrow"></i>
                </span>
                <span class="c-control-text imp-hide">
                    Скрыть подробную информацию
                    <br>
                    <i class="small-icon icon-green-arrow-down"></i>
                </span>
            </span>
            <div class="full-info toggle-content hide">
                <div class="info-content">
                    <div class="info-rows toggle2 group">
                        <p style="color: black;">
                            <?=str_replace("\n","<br />",$SHOP_INFO['descbig'])?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

                <div class="map">
                    <div class="map-block-center" id="map">
                    <script>
                            var map = mapbox.map('map', mapbox.layer().id('jam-media.map-tckxnm3s'), null, []);
                            map.centerzoom({lat: <?=$pinlat?>, lon: <?=$pinlon?> }, <?=$zoom?>);

                			//Слой с маркерами
                			var markerLayer=mapbox.markers.layer().features([
                					{
                						geometry  :{'type':'Point', 'coordinates':[<?=$pinlon?>,<?=$pinlat?>]},
                						properties:{'image':'https://dl.dropbox.com/u/23467346/pic/modernmonument.png'}
                					},
                				]).factory(function(f){
                				//Define a new factory function. This takes a GeoJSON object as its input and returns an element - in this case an image - that represents the point.
                				var img=document.createElement('img');
                				img.className='marker-image';
                				img.setAttribute('src', f.properties.image);
                				return img;
                			});
                			map.addLayer(markerLayer);
                			mapbox.markers.interaction(markerLayer);
                    </script>
                    </div>
                </div>
<?php
              //Комментарии
              $maxCount=3; //Количество выводимых комментариев, если их больше заданного числа
              $_comments='';
              $_comCount=$COMMENTS->CountComments(array('shop_id'=>$shop_id));
              //$_numBegin=$_comCount>$maxCount?$_comCount-$maxCount:0;
              $_fullList=$COMMENTS->ShowComments(array('shop_id'=>$shop_id),0,0,'ASC');

              if(is_array($_fullList))
                        foreach($_fullList as $k=>$v)
                            $_comments.='
                                <div class="comments group" id="comments-'.$v['id'].'-id">
                                    <img src="'.$v['user']['photo'].'">
                                    <div class="text">
                                        <b class="blue-color">'.$v['user']['firstname'].' '.$v['user']['lastname'].'</b>
                                        <em class="grey-light-color">'.ShowDateRus($v['date']).'</em>
                                        <div class="grey-dark-color">
                                            '.$v['msg'].'
                                        </div>
                                    </div>
                                    <div class="rating">
                                        <a href="javascript:;" onclick="CommentsAction('.$shop_id.',\'delete\','.$v['id'].')" class="opacity_link fl_r">
                                            <i class="small-icon icon-delete"></i>
                                        </a>
                                    </div>
                                    <div class="cleared"></div>
                                </div>';
?>
                <div class="news-lent">
                    <div class="feed-box2 no-border-top toggle-control">
                      <div class="toggle-group group">
                          <b class="green-bold" >
                              Отзывы (<span id="comments-<?=$shop_id?>-count0"><?=$_comCount?></span>)
                          </b>
                          <br /><br />
                          <div class="feed-status-top group">
                              <button class="btn btn-green fl_r no-margin" onclick="CommentsAction(<?=$shop_id?>,'add','shop_id', 'shop')">Отправить</button>
                              <div class="feed-status2 wrapped">
                                  <div class="group">
                                      <span class="arrow_box2 fl_l">Оставь свой отзыв</span>
                                      <div class="wrapped">
                                          <input id="comments-<?=$shop_id?>-add" type="text" class="no-margin" style="width: 100%;" placeholder="Оставь свой отзыв">
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="group">
                              <div class="fl_l toggle-link pointer">
                                  <span>Поставить свою оценку:</span>
                              </div>
                          </div>
                      </div>
                    </div>
<?php
    echo $_comments;
?>
                    <div id="comments-<?=$shop_id?>"></div>
                </div>
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
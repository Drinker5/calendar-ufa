<?php
	require_once('jquery/jq_lenta_sobitiy.php');

	//Статус
	$st_count=70; //Кол-во символов
	$be_count=$st_count-strlen($USER_INFO['status']);

	//Последние фотографии
	$photos='';
	$fPhoto=$USER->ShowLastPhoto($USER_INFO['user_wp'],9,53,53);
	if(is_array($fPhoto))
		foreach($fPhoto as $k=>$v)
			$photos.='<a rel="lastPhoto" href="'.$v['photo_original'].'"><img src="'.$v['photo'].'" alt="'.$v['header'].'" width="53" height="53"></a>'."\r\n";
	else
		$photos='Список фотографий пуст!';

	//CheckIn
	$iHere=$USER->IHere($USER_INFO['user_wp']);

	//Личная информация
	$uMaritalSatus=array();
	switch($USER_INFO['sex'])
	{
		case'1':
			$uMaritalSatus=array(0=>'Не женат',1=>'Встречаюсь',2=>'Помолвлен',3=>'Женат',4=>'Влюблен',5=>'Все сложно',6=>'В активном поиске');
		break;
		case'2':
			$uMaritalSatus=array(0=>'Не замужем',1=>'Встречаюсь',2=>'Помолвлена',3=>'Замужем',4=>'Влюблена',5=>'Все сложно',6=>'В активном поиске');
		break;
	}

	//Подробная информация
	$uContactInfo='';
	if(!empty($USER_INFO['icq']))
		$uContactInfo.='<p>ICQ: '.varr_int($USER_INFO['icq']).'</p>';
	if(!empty($USER_INFO['skype']))
		$uContactInfo.='<p>Skype: '.ToText($USER_INFO['skype']).'</p>';
	if(!empty($USER_INFO['url']))
		$uContactInfo.='<p>Веб-сайт: <a href="#">'.ToText($USER_INFO['url']).'</a></p>';

	//Лента новостей
	$subs_count=$USER->CountHistoryLenta(varr_int($USER_INFO['user_wp']),$circle);
	$rows=5;

	//Кумиры
	$mT=array('10051'=>'ТУР 19 ноября по Росии','10019'=>'Comedy Club Best 2011','10132'=>'Back To Bass Fall Tour 2012');
	$mA=array('10051'=>'«Любовь спасёт мир» (2010)','10019'=>'Cамый лучший Фильм. 2011','10132'=>'Sting: The Best of 25 Years (2CD Version)');;
?>
<?php if($USER_INFO['zvezda']==1){ ?>
                <div class="banner-cumir">
                    <img src="stars/photo/<?=$USER_INFO['user_wp']?>/banner.jpg" />
                </div>
                <div class="group">
                    <div class="fl_l text-cumir">
                        <h2 class="name blue clear"><?php echo trim($USER_INFO['firstname']." ".$USER_INFO['lastname']); ?></h2>
                        <ul class="text-l">
                        	<?php echo ($USER_INFO['town_id'] > 0)?"<li><i class=\"small-icon icon-address\"></i>г. ".$USER_INFO['town_name'].", ".$USER_INFO['country_name']."</li>":"";?>
                            <li><strong>День рождение: <?=$USER_INFO['birthday']?></strong></li>
                        </ul>
                    </div>

                    <div class="wrapped tx_r">
                        <div class="photos_mini">
                            <a rel="lastPhoto" href="stars/photo/<?=$USER_INFO['user_wp']?>/photo1_large.jpg"><img src="stars/photo/<?=$USER_INFO['user_wp']?>/photo1.jpg" alt="photo"></a>
                            <a rel="lastPhoto" href="stars/photo/<?=$USER_INFO['user_wp']?>/photo2_large.jpg"><img src="stars/photo/<?=$USER_INFO['user_wp']?>/photo2.jpg" alt="photo"></a>
                            <a rel="lastPhoto" href="stars/photo/<?=$USER_INFO['user_wp']?>/photo3_large.jpg"><img src="stars/photo/<?=$USER_INFO['user_wp']?>/photo3.jpg" alt="photo"></a>
                            <a rel="lastPhoto" href="stars/photo/<?=$USER_INFO['user_wp']?>/photo4_large.jpg"><img src="stars/photo/<?=$USER_INFO['user_wp']?>/photo4.jpg" alt="photo"></a><br>
                            <a class="no c-db" href="javascript:;">показать все</a>
                        </div>
                    </div>
                </div>  
<?php }else{ ?>
                <div class="group">
                    <div class="fl_l" style="width: 520px;">
                        <h2 class="name blue clear"><?php echo trim($USER_INFO['firstname']." ".$USER_INFO['lastname']); ?></h2>
                        <ul class="text-l">
                        	<?php echo ($USER_INFO['town_id'] > 0)?"<li><i class=\"small-icon icon-address\"></i>г. ".$USER_INFO['town_name'].", ".$USER_INFO['country_name']."</li>":"";?>
	                        <li>День рождение: <b><?=$USER_INFO['birthday']?></b></li>
                            <!-- <li class="p_r">
                                <div class="feed-status2 text-status-wrap wrapped">
                                    <div class="group">
                                        <span class="arrow_box2 fl_l">Статус</span>
                                        <div id="status-input-text" class="wrapped">
                                            <?php echo !empty($USER_INFO['status'])?ToText($USER_INFO['status']):'Введите статус'; ?>
                                        </div>
                                    </div>
                                </div>
<?php if($_SESSION['WP_USER']['user_wp']==$USER_INFO['user_wp']){ ?>

                                <div id="status-change-window">
                                    <h3 style="margin-bottom: 3px;">Изменение статуса</h3>
                                    <div class="feed-status2 wrapped">
                                        <div class="group">
                                            <span class="arrow_box2 fl_l">Статус</span>
                                            <div class="wrapped">
                                                <input type="text" id="status-input" maxlength="70" class="no-margin" placeholder="Введите статус" value="<?php echo !empty($USER_INFO['status'])?trim($USER_INFO['status']):''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="group" style="margin-top: 15px;">
                                        <span id="letter-count">Осталось <?=$be_count?> символа</span>
                                        <button class="btn btn-grey fl_r no-margin btn-small-padding" onclick="myStatus()">Сохранить</button>
                                    </div>
                                </div>
<?php } ?>
                            </li> -->
                        </ul>
                        <div class="photos_mini">
                            <?=$photos?><br>
                            <a class="no c-db" href="/<?php echo $_SESSION['WP_USER']['user_wp']==$USER_INFO['user_wp']?'my':$USER_INFO['user_wp']; ?>-photoalbums">показать все</a>
                        </div>
                    </div>
<?php
	$cDigit ='';
	$daysStr=strval($USER->CountWhohereShopAddress(isset($iHere['adress_id'])?$iHere['adress_id']:0));

	if($daysStr>0){
?>

                    <div class="fl_r">
                        <ul class="info_right">
                            <li class="fl_r"><i class="small-icon icon-check-in"></i><b>checked-in</b> <?=ShowDateRus($iHere['data'])?></li>
                            <li class="blue clear popover-btn name"><h3><a href="javascript:;"><?=$iHere['shop_name']?></a></h3></li>
                            <li>
                                <div class="y_counter fl_r">
                                    <span class="c_name">Сейчас здесь</span>
<?php
	for($d=0; $d<strlen($daysStr); $d++)
		$cDigit.='<span class="c_digit">'.$daysStr[$d].'</span>';

	echo'
                                    '.$cDigit;
?>
                                </div>
                            </li>
                        </ul>
                    </div>
<?php
	}
?>
                </div>
<?php } ?>
				<script type="text/javascript">
					$("a[rel=lastPhoto]").fancybox({
						'transitionIn'		: 'none',
						'transitionOut'		: 'none',
					});

					// function myStatus()
					// {
					// 	var status = $("#status-input").val(),
					// 		count  = $("#status-input").val().length;

					// 	if(count<=70)
					// 	{
					// 		$.ajax({
					// 			type: "POST",
					// 			url: "/jquery-status",
					// 			data: {status:status},
					// 			async: false,
					// 			success: function(data)
					// 			{
					// 				$("#status-input-text").html(status?status:'Введите текст');
					// 				$('#status-change-window').hide();
					// 			}
					// 		});
					// 	}
					// 	else
					// 		alert("STATUS: НЕ БОЛЕЕ 70 СИМВОЛОВ");
					// }

					// jQuery(function($){
					// 	$("#status-input").keypress(function(event){
					// 		if(event.which == '13'){
					// 			myStatus();
					// 			return false;
					// 		}
					// 	});
					// });
				</script>

                <div class="profile-info toggle-stop">
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
                                <div class="info-row">
                                    <p class="name">Личная информация:</p>
                                    <p>Пол: <?php echo ($USER_INFO['sex']==2)?'Женский':'Мужской'; ?></p>
                                    <p>Семейное положение: <?=$uMaritalSatus[$USER_INFO['marital_status']]?></p>
                                    <?php echo empty($USER_INFO['about'])?"":"<p>О себе: ".$USER_INFO['about']."</p>"; ?>
                                </div>
<?php if(!empty($USER_INFO['education'])){ ?>

                                <div class="info-row">
                                    <p class="name">Образование:</p>
                                    <p><?php echo ToText($USER_INFO['education']); ?></p>
                                </div>
<?php } ?>
<?php if(!empty($USER_INFO['career'])){ ?>

                                <div class="info-row">
                                    <p class="name">Карьера:</p>
                                    <p><?php echo ToText($USER_INFO['career']); ?></p>
                                </div>
<?php } ?>
<?php if(!empty($uContactInfo)){ ?>

                                <div class="info-row">
                                    <p class="name">Контактная информация:</p>
<?php echo $uContactInfo; ?>
                                </div>
<?php } ?>
                                
                            </div>
                        </div>
                    </div>
                </div>
<?php if($USER_INFO['zvezda']==1){ ?>

                <div class="music-disc">
                    <div class="block fl_l">
                        <img class="fl_l" src="stars/photo/<?=$USER_INFO['user_wp']?>/tour.jpg"/>
                        <strong><?=$mT[$USER_INFO['user_wp']]?></strong>
                        <!-- <p>On November 21, Sting will perform at the Lodz Arena in Lodz, Poland as part of his critically-acclaimed Back to Bass tour! </p> -->
                        <div class="tx-r fl_l button">
                                <a href="#" class="make-gift show opacity_link group">
                                    <span class="big-circle-icon circle-icon-make-favorite fl_r tx_c"></span>
                                    <div class="wrapped tx_r">
                                        Подарить билет
                                    </div>
                                </a>
                            </div>
                    </div>
                    <div class="block fl_r">
                        <img class="fl_r" src="stars/photo/<?=$USER_INFO['user_wp']?>/disc.jpg"/>
                        <strong><?=$mA[$USER_INFO['user_wp']]?></strong>

                        <div class="tx-r fl_r right_blok" style="margin-top: 30px;">
                                <a href="#" class="make-gift show opacity_link group">
                                    <span class="big-circle-icon circle-icon-make-favorite fl_r tx_c"></span>
                                    <div class="wrapped">
                                        Подарить альбом
                                    </div>
                                </a>
                            </div>
                    </div>
                    <div class="cleared"></div>
                </div>

<?php
	}

	$zoom=12; //Масштаб карты
	$myPos=false; //Мое местоположение

	$places=$MYSQL->query("
		SELECT `pfx_shops_adress`.`adress`, `pfx_shops_adress`.`latitude`, `pfx_shops_adress`.`longitude`, `pfx_shops`.`name`, `pfx_shops`.`id` `shop_id`
		FROM `pfx_users_places`
		INNER JOIN `pfx_shops_adress` ON `pfx_shops_adress`.`id`=`pfx_users_places`.`address`
		INNER JOIN `pfx_shops` ON `pfx_shops_adress`.`shop_id`=`pfx_shops`.`id`
		WHERE `pfx_users_places`.`user_wp`=".(int)$USER_INFO['user_wp']."
		ORDER BY `name` ASC
	");
	if(is_array($places)){
		$count=count($places);
		$markers='';
		$markers_lat=array();
		$markers_lon=array();

		for($i=0; $i<$count; $i++)
		{
			//$places[$i]['adress'];
			$markers_lat[$i]=$places[$i]["latitude"];
			$markers_lon[$i]=$places[$i]["longitude"];
			$markers.="{
				geometry:{'type':'Point', 'coordinates':[".$places[$i]["longitude"].",".$places[$i]["latitude"]."]},
				properties:{
					'image'  :'https://dl.dropbox.com/u/23467346/pic/modernmonument.png',
					'name'   :'".$places[$i]['name']."',
					'shop_id':'".$places[$i]['shop_id']."',
					'rating' :'<div class=\"rating\"><a href=\"\" class=\"small-icon icon-star action\"></a><a href=\"\" class=\"small-icon icon-star action\"></a><a href=\"\" class=\"small-icon icon-star action\"></a><a href=\"\" class=\"small-icon icon-star\"></a><a href=\"\" class=\"small-icon icon-star\"></a></div>',
					'address':'".str_replace('::', ', ', $places[$i]['adress'])."',
					'id'     :'pin".$i."'
				}
			},";
		}
		$max_lat=max($markers_lat);
		$min_lat=min($markers_lat);
		$max_lon=max($markers_lon);
		$min_lon=min($markers_lon);
		$map_cnt='lat:'.(($max_lat+$min_lat)/2).', lon:'.(($max_lon+$min_lon)/2);

		$distance=calc_distance($max_lon, $max_lat, $min_lon, $min_lat);
		$zoom    =map_zoom_horiz($distance);
		//echo $distance.' м, zoom='.$zoom;
	}
	else
	{
		$myPos=true;
		//$zoom=3;
		$lat=0;
		$lon=0;

		if(function_exists('geoip_record_by_name'))
		{
			$rec=geoip_record_by_name($_SERVER['REMOTE_ADDR']);
			if($rec)
			{
				$lat=$rec['latitude'];
				$lon=$rec['longitude'];
			}
		}
	}

	//if($myPos and $_SESSION['WP_USER']['user_wp']==$USER_INFO['user_wp']){
	if($myPos){
?>

                <div class="fav-places group">
                    <div class="separator no-margin-top"></div>
	                <h4>Любимые места</h4>
	                <div class="fancy" id="map" alt="map-wide" style="width:745px; height:172px;"></div>
	                <a class="fl_r no c-db" href="/my-places">редактировать</a>
                </div>

				<div id='pointselector-text'></div>

				<script>
					var map=mapbox.map('map', null, null, [
						easey_handlers.DragHandler(),
						easey_handlers.DoubleClickHandler()
					]).zoom(<?=$zoom?>).center({lat: <?=$lat?>, lon: <?=$lon?>});
					map.addLayer(mapbox.layer().id('jam-media.map-tckxnm3s'));
					map.ui.zoomer.add();

					var markerLayer = mapbox.markers.layer();      
					//var interaction = mapbox.markers.interaction(markerLayer);
					map.addLayer(markerLayer);

					if (!navigator.geolocation) {
						//geolocation is not available
					} else {
						navigator.geolocation.getCurrentPosition(
							function(position) {
								map.zoom(<?=$zoom?>).center({
									lat: position.coords.latitude,
									lon: position.coords.longitude
								});
								markerLayer.add_feature({
									geometry: {
										coordinates: [
											position.coords.longitude,
											position.coords.latitude
										]
									},
									properties: {
										'image': 'https://dl.dropbox.com/u/23467346/pic/male-2.png',
										'id':'pin',
									}
								});
								markerLayer.factory(function(f){
									var img = document.createElement('img');
									img.className = 'marker-image';
									img.setAttribute('src', f.properties.image);

								//Смещение к координатам маркера
								MM.addEvent(img, 'click', function(e){
									map.ease.location({
										lat:position.coords.latitude,
										lon:position.coords.longitude
									}).zoom(map.zoom()).optimal();
									$('.marker-image').attr({src:f.properties.image});
									img.setAttribute('src', 'https://dl.dropbox.com/u/23467346/pic/male-2.png'); //https://dl.dropbox.com/u/23467346/pic/alien.png
									$('.place').removeClass('active');
									$('.place[rel='+f.properties.id+']').fadeOut(function(){
										setTimeout(function(){
											$('.place[rel='+f.properties.id+']').prependTo('.places-list').fadeIn(function(){
												setTimeout(function(){
													$('.place[rel='+f.properties.id+']').addClass('active');
												},
												1);
											});
										},
										300);
									});
								});
								return img;
							});
							//interaction.formatter(function(feature){
							//	var o = 'Я тут';
							//	return o;
							//});
						},
						function(err) {
							//position could not be found
						});
					}
				
				</script>
<?php } elseif($myPos===false) { ?>

                <div class="fav-places group">
                    <div class="separator no-margin-top"></div>
	                <h4>Любимые места</h4>
	                <div class="fancy" id="map" alt="map-wide" style="width:745px; height:172px;"></div>
	                <?php echo ($_SESSION['WP_USER']['user_wp']==$USER_INFO['user_wp'])?"<a class=\"fl_r no c-db\" href=\"/my-places\">редактировать</a>":""; ?>
                </div>

				<div id='pointselector-text'></div>

				<script>
					var map=mapbox.map('map', null, null, [
						easey_handlers.DragHandler(),
						easey_handlers.DoubleClickHandler()
					]).zoom(<?=$zoom?>).center({<?=$map_cnt?>});
					map.addLayer(mapbox.layer().id('jam-media.map-tckxnm3s'));
					map.ui.zoomer.add();

					mrk=new Object();

					var markerLayer=mapbox.markers.layer().features([<?=$markers?>]).factory(function(f){
						var img=document.createElement('img');
						img.className='marker-image';
						img.setAttribute('src', f.properties.image);
						img.setAttribute('id', f.properties.id);

						mrk[f.properties.id]=new Object();
						mrk[f.properties.id]['lat']=f.geometry.coordinates[1];
						mrk[f.properties.id]['lon']=f.geometry.coordinates[0];
						mrk[f.properties.id]['img']=f.properties.image;

						//Смещение к координатам маркера
						MM.addEvent(img, 'click', function(e){
							console.log('Zoom: '+map.zoom()+'. Move: '+moveMarker(map.zoom())+'.');
							map.ease.location({
								lat:(f.geometry.coordinates[1]+moveMarker(map.zoom())),
								lon:f.geometry.coordinates[0]
							}).zoom(map.zoom()).optimal();
							//$('.marker-image').attr({src:f.properties.image});
							//img.setAttribute('src', 'https://dl.dropbox.com/u/23467346/pic/alien.png');
						});
						return img;
					});
					
					map.addLayer(markerLayer);
					mapbox.markers.interaction(markerLayer).formatter(function(feature){
						var o='<div class="title"><a href="/shop-'+feature.properties.shop_id+'">'+feature.properties.name+'</a></div>'+feature.properties.rating+' <div class="adress">'+feature.properties.address+'</div><div class="info"><i class="small-icon icon-check-in-green"></i> 214 <br><span><b>checkins</b></span></div><div class="info right"><i class="small-icon icon-gift-green"></i> 214 <br><span><b>подарков</b></span></div><div class="down-arrow"></div>';
						return o;
					});
					
				</script>
<?php } ?>

                <div class="news-feed">
                    <div class="separator"></div>
	                <h4>Лента новостей</h4>

	               <div class="feed-box2 no-border-top">
                    <div class="toggle-group group">
                        <div class="feed-status-top group">
                            <button class="btn btn-green fl_r no-margin" type="submit" onclick="NewEvent()">Отправить</button>
                            <div class="feed-status2 wrapped">
                                <div class="group">
                                    <!-- <i id="photo" class="small-icon icon-photo active fl_r pointer"></i> -->
                                    <!-- <input type="file" multiple="true" accept="image/jpeg,image/png,image/gif"> -->
                                    <span class="arrow_box2 fl_l">Что нового?</span>
                                    <div class="wrapped">
                                        <input type="text" id="status-input" class="no-margin" style="width: 100%;" placeholder="Оставь свое сообщение или отзыв">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="group">
                            <label class="fl_r"><input type="checkbox">Только друзья</label>
                            <div class="fl_l toggle-link pointer">
                                <span>Поиск по новостям</span>
                            </div>
                        </div> -->
                    </div>

                    <div class="toggle-group hide-elem hide group">
                        <div class="feed-status-top group">
                            <button class="btn btn-green fl_r no-margin" type="submit" onclick="LentaSearch()">Поиск</button>
                            <div class="feed-status2 wrapped">
                                <div class="group">
                                    <i id="photo" class="small-icon icon-search active fl_r pointer"></i>
                                    <span class="arrow_box2 fl_l">Поиск по новостям</span>
                                    <div class="wrapped">
                                        <input type="text" class="mo-margin" style="width: 100%;" placeholder="Введите имя, название или ключевое слово">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="group">
                            <div class="fl_l toggle-link pointer">
                                <span>Моя лента</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="feed-events"> -->
                	<div class="timeline" id="idLenta"><?=LentaList($USER_INFO['user_wp'],5,0)?></div>
                	<div id="loadmoreajaxloader" style="display:none; text-align:center;"><img src="/pic/loader.gif" alt="loader" width="32" height="32" /></div>
                <!-- </div> --></div>

				<script type="text/javascript">
					var page   = 1,
						max    = <?=ceil($subs_count/$rows)?>,
						rows   = <?=$rows?>,
						begin  = rows,
						cToggle= 1;

					function myLenta(){
						if(max>page)$('div#loadmoreajaxloader').show();
						$.ajax({
							url:'/jquery-lenta',
							type:'POST',
							data:{user_wp:<?=$USER_INFO['user_wp']?>,list:begin,items:rows,circle:<?=$circle?>},
							cache:false,
							success: function(data){
								var html,
									idFeed=$('#idLenta'),
									newElems;

								if(data){
									if(max>page){
										$('div#loadmoreajaxloader').hide();
										html = jQuery.parseJSON(data);
										idFeed.append(html.html);
										idFeed.find('[rel="'+html.uid+'"]').popover({
											trigger: 'none',
											autoReposition: false
											})
											.popover('content', $('#gift-all-info-template').html(), true)
											.popover('setOption', 'position', 'bottom')
										page =page+1;
										begin=begin+rows;
										//alert(page+' '+begin);
									}
									else{
										$('div#loadmoreajaxloader').hide();
										//$('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
									}
								}
							}
						});
					}

					function LentaSearch(){
					}

					function NewEvent()
					{
						var status = $("#status-input").val(),
							count  = $("#status-input").val().length;

						if(count<3)
							alert("NewEvent: НЕ МЕНЕЕ 3 СИМВОЛОВ");
						else if(count>70)
							alert("NewEvent: НЕ БОЛЕЕ 70 СИМВОЛОВ");
						else
						{
							$.ajax({
								type: "POST",
								url: "/jquery-event",
								data: {status:status,user_wp:<?=$_SESSION['WP_USER']['user_wp']?>,whom:<?=$USER_INFO['user_wp']?>},
								async: false,
								success: function(data)
								{
									var html,
									idLenta=$('#idLenta');

									if(data){
										html = jQuery.parseJSON(data);
										idLenta.prepend(html.html);
										$("#status-input").val("");
									}
								}
							});
						}
					}

					function CommentsAction(id,type,n,t){
						var msg     = $("#comments-" + id + "-add").val(),
							msgCount= $("#comments-" + id + "-add").val().length,
							//count   = $("#comments-" + id + "-count").get(0),
							count0  = $("#comments-" + id + "-count-other").get(0)||-1;

						if(type=='add' && msgCount<3)
							alert("CommentsAction: НЕ МЕНЕЕ 3 СИМВОЛОВ");
						else if(type=='add' && msgCount>70)
							alert("CommentsAction: НЕ БОЛЕЕ 70 СИМВОЛОВ");
						else
						{
							$.ajax({
								url:'/jquery-comments',
								type:'POST',
								data:{type:type,id:id,msg:msg,n:n},
								cache:false,
								success: function(data){
									var html,
										//nCount         = count.innerHTML,
										nCountOther    = count0.innerHTML,
										idComments     = $('#comments-' + id);

									if(data){
										if(type=='add'){
											//nCount++;
											nCountOther++;
											html = jQuery.parseJSON(data);
											idComments.append(html.html);
											$("#comments-" + id + "-add").val('');
										} else if(type=='delete'){
											//nCount--;
											nCountOther--;
											$('#comments-' + n + '-id').slideUp('slow',function(){
												$(this).remove();
											});
											if(t==1)
												$('<span id="comments-' + id + '-count-other">' + nCountOther +'</span>').replaceAll('span#comments-' + id + '-count-other');
											if(nCountOther==0)
												$('.toggle-change').remove();
										}
										//$('#comments-' + id + '-count').html(nCount);
									}
								}
							});
						}
					}

					function CommentsShow(id,num){
						var idCommentsFull = $('#comments-' + id + '-full');

						//$(this).toggle(function(){
							if(cToggle==1){
								$.ajax({
									url:'/jquery-comments',
									type:'POST',
									data:{type:'show',id:id,num:num},
									cache:false,
									success: function(data){
										var html;

										if(data){
											cToggle=0;
											html=jQuery.parseJSON(data);
											idCommentsFull.append(html.html);
										}
									}
								});
							}
							else
								idCommentsFull.slideToggle('slow');
							//else
							//	idCommentsFull.slideDown('slow');
						//},
						//function(){
							//idCommentsFull.slideUp('slow');
						//});
					}

					$(window).scroll(function(){
						if($(window).scrollTop()==$(document).height()-$(window).height()){
							myLenta();
						}
					});

					setInterval(function(){
						$.ajax({
							url:'/jquery-comments',
							type:'POST',
							data:{type:'autoload',time:'<?=date('Y-m-d H:i:s')?>'},
							cache:false,
							success: function(data){
								var html;

								if(data){
									html=jQuery.parseJSON(data);
									$('#idLenta').append(html.html);
								}
							}
						});
					},60000);

/*
					jQuery(function($){
						$("#status-input").keypress(function(event){
							if(event.which == '13'){
								myStatus();
								return false;
							}
						});
					});
*/
                </script>
<div class="title margin">
  <h2>Редактирование любимых мест</h2>
</div> <!-- /.title -->

<div class="nav-panel group top-border">
  <span class="category-select">Здесь ты можешь выбрать нужные категории:</span>
  <div class="fl_r categories">
      <a href="#" class="big-category-icon category-icon-eat"></a>
      <a href="#" class="big-category-icon category-icon-bar active"></a>
      <a href="#" class="big-category-icon category-icon-pizza"></a>
      <a href="#" class="big-category-icon category-icon-yoga"></a>
      <a href="#" class="big-category-icon category-icon-skates"></a>
      <a href="#" class="big-category-icon category-icon-painting"></a>
      <a href="#" class="big-category-icon category-icon-flower"></a>
      <a href="#" class="big-category-icon category-icon-perfume"></a>
      <a href="#" class="big-category-icon category-icon-people"></a>
  </div> <!-- /.categories -->
</div> <!-- /.nav-panel -->

<div class="separator"></div>

<style>

</style>

<div class="edit-places-container">
	<div class="map fl_l">
		<div class="map-block-top">
			<div class="map-block-bottom">
				<div class="map-block-center">
					<div id="map" style="width:395px; height:600px;"></div>
				</div>
			</div>
		</div>
	</div> <!-- /.map -->

<?php
	$zoom=12;//Масштаб карты
	$myPos=false;//Мое местоположение

	$places=$MYSQL->query("
		SELECT `pfx_users_places`.`address`, `pfx_shops_adress`.`adress`, `pfx_shops_adress`.`latitude`, `pfx_shops_adress`.`longitude`, `pfx_shops`.`name`, `pfx_shops`.`id` `shop_id`
		FROM `pfx_users_places`
		INNER JOIN `pfx_shops_adress` ON `pfx_shops_adress`.`id`=`pfx_users_places`.`address`
		INNER JOIN `pfx_shops` ON `pfx_shops_adress`.`shop_id`=`pfx_shops`.`id`
		WHERE `pfx_users_places`.`user_wp`=".(int)$_SESSION['WP_USER']['user_wp']."
		ORDER BY `name` ASC
	");
	if(is_array($places)){
		$count=count($places);
		$markers='';
		$markers_lat=array();
		$markers_lon=array();

		for($i=0; $i<$count; $i++){
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
		$zoom    =map_zoom($distance);
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
?>
	<div id='pointselector-text'></div>

	<script>
<?php if($myPos){ ?>
		var map=mapbox.map('map').zoom(<?=$zoom?>).center({lat: <?=$lat?>, lon: <?=$lon?>});
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
								position.coords.latitude]
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
						//  var o = 'Я тут';
						//
						//  return o;
						//});
				},
				function(err) {
				//position could not be found
			});
		}
<?php }else{ ?>
		var map=mapbox.map('map').zoom(<?=$zoom?>).center({<?=$map_cnt?>});
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
				map.ease.location({
					lat:f.geometry.coordinates[1],
					lon:f.geometry.coordinates[0]
				}).zoom(map.zoom()).optimal();
				$('.marker-image').attr({src:f.properties.image});
				img.setAttribute('src', 'https://dl.dropbox.com/u/23467346/pic/alien.png');
				$('.place').removeClass('active');
				$('.place[rel='+f.properties.id+']').fadeOut(function(){
					setTimeout(function(){
						$('.place[rel='+f.properties.id+']').prependTo('#search-content').fadeIn(function(){
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
		map.addLayer(markerLayer);
		mapbox.markers.interaction(markerLayer).formatter(function(feature){
			var o='<div class="title"><a href="/shop-'+feature.properties.shop_id+'">'+feature.properties.name+'</a></div>'+feature.properties.rating+' <div class="adress">'+feature.properties.address+'</div><div class="info"><i class="small-icon icon-check-in-green"></i> 214 <br><span><b>checkins</b></span></div><div class="info right"><i class="small-icon icon-gift-green"></i> 214 <br><span><b>подарков</b></span></div><div class="down-arrow"></div>';
			return o;
		});

		$('.place').live('mouseover', function(){
			var that=$(this), id=that.attr('rel');
			$('.place').removeClass('active');
			that.addClass('active');
			$('#'+id).attr({'src':'https://dl.dropbox.com/u/23467346/pic/alien.png'});
			map.ease.location({
				lat:mrk[id]['lat'],
				lon:mrk[id]['lon']
			}).zoom(map.zoom()).optimal();
			$('.marker-tooltip').remove();
		});
		$('.place').live('mouseout', function(){
			var that=$(this), id=that.attr('rel');
			that.removeClass('active');
			$('#'+id).attr({'src':mrk[id]['img']});
		});

		function placeSearch(){
			var html,
				name = $("#search-input").val();

			$.ajax({
				type: "POST",
				url: "/jquery-myplaces",
				data: {type:'search',name:name},
				async: false,
				//dataType: 'json',
				success: function(data){
					html = $.parseJSON(data);

					//Обновляем карту
					map.removeLayer('markers');

					mrkSearch=new Object();

					var markersLayer=mapbox.markers.layer().features(html.map).factory(function(f){
						var img=document.createElement('img');
						img.className='marker-image';
						img.setAttribute('src', f.properties.image);
						img.setAttribute('id', f.properties.id);

						mrkSearch[f.properties.id]=new Object();
						mrkSearch[f.properties.id]['lat']=f.geometry.coordinates[1];
						mrkSearch[f.properties.id]['lon']=f.geometry.coordinates[0];
						mrkSearch[f.properties.id]['img']=f.properties.image;

						//Смещение к координатам маркера
						MM.addEvent(img, 'click', function(e){
							map.ease.location({
								lat:f.geometry.coordinates[1],
								lon:f.geometry.coordinates[0]
							}).zoom(map.zoom()).optimal();
							$('.marker-image').attr({src:f.properties.image});
							img.setAttribute('src', 'https://dl.dropbox.com/u/23467346/pic/alien.png');
							$('.place').removeClass('active');
							$('.place[rel='+f.properties.id+']').fadeOut(function(){
								setTimeout(function(){
									$('.place[rel='+f.properties.id+']').prependTo('#search-content').fadeIn(function(){
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
					map.addLayer(markersLayer);
					mapbox.markers.interaction(markersLayer).formatter(function(feature){
						var o='<b>'+feature.properties.name+'</b><br />'+feature.properties.address;
						return o;
					});

					//Обновляем боковое меню
					$("#search-content").html(html.html);
				}
			});	
		}

		function placeDelete(id,pin){
			if(confirm('Вы уверены что хотите удалить это любимое место?')){
				$.ajax({
					url:'/jquery-shop',
					type:'POST',
					data:{type:'delete',id:id},
					cache:false,
					success: function(data){
						if(data){
							$('.place[rel=pin' + pin + ']').slideUp('slow',function(){
								$(this).remove();
							});
							$('#pin' + pin).remove();
							//if($("#search-content").html()==''){
							//	$("#search-content").html('Нет результатов');
							//}
						}
					}
				});
			}
		}

		jQuery(function($){
			//$("#search-submit").click(function(){
			//	placeSearch();
			//});
			//$("#search-input").keypress(function(event){
			//	if(event.which == '13'){
			//		placeSearch();
			//		return false;
			//	}
			//});
			$("#search-input").keyup(function(){
				placeSearch();
			});
		});
<?php } ?>
	</script>

	<div class="places-list fl_r">
		<div class="search-block">
			<div class="search-block-bottom">
				<!-- <form> -->
					<input type="text" id="search-input" class="search-field" placeholder="Поиск...">
					<input type="submit" id="search-submit" class="search-button" value="">
				<!-- </form> -->
			</div>
		</div>
		<div id="search-content">
<?php
	if(is_array($places)){
		for($i=0; $i<$count; $i++){
			$logo=ShowLogo(array($places[$i]['shop_id']),70,70);
			$logo=$logo[0]['logo'];
?>
			<div class="place group" rel="pin<?=$i?>">
				<div class="preview fl_l">
					<a href="/shop-<?=$places[$i]['shop_id']?>"><img src="<?=$logo?>" alt="" width="70"></a>
				</div>
				<div class="info fl_l">
					<a href="/shop-<?=$places[$i]['shop_id']?>"><?=$places[$i]['name']?></a>
					<p><?=str_replace('::', ', ', $places[$i]['adress'])?></p>
				</div>
				<div class="action fl_r">
					<a href="javascript:;" onclick="placeDelete(<?=$places[$i]['address']?>,<?=$i?>)" class="small-icon icon-delete"></a>
				</div>
			</div>
<?php
		}
?>
		</div>
<?php
	}
	else
	{
?>
У Вас ещё нет ни одного любимого места
<?php
	}
?>
	</div> <!-- /.places-list -->
</div> <!-- /.edit-places -->
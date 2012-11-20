<?php
	$avka=ShowAvatar(array($_SESSION['WP_USER']['user_wp']),25,25,false);
?>
                <div class="title margin">
                    <h2>Check-in</h2>    
                </div> <!-- /.title -->

                <div class="nav-panel group geo">
                	<span class="geo_icon"><span class="geo_rotate"></span></span>
                	<span class="geo_arrow"></span>
                	<span class="hint">Определи свое местоположение на карте и твои друзья смогут видеть тебя!</span>
                </div> <!-- /.nav-panel -->

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
					<script>
						var ihere=0;

						$('.place').live('mouseover', function(){
							var that=$(this), id=that.attr('rel');
							$('.place').removeClass('active');
							that.addClass('active');
							$('#'+id).attr({'src':'https://dl.dropbox.com/u/23467346/pic/alien.png'});
							map.ease.location({
								lat:mrkSearch[id]['lat'],
								lon:mrkSearch[id]['lon']
							}).zoom(map.zoom()).optimal();
							$('.marker-tooltip').remove();
						});
						$('.place').live('mouseout', function(){
							var that=$(this), id=that.attr('rel');
							that.removeClass('active');
							$('#'+id).attr({'src':mrkSearch[id]['img']});
						});

						function placeShow(){
							var html;

							navigator.geolocation.getCurrentPosition(
							function(position){//alert(position.coords.latitude+' -- '+position.coords.longitude);
								$.ajax({
									type: "POST",
									url: "/jquery-mycheckin",
									data: {type:'show',mylat:position.coords.latitude,mylng:position.coords.longitude},
									async: false,
									success: function(data){
										html = $.parseJSON(data);

										//Добавляем карту
										var map=mapbox.map('map').zoom(html.zoom).center(html.center);
										map.addLayer(mapbox.layer().id('jam-media.map-tckxnm3s'));
										map.ui.zoomer.add();

										//Обновляем карту
										map.removeLayer('markers');

										mrkSearch=new Object();

										var markersLayer=mapbox.markers.layer().features(html.map).factory(function(f){
											var img=document.createElement('img');
											img.className='marker-image';
											img.setAttribute('id', f.properties.id);
											//if(f.geometry.coordinates[1]==position.coords.latitude && position.coords.longitude==f.geometry.coordinates[0])
											//	img.setAttribute('src', '<?=$avka[0]['avatar']?>');
											//else
												img.setAttribute('src', f.properties.image);

											mrkSearch[f.properties.id]=new Object();
											mrkSearch[f.properties.id]['lat']=f.geometry.coordinates[1];
											mrkSearch[f.properties.id]['lon']=f.geometry.coordinates[0];
											//if(f.geometry.coordinates[1]==position.coords.latitude && position.coords.longitude==f.geometry.coordinates[0])
											//	mrkSearch[f.properties.id]['img']='<?=$avka[0]['avatar']?>';
											//else
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
										});console.log(markersLayer);
										map.addLayer(markersLayer);
										mapbox.markers.interaction(markersLayer).formatter(function(feature){
											var o='<b>'+feature.properties.name+'</b><br />'+feature.properties.address;
											return o;
										});

										//Обновляем боковое меню
										$("#search-content").html(html.html);
									}
								});
							},
							function(err){
								//position could not be found
							});
						}

						function placeSearch(){
							var html,
								name = $("#search-input").val();

							navigator.geolocation.getCurrentPosition(
							function(position){//alert(position.coords.latitude+' -- '+position.coords.longitude);
								$.ajax({
									type: "POST",
									url: "/jquery-mycheckin",
									data: {type:'search',mylat:position.coords.latitude,mylng:position.coords.longitude,name:name},
									async: false,
									//dataType: 'json',
									success: function(data){
										html = $.parseJSON(data);

										//Добавляем карту
										var map=mapbox.map('map').zoom(html.zoom).center(html.center);
										map.addLayer(mapbox.layer().id('jam-media.map-tckxnm3s'));
										map.ui.zoomer.add();

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
							},
							function(err){
								//position could not be found
							});
						}

						function checkIn(id){
							$.ajax({
								url:'/jquery-mycheckin',
								type:'POST',
								data:{type:'checkin',id:id},
								cache:false,
								success: function(data){
									if(data){
										if(ihere>0){
											$('#shop-' + ihere).removeClass('current');
											$('#shop-' + ihere +' a').removeClass('active');
										}
										$('#shop-' + id).addClass('current');
										$('#shop-' + id +' a').addClass('active');
										ihere=id;
									}
								}
							});
						}

						function FavAction(id){
							var type;

							$.ajax({
								url:'/jquery-shop',
								type:'POST',
								data:{type:'toogle',id:id},
								cache:false,
								success: function(data){
									if(data){
										type=$.parseJSON(data);

										if(type=='add'){
											$('#fav-'+id).html('<a href="javascript:;" onclick="FavAction('+id+')" class="fav-place"><i class="small-icon icon-favorite-place-green"></i> Любимое место</a>');
										} else if(type=='delete'){
											$('#fav-'+id).html('<a href="javascript:;" onclick="FavAction('+id+')" class="to-fav-place"><i class="small-icon icon-favorite-place"></i> Добавить в любимые места</a>');
										}
									}
								}
							});
						}

						jQuery(function($){
							placeShow();
							$("#search-input").keyup(function(){
								placeSearch();
							});
						});
					</script>
                    <div class="places-list fl_r">
                        <div class="search-block">
                            <div class="search-block-bottom">
                                <form>
                                    <input type="text" id="search-input" class="search-field" placeholder="Поиск...">
                                    <input type="submit" id="search-submit" class="search-button" value="">
                                </form>
                            </div>
                        </div>
                        <div class="places-wrapper" id="search-content">
                        	Пожалуйста подождите!
                        </div>
                    </div>
                </div>
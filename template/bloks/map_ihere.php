<?php
	//Величина для изменения радиуса поиска
	if(isset($_GET['m']) && $_GET['m'] > 0) $multiplicator = (int) $_GET['m'];
	else $multiplicator = 2;
?>
<script type="text/javascript">
			function success(position){
				geocoder=new google.maps.Geocoder();
				infowindow=new google.maps.InfoWindow();
				mapcanvas=document.createElement('div');
				mapcanvas.id='mapcanvas';
				mapcanvas.style.width='730px';
				mapcanvas.style.height='550px';				
				document.querySelector('#map').appendChild(mapcanvas);				

				//Это координаты, которые определяются автоматически
				var mylat=position.coords.latitude , mylng=position.coords.longitude;
				
				var shops = [];
				
				$.ajax({
	              type: "POST",
	              url: "/jquery-ihere.php",
	              dataType: "json",
	              data: {mylat: mylat, mylng: mylng, m: <?=$multiplicator?>},
	              async: false,
	              success: function(data){
	              	//Массив для маркеров с объектами
				    shops = data.shops;
	              },
	              error: function(jqXHR, textStatus){
	              	alert(textStatus);
	              }
	            });
	            
				var latlng=new google.maps.LatLng(mylat,mylng);//Создается карта с указанными координатами
				var myOptions={
					zoom:<?=(16-$multiplicator)?>,//Масштаб карты
					center:latlng,
					mapTypeControl:false,
					navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL},
					mapTypeId:google.maps.MapTypeId.ROADMAP
				};
				var map=new google.maps.Map(document.getElementById('mapcanvas'),myOptions);

				geocoder.geocode({'latLng':latlng},function(results,status){
					if(status==google.maps.GeocoderStatus.OK){
						if(results[0]){
							var s=document.querySelector('#status'), address=results[0].address_components;
							if(s.className=='success')return;
							var show_address='';
							for(var i=address.length-1; i>=0; i--){
								if(i==address.length-1)show_address=address[i].long_name;
								else show_address=show_address + ', ' + address[i].long_name;
							}
							s.innerHTML=show_address;
							s.className='success';
							//Маркер
							marker=new google.maps.Marker({
								position:latlng,
								map:map,
								title:'Вася',//Пользователь
								icon:'http://dl.dropbox.com/u/23467346/male-2.png'//Иконка пользователя
							});
							var infowindow = null;
							infowindow = new google.maps.InfoWindow({
								content: "holding..."
							});
							for(var i=0;i<shops.length;i++){
								var shop=shops[i];
								var myLatLng=new google.maps.LatLng(shop[1],shop[2]);
								var marker=new google.maps.Marker({
									position:myLatLng,
									map:map,
									icon:shop[3],//Иконка
									html:'<img src="'+shop[3]+'" align="left"><b>'+shop[0]+'</b><br>Расстояние: '+shop[4]+' м'//Код, который в баллуне отображается
								});
								//Инфоокошко
								google.maps.event.addListener(marker, 'click', function () {
									infowindow.setContent(this.html);
									infowindow.open(map,this);
								});
							};
							cityCircle = new google.maps.Circle({
								strokeColor: "#00ff00",
								strokeOpacity: 0.5,
								strokeWeight: 2,
								fillColor: "#00ff00",
								fillOpacity: 0.2,
								map: map,
								center:latlng,
								radius:<?=(500*$multiplicator)?>
							})							
						}
						else alert('По текущим координатам ничего не найдено.');
					}
					else alert('Geocoder не работает: '+status);
				});
			}

			function error(msg){
				var s=document.querySelector('#status');
				s.innerHTML=typeof msg=='string'?msg:'Не удалось получить координаты.';
				s.className = 'fail';
			}
			if(navigator.geolocation)navigator.geolocation.getCurrentPosition(success, error);
			else error('Не возможно получить координаты.');

</script>
<!--Левый блок-->
<div id="left" style="padding-top:30px;">
<h3>Место положение</h3>

<p>Радиус поиска: <b><?=(500*$multiplicator)?></b> м</p>
<div id="map">
	<p><span id="status">Подождите, мы определяем ваше местоположение <img src="http://hi-tech.mail.ru/img/loading.gif" height="16" width="16"></span></p>
</div>

</div>
<!--Конец левого блока-->
<?php

if(isset($_POST['mylat']) && isset($_POST['mylng'])){
	
	//Величина для изменения радиуса поиска
	if(isset($_POST['m'])) $multiplicator = (int) $_POST['m']; else $multiplicator = 1;
	$mylng = $_POST['mylng'];
	$mylat = $_POST['mylat'];
	
	//Цифры ниже - это 500 метров вверх-вниз или в сторону. Менять нельзя. Получены опытным путем.
	$wedistance=0.0044923*$multiplicator;//Вверх-вниз
	$nsdistance=0.007982*$multiplicator;//В сторону
	
	//Координаты квадрата для поиска
	$west=$mylng-$wedistance;
	$east=$mylng+$wedistance;
	$north=$mylat+$nsdistance;
	$south=$mylat-$nsdistance;
	
	
	//Запрос к базе
	$tbshops   = "pfx_shops";
	$tbaddress = "pfx_shops_adress";
	
	$result = $MYSQL->query("SELECT $tbshops.id shop_id, $tbshops.name shop_name, $tbaddress.id address_id, $tbaddress.adress, $tbaddress.longitude, $tbaddress.latitude
	                            FROM $tbaddress
	                           INNER JOIN $tbshops ON $tbshops.id = $tbaddress.shop_id
	                         WHERE $tbaddress.longitude BETWEEN ".$west." AND ".$east." AND $tbaddress.latitude BETWEEN ".$south." AND ".$north);
	
	for($i=0; $i < count($result); $i++){
		/*$title=mysql_result($q,$i,0);//Название точки
		$lng=mysql_result($q,$i,1);//Долгота
		$lat=mysql_result($q,$i,2);//Широта
		$type=mysql_result($q,$i,3);//Тип: магазин, ресторан и т. д.
		*/
		$title = $result[$i]['shop_name'];
		$lng   = $result[$i]['longitude'];
		$lat   = $result[$i]['latitude'];
		$type  = 'rest';

		//Расчет растояния до точки
		$distance = calc_distance($lat, $lng, $mylat, $mylng);
		//Если меньше нужного, то все объекты, наденные в квадрате, но дальше, чем нужное расстояние, не пишутся в результат
		if($distance<=500*$multiplicator){
			//Выбор иконки
			if($type=='rest')$icon='http://dl.dropbox.com/u/23467346/restaurant.png';
			else $icon='http://dl.dropbox.com/u/23467346/mall.png';
			//Элемент массива с данными
			//[Название, Долгота, Широта, Иконка, Расстояние]			
			
			@$content .= <<<EOD
            [
              "{$title}",
              "{$lat}",
              "{$lng}",
              "{$icon}",
              "{$distance}"
            ],
EOD;
		}
	}
}

//Функция для расчета расстояни
function calc_distance($lon1,$lat1,$lon2,$lat2){
	$theta=$lon1-$lon2;
	$dist=sin(deg2rad($lat1))*sin(deg2rad($lat2))+cos(deg2rad($lat1))*cos(deg2rad($lat2))*cos(deg2rad($theta));
	$dist=acos($dist);
	$dist=rad2deg($dist);
	return ceil($dist*111000);
}

header('Content-type: application/json');
?>
{
        "shops": [
            <?php echo substr(@$content, 0, -1); ?>
        ]
}
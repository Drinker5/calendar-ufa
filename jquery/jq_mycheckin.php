<?php
switch(@$_POST['type']){
	case'show':
		if(isset($_POST['mylat']) && isset($_POST['mylng'])){

			$html='Поиск не дал результатов';
			$zoom=12;
			$map=array();

			$lat=$_POST['mylat'];
			$lon=$_POST['mylng'];

			$center=array('lat'=>$_POST['mylat'],'lon'=>$_POST['mylng']);

			$wedistance=0.0044923*4; //2km
			$nsdistance=0.007982*4;  //2km

			$west =$lon-$wedistance;
			$east =$lon+$wedistance;
			$north=$lat+$nsdistance;
			$south=$lat-$nsdistance;

			$places=$MYSQL->query("
				SELECT `pfx_shops_adress`.`id`, `pfx_shops_adress`.`adress`, `pfx_shops_adress`.`latitude`, `pfx_shops_adress`.`longitude`, `pfx_shops`.`name`, `pfx_shops`.`id` `shop_id`, `pfx_users_ihere`.`user_wp`
				FROM `pfx_shops_adress`
				INNER JOIN `pfx_shops` ON `pfx_shops_adress`.`shop_id`=`pfx_shops`.`id`
				LEFT JOIN `pfx_users_ihere` ON `pfx_shops_adress`.`id`=`pfx_users_ihere`.`address_id`
				WHERE `pfx_shops_adress`.`latitude` BETWEEN ".$south." AND ".$north." AND `pfx_shops_adress`.`longitude` BETWEEN ".$west." AND ".$east."
				ORDER BY `name` ASC
			");

			if(is_array($places)){
				$count=count($places);
				$markers=$html='';
				$markers_lat=array();
				$markers_lon=array();

				for($i=0; $i<$count; $i++){
					$logo =ShowLogo(array($places[$i]['shop_id']),70,70);
					$logo =$logo[0]['logo'];

					$markers_lat[$i]=$places[$i]["latitude"];
					$markers_lon[$i]=$places[$i]["longitude"];

					$map[$i]['geometry']=array('type'=>'Point','coordinates'=>array($places[$i]["longitude"],$places[$i]["latitude"]));
					$map[$i]['properties']=array('image'=>'https://dl.dropbox.com/u/23467346/pic/modernmonument.png','name'=>$places[$i]['name'],'address'=>str_replace('::', ', ', $places[$i]['adress']),'id'=>'pin'.$i);

					$html.='
		<div class="place group checkin" rel="pin'.$i.'">
			<div class="preview fl_l">
				<a href="/shop-'.$places[$i]['shop_id'].'"><img src="'.$logo.'" alt="" width="70"></a>
			</div>
			<div class="info fl_l">
				<a href="/shop-'.$places[$i]['shop_id'].'">'.$places[$i]['name'].'</a>
				<p>'.str_replace('::', ', ', $places[$i]['adress']).'</p>
				<span id="fav-'.$places[$i]['id'].'">'.($SHOP->isFavorite(array($places[$i]['id']))?'<a href="javascript:;" onclick="FavAction('.$places[$i]['id'].')" class="fav-place"><i class="small-icon icon-favorite-place-green"></i> Любимое место</a>':'<a href="javascript:;" onclick="FavAction('.$places[$i]['id'].')" class="to-fav-place"><i class="small-icon icon-favorite-place"></i> Добавить в любимые места</a>').'</span>
			</div>
			'.($places[$i]['user_wp']==$_SESSION['WP_USER']['user_wp']?'
			<script>
				var ihere='.$places[$i]['id'].';
			</script>
            <div class="action current fl_r" id="shop-'.$places[$i]['id'].'">
                <a href="javascript:;" onclick="checkIn('.$places[$i]['id'].')" class="big-circle-icon circle-icon-check-in active"></a>
            </div>':'
            <div class="action fl_r" id="shop-'.$places[$i]['id'].'">
                <a href="javascript:;" onclick="checkIn('.$places[$i]['id'].')" class="big-circle-icon circle-icon-check-in hide"></a>
            </div>').'
		</div>';
				}

				$max_lat =max($markers_lat);
				$min_lat =min($markers_lat);
				$max_lon =max($markers_lon);
				$min_lon =min($markers_lon);
				$center  =array('lat'=>($max_lat+$min_lat)/2,'lon'=>($max_lon+$min_lon)/2);
				$distance=calc_distance($max_lon, $max_lat, $min_lon, $min_lat);
				$zoom    =map_zoom($distance);
			}

			echo json_encode(array('html'=>$html,'map'=>$map,'zoom'=>$zoom,'center'=>$center));
		}
	break;
	case'search':
		if(isset($_POST['mylat']) && isset($_POST['mylng']) && isset($_POST['name'])){ // && strlen(trim($_POST['name'])) >= 0

			$html='Поиск не дал результатов';
			$zoom=12;
			$map=array();

			$lat=$_POST['mylat'];
			$lon=$_POST['mylng'];

			$center=array('lat'=>$_POST['mylat'],'lon'=>$_POST['mylng']);

			$wedistance=0.0044923*4; //2km
			$nsdistance=0.007982*4;  //2km

			$west =$lon-$wedistance;
			$east =$lon+$wedistance;
			$north=$lat+$nsdistance;
			$south=$lat-$nsdistance;

			$places=$MYSQL->query("
				SELECT `pfx_shops_adress`.`id`, `pfx_shops_adress`.`adress`, `pfx_shops_adress`.`latitude`, `pfx_shops_adress`.`longitude`, `pfx_shops`.`name`, `pfx_shops`.`id` `shop_id`, `pfx_users_ihere`.`user_wp`
				FROM `pfx_shops_adress`
				INNER JOIN `pfx_shops` ON `pfx_shops_adress`.`shop_id`=`pfx_shops`.`id`
				LEFT JOIN `pfx_users_ihere` ON `pfx_shops_adress`.`id`=`pfx_users_ihere`.`address_id`
				WHERE `pfx_shops`.`name` LIKE '%".mysql_real_escape_string($_POST['name'])."%' AND `pfx_shops_adress`.`latitude` BETWEEN ".$south." AND ".$north." AND `pfx_shops_adress`.`longitude` BETWEEN ".$west." AND ".$east."
				ORDER BY `name` ASC
			");

			if(is_array($places)){
				$count=count($places);
				$markers=$html='';
				$markers_lat=array();
				$markers_lon=array();

				for($i=0; $i<$count; $i++){
					$logo =ShowLogo(array($places[$i]['shop_id']),70,70);
					$logo =$logo[0]['logo'];

					$markers_lat[$i]=$places[$i]["latitude"];
					$markers_lon[$i]=$places[$i]["longitude"];

					$map[$i]['geometry']=array('type'=>'Point','coordinates'=>array($places[$i]["longitude"],$places[$i]["latitude"]));
					$map[$i]['properties']=array('image'=>'https://dl.dropbox.com/u/23467346/pic/modernmonument.png','name'=>$places[$i]['name'],'address'=>str_replace('::', ', ', $places[$i]['adress']),'id'=>'pin'.$i);

					$html.='
		<div class="place group checkin" rel="pin'.$i.'">
			<div class="preview fl_l">
				<a href="/shop-'.$places[$i]['shop_id'].'"><img src="'.$logo.'" alt="" width="70"></a>
			</div>
			<div class="info fl_l">
				<a href="/shop-'.$places[$i]['shop_id'].'">'.$places[$i]['name'].'</a>
				<p>'.str_replace('::', ', ', $places[$i]['adress']).'</p>
				<span id="fav-'.$places[$i]['id'].'">'.($SHOP->isFavorite(array($places[$i]['id']))?'<a href="javascript:;" onclick="FavAction('.$places[$i]['id'].')" class="fav-place"><i class="small-icon icon-favorite-place-green"></i> Любимое место</a>':'<a href="javascript:;" onclick="FavAction('.$places[$i]['id'].')" class="to-fav-place"><i class="small-icon icon-favorite-place"></i> Добавить в любимые места</a>').'</span>
			</div>
			'.($places[$i]['user_wp']==$_SESSION['WP_USER']['user_wp']?'
			<script>
				var ihere='.$places[$i]['id'].';
			</script>
            <div class="action current fl_r" id="shop-'.$places[$i]['id'].'">
                <a href="javascript:;" onclick="checkIn('.$places[$i]['id'].')" class="big-circle-icon circle-icon-check-in active"></a>
            </div>':'
            <div class="action fl_r" id="shop-'.$places[$i]['id'].'">
                <a href="javascript:;" onclick="checkIn('.$places[$i]['id'].')" class="big-circle-icon circle-icon-check-in hide"></a>
            </div>').'
		</div>';
				}
				$max_lat =max($markers_lat);
				$min_lat =min($markers_lat);
				$max_lon =max($markers_lon);
				$min_lon =min($markers_lon);
				$center  =array('lat'=>($max_lat+$min_lat)/2,'lon'=>($max_lon+$min_lon)/2);
				$distance=calc_distance($max_lon, $max_lat, $min_lon, $min_lat);
				$zoom    =map_zoom($distance);
			}

			echo json_encode(array('html'=>$html,'map'=>$map,'zoom'=>$zoom,'center'=>$center));
		}
	break;
	case'checkin':
		if(isset($_POST['id']) && $_POST['id'] > 0){
			$MYSQL->query("UPDATE `pfx_users_ihere` SET `address_id`=".varr_int($_POST['id'])." WHERE `user_wp`=".varr_int($_SESSION['WP_USER']['user_wp'])."");
			$MYSQL->query("INSERT INTO `pfx_users_deystvie` (`data_add`,`user_wp`,`deystvie`,`id_deystvie`) VALUES (NOW(), ".varr_int($_SESSION['WP_USER']['user_wp']).", 8, ".varr_int($_POST['id']).")");
			echo json_encode(array('status'=>'success'));
		}
	break;
}
?>
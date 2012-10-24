<?php
switch(@$_POST['type']){
	case'search':
		if(isset($_POST['name'])){ // && strlen(trim($_POST['name'])) >= 0

			$html='Поиск не дал результатов';
			$map='';

			$places=$MYSQL->query("
				SELECT `pfx_users_places`.`address`, `pfx_shops_adress`.`adress`, `pfx_shops_adress`.`latitude`, `pfx_shops_adress`.`longitude`, `pfx_shops`.`name`, `pfx_shops`.`id` `shop_id`
				FROM `pfx_users_places`
				INNER JOIN `pfx_shops_adress` ON `pfx_shops_adress`.`id`=`pfx_users_places`.`address`
				INNER JOIN `pfx_shops` ON `pfx_shops_adress`.`shop_id`=`pfx_shops`.`id`
				WHERE `pfx_users_places`.`user_wp`=".(int)$_SESSION['WP_USER']['user_wp']." AND `pfx_shops`.`name` LIKE '%".mysql_real_escape_string($_POST['name'])."%'
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

					//$map.="{geometry:{'type':'Point', 'coordinates':[".$places[$i]["longitude"].",".$places[$i]["latitude"]."]}, properties:{'image':'https://dl.dropbox.com/u/23467346/pic/modernmonument.png', 'name':'".$places[$i]['name']."', 'address':'".str_replace('::', ', ', $places[$i]['adress'])."', 'id':'pin".$i."'}},";
					$map[$i]['geometry']=array('type'=>'Point','coordinates'=>array($places[$i]["longitude"],$places[$i]["latitude"]));
					$map[$i]['properties']=array('image'=>'https://dl.dropbox.com/u/23467346/pic/modernmonument.png','name'=>$places[$i]['name'],'address'=>str_replace('::', ', ', $places[$i]['adress']),'id'=>'pin'.$i);

					$html.='
		<div class="place group" rel="pin'.$i.'">
			<div class="preview fl_l">
				<a href="/shop-'.$places[$i]['shop_id'].'"><img src="'.$logo.'" alt="" width="70"></a>
			</div>
			<div class="info fl_l">
				<a href="/shop-'.$places[$i]['shop_id'].'">'.$places[$i]['name'].'</a>
				<p>'.str_replace('::', ', ', $places[$i]['adress']).'</p>
			</div>
			<div class="action fl_r">
				<a href="javascript:;" onclick="placeDelete('.$places[$i]['address'].','.$i.')" class="small-icon icon-delete"></a>
			</div>
		</div>';
				}
			}

			echo json_encode(array('html'=>$html,'map'=>$map));
		}
	break;
}
?>
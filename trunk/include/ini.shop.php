<?php
	class T_SHOP{
//!Информация о магазине
		function Info($id,$w=160,$h=104){
			global $MYSQL, $PAYMENT;

			$GLOBALS['PHP_FILE']=__FILE__;
			$GLOBALS['FUNCTION']=__FUNCTION__;
			$id                 =(int)$id;
			$tbshops            ="pfx_shops";
			//$tbshopsadress      ="pfx_shops_adress";
			$tbpodpiska         ="pfx_podpiska";
			$tbcountry          ="pfx_country";
			$tb_country_shops   ="pfx_country_shops";

			$MYSQL->query("UPDATE `".$tbshops."` SET `preview` = IFNULL(`preview`,0)+1 WHERE `id`=".$id);

			if(isset($_SESSION['KLIENT']))$where=" AND `klient_id`=".$_SESSION['KLIENT']['id'];
			else                          $where=" AND `moderator`=1";

			$shop=$MYSQL->query("
				SELECT `id`, `name`, `url`, `silver`, `gold`, `platinum`, `rate`, `special`, `preview`, `desc`, `descbig`, `fio`, `phone`
				FROM `".$tbshops."`
				WHERE `id`=".$id." ".$where
			);
			if(is_array($shop) && count($shop)==1){
				if($shop[0]['url']!=''){}
				else $shop[0]['url']='/shop-'.$id;

				if(isset($_SESSION['WP_USER'])){
					$my_discount=$PAYMENT->VIPDiscount(@$_SESSION['WP_USER']['user_wp'],$id);
					//if(is_array($my_discount))$vip_discount=$my_discount['Discount']['VIP'];    //убрать коментарий!!!!!!!!!!!
				}

				$podpisok=$MYSQL->query("SELECT Count(*) FROM `".$tbpodpiska."` WHERE `shop_id`=".$id);
				$adrs    =$MYSQL->query("SELECT `id`, `adress`, `latitude`, `longitude`  FROM `pfx_shops_adress` WHERE `shop_id`=".$id);
				foreach($adrs as $key=>$value){
					$value['adress'] = explode("::",$value['adress']);
					$adressa[]=array(
						'id'        => $value['id'],
						'street'    => @$value['adress'][0],
						'house'     => @$value['adress'][1],
						'town'      => @$value['adress'][2],
                        'latitude'  => $value['latitude'],
                        'longitude' => $value['longitude'],
				  );
				}

				$country_id=$MYSQL->query("
					SELECT `".$tbcountry."`.`parent`, `".$tbcountry."`.`name`
					FROM `".$tb_country_shops."`
					INNER JOIN `".$tbcountry."` ON `".$tbcountry."`.`id`=`".$tb_country_shops."`.`country_id`
					WHERE `".$tb_country_shops."`.`shop_id`=".$id." LIMIT 0,1
				");

				if(is_array($country_id)){
					$country=$country_id[0]['name'];
					$country_id=$country_id[0]['parent'];
				}

				$logo=ShowLogo(array(0 => $shop[0]['id']),$w,$h);
				$logo=$logo[0]['logo'];

				$array = array(
					'id'            => $shop[0]['id'],
					'name'          => htmlspecialchars(stripslashes(trim($shop[0]['name']))),
					'URL'           => htmlspecialchars(stripslashes(trim($shop[0]['url']))),
					'logo'          => $logo,
					'silver'        => Amount($shop[0]['silver'],0),
					'gold'          => Amount($shop[0]['gold'],0),
					'platinum'      => Amount($shop[0]['platinum'],0),
					'vip_discount'  => Amount(@$vip_discount,0),
					'rate'          => $shop[0]['rate'],
					'special'       => $shop[0]['special'],
					'preview'       => (int) $shop[0]['preview'],
					'descbig'       => htmlspecialchars(stripslashes(trim($shop[0]['descbig']))),
                    'desc'          => htmlspecialchars(stripslashes(trim($shop[0]['desc']))),
					'fio'           => htmlspecialchars(stripslashes(trim($shop[0]['fio']))),
					'adressa'       => $adressa,
					'phone'         => htmlspecialchars(stripslashes(trim($shop[0]['phone']))),
					'count_podpisok'=> $podpisok[0]['count'],
					'country'       => @$country,
					'country_id'    => @$country_id,
				);
			}
			return @$array;
		}

//!Проверка подписки
		function isSubscribed($shop_id){
			global $MYSQL;
			$GLOBALS['PHP_FILE']=__FILE__;
			$GLOBALS['FUNCTION']=__FUNCTION__;
			$result=$MYSQL->query("SELECT Count(*) FROM `pfx_podpiska` WHERE `user_wp`=".(int)$_SESSION['WP_USER']['user_wp']." AND `shop_id`=".$shop_id);
			if(is_array($result) && $result[0]['count']==0)return true;
			else return false;
		}

//!Количество подписчиков
		function CountSubscribers($shop_id){
			global $MYSQL;
			$GLOBALS['PHP_FILE']=__FILE__;
			$GLOBALS['FUNCTION']=__FUNCTION__;
			$result=$MYSQL->query("SELECT Count(*) FROM `pfx_podpiska` WHERE `shop_id`=".$shop_id);
			if(is_array($result))return $result[0]['count'];
		}

//!Типы
		function ShowType($type_id,$rows=25){
			global $MYSQL, $PAYMENT, $AKCIANAME;

			$GLOBALS['PHP_FILE'] = __FILE__;
		    $GLOBALS['FUNCTION'] = __FUNCTION__;

			$rows    = (int) $rows;
			$type_id = (int) $type_id;
			$page    = (int) page()-1;
			$begin   = $page*$rows;

			if($type_id <= 0) return '';

			$tbshops = "pfx_shops";
			$tbakcia = "pfx_akcia";
			$tbcountryshop = "pfx_country_shops";
			$tbtype     = "pfx_type";


			$akcia = $MYSQL->query("SELECT name_".LANG_SITE." FROM $tbtype WHERE id = $type_id");
			$AKCIANAME = $akcia[0]['name_'.LANG_SITE];

			if(isset($_SESSION['KLIENT'])){
				$where = " AND $tbakcia.klient_id = ".$_SESSION['KLIENT']['id'];
			} else $where = " AND $tbakcia.moderator=1";

			$count_all = $MYSQL->query("SELECT DISTINCT $tbshops.id
	                                 FROM $tbakcia
	                                INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
	                                INNER JOIN $tbcountryshop ON $tbcountryshop.shop_id = $tbshops.id
	                               WHERE $tbcountryshop.country_id=".(int)$_SESSION['TOWN_ID']." AND $tbakcia.idtype = $type_id AND $tbakcia.del<>1 $where");
			 $_SESSION['count_all'] = count($count_all);

	         $result = $MYSQL->query("SELECT DISTINCT $tbshops.id, $tbshops.name, $tbshops.URL, $tbshops.Logo, $tbshops.Silver, $tbshops.Gold, $tbshops.Platinum
	                                 FROM $tbakcia
	                                INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
	                                INNER JOIN $tbcountryshop ON $tbcountryshop.shop_id = $tbshops.id
	                               WHERE $tbcountryshop.country_id = ".(int)$_SESSION['TOWN_ID']." AND $tbakcia.idtype = $type_id AND $tbakcia.del<>1 $where");


			if(is_array($result))
	        foreach($result as $key=>$shop){

	        	if(isset($_SESSION['WP_USER'])){
				   $my_discount = $PAYMENT->VIPDiscount($_SESSION['WP_USER']['user_wp'],$shop['id']);
				     if(is_array($my_discount)){
				  	    $vip_discount = $my_discount['Discount']['VIP'];
				     }
				}

	        	$array[] = array(
	        	  'id'       => $shop['id'],
	        	  'name'     => $shop['name'],
	        	  'url'      => $shop['url'],
	        	  'logo'     => $shop['logo'],
	        	  'silver'   => $shop['silver'],
	        	  'gold'     => $shop['gold'],
	        	  'platinum' => $shop['platinum'],
	        	  'vip_discount' => Amount(@$vip_discount,0),
	        	);
	        }
	        return @$array;
		}

//!Проверка любимого места
		function isFavorite($adressa){
			global $MYSQL;

			$GLOBALS['PHP_FILE']=__FILE__;
			$GLOBALS['FUNCTION']=__FUNCTION__;

			if(!isset($_SESSION['WP_USER']['user_wp'])) return false;

			if(count($adressa)==0) return false;

			$tbplaces      = "pfx_users_places";
			$tbshopsadress = "pfx_shops_adress";

			$result=$MYSQL->query("SELECT Count(*) FROM $tbplaces WHERE `user_wp`=".(int)$_SESSION['WP_USER']['user_wp']." AND `address` IN (".implode(',',$adressa).")");

			if(is_array($result) && $result[0]['count']>0)
				return true;
			else
				return false;
		}

//!Добавить в любимые
		function AddToFav($address_id){
			global $MYSQL;

			$GLOBALS['PHP_FILE'] = __FILE__;
			$GLOBALS['FUNCTION'] = __FUNCTION__;

			if(!isset($_SESSION['WP_USER']['user_wp'])) return false;

			$tbplaces = "pfx_users_places";

			return $MYSQL->query("INSERT INTO $tbplaces (`user_wp`,`address`,`added`) VALUES(".varr_int($_SESSION['WP_USER']['user_wp']).",".varr_int($address_id).",NOW())");
		}

//!Удалить из любимых
		function DeleteFromFav($address_id){
			global $MYSQL;

			$GLOBALS['PHP_FILE'] = __FILE__;
			$GLOBALS['FUNCTION'] = __FUNCTION__;

			if(!isset($_SESSION['WP_USER']['user_wp'])) return false;

			$tbplaces = "pfx_users_places";

			return $MYSQL->query("DELETE FROM $tbplaces WHERE `user_wp`=".varr_int($_SESSION['WP_USER']['user_wp'])." AND `address`=".varr_int($address_id));
		}
		//Сокращенная информация о группе заведений.
		//$id_list - список $id в виде строки. (разделитель - запятая)
		function ShopsInfoMin($id_list, $sort_param='name', $category=1, $sort_type='ASC', $w = 50, $h = 50,$center=true)
		{
			global $MYSQL;

			$GLOBALS['PHP_FILE'] = __FILE__;
			$GLOBALS['FUNCTION'] = __FUNCTION__;
			$user_lat=0;
	        $user_lon=0;
			if(function_exists('geoip_record_by_name'))
	        {
	            $rec=geoip_record_by_name($_SERVER['REMOTE_ADDR']);
	            if($rec)
	            {
	                $user_lat=$rec['latitude'];
	                $user_lon=$rec['longitude'];
	            }
	        }

	        //невозможно отсортировать по дистанции.
	        if ($sort_param == 'distance' && $user_lat == 0)
	        	return 2;
	        $sort_query = '';
	        if ($sort_param == 'name')
	        	$sort_query = " ORDER BY `shop_name` ".$sort_type;
			$result = $MYSQL->query("SELECT  `shop`.`id` AS  `shop_id` , `adres`.`id` AS  `adres_id` , `adres`.`adress` AS  `adres` ,  `shop`.`name` AS  `shop_name` ,  `shop`.`DescBig` AS  `shop_info` ,  `shop`.`Logo` AS  `shop_logo` , IFNULL( `place`.`address` , 0 ) AS  `shop_favorite` , IFNULL(  `adres`.`latitude` , 0 ) AS  `lat` , IFNULL(  `adres`.`longitude` , 0 ) AS  `lon` 
FROM  `discount_shops` AS  `shop` 
LEFT JOIN  `discount_shops_adress` AS  `adres` ON  `shop`.`id`=`adres`.`shop_id` 
LEFT JOIN  `discount_users_places` AS  `place` ON  `adres`.`id`=`place`.`address` AND `place`.`user_wp` ={$_SESSION['WP_USER']['user_wp']}
WHERE `shop`.`id` IN ($id_list)
GROUP BY  `shop`.`id` ".$sort_query);
			if ( ! (is_array($result) && count($result) > 0))
				return 0;

			$shops = array();
			$i = 0;
			$logos = ShowLogoFast($result,$w,$h,$center);
			foreach ($result as $val) {
				$shops[] = array(
					'id' => $val['shop_id'],
					'name' => $val['shop_name'],
					'info' => $val['shop_info'],
					'logo' => $logos[$i]['logo'],
					'fav' => $val['shop_favorite'],
					'lat' => $val['lat'],
					'lon' => $val['lon'],
					'adres_id'=> $val['adres_id'],
					'adres' => $val['adres'],
					'distance' => ($user_lat == 0 || $val['lat'] == 0)?0:calc_distance($user_lon, $user_lat, $val['lon'], $val['lat']),
					);
				$i++;
			}
			if ($sort_param == 'distance')
				$shops = $this -> _SortByDistance($shops);
			return $shops;
		}
		function _SortByDistance($shops)
		{
			$sort_shops = $shops;
			$sortable = array();
			foreach ($shops as $key => $value) {
				$sortable[$key] = $value['distance'];
			}
			array_multisort($sortable, SORT_ASC, $sort_shops);
			return $sort_shops;

		}
		function Search($query, $sort_param='name',$category=1, $flags, $sort_type='ASC', $limit=5, $offset=0)
		{
			global $MYSQL;
			$GLOBALS['PHP_FILE'] = __FILE__;
			$GLOBALS['FUNCTION'] = __FUNCTION__;

			$query = varr_str(strtolower(trim($query)));
			$list_of_query = explode(' ', $query);
			$query = implode('%', $list_of_query);
			$cond_ang_join = $this-> _GetConditions($flags);
			$result = $MYSQL->query("SELECT  `shop`.`id` AS  `shop_id` FROM  `discount_shops` AS  `shop`
LEFT JOIN  `discount_shops_adress` AS  `adres` ON  `shop`.`id` =  `adres`.`shop_id`
LEFT JOIN  `discount_cat_to_shop` AS  `shop_cat` ON  `shop_cat`.`shop_id` =  `shop`.`id` 
LEFT JOIN  `discount_categories` AS  `cat` ON  `cat`.`menu_id` =  `shop_cat`.`cat_id`
{$cond_ang_join['join']}
WHERE (LOWER(`shop`.`name`) LIKE '%{$query}%' OR LOWER(`adres`.`adress`) LIKE '%{$query}%') AND (`cat`.`menu_id`=$category OR `cat`.`menu_level`=$category) {$cond_ang_join['condition']} 
GROUP BY  `shop`.`id` LIMIT {$offset},{$limit} ");
			if ( ! (is_array($result) && count($result) > 0))
				return 0;
			$list_of_id = array();
			foreach ($result as $val)
				$list_of_id[] = $val['shop_id'];
			$list_of_id = implode(',', $list_of_id);
			$shops = $this -> ShopsInfoMin($list_of_id, $sort_param,$category);
			if (is_array($shops))
				return $shops;
			else
				return 0;
		}
		function _GetConditions($flags)
		{
			$user_wp = $_SESSION['WP_USER']['user_wp'];
			$GLOBALS['PHP_FILE'] = __FILE__;
			$GLOBALS['FUNCTION'] = __FUNCTION__;
			$result = array('condition'=>array(),'join'=>array());
			if ($flags['flag-friend-here']> 0 && $flags['flag-ihere'] > 0)
			{
				$result['join'][] = " LEFT JOIN `pfx_users_ihere` ON `pfx_users_ihere`.`user_wp`=$user_wp OR `pfx_users_ihere`.`user_wp`=(SELECT CASE WHEN  `pfx_users_friends`.`user_wp`= $user_wp
THEN  `pfx_users_friends`.`friend_wp` 
ELSE  `pfx_users_friends`.`user_wp` 
END AS `wp`
FROM  `pfx_users_friends` 
WHERE  (`pfx_users_friends`.`user_wp`=$user_wp
OR  `pfx_users_friends`.`friend_wp` =$user_wp) AND `good`=1 GROUP BY `wp`) ";
			$result['condition'][] = " `pfx_users_ihere`.`address_id` = `adres`.`id` ";
			}
			else
			{
				if ($flags['flag-ihere'] > 0){
					$result['join'][] = " LEFT JOIN `pfx_users_ihere` ON `pfx_users_ihere`.`user_wp`=$user_wp ";
					$result['condition'][] = " `pfx_users_ihere`.`address_id` = `adres`.`id` ";
				}
				if ($flags['flag-friend-here'] > 0){
					$result['join'][] = " LEFT JOIN `pfx_users_ihere` ON `pfx_users_ihere`.`user_wp`=(SELECT CASE WHEN  `pfx_users_friends`.`user_wp`= $user_wp
	THEN  `pfx_users_friends`.`friend_wp` 
	ELSE  `pfx_users_friends`.`user_wp` 
	END AS `wp`
	FROM  `pfx_users_friends` 
	WHERE  (`pfx_users_friends`.`user_wp`=$user_wp
	OR  `pfx_users_friends`.`friend_wp` =$user_wp) AND `good`=1 GROUP BY `wp`) ";
					$result['condition'][] = " `pfx_users_ihere`.`address_id` = `adres`.`id` ";
				}
			}
			if ($flags['flag-act'] > 0){
				$result['join'][] = " LEFT JOIN `pfx_akcia` ON `pfx_akcia`.`shop_id`=`shop`.`id` ";
				$result['condition'][] = " `pfx_akcia`.`shop_id` = `shop`.`id` ";
			}
			$result['join'] = implode(' ', $result['join']);
			$result['condition'] = implode(' AND ', $result['condition']);
			if (strlen($result['condition']) > 0)
				$result['condition']=' AND '.$result['condition'];

			
			return $result;
		}
		
		function AddToFavorite($adress_id)
		{
			global $MYSQL;
			$GLOBALS['PHP_FILE'] = __FILE__;
			$GLOBALS['FUNCTION'] = __FUNCTION__;
			$user_wp = $_SESSION['WP_USER']['user_wp'];
			$tbl = 'pfx_users_places';
			$adress_id = varr_int($adress_id);
			//кто-то пытается добавить несуществующее место.
			if ($adress_id <= 0)
				return 0;
			$result = $MYSQL->query("SELECT `address` FROM $tbl WHERE `user_wp` =$user_wp AND `address`=$adress_id ");
			if (count($result) == 0)
			{
				$MYSQL->query("INSERT INTO $tbl (`user_wp`,`address`,`added`) VALUES ($user_wp,$adress_id,NOW())");
				return 1;
			}
			//запись уже присутствует
			else
				return 2;
		}

	}
	$SHOP = new T_SHOP();
?>
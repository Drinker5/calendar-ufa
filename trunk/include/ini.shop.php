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
				SELECT `id`, `name`, `url`, `silver`, `gold`, `platinum`, `rate`, `special`, `preview`, `descbig`, `fio`, `phone`
				FROM `".$tbshops."`
				WHERE `id`=".$id." ".$where
			);
			if(is_array($shop) && count($shop)==1){
				if($shop[0]['url']!=''){}
				else $shop[0]['url']='/shop-'.$id;

				if(isset($_SESSION['WP_USER'])){
					$my_discount=$PAYMENT->VIPDiscount(@$_SESSION['WP_USER']['user_wp'],$id);
					if(is_array($my_discount))$vip_discount=$my_discount['Discount']['VIP'];
				}

				$podpisok=$MYSQL->query("SELECT Count(*) FROM `".$tbpodpiska."` WHERE `shop_id`=".$id);
				$adrs    =$MYSQL->query("SELECT `id`, `adress` FROM `pfx_shops_adress` WHERE `shop_id`=".$id);
				foreach($adrs as $key=>$value){
					$value['adress'] = explode("::",$value['adress']);
					$adressa[]=array(
						'id'    => $value['id'],
						'street'=> @$value['adress'][0],
						'house' => @$value['adress'][1],
						'town'  => @$value['adress'][2],
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

	}
	$SHOP = new T_SHOP();
?>
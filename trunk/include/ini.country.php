<?php
	class T_COUNTRY {

//!Определение города и страны
		function Geo(){
			global $MYSQL, $varr, $_URLP;

			$GLOBALS['PHP_FILE']=__FILE__;
			$GLOBALS['FUNCTION']=__FUNCTION__;

			switch(@$_URLP[0]){
				case 'shop'://Для магазина
					$tbcountry_shops="pfx_country_shops";
					$tbcountry      ="pfx_country";
					$country=$MYSQL->query("
						SELECT `".$tbcountry_shops."`.`country_id` `town_id`, `".$tbcountry."`.`parent` `country_id`
						FROM `".$tbcountry_shops."`
						INNER JOIN `".$tbcountry."` ON `".$tbcountry."`.`id`=`".$tbcountry_shops."`.`country_id`
						WHERE `".$tbcountry_shops."`.`shop_id`=".varr_int(@$_URLP[1])
					);
					if(is_array($country)){
						$_SESSION['COUNTRY_ID']=$country[0]['country_id'];
						$_SESSION['TOWN_ID']   =$country[0]['town_id'];
						return;
					}
				break;

				case 'type'://Дла категорий подарков
					$tbcountry_shops="pfx_country_shops";
					$tbcountry      ="pfx_country";
					$country=$MYSQL->query("
						SELECT `".$tbcountry_shops."`.`country_id` `town_id`, `".$tbcountry."`.`parent` `country_id`
						FROM `".$tbcountry_shops."`
						INNER JOIN `".$tbcountry."` ON `".$tbcountry."`.`id`=`".$tbcountry_shops."`.`country_id`
						WHERE `".$tbcountry_shops."`.`shop_id`=".varr_int(@$_URLP[3])
					);
					if(is_array($country)){
						$_SESSION['COUNTRY_ID']=$country[0]['country_id'];
						$_SESSION['TOWN_ID']   =$country[0]['town_id'];
						return;
					}
				break;

				case 'gift'://Для подарков
					$tbgifts        ="pfx_akcia";
					$tbcountry_shops="pfx_country_shops";
					$tbcountry      ="pfx_country";
					$country=$MYSQL->query("
						SELECT `".$tbcountry_shops."`.`country_id` `town_id`, `".$tbcountry."`.`parent` `country_id`
						FROM `".$tbgifts."`
						INNER JOIN `".$tbcountry_shops."` ON `".$tbcountry_shops."`.`shop_id`=`".$tbgifts."`.`shop_id`
						INNER JOIN `".$tbcountry."` ON `".$tbcountry."`.`id`=`".$tbcountry_shops."`.`country_id`
						WHERE `".$tbgifts."`.`id`=".varr_int(@$_URLP[1])
					);
					if(is_array($country)){
						$_SESSION['COUNTRY_ID']=$country[0]['country_id'];
						$_SESSION['TOWN_ID']   =$country[0]['town_id'];
						return;
					}
				break;
			}

			//Если пользователь не авторизовался на сайте
			if(!isset($_SESSION['WP_USER']) && isset($varr['town_id']) && $varr['town_id']>0){
				$result = $MYSQL->query("SELECT id, parent FROM pfx_country WHERE id=".varr_int($varr['town_id']));
				if(is_array($result) && $result[0]['id']>0){
					$_SESSION['COUNTRY_ID']=$result[0]['parent'];
					$_SESSION['TOWN_ID']   =$result[0]['id'];
					return;
				}
			}

			//Если страны нет в сессии, то определяем ее по IP
			if(varr_int(@$_SESSION['COUNTRY_ID'])<=0){
				$json  =@objectToArray(json_decode(file_get_contents('http://smart-ip.net/geoip-json/'.$MYSQL->tep_get_ip_address())));
				$result=$MYSQL->query("SELECT `id` FROM `pfx_country` WHERE `iso2`='".strtoupper(@$json['countryCode'])."'");
				if(is_array($result) && count($result)==1)
					$_SESSION['COUNTRY_ID']=$result[0]['id'];
				else
					$_SESSION['COUNTRY_ID']=2;
				//Заносим в сессию город по умолчанию
				$result=$MYSQL->query("SELECT `id` FROM `pfx_country` WHERE `parent`=".varr_int($_SESSION['COUNTRY_ID'])." AND `def`=1");
				if(is_array($result))$_SESSION['TOWN_ID']=$result[0]['id'];
			}
		}


//!Список городов
		function Show($ParentID){
			global $MYSQL;

			$GLOBALS['PHP_FILE'] = __FILE__;
		    $GLOBALS['FUNCTION'] = __FUNCTION__;

			$tbcountry = "pfx_country";

			$result = $MYSQL->query("SELECT id, name FROM $tbcountry WHERE IFNULL(parent,0)=".varr_int($ParentID)." ORDER BY name");

			if (is_array($result) && count($result) > 0){
	         foreach($result as $key=>$value){
	          $array[] = array('id' => $value['id'],'name' => $value['name'],'count' => 0); //$this->ShowCountShops($value['id'])
	         }
	        }
			return @$array;
		}


		function ShowTree($ParentID, $lvl=0){

			unset($GLOBALS['lvl']);
			unset($GLOBALS['array']);
			return $this->Tree($ParentID, $lvl);
		}

		function Tree($ParentID, $lvl){
			global $MYSQL, $lvl, $array;

			$GLOBALS['PHP_FILE'] = __FILE__;
		    $GLOBALS['FUNCTION'] = __FUNCTION__;

			$tbcountry = "pfx_country";
	        $lvl++;
	        $result = $MYSQL->query("SELECT id, name, def FROM $tbcountry WHERE IFNULL(parent,0)=".varr_int($ParentID)." ORDER BY sort, name");
	        if (is_array($result) && count($result) > 0) {
	         foreach($result as $key=>$value){
	          $array[] = array('lvl'=>$lvl,'id'=>$value['id'],'name'=>$value['name'],'default'=>$value['def'],'count'=>0); //$this->ShowCountShops($value['id'])
	          $this->Tree($value['id'], $lvl);
	          $lvl--;
	         }
	        }
	        return @$array;
		}

		function ShowTree2($id, $lvl=0){

			unset($GLOBALS['lvl']);
			unset($GLOBALS['array']);
			return $this->Tree2($id, $lvl=0);
		}

		function Tree2($id, $lvl=0){
			global $MYSQL, $lvl, $array;

			$GLOBALS['PHP_FILE'] = __FILE__;
		    $GLOBALS['FUNCTION'] = __FUNCTION__;

			$tbcountry = "pfx_country";
	        $lvl++;
	        $result = $MYSQL->query("SELECT id, parent, name FROM $tbcountry WHERE id = ".varr_int($id)." ORDER BY name");
	        if(is_array($result) && count($result) > 0){
	         foreach($result as $key=>$value){
	          $array[] = array('lvl'=>$lvl,'id'=>$value['id'],'parent'=>$value['parent'],'name'=>$value['name'],'count' => 0); //$this->ShowCountShops($value['id'])
	          $this->Tree2($value['parent'], $lvl);
	          $lvl--;
	         }
	        }
	        return @$array;
		}

		function ShowCountShops($country_id){
			global $MYSQL;

			$GLOBALS['PHP_FILE'] = __FILE__;
		    $GLOBALS['FUNCTION'] = __FUNCTION__;

			$count = 0;
			$tbcountry       = "pfx_country";
			$tbshops         = "pfx_shops";
			$tbcountry_shops = "pfx_country_shops";

			if(isset($_SESSION['KLIENT'])){
				$where = " AND $tbshops.klient_id = ".(int)$_SESSION['KLIENT']['id'];
			} else $where = " AND $tbshops.moderator=1";

			$result = $MYSQL->query("SELECT Count(*) FROM $tbshops
			                       INNER JOIN $tbcountry_shops ON $tbcountry_shops.shop_id = $tbshops.id
			                      WHERE $tbcountry_shops.country_id = ".(int)$country_id." $where
			                      LIMIT 0,1");
			if(is_array($result) && $result[0]['count'] > 0){
				return $result[0]['count'];
			}
			else {
				$result = $MYSQL->query("SELECT id FROM $tbcountry WHERE IFNULL(parent,0) = ".varr_int($country_id)." ORDER BY name");
				if(is_array($result) && count($result) > 0){
				  foreach($result as $key=>$value){
				  	$count = $count + $this->ShowCountShops($value['id']);
	              }
	              return $count;
				} else return 0;
			}
		}
	}
	$COUNTRY = new T_COUNTRY();
?>
<?php
	class T_AKCIA {
//!Подсчет подарков
		function Count($shop_id=0,$type_id=0,$gift=0){
			global $MYSQL;

			$GLOBALS['PHP_FILE']=__FILE__;
			$GLOBALS['FUNCTION']=__FUNCTION__;
			$tbakcia            ="pfx_akcia";
			$tbshops            ="pfx_shops";
			$tbcountryshop      ="pfx_country_shops";
			$tbtypes            ="pfx_type";
			$shop_id            =varr_int($shop_id);
			$type_id            =varr_int($type_id);
			$where              ="";

			if($shop_id>0)$where.=" AND `".$tbakcia."`.`shop_id`=".$shop_id." ";
			if($type_id>0)$where.=" AND `".$tbakcia."`.`idtype`=".$type_id." ";
			if($gift   >0)$where.=" AND `".$tbtypes."`.`gift`=".$gift." ";

			if(isset($_SESSION['KLIENT']))$where.=" AND `".$tbakcia."`.`klient_id`=".$_SESSION['KLIENT']['id'];
			else                          $where.=" AND `".$tbakcia."`.`moderator`=1";

			$akcia_count=$MYSQL->query("
				SELECT Count(*) FROM `".$tbakcia."`
				INNER JOIN `".$tbshops."` ON `".$tbshops."`.`id`=`".$tbakcia."`.`shop_id`
				INNER JOIN `".$tbcountryshop."` ON `".$tbcountryshop."`.`shop_id`=`".$tbshops."`.`id`
				INNER JOIN `".$tbtypes."` ON `".$tbakcia."`.`idtype`=`".$tbtypes."`.`id`
				WHERE `".$tbakcia."`.`del`<>1 ".$where
			);

			return $akcia_count[0]['count'];
		}


		function ShowList($shop_id,$type_id,$rows=0){
			global $MYSQL;

			$shop_id = varr_int($shop_id);
			$type_id = varr_int($type_id);
			$rows    = varr_int($rows);
			$page    = varr_int(page()-1);
			$begin   = $page*$rows;

			$_SESSION['count_all'] = $this->Count($shop_id,$type_id);

			$GLOBALS['PHP_FILE'] = __FILE__;
		    $GLOBALS['FUNCTION'] = __FUNCTION__;

			$tbakcia    = "pfx_akcia";
			$tbtype     = "pfx_type";
			$tbcurrency = "pfx_currency";

			if($rows == 0) $like = ""; else $like = "LIMIT $begin,$rows";

			if(isset($_SESSION['KLIENT'])){
				$where = " AND $tbakcia.klient_id = ".$_SESSION['KLIENT']['id'];
			} else $where = " AND $tbakcia.moderator=1";

			$result = $MYSQL->query("SELECT $tbakcia.id, $tbakcia.header, IFNULL($tbakcia.discdata1,'0000-00-00') datastart, IFNULL($tbakcia.discdata2,'0000-00-00') dataend, $tbakcia.discdata2, $tbakcia.amount, $tbakcia.currency_id, IFNULL($tbtype.dogovor,0) dogovor
			                       FROM $tbakcia
			                       INNER JOIN $tbtype ON $tbtype.id = $tbakcia.idtype
			                      WHERE $tbakcia.idtype = $type_id AND $tbakcia.del<>1 AND $tbakcia.shop_id = $shop_id AND $where
			                      ORDER BY $tbakcia.sort
			                      $like");

			if(is_array($result) && count($result) > 0)
	        foreach($result as $key=>$akcia){

	        	$currency = $MYSQL->query("SELECT currency, mask FROM $tbcurrency WHERE id = ".(int)$akcia['currency_id']);
	        	/*if(is_array($currency) && count($currency) == 1)
	        	   $currency = $currency[0]['mask'];
	        	else
	        	   $currency = '';
	        	   */

	        	$array[] = array(
	        	   'id'          => $akcia['id'],
	        	   'header'      => htmlspecialchars(stripslashes(trim($akcia['header']))),
	        	   'dogovor'     => $akcia['dogovor'],
	        	   'type_id'     => $type_id,
	        	   'amount'      => $akcia['amount'],
	        	   'currency'    => @$currency[0]['mask'],
	        	   'currencypay' => @$currency[0]['currency'],
	        	   'datastart'   => MyDataTime($akcia['datastart'],'date'),
	        	   'datastop'    => MyDataTime($akcia['dataend'],'date'),
	        	);
	        }
	        return @$array;
		}


		function ShowListGroup($type_id,$gr_id=0,$rows,$shop_id=0,$page=1,$random=false){
			global $MYSQL, $AKCIANAME, $SHOPNAME;

			$_SESSION['count_all'] = $this->Count($shop_id,$type_id);

			$GLOBALS['PHP_FILE'] = __FILE__;
		    $GLOBALS['FUNCTION'] = __FUNCTION__;

			$type_id = varr_int($type_id);
			$shop_id = varr_int($shop_id);
			$gr_id   = varr_int($gr_id);
			$rows    = varr_int($rows);
			$page    = varr_int($page-1);
			$begin   = $page*$rows;
			$where   = "";

			$tbakcia    = "pfx_akcia";
			$tbtype     = "pfx_type";
			$tbcurrency = "pfx_currency";
			$tbshops    = "pfx_shops";
			$tbcountryshop = "pfx_country_shops";

			$akcia = $MYSQL->query("SELECT name_".LANG_SITE." name FROM $tbtype WHERE id = $type_id");
			$AKCIANAME = $akcia[0]['name'];

			$shop = $MYSQL->query("SELECT name FROM $tbshops WHERE id = $shop_id");
			$SHOPNAME = $shop[0]['name'];

			if($shop_id > 0) $where = " AND $tbshops.id = $shop_id";

			if(isset($_SESSION['KLIENT'])){
				$where .= " AND $tbakcia.klient_id = ".$_SESSION['KLIENT']['id'];
			} else $where .= " AND $tbakcia.moderator=1";

			if($random) $random = " RAND() "; else $random = " $tbakcia.sort ";

			$result = $MYSQL->query("SELECT $tbakcia.id, $tbakcia.shop_id, $tbakcia.header, IFNULL($tbakcia.discdata1,'0000-00-00') datastart, IFNULL($tbakcia.discdata2,'0000-00-00') dataend, $tbakcia.amount, $tbakcia.currency_id, IFNULL($tbtype.dogovor,0) dogovor, $tbshops.name shop_name
			                        FROM $tbakcia
			                       INNER JOIN $tbtype  ON $tbtype.id  = $tbakcia.idtype
			                       INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
			                       INNER JOIN $tbcountryshop ON $tbcountryshop.shop_id = $tbshops.id
			                      WHERE $tbcountryshop.country_id = ".(int)$_SESSION['TOWN_ID']." AND $tbakcia.idtype=$type_id AND $tbakcia.del<>1 $where
			                      ORDER BY $random
			                      LIMIT $begin,$rows");

			if(is_array($result)){
	        foreach($result as $key=>$akcia){

	        	$currency = $MYSQL->query("SELECT currency, mask FROM $tbcurrency WHERE id = ".(int)$akcia['currency_id']);
	        	if(is_array($currency))
	        	   $currency = $currency[0]['mask'];
	        	else
	        	   $currency = '';

	        	$array[] = array(
	        	   'akcia_id'  => $akcia['id'],
	        	   'shop_id'   => $akcia['shop_id'],
	        	   'shop_name' => $akcia['shop_name'],
	        	   'header'    => htmlspecialchars(stripslashes(trim($akcia['header']))),
	        	   'dogovor'   => $akcia['dogovor'],
	        	   'type_id'   => $type_id,
	        	   'amount'    => $akcia['amount'],
	        	   'currency'  => $currency,
	        	   'datastart' => MyDataTime($akcia['datastart'],'date'),
	        	   'datastop'  => MyDataTime($akcia['dataend'],'date'),
	        	);
	        }
	        return @$array;
			}
			//return array(array('akcia_id'=>0,'header'=>'В этом разделе нет ни одного предложения',));
		}


	    function Show($id,$w=160,$h=104){
	    	global $MYSQL, $GROUPS, $USER;

		    $GLOBALS['PHP_FILE'] = __FILE__;
		    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    	$id = varr_int($id);
	    	$tbshops    = "pfx_shops";
	    	$tbshopsaddr= "pfx_shops_adress";
	    	$tbakcia    = "pfx_akcia";
	    	$tbtype     = "pfx_type";
			$tbcurrency = "pfx_currency";
			$tbcountry  = "pfx_country";
			$tb_country_shops = "pfx_country_shops";

			//$MYSQL->query("UPDATE $tbakcia SET preview = IFNULL(preview,0)+1 WHERE id = $id");

			if(isset($_SESSION['KLIENT'])){
				$where = " AND $tbakcia.klient_id = ".$_SESSION['KLIENT']['id'];
			} else $where = " AND $tbakcia.moderator=1";

	    	$akcia = $MYSQL->query("SELECT $tbakcia.shop_id, $tbakcia.group_id, $tbakcia.header, $tbakcia.mtext, $tbakcia.preview, IFNULL($tbakcia.discdata1,'0000-00-00') datastart, IFNULL($tbakcia.discdata2,'0000-00-00') dataend, $tbakcia.amount, $tbakcia.currency_id, $tbtype.dogovor, $tbakcia.idtype, $tbshops.phone, $tbshops.name shopname, $tbakcia.link, $tbakcia.klient_id, $tbtype.name_".LANG_SITE." type_name, $tbakcia.discount, $tbakcia.kolvo, $tbshopsaddr.adress, $tbshopsaddr.latitude, $tbshopsaddr.longitude
			                       FROM $tbakcia
			                       INNER JOIN $tbtype  ON $tbtype.id  = $tbakcia.idtype
			                       INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
			                       INNER JOIN $tbshopsaddr ON $tbshopsaddr.shop_id = $tbakcia.shop_id
			                      WHERE $tbakcia.id = $id AND $tbakcia.del<>1 $where");

	    	if(is_array($akcia) && count($akcia) == 1){

	    	   $USER->PodpiskaViewAdd($id);

	    	   $currency = $MYSQL->query("SELECT currency, mask FROM $tbcurrency WHERE id = ".(int)$akcia[0]['currency_id']);

	    	   $gr = $GROUPS->ShowTree2($akcia[0]['group_id']);

	    	   $photo = ShowFotoAkcia(array($id),$w,$h);
	    	   $photobig = @$photo[0]['file'];
	    	   $photo = @$photo[0]['foto'];

	    	   $country_id = $MYSQL->query("SELECT $tbcountry.parent, $tbcountry.name
				                               FROM $tb_country_shops
				                              INNER JOIN $tbcountry ON $tbcountry.id = $tb_country_shops.country_id
				                             WHERE $tb_country_shops.shop_id = ".(int)$akcia[0]['shop_id']." LIMIT 0,1");

				if(is_array($country_id)){
					$country = $country_id[0]['name'];
				    $country_id = $country_id[0]['parent'];
				}

								

				return array(
		         'id'          => $id,
		         'header'      => htmlspecialchars(stripslashes(trim($akcia[0]['header']))),
		         'mtext'       => htmlspecialchars(stripslashes(trim($akcia[0]['mtext']))),
		         'phone'       => htmlspecialchars(stripslashes(trim($akcia[0]['phone']))),
		         'url'         => '<a target="_blank" href="/getto.php?urlto='.base64_encode($akcia[0]['link']).'">'.htmlspecialchars(stripslashes(trim($akcia[0]['link']))).'</a>',
		         'shop_id'     => $akcia[0]['shop_id'],
		         'shopname'    => htmlspecialchars(stripslashes(trim($akcia[0]['shopname']))),
		         'klient_id'   => $akcia[0]['klient_id'],
		         'dogovor'     => $akcia[0]['dogovor'],
	        	 'amount'      => $akcia[0]['amount'],
	        	 'discount'    => $akcia[0]['discount'],
	        	 'kolvo'       => $akcia[0]['kolvo'],
	        	 'type_id'     => $akcia[0]['idtype'],
	        	 'type_name'   => $akcia[0]['type_name'],
	        	 'currency_id' => $akcia[0]['currency_id'],
	        	 'currency'    => @$currency[0]['mask'],
	        	 'currencypay' => @$currency[0]['currency'],
	        	 'groups'      => @$gr,
	        	 'address'     => explode('::',$akcia[0]['adress']),
	        	 'latitude'    => $akcia[0]['latitude'],
	        	 'longitude'   => $akcia[0]['longitude'],
	        	 'country'     => @$country,
				 'country_id'  => @$country_id,
		         'preview'     => (int) $akcia[0]['preview'],
		         'datastart'   => MyDataTime($akcia[0]['datastart'],'date'),
	        	 'datastop'    => MyDataTime($akcia[0]['dataend'],'date'),
	        	 'photo'       => $photo,
	        	 'photobig'    => $photobig,
	        	 'photobig'    => $photobig,
		       );
	    	}
	    }


	    function Info_min($id,$w=183,$h=128){
	    	global $MYSQL;

		    $GLOBALS['PHP_FILE'] = __FILE__;
		    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    	$id         = varr_int($id);
	    	$tbshops    = "pfx_shops";
	    	$tbakcia    = "pfx_akcia";
	    	$tbtype     = "pfx_type";
			$tbcurrency = "pfx_currency";
			$tbcountry  = "pfx_country";
			$tb_country_shops = "pfx_country_shops";

			if(isset($_SESSION['KLIENT'])){
				$where = " AND $tbakcia.klient_id = ".$_SESSION['KLIENT']['id'];
			} else $where = " AND $tbakcia.moderator=1";

	    	$akcia = $MYSQL->query("SELECT $tbakcia.shop_id, $tbakcia.header, IFNULL($tbakcia.discdata1,'0000-00-00') datastart, IFNULL($tbakcia.discdata2,'0000-00-00') dataend, $tbakcia.amount, $tbakcia.currency_id, $tbtype.dogovor, $tbakcia.idtype, $tbshops.name shop_name, $tbtype.name_".LANG_SITE." type_name, $tbakcia.kolvo, $tbakcia.klient_id, $tbshops.phone
			                       FROM $tbakcia
			                       INNER JOIN $tbtype  ON $tbtype.id  = $tbakcia.idtype
			                       INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
			                      WHERE $tbakcia.id = $id AND $tbakcia.del<>1 $where");

	    	if(is_array($akcia) && count($akcia) == 1){

	    	   $currency = $MYSQL->query("SELECT currency, mask FROM $tbcurrency WHERE id = ".(int)$akcia[0]['currency_id']);
	    	   $photo = ShowFotoAkcia(array($id),$w,$h);
	    	   if(is_array($photo))
	    	       $photo = $photo[0]['foto'];

	    	   $country_id = $MYSQL->query("SELECT $tbcountry.parent, $tbcountry.name
				                               FROM $tb_country_shops
				                              INNER JOIN $tbcountry ON $tbcountry.id = $tb_country_shops.country_id
				                             WHERE $tb_country_shops.shop_id = ".(int)$akcia[0]['shop_id']." LIMIT 0,1");

				if(is_array($country_id)){
					$country = $country_id[0]['name'];
				    $country_id = $country_id[0]['parent'];
				}

		       return array(
		         'id'          => $id,
		         'header'      => htmlspecialchars(stripslashes(trim($akcia[0]['header']))),
		         'shop_id'     => $akcia[0]['shop_id'],
		         'shop_name'   => htmlspecialchars(stripslashes(trim($akcia[0]['shop_name']))),
		         'phone'       => htmlspecialchars(stripslashes(trim($akcia[0]['phone']))),
		         'klient_id'   => $akcia[0]['klient_id'],
		         'dogovor'     => $akcia[0]['dogovor'],
	        	 'kolvo'       => $akcia[0]['kolvo'],
	        	 'type_id'     => $akcia[0]['idtype'],
	        	 'type_name'   => $akcia[0]['type_name'],
	        	 'amount'      => $akcia[0]['amount'],
	        	 'currency_id' => $akcia[0]['currency_id'],
	        	 'currency'    => @$currency[0]['mask'],
		         'datastart'   => MyDataTime($akcia[0]['datastart'],'date'),
	        	 'datastop'    => MyDataTime($akcia[0]['dataend'],'date'),
	        	 'photo'       => @$photo,
	        	 'country'     => @$country,
				 'country_id'  => @$country_id,
		       );
	    	}
	    }
	}

	$AKCIA = new T_AKCIA();
?>
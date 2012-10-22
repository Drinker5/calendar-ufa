<?php
	class T_GROUPS {
		//!Группа
		function ShowGroup($id){
			global $MYSQL;
			$GLOBALS['PHP_FILE']=__FILE__;
			$GLOBALS['FUNCTION']=__FUNCTION__;
			$result=$MYSQL->query("SELECT `menu_id`, `name_".LANG_SITE."` `menu_name` FROM `pfx_categories` WHERE IFNULL(`visible`,0)=1 AND IFNULL(`menu_id`,0)=".$id);
			if(is_array($result)){
				foreach($result as $key=>$value){
					$array[]=array('id'=>$value['menu_id'],'name'=>$value['menu_name']);
				}
			}
			return @$array;
		}


	function Show($ParentID){
		global $MYSQL;

		$ParentID = varr_int($ParentID);

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$result = $MYSQL->query("SELECT menu_id, name_".LANG_SITE." menu_name, img FROM pfx_categories WHERE IFNULL(visible,0)=1 AND IFNULL(menu_level,0)=".$ParentID." ORDER BY menu_name");

		if(is_array($result)){
         foreach($result as $key=>$value){
          $array[] = array('id' => $value['menu_id'],'name' => $value['menu_name'],'img' => $value['img'],'count' => $this->ShowCountTovar($value['menu_id']));
         }
        }
		return @$array;
	}

	function ShowTree($ParentID, $lvl=0, $active=0, $count=0){

		unset($GLOBALS['lvl']);
		unset($GLOBALS['array']);
		return $this->Tree($ParentID, $lvl, $active, $count);
	}

	function Tree($ParentID, $lvl, $active, $count){
		global $MYSQL, $lvl, $array;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $ParentID = varr_int($ParentID);

        $lvl++;
        if($active == 0){
        $result = $MYSQL->query("SELECT menu_id, name_".LANG_SITE." menu_name, menu_level, IFNULL(visible,0) active FROM pfx_categories WHERE IFNULL(visible,0)=1 AND IFNULL(menu_level,0)=".$ParentID." ORDER BY menu_name");
        } elseif($active == 1){
        	$result = $MYSQL->query("SELECT menu_id, name_".LANG_SITE." menu_name, menu_level, IFNULL(visible,0) active FROM pfx_categories WHERE IFNULL(menu_level,0)=".$ParentID." ORDER BY menu_name");
        }

        if(is_array($result)){
         foreach($result as $key=>$value){
          $rows = $this->ShowCountTovar($value['menu_id']);
          if($count == 1 && $rows > 0)
           $array[] = array('lvl'=>$lvl,'id'=>$value['menu_id'],'parent'=>$value['menu_level'],'name'=>$value['menu_name'],'active'=>$value['active'],'count'=>$rows);
          elseif($count == 0)
           $array[] = array('lvl'=>$lvl,'id'=>$value['menu_id'],'parent'=>$value['menu_level'],'name'=>$value['menu_name'],'active'=>$value['active'],'count'=>$rows);
          $this->Tree($value['menu_id'], $lvl, $active, $count);
          $lvl--;
         }
        }
        return @$array;
	}

	function ShowTree2($id, $lvl=0, $active=0){

		unset($GLOBALS['lvl']);
		unset($GLOBALS['array']);
		return $this->Tree2($id, $lvl=0, $active=0);
	}

	function Tree2($id, $lvl=0, $active=0){
		global $MYSQL, $lvl, $array;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $id = varr_int($id);

        $lvl++;
        if($active == 0){
           $result = $MYSQL->query("SELECT menu_id, menu_level, name_".LANG_SITE." menu_name FROM pfx_categories WHERE IFNULL(visible,0)=1 AND menu_id=".$id." ORDER BY menu_name");
        } elseif($active == 1){
           $result = $MYSQL->query("SELECT menu_id, menu_level, name_".LANG_SITE." menu_name FROM pfx_categories WHERE menu_id=".$id." ORDER BY menu_name");
        }

        if(is_array($result)){
         foreach($result as $key=>$value){
          $array[] = array('lvl'=>$lvl,'id'=>$value['menu_id'],'parent'=>$value['menu_level'],'name'=>$value['menu_name'],'count' => $this->ShowCountTovar($value['menu_id']));
          $this->Tree2($value['menu_level'], $lvl, $active);
          $lvl--;
         }
        }
        return @$array;
	}


	function ShowCountTovar($GRID){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $GRID = varr_int($GRID);

	    //return 0;

		$count = 0;

		if(isset($_SESSION['KLIENT'])){
			$where = " AND pfx_shops.klient_id = ".$_SESSION['KLIENT']['id'];
		} else $where = " AND pfx_shops.moderator=1";

		$result = $MYSQL->query("SELECT Count(*) FROM pfx_shops
		                       INNER JOIN pfx_cat_to_shop ON pfx_cat_to_shop.shop_id = pfx_shops.id
		                       INNER JOIN pfx_country_shops ON pfx_country_shops.shop_id = pfx_shops.id
		                      WHERE pfx_country_shops.country_id = ".(int)$_SESSION['TOWN_ID']." AND pfx_cat_to_shop.cat_id = ".$GRID." $where");
		if(is_array($result) && $result[0]['count'] > 0){
			return $result[0]['count'];
		}
		else {
			$result = $MYSQL->query("SELECT menu_id FROM pfx_categories WHERE IFNULL(visible,0)=1 AND IFNULL(menu_level,0)=".$GRID." ORDER BY name_".LANG_SITE."");
			if(is_array($result)){
			  foreach($result as $key=>$value){
			  	$count = $count + $this->ShowCountTovar($value['menu_id']);
              }
              return $count;
			} else return 0;
		}
	}


	function ShowGroupType($type_id,$group_id=0){
		global $MYSQL, $AKCIANAME;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

	    $type_id  = varr_int($type_id);
	    $group_id = varr_int($group_id);

	    $tbgroups      = "pfx_categories";
		$tbskgr        = "pfx_cat_to_shop";
		$tbakcia       = "pfx_akcia";
		$tbcountryshop = "pfx_country_shops";
		$tbtype        = "pfx_type";

		$akcia = $MYSQL->query("SELECT name_".LANG_SITE." name FROM $tbtype WHERE id = $type_id");
		$AKCIANAME = $akcia[0]['name'];

		if(isset($_SESSION['KLIENT'])){
			$where = " AND $tbakcia.klient_id = ".$_SESSION['KLIENT']['id'];
		} else $where = " AND $tbakcia.moderator=1";

	    return $MYSQL->query("SELECT DISTINCT $tbgroups.menu_id gr_id, $tbgroups.name_".LANG_SITE." gr_name, $tbgroups.img gr_img, $tbgroups.classhtml
	                                FROM $tbgroups
	                               INNER JOIN $tbskgr ON $tbskgr.cat_id = $tbgroups.menu_id
	                               INNER JOIN $tbakcia ON $tbakcia.shop_id = $tbskgr.shop_id
	                               INNER JOIN $tbcountryshop ON $tbcountryshop.shop_id = $tbskgr.shop_id
	                             WHERE $tbgroups.menu_level=$group_id AND $tbcountryshop.country_id=".(int)$_SESSION['TOWN_ID']." AND $tbakcia.idtype = $type_id $where
	                             ORDER BY $tbgroups.name_".LANG_SITE."");
	}


	function ShowShops($grid=0,$typeid=0,$rows=20,$page=1){
		global $MYSQL, $USER, $PAYMENT;

		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;

		$grid   = varr_int($grid);
		$rows   = varr_int($rows);
		$typeid = varr_int($typeid);
		$page   = varr_int($page-1);
		$begin  = $page*$rows;
		$where  = "";

		$tbshops = "pfx_shops";
		$tbskgr  = "pfx_cat_to_shop";
		$tbakcia = "pfx_akcia";
		$tbcountryshop = "pfx_country_shops";

		if($grid > 0)
		   $where = " AND $tbskgr.cat_id=$grid";

		if($typeid > 0){

		if(isset($_SESSION['KLIENT'])){
			$where .= " AND $tbakcia.klient_id = ".$_SESSION['KLIENT']['id'];
		} else {
			$where .= " AND $tbakcia.moderator=1";
		}

		 $count_all = $MYSQL->query("SELECT DISTINCT $tbshops.id
                                 FROM $tbakcia
                                INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
                                INNER JOIN $tbskgr ON $tbskgr.shop_id = $tbshops.id
                                INNER JOIN $tbcountryshop ON $tbcountryshop.shop_id = $tbshops.id
                               WHERE $tbcountryshop.country_id=".(int)$_SESSION['TOWN_ID']." AND $tbakcia.idtype = $typeid $where");
		 $_SESSION['count_all'] = count($count_all);

         $result = $MYSQL->query("SELECT DISTINCT Count($tbakcia.shop_id) count_akcia, $tbshops.id, $tbshops.Name, $tbshops.Silver, $tbshops.Gold, $tbshops.Platinum
                                 FROM $tbakcia
                                INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
                                INNER JOIN $tbskgr ON $tbskgr.shop_id = $tbshops.id
                                INNER JOIN $tbcountryshop ON $tbcountryshop.shop_id = $tbshops.id
                               WHERE $tbcountryshop.country_id = ".(int)$_SESSION['TOWN_ID']." AND $tbakcia.idtype = $typeid $where
                               GROUP BY $tbskgr.shop_id LIMIT $begin,$rows");
		}
		else {

		if(isset($_SESSION['KLIENT'])){
			$where .= " AND $tbshops.klient_id = ".$_SESSION['KLIENT']['id'];
		} else $where .= " AND $tbshops.moderator=1";

		$count_all = $MYSQL->query("SELECT COUNT(*) FROM $tbshops
		                           INNER JOIN $tbskgr ON $tbskgr.shop_id = $tbshops.id
		                           INNER JOIN $tbcountryshop ON $tbcountryshop.shop_id = $tbshops.id
                                  WHERE $tbcountryshop.country_id = ".(int)$_SESSION['TOWN_ID']." $where");
        $_SESSION['count_all'] = $count_all[0]['count'];

        $result = $MYSQL->query("SELECT $tbshops.id, $tbshops.Name, $tbshops.Silver, $tbshops.Gold, $tbshops.Platinum
		                      FROM $tbshops
		                       INNER JOIN $tbskgr ON $tbskgr.shop_id = $tbshops.id
		                       INNER JOIN $tbcountryshop ON $tbcountryshop.shop_id = $tbshops.id
		                      WHERE $tbcountryshop.country_id = ".(int)$_SESSION['TOWN_ID']." $where
		                      ORDER BY $tbshops.Platinum DESC LIMIT $begin,$rows");
		}

		if(is_array($result))
        foreach($result as $key=>$shop){

        	if(isset($_SESSION['WP_USER'])){
			   $my_discount = $PAYMENT->VIPDiscount($_SESSION['WP_USER']['user_wp'],$shop['id']);
			     if(is_array($my_discount)){
			  	    $vip_discount = $my_discount['Discount']['VIP'];
			     }
			}

        	$array[] = array(
        	  'shop_id'       => $shop['id'],
        	  'shop_name'     => $shop['name'],
        	  'shop_silver'   => Amount($shop['silver'],0),
        	  'shop_gold'     => Amount($shop['gold'],0),
        	  'shop_platinum' => Amount($shop['platinum'],0),
        	  'vip_discount'  => Amount(@$vip_discount,0),
        	);
        }
        return @$array;
	}



}
	$GROUPS = new T_GROUPS();
?>
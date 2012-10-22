<?php

class T_STARS {
	
	function StarName($star_id){
		global $MYSQL;
		
		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;
		
	    $star_id = (int) $star_id;
	    
	    $result = $MYSQL->query("SELECT name_".LANG_SITE." name FROM pfx_stars WHERE menu_id=$star_id");
		return @$result[0]['name'];
	}
	
	function Show($ParentID){
		global $MYSQL;
		
		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;
		
		$result = $MYSQL->query("SELECT menu_id, name_".LANG_SITE." menu_name, img FROM pfx_stars WHERE IFNULL(visible,0)=1 AND IFNULL(menu_level,0)=".(int)$ParentID." ORDER BY sort");
		
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
		
        $lvl++;
        if($active == 0){
        $result = $MYSQL->query("SELECT menu_id, name_".LANG_SITE." menu_name, menu_level, IFNULL(visible,0) active FROM pfx_stars WHERE IFNULL(visible,0)=1 AND IFNULL(menu_level,0)=".(int)$ParentID." ORDER BY menu_name");
        } elseif($active == 1){
        	$result = $MYSQL->query("SELECT menu_id, name_".LANG_SITE." menu_name, menu_level, IFNULL(visible,0) active FROM pfx_stars WHERE IFNULL(menu_level,0)=".(int)$ParentID." ORDER BY menu_name");
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
		
        $lvl++;
        if($active == 0){
           $result = $MYSQL->query("SELECT menu_id, menu_level, name_".LANG_SITE." menu_name FROM pfx_stars WHERE IFNULL(visible,0)=1 AND menu_id=".(int)$id." ORDER BY menu_name");
        } elseif($active == 1){
           $result = $MYSQL->query("SELECT menu_id, menu_level, name_".LANG_SITE." menu_name FROM pfx_stars WHERE menu_id=".(int)$id." ORDER BY menu_name");
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
	    
	    return 0;
		
		$count = 0;
		
		$result = $MYSQL->query("SELECT Count(*) FROM pfx_shops
		                       INNER JOIN pfx_cat_to_shop ON pfx_cat_to_shop.shop_id = pfx_shops.id
		                       INNER JOIN pfx_country_shops ON pfx_country_shops.shop_id = pfx_shops.id
		                      WHERE pfx_country_shops.country_id = ".(int)$_SESSION['TOWN_ID']." AND pfx_cat_to_shop.cat_id = ".(int)$GRID." AND pfx_shops.moderator=1");
		if(is_array($result) && $result[0]['count'] > 0){
			return $result[0]['count'];
		}
		else {
			$result = $MYSQL->query("SELECT menu_id FROM pfx_categories WHERE IFNULL(visible,0)=1 AND IFNULL(menu_level,0)=".(int)$GRID." ORDER BY name_".LANG_SITE."");
			if(is_array($result)){
			  foreach($result as $key=>$value){
			  	$count = $count + $this->ShowCountTovar($value['menu_id']);
              }
              return $count;
			} else return 0;
		}
	}	
}
$STARS = new T_STARS();
?>
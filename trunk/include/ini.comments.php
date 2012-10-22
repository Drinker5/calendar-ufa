<?php
class T_COMMENT {
	
	function CountComments($data=array()){
		global $MYSQL;
				
		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;
	    
	    $tbcomments = "pfx_users_comments";
	    $where = "";
	    
	    if(isset($data['lenta_id'])){
	    	$where = " deystvie_id=".varr_int($data['lenta_id']);
	    }
	    elseif(isset($data['shop_id'])){
	    	$where = " shop_id=".varr_int($data['shop_id']);
	    }
	    elseif(isset($data['akcia_id'])){
	    	$where = " akcia_id=".varr_int($data['akcia_id']);
	    }
	    elseif(isset($data['photo_id'])){
	    	$where = " photo_id=".varr_int($data['photo_id']);
	    }

	    if($where == "") return 0;
	    $result = $MYSQL->query("SELECT Count(*) FROM $tbcomments WHERE ".$where);

	    return $result[0]['count'];
	}
	
	
	function ShowComments($data=array(),$rows=0,$begin=0,$order='DESC'){
		global $MYSQL, $USER;
				
		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;
	    
	    $rows   = varr_int($rows);
		//$page   = varr_int($page-1);
		//$begin  = $page*$rows;
		$begin  = varr_int($begin);
		$limit  = "";
		$where  = "";
	    
		$tbusers = "pfx_users";
		$tbcomments = "pfx_users_comments";
	    
	    if(isset($data['lenta_id'])){
	    	$where = " deystvie_id=".varr_int($data['lenta_id']);
	    }
	    elseif(isset($data['shop_id'])){
	    	$where = " shop_id=".varr_int($data['shop_id']);
	    }
	    elseif(isset($data['akcia_id'])){
	    	$where = " akcia_id=".varr_int($data['akcia_id']);
	    }
	    elseif(isset($data['photo_id'])){
	    	$where = " photo_id=".varr_int($data['photo_id']);
	    }
	    else return;
		
	    if($rows > 0) $limit = "LIMIT $begin,$rows";
	    
	    $result = $MYSQL->query("SELECT $tbcomments.id, IFNULL($tbcomments.parent,0) parent, $tbcomments.data, $tbcomments.msg, $tbusers.user_wp, $tbusers.firstname, $tbusers.lastname
	                                FROM $tbcomments
	                              INNER JOIN $tbusers ON $tbusers.user_wp = $tbcomments.user_wp
	                             WHERE $where ORDER BY $tbcomments.data $order
	                             $limit");
	    
        if(is_array($result) && count($result) > 0){
        	foreach($result as $key=>$value){
        		$result[$key]['user'] = $USER->Info_min($value['user_wp'],40,40);
        	    $result[$key]['msg']  = ShowSmile($value['msg']);
        	    $result[$key]['id']   = varr_int($value['id']);
        	    $result[$key]['date'] = $value['data'];
        	}
        	return $result; //array_reverse($result);
        }
	}
	
	
	function Add($data=array(),$msg,$parent=0){
		global $MYSQL;
		
		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;
	    
	    if(!isset($_SESSION['WP_USER']['user_wp'])) return false;
	    
	    $tbcomments = "pfx_users_comments";
		
	    $parent = varr_int($parent); if($parent <= 0) $parent = 'null';
	    $msg    = varr_str($msg);	    
	    
	    if(isset($data['lenta_id'])){
	    	$pole  = "deystvie_id";
	    	$value = varr_int($data['lenta_id']);
	    }
	    elseif(isset($data['shop_id'])){
	    	$pole  = "shop_id";
	    	$value = varr_int($data['shop_id']);
	    }
	    elseif(isset($data['akcia_id'])){
	    	$pole  = "akcia_id";
	    	$value = varr_int($data['akcia_id']);
	    }
	    elseif(isset($data['photo_id'])){
	    	$pole  = "photo_id";
	    	$value = varr_int($data['photo_id']);
	    }
	    else return false;
	    
	    return $MYSQL->query("INSERT INTO $tbcomments (data,parent,user_wp,msg,$pole) VALUES (now(),$parent,".varr_int($_SESSION['WP_USER']['user_wp']).",'$msg',$value)");
	}
	
	
	function Delete($n){
		global $MYSQL;
		
		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;
	    
	    if(!isset($_SESSION['WP_USER']['user_wp'])) return false;
	    
	    $tbcomments = "pfx_users_comments";

		return $MYSQL->query("DELETE FROM $tbcomments WHERE `id`=".varr_int($n));
	}
	
	/*********************************************************************************************/
	function Show($ParentID){
		global $MYSQL;
		
		$ParentID = varr_int($ParentID);
		
		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;
		
		$result = $MYSQL->query("SELECT id, data, msg, user_wp, deystvie_id FROM pfx_users_comments WHERE parent=$ParentID ORDER BY data");
		
		if(is_array($result)){
         foreach($result as $key=>$value){
          $array[] = array('id' => $value['id'],'data' => $value['data'],'msg' => $value['msg'],'user_wp' => $value['user_wp'],'deystvie_id' => $value['deystvie_id']);
         }
        }
		return @$array;
	}	
	
	function ShowTree($ParentID, $lvl=0, $count=0){
		
		unset($GLOBALS['lvl']);
		unset($GLOBALS['array']);
		return $this->Tree($ParentID, $lvl, $count);
	}
	
	function Tree($ParentID, $lvl, $count){
		global $MYSQL, $lvl, $array;
		
		$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;
	    
	    $ParentID = varr_int($ParentID);
		
        $lvl++;
        $result = $MYSQL->query("SELECT id, data, msg, user_wp, deystvie_id FROM pfx_users_comments WHERE parent=$ParentID ORDER BY data");
        if(is_array($result)){
         foreach($result as $key=>$value){
          $array[] = array('lvl'=>$lvl,'id' => $value['id'],'parent'=>$ParentID,'data' => $value['data'],'msg' => $value['msg'],'user_wp' => $value['user_wp'],'deystvie_id' => $value['deystvie_id']);
          $this->Tree($value['id'], $lvl, $count);
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
	    
	    $id = varr_int($id);
		
        $lvl++;        
        $result = $MYSQL->query("SELECT id, parent, data, msg, user_wp, deystvie_id FROM pfx_users_comments WHERE id=$id ORDER BY data");
        if(is_array($result)){
         foreach($result as $key=>$value){
          $array[] = array('lvl'=>$lvl,'id' => $value['id'],'parent'=>$value['parent'],'data' => $value['data'],'msg' => $value['msg'],'user_wp' => $value['user_wp'],'deystvie_id' => $value['deystvie_id']);
          $this->Tree2($value['id'], $lvl);
          $lvl--;
         }
        }
        return @$array;
	}
}
$COMMENTS = new T_COMMENT();
?>
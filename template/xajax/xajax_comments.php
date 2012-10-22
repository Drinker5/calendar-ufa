<?php

function AddComment($data,$msg){
	global $MYSQL, $USER;
	
	$msg = DeleteTags($msg);
	$tbcomments = "pfx_users_comments";
	$tbusers_deystvie = "pfx_users_deystvie";
	$id = 0;
	
	$objResponse = new xajaxResponse();
	
	if(isset($_SESSION['WP_USER']) && $_SESSION['WP_USER']['user_wp'] > 1000){
		
	  if(is_array($data)){
	   if(isset($data['shop_id']) && $data['shop_id'] > 0){
	      $par = array('shop_id' => $data['shop_id']);
	      $id  = $data['shop_id']; $shop_id = $id;
	   }
	    elseif(isset($data['akcia_id']) && $data['akcia_id'] > 0){
	      $par = array('akcia_id' => $data['akcia_id']);
	      $id  = $data['akcia_id']; $akcia_id = $id;
	    }
	    elseif(isset($data['photo_id']) && $data['photo_id'] > 0){
	      $par = array('photo_id' => $data['photo_id']);
	      $id  = $data['photo_id']; $photo_id = $id;
	    }
	    elseif(isset($data['adress_id']) && $data['adress_id'] > 0 and isset($data['user_wp']) && $data['user_wp'] > 0){
	      $par = array('adress_id' => $data['adress_id'], 'user_wp' => $data['user_wp']);
	      $id  = $data['adress_id']; $address_id = $id;
	    }
		
	    $result = $MYSQL->query("INSERT INTO $tbcomments (user_wp,data,msg,shop_id,akcia_id,photo_id,address_id,to_wp) VALUES (".(int)$_SESSION['WP_USER']['user_wp'].",now(),'$msg',".(int)@$shop_id.",".(int)@$akcia_id.",".(int)@$photo_id.",".(int)@$address_id.",".(int)@$data['user_wp'].")");
	    if($result > 0){
	   	 $MYSQL->query("INSERT INTO $tbusers_deystvie (data_add,user_wp,deystvie,id_deystvie) VALUES (now(),".(int)$_SESSION['WP_USER']['user_wp'].",7,$result)");
	     $objResponse->script("AddComment($id,'$msg',".$USER->CountComments($par).")");
	    }
	  }
	}	
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'AddComment');


function ShowAllComments($data){
	global $USER;
	
	$return = "";
	
	if(is_array($data)){
	   if(isset($data['shop_id']) && $data['shop_id'] > 0){
	      $par = array('shop_id' => $data['shop_id']);
	      $id  = $data['shop_id'];
	   }
	    elseif(isset($data['akcia_id']) && $data['akcia_id'] > 0){
	      $par = array('akcia_id' => $data['akcia_id']);
	      $id  = $data['akcia_id'];
	    }
	    elseif(isset($data['photo_id']) && $data['photo_id'] > 0){
	      $par = array('photo_id' => $data['photo_id']);
	      $id  = $data['photo_id'];
	    }
	    elseif(isset($data['adress_id']) && $data['adress_id'] > 0 and isset($data['user_wp']) && $data['user_wp'] > 0){
	      $par = array('adress_id' => $data['adress_id'], 'user_wp' => $data['user_wp']);
	      $id  = $data['adress_id'];
	    }
	    
	$comment = $USER->ShowComments(0,$par);
	if(is_array($comment)){
	 for($i=0; $i < count($comment); $i++){
		 $arr_users[] = $comment[$i]['user_wp'];
	 }
	 $avatar = ShowAvatar($arr_users,50,50);
	 for($i=0; $i < count($comment); $i++){
	  $return .= "
	                <div class=\"commentlist-item\">
				     <div class=\"commentlist-item-top\"></div>
				     <div class=\"commentlist-item-middle\">
					   <a href=\"/".$comment[$i]['user_wp']."\"><img src=\"".$avatar[$i]['avatar']."\" class=\"photo-small bordered\" width=\"50\" height=\"50\" /></a>
					   <div class=\"commentlist-item-text\">
						<div class=\"commentlist-item-user\"><strong><a href=\"/".$comment[$i]['user_wp']."\">".trim($comment[$i]['firstname']." ".$comment[$i]['lastname'])."</a></strong><br><em>".MyDataTime($comment[$i]['data'],'datetime')."</em></div>
						<div>".htmlspecialchars(stripslashes(trim($comment[$i]['msg'])))."</div>
					   </div>
				     </div>
				     <div class=\"commentlist-item-bottom\"></div>
			        </div>
	  ";
	 }
	}
	    
	}
	$objResponse = new xajaxResponse();
	$objResponse->assign($id.'_comments', 'innerHTML', $return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowAllComments');
?>
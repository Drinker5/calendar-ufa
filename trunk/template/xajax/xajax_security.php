<?php
function ShowSelectSecurity($type_data,$id=0){
	global $MYSQL, $USER;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$tbusers    = "pfx_users";
	$tbsecurity = "pfx_security";
	$tbalbum    = "pfx_users_photos_album";
	$return     = "";
	$script1    = "";
	$script2    = "";
	$class      = "";
	
	$id = (int) $id;
	
	$objResponse = new xajaxResponse();
	
	$security = $MYSQL->query("SELECT id, name FROM $tbsecurity WHERE active=1 ORDER BY orderby");
	if(is_array($security)){
		
		switch($type_data){
			case 'photoalbum':
				$pravo  = $MYSQL->query("SELECT IFNULL(pravo,'1') pravo FROM $tbalbum WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND id = $id");
				$script1 = '$(\'#btnext1\').css(\'display\',\'none\');';
				$script2 = '$(\'#btnext1\').css(\'display\',\'block\');';				
			break;
			
			default:
				$pravo  = $MYSQL->query("SELECT IFNULL(pravo,'1') pravo FROM $tbusers WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);
				$class = 'class="privacy-select"';
			break;
		}
		
		if(is_array($pravo)){
			if($pravo[0]['pravo'] == '1'){ // Все
				$pravo = array('security' => 1, 'yes' => '', 'no' => '',);
			} else {
				$pravo = unserialize($pravo[0]['pravo']);
			}
		} else $pravo = array('security' => 1, 'yes' => '', 'no' => '',);
		
		$return = '<select '.$class.' style="width:285px;" id="user_security" onChange="if(this.value==5){xajax_ShowSecurity(\''.$type_data.'\','.$id.'); '.$script1.'} else {$(\'#f_block\').css(\'display\',\'none\'); '.$script2.'}">';
		
		foreach($security as $key=>$value){
			if($pravo['security'] == $value['id'])
			   $return .= "<option value=\"".$value['id']."\" selected>".$value['name'];
			else
			   $return .= "<option value=\"".$value['id']."\">".$value['name'];
		}
		$return .= "</select>";
		
		if($pravo['security'] == 5)
		 $objResponse->script("xajax_ShowSecurity('".$type_data."',$id)");
	}
	
	$objResponse->assign('idSelectSecurity','innerHTML',$return);
	if($type_data == 'profile')
	 $objResponse->script("$(\".privacy-selected\").html($('.privacy-select :selected').html());");
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowSelectSecurity');



function ShowSecurity($type_data,$id){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$tbusers    = "pfx_users";
	$tbsecurity = "pfx_security";
	$tbalbum    = "pfx_users_photos_album";
	$arr_yes    = "";
	$arr_no     = "";
	
	switch($type_data){
	    case 'photoalbum':
			$pravo  = $MYSQL->query("SELECT IFNULL(pravo,'1') pravo FROM $tbalbum WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND id = $id");
		break;
			
		default:
			$pravo  = $MYSQL->query("SELECT IFNULL(pravo,'1') pravo FROM $tbusers WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);
		break;
	}
		
	if(is_array($pravo)){
		if($pravo[0]['pravo'] == '1'){ // Все
			$pravo = array('security' => 1, 'yes' => '', 'no' => '',);
		} else {
			$pravo = unserialize($pravo[0]['pravo']);
		}
	} else $pravo = array('security' => 1, 'yes' => '', 'no' => '',);
		
	
	
	for($i=0; $i < count($pravo['yes']); $i++){
		$user = $MYSQL->query("SELECT lastname, firstname FROM $tbusers WHERE user_wp = ".(int)@$pravo['yes'][$i]);
		if(is_array($user) && count($user) == 1)
		@$arr_yes .= "arrYes[$i] = {'id':".$pravo['yes'][$i].",'name':'".trim($user[0]['lastname']." ".$user[0]['firstname'])."'};".PHP_EOL;
	}
	
	for($i=0; $i < count($pravo['no']); $i++){
		$user = $MYSQL->query("SELECT lastname, firstname FROM $tbusers WHERE user_wp = ".(int)@$pravo['no'][$i]);
		if(is_array($user) && count($user) == 1)
		@$arr_no .= "arrNo[$i] = {'id':".$pravo['no'][$i].",'name':'".trim($user[0]['lastname']." ".$user[0]['firstname'])."'};".PHP_EOL;
	}

	$script = "
	var arrYes = new Array();
	var arrNo  = new Array();
			$arr_yes
			$arr_no
	
	$(document).ready(function() {
				$(\"#id_yes\").tokenInput(\"/jquery-searchuser.php?p=1\",
					 arrYes,
					 {
						classes: {
								tokenList: \"token-input-list-facebook\",
								token: \"token-input-token-facebook\",
								tokenDelete: \"token-input-delete-token-facebook\",
								selectedToken: \"token-input-selected-token-facebook\",
								highlightedToken: \"token-input-highlighted-token-facebook\",
								dropdown: \"token-input-dropdown-facebook\",
								dropdownItem: \"token-input-dropdown-item-facebook\",
								dropdownItem2: \"token-input-dropdown-item2-facebook\",
								selectedDropdownItem: \"token-input-selected-dropdown-item-facebook\",
								inputToken: \"token-input-input-token-facebook\"
						}
				});
				
				$(\"#id_no\").tokenInput(\"/jquery-searchuser.php?p=1\",
					 arrNo,
					 {
						classes: {
								tokenList: \"token-input-list-facebook\",
								token: \"token-input-token-facebook\",
								tokenDelete: \"token-input-delete-token-facebook\",
								selectedToken: \"token-input-selected-token-facebook\",
								highlightedToken: \"token-input-highlighted-token-facebook\",
								dropdown: \"token-input-dropdown-facebook\",
								dropdownItem: \"token-input-dropdown-item-facebook\",
								dropdownItem2: \"token-input-dropdown-item2-facebook\",
								selectedDropdownItem: \"token-input-selected-dropdown-item-facebook\",
								inputToken: \"token-input-input-token-facebook\"
						}
				});
		});
	";
	
	$return = "
		<table style=\"border-collapse:separate; border-spacing: 5px; width:98%;\">
				 <tr><td><img src=\"pic/ok.png\"> Могут видеть</td></tr>
				 <tr><td><input type=\"text\" id=\"id_yes\"></td></tr>
				 <tr><td><hr /></td></tr>
				 <tr><td><img src=\"pic/no.png\"> Не могут видеть</td></tr>
				 <tr><td><input type=\"text\" id=\"id_no\"></td></tr>
				</table>
	";

	$objResponse = new xajaxResponse();
	$objResponse->script("$('#f_block').css('display','block')");
	$objResponse->assign('idSecurity','innerHTML',$return);
	$objResponse->script($script);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowSecurity');
?>
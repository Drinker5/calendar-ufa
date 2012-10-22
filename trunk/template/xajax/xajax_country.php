<?php
function ShowSelectTowns($country_id){
	global $COUNTRY;
	
	$sel_towns = "<span>__________</span> <img src=\"pic/arrow.gif\" width=\"7\" height=\"5\" />";
	$return = "<select style=\"cursor:pointer\" id=\"town_id\" onChange=\"location.href='/?town_id='+this.value; $('#city').html('".loading_small."'); \"><option value=\"-1\">----------";
	$arrCountry = $COUNTRY->ShowTree($country_id);
	foreach($arrCountry as $key=>$value){
		  if($_SESSION['TOWN_ID'] == $value['id']){
		  	 $sel_towns = "<span>".$value['name']."</span> <img src=\"pic/arrow.gif\" width=\"7\" height=\"5\" />";
		     $return .= "<option value=\"".$value['id']."\" selected=\"selected\">".$value['name'];
		  }
		  else 
		     $return .= "<option value=\"".$value['id']."\">".$value['name'];
	}
	$return = $sel_towns.$return."</select>";
	
	$objResponse = new xajaxResponse();
	$objResponse->assign('city', 'innerHTML', $return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowSelectTowns');
?>
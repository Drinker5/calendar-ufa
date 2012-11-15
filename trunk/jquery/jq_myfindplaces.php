<?php
require_once(path_modules.'ini.shop.php');
global $SHOP;
switch(@$_POST['action']){
	case'search':
	$query = $_POST['search_query'];
	$sort_list = array(0=>'name', 1=>'distance', 2=>'popular');
	$sort_param = $sort_list[(int)$_POST['sort']];
	$category = varr_int($_POST['category']);
	$flags = array('flag-ihere'=>(int)$_POST['flag-ihere'],'flag-friend-here'=>(int)$_POST['flag-friend-here'],'flag-act'=>(int)$_POST['flag-act']);
	$shops = $SHOP -> Search($query,$sort_param,$category,$flags);
	$result = array('success'=>'','shops'=>'');
	if (is_array($shops))
	{
		$result['success'] = 1;
		$result['shops'] = $shops;
	}
	else
		$result['success'] = 0;
	echo json_encode($result);
	break;
	case'favorite':
	$result = array('success'=> $SHOP -> AddToFavorite($_POST['adress_id']) );
	echo json_encode($result);
	break;
}
?>
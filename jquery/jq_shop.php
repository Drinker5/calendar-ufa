<?php
switch(@$_POST['type']){
	case'add':
		if(isset($_POST['id']) && $_POST['id'] > 0){
			$SHOP->AddToFav(varr_int($_POST['id']));
			echo json_encode(array('status'=>'success'));
		}
	break;
	case'delete':
		if(isset($_POST['id']) && $_POST['id'] > 0){
			$SHOP->DeleteFromFav(varr_int($_POST['id']));
 			echo json_encode(array('status'=>'success'));
 		}
	break;
}
?>
<?php
$akcia_id = varr_int(@$_POST['akcia_id']);
$want = varr_int(@$_POST['want']);
if($akcia_id > 0 && $want == 0){
	$MYSQL->query("INSERT INTO pfx_podpiska_ban (user_wp,akcia_id) VALUES (".varr_int($_SESSION['WP_USER']['user_wp']).",".$akcia_id.")");
}
elseif($akcia_id > 0 && $want == 1){
	$USER->DeleteHochu($akcia_id);
}
?>
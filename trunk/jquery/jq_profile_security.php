<?php
if(!is_array(@$_POST['circles'])){
	echo "<font style=\"color:red\">Ошибка передачи параметров</font>";
	exit();
}

$GLOBALS['PHP_FILE'] = __FILE__;
$GLOBALS['FUNCTION'] = __FUNCTION__;

foreach($_POST['circles'] as $key=>$value){
	$pravo[] = array('krug_id'=>varr_int($value));
}

if(isset($pravo) && is_array($pravo)){
	$pravo = serialize($pravo);
	$MYSQL->query("UPDATE pfx_users SET pravo='$pravo' WHERE user_wp=".varr_int($_SESSION['WP_USER']['user_wp']));
	echo "<font style=\"color:green\">Настройки конфиденциальности сохранены</font>";
}
?>
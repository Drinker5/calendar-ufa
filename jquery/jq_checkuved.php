<?php
	if($_REQUEST['id']=='all')$MYSQL->query("UPDATE `pfx_uvedomlenie` SET `view`=1 WHERE `user_wp`=".(int)$_SESSION["WP_USER"]["user_wp"]);
	else                      $MYSQL->query("UPDATE `pfx_uvedomlenie` SET `view`=1 WHERE `id`='".str_replace("u","",$_REQUEST["id"])."'");
	echo str_replace('u','',$_REQUEST['id']);
?>
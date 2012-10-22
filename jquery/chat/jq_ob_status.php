<?php
	if(isset($_REQUEST['type'])){
		if($_REQUEST['type']=='switch'){
			if($_REQUEST['status']=='icon-online')     $_REQUEST['status']=1;
			elseif($_REQUEST['status']=='icon-eezzy')  $_REQUEST['status']=2;
			elseif($_REQUEST['status']=='icon-disturb')$_REQUEST['status']=3;
			else                                       $_REQUEST['status']=0;

			$_SESSION['WP_USER']['status_chat']=$_REQUEST['status'];
			$MYSQL->query("UPDATE `pfx_users` SET `status_chat`=".$_REQUEST['status']." WHERE `user_wp`=".(int)$_SESSION['WP_USER']['user_wp']);
			echo $_REQUEST['status'];
		}
	}
	//else echo '123';
?>
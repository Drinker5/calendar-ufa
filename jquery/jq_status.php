<?php
	$id=$USER->UpdateUserStatus(@$_POST['status']);
	return $id>0?true:false;
?>
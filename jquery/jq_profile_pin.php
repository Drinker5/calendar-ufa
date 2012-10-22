<?php
if(isset($_POST['pin']) && strlen($_POST['pin']) == 4 && isset($_POST['passw'])){
	$result = $PAYMENT->ChangePIN($_SESSION['WP_USER']['user_wp'],$_POST['passw'],$_POST['pin']);
	if(is_array($result) && $result['Error']['ErrorId'] === '0'){
		echo "<p style=\"color:green\">ПИН КОД изменен</p>"; exit();
		exit();
	} else {
		echo "<p style=\"color:red\">Ошибка!<br />".@$result['Error']['ErrorDesc']."</p>";
		exit();
	}
}
?>
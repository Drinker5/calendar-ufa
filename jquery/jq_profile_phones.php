<?php
$phones = json_decode(stripcslashes($_POST['phones']),true);
$new_phones = array();
$change_phones = array();
$main = "";
foreach ($phones as $key => $value) {
	if ($value['oldphone']=='new')
		$new_phones[] = $value; 
	elseif ($value['oldphone'] != $value['phone'] )
		$change_phones[] = $value;
	if ((int)$value['main'] != -1 && strlen($value['phone']>0))
		$main = $value['phone'];
}

foreach ($new_phones as $key => $value)
	$USER->AddMobile($value['phone']);

foreach ($change_phones as $key => $value)
	$USER->ChangeMobile($value['oldphone'],$value['phone']);

$USER->UpdateMobileMain($main);
echo "<font style=\"color:green\">Ваши телефоны успешно сохранены</font>";
?>
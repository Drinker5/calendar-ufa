<?php
if($varr['sname'] != '' && $varr['fname'] != '' && $varr['town_id'] > 0){
  if(is_email($varr['email'])){
  	if(strlen($varr['year']) > 0 && strlen($varr['month']) > 0 && strlen($varr['day']) > 0){
	   if(checkdate(varr_int($varr['month']),varr_int($varr['day']),varr_int($varr['year']))){}
	   else { echo "Дата рождения указана не верно</font>"; }
  	}
  	switch($USER->UpdateProfile($varr)){
		case  1: 
            echo "<font style=\"color:green\">Ваш профиль успешно изменен</font>";
            foreach ($_POST['circles'] as $key => $value) {
                $pravo[] = array('krug_id'=>varr_int($value));
            }
            $pravo = serialize($pravo);
            $MYSQL->query("UPDATE pfx_users SET pravo='$pravo' WHERE user_wp=".varr_int($_SESSION['WP_USER']['user_wp']));
            break;
	  	case -1: echo "<font style=\"color:red\">Такой Email уже зарегистрирован, укажите другой!</font>"; break;
	  	default: echo "<font style=\"color:red\">Ошибка сохранения данных</font>"; break;
	}
  } else {
  	echo "<font style=\"color:red\">Указанный Вами email адрес неверный</font>";
  }	
} else {
	echo "<font style=\"color:red\">Заполните все поля помеченные звездочкой (*)</font>";
}
?>
<?php
if(isset($_POST['email']) && is_email($_POST['email'])){
    $result = $USER->SendNewPassw(trim($_POST['email']));
    if($result == 1){
       echo "<font style=\"color:green\">Ваш новый пароль отправлен Вам на ".$_POST['email']."</font>";
    }
    else
     echo "Ошибка отправки нового пароля, попробуйте повторить позднее";
}
else
 echo "Email указан не верно";

?>
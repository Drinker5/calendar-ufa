<?php
switch(@$_POST['type']){
	case 'forgot':
		if(isset($_POST['email']) && is_email($_POST['email'])){
			$result = $USER->SendNewPassw(trim($_POST['email']));
	        if($result == 1){
	           echo "<font style=\"color:green\">Ваш новый пароль отправлен Вам на ".$_POST['email']."</font>";
	        } else echo "Ошибка отправки нового пароля";
		} else echo "Email указан не верно";
	break;
	default:
		echo "
		  <div id=\"forgot-form\">
	        <p><strong>Восстановление пароля</strong></p>
	        <p id=\"idErrorMsg\" style=\"color:red\"></p>
	        <p><input type=\"text\" id=\"id-forgot-email\" value=\"".@$_COOKIE['user_email']."\" placeholder=\"Email\" class=\"brdrd\" /></p>
	        <span class=\"clr-but clr-but-blue\"><center>".loading_clock."</center><div id=\"btnEnter\"><sub></sub><a href=\"#\" id=\"forgot-form-but\" onClick=\"return false;\">Получить новый пароль</a><sup></sup></div></span>
           </div>
         <script>
           $('#loading').css('display','none');
           $('#forgot-form-but').click(function(){
              $('#btnEnter').css('display','none'); $('#loading').css('display','block');
              $.ajax({
	            url:'/jquery-forgot',
	            cache:false, type:'POST',
	            data: {type:'forgot',email:$('#id-forgot-email').val()},
	            success:function(result){	   	          
	   	            $('#idErrorMsg').html(result);
	   	            $('#loading').css('display','none');
	   	            $('#btnEnter').css('display','block');
	            }
	          });
           });
         </script>
		";
	break;
}
?>
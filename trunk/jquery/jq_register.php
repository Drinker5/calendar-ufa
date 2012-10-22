<?php
  switch(@$_POST['type']){
  	case 'register':
  		if(isset($_POST['email']) && is_email($_POST['email'])){
  			switch($USER->Add(trim($_POST['email']),trim($_POST['password']))){
		      case  0: echo 'ok'; break;
		      case -1: echo 'Не корректный email'; break;
		      case -2: echo 'Ошибка выполнения скрипта'; break;
		      case -3: echo 'Регистрация прошла успешно, но отправить письмо с активацией не удалось'; break;
		      case -4: echo 'Такой Email уже зарегистрирован';
	        }
  		} else echo "Email указан не верно";
  	break;
  	default:
  		echo "
  		 <div id=\"reg-form\">
	       <p><strong>".LANG_REGISTER_FOR_USER."</strong></p>
	       <p id=\"idErrorMsg\" style=\"color:red\"></p>
	       <p><input type=\"text\" id=\"user-reg-mail\" placeholder=\"Email\" class=\"brdrd\" /></p>
	       <p><input type=\"password\" id=\"user-reg-password\" placeholder=\"Пароль\" class=\"brdrd\" /></p>
	       <p><input type=\"checkbox\" checked=\"checked\" class=\"checkbox\" safari=1 id=\"user-accordance\" disabled /> С правилами <b>".sys_copy."</b> согласен </p>
	       <span class=\"clr-but clr-but-blue\"><center>".loading_clock."</center><div id=\"btnEnter\"><sub></sub><a href=\"#\" id=\"reg-form-but\" onClick=\"return false;\">".LANG_BTN_REGISTER_2."</a><sup></sup></div></span>
         </div>
         <script>
           $('#loading').css('display','none');
           $('#reg-form-but').click(function(){
              $('#btnEnter').css('display','none'); $('#loading').css('display','block');
              $.ajax({
	            url:'/jquery-register',
	            cache:false, type:'POST',
	            data: {type:'register',email:$('#user-reg-mail').val(),password:$('#user-reg-password').val()},
	            success:function(result){
	   	          if(result == 'ok'){
	   	            $('#idErrorMsg').html('<font style=\"color:green\">Регистрация прошла успешно! Вам выслано письмо с активацией.</font>');
	   	            $('#loading').css('display','none');
	   	          } else {
	   	            $('#idErrorMsg').html(result);
	   	            $('#loading').css('display','none');
	   	            $('#btnEnter').css('display','block');
	   	          }
	            }
	          });
           });
         </script>
  		";
  	break;
  }
?>
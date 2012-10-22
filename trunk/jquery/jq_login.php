<?php
 switch(@$_POST['type']){
 	case 'login':
 		if(isset($_POST['email']) && isset($_POST['passw'])){
 			$result = $USER->AuthUser($_POST['email'],$_POST['passw']);
	        if($result === true) echo 'ok'; else echo $result;
 		}
 	break;

 	default:
 		echo "
 		 <div id=\"login-form\">
	       <p><strong>".LANG_USER_IN."</strong></p>
	       <p id=\"idErrorMsg\" style=\"color:red\"></p>
	       <p><input type=\"text\" id=\"id-user-email\" value=\"".@$_COOKIE['user_email']."\" placeholder=\"Email\" class=\"brdrd\" /></p>
	       <p><input type=\"password\" id=\"id-user-passw\" placeholder=\"".LANG_PASSWORD."\" class=\"brdrd\" /></p>
	       <p><a href=\"#\" id=\"forgot-but\">".LANG_I_NOT_PASSWORD."</a></p>
	       <span class=\"clr-but clr-but-blue\"><center>".loading_clock."</center><div id=\"btnEnter\"><sub></sub><a href=\"#\" id=\"login-form-but\" onClick=\"return false;\" style=\"color:#000 !important;\">".LANG_BTN_ENTER."</a><sup></sup></div></span>
         </div>
         <script>
           $('#loading').css('display','none');
           $('#login-form-but').click(function(){
              $('#btnEnter').css('display','none'); $('#loading').css('display','block');
              $.ajax({
	            url:'/jquery-login',
	            cache:false, type:'POST',
	            data: {type:'login',email:$('#id-user-email').val(),passw:$('#id-user-passw').val()},
	            success:function(result){
	   	          if(result == 'ok'){
	   	           location.href='/';
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
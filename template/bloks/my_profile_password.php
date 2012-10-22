<div class="title margin">
	<h2>Изменить пароль</h2>
</div>
<div class="nav-panel group">
    <ul class="fl_r right">
        <li class="opacity_link"><a href="/my-profile">Мой профиль</a></li>
        <li class="opacity_link"><a href="/my-phones">Телефон</a></li>
        <!--<li class="opacity_link"><a href="/my-wallets">Счет</a></li>-->
        <li class="opacity_link"><a class="active" href="/my-alerts">Оповещения</a></li>
        <li class="opacity_link"><a href="/my-avatar">Изменить аватар</a></li>
        <li class="opacity_link"><a href="/my-password">Изменить пароль</a></li>
        <!--<li class="opacity_link"><a href="/my-subscribes">Подписки</a></li>-->
    </ul>
</div>
<div class="tools_block">
    <p id='msg' style='color:red;'></p>
    <form method="POST">
        <div class="tools pass-tools">
        	<p id='msg'>
        	<label for="old-password">Старый пароль*</label>
            <input id="old-password" type="password">
            <label for="new-password">Новый пароль*</label>
            <input id="new-password" type="password">
            <label for="new-password-repeat">Новый пароль еще раз*</label>
            <input id="new-password-repeat" type="password">
        </div>
        <button  class="btn btn-green fl_r" onClick="ChangePassword(); return false;">Изменить</button>
    </form>
</div>

<script type="text/javascript">
	function ChangePassword(){
		var password1=$('#old-password').val();
		var password2=$('#new-password').val();
		var password3=$('#new-password-repeat').val();

		if(password1!='' && password2!='' && password3!=''){
			if(password2==password3){
				$.ajax({
					url    :'/jquery-password',
					cache  :false,
					type   :'POST',
					data   :{'password1':password1, 'password2':password2, 'password3':password3},
					success:
						function(data){
							location.href='/<?=$_SESSION['WP_USER']['user_wp']?>';
							//$('#msg').html(data);
						},
					error  :function(){alert('Не удалось изменить пароль!')}
				});
			}
			else{
				$('#msg').html('Новый пароль в обоих полях должен быть одинаковым!');
			}
		}
		else{
			$('#msg').html('Все поля должны быть заполнены!');
		}
	}
</script>
<!--<div id="center-profile">
	<h1>Бонусный счет</h1>
	<p class="long-hr" style="padding:0; height:1px;"></p>
	<h2>Изменение ПИН КОДА</h2>
	<div id="result">
	  <p>Введите новый ПИН КОД <small>макс 4 символа</small></p>
	  <p><div class="pin-cntnr"><input type="text" value="" id="newpin" class="bonus-pin" maxlength="4" /></div></p><br />
	  <p>Введите свой пароль для входа на сайт</small></p>
	  <p><div class="pin-cntnr"><input type="password" value="" id="passw" style="height:25px"/></div> <span class="clr-but clr-but-green-big"><sub></sub><a href="#" id="change_pin">Изменить ПИН КОД</a><sup></sup></span></p>
	</div>
	<div class="clear"></div>
	<p class="long-hr"></p>
	<table class="bonus-table">
<?php
		$result=$PAYMENT->Balance($_SESSION['WP_USER']['user_wp'],0);
		if(is_array($result)){
			$count=count($result);
			echo '<tr><th colspan="2">Страна</th><th rowspan="'.($count+5).'" class="vhr"></th><th>Бонусный счет</th><th rowspan="'.($count+5).'" class="vhr"></th><th></th></tr>';
			foreach($result['Balance'] as $key=>$value){
				$country =$MYSQL->query("SELECT `name`, `bonus_icon` FROM `pfx_country` WHERE `id`=".(int)$key);
				$currency=$MYSQL->query("SELECT `mask` FROM `pfx_currency` WHERE `id`=".(int)$value['Currency']);
?>
				<tr>
					<td><img src="pic/<?=@$country[0]['bonus_icon']?>" width="17" height="17" /></td>
					<td><?=@$country[0]['name']?></td>
					<td><?=($value['Amount']/100)?> <?=@$currency[0]['mask']?></td>
					<td><span class="clr-but clr-but-green-big"><sub></sub><a href="#" onClick="WindowPayBalance(<?=$_SESSION['WP_USER']['user_wp']?>, <?=$value['Currency']?>);">Пополнить</a><sup></sup></span></td>
				</tr>
<?php
			}
		}
?>
	</table>
</div>-->

<div class="title margin">
    <h2>Настройки</h2>
</div>
<div class="nav-panel group">
    <ul class="fl_r right">
        <li class="opacity_link"><a href="/my-profile">Мой профиль</a></li>
        <li class="opacity_link"><a href="/my-phones">Телефон</a></li>
        <li class="opacity_link"><a class="active" href="/my-wallets">Счет</a></li>
        <li class="opacity_link"><a href="/my-alerts">Оповещения</a></li>
        <li class="opacity_link"><a  href="/my-avatar">Изменить аватар</a></li>
        <li class="opacity_link"><a href="/my-subscribes">Подписки</a></li>
    </ul>
</div>
<div class="tools_block">                    
    <div class="tools">
        <div id="schet_tools">
            <form method="POST" action="/">
                <div class="left_sidebar fl_l">
                        <h1>Изменение ПИН КОДА:</h1>
                        <label>Введите новый ПИН КОД<br /><span>(макс 4 символа)</span></label>
                        <input name="pin" id="pin" type="text"/>    
                        <button type="submit" class="btn btn-green" id="save_buttonus">Изменить</button>
                </div>
            </form>
            <div class="right_sidebar fl_l">
                <!-- <form method="POST" action="/"> -->
                <table>
                    <thead>
                        <td width="200px">Страна</td>
                        <td width="400px">Бонусный счет</td>
                        <td width="100px"></td>
                    </thead>
                    <tbody>
                        <?php
                            $result=$PAYMENT->Balance($_SESSION['WP_USER']['user_wp'],0);
                            if(is_array($result)){
                                    $count=count($result);
                                    echo '<tr><th colspan="2">Страна</th><th rowspan="'.($count+5).'" class="vhr"></th><th>Бонусный счет</th><th rowspan="'.($count+5).'" class="vhr"></th><th></th></tr>';
                                    foreach($result['Balance'] as $key=>$value){
                                            $country =$MYSQL->query("SELECT `name`, `bonus_icon` FROM `pfx_country` WHERE `id`=".(int)$key);
                                            $currency=$MYSQL->query("SELECT `mask` FROM `pfx_currency` WHERE `id`=".(int)$value['Currency']);
                                        ?>
<!--                                            <tr>
                                                    <td><img src="pic/<?=@$country[0]['bonus_icon']?>" width="17" height="17" /></td>
                                                    <td><?=@$country[0]['name']?></td>
                                                    <td><?=($value['Amount']/100)?> <?=@$currency[0]['mask']?></td>
                                                    <td><span class="clr-but clr-but-green-big"><sub></sub><a href="#" onClick="WindowPayBalance(<?=$_SESSION['WP_USER']['user_wp']?>, <?=$value['Currency']?>);">Пополнить</a><sup></sup></span></td>
                                            </tr>-->
                                            <tr>
                                                <td><?=@$country[0]['name']?></td>
                                                <td><?=($value['Amount']/100)?> <?=@$currency[0]['mask']?></td>
                                                <td><div class="plus_tools"><div class="small-icon icon-add"></div><strong>Пополнить</strong></div></td>
                                                <td>
                                                    <a href="#" class="delete_tools opacity_link">
                                                        <i class="small-icon icon-delete"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                    <?php
                                    }
                            }
                            ?>
                        
<!--                        <tr>
                            <td>Россия</td>
                            <td>10 руб</td>
                            <td><div class="plus_tools"><div class="small-icon icon-add"></div><strong>Пополнить</strong></div></td>
                            <td>
                                <a href="#" class="delete_tools opacity_link">
                                    <i class="small-icon icon-delete"></i>
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>Китай</td>
                            <td>10000 руб</td>
                            <td><div class="plus_tools"><div class="small-icon icon-add"></div><strong>Пополнить</strong></div></td>
                            <td>
                                <a href="#" class="delete_tools opacity_link">
                                    <i class="small-icon icon-delete"></i>
                                </a>
                            </td>
                        </tr>-->
                    </tbody>
                </table>
                <div class="bill-add-block tx_r" style="margin-top: 25px;">
                    <a href="#" class="opacity_link" style="line-height: 15px;text-decoration: none; font-size: 11px;color:#5b6880;"><i class="small-icon icon-add"></i><strong>Добавить счет</strong></a>
                </div>
            </div>
            <div class="cleared"></div>
            <div class="tx_r">
                <button type="submit" class="btn btn-green" id="save_buttonus">Сохранить</button>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('#change_pin').live('click', function(){
			if($('#newpin').val().length == 4){
				var pin  =$('#newpin').val();
				var passw=$('#passw').val();
				$('#result').html('<div id="loading" style="padding-top:30px; text-align: center;"><img src="pic/loader_clock.gif"></div>');
				$.ajax({
					url:'/jquery-changepin',
					cache:false, type:'POST',
					data: {pin:pin,passw:passw},
					success:function(data){
						$('#loading').remove();
						$('#result').html(data);
					}
				});
			}
		});
	});
</script>
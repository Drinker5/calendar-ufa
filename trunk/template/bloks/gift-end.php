<?php
	if(isset($_SESSION['may_bring'])){
		$infoik=$USER->Info_min($_URLP[2]);
		//echo $infoik['mobile'];
		
	if(is_mobile($infoik['mobile'])){
		require_once(path_modules."ini.sms.php");
		$akcia = $AKCIA->Info_min($_URLP[1],0,0);
		    			    	$sms_code_gift = "";
		    			    	//foreach($users_wp[$i]['gift_code'] as $key=>$value)
		    			    	   //$sms_code_gift .= $value."\n";
		    			    	if($_SESSION['WP_USER']['sex'] == 1) $end = ""; else $end = "а";
		    			    	$sms_msg = trim($_SESSION['WP_USER']['firstname'] ." ".$_SESSION['WP_USER']['lastname'])." сделал$end Вам подарок в ".$akcia['shop_name']."  ".$akcia['country']."\nКод подарка: 123456\nКод действителен до ".MyDataTime(date("d.m.y"),'date2','+',day_podarok)."\nТел.:".$akcia['phone'];
		    			        $SMS->SendSMS($infoik['mobile'],$sms_msg);
		    			    }
?>
	
	
		<div id="content" class="fl_r">
            	<div class="center">
	                <span class="payments-gifts"></span>
	                <p class="hint px28">Поздравляем, оплата подарка прошла успешно!</p>
	                <p class="hint px28">Получателю отправлено SMS-оповещение о коде подарка.</p>
	                <p><a href="/type=5">Сделать ещё подарок</a></p>
	            </div>
               
            </div> <!-- закрываем #content -->
            
            <script>
            	$.ajax({
            		type: "POST",
            		url: "/jquery-paygift",
            		data:{gift_id:'<?=$_URLP[1]?>', users:'<?=$_URLP[2]?>', msg: '123', pin:'0000', privat:0},
            		cache: false,
            		success: function(data){
            			console.log(data);
            		}
            	});
            </script>
<?php
		unset($_SESSION['may_bring']);
	}
?>
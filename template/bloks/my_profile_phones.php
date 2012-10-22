<!--<div id="center-profile">
 <h1>Добавить новый номер телефона</h1>
 <table class="cells3-small" id="add_mobile">
   <tr><td>Код страны</td><td>Телефон</td><td></td></tr>
   <tr>
	<td>
	 <select id="mobile_code">
	  <?php
	   $GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
	   $result = $MYSQL->query("SELECT `mobcode` FROM pfx_country WHERE `parent`=0 ORDER BY `sort`");
	   if(is_array($result)){
	   	foreach($result as $key=>$value)
		  echo "<option value=\"".$value['mobcode']."\">".$value['mobcode']."</option>";
	   }
	  ?>
	 </select>
	</td>
	<td><input type="text" id="mobile_new" class="brdrd" style="width:300px;" title="Добавить номер телефона" /></td>
	<td><span class="clr-but clr-but-green"><sub></sub><a href="#" id="addmobile" onClick="return false;">Добавить</a><sup></sup></span></td>
   </tr>
 </table> 
 <p class="long-hr"></p><br />
 <h1>Телефоны</h1>
 <form>
<?php
 $mobiles = $MYSQL->query("SELECT mobile, main FROM pfx_users_mobile WHERE user_wp=".(int)$_SESSION['WP_USER']['user_wp']);
 if(is_array($mobiles)){
	for($i=0; $i < count($mobiles); $i++){
		if(is_mobile($mobiles[$i]['mobile'])){
		 echo "<table class=\"cells3-small\">
		        <tr><td>Телефон</td><td></td></tr>
		        <tr>
			     <td><input type=\"text\" value=\"".$mobiles[$i]['mobile']."\" id=\"mobiles\" class=\"brdrd\" style=\"width:385px; cursor:pointer;\" title=\"Удалить номер\" readonly /></td>
			     <td><input type=\"radio\" name=\"user_mobile_main\" value=\"".$mobiles[$i]['mobile']."\" class=\"checkbox\" safari=1 ".str_replace('0','',str_replace('1','checked',$mobiles[$i]['main']))."/> основной</td>
		        </tr>
	           </table>";
		}
	}
 } 
?>
 </form>
 <p class="long-hr"></p>
 <div class="clear"></div>
</div>-->
<?php 
$codes=array();
    $GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
    $result = $MYSQL->query("SELECT `mobcode` FROM pfx_country WHERE `parent`=0 ORDER BY `sort`");
    if(is_array($result))
        {
            foreach($result as $key=>$value)
                $codes[]=$value['mobcode'];
        }
function drow_selecter($value,$codes)
{
    $select='<select>';
    foreach($codes as $k=>$vv)
        if($vv!=$value)
            $select.="<option value=\"".$vv."\">".$vv."</option>";
        else 
            $select.="<option value=\"".$vv."\" selected=\"selected\">".$vv."</option>";
    $select.='</select>';
    return $select;

}
function phone_with_code($phone,$codes)
{
    foreach($codes as $k=>$vv)
        {$v=substr($vv,1);
            if(substr($phone, 0,strlen($v.''))==$v.'')return array($v,substr($phone,strlen($v.'')));
        }
}
?>

<div class="title margin">
	<h2>Мои телефоны</h2>
</div>
<div class="nav-panel group">
    <ul class="fl_r right">
        <li class="opacity_link"><a href="/my-profile">Мой профиль</a></li>
        <li class="opacity_link"><a class="active" href="/my-phones">Телефон</a></li>
        <!--<li class="opacity_link"><a href="/my-wallets">Счет</a></li>-->
        <li class="opacity_link"><a href="/my-alerts">Оповещения</a></li>
        <li class="opacity_link"><a href="/my-avatar">Изменить аватар</a></li>
        <li class="opacity_link"><a href="/my-password">Изменить пароль</a></li>
        <!--<li class="opacity_link"><a href="/my-subscribes">Подписки</a></li>-->
    </ul>
</div>
                    <form method="POST" action="/">
                        <div class="tools">
                            <p id='msg'></p>
                                <dl class="tel_tools">
                                    <?php
 $mobiles = $MYSQL->query("SELECT mobile, main FROM pfx_users_mobile WHERE user_wp=".(int)$_SESSION['WP_USER']['user_wp']);
 if(is_array($mobiles)){
	for($i=0; $i < count($mobiles); $i++){
		if(is_mobile($mobiles[$i]['mobile'])){
                    $phone=phone_with_code($mobiles[$i]['mobile'],$codes);
                    ?>
                    <dt class="phones">
                        <div class="grid20">
                            <label>Код страны*</label>
                            <?=drow_selecter($phone['0'],$codes)?>
                        </div> 
                        <div class="grid50">
                            <label>Телефон*</label>
                            <input name="first_name_user" class="input_block phone" id="first_name_user" type="text" value="<?=$phone['1']?>" style="height: auto;"/> 
                            <input name='oldcode' type='hidden' value="<?=$phone['0']?>" />
                            <input name='oldphone' type='hidden' value="<?=$phone['1']?>" />
                            <br /><error id="er1">Введите номер телефона</error>
                        </div>
                        <div class="grid30">
                            <label class="inline_label"><input name="sex_name_user" class="input_block" id="otc_name_user" type="radio" value="<?=$phone['1']?>" <?=str_replace('0','',str_replace('1','checked',$mobiles[$i]['main']))?>/>Основной</label>   
                        </div>  
                        <div class="cleared"></div>                     
                    </dt>
                                        <?php
//                    
//		 echo "<table class=\"cells3-small\">
//		        <tr><td>Телефон</td><td></td></tr>
//		        <tr>
//			     <td><input type=\"text\" value=\"".$mobiles[$i]['mobile']."\" id=\"mobiles\" class=\"brdrd\" style=\"width:385px; cursor:pointer;\" title=\"Удалить номер\" readonly /></td>
//			     <td><input type=\"radio\" name=\"user_mobile_main\" value=\"".$mobiles[$i]['mobile']."\" class=\"checkbox\" safari=1 ".str_replace('0','',str_replace('1','checked',$mobiles[$i]['main']))."/> основной</td>
//		        </tr>
//	           </table>";
		}
	}
 } 
?>
                                    <dt class="phones">
                                        <div class="grid20">
                                            <label>Код страны*</label>
                                            <?=drow_selecter('',$codes)?>
                                        </div>
                                        <div class="grid50">
                                            <label>Телефон*</label>
                                            <input name="first_name_user" class="input_block phone" id="first_name_user" type="text" style="height: auto;"/>
                                            <input name="oldphone" type="hidden" value="new" />
                                            <br /><error style='display:none;'>Введите номер телефона</error>
                                        </div>
                                        <div class="grid30">
                                            <label class="inline_label"><input name="sex_name_user" class="input_block" id="otc_name_user" type="radio" />Основной</label>   
                                        </div>  
                                        <div class="cleared"></div>                 
                                    </dt>

                                    <dt>
                                        <div class="grid20">&nbsp;
                                        </div> 
                                        <div class="grid50">&nbsp;
                                        </div>
                                        <div class="grid30 tx_r">
                                            <a id="add_phone_button" href="#" class="opacity_link" style="line-height: 15px;"><i class="small-icon icon-add"></i>Добавить телефон</a>
                                        </div>  
                                        <div class="cleared"></div>                     
                                    </dt>
                                </dl>
                            <div class="cleared"></div>
                        </div>
                        <button type="submit" class="btn btn-green" id="save_buttonus">Сохранить</button>
                    </form>
                
<script type="text/javascript">
function isChecked(selector)
{
    return $(selector).prop("checked"); 
}

function getPhones()
{
    var phones = new Array();
    var counter = 0;
    $('.phones').each(function(){
        var code = $('select', this).val();
        var phone = $('.phone', this).val();
        var oldphone = $('input[name=oldphone]',this).val();
        if (oldphone != 'new')
        {
            var oldcode = $('input[name=oldcode]',this).val();
            oldphone = oldcode + oldphone;
        }
        if ( $('input[type=radio]', this).prop("checked") ) 
            var main = counter;
        else
            var main = -1;

        phones.push({ 'phone':code+phone, 'oldphone':oldphone, 'main':main, 'number':counter, });
        counter++;
    });
    return phones;

}
 $(document).ready(function(){
    $("#add_phone_button").click(function(){
        $(".phones:last").after(
            '<dt class="phones">'+
                '<div class="grid20">'+
                    '<label>Код страны*</label>'+
                    '<?=drow_selecter('',$codes)?>'+
                '</div>'+
                '<div class="grid50">'+
                    '<label>Телефон*</label>'+
                    '<input name="first_name_user" class="input_block phone" id="first_name_user" type="text" style="height: auto;"/>'+
                    '<input name="oldphone" type="hidden" value="new" />'+
                    '<br /><error style="display:none;">Введите номер телефона</error>'+
                '</div>'+
                '<div class="grid30">'+
                    '<label class="inline_label"><input name="sex_name_user" class="input_block" id="otc_name_user" type="radio" />Основной</label>'+   
                '</div>'+  
                '<div class="cleared"></div>'+                     
            '</dt>');
        $('.tel_tools select').selectBox();
        $('error').hide();
    });
    $('#save_buttonus').click(function()
    {
        var phones = JSON.stringify( getPhones() ); 
        $.ajax({
            url:'/jquery-mymobiles',
            type:'POST',
            cache:false,
            data: {'phones':phones},
            success:function(data){
                $('#msg').html(data);

            },
          
        });
        return false;
    });
    $('error').hide();

     
	$('#center-profile select').selectBox();
	$('input[safari]:radio').checkbox({cls:'jquery-safari-checkbox'});
	$('#addmobile').click(function(){
		var mobile = $('#mobile_code').val()+$('#mobile_new').val();
		if(confirm('Проверьте правильность введенного номера\n'+mobile+'\nСохранить?')){
		$.ajax({
	      url:'/jquery-mymobiles',
	      cache:false, type:'POST',
	      data: {type:'add',mobile:mobile},
	      success:function(result){
	       	if(result == 'ok')
		       location.href='/my-phones';
	      }
	    });}
	});
	$('.checkbox').click(function(){
		var mobile = $(this).val();
		$.ajax({
	      url:'/jquery-mymobiles',
	      cache:false, type:'POST',
	      data: {type:'main',mobile:mobile},
	      success:function(result){
	       	if(result == 'ok')
		       location.href='/my-phones';
	      }
	    });
	});
	$('#mobiles').live('click',function(){
		if(confirm('Вы действительно хотите удалить номер '+$(this).val())){
			var mobile = $(this).val();
			$.ajax({
	          url:'/jquery-mymobiles',
	          cache:false, type:'POST',
	          data: {type:'delete',mobile:mobile},
	          success:function(result){
	          	if(result != 'ok')
		           alert('Ошибка сохранения данных');
	          }
	        });
		}
	});
 });
 
</script>
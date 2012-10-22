<div id="center-profile">    
  <h1>Страницу смогут видеть:</h1>
  <table id="chks">
	<tr>
		<td style="vertical-align:top;">
		<?php
		 $GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
	     if($_SESSION['WP_USER']['zvezda'] == 1) $where = " AND krug_id <> 9 "; else $where = " AND krug_id <> 10 ";
            $result = $MYSQL->query("SELECT krug_id, name_".LANG_SITE." name FROM pfx_krugi WHERE krug_id <> 1 $where ORDER BY sort");
            $pravo  = $MYSQL->query("SELECT IFNULL(pravo,'') security FROM pfx_users WHERE user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
            $pravo  = @$pravo[0]['security'];
            if(strlen($pravo) > 0) {$pravo = unserialize($pravo);} else {$pravo[] = array('krug_id'=>0);} // По умолчанию страница доступна всем
            $checked = "";
            if(is_array($result)){
            	foreach($result as $key=>$value){
            		if(is_array($pravo))
            		foreach($pravo as $key2=>$value2)
            		 if($value['krug_id'] == $value2['krug_id'])
            		 {$checked = "checked"; break;} else $checked = "";
            		
            	  echo "<p><label><input type=\"checkbox\" name=\"circle1\" value=\"".$value['krug_id']."\" class=\"frnchck\" $checked>".$value['name']."</label></p>";
            	}
            }
		?>
		</td>
		<td style="vertical-align:top; padding-left:60px; background:url('pic/bonus-table-th.png') no-repeat 20px -20px;">
			<p><label><input type="checkbox" name="circle1" value="0" id="chk_all" class="frnchck" <?=str_replace('0','checked',@$pravo[0]['krug_id'])?>>Все</label></p>
			<p><label><input type="checkbox" name="circle1" value="1" id="chk_i" class="frnchck" <?=str_replace('1','checked',@$pravo[0]['krug_id'])?>>Только Я</label></p>
		</td>
	</tr>
  </table>
  <p>&nbsp;</p>
  <p id="msg"></p>
  <p class="long-hr"></p>
  <p style="padding-left:360px;"><span class="clr-but clr-but-green"><sub></sub><a href="#" id="prof-save" onClick="return false;">Сохранить</a><sup></sup></span></p>
  <div class="clear"></div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('input.frnchck').checkbox({cls:'jquery-safari-checkbox-box'});
	$('input.frnchck').change(function(){
		if($(this).attr('id') != undefined){
		   var allCheckboxes = $("#chks input:checkbox:enabled");
		   allCheckboxes.removeAttr('checked');
		   $(this).attr('checked','checked');
		} else {
		   $('#chk_all').removeAttr('checked');
		   $('#chk_i').removeAttr('checked');
		}
	});
	$('#prof-save').click(function(){
	   var circles = new Array();
	   var i = 0;
	   $('input.frnchck').each(function(key,input){
	      if(input.checked == true)
	      circles[i++] = input.value;
	   });
	   $.ajax({
	     url:'/jquery-profsecurity',
	     cache:false, type:'POST',
	     data: {circles:circles},
	     success:function(data){
	  	   $('#msg').html(data);
	     }
	   });
	});
});
</script>
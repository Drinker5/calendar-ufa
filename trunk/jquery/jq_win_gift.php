<div class="bring-top"></div>
<div class="bring-mid">
  <div class="bring-inner">
<?php
 $gift_id   = varr_int($varr['gift']);
 $GIFT_INFO = $AKCIA->Info_min($gift_id,183,128);
 if(is_array($GIFT_INFO)){
 	$balance = $_SESSION['WP_USER']['balance']." ".$_SESSION['WP_USER']['balance_mask'];
 	echo "
 	  <h2>".$GIFT_INFO['header']."</h2>
		<p>Раздел: <a href=\"#\">Подарки</a></p>
		<table>
			<tr>
				<td class=\"lefttd\">
					<div class=\"left-gift-current-item\">
						<img src=\"".$GIFT_INFO['photo']."\" width=\"183\" height=\"128\" />
						<span class=\"left-gift-current-price\">".($GIFT_INFO['amount']/100)." ".$GIFT_INFO['currency']."</span>
					</div>
					<p class=\"yourcount\">Ваш бонусный счет:</p>
					<table><tr><td><img src=\"pic/cont-cent-icon-cash.png\" width=\"17\" height=\"23\" /></td><td><span>$balance</span></td></tr></table>
					<p class=\"countplus\"><a href=\"#\" onClick=\"WindowPayBalance(".$_SESSION['WP_USER']['user_wp'].",".$_SESSION['COUNTRY_ID']."); return false;\">".LANG_TO_RECHARGE."</a></p>
				</td>
				<td class=\"righttd\" id=\"step_menu\">
					<h2>".LANG_PRESENT_GIFT."</h2>
					<p class=\"bring-steps\"><img src=\"pic/win-bring-step1.png\" width=\"238\" height=\"31\" /></p>
					<h3>1. Выбор получателя</h3>
					<div class=\"bring-big-buts\">
						<a href=\"#\" id=\"bring-friend\" onClick=\"InitInterface(1); ListPioples(1,1); $('#step_menu').css('display', 'none'); $('#step_friends').css('display', 'block'); $('#prevstep').css('display', 'block'); return false;\"><div>Выбрать друга</div></a>
						<a href=\"#\" id=\"bring-star\" onClick=\"InitInterface(2); ListPioples(1,2); $('#step_menu').css('display', 'none'); $('#step_stars').css('display', 'block'); $('#prevstep').css('display', 'block'); return false;\"><div>Выбрать кумира</div></a>
						<a href=\"#\" id=\"bring-mail\" onClick=\"$('#step_menu').css('display', 'none'); $('#step_email').css('display', 'block'); $('#prevstep').css('display', 'block'); return false;\"><div>Email получателя</div></a>
						<a href=\"#\" id=\"bring-phone\" onClick=\"$('#step_menu').css('display', 'none'); $('#step_phone').css('display', 'block'); $('#prevstep').css('display', 'block'); return false;\"><div>Телефон получателя</div></a>
					</div>
				</td>
				<!-- --------------------------------------- -->
				<td class=\"righttd\" id=\"step_friends\" style=\"display:none\">
					<h2>".LANG_PRESENT_GIFT."</h2>
					<p class=\"bring-steps\"><img src=\"pic/win-bring-step1.png\" width=\"238\" height=\"31\" /></p>
					<h3>1. Выбрать друга</h3>
					<div class=\"bring-search\">
						<sub></sub><span style=\"width:110px;\">Поиск друга</span>
						<input type=\"text\" placeholder=\"Введите имя друга...\" style=\"width:350px;\" id=\"search_friend\" onKeyUp=\"SearchPioples($('#search_friend').val(),1); return false;\" />
						<img src=\"pic/win-bring-maginf.png\" width=\"28\" height=\"27\" />
					</div>
					<p class=\"yourcount\">Выбранные друзья:</p>
					<div id=\"item1\"></div>
					<div class=\"bring-search bring-step-2\"><sub></sub><span style=\"width:110px;\" onClick=\"if(arrUsers.length > 0 && arrUsers.length <= 10){ $('#step_friends').css('display', 'none'); $('#step_msg').css('display', 'block'); $('#prevstep').attr('rel',function(i,val){return val+':step_friends';});} return false;\">Продолжить</span><b></b><sup></sup></div>
				</td>
				<!-- --------------------------------------- -->
				<td class=\"righttd\" id=\"step_stars\" style=\"display:none\">
					<h2>".LANG_PRESENT_GIFT."</h2>
					<p class=\"bring-steps\"><img src=\"pic/win-bring-step1.png\" width=\"238\" height=\"31\" /></p>
					<h3>1. Выбрать кумира</h3>
					<div class=\"bring-search\">
						<sub></sub><span style=\"width:110px;\">Поиск кумира</span>
						<input type=\"text\" placeholder=\"Введите имя кумира...\" style=\"width:350px;\" id=\"search_star\" onKeyUp=\"SearchPioples($('#search_star').val(),2); return false;\" />
						<img src=\"pic/win-bring-maginf.png\" width=\"28\" height=\"27\" />
					</div>
					<p class=\"yourcount\">Выбранные кумиры:</p>
					<div id=\"item2\"></div>
					<div class=\"bring-search bring-step-2\"><sub></sub><span style=\"width:110px;\" onClick=\"if(arrUsers.length > 0 && arrUsers.length <= 10){ $('#step_stars').css('display', 'none'); $('#step_msg').css('display', 'block'); $('#prevstep').attr('rel',function(i,val){return val+':step_stars';});} return false;\">Продолжить</span><b></b><sup></sup></div>
				</td>
				<!-- --------------------------------------- -->
				<td class=\"righttd\" id=\"step_email\" style=\"display:none\">
					<h2>".LANG_PRESENT_GIFT."</h2>
					<p class=\"bring-steps\"><img src=\"pic/win-bring-step1.png\" width=\"238\" height=\"31\" /></p>
					<h3>1. Email получателя</h3>					
					<table>
						<tr>
							<td>
								<input type=\"text\" value=\"\" id=\"user_mail\" class=\"brdrd\" style=\"width:400px;\" placeholder=\"Email\" />
							</td>
						</tr>
					</table>
					<div class=\"bring-search bring-step-2\"><sub></sub><span style=\"width:110px;\" onClick=\"if($('#user_mail').val().length > 5){arrUsers[0] = {'user_email':$('#user_mail').val()}; $('#step_email').css('display', 'none'); $('#step_msg').css('display', 'block'); $('#idInfoToUser').html('Получатель: <span style=\'color:#349BDF; font-size:18px; font-weight:bold;\'>'+$('#user_mail').val()+'</span>'); $('#idInfoToUser').css('display','block'); $('#prevstep').attr('rel',function(i,val){return val+':step_email';});} return false;\">Продолжить</span><b></b><sup></sup></div>
				</td>
				<!-- --------------------------------------- -->
				<td class=\"righttd\" id=\"step_phone\" style=\"display:none\">
					<h2>".LANG_PRESENT_GIFT."</h2>
					<p class=\"bring-steps\"><img src=\"pic/win-bring-step1.png\" width=\"238\" height=\"31\" /></p>
					<h3>1. Телефон получателя</h3>					
					<table>
						<tr>
							<td>
								<select id=\"country-code1\">";
 	                            $GLOBALS['PHP_FILE'] = __FILE__;
	                            $GLOBALS['FUNCTION'] = __FUNCTION__;
					 			$result = $MYSQL->query("SELECT `mobcode` FROM pfx_country WHERE `parent`=0 ORDER BY `sort`");
			                    if(is_array($result)){
			     	               foreach($result as $key=>$value)
			     	                echo "<option value=\"".$value['mobcode']."\">".$value['mobcode']."</option>";
			                    }
					     echo " </select>
							</td>
							<td>
								<input type=\"text\" value=\"\" id=\"user_mobile\" class=\"brdrd\" style=\"width:300px;\" placeholder=\"Введите номер мобильного телефона\" />
							</td>
						</tr>
					</table>
					<div class=\"bring-search bring-step-2\"><sub></sub><span style=\"width:110px;\" onClick=\"if($('#user_mobile').val().length > 5){arrUsers[0] = {'user_mobile':$('#country-code1').val()+$('#user_mobile').val()}; $('#step_phone').css('display', 'none'); $('#step_msg').css('display', 'block'); $('#idInfoToUser').html('Получатель: <span style=\'color:#349BDF; font-size:18px; font-weight:bold;\'>'+$('#country-code1').val()+$('#user_mobile').val()+'</span>'); $('#idInfoToUser').css('display','block'); $('#prevstep').attr('rel',function(i,val){return val+':step_phone';});} return false;\">Продолжить</span><b></b><sup></sup></div>
				</td>
				<!-- --------------------------------------- -->
				<td class=\"righttd\" id=\"step_msg\" style=\"display:none\">
					<h2>".LANG_PRESENT_GIFT."</h2>
					<p class=\"bring-steps\"><img src=\"pic/win-bring-step2.png\" width=\"238\" height=\"31\" /></p>
					<h3>2. Поздравительное сообщение</h3>
					<table>
						<tr>
							<td>
								<textarea id=\"msg\" class=\"brdrd\" style=\"width:490px; height:250px;\"></textarea>
							</td>
						</tr>
					</table>
					<div class=\"bring-search bring-step-3\"><sub></sub><span style=\"width:110px;\" onClick=\"$('#step_msg').css('display', 'none'); $('#step_pin').css('display', 'block'); $('#prevstep').attr('rel',function(i,val){return val+':step_msg';}); return false;\">Продолжить</span><b></b><sup></sup></div>
					<div class=\"bring-search bring-step-3-skip\"><sub></sub><span style=\"width:110px;\" onClick=\"$('#msg').val('');  $('#step_msg').css('display', 'none'); $('#step_pin').css('display', 'block'); $('#prevstep').attr('rel',function(i,val){return val+':step_msg';}); return false;\">Пропустить</span><b></b><sup></sup></div>
				</td>
				<!-- --------------------------------------- -->
				<td class=\"righttd\" id=\"step_pin\" style=\"display:none\">
					<h2>".LANG_PRESENT_GIFT."</h2>
					<p class=\"bring-steps\"><img src=\"pic/win-bring-step3.png\" width=\"238\" height=\"31\" /></p>
					<div id=\"result_pay\">
					<h3>3. Введите Пин-Код</h3>					
					<table>
						<tr>
							<td><input type=\"password\" id=\"pincode\" class=\"brdrd\" style=\"width:230px;\" placeholder=\"Пин-Код\" /></td>
						</tr>
					</table>
					<p class=\"yourcount\" style=\"margin:10px 0;\"><div id=\"idInfoToUser\" style=\"display:none;\"></div></p>					
					<div class=\"finalbring\">Подарить</div>
					</div>
				</td>
				<!-- --------------------------------------- -->
			</tr>
		</table>
		<script type=\"text/javascript\">
		   var arrUsers = new Array();
           var Privat   = 0;
		   $(document).ready(function(){
              $('#country-code1').selectBox();
              $('#prevstep').click(function(){
                 var str = '';
                 var rel = $(this).attr('rel');
                 rel = rel.split(':');
                 $('.righttd').css('display', 'none');
		         $('#'+rel[rel.length-1]).css('display', 'block');
		         for(var i=0; i < rel.length-1; i++){
		           if(i == 0){ str = str + rel[i]; }
		           else {str = str + ':' + rel[i]; }
		         }
		         if(str == '') str = 'step_menu';
		         $('#prevstep').attr('rel',str);
		         if(rel[rel.length-1] == 'step_menu'){
		            $('#prevstep').css('display','none');
		            arrUsers = new Array();
		         }
              });
              
              $('.finalbring').click(function(){
                 var pin = $('#pincode').val();
                 if(pin.length != 4) return false;
		         $('#result_pay').html('<h3>Обработка данных</h3><div id=\"loading\" style=\"padding-top:30px; text-align: center;\"><img src=\"pic/loader_clock.gif\"></div>');
	             $.ajax({
	               url:'/jquery-paygift',
		           cache:false,
		           type: 'POST',
		           data: {gift_id:$gift_id,users:arrUsers,msg:$('#msg').val(),pin:pin,privat:Privat},		           
		           success:function(data){
		            $('#result_pay').html(data);
		           }
	             });
              });
           });
		
		   function InitInterface(id){
		     var text = new Array('Максимальное колличество друзей набрано (10)','Максимальное колличество кумиров набрано (10)');
		     $('#sel-people').remove(); $('#people-list').remove();
		     $('#item'+id).html('<div id=\"sel-people\"></div><p class=\"long-hr\"></p><div id=\"people-list\" class=\"scroll-pane\"></div><div id=\"more10\">'+text[id-1]+'<div></div></div>');
		   }
		
		
		   function ListPioples(page,star){
		     $('#next_friends').remove();
		     $('#people-list').append('<div id=\"loading\" style=\"padding-top:30px; text-align: center;\"><img src=\"pic/loader_clock.gif\"></div>');
	         $.ajax({
	          url:'/jquery-listmyfriends',
		      cache:false,
		      type: 'POST',
		      data: {page:page,star:star},
		      success:function(data){
			     $('#loading').remove();
			     $('#people-list').jScrollPane({showArrows:false}).data('jsp').destroy();
			     $('#people-list').append(data);
			     $('#people-list').jScrollPane({showArrows:false});
		      }
	         });
		   }
		   
		   function SearchPioples(fio,star){
		     if(fio.length >= 3){
		        $('#people-list').append('<div id=\"loading\" style=\"padding-top:30px; text-align: center;\"><img src=\"pic/loader_clock.gif\"></div>');
		        $.ajax({
	              url:'/jquery-listmyfriends',
		          cache:false,
		          type: 'POST',
		          data: {fio:fio,star:star},
		          success:function(data){
			        $('#loading').remove();
			        $('#people-list').jScrollPane({showArrows:false}).data('jsp').destroy();
			        $('#people-list').html(data);
			        $('#people-list').jScrollPane({showArrows:false});
		          }
	            });
		     } else if(fio.length <= 0){ $('#people-list').jScrollPane({showArrows:false}).data('jsp').destroy(); $('#people-list').html(''); ListPioples(1,star); }		     
		   }
		
		   function AddUsers(id){
		     var error=0;
		     var result = false;
	         for(var i=0; i < arrUsers.length; i++){
	           if(arrUsers[i].user_wp == id){
	   	         error=1; break;
	           }
             }
	         if(error == 0){
		        arrUsers[arrUsers.length] = {'user_wp':id};
		        result = true;
	         }
	         return result;
		   }
		   
		   function DelUsers(id){
		     var tmpArray = new Array();
		     var result = false;
	         for(var i=0; i < arrUsers.length; i++){
	             if(arrUsers[i].user_wp == id){
	                result = true;
	             } else {
	   	           tmpArray[tmpArray.length] = {'user_wp':arrUsers[i].user_wp};
	             }
             }
             arrUsers = tmpArray;
             return result;
		   }
		   
		   $('#people-list .fr-box').live('click', function(){
		     var n=$('#sel-people div.fr-box').size();
		     if(n<10){
			   var item=$(this), rel=item.attr('rel'), insert=$('#sel-people'), img=item.children('img').attr('src');
			       AddUsers(rel);
				   if(item.find('div').length>0){}
			    else item.append('<div><img src=\"pic/win-bring-fr-add.png\" /></div>');
				   if(insert.find('.fr-box[rel='+rel+']').length>0){}
			    else insert.append('<div class=\"fr-box\" rel=\"'+rel+'\"><img src=\"'+img+'\" width=\"36\" height=\"36\" /><div><img src=\"pic/win-bring-fr-del.png\" /></div></div>');
			    
		     }
		     else{
			   var info=$('#more10');
			   info.css({'display':'block','left':(511-info.width())/2+193+50, 'top':310});
		     }
	        });
	      $('html').click(function(e){
		   if(e.target.id!='#more10')$('#more10').css('display','none');
	      });

	      $('#sel-people .fr-box').live('click', function(){
		   var item=$(this), rel=item.attr('rel');
		   if(DelUsers(rel)){
		      item.remove();
	   	      $('#people-list .fr-box[rel='+rel+'] div').remove();
		   }
	      });
	    </script>
 	";
 	
 } //else header("Location: '/'");
?>
  </div>
</div>
<div class="bring-bot"></div>
<div class="bring-prev" id="prevstep" rel="step_menu" style="display:none"><img src="pic/colorboxprev.png" width="21" height="20" /></div>
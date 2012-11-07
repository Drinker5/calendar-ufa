<?php
	//PreArray($AKCIA_INFO);
   require_once('jquery/jq_friends.php');

   //Друзья
   $online=0;
   $circle=isset($_REQUEST['c'])?$_REQUEST['c']:0;
   $user_wp=(int)$_SESSION['WP_USER']['user_wp'];
   $subs_count=$USER->CountFriends(0,$user_wp,$online); //XXS?XS???!?!?!??!
   $rows=12;

   $action_str = '';
   $result = $USER->CheckHochu($akcia_id);

   //$akcia_id.', '.$AKCIA_INFO['shop_id']

   if($result==0){
        $action_str = '<a class="wish_add" href="javascript:;" data-id="'.$akcia_id.'" data-shop="'.$AKCIA_INFO['shop_id'].'"><span class="wa_icon"></span>Добавить к себе в желания</a>';
   }
   else $action_str = '<a class="disabled" href="javascript:;"><span class="wa_icon"></span>Добавлено в желания</a>';

?>
            <div id="content" class="fl_r">
               <div class="title margin">
                  <h2>Подарки</h2>
               </div>
               <div class="separator"></div>
               <a href="/type-5" class="arrow-back">Вернуться к списку подарков</a>
               <div id="gift_info">
                  <div class="gi_l">
                     <div class="gift_l">
                        <div class="gift_img">
<?php
	if($AKCIA_INFO['discount']>0){
		echo'
                           <span class="sale_circle">'.$AKCIA_INFO['discount'].'%</span>';
	}
?>
                           <img src="<?=$AKCIA_INFO['photo']?>" alt="cappuccino" width="185" height="184">
                           <span class="gift_price"><?=round($AKCIA_INFO['discount']>0?$AKCIA_INFO['amount']*(100-$AKCIA_INFO['discount'])/10000:$AKCIA_INFO['amount']/100)?> <?=$AKCIA_INFO['currency']?></span>
                        </div>
                           <ul class="currency">
                           <li class="active">RUB</li>
                           <li>USD</li>
                           <li>EUR</li>
                        </ul>
                           <?=$action_str?>
                           <a class="wish_make" href="javascript:;" style="display: none;"><span class="gift_icon"></span>Сделать подарок</a>
                     </div>
                  </div>
                  
                  <div class="gi_r">
                     <div class="map">
                        <div class="m_header">
                           <i class="map_marker"></i>
                           <span class="map_name"><?=$AKCIA_INFO['shopname']?></span>
                           <i class="map_close"></i>
                           <span class="map_wrapper"></span>
                        </div>
                     </div>
                     <div class="chosen_friend" style="display: none;">
                        <p class="c_title">Кому</p>
                        <p class="c_name">Александер Мартиросянович</p>
                        <div class="round_mask">
                        </div>
                     </div>
                     
                     <span class="gift_name"><?=$AKCIA_INFO['header']?></span>
                     
                     <div class="gift_block">
                        <div class="gb_title">
                           Место:
                        </div>
                        <div class="gb_content">
                           <p class="gift_title"><?=$AKCIA_INFO['shopname']?></p>
                           <p>г. Москва, Тверская улица д.3 стр. 5</p>
                           <p>Телефон: <?=$AKCIA_INFO['phone']?></p>
                           <p class="toggle_map"><i class="small-icon icon-check-in"></i><a class="px11" href="javascript:;">Посмотреть на карте</a><i class="icon-blue-arrow-right"></i></p>
                        </div>
                     </div>
                     
                     <div class="gift_block">
                        <div class="gb_title">
                           Наличие:
                        </div>
                        <div class="gb_content">
                           <div class="contentProgress"><div class="available tipN" id="bar1" title="<?=$AKCIA_INFO['kolvo']*10?>%"></div></div>
                        </div>
                     </div>
                     
                     <div class="gift_block">
                        <hr>
                        <div class="gb_title">
                           Оплата:
                        </div>
                        <div class="gb_content">
                           <a href="javascript:;" class="gb_icon gb_paypal"></a>
                           <a href="javascript:;" class="gb_icon gb_cp"></a>
                           <a href="javascript:;" class="gb_icon gb_visa"></a>
                           <a href="javascript:;" class="gb_icon gb_maestro"></a>
                           <a href="javascript:;" class="gb_icon gb_webmoney"></a>
                           
                           <div class="show_info popover-btn">
                              <i class="small-icon icon-info"></i>
                              <a class="px11" href="javascript:;">Подробная информация</a>
                           </div>
                        </div>
                     </div>
                     
                     <div class="gift_block">
                        <hr>
                        <div class="gb_title">
                           Описание:
                        </div>
                        <div class="gb_content">
                           <p>Расчет основан на предположении, что вы являетесь частным покупателем и еще не импортировали другие товары в этом месяце. </p>
                        </div>
                     </div>
                     
                     <div class="gift_block">
                        <hr>
                        <p class="g_desc">Срок действия подарка с момента получения <span class="green">30 дней</span>.</p>
                     </div>
                     
                     <div class="gift_block">
                        <span class="g_desc">Рассказать друзьям:</span>
                           <a href="javascript:;" class="gb_icon gb_fb"></a>
                           <a href="javascript:;" class="gb_icon gb_lj"></a>
                           <a href="javascript:;" class="gb_icon gb_vk"></a>
                           <a href="javascript:;" class="gb_icon gb_gp"></a>
                           <a href="javascript:;" class="gb_icon gb_tw"></a>
                     </div>
                     
                  </div>
               </div>

               <div class="c_a" style="display:none;">
                  <i class="small-icon icon-man-near"></i>
                  <a class="px11" href="#selectFriend" onclick="setOtherFriends()">Выбрать другого получателя</a>
               </div>

               <div id="selectFriend">
               <p class="px13 h3 green">Осталось выбрать получателя</p>
               <form class="recipient" action="#selectFriend">
                  <input type="search" placeholder="Алекс..." id="search-input">
                  <input type="submit" value="">
               </form>

               <div class="nav-panel group">
<?php
   $circles=Circles();
   if(is_array($circles)){
      echo '<ul class="fl_r right">';
      foreach($circles as $key=>$value){
         echo '<li';
         if(@$_REQUEST['c']==$value['krug_id'])echo ' class="active"';
         echo '><a href="javascript:;" onclick="setCircle('.$value['krug_id'].',\''.$value['name'].'\')">'.$value['name'].'</a></li>';
      }
      echo '</ul>';
   }
?>
                </div>
                
                <div class="choose-list">
                   <p class="px13 l_blue"><span id="circleName">Моя страница (<?=$subs_count?>)</span></p>
                   <div id="idfriends"><?=ShowPeopleList($user_wp,$rows,0,$online)?></div>
                   <div id="loadmoreajaxloader" style="display:none; text-align:center;"><img src="/pic/loader.gif" alt="loader" width="32" height="32" /></div>
                </div>
                
                <div class="choose-list">
                   <p class="px13 l_blue">Другие пользователи</p>
<?php
   //Если друзей 6 и меньше показываем случайных людей
   if($subs_count<7){
      if(isset($_SESSION['WP_USER']['user_wp'])){
         $result =$MYSQL->query("
            SELECT `u`.`user_wp`
            FROM `discount_users` `u`
            WHERE `u`.`user_wp`<>".$user_wp."
            AND `u`.`zvezda`=0
            AND `u`.`everif`=1
            AND `u`.`user_wp` NOT IN (SELECT `user_wp` FROM `discount_users_friends` WHERE `friend_wp` =".$user_wp.")
            AND `u`.`town_id`=(SELECT `town_id` FROM `pfx_users` WHERE `pfx_users`.`user_wp`=".$user_wp.")
            ORDER BY rand()
            LIMIT 0,4
         ");
         if(is_array($result)){
            //echo '<br />Может быть, Вы знаете этих людей?<br />';
            foreach ($result as $k=>$v){
               $stamp=time();
               $new_usr=$USER->Info_min($v['user_wp'],70,70);
               echo ShowFriendBlock($user_wp, $new_usr['photo'], $new_usr, $stamp);
            }
         }
      }
   }

?>
               </div>
               </div>

            <script>
               var page=1, max=<?=ceil($subs_count/$rows)?>, rows=<?=$rows?>, begin=rows, circle=<?=$circle?>;

               function getFriends(){
                  var fio=fio||$('#search-input').val();

                  if(max>page){
                     $.ajax({
                        url:'/jquery-listfriends',
                        type:'POST',
                        data:{user_wp:<?=$user_wp?>, list:begin, items:rows, circle:circle, online:'<?=$online?>', fio:fio},
                        cache:false,
                        success:function(data){
                           var html;

                           if(data){
                              $('div#loadmoreajaxloader').hide();
                              html =jQuery.parseJSON(data);
                              $('#idfriends').append(html.html);
                              page =page+1;
                              begin=begin+rows;
                           }
                        }
                     });
                  }
               }

               function setCircle(id,name){
                  var fio=fio||$('#search-input').val();

                  begin=0; circle=id;
                  $('#idfriends').html('');
                  $('div#loadmoreajaxloader').show();

                  $.ajax({
                     url:'/jquery-listfriends',
                     type:'POST',
                     data:{user_wp:<?=$user_wp?>, list:begin, items:rows, circle:circle, online:'<?=$online?>', fio:fio},
                     cache:false,
                     success:function(data){
                        var html;

                        if(data){
                           $('div#loadmoreajaxloader').hide();
                           html =jQuery.parseJSON(data);
                           $('#circleName').html(name + ' (' + html.count + ')');
                           $('#idfriends').append(html.html);
                           page=1;
                           begin=begin+rows;
                        }
                     }
                  });
               }

               function searchFriends(){
                  var fio=$('#search-input').val();

                  if(fio.length>2){
                     begin=0;
                     $('#idfriends').html('');
                     $('div#loadmoreajaxloader').show();
                  }

                  $.ajax({
                     url:'/jquery-listfriends',
                     type:'POST',
                     data:{user_wp:<?=$user_wp?>, list:begin, items:rows, circle:circle, online:'<?=$online?>', fio:fio},
                     cache:false,
                     success:function(data){
                        var html;

                        if(data){
                           $('div#loadmoreajaxloader').hide();
                           html =jQuery.parseJSON(data);
                           $('#idfriends').append(html.html);
                           page=1;
                           begin=begin+rows;
                        }
                     }
                  });
               }

               function setFriends(fio,photo){
                  $('#selectFriend').hide();
                  $('.chosen_friend .c_name').html(fio);
                  $('.chosen_friend .round_mask').html('<span class="r_m"></span><img src="' + photo + '">');
                  $('.wish_make').show();
                  $('.chosen_friend').show();
                  $('.c_a').show();
               }

               function setOtherFriends(){
                  $('.wish_make').hide();
                  $('.chosen_friend').hide();
                  $('.c_a').hide();
                  $('#selectFriend').show();
               }

               $(window).scroll(function(){
                  if($(window).scrollTop()==$(document).height()-$(window).height()){
                     getFriends();
                  }
               });

               jQuery(function($){
                  $("#search-input").change(function(event){
                     searchFriends();
                  });
               });
            </script>

            </div>
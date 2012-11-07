			<div id="sidebar" class="fl_l">
<?php
function showAvatarsSlider($user_id)
	{
		global $USER;
		$limit = 5;
		$photos = $USER -> ShowAvatarAlbum($user_id,$limit);

		if ($photos == 0)
			return '<img width="190" src="'.no_foto.'" />';
		$default_avatar = $_SESSION['WP_USER']['photo'];
		//общий output
		$output = '';
		//временный output, пишем сюда все аватары, кроме дефолтной
		$tmp_output = '';
		foreach ($photos as $value) {
			if ($default_avatar == $value['avatar'])
				$output .= "<img width='190' class='rsImg' src='{$value['avatar']}' />";
			else
				$tmp_output .= "<img width='190' class='rsImg' src='{$value['avatar']}' />";
		}
		return $output.$tmp_output;
	}
	switch($left_menu){
		//!Страница пользователя но не моя
		case 0:
			$kumir=''; $of_site='';//официальный сайт звезды

			//Если звезда
			if($USER_INFO['zvezda']==1){
				$kumir='<div id="star-garant"><div>'.LANG_KUMIR.'</div><span>'.LANG_GARANT.'</span></div>';
				if(strlen($USER_INFO['url'])>5)$of_site='<tr><td><img src="/pic/official_site.png" width="13" height="17" />&nbsp;</td><td><a href="'.$USER_INFO['url'].'" target="_blank">'.LANG_OF_SITE.'</a></td></tr>';
			}


?>
				<div class="sidebar-inner">
					<div class="profile-avatar">
						<div class="royalSlider rsDefault">
	                        <?=showAvatarsSlider($USER_INFO['user_wp'])?>
                     	</div>
                     </div>
					<div class="profile-bottom">
						<div class="status-wrap">
							<?=OnlineStatus($USER_INFO['status_chat'],'')?>
							<?=trim($USER_INFO['firstname'].' '.$USER_INFO['lastname'])?>
						</div>
						<div class="separator"></div>
						<ul class="profile-actions group">
							<li><a href="#" class="big-circle-icon circle-icon-make-gift"></a><br />Сделать подарок</li>
							<li><a href="#" class="big-circle-icon circle-icon-chat"></a><br />Начать чат</li>
							<li><a href="#" class="big-circle-icon circle-icon-invite"> </a><br />Пригласить</li>
						</ul>
					</div>
					<ul class="sidebar-menu">
		<?php if($USER_INFO['security']){
						$count_hochu=$USER->CountIHochu($USER_INFO['user_wp']);
		?>
						<li><a href="/<?=$USER_INFO['user_wp']?>-wishes"><i class="small-icon icon-wish"></i> Желания</a> <span class="notice-wrap"><?=$count_hochu['all']?></span></li>
						<li><a href="/<?=$USER_INFO['user_wp']?>-calendar"><i class="small-icon icon-calendar"></i> Календарь</a></li>
						<li><a href="/<?=$USER_INFO['user_wp']?>-friends"><i class="small-icon icon-whos-near"></i> Друзья</a> <span class="notice-wrap"><?=$USER->CountFriends(0,$USER_INFO['user_wp'])?></span></li>
						<li><a href="/<?=$USER_INFO['user_wp']?>-photoalbums"><i class="small-icon icon-photoalbum"></i> Фотоальбомы</a> <span class="notice-wrap"><?=$USER->CountPhotoAlbums($USER_INFO['user_wp'])?></span></li>
						<div class="separator"></div>
		<?php } ?>
		<?php
			switch($USER->IsFriendAction($USER_INFO['user_wp'])){
				case 1://Могу пригласить
					echo '<li><a href="#" class="add_new_friend" data-user="'.$USER_INFO['user_wp'].'" data-name="'.trim($USER_INFO['firstname'].' '.$USER_INFO['lastname']).'"><i class="small-icon icon-add-friend"></i> Добавить в друзья</a></li>';
				break;

				case 2://Приглашение отправленно
					echo '<li><a href="#"><i class="small-icon icon-man-near"></i> Уже приглашён</a></li>';
				break;

				case 3://Подтвердить приглашение
					echo '<li><a href="#" class="save_new_friend" data-user="'.$USER_INFO['user_wp'].'" data-name="'.trim($USER_INFO['firstname'].' '.$USER_INFO['lastname']).'"><i class="small-icon icon-add-friend"></i> Принять приглашение</a></li>';
				break;

				case 4://Удалить пользователя
					echo '<li><a href="#" class="id_friend_del" data-user="'.$USER_INFO['user_wp'].'" data-name="'.trim($USER_INFO['firstname'].' '.$USER_INFO['lastname']).'"><i class="small-icon icon-delete-friend"></i> Удалить из друзей</a></li>';
				break;
			}
		?>
					</ul>
				</div>
<?php
		break;

		//!Моя страница
		case 1:
			//Маленький аватар
			
?>
				<div class="sidebar-inner">
					<div class="profile-avatar">
						<div class="royalSlider rsDefault">
							<?=showAvatarsSlider($_SESSION['WP_USER']['user_wp'])?>
						</div>
					</div>
					<div class="profile-bottom">
						<div class="status-wrap">
							<span class="icon-wrap" style="position: relative;">
								<div class="status-change">
									<ul>
										<li<?php if($_SESSION['WP_USER']['status_chat']==1)echo ' class="active"' ?> data-class="icon-online">
											<i class="status-icon icon-check"></i>
											<i class="status-icon icon-online"></i>
											онлайн
										</li>
										<li<?php if($_SESSION['WP_USER']['status_chat']==2)echo ' class="active"' ?> data-class="icon-eezzy">
											<i class="status-icon icon-check"></i>
											<i class="status-icon icon-eezzy"></i>
											eezzy
										</li>
										<li<?php if($_SESSION['WP_USER']['status_chat']==3)echo ' class="active"' ?> data-class="icon-disturb">
											<i class="status-icon icon-check"></i>
											<i class="status-icon icon-disturb"></i>
											не трогай меня
										</li>
										<li<?php if($_SESSION['WP_USER']['status_chat']==0)echo ' class="active"' ?> data-class="icon-offline">
											<i class="status-icon icon-check"></i>
											<i class="status-icon icon-offline"></i>
											не в сети
										</li>
									</ul>
								</div><!--end of .status-change-->
								<?=OnlineStatus($_SESSION['WP_USER']['status_chat'],'')?>
							</span><!--end of .icon-wrap-->
							<?=trim($_SESSION['WP_USER']['firstname'].' '.$_SESSION['WP_USER']['lastname'])?>
						</div><!--end of .status-wrap-->
						<div class="separator"></div>
						<ul class="profile-actions group">
							<li>
								<a href="/my-checkin" class="big-circle-icon circle-icon-check-in"></a><br>Check-in
							</li>
							<li>
								<a href="#" class="big-circle-icon circle-icon-whos-near"></a><br>Кто рядом
							</li>
							<li>
								<a href="#" class="big-circle-icon circle-icon-where-to-go"></a><br>Куда пойти
							</li>
						</ul>
					</div><!--end of .profile-bottom-->

					<ul class="sidebar-menu">
						<li<?php if(@$_URLP[1]=='gifts')echo ' class="active"'; ?>>
							<a href="/my-gifts"><i class="small-icon icon-gift"></i> Мои подарки</a>
							<span class="notice-wrap"><?=$USER->CountPodarki(0,0,'new')?></span>
						</li>
						<li<?php if(@$_URLP[1]=='friends')echo ' class="active"'; ?>>
							<a href="/my-friends"><i class="small-icon icon-whos-near"></i> Мои друзья</a>
							<span class="notice-wrap popover-btn" id="add-friend"><i class="small-icon icon-green-plus"></i></span>
						</li>
						<li<?php if(@$_URLP[1]=='wishes')echo ' class="active"'; ?>>
							<a href="/my-wishes"><i class="small-icon icon-wish"></i> Мои желания</a>
						</li>
						<!--li<?php if(@$_URLP[1]=='calendar')echo ' class="active"'; ?>>
							<a href="/my-calendar"><i class="small-icon icon-calendar"></i> Мой календарь</a>
						</li-->
						<li<?php if(@$_URLP[1]=='subscribes')echo ' class="active"'; ?>>
							<a href="/my-subscribes"><i class="small-icon icon-subscription"></i> Мои подписки</a>
						</li>
						<li<?php if(@$_URLP[1]=='photoalbums')echo ' class="active"'; ?>>
							<a href="/my-photoalbums"><i class="small-icon icon-photoalbum"></i> Мои фотоальбомы</a>
						</li>
						<div class="separator"></div>
						<li<?php if(@$_URLP[1]=='feed')echo ' class="active"'; ?>>
							<a href="/my-feed"><i class="small-icon icon-address"></i> Лента новостей</a>
						</li>
						<li<?php if(@$_URLP[1]=='announcements')echo ' class="active"'; ?>>
							<a href="/my-announcements"><i class="small-icon icon-notice"></i> Мои уведомления</a>
							<span class="notice-wrap"><?=$USER->CountUvedom()?></span>
						</li>
						<div class="separator"></div>
						<li<?php if(@$_URLP[1]=='profile' or @$_URLP[1]=='phones' or @$_URLP[1]=='alerts' or @$_URLP[1]=='avatar' or @$_URLP[1]=='password')echo ' class="active"'; ?>>
							<a href="/my-profile"><i class="small-icon icon-settings"></i> Мои настройки</a>
						</li>
					</ul>
				</div><!--end of .sidebar-inner-->

				<!--table class="online-count">
					<tr>
						<td><div class="bubble bordered">сейчас on-line <?=lang_friends_online($USER->CountFriends(0,$_SESSION['WP_USER']['user_wp'],1),$_SESSION['lang'])?></div></td>
						<td><a href="#" class="btn btn-green">Чат</a></td>
					</tr>
				</table><!--end of .online-count-->
<?php
		break;

		//!Категории подарков
		case 2:
		echo "
		<script type=\"text/javascript\">
          jQuery(document).ready(function($){

         $('ul#left-menu-gift-lst > li > a').click(function () {
          item = $(this).closest('li');
          item
             .siblings('li')
             .removeClass('active')
             .children('ul')
             .slideUp()
             .closest('li')
             .find('div.left-menu-gift-img')
             .css('background-position-y', '0px')
             .closest('li')
             .children('sup')
             .css('display', 'none');

             item.find('div.left-menu-gift-img').css('background-position-y', '-37px');
             item.addClass('active').children('ul').slideToggle();
             item.children('sup').css('display', 'block');

             return false;/* чтобы ссылка не срабатывала */
           });
         });
        </script>
		<!--Левое меню-->
			<div id=\"left-gift\">
			<div id=\"left-menu-gift\">
			    <div id=\"left-menu-gift-top\"></div>
			    <h1>Категории подарков</h1>
			    <ul id=\"left-menu-gift-lst\">";
			      for($i=0; $i < count($arrGROUPS); $i++){ ///grshops-".$arrGROUPS[$i]['gr_id']."-$type_id
                    echo "<li class=\"left-menu-gift-lst-elem\">
                        <a href=\"#\">
                            <div class=\"left-menu-gift-img ".$arrGROUPS[$i]['classhtml']."\"></div>
                            <span>".$arrGROUPS[$i]['gr_name']."</span>
                        </a>
                        <sup class=\"hide\"></sup>
                        <ul class=\"left-menu-gift-sub hide\">";
                            $arrGROUPS2 = $GROUPS->ShowGroupType($type_id,$arrGROUPS[$i]['gr_id']);
                            if(is_array($arrGROUPS2))
                             foreach($arrGROUPS2 as $key=>$value)
                               echo "<li><a href=\"/type-$type_id-".$value['gr_id']."\">".$value['gr_name']."</a></li>";
                        echo "</ul>
                    </li>";
			      }
               echo "
			    </ul>
			    <div id=\"left-menu-gift-bottom\"></div>
			</div>
			</div>
		";
	break;

		//!Страница магазина
		case 3:
?>
				<div class="sidebar-inner">
					<div class="profile-avatar">
						<img src="<?=$SHOP_INFO['logo']?>" alt="<?=$SHOP_INFO['name']?>" width="190" />
					</div>
					<div class="profile-bottom">
						<div class="status-wrap"><?=$SHOP_INFO['name']?></div>
						<div class="separator"></div>
						<ul class="profile-actions group">
<?php
						if($SHOP->isSubscribed($SHOP_INFO['id']))
							echo '<li><a href="#" class="big-circle-icon circle-icon-subscribe add_subscribe" data-shop="'.$SHOP_INFO['id'].'"></a><br />Подписаться</li>';
						else
							echo '<li><a href="#" class="big-circle-icon circle-icon-subscribe del_subscribe" data-shop="'.$SHOP_INFO['id'].'"></a><br />Отписаться</li>';

						$aFav=array();
						foreach($SHOP_INFO['adressa'] as $k=>$v)
							$aFav[]=$v['id'];

						if($SHOP->isFavorite($aFav))
							echo '<script>var type="delete";</script><li><a href="javascript:;" onclick="FavAction('.$SHOP_INFO['id'].')" class="big-circle-icon circle-icon-favorite active"></a><br />Любимое место</li>';
						else
							echo '<script>var type="add";</script><li><a href="javascript:;" onclick="FavAction('.$SHOP_INFO['id'].')" class="big-circle-icon circle-icon-favorite"></a><br />Любимое место</li>';
?>
							<li><a href="#" class="big-circle-icon circle-icon-invite"></a><br />Пригласить</li>
						</ul>
					</div>
					<ul class="sidebar-menu">

<?php
					if(isset($_SESSION['KLIENT']))$where=" AND `pfx_akcia`.`klient_id`=".$_SESSION['KLIENT']['id'];
					else                          $where=" AND `pfx_akcia`.`moderator`=1";

					$type=$MYSQL->query("
						SELECT Count(`pfx_akcia`.`idtype`) `rows`, `pfx_type`.`id`, `pfx_type`.`name_".LANG_SITE."` `name`, `pfx_type`.`img_small2`
						FROM `pfx_akcia`
						INNER JOIN `pfx_type` ON `pfx_type`.`id`=`pfx_akcia`.`idtype`
						WHERE `pfx_akcia`.`shop_id`=".$shop_id." AND `pfx_type`.`active`=1 AND `pfx_akcia`.`del`<>1 ".$where."
						GROUP BY `pfx_type`.`id`, `pfx_type`.`name_".LANG_SITE."`
						ORDER BY `pfx_type`.`name_".LANG_SITE."`"
					);

					if(is_array($type)){
						foreach($type as $key=>$value){
							echo '<li><a href="/type-'.$value['id'].'"><i class="small-icon icon-'.$value['id'].'"></i> '.$value['name'].'</a> <span class="notice-wrap">'.$value['rows'].'</span></li>';
						}
					}
?>
						<div class="separator"></div>
						<li><a href="/shop-<?=$SHOP_INFO['id']?>-calendar"><i class="small-icon icon-calendar"></i> Календарь</a> <span class="notice-wrap"><?=$SHOP_INFO['count_podpisok']?></span></li>
						<li><a href="/shop-<?=$SHOP_INFO['id']?>-photoalbums"><i class="small-icon icon-photoalbum"></i> Фотоальбомы</a></li>
						<div class="separator"></div>
						<li id="count_subscribers"><a href="/shop-<?=$SHOP_INFO['id']?>-subscribers"><i class="small-icon icon-subscription"></i> Подписчики</a> <span class="notice-wrap"><?=$SHOP->CountSubscribers($SHOP_INFO['id'])?></span></li>
						<li><a href="/shop-<?=$SHOP_INFO['id']?>-reviews"><i class="small-icon icon-review"></i> Отзывы</a></li>
					</ul>
				</div>
<?php
		break;

	case 4: // Страница подарка
	  if($AKCIA_INFO['dogovor'] == 1){
 	     $price = ($AKCIA_INFO['amount']/100)." ".$AKCIA_INFO['currency'];
      } else {
      	 $date = "";
      	 if(strlen($AKCIA_INFO['datastart']) > 0) $date  = "с ".$AKCIA_INFO['datastart'];
  	     if(strlen($AKCIA_INFO['datastop']) > 0)  $date .= " <span>до ".$AKCIA_INFO['datastop']."</span>";
 	     $price = $date;
      }

	  echo "
	   <div id=\"left-current-gift-menu\">
                <div class=\"left-gift-current-item\">
                    <img src=\"".$AKCIA_INFO['photo']."\" width=\"183\" height=\"128\" alt=\"".$AKCIA_INFO['header']."\" />
                    <span class=\"left-gift-current-price\">$price</span>
                </div>
                <a class=\"left-gift-but left-gift-but-green\" href=\"#\" id=\"bring\">
                    <sub></sub>
                    <span>Подарить</span>
                    <sup></sup>
                </a>
                <a class=\"left-gift-but left-gift-but-blue\" href=\"#\">
                    <sub></sub>
                    <span>Добавить в мои желания</span>
                    <sup></sup>
                </a>
            </div>
	  ";
	break;

	case 5: // Профиль пользователя
		switch(str_replace(".php","",@$_URLP[1])){
			case 'profile'      :$cur1='class="cur"'; break;
			case 'avatar'       :$cur2='class="cur"'; break;
			case 'phones'       :$cur3='class="cur"'; break;
			case 'purces'       :$cur4='class="cur"'; break;
			case 'notifications':$cur5='class="cur"'; break;
			//case '': $cur6 = "class=\"cur\""; break;
			case 'security'     :$cur7='class="cur"'; break;
			case 'password'     :$cur8='class="cur"'; break;
		}
?>
		<div id="left" style="top:41px;">
			<div id="left-menu">
				<h1 class="small-h1">Мои настройки</h1>
				<img src="pic/cont-left-hr.png" width="148" height="1" />
				<ul>
					<li><a href="/my-profile" <?=@$cur1?>>Профиль</a></li>
					<li><a href="/my-password" <?=@$cur8?>>Изменить пароль</a></li>
					<li><a href="/my-avatar" <?=@$cur2?>>Изменить аватар</a></li>
					<li><a href="/my-phones" <?=@$cur3?>>Телефоны</a></li>
					<li><a href="/my-purces" <?=@$cur4?>>Бонусный счет</a></li>
					<li><a href="/my-notifications" <?=@$cur5?>>Уведомления</a></li>
					<!--li><a href="/my-" <?=@$cur6?>>Подписки</a></li-->
				<li><a href="/my-security" <?=@$cur7?>>Конфиденциальность</a></li>
				</ul>
				<img src="pic/cont-left-hr.png" width="148" height="1" />
			</div><!--end of left menu-->
		</div>
<?php
	break;

	case 6: // Страница лента подписок
		echo "
		 <div id=\"left\">
				<div id=\"left-menu\">
					<h1>Мои подписки</h1>
					<img src=\"pic/cont-left-hr.png\" width=\"148\" height=\"1\" />
					<!-- <ul>
						<li><a href=\"#\" class=\"cur\">Показать все</a></li>
					</ul>
					<img src=\"pic/cont-left-hr.png\" width=\"148\" height=\"1\" /> -->
				</div>
			</div>
		";
	break;

	case 7: // Мои желания
		echo "
		 <div id=\"left\">
				<div id=\"left-menu\">
					<h1>Мои желания</h1>
					<img src=\"pic/cont-left-hr.png\" width=\"148\" height=\"1\" />
					<!-- <ul>
						<li><a href=\"#\" class=\"cur\">Мои желания</a></li>
						<li><a href=\"#\">Исполненные желания</a></li>
					</ul>
					<img src=\"pic/cont-left-hr.png\" width=\"148\" height=\"1\" />
					<div class=\"plusblock\">
						<span class=\"clr-but clr-but-green-nb\"><a href=\"#\" id=\"reg-form-but\">Добавить желание</a><sup></sup></span>
						<img src=\"pic/but-green-nb-plus.png\" width=\"26\" height=\"27\" />
					</div> -->
				</div>
			</div>
		";
	break;

	case 8: // Список моих друзей
		echo "
		   <div id=\"left\">
				<div id=\"left-menu\">
					<h1>Мои друзья</h1>
					<img src=\"pic/cont-left-hr.png\" width=\"148\" height=\"1\" />
					<ul>";

		     $GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
		     if($_SESSION['WP_USER']['zvezda'] == 1) $where = " AND krug_id <> 9 "; else $where = " AND krug_id <> 10 ";
             $result = $MYSQL->query("SELECT krug_id, name_".LANG_SITE." name FROM pfx_krugi WHERE krug_id <> 1 $where ORDER BY sort");
             $circle = varr_int(@$varr['circle']); if($circle <= 1) $circle = 2;
             if(is_array($result)){
             	foreach($result as $key=>$value){
             		if($circle == $value['krug_id']) $class = "class=\"cur\""; else $class = "";
             		echo "<li><a href=\"/my-friends?circle=".$value['krug_id']."\" $class>".$value['name']."</a></li>";
             	}
             }

			  echo "</ul>
					<img src=\"pic/cont-left-hr.png\" width=\"148\" height=\"1\" />
				</div>
			</div>
		";
	break;

	case 9: // Мои подарки
		echo "
		 <div id=\"left\">
				<div id=\"left-menu\">
					<h1>Мои подарки</h1>
					<img src=\"pic/cont-left-hr.png\" width=\"148\" height=\"1\" />
					<ul>";
		        $type_id = varr_int(@$_GET['type']);
		        if($type_id == 0) echo "<li><a href=\"/my-gifts\" class=\"cur\">Показать все</a></li>"; else echo "<li><a href=\"/my-gifts\">Показать все</a></li>";
		        $podarki = $USER->ShowType();
                if(is_array($podarki))
	               foreach($podarki as $key=>$value)
	                 if($type_id == $value['id'])
		                echo "<li><a href=\"/my-gifts?type=".$value['id']."\" class=\"cur\">".$value['name']." ".$value['count']."</a></li>";
		             else
		                echo "<li><a href=\"/my-gifts?type=".$value['id']."\">".$value['name']." ".$value['count']."</a></li>";

				echo "</ul>
					<img src=\"pic/cont-left-hr.png\" width=\"148\" height=\"1\" />
				</div>
			</div>
		";
	break;

	case 10: // Мои фотоальбомы
		echo "
		 <div id=\"left\">
				<div id=\"left-menu\">
					<h1>Мои альбомы</h1>
					<img src=\"pic/cont-left-hr.png\" width=\"148\" height=\"1\" />
					<ul>
						<li><a href=\"/my-photoalbums\" class=\"cur\">Все альбомы</a></li>
						<li><a href=\"#\">Комментарии</a></li>
					</ul>
					<img src=\"pic/cont-left-hr.png\" width=\"148\" height=\"1\" />
					<div class=\"plusblock\">
						<span class=\"clr-but clr-but-green-nb\"><a href=\"/my-photoalbums-add\" id=\"reg-form-but\">Добавить альбом</a><sup></sup></span>
						<img src=\"pic/but-green-nb-plus.png\" width=\"26\" height=\"27\" />
					</div>
				</div>
			</div>
		";
	break;

}
?>
			</div>
<script>

</script>
<!--end of #sidebar-->
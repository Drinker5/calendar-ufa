<?php
	if($_SESSION['WP_USER']['balance']<0)$balance='<a href="#">'.LANG_BONUS_PAY.'</a>';
	else $balance=$_SESSION['WP_USER']['balance'].' '.$_SESSION['WP_USER']['balance_mask'];

/*
	switch($_SESSION['WP_USER']['card_type']){
		case 2:$card_img = "user-info-bage-gold.png"; break;
		case 3:$card_img = "user-info-bage-platin.png"; break;
		default:$card_img = "user-info-bage-silver.png"; break;
	}
*/

	$avatar=ShowAvatar(array($_SESSION['WP_USER']['user_wp']),72,72);
	if(is_array($avatar))$avatar=$avatar[0]['avatar'];

	$count=$USER->CountUvedom();
	if($count>0){
		if($count>9)$count='+9';
		else $count='<span class="note purple-note">'.$count.'</span>';
	}
	else $count='';

/*
	$user_menu_type='';
	if($_SESSION['WP_USER']['user_wp']==10000)$user_menu_type.='<tr><td class="av-left"><a href="'.$_SESSION['WP_USER']['user_wp'].'?type=visitors">Гости:</a></td><td><a href="'.$_SESSION['WP_USER']['user_wp'].'?type=visitors">'.$USER->CountVisitor().'</a></td></tr>';
*/

?>
<div id="right">
	<div id="right-menu">
		<div id="right-top"></div><!--end of right-top-->
		<div id="right-mid">
			<p><a href="/<?=$_SESSION['WP_USER']['user_wp']?>"><?=trim($_SESSION['WP_USER']['firstname'].' '.$_SESSION['WP_USER']['lastname'])?></a></p>
			<div class="avatar">
				<a href="/<?=$_SESSION['WP_USER']['user_wp']?>"><img src="<?=$avatar?>" width="72" height="72" /></a>
				<table>
					<tr><td><img src="pic/cont-right-mid-icon-id.png" width="25" height="9" /></td><td><?=$_SESSION['WP_USER']['user_wp']?></td></tr>
					<tr><td><img src="pic/cont-right-mid-icon-cur.png" width="25" height="23" /></td><td><?=$balance?></td></tr>
					<tr><td><img src="pic/cont-right-mid-icon-add.png" width="25" height="23" /></td><td><a href="/my-purces"><?=LANG_TO_RECHARGE?></a></td></tr>
				</table>
			</div><!--end of avatar-->

			<img src="pic/cont-right-mid-hr.png" width="205" height="1" />

			<table class="stats">
				<tr><td><a href="/my-friends"><?=LANG_FRIENDS?></a></td><td align="right"><?=$USER->CountFriends()?></td></tr>
				<tr><td><a href="/my-gifts"><?=LANG_GIFTS?></a></td><td align="right"><?=$USER->CountPodarki()?></td></tr>
				<tr><td><a href="/my-want"><?=LANG_DESIRE?></a></td><td align="right"><?=$USER->CountIHochu()?></td></tr>
				<!--tr><td><a href="#"><?=LANG_CALENDAR?></a></td><td align="right">2</td></tr-->
				<!--tr><td><a href="/my-rss"><?=LANG_SUBSCRIPTION?></a></td><td align="right"><?=$USER->CountLentaAkcia()?></td></tr-->
				<tr><td><a href="/my-photoalbums"><?=LANG_PHOTOALBUMS?></a></td><td align="right"><?=$USER->CountPhotoAlbums($_SESSION['WP_USER']['user_wp'])?></td></tr>
			</table>

			<img src="pic/cont-right-mid-hr.png" width="205" height="1" />

			<div class="right-icons" style="padding:0 68px;">
			<!--div class="right-icons" style="padding:0 25px;"-->
				<a href="/my-notifications" original-title="<?=LANG_NOTIFICATION?>"><img src="pic/cont-right-icon-mail.png" width="41" height="41" /><?=$count?></a>
				<!--a href="#" original-title="<?=LANG_CHAT?>"><img src="pic/cont-right-icon-chat.png" width="41" height="41" /><span class="note purple-note">8</span></a-->
				<!--a href="#" original-title="<?=LANG_PUZZLE?>"><img src="pic/cont-right-icon-puzzle.png" width="41" height="41" /></a-->
				<!--a href="#" original-title="<?=LANG_CREATE_ACTION?>"><img src="pic/cont-right-icon-cal.png" width="41" height="41" /></a-->
			<!--/div>
			<div class="right-icons" style="padding:0 68px;"-->
				<!--a href="/my-invitefriends" original-title="<?=LANG_TO_INVITE_FRIENDS?>"><img src="pic/cont-right-icon-fadd.png" width="41" height="41" /></a-->
				<a href="/my-friends" original-title="<?=LANG_FIND_FRIENDS?>"><img src="pic/cont-right-icon-fsrch.png" width="41" height="41" /></a>
			</div>
		</div><!--end of right-mid-->

		<div id="right-bot">
			<a href="/<?=$_SESSION['WP_USER']['user_wp']?>"><img src="pic/cont-right-bot-icon-home.png" width="13" height="12" /></a><a href="/my-profile"><img src="pic/cont-right-bot-icon-sett.png" width="13" height="12" /></a> <a href="/exit" style="color:#fff;">Выйти</a>
		</div><!--end of right-bot-->
	</div><!--end of right-menu-->
</div>
<?php
/*
".$USER->CountFriens(1)."
".$USER->CountLentaAkciaNew()."
/my-podpiska\">Настройка подписки ".$USER->CountPodpiska()."

<a href=\"/ihere\">".LANG_WHERE_I."?
<a href=\"#\">".LANG_WHO_NEARBY."?
*/
?>
<div id="foter-inner">
   <ul>
	<!-- <li><a href="/<?=$_SESSION['WP_USER']['user_wp']?>" style="margin-top:16px;"><img src="pic/foot-icon-news.png" alt="foot-icon-news" width="42" height="40" /><br />Лента</a></li> -->
	<?php
	  $count = $USER->CountFriends(0,0,1);
	  if($count > 0) $count = "<span class=\"note green-note\">$count</span>"; else $count = "";
	?>
	<li><a href="#" style="margin-top:12px;"><img src="pic/foot-icon-friends.png" alt="foot-icon-friends" width="49" height="46" /><br />Друзья on-line<?=$count?></a></li>
	<li><a href="#" style="margin-top:11px;"><img src="pic/foot-icon-photo.png" alt="foot-icon-photo" width="45" height="48" /><br />Фото друзей<span class="note red-note">8</span></a></li>
	<li><a href="#" style="margin-top:8px;"><img src="pic/foot-icon-puzzle.png" alt="foot-icon-puzzle" width="56" height="51" /><br />Puzzle друзей<span class="note red-note">8</span></a></li>
	<li><a href="#" style="margin-top:8px;"><img src="pic/foot-icon-cal.png" alt="foot-icon-cal" width="40" height="51" /><br />События друзей<span class="note green-note">8</span></a></li>
	<li><a href="#" style="margin-top:11px;"><img src="pic/foot-icon-who.png" alt="foot-icon-who" width="52" height="48" /><br />Кто рядом</a></li>
	<li><a href="#" style="margin-top:12px;"><img src="pic/foot-icon-feet.png" alt="foot-icon-feet" width="27" height="46" /><br />Куда пойти</a></li>
	<!-- <li><a href="#" style="margin-top:16px;"><img src="pic/foot-icon-anons.png" alt="foot-icon-anons" width="43" height="40" /><br />Анонсы заведений<span class="note red-note">8</span></a></li> -->
  </ul>
</div>
<img src="pic/foot-hr.png" alt="foot-hr" width="234" height="1" />
<p><a href="#">Мой кабинет</a> &nbsp;&nbsp; <a href="#">Добавить магазин</a> &nbsp;&nbsp; <a href="#">Как это работает?</a></p>
<script type="text/javascript">
  function WindowPayBalance(user_wp,country_id){
 	var newWin = window.open("<?=pay_url?>?user_wp="+user_wp+"&country_id="+country_id,"winpay","width=900,height=540,resizable=no,scrollbars=no,status=no,toolbar=no,location=no,menubar=no");
 	newWin.focus();	return false;
 }
</script>
<?php
 switch($left_menu){
 	case 0: // Страница пользователя
 	case 1: // Моя страница
 		echo "<script type=\"text/javascript\" src=\"http://maps.google.com/maps/api/js?sensor=true\"></script>";
 	break;

 	case 3: // Страница магазина
 	case 4: // Страница подарка
 	    echo "<script type=\"text/javascript\" src=\"http://maps.google.com/maps/api/js?sensor=true\"></script>";
 		echo "<script type=\"text/javascript\" src=\"http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4e64bfa6122e2e8a\"></script>
              <script type=\"text/javascript\">
                 var addthis_config = {
                   ui_language: '".LANG_SITE."'
                 }
              </script>";
 	break;
 }
?>
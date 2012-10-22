<div id="center">
	<h1><?=$TITLE?></h1>
	<img src="pic/cont-cent-hr.png" alt="cont-cent-hr" width="538" height="1" />
	<div id="idItems">
	 <?php
	  $rows = 30;
	  $friends = $USER->ShowMyFriends($USER_INFO['user_wp'],$rows,'fio_up',0,1,0);
	  if(is_array($friends)){
	  	for($i=0; $i <  count($friends); $i++){
			$arr_users[] = $friends[$i]['user_wp'];
		}
		$avatar = ShowAvatar($arr_users,70,70);
		
		for($i=0; $i <  count($friends); $i++){
			$online = ""; $checkin = "";
			if(@$friends[$i]['online'])  $online = "<span class=\"curstat-green\"></span>";
			if(@$friends[$i]['checkin']) $checkin = "<em></em>";
			echo "
			 <div class=\"myfriend\" rel=\"".$friends[$i]['user_wp']."\">
			   <a href=\"/".$friends[$i]['user_wp']."\" class=\"frlistav\"><img src=\"".$avatar[$i]['avatar']."\" width=\"70\" height=\"70\" />$checkin<span></span></a>
			   $online<a href=\"/".$friends[$i]['user_wp']."\">".trim($friends[$i]['firstname']." ".$friends[$i]['lastname'])."</a><br />
			   <span>".$friends[$i]['town_name'].", ".$friends[$i]['country_name']."</span>";
			 if($friends[$i]['user_wp'] != $_SESSION['WP_USER']['user_wp'])
			   echo "<span class=\"clr-but clr-but-blue-nb\"><sub></sub><a href=\"#\" id=\"reg-form-but\">Действия</a><b></b><sup></sup></span>";
		     echo "</div>
			";
		}
	  }
	 ?>
	</div>
 <div class="clear"></div>
</div>
<script type="text/javascript">
   var page = 2;
   function ShowMyFriends(){
   	 $('#idItems').find('.myfriend:last').after('<div id="loading" style="padding-top:30px; text-align: center;"><img src="/pic/loader_clock.gif"></div>');
     $.ajax({
	   url:'/jquery-showfriends',
	   cache:false,
	   type: 'POST',
	   data: {user:<?=$USER_INFO['user_wp']?>,page:page++},
	   success:function(data){
	   	$('#loading').remove();
	    if($('#idItems').find('.myfriend:last').length == 0){
	    	$('#idItems').html(data);
	    } else {$('#idItems').find('.myfriend:last').after(data);}
	    popupMenu($('.myfriend .clr-but'),'frndopt','/jquery-friendaction','Загрузка…',5,'left');
	   }
	 });
   }

$(document).ready(function(){
   popupMenu($('.myfriend .clr-but'),'frndopt','/jquery-friendaction','Загрузка…',5,'left');
   var scrH = $(window).height();
   $(window).scroll(function(){
     var scro = $(this).scrollTop();
     var scrHP = $('#idItems').height();
     var scrH2 = 0;
     scrH2 = scrH + scro;
     var leftH = scrHP - scrH2;     
     if(leftH < 300 && <?=ceil($USER->CountFriends(0,$USER_INFO['user_wp']) / $rows)?> >= page){
        ShowMyFriends();
     }     
   });
 });
</script>
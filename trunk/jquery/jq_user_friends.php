<?php
$rows    = 30;
$user_wp = varr_int(@$_POST['user']);

$friends = $USER->ShowMyFriends($user_wp,$rows,'fio_up',0,$_POST['page'],0);
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
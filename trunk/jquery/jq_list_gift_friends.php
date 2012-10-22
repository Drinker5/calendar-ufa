<?php
$page = varr_int(@$varr['page']); if($page <= 0) $page = 1;
$star = varr_int(@$varr['star']); if($star != 2) $star = 1;
$fio  = varr_str(@$varr['fio']);
$rows = 15;
if(strlen($fio) < 3){
   if($star == 1){
      $count_friends_all = $USER->CountFriends();
      if($count_friends_all > 0){
         $ostalos = $count_friends_all - $rows*$page;
         if($ostalos <= $count_friends_all){
            $my_friends = $USER->ShowMyFriends($_SESSION['WP_USER']['user_wp'],$rows,'fio_up',0,$page);
            if(is_array($my_friends)){
	           for($i=0; $i <  count($my_friends); $i++){
		           $arr_users[] = $my_friends[$i]['user_wp'];
	           }
	           $avatar = ShowAvatar($arr_users,36,36);
	           for($i=0; $i < count($my_friends); $i++)
		           echo "<div class=\"fr-box\" rel=\"".$my_friends[$i]['user_wp']."\"><img src=\"".$avatar[$i]['avatar']."\" width=\"36\" height=\"36\" class=\"plist\" />".$my_friends[$i]['lastname']."<br />".$my_friends[$i]['firstname']."</div>";
	           if(ceil($count_friends_all / $rows) > $page)
	              echo "<div id=\"next_friends\" class=\"clear\" style=\"text-align:center\"><img class=\"cont-gift-hr\" src=\"pic/cont-gift-hr.png\" width=\"100%\" height=\"1\"><a href=\"#\" onClick=\"ListPioples(".($page+1).",1); return false;\">".LANG_EVEN." (".$ostalos.")</a> </div>";
            }
         }
      }
   }
   elseif($star == 2){
      $count_stars_all = $USER->CountStars();
      if($count_stars_all > 0){
         $ostalos = $count_stars_all - $rows*$page;
         if($ostalos <= $count_stars_all){
            $stars = $USER->ShowStars(0,$rows,$page);
            if(is_array($stars)){
	           for($i=0; $i <  count($stars); $i++){
		           $arr_users[] = $stars[$i]['user_wp'];
	           }
	           $avatar = ShowAvatar($arr_users,36,36);
	           for($i=0; $i < count($stars); $i++)
		           echo "<div class=\"fr-box\" rel=\"".$stars[$i]['user_wp']."\"><img src=\"".$avatar[$i]['avatar']."\" width=\"36\" height=\"36\" class=\"plist\" />".$stars[$i]['lastname']."<br />".$stars[$i]['firstname']."</div>";
	           if(ceil($count_stars_all / $rows) > $page)
	              echo "<div id=\"next_friends\" class=\"clear\" style=\"text-align:center\"><img class=\"cont-gift-hr\" src=\"pic/cont-gift-hr.png\" width=\"100%\" height=\"1\"><a href=\"#\" onClick=\"ListPioples(".($page+1).",2); return false;\">".LANG_EVEN." (".$ostalos.")</a> </div>";
            }
         }
      }
   }
} else {
	$my_friends = $USER->SearchFriends($fio,$star,0);
	if(is_array($my_friends)){
	     for($i=0; $i <  count($my_friends); $i++){
		   $arr_users[] = $my_friends[$i]['user_wp'];
	     }
	     $avatar = ShowAvatar($arr_users,36,36);
	     for($i=0; $i < count($my_friends); $i++)
		     echo "<div class=\"fr-box\" rel=\"".$my_friends[$i]['user_wp']."\"><img src=\"".$avatar[$i]['avatar']."\" width=\"36\" height=\"36\" class=\"plist\" />".$my_friends[$i]['lastname']."<br />".$my_friends[$i]['firstname']."</div>";
      }
}
?>
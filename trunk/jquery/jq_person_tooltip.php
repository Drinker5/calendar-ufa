<?php
if(isset($varr['user_wp'])){
	$user_info = $USER->Info_min($varr['user_wp'],150,150);
	if(is_array($user_info)){
		
	   if($user_info['user_wp'] != $_SESSION['WP_USER']['user_wp']){
	      if($USER->IsFriend($user_info['user_wp'])){
	   	     if($user_info['online']) $link_chat = LANG_CHAT_ON; else $link_chat = LANG_CHAT_OFF;
		     $chat = "<tr><td align=\"center\"><a href=\"javascript:void(0)\" onClick=\"javascript:chatWith('".$user_info['user_wp']."','".trim($user_info['firstname']." ".$user_info['lastname'])."','".$user_info['photo']."')\">$link_chat</a></td></tr>";
	      }
	   }
	   
	   if($user_info['online']) $ico_online = "<div class=\"ic_online_".LANG_SITE."_popup\" style=\"margin-left: 49%;\"></div>"; else $ico_online="";
	   $send_podarok = "<tr><td align=\"center\"><a href=\"/type-5.php?present=".$user_info['user_wp']."&town_id=".$user_info['town_id']."\">".LANG_PRESENT_GIFT."</a></td></tr>";
	   if($user_info['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
	   
		echo "
		  <table>
		   <tr><td>$ico_online<a href=\"/".$user_info['user_wp']."\"><img src=\"".$user_info['photo']."\" width=\"150\" height=\"150\"></a></td></tr>
		   <tr><td align=\"center\">$zvezda ".trim($user_info['firstname']." ".$user_info['lastname'])."</td></tr>
		   <tr><td><hr /></td></tr>
		   ".@$send_podarok."
		   ".@$chat."
		  </table>
		";
		//<tr><td align=\"center\"><a href=\"#\" onClick=\"WindowPayBalance(".$user_info['user_wp'].",0); return false;\">".LANG_TO_RECHARGE."</a></td></tr>
	}
}
?>
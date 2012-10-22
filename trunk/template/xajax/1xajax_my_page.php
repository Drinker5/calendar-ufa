<?php

function ShowLenta($user_wp,$day,$counter){
	global $USER;
	
	//$time_start = get_micro_time();
	
	$user_wp = (int) $user_wp;
	$result = $USER->ShowHistoryLenta($user_wp,$day);
	$day = $GLOBALS['day'];
	$return = "";
	$count_result = 0;
	
	if(is_array($result) && !isset($result['error_id'])){
		$j=0; $count_result = count($result);
		for($i = 0; $i < $count_result; $i++){
			$value = $result[$i];
			if($j == 0){
				$date_prev = MyDataTime($value['data'],'date');
			    if(strtotime($date_prev) == strtotime(MyDataTime(@date(),'date')))
			       $now = " <b><font style=\"color:red\">сегодня</font></b> ";
			    elseif($day == 1) $now = $date_prev."&nbsp;|&nbsp;<b>вчера</b> ";
			    else $now = $date_prev."&nbsp;|&nbsp;($day д. назад)";
			    $return .= "<div class=\"block\"><p class=\"header\">$now&nbsp;&nbsp;&nbsp;</p><ul>";
		    }
		    
		    $j++;
			
		    if($i == $count_result-1) $line = "style=\"border-bottom:none;\""; else $line = "";
		    
			switch($value['deystvie']){
				case 1: // Друзья
				 if($value['user1']['zvezda'] == 1) $zvezda1 = "<div class=\"star-user\"></div>"; else $zvezda1 = "";
				 if($value['user2']['zvezda'] == 1) $zvezda2 = "<div class=\"star-user\"></div>"; else $zvezda2 = "";
				
				 $return .= "
				  <li $line>				   
				     <a class=\"personPopupTrigger\" rel=\"".$value['user1']['user_wp']."\" href=\"/".$value['user1']['user_wp']."\">$zvezda1 ".trim($value['user1']['firstname']." ".$value['user1']['lastname'])."</a>
				    теперь дружит с
				    <div class=\"imgfriend\">
				     <a class=\"personPopupTrigger\" rel=\"".$value['user2']['user_wp']."\" href=\"/".$value['user2']['user_wp']."\"><img src=\"".$value['user2']['photo']."\" width=\"70\" height=\"70\"><br />$zvezda2 ".trim($value['user2']['firstname']." ".$value['user2']['lastname'])."</a>
				   </div>
				  </li>";
				break;
				
				case 2: // Подарок
				 if($value['user2']['user_wp'] > 0){
				 	$wp2 = $value['user2']['user_wp'];
				 	$avatar = ShowAvatar(array($wp2),70,70);
				 	if(is_array($avatar)) $avatar = $avatar[0]['avatar'];
				 	
				 	$link = "
				 	<a class=\"personPopupTrigger\" rel=\"$wp2\" href=\"/$wp2\"><img src=\"".$avatar."\" width=\"70\" height=\"70\"></a>
				 	";
				 } else {$wp2 = ""; $link = "<img src=\"".no_foto."\" width=\"70\" height=\"70\">";}
				 
				 if($value['user1']['sex'] == 1) $end = ""; else $end = "а";				 
				 if($value['user1']['zvezda'] == 1) $zvezda1 = "<div class=\"star-user\"></div>"; else $zvezda1 = "";
				 if($value['user2']['zvezda'] == 1) $zvezda2 = "<div class=\"star-user\"></div>"; else $zvezda2 = "";
				 $return .= "<li $line>
				 <a class=\"personPopupTrigger\" rel=\"".$value['user1']['user_wp']."\" href=\"/".$value['user1']['user_wp']."\">$zvezda1 ".trim($value['user1']['firstname']." ".$value['user1']['lastname'])."</a> сделал$end подарок
				               <table class=\"tblok\">
				                <tr>
				                 <td width=\"30%\"><a href=\"/shop-".$value['podarok']['shop_id'].".php\"><img src=\"".$value['podarok']['shop_logo']."\"></a></td>
				                 <td width=\"5%\"><img src=\"pic/next.gif\"></td>
				                 <td width=\"30%\"><a href=\"/gift-".$value['podarok']['akcia_id'].".php\"><img src=\"".$value['podarok']['photo']."\"></a></td>
				                 <td width=\"5%\"><img src=\"pic/next.gif\"></td>
				                 <td width=\"30%\">$link</td>
				                </tr>
				                <tr>
				                 <td><a href=\"/shop-".$value['podarok']['shop_id'].".php\">в ".$value['podarok']['shop_name']."</a></td>
				                 <td></td>
				                 <td><a href=\"/gift-".$value['podarok']['akcia_id'].".php\">".$value['podarok']['header']."</a></td>
				                 <td></td>
				                 <td style=\"font-size:12px\">".@$zvezda2." ".trim(@$value['user2']['firstname']." ".@$value['user2']['lastname'])."</td>
				                </tr>
				               </table>
				             </li>";
				break;
				
				case 3: // Подписка
				 $logo = ShowLogo(array($value['podpiska']['shop_id']),100,100);
				 if(is_array($logo)) $logo = $logo[0]['logo']; 
				 if($value['podpiska']['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
				 if($value['podpiska']['sex'] == 1) $end = "ся"; else $end = "ась";
				 $return .= "
				 <li $line>
				  <a class=\"personPopupTrigger\" rel=\"".$value['podpiska']['user_wp']."\" href=\"/".$value['podpiska']['user_wp']."\">$zvezda ".trim($value['podpiska']['firstname']." ".$value['podpiska']['lastname'])."</a>
				  подписал$end на 
				 <div class=\"imgpodpiska\"><a href=\"/shop-".$value['podpiska']['shop_id'].".php\"><img src=\"".$logo."\"><br />".$value['podpiska']['shop_name']."</a></div>
				 </li>";
				break;
				
				
				case 4: // Фотоальбом
				 if($value['user']['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
				 if($value['user']['sex'] == 1) $end = ""; else $end = "а";
				 $return .= "
				 <li $line>
				  <a class=\"personPopupTrigger\" rel=\"".$value['user']['user_wp']."\" href=\"/".$value['user']['user_wp']."\">$zvezda ".trim($value['user']['firstname']." ".$value['user']['lastname'])."</a>
				  разместил$end новый фотоальбом  
				 <div class=\"imgpodpiska\"><a href=\"/".$value['user']['user_wp']."?type=photoalbums&album=".$value['photoalbum']['id']."\"><img src=\"".$value['photoalbum']['logo']."\"><br />".$value['photoalbum']['header']."</a></div>
				 </li>";
				break;
				
				case 5: // Мне нравится
				 $photo = ShowFotoAkcia(array($value['akcia_id']),100,100);
				 if(is_array($photo)) $photo = $photo[0]['foto'];
				 if($value['user']['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
				 $return .= "
				 <li $line>
				  <a class=\"personPopupTrigger\" rel=\"".$value['user']['user_wp']."\" href=\"/".$value['user']['user_wp']."\">$zvezda ".trim($value['user']['firstname']." ".$value['user']['lastname'])."</a>
				  нравится 
				 <div class=\"imgpodpiska\"><a href=\"/gift-".$value['akcia_id'].".php\"><img src=\"".$photo."\"><br />".$value['header']."</a></div>
				 </li>";
				break;
				
				case 6: // Хочу себе
				$photo = ShowFotoAkcia(array($value['akcia_id']),100,100);
				if(is_array($photo)) $photo = $photo[0]['foto'];
				if($value['user']['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
				$return .= "
				 <li $line>
				  <a class=\"personPopupTrigger\" rel=\"".$value['user']['user_wp']."\" href=\"/".$value['user']['user_wp']."\">$zvezda ".trim($value['user']['firstname']." ".$value['user']['lastname'])."</a>				  хочет 				  
				   <table class=\"tblok\">
				    <tr>
				     <td width=\"45%\"><a href=\"/gift-".$value['akcia_id'].".php\"><img src=\"".$photo."\"></a></td>
				     <td width=\"10%\"><img src=\"pic/next.gif\"></td>
				     <td width=\"45%\"><a href=\"/shop-".$value['shop_id'].".php\"><img src=\"".$value['shop_logo']."\"></a></td>
				    </tr>
				    <tr>
				     <td><a href=\"/gift-".$value['akcia_id'].".php\">".$value['header']."</a></td>
				     <td></td>
				     <td><a href=\"/shop-".$value['shop_id'].".php\">".$value['shop_name']."</a></td>
				    </tr>
				   </table>
				 </li>";
				break;
				
				case 7: // Комментарии
				$comments = "";
				
				if(isset($value['akcia_id']) && $value['akcia_id'] > 0){ // Комментят акции
				   $comments = "
				   <table class=\"tblok\">
				    <tr>
				     <td width=\"45%\"><a href=\"/gift-".$value['akcia_id'].".php\"><img src=\"".$value['akcia_photo']."\"></a></td>
				     <td width=\"10%\"><img src=\"pic/next.gif\"></td>
				     <td width=\"45%\"><a href=\"/shop-".$value['shop_id'].".php\"><img src=\"".$value['shop_logo']."\"></a></td>
				    </tr>
				    <tr>
				     <td><a href=\"/gift-".$value['akcia_id'].".php\">".$value['header']."</a></td>
				     <td></td>
				     <td><a href=\"/shop-".$value['shop_id'].".php\">".$value['shop_name']."</a></td>
				    </tr>
				    <tr><td colspan=\"3\"><br /><i style=\"font-size:12px\">".$value['msg']."</i></td></tr>
				    <tr><td colspan=\"3\"><br /><a style=\"color:green\" name=\"w_comments\" href=\"/window-comments.php?akcia_id=".$value['akcia_id']."\" title=\"".$value['header']."\">Все комментарии <span id=\"idCountComments2_".$value['akcia_id']."\">(".$USER->CountComments(array('akcia_id'=>$value['akcia_id'])).")</span></a></td></tr>
				   </table>
				   ";
				}
                elseif(isset($value['photo_id']) && $value['photo_id'] > 0){ // Если комментируют ФотоАльбом
                   if($value['user2']['zvezda'] == 1) $zvezda2 = "<div class=\"star-user\"></div>"; else $zvezda2 = "";
				   $comments = "
				   <table class=\"tblok\">
				    <tr>
				     <td width=\"45%\"><a href=\"/".$value['user2']['user_wp']."\"><img src=\"".$value['user2']['photo']."\" width=\"100\" height=\"100\"></a></td>
				     <td width=\"10%\"><img src=\"pic/next.gif\"></td>
				     <td width=\"45%\"><a href=\"/".$value['user2']['user_wp']."?type=photoalbums&album=".$value['album_id']."\"><img src=\"".$value['photo']."\"></a></td>
				     <!-- <td width=\"45%\"><a href=\"".$value['photo_original']."\" name=\"w_photoalbom\" title=\"".$value['header']."\"><img src=\"".$value['photo']."\"></a></td> -->
				    </tr>
				    <tr>
				     <td><a href=\"/".$value['user2']['user_wp']."\">$zvezda2 ".trim($value['user2']['firstname']." ".$value['user2']['lastname'])."</a></td>
				     <td></td>
				     <td></td>
				    </tr>
				    <tr><td colspan=\"3\"><br /><i style=\"font-size:12px\">".$value['msg']."</i></td></tr>
				    <tr><td colspan=\"3\"><br /><a style=\"color:green\" href=\"#\" onClick=\"CommentsPhoto(".$value['photo_id']."); return false;\" title=\"".$value['header']."\">Все комментарии <span id=\"idCountComments2_".$value['photo_id']."\">(".$USER->CountComments(array('photo_id'=>$value['photo_id'])).")</span></a></td></tr>
				   </table>
				   ";
                }
                elseif(isset($value['shop_id']) && $value['shop_id'] > 0){ // Если комментируют магазин				
					$comments = "
					<div class=\"imgpodpiska\"><a href=\"/shop-".$value['shop_id'].".php\"><img src=\"".$value['shop_logo']."\"></a><br />".$value['shop_name']."<br /><i style=\"font-size:12px\">".$value['msg']."</i></div>
					<div class=\"imgpodpiska\"><br /><a style=\"color:green\" href=\"#\" onClick=\"CommentsShop(".$value['shop_id']."); return false;\" title=\"".$value['shop_name']."\">Все комментарии <span id=\"idCountComments2_".$value['shop_id']."\">(".$USER->CountComments(array('shop_id'=>$value['shop_id'])).")</span></a></div>
				   ";
				}
				
				if($value['user']['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
				if($value['user']['sex'] == 1) $end = ""; else $end = "а";
				$return .= "
				 <li $line>
				  <a class=\"personPopupTrigger\" rel=\"".$value['user']['user_wp']."\" href=\"/".$value['user']['user_wp']."\">$zvezda ".trim($value['user']['firstname']." ".$value['user']['lastname'])."</a>
				  прокомментировал$end
				   $comments
				 </li>";
				break;
				
				case 8: // Я здесь
				 if($value['user']['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
				 $return .= "
				 <li $line>
				  <a class=\"personPopupTrigger\" rel=\"".$value['user']['user_wp']."\" href=\"/".$value['user']['user_wp']."\">$zvezda ".trim($value['user']['firstname']." ".$value['user']['lastname'])."</a>
				  находится
				   <div class=\"imgpodpiska\"><a href=\"/shop-".$value['shop_id'].".php\"><img src=\"".$value['shop_logo']."\"><br />".$value['shop_name']."</a><br /><i style=\"font-size:12px\">".$value['shop_adress']['town']." ".$value['shop_adress']['street']." ".$value['shop_adress']['house']."</i></div>
				 </li>";				  
				break;
				
				case 9: // Получил подарок
				  $photo = ShowFotoAkcia(array($value['akcia_id']),100,100);
				  if(is_array($photo)) $photo = $photo[0]['foto'];
				  if($value['user']['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
				$return .= "
				 <li $line>
				  <a class=\"personPopupTrigger\" rel=\"".$value['user']['user_wp']."\" href=\"/".$value['user']['user_wp']."\">$zvezda ".trim($value['user']['firstname']." ".$value['user']['lastname'])."</a>
				  получил 				  
				   <table class=\"tblok\">
				    <tr>
				     <td width=\"45%\"><a href=\"/gift-".$value['akcia_id'].".php\"><img src=\"".$photo."\"></a></td>
				     <td width=\"10%\"><img src=\"pic/next.gif\"></td>
				     <td width=\"45%\"><a href=\"/shop-".$value['shop_id'].".php\"><img src=\"".$value['shop_logo']."\"></a></td>
				    </tr>
				    <tr>
				     <td><a href=\"/gift-".$value['akcia_id'].".php\">".$value['header']."</a></td>
				     <td></td>
				     <td><a href=\"/shop-".$value['shop_id'].".php\">".$value['shop_name']."</a></td>
				    </tr>
				   </table>
				 </li>";
				break;
			}
			
			if(strtotime($date_prev) > strtotime(MyDataTime(@$result[$i+1]['data'],'date'))){
		    	$return .= "</ul></div>";
		    	$j=0;
		    }
		}
		$return .= "</ul></div>";
		
		if($counter < $GLOBALS['day_max']){
		   $return .= "<div id=\"idLinkNext\" class=\"roundedbutton greenbutton\" style=\"float:right\" onClick=\"xajax_ShowLenta($user_wp,".($day+1).",".($counter+1)."); $('#idLinkNext').remove(); $('#lenta_$counter').html('<br /><center>".loading_clock."</center>'); return false;\"><sub></sub><div>Показать еще</div><sup></sup></a></span></div>";
		   $return .= "<div id=\"lenta_$counter\" style=\"float:left; width:100%; color:#a1a0a0; font-size:11px;\"></div>";
		}
	}
	
	
	$objResponse = new xajaxResponse();
	
	if($count_result > 0){ //$return .= "<div>".(get_micro_time()-$time_start)."</div>";
	   if($counter == 2){
	      $objResponse->assign('idLenta', 'innerHTML', $return);
	      if($count_result <= 5) // если действий за сегодня менее или равно 5, то вывести и прошедший день
	         $objResponse->script("xajax_ShowLenta($user_wp,".($day+1).",".($counter+1)."); $('#idLinkNext').remove(); $('#lenta_$counter').html('<br /><center>".loading_clock."</center>');");
	   }
	   else {
	      $objResponse->assign("lenta_".($counter-1), "innerHTML", $return);
	   }
	} else {
		if($user_wp == @$_SESSION['WP_USER']['user_wp'])
		   $newfriend = $USER->ShowNewFriends();
		if(isset($newfriend) && is_array($newfriend)){
			$return = "";
			for($i=0; $i <  count($newfriend); $i++){
			  $arr_users[] = $newfriend[$i]['user_wp'];
		    }
		    $avatar = ShowAvatar($arr_users,80,80);
		    
		    for($i=0; $i <  count($newfriend); $i++){
			   if($USER->OnLine($newfriend[$i]['user_wp'])){				  
			      $ico_online = "<div class=\"ic_online_list_my_friends\"></div>";
			   } else {
				  $ico_online="";
			}
			$return .= "
			 <div class=\"mypage-topblock\">
			  <p>".trim($newfriend[$i]['firstname']." ".$newfriend[$i]['lastname'])."<p>
			  $ico_online<a href=\"/".$newfriend[$i]['user_wp']."\"><img src=\"".$avatar[$i]['avatar']."\" width=\"80\" height=\"80\" class=\"bordered\" /></a>
               <div class=\"friendbuttons\">
			     ".ShowBtnAddFriend($newfriend[$i]['user_wp'],0,1)."
		       </div>
	         </div>
	         <div class=\"clear\"></div>
			";
		   }
		   $objResponse->assign('idLenta', 'innerHTML', $return);
		} else {
		   $return = "<div style=\"float:left; width:95%; padding: 8px 11px; background-color: #F9F6E7; border: 1px solid #D4BC4C;\">Лента событий еще пуста</div>";
		   $objResponse->assign('idLenta', 'innerHTML', $return);
		}
	}
	
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowLenta');
?>
<?php

function ShowLenta($user_wp,$day,$counter,$circle){
	global $USER;
	
	//$time_start = get_micro_time();
	
	$user_wp = (int) $user_wp;
	$result  = $USER->ShowHistoryLenta($user_wp,$day,$circle);
	$day     = $GLOBALS['day'];
	$return  = "";
	$scripts = "";
	$append  = "";
	$count_result = 0;
	
	if(is_array($result) && !isset($result['error_id'])){
		$j=0; $count_result = count($result);
		for($i = 0; $i < $count_result; $i++){
			$value = $result[$i];			
			if($j == 0){
				$date_prev = MyDataTime($value['data'],'date');
			    $return .= "<img src=\"pic/cont-cent-hr.png\" width=\"538\" height=\"1\" />";
		    }
		    
		    $j++;
			
		    if($i == $count_result-1) $line = ""; else $line = "<img src=\"pic/cont-cent-hr.png\" class=\"hr\" width=\"538\" height=\"1\" />";
		    
			switch($value['deystvie']){
				case 1: // Друзья
				 if($value['user1']['zvezda'] == 1) $zvezda1 = "<div class=\"star-user\"></div>"; else $zvezda1 = "";
				 if($value['user2']['zvezda'] == 1) $zvezda2 = "<div class=\"star-user\"></div>"; else $zvezda2 = "";
				 $timer = str_replace("-",",",str_replace(" ",",",str_replace(":",",",$value['data'])));
				 
				 $return .= "
				  <div class=\"post\">
					<div class=\"title\">
						<a class=\"personPopupTrigger\" rel=\"".$value['user1']['user_wp']."\" href=\"/".$value['user1']['user_wp']."\"><img src=\"".$value['user1']['photo']."\" class=\"ava-mini\" width=\"40\" height=\"40\" /></a>
						<div class=\"date\" id=\"date_".$value['id']."\" time=\"$timer\"></div>
						<p><a href=\"/".$value['user1']['user_wp']."\">$zvezda1 ".trim($value['user1']['firstname']." ".$value['user1']['lastname'])."</a> теперь дружит с</p>
						<p><img src=\"pic/cont-cent-icon-friend.png\" width=\"29\" height=\"23\" /></p>
					</div>
					<div class=\"detail\">
						<div><a class=\"personPopupTrigger\" href=\"/".$value['user2']['user_wp']."\" rel=\"".$value['user2']['user_wp']."\"><img src=\"".$value['user2']['photo']."\" width=\"78\" height=\"101\" /></a><br /><a href=\"/".$value['user2']['user_wp']."\">$zvezda2 ".trim($value['user2']['firstname']." ".$value['user2']['lastname'])."</a></div>
					</div>
				  </div>
				 ".$line;
				 $scripts .= "$('#countdown-example').timeUpdate('#date_".$value['id']."','time',60000); ";
				break;
				
				case 2: // Подарок
				 if($value['user2']['user_wp'] > 0){
				 	$wp2 = $value['user2']['user_wp'];
				 	$link = "<a class=\"personPopupTrigger\" rel=\"$wp2\" href=\"/$wp2\"><img src=\"".$value['user2']['photo']."\" width=\"78\" height=\"101\"></a>";
				 } else {$wp2 = ""; $link = "<img src=\"http://83.222.116.202/pictures/tmp/78x101/nofoto-big.jpg\" width=\"78\" height=\"101\">";}
				 
				 if($value['user1']['sex'] == 1) $end = ""; else $end = "а";
				 if($value['user1']['zvezda'] == 1) $zvezda1 = "<div class=\"star-user\"></div>"; else $zvezda1 = "";
				 if($value['user2']['zvezda'] == 1) $zvezda2 = "<div class=\"star-user\"></div>"; else $zvezda2 = "";
				 $timer = str_replace("-",",",str_replace(" ",",",str_replace(":",",",$value['data'])));
				 $return .= "
				 <div class=\"post\">
					<div class=\"title\">
						<a class=\"personPopupTrigger\" href=\"/".$value['user1']['user_wp']."\" rel=\"".$value['user1']['user_wp']."\"><img src=\"".$value['user1']['photo']."\" class=\"ava-mini\" width=\"40\" height=\"40\" /></a>
						<div class=\"date\" id=\"date_".$value['id']."\" time=\"$timer\"></div>
						<p><a href=\"/".$value['user1']['user_wp']."\">$zvezda1 ".trim($value['user1']['firstname']." ".$value['user1']['lastname'])."</a> сделал$end подарок в <a href=\"/shop-".$value['podarok']['shop_id']."\">".$value['podarok']['shop_name']."</a>, ".$value['podarok']['shop_town']."</p>
						<p><img src=\"pic/cont-cent-icon-gift.png\" width=\"17\" height=\"23\" /></p>
					</div>
					<div class=\"detail\">
						<div>$link<br />".@$zvezda2." ".trim(@$value['user2']['firstname']." ".@$value['user2']['lastname'])."<span></span></div>
						<div><a href=\"/gift-".$value['podarok']['akcia_id']."\"><img src=\"".$value['podarok']['photo']."\" width=\"146\" height=\"101\" /><br />".$value['podarok']['header']."</a></div>
					</div>
				 </div>
				 ".$line;
				 $scripts .= "$('#countdown-example').timeUpdate('#date_".$value['id']."','time',60000); ";
				break;
				
				/*case 3: // Подписка
				 $logo = ShowLogo(array($value['podpiska']['shop_id']),100,100);
				 if(is_array($logo)) $logo = $logo[0]['logo']; 
				 if($value['podpiska']['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
				 if($value['podpiska']['sex'] == 1) $end = "ся"; else $end = "ась";
				 $return .= "
				 <li $line>
				  <a class=\"personPopupTrigger\" rel=\"".$value['podpiska']['user_wp']."\" href=\"/".$value['podpiska']['user_wp']."\">$zvezda ".trim($value['podpiska']['firstname']." ".$value['podpiska']['lastname'])."</a>
				  подписал$end на 
				 <div class=\"imgpodpiska\"><a href=\"/shop-".$value['podpiska']['shop_id']."\"><img src=\"".$logo."\"><br />".$value['podpiska']['shop_name']."</a></div>
				 </li>";
				break;*/
				
				
				case 4: // Фотоальбом
				 if($value['user']['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
				 if($value['user']['sex'] == 1) $end = ""; else $end = "а";
				 $timer = str_replace("-",",",str_replace(" ",",",str_replace(":",",",$value['data'])));
				 $return .= "
				  <div class=\"post\">
					<div class=\"title\">
						<a class=\"personPopupTrigger\" rel=\"".$value['user']['user_wp']."\" href=\"/".$value['user']['user_wp']."\"><img src=\"".$value['user']['photo']."\" class=\"ava-mini\" width=\"40\" height=\"41\" /></a>
						<div class=\"date\" id=\"date_".$value['id']."\" time=\"$timer\"></div>
						<p><a href=\"/".$value['user']['user_wp']."\">$zvezda ".trim($value['user']['firstname']." ".$value['user']['lastname'])."</a> разместил$end фотоальбом &laquo;<a href=\"#\">".$value['photoalbum']['header']."</a>&raquo;</p>
						<p><img src=\"pic/cont-cent-icon-photo.png\" width=\"23\" height=\"23\" /></p>
					</div>
					<div class=\"detail\">
						<div class=\"maxidiv\"><a href=\"#\"><img src=\"".$value['photoalbum']['logo']."\" width=\"211\" height=\"163\" /></a></div>";
				    if(is_array($value['photoalbum']['photos']))
				      foreach($value['photoalbum']['photos'] as $key1=>$value1)
						$return .= "<div class=\"minidiv\"><a href=\"#\"><img src=\"".$value1['photo']."\" width=\"99\" height=\"74\" /></a></div>";
						
					$return .= "</div>
					<div class=\"showcom\"><a href=\"#\">Комментариев (12)</a></div>
					<div class=\"clear\"></div>
				 </div>
				 ".$line;
				 $scripts .= "$('#countdown-example').timeUpdate('#date_".$value['id']."','time',60000); ";
					
				 /*$return .= "
				 <li $line>
				  <a class=\"personPopupTrigger\" rel=\"".$value['user']['user_wp']."\" href=\"/".$value['user']['user_wp']."\">$zvezda ".trim($value['user']['firstname']." ".$value['user']['lastname'])."</a>
				  разместил$end новый фотоальбом  
				 <div class=\"imgpodpiska\"><a href=\"/".$value['user']['user_wp']."?type=photoalbums&album=".$value['photoalbum']['id']."\"><img src=\"".$value['photoalbum']['logo']."\"><br />".$value['photoalbum']['header']."</a></div>
				 </li>";*/
				break;
				
				/*case 5: // Мне нравится
				 $photo = ShowFotoAkcia(array($value['akcia_id']),100,100);
				 if(is_array($photo)) $photo = $photo[0]['foto'];
				 if($value['user']['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
				 $return .= "
				 <li $line>
				  <a class=\"personPopupTrigger\" rel=\"".$value['user']['user_wp']."\" href=\"/".$value['user']['user_wp']."\">$zvezda ".trim($value['user']['firstname']." ".$value['user']['lastname'])."</a>
				  нравится 
				 <div class=\"imgpodpiska\"><a href=\"/gift-".$value['akcia_id']."\"><img src=\"".$photo."\"><br />".$value['header']."</a></div>
				 </li>";
				break;*/
				
				case 6: // Хочу себе
				$photo = ShowFotoAkcia(array($value['akcia_id']),78,101);
				if(is_array($photo)) $photo = $photo[0]['foto'];
				if($value['user']['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
				$timer = str_replace("-",",",str_replace(" ",",",str_replace(":",",",$value['data'])));
				$return .= "
				 <div class=\"post\">
					<div class=\"title\">
						<img src=\"".$value['user']['photo']."\" class=\"ava-mini\" width=\"40\" height=\"41\" />
						<div class=\"date\" id=\"date_".$value['id']."\" time=\"$timer\"></div>
						<p><a href=\"#\">$zvezda ".trim($value['user']['firstname']." ".$value['user']['lastname'])."</a> хочет подарок</p>
						<p class=\"post-icon\"><img src=\"pic/cont-cent-icon-check.png\" width=\"24\" height=\"23\" /> <span class=\"clr-arrow clr-arrow-green\"><sub></sub><a href=\"#\">Подарить</a><sup></sup></span></p>
					</div>
					<div class=\"detail\">
						<div><a href=\"/shop-".$value['shop_id']."\"><img src=\"".$value['shop_logo']."\" width=\"188\" height=\"101\" /><br />в ".$value['shop_name']."</a><span></span></div>
						<div><a href=\"/gift-".$value['akcia_id']."\"><img src=\"".$photo."\" width=\"78\" height=\"101\" /><br />".$value['header']."</a></div>
					</div>
				</div>
				".$line;
				$scripts .= "$('#countdown-example').timeUpdate('#date_".$value['id']."','time',60000); ";				
				break;
				
				/*case 7: // Комментарии
				$comments = "";
				
				if(isset($value['akcia_id']) && $value['akcia_id'] > 0){ // Комментят акции
				   $comments = "
				   <table class=\"tblok\">
				    <tr>
				     <td width=\"45%\"><a href=\"/gift-".$value['akcia_id']."\"><img src=\"".$value['akcia_photo']."\"></a></td>
				     <td width=\"10%\"><img src=\"pic/next.gif\"></td>
				     <td width=\"45%\"><a href=\"/shop-".$value['shop_id']."\"><img src=\"".$value['shop_logo']."\"></a></td>
				    </tr>
				    <tr>
				     <td><a href=\"/gift-".$value['akcia_id']."\">".$value['header']."</a></td>
				     <td></td>
				     <td><a href=\"/shop-".$value['shop_id']."\">".$value['shop_name']."</a></td>
				    </tr>
				    <tr><td colspan=\"3\"><br /><i style=\"font-size:12px\">".$value['msg']."</i></td></tr>
				    <tr><td colspan=\"3\"><br /><a style=\"color:green\" name=\"w_comments\" href=\"/window-comments?akcia_id=".$value['akcia_id']."\" title=\"".$value['header']."\">Все комментарии <span id=\"idCountComments2_".$value['akcia_id']."\">(".$USER->CountComments(array('akcia_id'=>$value['akcia_id'])).")</span></a></td></tr>
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
					<div class=\"imgpodpiska\"><a href=\"/shop-".$value['shop_id']."\"><img src=\"".$value['shop_logo']."\"></a><br />".$value['shop_name']."<br /><i style=\"font-size:12px\">".$value['msg']."</i></div>
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
				break;*/
				
				case 8: // Я здесь
				 if($value['user']['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
				 if($value['user']['sex'] == 1) $end = ""; else $end = "а";
				 $timer = str_replace("-",",",str_replace(" ",",",str_replace(":",",",$value['data'])));
				 $return .= "
				  <div class=\"post\">
					<div class=\"title\">
						<img src=\"".$value['user']['photo']."\" class=\"ava-mini\" width=\"40\" height=\"41\" />
						<div class=\"date\" id=\"date_".$value['id']."\" time=\"$timer\"></div>
						<p><a href=\"#\">$zvezda ".trim($value['user']['firstname']." ".$value['user']['lastname'])."</a> был$end замечен$end в <a href=\"/shop-".$value['shop_id']."\">".$value['shop_name']."</a>, ".$value['shop_adress']['town']."</p>
						<p><img src=\"pic/cont-cent-icon-place.png\" width=\"25\" height=\"23\" /></p>
					</div>
					<div class=\"detail\">
						<div id=\"map_$i\" style=\"width:147px; height:101px;\"></div>
						<div><p><a href=\"/shop-".$value['shop_id']."\">".$value['shop_name']."</a><br />".$value['shop_adress']['town'].",<br />".$value['shop_adress']['street']." ".$value['shop_adress']['house']."</p></div>
					</div>
				 </div>
				 ".$line;
				 $scripts .= "$('#countdown-example').timeUpdate('#date_".$value['id']."','time',60000); ";
				 $scripts .= "MapIHere('".$value['shop_adress']['town']." ".$value['shop_adress']['street']." ".$value['shop_adress']['house']."','map_$i'); ";
				break;
				
				/*case 9: // Получил подарок
				  $photo = ShowFotoAkcia(array($value['akcia_id']),100,100);
				  if(is_array($photo)) $photo = $photo[0]['foto'];
				  if($value['user']['zvezda'] == 1) $zvezda = "<div class=\"star-user\"></div>"; else $zvezda = "";
				$return .= "
				 <li $line>
				  <a class=\"personPopupTrigger\" rel=\"".$value['user']['user_wp']."\" href=\"/".$value['user']['user_wp']."\">$zvezda ".trim($value['user']['firstname']." ".$value['user']['lastname'])."</a>
				  получил 				  
				   <table class=\"tblok\">
				    <tr>
				     <td width=\"45%\"><a href=\"/gift-".$value['akcia_id']."\"><img src=\"".$photo."\"></a></td>
				     <td width=\"10%\"><img src=\"pic/next.gif\"></td>
				     <td width=\"45%\"><a href=\"/shop-".$value['shop_id']."\"><img src=\"".$value['shop_logo']."\"></a></td>
				    </tr>
				    <tr>
				     <td><a href=\"/gift-".$value['akcia_id']."\">".$value['header']."</a></td>
				     <td></td>
				     <td><a href=\"/shop-".$value['shop_id']."\">".$value['shop_name']."</a></td>
				    </tr>
				   </table>
				 </li>";
				break;
				*/
			}
			
			if(strtotime($date_prev) > strtotime(MyDataTime(@$result[$i+1]['data'],'date'))){
		    	$j=0;
		    }
		}
		
		if($counter < $GLOBALS['day_max']){
		   $return .= "<div id=\"idLinkNext\" style=\"float:right\" onClick=\"xajax_ShowLenta($user_wp,".($day+1).",".($counter+1).",$circle); $('#idLinkNext').remove(); $('#lenta_$counter').html('<br /><center>".loading_clock."</center><br /><br /><br /><br /><br /><br /><br /><br /><br />'); return false;\">Показать еще</a></div>";
		   $append  = "$('#idLenta').append('<div id=\"lenta_$counter\" style=\"float:left; width:100%;\"></div>'); ";
		} else {
			$return .= "<img src=\"pic/cont-cent-hr.png\" width=\"538\" height=\"1\" />";
			$return .= "<br /><br /><br /><br /><br /><br /><br /><br /><br />";
		}
	}
	
	
	$objResponse = new xajaxResponse();
	
	if($count_result > 0){ //$return .= "<div>".(get_micro_time()-$time_start)."</div>";
	   if($counter == 2){
	      $objResponse->assign('idLenta', 'innerHTML', $return);
	      if($count_result <= 5) // если действий за сегодня менее или равно 5, то вывести и прошедший день
	         $objResponse->script("xajax_ShowLenta($user_wp,".($day+1).",".($counter+1).",$circle); $('#idLinkNext').remove(); $('#lenta_$counter').html('<br /><center>".loading_clock."</center><br /><br /><br /><br /><br /><br /><br /><br /><br />');");
	   }
	   else {
	      $objResponse->assign("lenta_".($counter-1), "innerHTML", $return);
	      if($counter <= 5 && $count_result <= 3) // если действий менее или равно 3, то вывести и прошедший день
	         $objResponse->script("xajax_ShowLenta($user_wp,".($day+1).",".($counter+1).",$circle); $('#idLinkNext').remove(); $('#lenta_$counter').html('<br /><center>".loading_clock."</center><br /><br /><br /><br /><br /><br /><br /><br /><br />');");
	   }
	} else {
		/*if($user_wp == @$_SESSION['WP_USER']['user_wp'])
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
		}*/
		$return = "<img src=\"pic/cont-cent-hr.png\" width=\"538\" height=\"1\" />
		           <div class=\"post\">
		            <div style=\"float:left; width:534px; padding: 8px 11px; background-color: #F9F6E7; border: 1px solid #D4BC4C;\">Лента событий еще пуста</div>
		           </div>";
		$objResponse->assign('idLenta', 'innerHTML', $return);
	}
	
	if($append != "")
	  $objResponse->script($append);
	
	if($scripts != "")
	  $objResponse->script($scripts);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowLenta');
?>
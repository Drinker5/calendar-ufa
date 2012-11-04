<?php
function ShowAlbumsBlock($user_wp,$album,$stamp,$cart){
	$html='
	<div class="album fl_l">
		<a href="" class="stack rotated medium-avatar fl_l"><img src="'.$album['logo'].'" alt="" /></a>
		<div class="content fl_l">
			<div class="text">
				<div class="name">'.$album['header'].'</div>';
	if($album['updated']=='')$html.='<div class="date">добавлен: <b>'.$album['data'].'</b></div>';
	else                     $html.='<div class="date">обновлен: <b>'.$album['updated'].'</b></div>';
	$html.='
			</div>
			<div>
				<b><i class="small-icon icon-photo"></i>'.$album['count_photos'].'</b>';
	if($user_wp==$_SESSION['WP_USER']['user_wp'])$html.='
				<span class="popover-btn actions opacity_link" rel="'.$stamp.'" data-content="'.htmlspecialchars(json_encode($cart)).'">
					<i class="small-icon icon-action"></i>
					Действия
					<i class="small-icon icon-grey-arrow"></i>
				</span>
			</div>
		</div>
	</div>';
	
	return $html;
}

function PhotoAlbumsList($user_wp,$rows=20,$begin=0,$type_id=0,$par='',$what=''){
	global $USER, $MYSQL;
	$GLOBALS['PHP_FILE']=__FILE__;
	$GLOBALS['FUNCTION']=__FUNCTION__;
	$html='<p>У вас пока нет ни одного фотоальбома.</p>';
	$stamp=time();
	$albums=$USER->ShowListPhotoAlbums($user_wp,$rows);
	if($_SESSION['WP_USER']['zvezda'] == 1) $where = " AND krug_id <> 9 "; else $where = " AND krug_id <> 10 ";
	$krugi	= $MYSQL->query("SELECT `krug_id`, `name_".LANG_SITE."` `name` FROM `pfx_krugi` WHERE `show`=1 AND krug_id <> 1 $where ORDER BY sort");
	//$pravo  = $MYSQL->query("SELECT IFNULL(pravo,'') security FROM pfx_users_photos_album  WHERE user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
	//$pravo  = @$pravo[0]['security'];
	//if(strlen($pravo) > 0) {$pravo = unserialize($pravo);} else {$pravo[] = array('krug_id'=>0);} // По умолчанию страница доступна всем
	if(is_array($albums)){
		$html=''; $cart='';
		foreach($albums as $key=>$value){
			if(is_array($value['prava'])){
				for($ki=0; $ki<count($krugi); $ki++){
						if(in_array($krugi[$ki]['krug_id'], $value['prava'])) $krugi[$ki]['checked']=true;
						else                                             	  $krugi[$ki]['checked']=false;
					}
			}
				else $krugi[$ki]['checked']=false;
					
			$cart=array(
				"album-id" 		=> trim($value['album_id']),
				"album-krugi"	=> $krugi,
			);
			
			$html.=ShowAlbumsBlock($user_wp,$value,$stamp,$cart);
		}

		//if(is_array($krugi)){
			//echo "<p><label><input type=\"checkbox\" name=\"circle1\" value=\"0\" id=\"chk_all\" class=\"frnchck\"". str_replace('0','checked',@$pravo[0]['krug_id']).">Все</label></p>";
			//echo "<p><label><input type=\"checkbox\" name=\"circle1\" value=\"0\" id=\"chk_all\" class=\"frnchck\"". str_replace('1','checked',@$pravo[0]['krug_id']).">Только Я</label></p>";
			
		//}
		
		$resultArray=array(
			"html"=>$html,
			"uid" =>$stamp
		);
			

		if($what=='json')echo json_encode($resultArray);
		else{             echo $html;}
	}
}


function ShowAlbumClear($user_wp,$cart){
$html='
<div class="photoalbum group">
	<div class="bordered medium-avatar fl_l">
		<img id="logo" alt="" src="pic/camera.jpg">
	</div>
	<div class="add-album-description wrapped">
		<table>
			<thead></thead>
			<tbody>
				<tr>
					<td>Альбом:</td>
					<td>
						<input id="title" class="bordered" type="text" placeholder="Название альбома..." name="header">
					</td>
				</tr>
				<tr>
					<td>Описание:</td>
					<td>
						<textarea id="albopis" class="bordered" placeholder="Введите описание альбома" name="opis"></textarea>
					</td>
				</tr>
			</tbody>
		</table>';
		if($user_wp==$_SESSION['WP_USER']['user_wp'])$html.='
		<div class="tx_r">
			<span class="popover-btn actionscomm opacity_link" data-content="'.htmlspecialchars(json_encode($cart)).'">
				Разрешить просмотр альбома
				<i class="small-icon icon-grey-arrow"></i>
			</span>
			
			<span class="popover-btn actionscomm opacity_link" data-content="'.htmlspecialchars(json_encode($cart)).'">
				Разрешить комментировать альбом
				<i class="small-icon icon-grey-arrow"></i>
			</span>
		</div>
	</div>
</div>';
	return $html;

}

function PhotoAlbumsEdit($user_wp,$what=''){
	global $USER, $MYSQL;
	$GLOBALS['PHP_FILE']=__FILE__;
	$GLOBALS['FUNCTION']=__FUNCTION__;
	//$albums=$USER->ShowListPhotoAlbums($user_wp);
	if($_SESSION['WP_USER']['zvezda'] == 1) $where = " AND krug_id <> 9 "; else $where = " AND krug_id <> 10 ";
	$krugi	= $MYSQL->query("SELECT `krug_id`, `name_".LANG_SITE."` `name` FROM `pfx_krugi` WHERE `show`=1 AND krug_id <> 1 $where ORDER BY sort");
	
	$html=''; $cart='';
	
	/*PreArray($albums);
	if(is_array($albums)){
		foreach($albums as $key=>$value){
			if(is_array($value['prava'])){
				//for($ki=0; $ki<count($krugi); $ki++){
						if(in_array($krugi[$ki]['krug_id'], $value['prava'])) $krugi[$ki]['checked']=true;
						else                                             	  $krugi[$ki]['checked']=false;
					//}
			}
				//else $krugi[$ki]['checked']=false;
					*/
			$cart=array(
			//	"album-id" 		=> trim($value['album_id']),
				"album-krugi"	=> $krugi,
			);
			
			$html=ShowAlbumClear($user_wp,$cart);
		//}

		//if(is_array($krugi)){
			//echo "<p><label><input type=\"checkbox\" name=\"circle1\" value=\"0\" id=\"chk_all\" class=\"frnchck\"". str_replace('0','checked',@$pravo[0]['krug_id']).">Все</label></p>";
			//echo "<p><label><input type=\"checkbox\" name=\"circle1\" value=\"0\" id=\"chk_all\" class=\"frnchck\"". str_replace('1','checked',@$pravo[0]['krug_id']).">Только Я</label></p>";
			
		//}
		
		$resultArray=array(
			"html"=>$html,
			//"uid" =>$stamp
		);
			

		if($what=='json')echo json_encode($resultArray);
		else{             echo $html;}
	//}
}


?>
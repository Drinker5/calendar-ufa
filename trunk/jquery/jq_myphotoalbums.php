<?php
	function PhotoAlbumsList($user_wp,$rows=20,$begin=0,$type_id=0,$par='',$what=''){
		global $USER, $MYSQL;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$html='<p>У вас пока нет ни одного фотоальбома.</p>';
		$stamp=time();

		$albums=$USER->ShowListPhotoAlbums($user_wp,$rows);
		if(is_array($albums)){
			$html=''; $cart='';
			foreach($albums as $key=>$value){
				$cart=array(
					"album-id" => trim($value['album_id']),
				);

				$html.='
				<div class="album fl_l">
					<div class="bordered medium-avatar fl_l"><img src="'.$value['logo'].'" alt="" /></div>
					<div class="content fl_l">
						<div class="text">
							<div class="name">'.$value['header'].'</div>';
				if($value['updated']=='')$html.='<div class="date">добавлен: <b>'.$value['data'].'</b></div>';
				else                     $html.='<div class="date">обновлен: <b>'.$value['updated'].'</b></div>';
				$html.='
						</div>
						<div>
							<b><i class="small-icon icon-photo"></i>'.$value['count_photos'].'</b>';
				if($user_wp==$_SESSION['WP_USER']['user_wp'])$html.='
							<span class="popover-btn actions opacity_link" rel="'.$stamp.'" data-content="'.htmlspecialchars(json_encode($cart)).'">
								<i class="small-icon icon-action"></i>
								Действия
								<i class="small-icon icon-grey-arrow"></i>
							</span>';
				$html.='
						</div>
					</div>
				</div>';
			}
		}

		$resultArray=array(
			"html"=>$html,
			"uid" =>$stamp
		);

		if($what=='json')echo json_encode($resultArray);
		else             echo $html;
	}
?>
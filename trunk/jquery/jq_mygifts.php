<?php
	function GiftList($rows=10,$begin=0,$type_id=0,$par='',$what=''){
		global $USER, $MYSQL;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$html='<p>Таких подарков у вас еще не было.</p>';
		$stamp=time();

		$myGifts=$USER->ShowPodarki($type_id,0,$par,$rows,$begin);
		if(is_array($myGifts)){
			$html=''; $cart='';

			for($i=0; $i<count($myGifts); $i++){
				$users_arr[]=$myGifts[$i]['from_wp'];
				$akcia_arr[]=$myGifts[$i]['akcia_id'];
				$avatar     =ShowAvatar($users_arr,100,100);
				$photo      =ShowFotoAkcia($akcia_arr,100,100);
			}

			for($i=0; $i<count($myGifts); $i++){
				$users_arr[]=$myGifts[$i]['from_wp'];
				$akcia_arr[]=$myGifts[$i]['akcia_id'];
				$avatar     =ShowAvatar($users_arr,100,100);
				$photo      =ShowFotoAkcia($akcia_arr,100,100);

				$result=$MYSQL->query("SELECT `code`, `podarok`, `msg` FROM `pfx_historypay` WHERE `id`=".$myGifts[$i]['id']);
				if(is_array($result)){
					$result=$result[0];
					$info  =unserialize($result['podarok']);
					$logo  =ShowLogo(array(varr_int($info['shop_id'])),85,60);
					if(is_array($logo))$logo=$logo[0]['logo'];
					$cart=array(
						"shop-name"         => trim($info['shop_name']),
						"shop-type"         => 'Ресторан',
						"path-to-avatar"    => $logo,
						"shop-page-link"    => '/shop-'.varr_int($info['shop_id']),
						"shop-map"          => 'wewqewqe',
						"shop-add-favorite" => 'dsad',
						"gift-code"         => $result['code'],
						"gift-message"      => trim($result['msg'])
					);
				}

				$html.='
				<div class="gift group">
					<div class="image-block fl_l tx_c">
						<div class="gift-avatar image-border bordered big-avatar">
							<a href="/gift-'.$myGifts[$i]['akcia_id'].'"><img src="'.$photo[$i]['foto'].'" class="what" alt="" /></a>
						</div>
						<a href="/gift-'.$myGifts[$i]['akcia_id'].'">'.$myGifts[$i]['header'].'</a>
					</div>
					<span class="from">от</span>
					<div class="image-block fl_l tx_c">
						<div class="image-border bordered big-avatar">
							<a href="/'.$myGifts[$i]['from_wp'].'"><img src="'.$avatar[$i]['avatar'].'" width="100" height="100" class="who" alt="" /></a>
						</div>
						<a href="/'.$myGifts[$i]['from_wp'].'">'.$myGifts[$i]['from_fio'].'</a>
					</div>
					<div class="info fl_l">
						<p><span class="date-label fl_l">Дата покупки:</span><span class="date group">'.$myGifts[$i]['datapay'].'</span></p>';
				if($myGifts[$i]['status']=='recieved')$html.='<p><span class="date-label fl_l">Получен:</span><span class="date group">'.$myGifts[$i]['dataend'].'</span></p>';
				elseif($myGifts[$i]['status']=='new') $html.='<p><span class="date-label fl_l">Получить до:</span><span class="date group">'.$myGifts[$i]['dataend'].'</span></p>';
				if($myGifts[$i]['status']=='new'){
					$html.='<div class="counter group"><div class="y_counter"><span class="c_name">Осталось дней</span>';
					$daysStr=strval($myGifts[$i]['days_end'][2]);
					for($d=0; $d<strlen($daysStr); $d++){
						$html.='<span class="c_digit">'.$daysStr[$d].'</span>';
					}
					$html.='</div></div>';
				}
				$html.='
						<a href="#" class="all-info-link popover-btn opacity_link" data-id="1" rel="'.$stamp.'" data-content="'.htmlspecialchars(json_encode($cart)).'">
							<i class="small-icon icon-info"></i>
							Показать всю информацию
							<i class="small-icon icon-grey-arrow"></i>
						</a>
					</div> <!-- /.info -->
					<div class="actions wrapped">
						<ul>
							<li><a href="#"><i class="small-icon icon-5"></i>Ответный подарок</a></li>
							<li><a href="#"><i class="small-icon icon-review"></i>Поблагодарить</a></li>
							<li><a href="#"><i class="small-icon icon-invite"></i>Пригласить</a></li>
						</ul>
					</div>
				</div> <!-- /.gift -->
				<div class="separator"></div>';
			}
		}

		$resultArray=array(
				"html"=>$html,
				"uid" =>$stamp
		);

		if($what=='json')echo json_encode($resultArray);
		else             echo $html;
	}

	if(isset($_POST['list'])){
		GiftList($_POST['items'],$_POST['list'],$_POST['type_id'],$_POST['par'],'json');
	}
?>
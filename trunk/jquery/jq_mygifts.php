<?php
	function GiftList($rows=10,$begin=0,$type_id=0,$par='',$what=''){
		global $USER, $MYSQL, $SHOP;
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

				$result=$MYSQL->query("SELECT `pfx_historypay`.`code`, `pfx_historypay`.`podarok`, `pfx_historypay`.`msg`, `pfx_shops_adress`.`id` `address_id` FROM `pfx_historypay` INNER JOIN `pfx_shops_adress` ON `pfx_historypay`.`shop_id`=`pfx_shops_adress`.`shop_id` WHERE `pfx_historypay`.`id`=".$myGifts[$i]['id']);
				if(is_array($result)){
					$result=$result[0];
					$info  =unserialize($result['podarok']);
					$logo  =ShowLogo(array(varr_int($info['shop_id'])),100,100,true);
					if(is_array($logo))$logo=$logo[0]['logo'];
					$cart=array(
						"shop-name"         => trim($info['shop_name']),
						"shop-type"         => 'Ресторан',
						"path-to-avatar"    => $logo,
						"shop-page-link"    => '/shop-'.varr_int($info['shop_id']),
						"shop-address-id"   => varr_int($result['address_id']),
						"shop-if"           => $SHOP->isFavorite(array(varr_int($result['address_id'])))?'<a href="javascript:;" onclick="FavAction('.varr_int($result['address_id']).')"><i class="small-icon icon-favorite-place-green"></i>Любимое место</a>':'<a href="javascript:;" onclick="FavAction('.varr_int($result['address_id']).')"><i class="small-icon icon-favorite-place"></i>Добавить в любимые места</a>',
						"shop-map"          => 'wewqewqe',
						"gift-code"         => $result['code'],
						"gift-message"      => trim($result['msg'])
					);
				}

				$html.='
				<div class="gift group">
					<div class="fl_l info_block">
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
						<span class="where">где</span>

						<div class="image-block fl_l tx_c">
							<div class="image-border bordered big-avatar">
								<img src="'.$cart['path-to-avatar'].'" width="100" height="100" class="who" alt="">
							</div>
							<a href="'.$cart['shop-page-link'].'">'.$cart['shop-name'].'</a>
						</div>

						<div class="info fl_l">
							<p><div class="arrow-box">Код подарка</div> <span class="green big_text">  '.$cart['gift-code'].'</span></p><br />
							<p><span class="date-label fl_l">Дата покупки:</span><span class="date group">'.$myGifts[$i]['datapay'].'</span></p>';
						if($myGifts[$i]['status']=='recieved')$html.='<p><span class="date-label fl_l">Получен:</span><span class="date group">'.$myGifts[$i]['dataend'].'</span></p>';
						elseif($myGifts[$i]['status']=='new') $html.='<p><span class="date-label fl_l">Получить до:</span><span class="date group">'.$myGifts[$i]['dataend'].'</span></p>';
						if($myGifts[$i]['status']=='new'){
							$html.='<p>
								<span class="c_name">Осталось дней:   </span>
								<span class="c_digit">  <i class="small-icon icon-clock"></i> '.$myGifts[$i]['days_end'][2].'</span>
							</p>';
						}
						$html.='
						</div> <!-- /.info -->';

						if(strlen($cart['gift-message'])>0)$html.='<div class="cleared"><span class="green">Поздравительное сообщение: </span> '.$cart['gift-message'].'</div>';
						$html.='
					</div>
					<div class="actions wrapped">
						<ul>
							<li><a href="/type-5" class="tipE" original-title="Отправить подарок"><i class="small-icon icon-gift active"></i></a></li>
							<li><a class="tipE thank-link popover-btn" href="#" original-title="Отправить сообщение"><i class="small-icon icon-review active"></i></a></li>
							<li><a class="tipE" href="#" original-title="Пригласить"><i class="small-icon icon-invite active"></i></a></li>
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
<?php
	function countGifts($type_id,$gr_id,$category=0,$currency=2,$cF='7500',$cT='30000',$name='',$region='',$mR=false,$mP=false,$oA=false){
		global $MYSQL, $AKCIANAME, $SHOPNAME;

		$GLOBALS['PHP_FILE'] = __FILE__;
		$GLOBALS['FUNCTION'] = __FUNCTION__;

		$type_id = varr_int($type_id);
		$gr_id   = varr_int($gr_id);
		$category= varr_int($category);
		$currency= varr_int($currency);
		$inner   = '';
		$f       = '';
		$w       = array();

		$tbakcia       = "pfx_akcia";
		$tbtype        = "pfx_type";
		$tbcurrency    = "pfx_currency";
		$tbshops       = "pfx_shops";
		$tbshopsadr    = "pfx_shops_adress";
		$tbcountryshop = "pfx_country_shops";
		$tbplaces      = "pfx_users_places";

		$w[]=$tbakcia.'.`idtype`='.$type_id;
		$w[]=$tbakcia.'.`amount` BETWEEN '.varr_int($cF).' AND '.varr_int($cT);

		//if(isset($_SESSION['KLIENT']))
		//	$w[]=$tbakcia.'.`klient_id`='.$_SESSION['KLIENT']['id'];
		//else
		//	$w[]=$tbakcia.'.`moderator`=1';

		//if($category>0)
		//	$w[]='';

		if($currency>0)
			$w[]=$tbakcia.'.`currency_id`='.$currency;

		if(!empty($name) and strlen(trim($name))>0)
			$w[]=$tbakcia.'.`header` LIKE \'%'.mysql_real_escape_string($name).'%\'';

		if($mR)
			$w[]=$tbcountryshop.'.`country_id`='.(int)$_SESSION['TOWN_ID'];
		//elseif(!empty($region))
		//	$w[]=$tbakcia.'.`country_id`='.(int)$region;

		if($mP)
		{
			$inner.="INNER JOIN $tbshopsadr ON $tbshopsadr.shop_id = $tbshops.id
					 INNER JOIN $tbplaces ON $tbplaces.address = $tbshopsadr.id\r\n";
			$w[]=$tbplaces.'.`user_wp`='.(int)$_SESSION['WP_USER']['user_wp'];
		}

		if($oA)
			$w[]=$tbakcia.'.`discount`<>0';

		if($w)
			$f='WHERE '.implode(' AND ',$w);

		$result = $MYSQL->query("SELECT Count(*)
			                 	 FROM $tbakcia
			                 	 INNER JOIN $tbtype  ON $tbtype.id  = $tbakcia.idtype
			                 	 INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
			                 	 INNER JOIN $tbcountryshop ON $tbcountryshop.shop_id = $tbshops.id
			                 	 $inner
			                 	 $f");

		return $result[0]['count'];
	}

	function searchGifts($type_id,$gr_id,$rows,$begin=0,$order=1,$what='',$category=0,$currency=2,$cF='7500',$cT='30000',$name='',$region='',$mR=false,$mP=false,$oA=false){
		global $MYSQL, $AKCIANAME, $SHOPNAME;

		$GLOBALS['PHP_FILE'] = __FILE__;
		$GLOBALS['FUNCTION'] = __FUNCTION__;

		$type_id = varr_int($type_id);
		$gr_id   = varr_int($gr_id);
		$rows    = varr_int($rows);
		$begin   = varr_int($begin);
		$order   = varr_int($order);
		$category= varr_int($category);
		$currency= varr_int($currency);
		$inner   = '';
		$f       = '';
		$html    = 'Список подарков пуст.';
		$w       = array();

		$tbakcia       = "pfx_akcia";
		$tbtype        = "pfx_type";
		$tbcurrency    = "pfx_currency";
		$tbshops       = "pfx_shops";
		$tbshopsadr    = "pfx_shops_adress";
		$tbcountryshop = "pfx_country_shops";
		$tbplaces      = "pfx_users_places";

		$w[]=$tbakcia.'.`idtype`='.$type_id;
		$w[]=$tbakcia.'.`amount` BETWEEN '.varr_int($cF).' AND '.varr_int($cT);

		//if(isset($_SESSION['KLIENT']))
		//	$w[]=$tbakcia.'.`klient_id`='.$_SESSION['KLIENT']['id'];
		//else
		//	$w[]=$tbakcia.'.`moderator`=1';

		//if($category>0)
		//	$w[]='';

		if($currency>0)
			$w[]=$tbakcia.'.`currency_id`='.$currency;

		if(!empty($name) and strlen(trim($name))>0)
			$w[]=$tbakcia.'.`header` LIKE \'%'.mysql_real_escape_string($name).'%\'';

		if($mR)
			$w[]=$tbshopsadr.'.`adress`='.(int)$_SESSION['WP_USER']['town_name'];
		elseif(!empty($region) and strlen(trim($region))>0)
			$w[]=$tbshopsadr.'.`adress`='.(int)$region;

		if($mR or $mP or (!empty($region) and strlen(trim($region))>0))
			$inner.="
				                 INNER JOIN $tbshopsadr ON $tbshopsadr.shop_id = $tbshops.id";

		if($mP)
		{
			$inner.="
				                 INNER JOIN $tbplaces ON $tbplaces.address = $tbshopsadr.id";
			$w[]=$tbplaces.'.`user_wp`='.(int)$_SESSION['WP_USER']['user_wp'];
		}

		if($oA)
			$w[]=$tbakcia.'.`discount`<>0';

		if($w)
			$f='WHERE '.implode(' AND ',$w);

		switch(@$order){
			case 4:
				$by='ORDER BY '.$tbakcia.'.`presented` ASC';
			break;
			case 3:
				$by='ORDER BY '.$tbakcia.'.`amount` ASC';
			break;
			case 2:
				$by='ORDER BY '.$tbakcia.'.`amount` DESC';
			break;
			case 1:
			default:
				$by='ORDER BY '.$tbakcia.'.`header`';
			break;
		}

		$result = $MYSQL->query("SELECT $tbakcia.id, $tbakcia.shop_id, $tbakcia.header, IFNULL($tbakcia.discdata1,'0000-00-00') datastart, IFNULL($tbakcia.discdata2,'0000-00-00') dataend, $tbakcia.discount, $tbakcia.amount, $tbakcia.currency_id, IFNULL($tbtype.dogovor,0) dogovor, $tbshops.name shop_name
				                 FROM $tbakcia
				                 INNER JOIN $tbtype  ON $tbtype.id  = $tbakcia.idtype
				                 INNER JOIN $tbshops ON $tbshops.id = $tbakcia.shop_id
				                 INNER JOIN $tbcountryshop ON $tbcountryshop.shop_id = $tbshops.id
				                 $inner
				                 $f
				                 $by
				                 LIMIT $begin,$rows");

		if(is_array($result)){
			$html='';

			foreach($result as $key=>$akcia){

		        $currency = $MYSQL->query("SELECT currency, mask FROM $tbcurrency WHERE id = ".(int)$akcia['currency_id']);
		        if(is_array($currency))
		        	$currency = $currency[0]['mask'];
		        else
		        	$currency = '';

				$photo=ShowFotoAkcia(array($akcia['id']),100,100);

				$h=htmlspecialchars(stripslashes(trim($akcia['header'])));
				$n=htmlspecialchars(stripslashes(trim($akcia['shop_name'])));

				$html.='
                    <div class="fl_l '.($akcia['discount']>0?'action ':'').'gift-list-item wrapped tx_c p_r">
                        <a href="/gift-'.$akcia['id'].'" class="bordered big-avatar p_r" title="'.$h.'">
                            <img src="'.@$photo[0]['foto'].'" alt="'.$h.'" width="100" height="100">
                            <div class="tx_c price">
                                '.round($akcia['discount']>0?$akcia['amount']*(100-$akcia['discount'])/10000:$akcia['amount']/100).' '.$currency.'
                            </div>
                        </a>
                        <div class="name wrapped">'.(strlen($h)<=11?$h:mb_substr($h,'0',11,'UTF-8').'...').'</div>
                        <div class="shop wrapped">'.(strlen($n)<=16?$n:mb_substr($n,'0',16,'UTF-8').'...').'</div>
                        '.($akcia['discount']>0?'
                        <div class="discount">
                            <div class="discount-inner">
                                '.$akcia['discount'].'%
                            </div>
                        </div>':'').'
                    </div>';
			}
		}

		$resultArray=array(
			"html"=>$html,
			"max"=>ceil(countGifts($type_id,$gr_id,$category,$currency,$cF,$cT,$name,$region,$mR,$mP,$oA)/$rows),
		);

		if($what=='json')echo json_encode($resultArray);
		else             echo $html;
	}

	function catArray(){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
		$GLOBALS['FUNCTION'] = __FUNCTION__;

		$cat=$MYSQL->query("SELECT name_ru name, menu_level, menu_id FROM `pfx_categories`");
		if(is_array($cat))
		{
			$catFull=array();
			foreach($cat as $key=>$value)
			{
				if($value['menu_level']==0)
					$catFull[$value['menu_id']]['mainname']=$value['name'];
				else
					$catFull[$value['menu_level']]['sub'][]=array('key'=>$value['menu_id'],'name'=>$value['name']);
			}
		}
		return $catFull;
	}

	if(isset($_POST['list'])){
		searchGifts($_POST['type_id'],$_POST['gr_id'],$_POST['items'],$_POST['list'],$_POST['order'],'json',$_POST['cat'],$_POST['currency'],$_POST['cFrom']*100,$_POST['cTo']*100,$_POST['name'],$_POST['region'],$_POST['mR'],$_POST['mP'],$_POST['oA']);
	}
?>
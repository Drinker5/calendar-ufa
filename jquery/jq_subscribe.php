<?php
//!Подписаться
	if(isset($_POST['subscribe'])){
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbusers_deystvie   ="pfx_users_deystvie";
		$_POST['subscribe'] =(int)$_POST['subscribe'];

		$podpiska=$MYSQL->query("SELECT Count(*) FROM `pfx_podpiska` WHERE `user_wp`=".(int)$_SESSION['WP_USER']['user_wp']." AND `shop_id`=".$_POST['subscribe']);
		if(is_array($podpiska) && $podpiska[0]['count']==0){
			$result=$MYSQL->query("
				SELECT `pfx_cat_to_shop`.`cat_id`
				FROM `pfx_cat_to_shop`
				INNER JOIN `pfx_categories` ON `pfx_categories`.`menu_id`=`pfx_cat_to_shop`.`cat_id`
				WHERE `pfx_categories`.`menu_level` <> 0 AND `pfx_cat_to_shop`.`shop_id`=".$_POST['subscribe']
			);

			if(is_array($result) && count($result)>0){
				$podpiska_id=$MYSQL->query("INSERT INTO `pfx_podpiska` (`data`, `user_wp`, `shop_id`, `lenta`, `rss`, `email`, `sms`) VALUES (now(), ".(int)$_SESSION['WP_USER']['user_wp'].", ".$_POST['subscribe'].", 1, 1, 1, 0)");
				$MYSQL->query("INSERT INTO `".$tbusers_deystvie."` (`data_add`, `user_wp`, `deystvie`, `id_deystvie`) VALUES (now(), ".(int)$_SESSION['WP_USER']['user_wp'].", 3, ".$podpiska_id.")");

				foreach($result as $key=>$value){
					$MYSQL->query("INSERT INTO `pfx_podpiska_groups` (`podpiska_id`, `group_id`) VALUES (".$podpiska_id.", ".$value['cat_id'].")");
				}

				$types=$MYSQL->query("
					SELECT DISTINCT `pfx_type`.`id` `type_id`
					FROM `pfx_akcia`
					INNER JOIN `pfx_type` ON `pfx_type`.`id`=`pfx_akcia`.`idtype`
					WHERE `pfx_akcia`.`shop_id`=".$_POST['subscribe']." AND `pfx_type`.`active`=1 AND `pfx_akcia`.`del`<>1
					GROUP BY `pfx_type`.`id`
				");

				if(is_array($types)){
					foreach($types as $key=>$value){
						$MYSQL->query("INSERT INTO `pfx_podpiska_type` (`podpiska_id`, `type_id`) VALUES (".$podpiska_id.", ".$value['type_id'].")");
					}
				}

				echo 'ok';
			}
		}
	}


//!Отписаться
	if(isset($_POST['unsubscribe'])){
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbusers_deystvie   ="pfx_users_deystvie";

		$result=$MYSQL->query("SELECT `id` FROM `pfx_podpiska` WHERE `user_wp`=".(int)$_SESSION['WP_USER']['user_wp']." AND `shop_id`=".(int)$_POST['unsubscribe']);
		if(is_array($result)){
			$MYSQL->query("DELETE FROM `pfx_podpiska` WHERE `id`=".(int)$result[0]['id']);
			$MYSQL->query("DELETE FROM `pfx_podpiska_groups` WHERE `podpiska_id`=".(int)$result[0]['id']);
			$MYSQL->query("DELETE FROM `pfx_podpiska_type` WHERE `podpiska_id`=".(int)$result[0]['id']);
			$MYSQL->query("DELETE FROM `".$tbusers_deystvie."` WHERE `user_wp`=".(int)$_SESSION['WP_USER']['user_wp']." AND `id_deystvie`=".(int)$result[0]['id']);
			echo 'ok';
		}
	}


//!Список подписок
	function SubscribesList($rows=12,$begin=0,$what=''){
		global $USER;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		$podpiska=$USER->ShowPodpiska(0,$rows,100,100,$begin);
		if(is_array($podpiska)){
			$stamp=time();
			$html='';
			foreach($podpiska as $key=>$value){
				$cart=array(
					"shop-name"        =>$value['shop_name'],
					"path-to-avatar"   =>$value['shop_logo_mini'],
					"shop-page-link"   =>"/shop-".$value['shop_id'],
					"shop-map"         =>"yandex.ru",
					"shop-add-favorite"=>$key
				);

				$html.='<div class="subscr_block fl_l"><div class="bordered big-avatar fl_l"><a href="/shop-'.$value['shop_id'].'"><img src="'.$value['shop_logo'].'" /></a></div><div class="subscr_content wrapped"><a class="name popover-btn" href="#" rel="'.$stamp.'" data-content="'.htmlspecialchars(json_encode($cart)).'">'.$value['shop_name'].'</a><br />';
				foreach($value['shop_groups'] as $key2=>$value2){
					$html.='<span class="section">'.$value2['name'].'</span><br />';
				}
				$html.='<a href="#" class="delete_subscr opacity_link del_subscribe" data-shop="'.$value['shop_id'].'"><i class="small-icon icon-delete"></i> Удалить</a></div></div>';
			}

			$resultArray=array(
				"html"=>$html,
				"uid" =>$stamp
			);

			if($what=='json')echo json_encode($resultArray);
			else             echo $html;
		}
	}

	if(isset($_POST['list'])){
		SubscribesList($_POST['items'],$_POST['list'],'json');
	}
?>
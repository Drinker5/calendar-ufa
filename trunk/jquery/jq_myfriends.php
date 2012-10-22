<?php
//Блок с информацие о друге
	function ShowFriendBlock($avatar, $friend, $stamp, $cart){
		$html='
		<div class="friend-item fl_l">
			<a class="bordered medium-avatar fl_l" href="/'.$friend['user_wp'].'"><img src="'.$avatar.'" alt="" /></a>
			<div class="content wrapped">'.OnlineStatus($friend['status_chat']).'
				<span class="name"><a href="/'.$friend['user_wp'].'">'.$friend['firstname'].' '.$friend['lastname'].'</a></span>
				<br />
				<span class="place">'.$friend['country_name'].', '.$friend['town_name'].'</span>
			</div>
			<span class="popover-btn my-friend-actions opacity_link" rel="'.$stamp.'" data-content="'.htmlspecialchars(json_encode($cart)).'">
				<i class="small-icon icon-action"></i> Действия <i class="small-icon icon-grey-arrow"></i>
			</span>
		</div>';
		return $html;
	}

//Список людей
	function ShowPeopleList($rows=30,$begin=0,$online=0,$circle=0,$what=''){
		global $USER, $MYSQL;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$html='';
		$stamp=time();

		$friends=$USER->ShowMyFriends(0,$rows,'fio_up',$online,$begin,$circle);
		if(is_array($friends)){
			$html=''; $cart='';
			$friends_wp=array();//Формируем масив для запроса аватарок
			foreach($friends as $k=>$v)$friends_wp[]=$v['user_wp'];
			$avatars=ShowAvatar($friends_wp,70,70,true);//Запрос аватарок каждого друга
			$krugi=$MYSQL->query("SELECT `krug_id`, `name_".LANG_SITE."` `name` FROM `pfx_krugi` WHERE `show`=1");
			foreach($friends as $k=>$v){
				for($ki=0; $ki<count($krugi); $ki++){
					if(is_array($v['krugi'])){
						if(in_array($krugi[$ki]['krug_id'], $v['krugi']))$krugi[$ki]['checked']=true;
						else																						$krugi[$ki]['checked']=false;
					}
					else $krugi[$ki]['checked']=false;
				}

				$cart=array(
					"friend-id"   =>$v['fid'],
					"friend-wp"   =>$v['user_wp'],
					"friend-krugi"=>$krugi,
					"friend-name" =>$v['firstname'].' '.$v['lastname'],
				);
				$html.=ShowFriendBlock($avatars[$k]['file'],$v,$stamp,$cart);
			}
		}

		$resultArray=array(
				"html"=>$html,
				"uid" =>$stamp
		);

		if($what=='json')echo json_encode($resultArray);
		else{             echo $html;}
	}

	if(isset($_POST['fio'])){
		$search=varr_str(@$_POST['fio']);

		if(strlen($search)>2){
			$html='';
			$stamp=time();
			$friends = $USER->SearchFriends($search,1,0,1); //$_POST['circle']
			if(is_array($friends)){
				for($i=0; $i <  count($friends); $i++){
					$arr_users[] = $friends[$i]['user_wp'];
				}
				$avatar = ShowAvatar($arr_users,70,70);
				for($i=0; $i <  count($friends); $i++){
					$friend = $USER->Info_min($friends[$i]['user_wp'],0,0);
					$checkin = "";
					if(@$friends[$i]['checkin']) $checkin = "<em></em>";
					$cart=array(
						"friend-id"  => $friends[$i]['fid'],
						"friend-wp"  => $friends[$i]['user_wp'],
						"friend-name"=> $friends[$i]['firstname'].' '.$friends[$i]['lastname'],
					);
					$html.=ShowFriendBlock($avatar[$i]['avatar'], $friend, $stamp, $cart);
				}
			}
			$resultArray=array(
				"html"=>$html,
				"uid" =>$stamp
			);

			echo json_encode($resultArray);
		}

		elseif(strlen($search)==0){
			ShowPeopleList($_POST['items'], $_POST['list'], $_POST['online'], $_POST['circle'], 'json');
		}
	}
?>
<?php
//Блок с информацие о друге
	function ShowFriendBlock($user_wp, $avatar, $friend, $stamp, $cart){
		global $USER;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$html='
		<div class="friend-item fl_l" id="u'.$friend['user_wp'].'">
			<a class="bordered medium-avatar fl_l" href="/'.$friend['user_wp'].'"><img src="'.$avatar.'" alt="" /></a>
			<div class="content wrapped">'.OnlineStatus($friend['status_chat']).'
				<span class="name"><a href="/'.$friend['user_wp'].'">'.$friend['firstname'].' '.$friend['lastname'].'</a></span>
				<br />
				<span class="place">'.$friend['country_name'].', '.$friend['town_name'].'</span>
			</div>';
		if($USER->IsFriend($friend['user_wp'])){
			$html.='
				<div class="tools_block hide absolute tx_r">
					<span class="fl_l">
						<a href="/type-5" class="opacity_link"><i original-title="Сделать подарок" class="tipN active small-icon icon-gift"></i></a>
						<a href="#" class="opacity_link"><i original-title="Написать сообщение" class="tipN active small-icon icon-chat"></i></a>
						<a href="#" class="opacity_link"><i original-title="Пригласить" class="tipN active small-icon icon-invite"></i></a>
					</span>';
			if($friend['user_wp']!=$_SESSION['WP_USER']['user_wp']){
				$html.='<span class="popover-btn ';
				if($USER->IsFriend($friend['user_wp'])){
					if($user_wp!=$_SESSION['WP_USER']['user_wp'])$html.='my-friend-actions-short';
					else                                         $html.='my-friend-actions';
				}
				else                                   $html.='find-friend-actions';
				$html.=' opacity_link" rel="'.$stamp.'" data-content="'.htmlspecialchars(json_encode($cart)).'"><i class="small-icon icon-settings"></i></span>';
			}
			$html.='</div>';
		}
		else $html.='<div class="tools_block hide absolute tx_r">
                            <span class="fl_l">
                               <a href="#" class="add_new_friend opacity_link" data-user="'.$friend['user_wp'].'" data-name="'.$friend['firstname'].' '.$friend['lastname'].'"><i original-title="Добавить в друзья" class="tipN active small-icon icon-add-friend"></i></a>
                                <a href="#" class="opacity_link"><i original-title="Сделать подарок" class="tipN active small-icon icon-gift"></i></a>
                                <a href="#" class="opacity_link"><i original-title="Написать сообщение" class="tipN active small-icon icon-chat"></i></a>
                                <a href="#" class="opacity_link"><i original-title="Пригласить" class="tipN active small-icon icon-invite"></i></a>
                            </span>
                        </div>';
    $html.='</div>';
		return $html;
	}

//Список людей
	function ShowPeopleList($user_wp,$rows=30,$begin=0,$online=0,$circle=0,$what=''){
		global $USER, $MYSQL;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$html='';
		$stamp=time();

		$friends=$USER->ShowMyFriends($user_wp,$rows,'fio_up',$online,$begin,$circle);
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
						else                                             $krugi[$ki]['checked']=false;
					}
					else $krugi[$ki]['checked']=false;
				}

				$cart=array(
					"friend-id"   =>$v['fid'],
					"friend-wp"   =>$v['user_wp'],
					"friend-krugi"=>$krugi,
					"friend-name" =>$v['firstname'].' '.$v['lastname'],
				);
				$html.=ShowFriendBlock($user_wp, $avatars[$k]['file'], $v, $stamp, $cart);
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
					$krugi=$MYSQL->query("SELECT `krug_id`, `name_".LANG_SITE."` `name` FROM `pfx_krugi` WHERE `show`=1");
					$checkin = "";
					if(@$friends[$i]['checkin']) $checkin = "<em></em>";
					$cart=array(
						"friend-id"  => $friends[$i]['fid'],
						"friend-wp"  => $friends[$i]['user_wp'],
						"friend-name"=> $friends[$i]['firstname'].' '.$friends[$i]['lastname'],
					);
					$html.=ShowFriendBlock($_SESSION['WP_USER']['user_wp'], $avatar[$i]['avatar'], $friend, $stamp, $cart);
				}
			}
			$resultArray=array(
				"html"=>$html,
				"uid" =>$stamp
			);

			echo json_encode($resultArray);
		}

		elseif(strlen($search)==0){
			ShowPeopleList($_POST['user_wp'], $_POST['items'], $_POST['list'], $_POST['online'], $_POST['circle'], 'json');
		}
	}
?>
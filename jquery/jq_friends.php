<?php
//Блок с информацие о друге
	function ShowFriendBlock($user_wp, $avatar, $friend){
		global $USER;
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$html='
                   <a href="javascript:;" onclick="setFriends(\''.$friend['firstname'].' '.$friend['lastname'].'\',\''.$avatar.'\',\''.$friend['user_wp'].'\')"><div class="choose_friend active">
                      <img src="'.$avatar.'" class="fl_l">
                      '.OnlineStatus($friend['status_chat'],'-small fl_l').'
                      <div class="choose">
                        <span class="c_name">'.$friend['firstname'].' '.$friend['lastname'].'</span><span class="c_button">Выбрать</span>
                      </div>
                   </div></a>';
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
				$html.=ShowFriendBlock($user_wp, $avatars[$k]['file'], $v);
			}
		}

		$resultArray=array(
				"html"=>$html,
				"count"=>$USER->CountFriendsInCircle(0,$user_wp,$online,$circle),
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
					$html.=ShowFriendBlock($_SESSION['WP_USER']['user_wp'], $avatar[$i]['avatar'], $friend);
				}
			}
			$resultArray=array(
				"html"=>$html,
				"uid" =>$stamp
			);

			echo json_encode($resultArray);
		}

		elseif(strlen($search)==0){
			ShowPeopleList($_POST['user_wp'], $_POST['items'], $_POST['list'], $_POST['online'], $_POST['circle'], $_POST['kolvo'], 'json');
		}
	}
?>
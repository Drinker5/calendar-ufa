<?php
	if($_URLP['0']=='my'){
		$user_wp=(int)$_SESSION['WP_USER']['user_wp'];
		$my='my';
	}
	else $user_wp=$my=(int)$_URLP['0'];

	require_once('jquery/jq_myfriends.php');
	$friends_all=$subs_count=$USER->CountFriends(0, $user_wp);
	$rows=30;//Количество выводимых друзей.
	$t=''; $c=''; $online=0; $circle=1;

	if(isset($_REQUEST['t'])){
		$t='t='.$_REQUEST['t'].'&';
		if($_REQUEST['t']=='online'){
			$online=1;
			$subs_count=$USER->CountFriends(0, $user_wp, 1);
		}
		else $subs_count=$USER->CountFriends(1, $user_wp);
	}

	if(isset($_REQUEST['c'])){
		$c     ='&c='.$_REQUEST['c'];
		$circle=$_REQUEST['c'];
	}
?>
<div class="title margin">
	<h2>Друзья <span class="title-count">(<?=$friends_all?>)</span></h2>
</div>
<div class="nav-panel group">
<div class="nav_block">
	<ul class="fl_l left">
		<li<?php if(!isset($_REQUEST['t']) && !isset($_REQUEST['c']))  echo ' class="active"' ?>><a href="/<?=$my?>-friends">Все <span class="black" id="fr-all">(<?=$friends_all?>)</span></a></li>
		<li<?php if(isset($_REQUEST['t']) && $_REQUEST['t']=='online') echo ' class="active"' ?>><a href="/<?=$my?>-friends?t=online">Друзья онлайн <span class="black">(<?=$USER->CountFriends(0, $user_wp, 1)?>)</span></a></li>
<?php
	if($_URLP['0']=='my'){
		$count_zayvki=$USER->CountFriends(1, $user_wp);
		if($count_zayvki>0){
			echo '<li';
			if(isset($_REQUEST['t']) && $_REQUEST['t']=='request')echo ' class="active"';
			echo '><a href="/'.$my.'-friends?t=request">Заявки в друзья <span class="green_stiker">+'.$count_zayvki.'</span></a></li>';
		}
	}
?>
	</ul>
	<div class="cleared"></div>
</div>
	<?php
	if(@$_REQUEST['t']!='request'){
?>
<div class="search-block">

        <div class="select_search-block-bottom">
            <div class="search-friend group fl_l">
                <div class="p_r">
                    <input type="text" class="bordered" placeholder="Введите имя друга" id="fio" onkeyup="/*this.value=this.value.replace(/[^a-zA-Zа-я-А-ЯёЁ0-9 ]+/ig,'');*/ search=true; friends(); return false;" />
			<i class="small-icon icon-search"></i>
                </div>
            </div>
            <div class="sortus fl_r">
                <span class="name">Сортировка по: </span>
                <select id="region-select" class="selectBox" style="display: none; " onChange="if(value!=''){location=value}else{options[selectedIndex=0];}">
                	<option>Выберите параметр</option>
<?php
		$circles=Circles();
		if(is_array($circles)){
			foreach($circles as $key=>$value){
				echo '<option value="/'.$my.'-friends?c='.$value['krug_id'].'">'.$value['name'].'</option>';
			}
		}
?>
                </select>
            </div>
       </div>
    </div>
</div>
<?php
	}
//!Заявки в друзья
	if(@$_REQUEST['t']=='request' && $_URLP['0']=='my'){
		echo '<div class="friend-container wider group">';
		$result=$USER->ShowNewFriends();
		if(is_array($result)){
			foreach($result as $k=>$v){
				$friends[0]=$v['user_wp'];
				$avatar=ShowAvatar($friends,70,70,true);
				echo '
	<div class="friend-item fl_l fr-requests">
		<div class="bordered medium-avatar fl_l"><img src="'.$avatar[0]['file'].'" alt=""></div>
		<div class="content wrapped">'.OnlineStatus($v['status_chat']).'
			<span class="name">'.$v['firstname'].' '.$v['lastname'].'</span>
			<br>
			<span class="place">'.$v['town_name'].', '.$v['country_name'].'</span>
		</div>
		<a href="#" class="btn btn-green save_new_friend" data-user="'.$v['user_wp'].'" data-name="'.$v['firstname'].' '.$v['lastname'].'">Дружить</a>
		<a href="#" class="btn btn-grey id_friend_del" data-user="'.$v['user_wp'].'" data-name="'.$v['firstname'].' '.$v['lastname'].'">Игнорировать</a>
	</div>';
			}
		}
		else echo 'Заявок пока нет.';
		echo '</div>';
	}

//!Список друзей
	else{
?>
<div class="friend-container group" id="idLenta" style="overflow: hidden;">
	<?=ShowPeopleList($user_wp, $rows, 0, $online, $circle)?>
</div>
<div id="loadmoreajaxloader" style="display:none; text-align:center;"><img src="/pic/loader.gif" alt="loader" width="32" height="32" /></div>
<?php

	//for($i=1016; $i<1021; $i++)$USER->AddHochu($i);

	//Если друзей 6 и меньше показываем случайных людей
	if($subs_count<7 && $_URLP[0]=='my'){
		if(isset($_SESSION['WP_USER']['user_wp'])){
			$result =$MYSQL->query("
				SELECT `u`.`user_wp`
				FROM `discount_users` `u`
				WHERE `u`.`user_wp`<>".$user_wp."
				AND `u`.`zvezda`=0
				AND `u`.`everif`=1
				AND `u`.`user_wp` NOT IN (SELECT `user_wp` FROM `discount_users_friends` WHERE `friend_wp` =".$user_wp.")
				AND `u`.`town_id`=(SELECT `town_id` FROM `pfx_users` WHERE `pfx_users`.`user_wp`=".$user_wp.")
				ORDER BY rand()
				LIMIT 0,6
			");
			if(is_array($result)){
				echo '<br />Может быть, Вы знаете этих людей?<br />';
				foreach ($result as $k=>$v){
					$cart=array(
						"friend-id" => $v['user_wp'],
					);
					$stamp=time();
					$new_usr=$USER->Info_min($v['user_wp'],70,70);
					echo ShowFriendBlock($user_wp, $new_usr['photo'], $new_usr, $stamp, $cart);
				}
			}
		}
	}

?>

<script>
	var page=1, max=<?=ceil($subs_count/$rows)?>, rows=<?=$rows?>, begin=rows, search=false, circle=<?=$circle?>, loading=false;

	function friends(){
		var fio=$('#fio').val();
		if(loading==false){
			loading=true;
	
			if(max>page)$('div#loadmoreajaxloader').show();
	
			if(search==true && fio.length>2){
				page=1; begin=0;
				$('#idLenta').html('');
				$('div#loadmoreajaxloader').show();
			}
			else
				if(search==true && fio.length==0){
					page=0; begin=0;
					$('#idLenta').html('');
					$('div#loadmoreajaxloader').show();
				}

			//console.log(fio);

			$.ajax({
				url:'/jquery-showmyfriends', type:'POST', data:{user_wp:<?=$user_wp?>, list:begin, items:rows, circle:circle, online:'<?=$online?>', fio:fio}, cache:false,
				success:function(data){
					var html, idLenta=$('#idLenta'), newElems;
					if(data){
						//console.log(data);
						if(max>page){
							$('div#loadmoreajaxloader').hide();
							html =jQuery.parseJSON(data);
							idLenta.append(html.html);
							idLenta.find('.my-friend-actions[rel="'+html.uid+'"]')
								.popover({
									trigger: 'none',
									autoReposition: false,
									stopChildrenPropagation: false,
									hideOnHTMLClick: true
								})
								.popover('content', $('#my-friend-action-template').html(), true)
								.popover('setOption', 'position', 'bottom')
								.popover('setOption', 'horizontalOffset', -31)
								.popover('setClasses', 'friend-action-popover');
<?php if($_URLP!='my'){ ?>
							idLenta.find('.find-friend-actions[rel="'+html.uid+'"]')
								.popover({
									trigger: 'none',
									autoReposition: false,
									stopChildrenPropagation: false,
									hideOnHTMLClick: true
								})
								.popover('content', $('#find-friend-action-template').html(), true)
								.popover('setOption', 'position', 'bottom')
								.popover('setOption', 'horizontalOffset', -31)
								.popover('setClasses', 'friend-action-popover');
<?php } ?>
							$(".popover input:checkbox").uniform();
							page =page+1;
							begin=begin+rows;
						}
						else{
							$('div#loadmoreajaxloader').hide();
						}
					}
				}
			});
			loading=false;
			search =false;
		}
	}

	$(window).scroll(function(){
		if($(window).scrollTop()==$(document).height()-$(window).height()){
			friends();
			console.log(page);
		}
	});
</script>
<?php
	}
?>
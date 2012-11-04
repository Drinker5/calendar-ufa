		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?=@$TITLE?></title>
		<link rel="stylesheet" type="text/css" media="screen, projection" href="/css/screen.css" />
		<!--[if lte IE 8]>
			<link rel="stylesheet" type="text/css" href="/css/ie.css" />
		<![endif]-->

		<script id="common-actions" type="text/template">
			<ul id="actions">
				<li><a href="/my-profile">Настройки</a></li>
				<li><a href="/help">Помощь</a></li>
				<li><a href="/exit">Выйти</a></li>
			</ul>
		</script>

		<script type="text/javascript" src="/js/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/js/mustache.js"></script>
		<script type="text/javascript" src="/js/jquery.popover-1.1.0.js"></script>
		<script type="text/javascript" src="/js/jquery.selectBox.min.js"></script>
		<script type="text/javascript" src="/js/common.js"></script>
		<script type="text/javascript" src="/js/input.js"></script>

		<script type="text/javascript" src="/js/jquery.uniform.js"></script>

<?php
	if(isset($scripts))echo $scripts;

	if($left_menu==1){
?>
		<script id="add-friend-template" type="text/template">
			<ul id="friend-bubble">
				<li><a href="/my-findfriends"><i class="small-icon icon-search-friend"></i> Поиск друзей</a></li>
				<li><a href="#"><i class="small-icon icon-add-friend"></i> Пригласить друга</a></li>
			</ul>
		</script>
<?php
	/*
?>
		<script id="personal-info-template" type="text/template">
			<div id="info-bubble" class="tx_l">
				<div class="info-bubble-inner">
					<strong><?=trim($_SESSION['WP_USER']['firstname'].' '.$_SESSION['WP_USER']['lastname'])?></strong><br>
					ID: <?=$_SESSION['WP_USER']['user_wp']?><br>
					Ваш баланс:
					<ul>
<?php
						foreach($_SESSION['WP_USER']['balance'] as $key=>$value)echo '<li><span>'.$_SESSION['WP_USER']['balance_mask'][$key].'</span><em>'.$value.'</em></li>';
?>
					</ul>
				</div>
				<a href="#" class="add_balance tx_r">Пополнить баланс</a>
			</div>
		</script>
<?php
	*/
	}

	if(@$_URLP[0]=='type'){
?>

        <script id="gifts-categories" type="text/template">
            <ul id="gifts-categories-list">
            	{{#sub}}
                <li><a href="javascript:;" onclick="setCat({{key}})">{{name}}</a></li>
                {{/sub}}
            </ul>
        </script>

		<script id="gift-exchange-list" type="text/template">
			<ul class="common-list">
<?php
    $curFull=curArray();
    foreach($curFull as $key=>$value){
    	echo'
				<li class="common-list-item">
					<span class="common-list-link"><a href="javascript:;" onclick="setCurrency('.$value['id'].',\''.$value['mask'].'\')">'.$value['mask'].'</a></span>
				</li>';
    }
?>
			</ul>
		</script>
<?php
	}

	if(@$_URLP[1]=='subscribes'){
?>
		<script id="subscribe-info" type="text/template">
			<div class="all-info my-subsrc">
				<h3>{{{shop-name}}}</h3>
				<div class="separator"></div>
				<div class="group">
					<div class="place fl_l">
						<img src="{{{path-to-avatar}}}" alt="" />
					</div>
					<div class="place-info wrapped">
						<ul>
							<li><a href="{{{shop-page-link}}}"><i class="small-icon icon-shop-page"></i>Страница заведения</a></li>
							<li><a href="{{{shop-map}}}"><i class="small-icon icon-position"></i>Посмотреть на карте</a></li>
							<li><a href="{{{shop-add-favorite}}}"><i class="small-icon icon-favorite-place"></i>Добавить в любимые места</a></li>
						</ul>
					</div>
				</div>
			</div>
		</script>
<?php
	}

	if(@$_URLP[1]=='gifts'){
?>
		<script id="gift-all-info-template" type="text/template">
			<div class="all-info">
				<div class="group">
					<div class="place fl_l"><div class="bordered place-avatar"><img src="{{{path-to-avatar}}}" alt="" /></div></div>
					<div class="place-info wrapped">
						<h3>{{{shop-name}}}</h3>
						<!--h4>{{{shop-type}}}</h4-->
						<ul>
							<li><a href="{{{shop-page-link}}}"><i class="small-icon icon-shop-page"></i>Страница заведения</a></li>
							<li><a href="{{{shop-map}}}"><i class="small-icon icon-check-in"></i>Посмотреть на карте</a></li>
							<li id="fav-{{{shop-address-id}}}">{{{shop-if}}}</li>
						</ul>
					</div>
				</div>
				<div class="separator"></div>
				<div class="group">
					<div class="code tx_c fl_l">Код для получения: <span>{{{gift-code}}}</span></div>
					<div class="message fl_l"><span>Поздравительное сообщение указанное в подарке:</span><p>{{{gift-message}}}</p></div>
				</div>
			</div>
		</script>
<?php
	}

    if(@$_URLP[0]=='my' && @$_URLP[1]=='wishes'){
?>
        <script id="wish-edit-template" type="text/template">
            <div id="wish_edit" class="wish-edit-wrap group tx_l">
                <h3>Редактирование желания</h3>
                <div class="bordered big-avatar fl_l">
                    <img src="{{{wish-img-path}}}">
                </div>
                <div class="wrapped">
                    <table>
                        <tr>
                            <td>Повод</td>
                            <td>
                                <textarea id="wish_reason{{{wish-id}}}" class="bordered">{{{wish-reason}}}</textarea>
                            </td>
                        </tr>
                        <!--
                        <tr>
                            <td>Дата</td>
                            <td>
                                <input type="text" id="wish_date" class="bordered" value="{{{wish-date}}}">
                            </td>
                        </tr>
                        -->
                        <tr>
                            <td></td>
                            <td class="tx_r"><a href="#" id="save_wish" class="btn btn-green" data-wish="{{{wish-id}}}" onClick="return SaveWish({{{wish-id}}},$(this));">Сохранить</a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </script>
<?php
    }
	if(@$_URLP[0]=='my' && @$_URLP[1]=='photoalbums'){
?>
		<script id="my-fotoalbum-template" type="text/template">
			<div id="a{{{album-id}}}">
				<ul class="friend-actions">
					<li><a href="/my-photoalbums-edit?album_id={{{album-id}}}"><i class="small-icon icon-edit"></i> Редактировать</a></li>
					<li><a href="#"><i class="small-icon icon-comments"></i> Комментарии</a></li>
					<li><a href="#"><i class="small-icon icon-delete"></i> Удалить альбом</a></li>
					<div class="separator"></div>
					Просматривать альбом могут:
					<li><label class="crcledt"><input type="checkbox"{{#checked}} checked="checked"{{/checked}} value="0" />Все</label></li>
					{{#album-krugi}}
							<li><label class="crcledt"><input type="checkbox"{{#checked}} checked="checked"{{/checked}} value="{{{krug_id}}}" />{{{name}}}</label></li>
					{{/album-krugi}}
				</ul>
			</div>
		</script>
<?php
	}
	if(@$_URLP[1]=='photoalbums' && @$_URLP[2]=='add'){
?>
		<script id="album-can-comment-template" type="text/template">
			<div id="a{{{album-id}}}">
				<ul class="friend-actions">
					<li><label class="crcledt"><input type="checkbox"{{#checked}} checked="checked"{{/checked}} value="0" /> Все</label></li>
					{{#album-krugi}}
							<li><label class="crcledt"><input type="checkbox"{{#checked}} checked="checked"{{/checked}} value="{{{krug_id}}}" />{{{name}}}</label></li>
					{{/album-krugi}}
				</ul>
			</div>
		</script>
<?php
	}
	if(@$_URLP[1]=='friends'){
?>
		<script id="subscriber-action-template" type="text/template">
			<div>
				<ul class="friend-actions">
					<li><a href="#"><i class="small-icon icon-gift"></i> Сделать подарок</a></li>
					<li><a href="#"><i class="small-icon icon-chat"></i> Начать чат</a></li>
					<li><a href="#"><i class="small-icon icon-invite"></i> Отправить приглашение</a></li>
					<li><a href="#"><i class="small-icon icon-add-friend"></i> Пригласить в друзья</a></li>
				</ul>
			</div>
		</script>

		<script id="find-friend-action-template-exist" type="text/template">
			<ul class="friend-actions">
				<li><i class="small-icon icon-add-friend"></i> Уже</li>
				<li><a href="#"><i class="small-icon icon-gift"></i> Сделать подарок</a></li>
				<li><a href="#"><i class="small-icon icon-chat"></i> Начать чат</a></li>
				<li><a href="#"><i class="small-icon icon-invite"></i> Отправить приглашение</a></li>
			</ul>
		</script>

		<script id="find-friend-action-template" type="text/template">
			<ul class="friend-actions">
				<li><a href="#" class="add_new_friend" data-user="{{{friend-wp}}}" data-name="{{{friend-name}}}"><i class="small-icon icon-add-friend"></i> Добавить в друзья</a></li>
				<li><a href="#"><i class="small-icon icon-gift"></i> Сделать подарок</a></li>
				<li><a href="#"><i class="small-icon icon-chat"></i> Начать чат</a></li>
				<li><a href="#"><i class="small-icon icon-invite"></i> Отправить приглашение</a></li>
			</ul>
		</script>

		<script id="friend-action-template" type="text/template">
			<div>
				<ul class="friend-actions">
					<li><a href="#"><i class="small-icon icon-gift"></i> Сделать подарок</a></li>
					<li><a href="#"><i class="small-icon icon-chat"></i> Начать чат</a></li>
					<li><a href="#"><i class="small-icon icon-invite"></i> Отправить приглашение</a></li>
				</ul>
			</div>
		</script>

		<script id="my-friend-action-template" type="text/template">
			<div data-id="{{{friend-id}}}" class="friend-id">
				<ul class="friend-actions">
					<li><a href="#"><i class="small-icon icon-gift"></i> Сделать подарок</a></li>
					<li><a href="#"><i class="small-icon icon-chat"></i> Начать чат</a></li>
					<li><a href="#"><i class="small-icon icon-invite"></i> Отправить приглашение</a></li>
					<li><a href="#" class="id_friend_del" data-user="{{{friend-wp}}}" data-name="{{{friend-name}}}"><i class="small-icon icon-delete-friend"></i> Убрать из друзей</a></li>
					<div class="separator"></div>
					{{#friend-krugi}}
						<li><label class="crcledt"><input type="checkbox"{{#checked}} checked="checked"{{/checked}} value="{{{krug_id}}}" />{{{name}}}</label></li>
					{{/friend-krugi}}
				</ul>
			</div>
		</script>
		
		<script id="my-friend-action-template-short" type="text/template">
			<div data-id="{{{friend-id}}}" class="friend-id">
				<ul class="friend-actions">
					<li><a href="#"><i class="small-icon icon-gift"></i> Сделать подарок</a></li>
					<li><a href="#"><i class="small-icon icon-chat"></i> Начать чат</a></li>
					<li><a href="#"><i class="small-icon icon-invite"></i> Отправить приглашение</a></li>
					<li><a href="#" class="id_friend_del" data-user="{{{friend-wp}}}" data-name="{{{friend-name}}}"><i class="small-icon icon-delete-friend"></i> Убрать из друзей</a></li>
				</ul>
			</div>
		</script>
<?php
	}
?>
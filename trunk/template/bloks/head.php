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

		<style>        
			.b-desc:last-word {
				display: block;
			}
		</style>

		<script type="text/javascript" src="/js/jquery.min.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script>
		<script type="text/javascript" src="/js/mustache.js"></script>
		<script type="text/javascript" src="/js/jquery.popover-1.1.0.js"></script>
		<script type="text/javascript" src="/js/jquery.selectBox.min.js"></script>
		<script type="text/javascript" src="/js/common.js"></script>
		<script type="text/javascript" src="/js/input.js"></script>
		<script type="text/javascript" src="/js/jquery.royalslider.min.js"></script>
		<script type="text/javascript" src="/js/plugins/ui/jquery.tipsy.js"></script>
		<script type="text/javascript" src="/js/jkerny.min.js"></script>

		<script type="text/javascript" src="/js/jnotifier.min.js"></script>
		<script type="text/javascript" src="/js/jquery.titlealert.min.js"></script>
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

	if(@$_URLP[0]=='gift'){
?>

        <script id="gift_information" type="text/template">
         <div class="g_if">
           <p class="gift_title">Сеть кофеен “Кофемания”</p>
           <p class="nm">Также показать сообщение в ленте новостей</p>
           <img src="pic/popup_payment.png" style="margin: 5px 0;">
           <p>Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium
doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est, qui dolorem ipsum, quia dolor sit, amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt, ut labore et dolore magnam aliquam quaerat voluptatem.</p>
<p>Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit, qui in ea voluptate velit esse, quam nihil molestiae consequatur, vel illum, qui dolorem eum fugiat, quo voluptas nulla pariatur? At vero eos et accusamus et iusto odio dignissimos ducimus, qui blanditiis praesentium voluptatum deleniti atque corrupti, quos dolores et quas molestias excepturi sint, obcaecati cupiditate non provident, similique sunt in culpa, qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
<p>Nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet, ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.</p>
         </div>
        </script>

        <script id="currency_information" type="text/template">
         <div class="g_if">
           <p class="gift_title">Информация о валюте</p>
           <p class="nm">Подсчёт приблизительный</p>
           <img src="pic/popup_payment.png" style="margin: 5px 0;">
           <p>Sed ut perspiciatis, unde omnis iste natus error sit voluptatem accusantium
doloremque laudantium, totam rem aperiam eaque ipsa, quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt, explicabo. Nemo enim ipsam voluptatem, quia voluptas sit, aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos, qui ratione voluptatem sequi nesciunt, neque porro quisquam est, qui dolorem ipsum, quia dolor sit, amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt, ut labore et dolore magnam aliquam quaerat voluptatem.</p>
<p>Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit, qui in ea voluptate velit esse, quam nihil molestiae consequatur, vel illum, qui dolorem eum fugiat, quo voluptas nulla pariatur? At vero eos et accusamus et iusto odio dignissimos ducimus, qui blanditiis praesentium voluptatum deleniti atque corrupti, quos dolores et quas molestias excepturi sint, obcaecati cupiditate non provident, similique sunt in culpa, qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio.</p>
<p>Nam libero tempore, cum soluta nobis est eligendi optio, cumque nihil impedit, quo minus id, quod maxime placeat, facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet, ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.</p>
         </div>
        </script>
<?php
	}

    if (@$_URLP[0]=='shop'){
?>
		<link href='http://api.tiles.mapbox.com/mapbox.js/v0.6.5/mapbox.css' rel='stylesheet' />
		<script src="http://api.tiles.mapbox.com/mapbox.js/v0.6.5/mapbox.js"></script>
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

/*	if(@$_URLP[1]=='gifts'){
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
	}*/

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
							<li><label class="crcledt"><input type="checkbox"{{#checked}} checked="checked"{{/checked}} value="{{{krug_id}}}" class="frnchck"/>{{{name}}}</label></li>
					{{/album-krugi}}
				</ul>
			</div>
		</script>
<?php
	}
	if(@$_URLP[1]=='friends' or @$_URLP[1]=='findfriends'){
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
					{{#friend-krugi}}
						<li><label class="crcledt"><input type="checkbox"{{#checked}} checked="checked"{{/checked}} value="{{{krug_id}}}" />{{{name}}}</label></li>
					{{/friend-krugi}}
					<div class="separator"></div>
					<li><a href="#" class="id_friend_del" data-user="{{{friend-wp}}}" data-name="{{{friend-name}}}" data-list="true"><i class="small-icon icon-delete-friend"></i> Убрать из друзей</a></li>
				</ul>
			</div>
		</script>
		
		<script id="my-friend-action-template-short" type="text/template">
			<div data-id="{{{friend-id}}}" class="friend-id">
				<ul class="friend-actions">
					<li><a href="#"><i class="small-icon icon-gift"></i> Сделать подарок</a></li>
					<li><a href="#"><i class="small-icon icon-chat"></i> Начать чат</a></li>
					<li><a href="#"><i class="small-icon icon-invite"></i> Отправить приглашение</a></li>
					<li><a href="#" class="id_friend_del" data-user="{{{friend-wp}}}" data-name="{{{friend-name}}}" data-list="true"><i class="small-icon icon-delete-friend"></i> Убрать из друзей</a></li>
				</ul>
			</div>
		</script>
<?php
	}
?>
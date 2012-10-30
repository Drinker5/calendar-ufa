<?php
$_URLP[1] = str_replace(".php","",@$_URLP[1]);
switch($_URLP[1]){

	case 'login': // Вход пользователя
		require_once(path_root."jquery/jq_login.php");
	break;

	case 'register': // Регистрация пользователя
		require_once(path_root."jquery/jq_register.php");
	break;

	case 'forgot': // Восстановление пароля
		require_once(path_root."jquery/jq_forgot.php");
	break;

	case 'video': // Демо ролик
		require_once(path_root."jquery/jq_video.php");
	break;

	case 'sellang': // Выбор языка на странице home
		require_once(path_root."jquery/jq_sellang.php");
	break;

	case 'chat':
		if(isset($_SESSION['WP_USER']))
		//require_once(path_root."jquery/jq_chat.php");
		require_once(path_root."jquery/chat/ini.jquery.php");
	break;

	case 'gbnchat': // Чат
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/chat/ini.jquery.php");
	break;

	case 'person': // Открываем хинт с инфо про пользователя на моей странице
	    if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_person_tooltip.php");
	break;

	case 'upload':
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_upload.php");
	break;

	case 'searchuser':
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_search_users.php");
	break;

	case 'sort': // Сортировка подарков в админке
		require_once(path_root."jquery/jq_sort.php");
	break;

	case 'ihere': // Выводит на карту все заведения рядом с пользователем
	    if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_mapihere.php");
	break;

	case 'nextitems': // Показывает список магазинов или подарков по type_id
	    if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_type_items.php");
	break;

	case 'seltowns': // Выбор города в профиле
		require_once(path_root."jquery/jq_towns.php");
	break;

	case 'wingift': // ОКНО Подарить подарок
	    if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_win_gift.php");
	break;

	case 'listmyfriends': // Выводит список моих друзей для подарка
	    if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_list_gift_friends.php");
	break;

	case 'paygift': // Оплата подарка
	    if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_pay_gift.php");
	break;

	case 'showpodpiska': // Выводим ленту подписок
	    if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_lenta_podpisok.php");
	break;

	case 'showwant': // Выводим ленту подписок
	    if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_wants.php");
	break;

	case 'hideitem': // Скрыть пункт из ленты подписки
	    if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_hide_lenta_item.php");
	break;

	case 'friendaction': // Меню действий в списке моих друзей
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_friend_action.php");
	break;

	case 'showmyfriends': // Список моих друзей
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_myfriends.php");
	break;

	case 'mygifts': // Мои подарки
	    if(isset($_SESSION['WP_USER']))
	    require_once(path_root."jquery/jq_mygifts.php");
	break;

    case 'mywishes': // Мои желания
	    if(isset($_SESSION['WP_USER']))
	    require_once(path_root."jquery/jq_mywishes.php");
	break;

    case 'myplaces': // Любимые места
	    if(isset($_SESSION['WP_USER']))
	    require_once(path_root."jquery/jq_myplaces.php");
	break;

	case 'mycheckin': // Карта
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_mycheckin.php");
	break;

	case 'profile': // Сохранения инфо. профиля
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_profile_update.php");
	break;

	case 'changepin': // Смена пин кода
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_profile_pin.php");
	break;

	case 'mymobiles': // Мобильные телефоны
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_profile_phones.php");
	break;

	case 'profsecurity': // Сохранения конфиденциальность для ленты событий
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_profile_security.php");
	break;

	case 'password': //Изменение пароля
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_profile_password.php");
	break;

	case 'showfriends': // Список друзей пользователя
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_user_friends.php");
	break;

	case 'lenta': // Лента событий
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_lenta_sobitiy.php");
	break;

	case 'comments': // Комментарии
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_comments.php");
	break;

	case 'myphotoalbums': // Мой фотоальбом
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_myphotoalbums.php");
	break;

	case 'photoview': // Просмотр фотографий
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_photoview.php");
	break;

	case 'photoalbumaction': // Действия для фотоальбома
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_photoalbum_action.php");
	break;

	case 'checkuved': //Отмечем уведомления, как прочитанные
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_checkuved.php");
	break;

	case 'subscribe': //Отмечем уведомления, как прочитанные
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_subscribe.php");
	break;

	case 'status': //Меняем статус пользователя
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_status.php");
	break;

	case 'feed': //Лента новостей
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_feed.php");
	break;

    case 'shop': // Любимые места
		require_once(path_root."jquery/jq_shop.php");
	break;

	case 'gifts': // Подарки
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_gifts.php");
	break;

    case 'invitefriends': // Вход пользователя
		require_once(path_root."jquery/jq_invite_friends.php");
	break;
	
	case 'recommendfriends': // Вход пользователя
		require_once(path_root."jquery/jq_recommend_friends.php");
	break;

	case 'calendar': // Календарь
		if(isset($_SESSION['WP_USER']))
		require_once(path_root."jquery/jq_calendar.php");
	break;
}
?>
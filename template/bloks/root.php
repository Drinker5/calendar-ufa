<?php
	$scripts=''; $left_menu=-1;
	if(isset($_SESSION['WP_USER'])){
	// Если я делаю подарок выбранному другу
	/*if(isset($varr['present'])){
	   if($varr['present'] == 'delete') unset($_SESSION['present_to_user']);
	   else $_SESSION['present_to_user'] = $USER->Info_min($varr['present'],60,60);
	}*/
		//Скрипт чата
		//$scripts.='<script type="text/javascript" src="js/chat.js"></script>';
	}

	//*******************************************************************************
	switch($_URLP[0]){
		case 'my':
			if(isset($_SESSION['WP_USER'])){
				switch(str_replace('.php', '', @$_URLP[1])){
					//!Мои подписки - управление подписками
					case 'subscribes':
						$left_menu=1;
						require_once(path_modules.'ini.groups.php');
						$file='bloks/my_subscribes.php';
						$TITLE='Мои подписки';
						$scripts.="<script type='text/javascript' src='js/common.subscribes.js'></script>";
					break;
					
					//!Мои календарь - управление календарём
					case 'calendar':
						$left_menu=1;
						$file='bloks/my_calendar.php';
						$TITLE='Мой календарь';
						$scripts.="	<link rel=\"stylesheet\" type=\"text/css\" href=\"/css/gleb.css\" />
									<link rel=\"stylesheet\" type=\"text/css\" href=\"/css/aigul.css\" />
									<link rel=\"stylesheet\" type=\"text/css\" href=\"/css/jquery-ui-timepicker-addon.css\" />
									<link rel=\"stylesheet\" type=\"text/css\" href=\"/css/fullcalendar.css\" />
									<script type=\"text/javascript\" src=\"js/common.calendar.js\"></script>
									<script type=\"text/javascript\" src=\"js/plugins/others/jquery.fullcalendar.js\"></script>
									<script type=\"text/javascript\" src=\"js/plugins/others/jquery-ui-timepicker-addon.js\"></script>
									<script src=\"http://api.tiles.mapbox.com/mapbox.js/v0.6.5/mapbox.js\"></script>
									<link href=\"http://api.tiles.mapbox.com/mapbox.js/v0.6.5/mapbox.css\" rel=\"stylesheet\" />
								   ";
					break;

					case 'rss':
						$left_menu=6;
						$file ='bloks/my_lentapodpisok.php';
						$TITLE='Лента подписок';
					break;

					//!Мои желания - управление желаниями
					case 'wishes':
                        $user_wp = $_SESSION['WP_USER']['user_wp'];
						$left_menu=1;
						$file ='bloks/my_wishes.php';
						$TITLE='Мои желания';
						$scripts.='<script type="text/javascript" src="js/common.wishes.js"></script>
                                   <script type="text/javascript" src="js/common.comments.js"></script>';
					break;

					//!Мои друзья - управление друзьями
					case 'friends':
						$left_menu=1;
						$file     ='bloks/my_friends.php';
						$TITLE    ='Найти друзей / Мои друзья';
						$scripts .='<script type="text/javascript" src="js/common.friends.js"></script>';
					break;

					//!Мои подарки - управление подарками
					case 'gifts':
						$left_menu=1;
						$file    ='bloks/my_gifts.php';
						$TITLE   ='Мои подарки';
						$scripts.='';
					break;

					//!Мои фотоальбомы - управление фотоальбомами
					case 'photoalbums':
						$left_menu=1;
						switch(@$_URLP[2]){
							//Добавление
							case 'add':
								$file    ='bloks/my_photoalbum_add.php';
								$TITLE   ='Создать фотоальбом';
								$scripts.='<link rel="stylesheet" href="css/upload.css" type="text/css" />';
							break;

							//Редактирование
							case 'edit':
								$album_id=varr_int(@$_GET['album_id']);
								$album   =$USER->InfoPhotoAlbum(0,$album_id,113,95);
								$photos  =$USER->ShowPhotosIsAlbum(0,$album_id,113,95);
								if(!is_array($album) or !is_array($photos)) die('ERROR');
								$file    ='bloks/my_photoalbum_edit.php';
								$TITLE   ='Редактирование фотоальбома';
								$scripts.='<link rel="stylesheet" href="css/upload.css" type="text/css" />';
							break;

							//Вывод списка фотоальбомов
							default:
								$file    ='bloks/my_photoalbums.php';
								$TITLE   ='Мои фотоальбомы';
								/*$scripts.='
									<script type="text/javascript" src="js/jquery.popupmenu.js"></script>
									<link type="text/css" rel="stylesheet" href="css/jquery.safari-checkbox.css" />
									<script type="text/javascript" src="js/jquery.mousewheel.js"></script>
									<link type="text/css" href="css/jquery.jscrollpane.css" rel="stylesheet" media="all" />
									<script type="text/javascript" src="js/jquery.jscrollpane.min.js"></script>
									<script type="text/javascript" src="js/jquery.checkbox.min.js"></script>
									<link type="text/css" rel="stylesheet" href="css/colorbox.css" />
									<script type="text/javascript" src="js/jquery.colorbox.js"></script>
									<script type="text/javascript" src="js/photoview.js"></script>';*/
                                $scripts .= '<script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
                 							 <script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
                							 <link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />';
							break;

						}
					break;


					//!Приглашение друзей
					case 'invitefriends':
                                            $left_menu=1;
						//require_once(path_root."template/xajax/xajax_invite_users.php");
						$file = 'bloks/my_invite_friends.php';
						$TITLE = LANG_TO_INVITE_FRIENDS;
						$scripts .='
							<link type="text/css" rel="stylesheet" href="css/jquery.selectBox.css" />
							<script type="text/javascript" src="js/jquery.selectBox.min.js"></script>
							<link type="text/css" rel="stylesheet" href="css/jquery.safari-checkbox.css" />
							<script type="text/javascript" src="js/jquery.checkbox.min.js"></script>';

					break;

					//!Поиск друзей
					case 'findfriends':
						$left_menu=1;
						$file = 'bloks/my_invite_friends.php';
						$TITLE = LANG_TO_INVITE_FRIENDS;
						$scripts .='
							<link type="text/css" rel="stylesheet" href="css/jquery.selectBox.css" />
							<script type="text/javascript" src="js/jquery.selectBox.min.js"></script>
							<link type="text/css" rel="stylesheet" href="css/jquery.safari-checkbox.css" />
							<script type="text/javascript" src="js/jquery.checkbox.min.js"></script>
							<script type="text/javascript" src="js/common.friends.js"></script>';
					break;

					//!Уведомления
					case 'announcements':
						$left_menu=1;
						$file     ='bloks/my_announcement.php';
						$TITLE    ='Мои уведомления';
					break;


//!Мой профиль и настройки
					//!Общие сведения
					case 'profile':
						$left_menu=1;
						$file     ='bloks/my_profile.php';
						$TITLE    ='Мой профиль';
						$scripts .='<link type="text/css" rel="stylesheet" href="css/jquery.selectBox.css" />
							<script type="text/javascript" src="js/jquery.selectBox.min.js"></script>';
					break;

					//!Смена аватарки
					case 'avatar':
						$left_menu=1;
						$file     ='bloks/my_profile_avatar.php';
						$TITLE    ='Изменить аватару';
						$scripts .='
							<script src="js/jcrop/jquery.Jcrop.min.js"></script>
							<link rel="stylesheet" href="js/jcrop/jquery.Jcrop.css" type="text/css" />
							<link rel="stylesheet" href="css/upload.css" type="text/css" />
							<link rel="stylesheet" href="css/profile-avatar-page.css" type="text/css" />';
					break;

					//!Телефоны
					case 'phones':
						$left_menu=1;
						$file     ='bloks/my_profile_phones.php';
						$TITLE    ='Мой профиль - Моб. телефоны';
						$scripts .='';
					break;

					//!Управление денежными средствами
					case 'wallets':
						$left_menu=1;
						$file     ='bloks/my_profile_wallets.php';
						$TITLE    ='Мой профиль - Бонусные счета';
					break;

					//!Настройки кофеденциальности
					case 'security':
						$left_menu=1;
						$file     ='bloks/my_profile_security.php';
						$TITLE    ='Мои настройки конфиденциальности';
						$scripts .='';
					break;

					//!Смена пароля
					case 'password':
						$left_menu=1;
						$file     ='bloks/my_profile_password.php';
						$TITLE='Изменить пароль';
					break;

					//!Уведомления
					case 'notifications':
						$left_menu=1;
						$file     ='bloks/my_profile_notification.php';
						$TITLE    ='Мои оповещения';
					break;

					//!Уведомления
					case 'places':
						$left_menu=1;
						$file     ='bloks/my_places.php';
						$TITLE    ='Мои любимые места';
						$scripts .='
							<script src="http://api.tiles.mapbox.com/mapbox.js/v0.6.5/mapbox.js"></script>
							<link href="http://api.tiles.mapbox.com/mapbox.js/v0.6.5/mapbox.css" rel="stylesheet" />';
					break;

					case 'findplaces':
						$left_menu=1;
						$file     ='bloks/my_find_places.php';
						$TITLE    ='Куда пойти';
						$scripts .='
							<script src="http://api.tiles.mapbox.com/mapbox.js/v0.6.5/mapbox.js"></script>
							<link href="http://api.tiles.mapbox.com/mapbox.js/v0.6.5/mapbox.css" rel="stylesheet" />
							<link href="css/ui_custom.css" type="text/css" rel="stylesheet" />';

					break;
                                        //Оповещения
					case 'alerts':
						$left_menu=1;
						$file     ='bloks/my_alerts.php';
						$TITLE    ='Мои оповещения';
					break;

					//!Лента новостей
					case 'feed':
						$left_menu=1;
						$file     ='bloks/my_feed.php';
						$TITLE    ='Лента новостей';
                        $scripts .='<script type="text/javascript" src="js/common.wishes.js"></script>';
					break;

					//!Check-in
					case 'checkin':
						$left_menu=1;
						$file     ='bloks/my_checkin.php';
						$TITLE    ='Check-in';
						$scripts .='
							<script src="http://api.tiles.mapbox.com/mapbox.js/v0.6.5/mapbox.js"></script>
							<link href="http://api.tiles.mapbox.com/mapbox.js/v0.6.5/mapbox.css" rel="stylesheet" />';
					break;
//Конец профиля

					default:
						header("Location: /".$_SESSION['WP_USER']['user_wp']);
					break;
				}
			}
			else header("Location: /");
		break;


//**********************************************************************************************


	/*case 'platejka':
		$TITLE = "Как пополнить счет через терминалы Платежка";
		$file = 'bloks/info.platejka.php';
	break;

	case 'citypay':
		$TITLE = "Как пополнить счет через терминалы City-Pay";
		$file = 'bloks/info.citypay.php';
	break;

	case 'privattermua':
		$TITLE = "Как пополнить счет через терминалы ПриватБанк";
		$file = 'bloks/info.privat_term_ua.php';
	break;

	case 'search':
		$TITLE = "Поиск акций, подарков, скидок и т.п.";
		require_once(path_modules.'ini.search.php');
		$file = 'bloks/search.php';
		$resultSEARCH = Search(20);
	break;
	*/

		//!Типы подарков
		case 'type':
			$type_id  =varr_int(@$_URLP[1]);
			$gr_id    =varr_int(@$_URLP[2]);
			$shop_id  =varr_int(@$_URLP[3]);
			if($type_id==0)header("Location: /".@$_SESSION['WP_USER']['user_wp']);
			$left_menu=1;

			require_once(path_modules.'ini.groups.php');
			$arrGROUPS = $GROUPS->ShowGroupType($type_id,0);
			if(is_array($arrGROUPS)){
				$TITLE = $AKCIANAME;
				$file = 'bloks/type_groups.php';
			} else header("Location: /".@$_SESSION['WP_USER']['user_wp']);
		break;

		//!Магазины и прочие заведения
		case 'shop':
			$left_menu=3;
			$shop_id  =varr_int(@$_URLP[1]);
			require_once(path_modules.'ini.groups.php');
            require_once(path_modules.'ini.shop.php');
			$SHOP_INFO=$SHOP->Info($shop_id,190,190);
			if(!is_array($SHOP_INFO)) header("Location: /".@$_SESSION['WP_USER']['user_wp']);
			$TITLE    =$SHOP_INFO['name'];
			$file     ='bloks/shop.php';
			$scripts .='<script type="text/javascript" src="js/common.subscribes.js"></script>
                        <script type="text/javascript" src="js/common.comments.js"></script>';
		break;

		//!Подарки
		case 'gift':
			$left_menu=1;
			require_once(path_modules.'ini.groups.php');
			$akcia_id = varr_int(@$_URLP[1]);
			$AKCIA_INFO = $AKCIA->Show($akcia_id,185,184);
			//if(!is_array($AKCIA_INFO)) header("Location: /".@$_SESSION['WP_USER']['user_wp']);
			$TITLE = $AKCIA_INFO['header'];
			if(strlen(@$_URLP[3])!=0 and @$_URLP[3]=='end')$file = 'bloks/gift-end.php';
			elseif(strlen(@$_URLP[2])!=0)$file = 'bloks/gift-bring.php';
			
			else                    $file = 'bloks/gift.php';
            $scripts.='<script type="text/javascript" src="js/common.wishes.js"></script>';
			//$scripts .= "
			//  <link type=\"text/css\" rel=\"stylesheet\" href=\"css/colorbox.css\">
			//  <script type=\"text/javascript\" src=\"js/jquery.colorbox.js\"></script>
			//  <script type=\"text/javascript\" src=\"js/jquery.mousewheel.js\"></script>
			//  <link type=\"text/css\" href=\"css/jquery.jscrollpane.css\" rel=\"stylesheet\" media=\"all\" />
			//  <script type=\"text/javascript\" src=\"js/jquery.jscrollpane.min.js\"></script>
			//  <script type=\"text/javascript\" src=\"js/photoview.js\"></script>
			//";
		break;

        //Просмотр вишлиста
        case 'wlist':
            require_once(path_modules.'ini.groups.php');
		    $left_menu=1;
            $wlist_id = varr_int(@$_URLP[1]);
			$file ='bloks/wishlist.php';
			$TITLE='Wishlist';
			$scripts.='<script type="text/javascript" src="js/common.comments.js"></script>';
		break;

		//!Кумиры
		case 'stars':
			$star_id = varr_int(@$_URLP[1]);
			if($star_id == 0) header("Location: /");
			require_once(path_modules.'ini.stars.php');
			$TITLE = $STARS->StarName($star_id);
			$file = 'bloks/stars_groups.php';
		break;

		//!Список кумиров
		case 'starslist':
			$USER_INFO=$_SESSION['WP_USER'];
			$circle   =1;
			$left_menu=1;//Отобразить пользователя страницу
			$TITLE    ='Список звезд';
			$file     ='bloks/stars_list.php';
		break;

		//!Страница пользователя
		default:
			if(isset($_SESSION['WP_USER']['user_wp']) && $_URLP[0] == $_SESSION['WP_USER']['user_wp']){ // Если это я
				$USER_INFO=$_SESSION['WP_USER'];
				$left_menu=1;//Отобразить мою страницу
			}
			else{
				$USER_INFO=$USER->Info_min($_URLP[0],190,190);
				$left_menu=0;//Отобразить пользователя страницу
				$scripts .='<script type="text/javascript" src="js/common.friends.js"></script>
                            <script type="text/javascript" src="js/common.wishes.js"></script>';
			}

			//Страницы других пользоватеей
			if(is_array($USER_INFO)){
				$USER->AddVisitor($USER_INFO['user_wp']);//Определяем заход в гости
				$TITLE=trim($USER_INFO['firstname'].' '.$USER_INFO['lastname']);
				switch(@$_URLP[1]){
					case 'friends': // Друзья пользователя
						if($USER_INFO['user_wp']==$_SESSION['WP_USER']['user_wp'])header("Location: /my-friends");

						$TITLE.=' - Друзья';
						$file  ='bloks/my_friends.php';
					break;

                    case 'wishes':
                        $user_wp  = (int)$_URLP[0];
						$file ='bloks/my_wishes.php';
						$TITLE='Желания';
						$scripts.='<script type="text/javascript" src="js/common.wishes.js"></script>
                                   <script type="text/javascript" src="js/common.comments.js"></script>';
					break;

					case 'photoalbums': // Фотоальбомы пользователя
						if($USER_INFO['user_wp'] == $_SESSION['WP_USER']['user_wp'])
							header("Location: /my-photoalbums");

						$TITLE .= " - Фотоальбомы";
						$file = "bloks/my_photoalbums.php";
                        $scripts .= '<script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
            			             <script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
            						 <link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />';
						/*$scripts .= "
							<script type=\"text/javascript\" src=\"js/jquery.mousewheel.js\"></script>
							<link type=\"text/css\" href=\"css/jquery.jscrollpane.css\" rel=\"stylesheet\" media=\"all\" />
							<script type=\"text/javascript\" src=\"js/jquery.jscrollpane.min.js\"></script>
							<link type=\"text/css\" rel=\"stylesheet\" href=\"css/colorbox.css\">
							<script type=\"text/javascript\" src=\"js/jquery.colorbox.js\"></script>
							<script type=\"text/javascript\" src=\"js/photoview.js\"></script>";*/
					break;

					default:
						$left_menu=$USER_INFO['zvezda']==1?11:$left_menu;
						if(isset($varr['circle'])) $circle = varr_int($varr['circle']); else $circle = 1;
						$file = 'bloks/my_page.php';
						$scripts .='
							<script src="http://api.tiles.mapbox.com/mapbox.js/v0.6.5/mapbox.js"></script>
							<link href="http://api.tiles.mapbox.com/mapbox.js/v0.6.5/mapbox.css" rel="stylesheet" />
							<script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
							<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
							<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />';
					break;
				}
			}

			else header("Location: /".$_SESSION['WP_USER']['user_wp']);
		break;
	}
?>
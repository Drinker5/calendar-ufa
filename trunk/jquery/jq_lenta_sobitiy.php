<?php
	function LentaList($user_wp,$rows=12,$begin=0,$circle=1,$what=''){
		global $USER, $COMMENTS, $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$html=''; //<p>Нет событий</p>
		$stamp=time();

        //Комментарии
        $maxCount=3; //Количество выводимых комментариев, если их больше заданного числа

		$massFeed=$USER->ShowHistoryLenta($user_wp,$circle,$rows,$begin);
		if(is_array($massFeed) and !isset($massFeed['error_id'])){
			$html='';

			$photo=ShowAvatar(array($_SESSION['WP_USER']['user_wp']),50,50,false);
			if(is_array($photo))$photo=$photo[0]['avatar'];

			for($i=0; $i<count($massFeed); $i++){

                //Комментарии
                $_comments='';
                $_comCount=$COMMENTS->CountComments(array('lenta_id'=>$massFeed[$i]['id']));
                $_numBegin=$_comCount>$maxCount?$_comCount-$maxCount:0;
                $_fullList=$COMMENTS->ShowComments(array('lenta_id'=>$massFeed[$i]['id']),$maxCount,$_numBegin,'ASC');

                if(is_array($_fullList))
                    foreach($_fullList as $k=>$v)
                        $_comments.='
                            <div class="wishlist-comment group" id="comments-'.$v['id'].'-id">
                                <img src="'.$v['user']['photo'].'" class="small-avatar-img fl_l">
                                '.($v['user']['user_wp']==$_SESSION['WP_USER']['user_wp']?'
                                <a href="javascript:;" onclick="CommentsAction('.$massFeed[$i]['id'].',\'delete\','.$v['id'].',0)" class="opacity_link fl_r">
                                    <i class="small-icon icon-close"></i>
                                </a>':'').'
                                <div class="comment-content wrapped">
                                    <a href="/'.$v['user']['user_wp'].'" class="comment-author">'.$v['user']['firstname'].' '.$v['user']['lastname'].'</a><em class="comment-date">'.ShowDateRus($v['date']).'</em>
                                    <br>
                                    <span class="comment-text">
                                        '.$v['msg'].'
                                    </span>
                                </div>
                            </div>';

				switch($massFeed[$i]['deystvie'])
				{
					case 1: // Друзья
						$html.='
                    <div class="timeline-elem toggle-stop group">
                        <div class="p_r">
                        <div class="right-menu fl_r">
                            '.($_SESSION['WP_USER']['user_wp']==$user_wp?'':'
                            <ul class="actions">
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="tipE small-icon active icon-whos-near" original-title="Добавить в друзья"></i>
                                    </a>
                                </li>
                            </ul>').'
                        </div>
                        <div class="wrapped">
                            <i class="small-icon active icon-whos-near active fl_l"></i>
                            <a href="/'.$massFeed[$i]['user1']['user_wp'].'" class="s-medium-avatar fl_l">
                                <img src="'.$massFeed[$i]['user1']['photo'].'" alt="">
                            </a>
                            '.OnlineStatus($massFeed[$i]['user1']['status_chat'],'-small fl_l').'
                            <div class="centered wrapped">
                                <div class="group">
                                    <h3 class="name fl_l"><a href="/'.$massFeed[$i]['user1']['user_wp'].'">'.$massFeed[$i]['user1']['firstname'].' '.$massFeed[$i]['user1']['lastname'].'</a></h3>
                                    <em class="date fl_r">'.ShowDateRus($massFeed[$i]['data']).'</em>
                                </div>
                                <p class="action">теперь дружит с <strong><a href="/'.$massFeed[$i]['user2']['user_wp'].'">'.$massFeed[$i]['user2']['firstname'].' '.$massFeed[$i]['user2']['lastname'].'</a></strong></p>
                                <div class="content group">
                                    <a href="/'.$massFeed[$i]['user2']['user_wp'].'" class="bordered medium-avatar fl_l">
                                        <img src="'.$massFeed[$i]['user2']['photo'].'" alt="">
                                    </a>
                                    '.OnlineStatus($massFeed[$i]['user2']['status_chat'],'-small fl_l').'
                                    <div class="info wrapped">
                                        <h3 class="name"><a href="/'.$massFeed[$i]['user2']['user_wp'].'">'.$massFeed[$i]['user2']['firstname'].' '.$massFeed[$i]['user2']['lastname'].'</a></h3>
                                        <p class="action">'.$massFeed[$i]['user2']['town_name'].', '.$massFeed[$i]['user2']['country_name'].'</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="sub-action">
                            <li>
                                <a href="#" class="opacity_link">
                                    <i class="tipE small-icon active icon-like" original-title="Мне нравится"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="opacity_link toggle-control">
                                    <i class="tipE small-icon active icon-comments" original-title="Комментировать"></i>
                                </a>
                            </li>
                        </ul>
                        </div>
                        <div class="wishlist-comments-container toggle-content no-margin-top">
                            '.($_comCount>$maxCount?'
                            <span class="toggle-comments tx_c pointer toggle-change" onclick="CommentsShow('.$massFeed[$i]['id'].','.$_numBegin.')">
                                <span class="c-control-text">
                                    Показать все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow"></i>
                                </span>
                                <span class="c-control-text imp-hide">
                                    Скрыть все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow-down"></i>
                                </span>
                            </span>':'').'
                            <div id="comments-'.$massFeed[$i]['id'].'-full"></div>
                            '.$_comments.'
                            <div id="comments-'.$massFeed[$i]['id'].'"></div>
                            <div class="feed-status-top group">
                                <form action="" onsubmit="CommentsAction('.$massFeed[$i]['id'].',\'add\',\'\',0); return false;">
                                    <div class="leave-comment">
                                        <img src="'.$photo.'" alt="" class="commenter-avatar" width="50px" height="50px"><textarea id="comments-'.$massFeed[$i]['id'].'-add" placeholder="Комментировать..."></textarea> 
                                    </div>

                                    <div class="submit-and-info">
                                        <button class="btn btn-green no-margin" type="submit">Отправить</button>
                                        <span>Shift+Enter<br>
                                        Перевод строки</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
					break;
					case 2: // Подарки
						$html.='
                    <div class="timeline-elem toggle-stop group">
                        <div class="p_r">
                        <div class="right-menu fl_r">
                            '.($_SESSION['WP_USER']['user_wp']==$user_wp?'':'
                            <ul class="actions">
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="tipE small-icon active icon-5" original-title="Ответный подарок"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="tipE small-icon active icon-review" original-title="Поблагодарить"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="tipE small-icon active icon-invite" original-title="Пригласить"></i>
                                    </a>
                                </li>
                            </ul>').'
                        </div>
                        <div class="wrapped">
                            <i class="small-icon active icon-5 fl_l"></i>
                            <a href="/'.$massFeed[$i]['user1']['user_wp'].'" class="s-medium-avatar fl_l">
                                <img src="'.$massFeed[$i]['user1']['photo'].'" alt="">
                            </a>
                            '.OnlineStatus($massFeed[$i]['user1']['status_chat'],'-small fl_l').'
                            <div class="centered wrapped">
                                <div class="group">
                                    <h3 class="name fl_l"><a href="/'.$massFeed[$i]['user1']['user_wp'].'">'.$massFeed[$i]['user1']['firstname'].' '.$massFeed[$i]['user1']['lastname'].'</a></h3>
                                    <em class="date fl_r">'.ShowDateRus($massFeed[$i]['data']).'</em>
                                </div>
                                <p class="action">Получен подарок</p>
                                <div class="content group">
                                    <a href="/gift-'.$massFeed[$i]['podarok']['akcia_id'].'" class="fl_l no-margin tx_c person-link" style="width: 152px;">
                                        <div class="bordered">
                                            <img src="'.$massFeed[$i]['podarok']['photo'].'" alt="">
                                        </div>
                                        <span>'.$massFeed[$i]['podarok']['header'].'</span>
                                    </a>
                                    <span class="fl_l timeline-middle">от</span>
                                    <a href="/'.$massFeed[$i]['user2']['user_wp'].'" class="fl_l no-margin tx_c person-link" style="width: 84px;">
                                        <div class="bordered">
                                            <img src="'.$massFeed[$i]['user2']['photo'].'" alt="">
                                        </div>
                                        <span>'.$massFeed[$i]['user2']['firstname'].' '.$massFeed[$i]['user2']['lastname'].'</span>
                                    </a>
                                    <span class="fl_l timeline-middle">в</span>
                                    <a href="/shop-'.$massFeed[$i]['podarok']['shop_id'].'" class="fl_l no-margin tx_c person-link" style="width: 152px;">
                                        <div class="bordered">
                                            <img src="'.$massFeed[$i]['podarok']['shop_logo'].'" alt="">
                                        </div>
                                        <span>'.$massFeed[$i]['podarok']['shop_name'].'</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <ul class="sub-action">
                            <li>
                                <a href="#" class="opacity_link">
                                    <i class="tipE small-icon active icon-like" original-title="Мне нравится"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="opacity_link toggle-control">
                                    <i class="tipE small-icon active icon-comments" original-title="Комментировать"></i>
                                </a>
                            </li>
                        </ul>
                        </div>
                        <div class="wishlist-comments-container toggle-content no-margin-top">
                            '.($_comCount>$maxCount?'
                            <span class="toggle-comments tx_c pointer toggle-change" onclick="CommentsShow('.$massFeed[$i]['id'].','.$_numBegin.')">
                                <span class="c-control-text">
                                    Показать все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow"></i>
                                </span>
                                <span class="c-control-text imp-hide">
                                    Скрыть все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow-down"></i>
                                </span>
                            </span>':'').'
                            <div id="comments-'.$massFeed[$i]['id'].'-full"></div>
                            '.$_comments.'
                            <div id="comments-'.$massFeed[$i]['id'].'"></div>
                            <div class="feed-status-top group">
                                <form action="" onsubmit="CommentsAction('.$massFeed[$i]['id'].',\'add\',\'\',0); return false;">
                                    <div class="leave-comment">
                                        <img src="'.$photo.'" alt="" class="commenter-avatar" width="50px" height="50px"><textarea id="comments-'.$massFeed[$i]['id'].'-add" placeholder="Комментировать..."></textarea> 
                                    </div>

                                    <div class="submit-and-info">
                                        <button class="btn btn-green no-margin" type="submit">Отправить</button>
                                        <span>Shift+Enter<br>
                                        Перевод строки</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
					break;
					case 3: // Подписка
						$html.='<div class="timeline-elem toggle-stop group">
                        <div class="p_r">
                         
                        <div class="right-menu fl_r">
                            '.($_SESSION['WP_USER']['user_wp']==$user_wp?'':'
                            <ul class="actions">
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="tipE small-icon active icon-watch" original-title="Посмотреть"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="tipE small-icon active icon-to-me" original-title="Поместить себе"></i>
                                    </a>
                                </li>
                            </ul>').'
                        </div>
                        <div class="wrapped">
                            <i class="small-icon active icon-subscription active fl_l"></i>
                            <a href="/'.$massFeed[$i]['user']['user_wp'].'" class="s-medium-avatar fl_l">
                                <img src="'.$massFeed[$i]['user']['photo'].'" alt="">
                            </a>
                            '.OnlineStatus($massFeed[$i]['user']['status_chat'],'-small fl_l').'
                            <div class="centered wrapped">
                                <div class="group">
                                    <h3 class="name fl_l"><a href="/'.$massFeed[$i]['user']['user_wp'].'">'.$massFeed[$i]['user']['firstname'].' '.$massFeed[$i]['user']['lastname'].'</a></h3>
                                    <em class="date fl_r">'.ShowDateRus($massFeed[$i]['data']).'</em>
                                </div>
                                <p class="action">сделана подписка на <strong><a href="/shop-'.$massFeed[$i]['podpiska']['shop_id'].'">'.$massFeed[$i]['podpiska']['shop_name'].'</a></strong></p>
                                <div class="content group">
                                    <a href="/shop-'.$massFeed[$i]['podpiska']['shop_id'].'" class="bordered fl_l">
                                        <img src="'.$massFeed[$i]['podpiska']['shop_logo'].'" alt="">
                                    </a>
                                    <div class="info wrapped">
                                        <h3 class="name"><a href="/shop-'.$massFeed[$i]['podpiska']['shop_id'].'">'.$massFeed[$i]['podpiska']['shop_name'].'</a></h3>
                                        <p class="description">
        '.(strlen($massFeed[$i]['podpiska']['shop_desc'])<=250?$massFeed[$i]['podpiska']['shop_desc']:mb_substr($massFeed[$i]['podpiska']['shop_desc'],'0',250,'UTF-8').'...').'
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="sub-action">
                            <li>
                                <a href="#" class="opacity_link">
                                    <i class="tipE small-icon active icon-like" original-title="Мне нравится"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="opacity_link toggle-control">
                                    <i class="tipE small-icon active icon-comments" original-title="Комментировать"></i>
                                </a>
                            </li>
                        </ul>
                        </div>
                        <div class="wishlist-comments-container toggle-content no-margin-top">
                            '.($_comCount>$maxCount?'
                            <span class="toggle-comments tx_c pointer toggle-change" onclick="CommentsShow('.$massFeed[$i]['id'].','.$_numBegin.')">
                                <span class="c-control-text">
                                    Показать все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow"></i>
                                </span>
                                <span class="c-control-text imp-hide">
                                    Скрыть все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow-down"></i>
                                </span>
                            </span>':'').'
                            <div id="comments-'.$massFeed[$i]['id'].'-full"></div>
                            '.$_comments.'
                            <div id="comments-'.$massFeed[$i]['id'].'"></div>
                            <div class="feed-status-top group">
                                <form action="" onsubmit="CommentsAction('.$massFeed[$i]['id'].',\'add\',\'\',0); return false;">
                                    <div class="leave-comment">
                                        <img src="'.$photo.'" alt="" class="commenter-avatar" width="50px" height="50px"><textarea id="comments-'.$massFeed[$i]['id'].'-add" placeholder="Комментировать..."></textarea> 
                                    </div>

                                    <div class="submit-and-info">
                                        <button class="btn btn-green no-margin" type="submit">Отправить</button>
                                        <span>Shift+Enter<br>
                                        Перевод строки</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
					break;
					case 4: // Фотоальбом
						$fotki='';
                        if(is_array($massFeed[$i]['photoalbum']['photos']))
                            foreach($massFeed[$i]['photoalbum']['photos'] as $k=>$v)
						      $fotki.='<a href="'.$v['photo_original'].'" rel="albumId'.$massFeed[$i]['id'].'"><img src="'.$v['photo'].'" alt="" class="fl_l"></a>';

						$html.='
                    <div class="timeline-elem toggle-stop group">
                        <div class="p_r">
                        <div class="right-menu fl_r">
                            '.($_SESSION['WP_USER']['user_wp']==$user_wp?'':'
                            <ul class="actions">
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="tipE small-icon active icon-photoalbum" original-title="Посмотреть"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="tipE small-icon active icon-to-me" original-title="Поместить себе"></i>
                                    </a>
                                </li>
                            </ul>').'
                        </div>
                        <div class="wrapped">
                            <i class="small-icon active icon-photoalbum fl_l"></i>
                            <a href="/'.$massFeed[$i]['user']['user_wp'].'" class="s-medium-avatar fl_l">
                                <img src="'.$massFeed[$i]['user']['photo'].'" alt="">
                            </a>
                            '.OnlineStatus($massFeed[$i]['user']['status_chat'],'-small fl_l').'
                            <div class="centered wrapped">
                                <div class="group">
                                    <h3 class="name fl_l"><a href="/'.$massFeed[$i]['user']['user_wp'].'">'.$massFeed[$i]['user']['firstname'].' '.$massFeed[$i]['user']['lastname'].'</a></h3>
                                    <em class="date fl_r">'.ShowDateRus($massFeed[$i]['data']).'</em>
                                </div>
                                <p class="action">Добавлен фотоальбом <strong><a href="#">"'.$massFeed[$i]['photoalbum']['header'].'" ('.$USER->CountPhotosIsAlbum($massFeed[$i]['photoalbum']['id']).')</a></strong></p>
                                <div class="content group">
                                    <script>$("a[rel=albumId'.$massFeed[$i]['id'].']").fancybox({\'transitionIn\':\'none\',\'transitionOut\':\'none\',});</script>'.$fotki.'
                                </div>
                            </div>
                        </div>
                        <ul class="sub-action">
                            <li>
                                <a href="#" class="opacity_link">
                                    <i class="tipE small-icon active icon-like" original-title="Мне нравится"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="opacity_link toggle-control">
                                    <i class="tipE small-icon active icon-comments" original-title="Комментировать"></i>
                                </a>
                            </li>
                        </ul>
                        </div>
                        <div class="wishlist-comments-container toggle-content no-margin-top">
                            '.($_comCount>$maxCount?'
                            <span class="toggle-comments tx_c pointer toggle-change" onclick="CommentsShow('.$massFeed[$i]['id'].','.$_numBegin.')">
                                <span class="c-control-text">
                                    Показать все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow"></i>
                                </span>
                                <span class="c-control-text imp-hide">
                                    Скрыть все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow-down"></i>
                                </span>
                            </span>':'').'
                            <div id="comments-'.$massFeed[$i]['id'].'-full"></div>
                            '.$_comments.'
                            <div id="comments-'.$massFeed[$i]['id'].'"></div>
                            <div class="feed-status-top group">
                                <form action="" onsubmit="CommentsAction('.$massFeed[$i]['id'].',\'add\',\'\',0); return false;">
                                    <div class="leave-comment">
                                        <img src="'.$photo.'" alt="" class="commenter-avatar" width="50px" height="50px"><textarea id="comments-'.$massFeed[$i]['id'].'-add" placeholder="Комментировать..."></textarea> 
                                    </div>

                                    <div class="submit-and-info">
                                        <button class="btn btn-green no-margin" type="submit">Отправить</button>
                                        <span>Shift+Enter<br>
                                        Перевод строки</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
					break;
					case 5: // Мне нравиться
						$html.='';
					break;
					case 6: // Хочу себе //Доделать
						$html.='
                    <div class="timeline-elem toggle-stop group">
                        <div class="p_r">
                        <div class="right-menu fl_r">
                            '.($_SESSION['WP_USER']['user_wp']==$user_wp?'':'
                            <ul class="actions">
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="tipE small-icon active icon-5" original-title="Ответный подарок"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="tipE small-icon active icon-watch" original-title="Посмотреть"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="tipE small-icon active icon-invite" original-title="Пригласить"></i>
                                    </a>
                                </li>
                                <!-- <li>
                                    <a class="opacity_link add_me" id="wish-add-'.$massFeed[$i]['akcia_id'].'" href="javascript:;" data-status="0" data-id="'.$massFeed[$i]['akcia_id'].'" data-shop="'.$massFeed[$i]['shop_id'].'">
                                        <strong>
                                            <i class="small-icon active icon-wish"></i>
                                            Добавить себе
                                            <i class="small-icon active icon-green-plus"></i>
                                        </strong>
                                    </a>
                                </li> -->
                            </ul>').'
                        </div>
                        <div class="wrapped">
                            <i class="small-icon active icon-wish fl_l"></i>
                            <a href="/'.$massFeed[$i]['user']['user_wp'].'" class="s-medium-avatar fl_l">
                                <img src="'.$massFeed[$i]['user']['photo'].'" alt="">
                            </a>
                            '.OnlineStatus($massFeed[$i]['user']['status_chat'],'-small fl_l').'
                            <div class="centered wrapped">
                                <div class="group">
                                    <h3 class="name fl_l"><a href="/'.$massFeed[$i]['user']['user_wp'].'">'.$massFeed[$i]['user']['firstname'].' '.$massFeed[$i]['user']['lastname'].'</a></h3>
                                    <em class="date fl_r">'.ShowDateRus($massFeed[$i]['data']).'</em>
                                </div>
                                <p class="action">Создано желание <strong><a href="/gift-'.$massFeed[$i]['akcia_id'].'">"'.$massFeed[$i]['header'].'"</a></strong></p>
                                <div class="content group">
                                    <a href="/gift-'.$massFeed[$i]['akcia_id'].'" class="bordered fl_l">
                                        <img src="'.$massFeed[$i]['akcia_photo'].'" alt="">
                                    </a>
                                    <div class="info wrapped">
                                        <h3 class="name">
                                            <strong class="other">Желание: </strong>
                                            <a href="/gift-'.$massFeed[$i]['akcia_id'].'">"'.$massFeed[$i]['header'].'"</a>
                                        </h3>
                                        <span>
                                            Подарок к: <strong>13.08.2012</strong>
                                            <br>
                                            Место получения: <strong><a href="#">Apple Store Moscow на Новослободской</a></strong>
                                        </span> 
                                        <p class="description">
                                            Повод: Давно хотел себе такой чехол. Классный и все такое... и бла-бла-бла
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="sub-action">
                            <li>
                                <a href="#" class="opacity_link">
                                    <i class="tipE small-icon active icon-like" original-title="Мне нравится"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="opacity_link toggle-control">
                                    <i class="tipE small-icon active icon-comments" original-title="Комментировать"></i>
                                </a>
                            </li>
                        </ul>
                        </div>
                        <div class="wishlist-comments-container toggle-content no-margin-top">
                            '.($_comCount>$maxCount?'
                            <span class="toggle-comments tx_c pointer toggle-change" onclick="CommentsShow('.$massFeed[$i]['id'].','.$_numBegin.')">
                                <span class="c-control-text">
                                    Показать все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow"></i>
                                </span>
                                <span class="c-control-text imp-hide">
                                    Скрыть все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow-down"></i>
                                </span>
                            </span>':'').'
                            <div id="comments-'.$massFeed[$i]['id'].'-full"></div>
                            '.$_comments.'
                            <div id="comments-'.$massFeed[$i]['id'].'"></div>
                            <div class="feed-status-top group">
                                <form action="" onsubmit="CommentsAction('.$massFeed[$i]['id'].',\'add\',\'\',0); return false;">
                                    <div class="leave-comment">
                                        <img src="'.$photo.'" alt="" class="commenter-avatar" width="50px" height="50px"><textarea id="comments-'.$massFeed[$i]['id'].'-add" placeholder="Комментировать..."></textarea> 
                                    </div>

                                    <div class="submit-and-info">
                                        <button class="btn btn-green no-margin" type="submit">Отправить</button>
                                        <span>Shift+Enter<br>
                                        Перевод строки</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
					break;
					case 7: // Комментарии
						$html.='';
					break;
					case 8: // Я здесь
						$html.='
                    <div class="timeline-elem toggle-stop group">
                        <div class="p_r">
                        <div class="right-menu fl_r">
                            '.($_SESSION['WP_USER']['user_wp']==$user_wp?'':'
                            <ul class="actions">
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="tipE small-icon active icon-5" original-title="Ответный подарок"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="tipE small-icon active icon-watch" original-title="Посмотреть"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                            <i class="small-icon active icon-wish"></i>
                                    </a>
                                </li>
                            </ul>').'
                        </div>
                        <div class="wrapped">
                            <i class="small-icon active icon-check-in active fl_l"></i>
                            <a href="/'.$massFeed[$i]['user']['user_wp'].'" class="s-medium-avatar fl_l">
                                <img src="'.$massFeed[$i]['user']['photo'].'" alt="">
                            </a>
                            '.OnlineStatus($massFeed[$i]['user']['status_chat'],'-small fl_l').'
                            <div class="centered wrapped">
                                <div class="group">
                                    <h3 class="name fl_l"><a href="/'.$massFeed[$i]['user']['user_wp'].'">'.$massFeed[$i]['user']['firstname'].' '.$massFeed[$i]['user']['lastname'].'</a></h3>
                                    <em class="date fl_r">'.ShowDateRus($massFeed[$i]['data']).'</em>
                                </div>
                                <p class="action">check-in в <strong><a href="#">'.$massFeed[$i]['shop_name'].'</a></strong></p>
                                <div class="content group">
                                    <a href="#" class="bordered fl_l">
                                        <img src="'.$massFeed[$i]['shop_photo'].'" alt="">
                                    </a>
                                    <div class="info wrapped">
                                        <h3 class="name"><a href="#">'.$massFeed[$i]['shop_name'].'</a></h3>
                                        <p>
                                            <i class="small-icon icon-address active"></i>
                                            Москва, ул. Фрунзенская, какой-то там дом
                                        </p>
                                        <p><strong>+7 495 4542423324</strong></p>
                                        <a class="link" target="_blank" href="http://www.applestore.ru"><strong>www.applestore.ru</strong></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <ul class="sub-action">
                            <li>
                                <a href="#" class="opacity_link">
                                    <i class="tipE small-icon active icon-like" original-title="Мне нравится"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="opacity_link toggle-control">
                                    <i class="tipE small-icon active icon-comments" original-title="Комментировать"></i>
                                </a>
                            </li>
                        </ul>
                        </div>
                        <div class="wishlist-comments-container toggle-content no-margin-top">
                            '.($_comCount>$maxCount?'
                            <span class="toggle-comments tx_c pointer toggle-change" onclick="CommentsShow('.$massFeed[$i]['id'].','.$_numBegin.')">
                                <span class="c-control-text">
                                    Показать все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow"></i>
                                </span>
                                <span class="c-control-text imp-hide">
                                    Скрыть все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow-down"></i>
                                </span>
                            </span>':'').'
                            <div id="comments-'.$massFeed[$i]['id'].'-full"></div>
                            '.$_comments.'
                            <div id="comments-'.$massFeed[$i]['id'].'"></div>
                            <div class="feed-status-top group">
                                <form action="" onsubmit="CommentsAction('.$massFeed[$i]['id'].',\'add\',\'\',0); return false;">
                                    <div class="leave-comment">
                                        <img src="'.$photo.'" alt="" class="commenter-avatar" width="50px" height="50px"><textarea id="comments-'.$massFeed[$i]['id'].'-add" placeholder="Комментировать..."></textarea> 
                                    </div>

                                    <div class="submit-and-info">
                                        <button class="btn btn-green no-margin" type="submit">Отправить</button>
                                        <span>Shift+Enter<br>
                                        Перевод строки</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
					break;
					case 9: // получил подарок
						$html.='';
					break;
                    case 10: // Обновлен статус/Оставил сообщение
                        $html.='
                    <div class="timeline-elem toggle-stop group">
                        <div class="p_r">
                            <div class="right-menu fl_r">
                            </div>
                            <div class="wrapped">
                                <i class="small-icon active icon-status fl_l"></i>
                                <a href="/'.$massFeed[$i]['user']['user_wp'].'" class="s-medium-avatar fl_l">
                                    <img src="'.$massFeed[$i]['user']['photo'].'" alt="">
                                </a>
                                '.OnlineStatus($massFeed[$i]['user']['status_chat'],'-small fl_l').'
                                <div class="centered wrapped">
                                    <div class="group">
                                        <h3 class="name fl_l"><a href="/'.$massFeed[$i]['user']['user_wp'].'">'.$massFeed[$i]['user']['firstname'].' '.$massFeed[$i]['user']['lastname'].'</a></h3>
                                        <em class="date fl_r">'.ShowDateRus($massFeed[$i]['data']).'</em>
                                    </div>
                                    <p class="action">'.($massFeed[$i]['user']['user_wp']==$user_wp?'У меня новости':'Оставил'.(@$userInfo['sex']==2?'а':'').' сообщение').'</p>
                                    <div class="content group">
                                        '.$massFeed[$i]['status'].'
                                    </div>
                                </div>
                            </div>
                            <ul class="sub-action">
                                <li>
                                	<a href="#" class="opacity_link">
                                        <i class="tipE small-icon active icon-like" original-title="Мне нравится"></i>
                                    </a>
                                </li>
                                <li>
                                	<a href="#" class="opacity_link toggle-control">
                                        <i class="tipE small-icon active icon-comments" original-title="Комментировать"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="wishlist-comments-container toggle-content no-margin-top">
                            '.($_comCount>$maxCount?'
                            <span class="toggle-comments tx_c pointer toggle-change" onclick="CommentsShow('.$massFeed[$i]['id'].','.$_numBegin.')">
                                <span class="c-control-text">
                                    Показать все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow"></i>
                                </span>
                                <span class="c-control-text imp-hide">
                                    Скрыть все комментарии <span class="c-control-text-2">(<span id="comments-'.$massFeed[$i]['id'].'-count-other">'.$_numBegin.'</span>)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow-down"></i>
                                </span>
                            </span>':'').'
                            <div id="comments-'.$massFeed[$i]['id'].'-full"></div>
                            '.$_comments.'
                            <div id="comments-'.$massFeed[$i]['id'].'"></div>
                            <div class="feed-status-top group">
                                <form action="" onsubmit="CommentsAction('.$massFeed[$i]['id'].',\'add\',\'\',0); return false;">
                                    <div class="leave-comment">
                                        <img src="'.$photo.'" alt="" class="commenter-avatar" width="50px" height="50px"><textarea id="comments-'.$massFeed[$i]['id'].'-add" placeholder="Комментировать..."></textarea> 
                                    </div>

                                    <div class="submit-and-info">
                                        <button class="btn btn-green no-margin" type="submit">Отправить</button>
                                        <span>Shift+Enter<br>
                                        Перевод строки</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>';
                    break;
				}
			}
		}

		$resultArray=array(
				"html"=>$html,
				"uid" =>$stamp
		);

		if($what=='json')echo json_encode($resultArray);
		else             echo $html;
	}

	if(isset($_POST['list'])){
		LentaList($_POST['user_wp'],$_POST['items'],$_POST['list'],$_POST['circle'],'json');
	}
?>
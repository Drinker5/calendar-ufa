<?php
	function FeedList($rows=12,$begin=0,$circle=1,$what=''){
		global $USER, $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$html='<p>Нет событий</p>';
		$stamp=time();

		$massFeed=$USER->ShowMassHistoryLenta($circle,$rows,$begin);
		if(is_array($massFeed) and !isset($massFeed['error_id'])){
			$html='';

			for($i=0; $i<count($massFeed); $i++){
				switch($massFeed[$i]['deystvie'])
				{
					case 1: // Друзья
						$html.='
                    <div class="timeline-elem toggle-stop group">
                        <div class="p_r">
                        <div class="separator"></div>
                        <div class="right-menu fl_r">
                            <ul class="actions">
                                <li>
                                    <a href="#" class="opacity_link">
                                        <strong>
                                            <i class="small-icon active icon-whos-near"></i>
                                            Добавить в друзья
                                            <i class="small-icon active icon-green-plus"></i>
                                        </strong>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="wrapped">
                            <i class="small-icon active icon-whos-near active fl_l"></i>
                            <a href="/'.$massFeed[$i]['user1']['user_wp'].'" class="s-medium-avatar fl_l">
                                <img src="'.$massFeed[$i]['user1']['photo'].'" alt="">
                            </a>
                            '.OnlineStatus($massFeed[$i]['user1']['status_chat'],'-small fl_l').'
                            <div class="centered wrapped">
                                <div class="group">
                                    <h3 class="name fl_l"><a href="#">'.$massFeed[$i]['user1']['firstname'].' '.$massFeed[$i]['user1']['lastname'].'</a></h3>
                                    <em class="date fl_r">'.$massFeed[$i]['data'].'</em>
                                </div>
                                <p class="action">теперь дружит с <strong><a href="/'.$massFeed[$i]['user1']['user_wp'].'">'.$massFeed[$i]['user2']['firstname'].' '.$massFeed[$i]['user2']['lastname'].'</a></strong></p>
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
                                    <strong>
                                        <i class="small-icon active icon-like"></i>
                                        Мне нравится
                                    </strong>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="opacity_link toggle-control">
                                    <strong>
                                        <i class="small-icon active icon-comments"></i>
                                        Комментировать (17)
                                    </strong>
                                </a>
                            </li>
                        </ul>
                        </div>
                        <div class="wishlist-comments-container toggle-content no-margin-top">
                            <a href="#" class="toggle-comments tx_c show-all-comments">
                                <span class="c-control-text">
                                    Показать все комментарии <span class="c-control-text-2">(17)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow"></i>
                                </span>
                                <span class="c-control-text hide">
                                    Скрыть все комментарии <span class="c-control-text-2">(17)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow-down"></i>
                                </span>
                            </a>
                            <div class="wishlist-comment group">
                                <img src="pic/small-avatar.png" class="small-avatar-img fl_l">
                                <a href="#" class="opacity_link fl_r">
                                    <i class="small-icon icon-delete active"></i>
                                </a>
                                <div class="comment-content wrapped">
                                    <span class="comment-author">Леонид Р.</span><em class="comment-date">Июнь 12, 2012</em>
                                    <br>
                                    <span class="comment-text">
                                        Легендарное место от А.Соркина и И.Ланцмана на Красном Октябре. Реки алкоголя, море девушек и
                                        океан сумасшествия. Только для членов клуба DIVES: face control, 50% дисконт, гарантия наличия стола.
                                    </span>
                                </div>
                            </div>
                            <div class="wishlist-comment group">
                                <img src="pic/small-avatar.png" class="small-avatar-img fl_l">
                                <a href="#" class="opacity_link fl_r">
                                    <i class="small-icon icon-delete active"></i>
                                </a>
                                <div class="comment-content wrapped">
                                    <span class="comment-author">Леонид Р.</span><em class="comment-date">Июнь 12, 2012</em>
                                    <br>
                                    <span class="comment-text">
                                        Легендарное место от А.Соркина и И.Ланцмана на Красном Октябре. Реки алкоголя, море девушек и
                                        океан сумасшествия. Только для членов клуба DIVES: face control, 50% дисконт, гарантия наличия стола.
                                    </span>
                                </div>
                            </div>
                            <div class="feed-status-top group">
                                <button class="btn btn-green fl_r no-margin" type="submit">Отправить</button>
                                <div class="feed-status2 wrapped">
                                    <div class="group">
                                        <span class="arrow_box2 fl_l">Комментировать</span>
                                        <div class="wrapped">
                                            <input type="text" class="no-margin" style="width: 100%;" placeholder="Оставь свое сообщение или отзыв">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
					break;
					case 2: // Подарки
						$html.='
                    <div class="timeline-elem toggle-stop group">
                        <div class="p_r">
                        <div class="separator"></div>
                        <div class="right-menu fl_r">
                            <ul class="actions">
                                <li>
                                    <a href="#" class="opacity_link">
                                        <strong>
                                            <i class="small-icon active icon-5"></i>
                                            Ответный подарок
                                        </strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                        <strong>
                                            <i class="small-icon active icon-review"></i>
                                            Поблагодарить
                                        </strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                        <strong>
                                            <i class="small-icon active icon-invite"></i>
                                            Пригласить
                                        </strong>
                                    </a>
                                </li>
                            </ul>
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
                                    <em class="date fl_r">'.$massFeed[$i]['data'].'</em>
                                </div>
                                <p class="action">Получен подарок</p>
                                <div class="content group">
                                    <a href="#" class="fl_l no-margin tx_c person-link">
                                        <div class="bordered">
                                            <img src="'.$massFeed[$i]['podarok']['photo'].'" alt="">
                                        </div>
                                        <span>'.$massFeed[$i]['podarok']['header'].'</span>
                                    </a>
                                    <span class="fl_l timeline-middle">от</span>
                                    <a href="/'.$massFeed[$i]['user2']['user_wp'].'" class="fl_l no-margin tx_c person-link">
                                        <div class="bordered">
                                            <img src="'.$massFeed[$i]['user2']['photo'].'" alt="">
                                        </div>
                                        <span>'.$massFeed[$i]['user2']['firstname'].' '.$massFeed[$i]['user2']['lastname'].'</span>
                                    </a>
                                    <span class="fl_l timeline-middle">в</span>
                                    <a href="#" class="fl_l no-margin tx_c person-link">
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
                                    <strong>
                                        <i class="small-icon active icon-like"></i>
                                        Мне нравится
                                    </strong>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="opacity_link toggle-control">
                                    <strong>
                                        <i class="small-icon active icon-comments"></i>
                                        Комментировать (17)
                                    </strong>
                                </a>
                            </li>
                        </ul>
                        </div>
                        <div class="wishlist-comments-container toggle-content no-margin-top">
                            <a href="#" class="toggle-comments tx_c show-all-comments">
                                <span class="c-control-text">
                                    Показать все комментарии <span class="c-control-text-2">(17)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow"></i>
                                </span>
                                <span class="c-control-text hide">
                                    Скрыть все комментарии <span class="c-control-text-2">(17)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow-down"></i>
                                </span>
                            </a>
                            <div class="wishlist-comment group">
                                <img src="pic/small-avatar.png" class="small-avatar-img fl_l">
                                <a href="#" class="opacity_link fl_r">
                                    <i class="small-icon icon-delete active"></i>
                                </a>
                                <div class="comment-content wrapped">
                                    <span class="comment-author">Леонид Р.</span><em class="comment-date">Июнь 12, 2012</em>
                                    <br>
                                    <span class="comment-text">
                                        Легендарное место от А.Соркина и И.Ланцмана на Красном Октябре. Реки алкоголя, море девушек и
                                        океан сумасшествия. Только для членов клуба DIVES: face control, 50% дисконт, гарантия наличия стола.
                                    </span>
                                </div>
                            </div>
                            <div class="wishlist-comment group">
                                <img src="pic/small-avatar.png" class="small-avatar-img fl_l">
                                <a href="#" class="opacity_link fl_r">
                                    <i class="small-icon icon-delete active"></i>
                                </a>
                                <div class="comment-content wrapped">
                                    <span class="comment-author">Леонид Р.</span><em class="comment-date">Июнь 12, 2012</em>
                                    <br>
                                    <span class="comment-text">
                                        Легендарное место от А.Соркина и И.Ланцмана на Красном Октябре. Реки алкоголя, море девушек и
                                        океан сумасшествия. Только для членов клуба DIVES: face control, 50% дисконт, гарантия наличия стола.
                                    </span>
                                </div>
                            </div>
                            <div class="feed-status-top group">
                                <button class="btn btn-green fl_r no-margin" type="submit">Отправить</button>
                                <div class="feed-status2 wrapped">
                                    <div class="group">
                                        <span class="arrow_box2 fl_l">Комментировать</span>
                                        <div class="wrapped">
                                            <input type="text" class="no-margin" style="width: 100%;" placeholder="Оставь свое сообщение или отзыв">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
					break;
					case 3: // Подписка
						$html.='';
					break;
					case 4: // Фотоальбом
						$fotki='';
						foreach($massFeed[$i]['photoalbum']['photos'] as $k=>$v){
							$fotki.='<img src="'.$v['photo'].'" alt="" class="fl_l">';
						}
						$html.='
                    <div class="timeline-elem toggle-stop group">
                        <div class="p_r">
                        <div class="separator"></div>
                        <div class="right-menu fl_r">
                            <ul class="actions">
                                <li>
                                    <a href="#" class="opacity_link">
                                        <strong>
                                            <i class="small-icon icon-photoalbum active"></i>
                                            Посмотреть
                                        </strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                        <strong>
                                            <i class="small-icon active icon-to-me"></i>
                                            Поместить себе
                                        </strong>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="wrapped">
                            <i class="small-icon active icon-photoalbum fl_l"></i>
                            <a href="/'.$massFeed[$i]['user']['user_wp'].'" class="s-medium-avatar fl_l">
                                <img src="'.$massFeed[$i]['user']['photo'].'" alt="">
                            </a>
                            '.OnlineStatus($massFeed[$i]['user1']['status_chat'],'-small fl_l').'
                            <div class="centered wrapped">
                                <div class="group">
                                    <h3 class="name fl_l"><a href="/'.$massFeed[$i]['user']['user_wp'].'">'.$massFeed[$i]['user']['firstname'].' '.$massFeed[$i]['user']['lastname'].'</a></h3>
                                    <em class="date fl_r">'.$massFeed[$i]['data'].'</em>
                                </div>
                                <p class="action">Добавлен фотоальбом <strong><a href="#">"'.$massFeed[$i]['photoalbum']['header'].'" ('.CountPhotosIsAlbum($massFeed[$i]['photoalbum']['id']).')</a></strong></p>
                                <div class="content group">
                                    '.$fotki.'
                                </div>
                            </div>
                        </div>
                        <ul class="sub-action">
                            <li>
                                <a href="#" class="opacity_link">
                                    <strong>
                                        <i class="small-icon active icon-like"></i>
                                        Мне нравится
                                    </strong>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="opacity_link toggle-control">
                                    <strong>
                                        <i class="small-icon active icon-comments"></i>
                                        Комментировать (17)
                                    </strong>
                                </a>
                            </li>
                        </ul>
                        </div>
                        <div class="wishlist-comments-container toggle-content no-margin-top">
                            <a href="#" class="toggle-comments tx_c show-all-comments">
                                <span class="c-control-text">
                                    Показать все комментарии <span class="c-control-text-2">(17)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow"></i>
                                </span>
                                <span class="c-control-text hide">
                                    Скрыть все комментарии <span class="c-control-text-2">(17)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow-down"></i>
                                </span>
                            </a>
                            <div class="wishlist-comment group">
                                <img src="pic/small-avatar.png" class="small-avatar-img fl_l">
                                <a href="#" class="opacity_link fl_r">
                                    <i class="small-icon icon-delete active"></i>
                                </a>
                                <div class="comment-content wrapped">
                                    <span class="comment-author">Леонид Р.</span><em class="comment-date">Июнь 12, 2012</em>
                                    <br>
                                    <span class="comment-text">
                                        Легендарное место от А.Соркина и И.Ланцмана на Красном Октябре. Реки алкоголя, море девушек и
                                        океан сумасшествия. Только для членов клуба DIVES: face control, 50% дисконт, гарантия наличия стола.
                                    </span>
                                </div>
                            </div>
                            <div class="wishlist-comment group">
                                <img src="pic/small-avatar.png" class="small-avatar-img fl_l">
                                <a href="#" class="opacity_link fl_r">
                                    <i class="small-icon icon-delete active"></i>
                                </a>
                                <div class="comment-content wrapped">
                                    <span class="comment-author">Леонид Р.</span><em class="comment-date">Июнь 12, 2012</em>
                                    <br>
                                    <span class="comment-text">
                                        Легендарное место от А.Соркина и И.Ланцмана на Красном Октябре. Реки алкоголя, море девушек и
                                        океан сумасшествия. Только для членов клуба DIVES: face control, 50% дисконт, гарантия наличия стола.
                                    </span>
                                </div>
                            </div>
                            <div class="feed-status-top group">
                                <button class="btn btn-green fl_r no-margin" type="submit">Отправить</button>
                                <div class="feed-status2 wrapped">
                                    <div class="group">
                                        <span class="arrow_box2 fl_l">Комментировать</span>
                                        <div class="wrapped">
                                            <input type="text" class="no-margin" style="width: 100%;" placeholder="Оставь свое сообщение или отзыв">
                                        </div>
                                    </div>
                                </div>
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
                        <div class="separator"></div>
                        <div class="right-menu fl_r">
                            <ul class="actions">
                                <li>
                                    <a href="#" class="opacity_link">
                                        <strong>
                                            <i class="small-icon active icon-5"></i>
                                            Подарить
                                        </strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                        <strong>
                                            <i class="small-icon active icon-watch"></i>
                                            Посмотреть
                                        </strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                        <strong>
                                            <i class="small-icon active icon-invite"></i>
                                            Добавить себе
                                            <i class="small-icon icon-green-plus"></i>
                                        </strong>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="wrapped">
                            <i class="small-icon active icon-wish fl_l"></i>
                            <a href="/'.$massFeed[$i]['user']['user_wp'].'" class="s-medium-avatar fl_l">
                                <img src="'.$massFeed[$i]['user']['photo'].'" alt="">
                            </a>
                            '.OnlineStatus($massFeed[$i]['user1']['status_chat'],'-small fl_l').'
                            <div class="centered wrapped">
                                <div class="group">
                                    <h3 class="name fl_l"><a href="/'.$massFeed[$i]['user']['user_wp'].'">'.$massFeed[$i]['user']['firstname'].' '.$massFeed[$i]['user']['lastname'].'</a></h3>
                                    <em class="date fl_r">'.$massFeed[$i]['data'].'</em>
                                </div>
                                <p class="action">Создано желание <strong><a href="#">"'.$massFeed[$i]['header'].'"</a></strong></p>
                                <div class="content group">
                                    <a href="#" class="bordered fl_l">
                                        <img src="pic/medium-avatar.png" alt="">
                                    </a>
                                    <div class="info wrapped">
                                        <h3 class="name">
                                            <strong class="other">Желание: </strong>
                                            <a href="#">"IPhone 4S"</a>
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
                                    <strong>
                                        <i class="small-icon active icon-like"></i>
                                        Мне нравится
                                    </strong>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="opacity_link toggle-control">
                                    <strong>
                                        <i class="small-icon active icon-comments"></i>
                                        Комментировать (17)
                                    </strong>
                                </a>
                            </li>
                        </ul>
                        </div>
                        <div class="wishlist-comments-container toggle-content no-margin-top">
                            <a href="#" class="toggle-comments tx_c show-all-comments">
                                <span class="c-control-text">
                                    Показать все комментарии <span class="c-control-text-2">(17)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow"></i>
                                </span>
                                <span class="c-control-text hide">
                                    Скрыть все комментарии <span class="c-control-text-2">(17)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow-down"></i>
                                </span>
                            </a>
                            <div class="wishlist-comment group">
                                <img src="pic/small-avatar.png" class="small-avatar-img fl_l">
                                <a href="#" class="opacity_link fl_r">
                                    <i class="small-icon icon-delete active"></i>
                                </a>
                                <div class="comment-content wrapped">
                                    <span class="comment-author">Леонид Р.</span><em class="comment-date">Июнь 12, 2012</em>
                                    <br>
                                    <span class="comment-text">
                                        Легендарное место от А.Соркина и И.Ланцмана на Красном Октябре. Реки алкоголя, море девушек и
                                        океан сумасшествия. Только для членов клуба DIVES: face control, 50% дисконт, гарантия наличия стола.
                                    </span>
                                </div>
                            </div>
                            <div class="wishlist-comment group">
                                <img src="pic/small-avatar.png" class="small-avatar-img fl_l">
                                <a href="#" class="opacity_link fl_r">
                                    <i class="small-icon icon-delete active"></i>
                                </a>
                                <div class="comment-content wrapped">
                                    <span class="comment-author">Леонид Р.</span><em class="comment-date">Июнь 12, 2012</em>
                                    <br>
                                    <span class="comment-text">
                                        Легендарное место от А.Соркина и И.Ланцмана на Красном Октябре. Реки алкоголя, море девушек и
                                        океан сумасшествия. Только для членов клуба DIVES: face control, 50% дисконт, гарантия наличия стола.
                                    </span>
                                </div>
                            </div>
                            <div class="feed-status-top group">
                                <button class="btn btn-green fl_r no-margin" type="submit">Отправить</button>
                                <div class="feed-status2 wrapped">
                                    <div class="group">
                                        <span class="arrow_box2 fl_l">Комментировать</span>
                                        <div class="wrapped">
                                            <input type="text" class="no-margin" style="width: 100%;" placeholder="Оставь свое сообщение или отзыв">
                                        </div>
                                    </div>
                                </div>
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
                        <div class="separator"></div>
                        <div class="right-menu fl_r">
                            <ul class="actions">
                                <li>
                                    <a href="#" class="opacity_link">
                                        <strong>
                                            <i class="small-icon active icon-5"></i>
                                            Подарить
                                        </strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                        <strong>
                                            <i class="small-icon active icon-watch"></i>
                                            Посмотреть
                                        </strong>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="opacity_link">
                                        <strong>
                                            <i class="small-icon active icon-wish"></i>
                                            Добавить себе
                                            <i class="small-icon active icon-green-plus"></i>
                                        </strong>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="wrapped">
                            <i class="small-icon active icon-check-in active fl_l"></i>
                            <a href="/'.$massFeed[$i]['user']['user_wp'].'" class="s-medium-avatar fl_l">
                                <img src="'.$massFeed[$i]['user']['photo'].'" alt="">
                            </a>
                            '.OnlineStatus($massFeed[$i]['user1']['status_chat'],'-small fl_l').'
                            <div class="centered wrapped">
                                <div class="group">
                                    <h3 class="name fl_l"><a href="/'.$massFeed[$i]['user']['user_wp'].'">'.$massFeed[$i]['user']['firstname'].' '.$massFeed[$i]['user']['lastname'].'</a></h3>
                                    <em class="date fl_r">'.$massFeed[$i]['data'].'</em>
                                </div>
                                <p class="action">check-in в <strong><a href="#">'.$massFeed[$i]['shop_name'].'</a></strong></p>
                                <div class="content group">
                                    <a href="#" class="bordered fl_l">
                                        <img src="'.$massFeed[$i]['shop_name'].'" alt="">
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
                                    <strong>
                                        <i class="small-icon active icon-like"></i>
                                        Мне нравится
                                    </strong>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="opacity_link toggle-control">
                                    <strong>
                                        <i class="small-icon active icon-comments"></i>
                                        Комментировать (17)
                                    </strong>
                                </a>
                            </li>
                        </ul>
                        </div>
                        <div class="wishlist-comments-container toggle-content no-margin-top">
                            <a href="#" class="toggle-comments tx_c show-all-comments">
                                <span class="c-control-text">
                                    Показать все комментарии <span class="c-control-text-2">(17)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow"></i>
                                </span>
                                <span class="c-control-text hide">
                                    Скрыть все комментарии <span class="c-control-text-2">(17)</span>
                                    <br>
                                    <i class="small-icon icon-green-arrow-down"></i>
                                </span>
                            </a>
                            <div class="wishlist-comment group">
                                <img src="pic/small-avatar.png" class="small-avatar-img fl_l">
                                <a href="#" class="opacity_link fl_r">
                                    <i class="small-icon icon-delete active"></i>
                                </a>
                                <div class="comment-content wrapped">
                                    <span class="comment-author">Леонид Р.</span><em class="comment-date">Июнь 12, 2012</em>
                                    <br>
                                    <span class="comment-text">
                                        Легендарное место от А.Соркина и И.Ланцмана на Красном Октябре. Реки алкоголя, море девушек и
                                        океан сумасшествия. Только для членов клуба DIVES: face control, 50% дисконт, гарантия наличия стола.
                                    </span>
                                </div>
                            </div>
                            <div class="wishlist-comment group">
                                <img src="pic/small-avatar.png" class="small-avatar-img fl_l">
                                <a href="#" class="opacity_link fl_r">
                                    <i class="small-icon icon-delete active"></i>
                                </a>
                                <div class="comment-content wrapped">
                                    <span class="comment-author">Леонид Р.</span><em class="comment-date">Июнь 12, 2012</em>
                                    <br>
                                    <span class="comment-text">
                                        Легендарное место от А.Соркина и И.Ланцмана на Красном Октябре. Реки алкоголя, море девушек и
                                        океан сумасшествия. Только для членов клуба DIVES: face control, 50% дисконт, гарантия наличия стола.
                                    </span>
                                </div>
                            </div>
                            <div class="feed-status-top group">
                                <button class="btn btn-green fl_r no-margin" type="submit">Отправить</button>
                                <div class="feed-status2 wrapped">
                                    <div class="group">
                                        <span class="arrow_box2 fl_l">Комментировать</span>
                                        <div class="wrapped">
                                            <input type="text" class="no-margin" style="width: 100%;" placeholder="Оставь свое сообщение или отзыв">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
					break;
					case 9: // получил подарок
						$html.='';
					break;
                    case 10: // Обновлен статус
                        $html.='';
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
		FeedList($_POST['items'],$_POST['list'],$_POST['circle'],'json');
	}
?>
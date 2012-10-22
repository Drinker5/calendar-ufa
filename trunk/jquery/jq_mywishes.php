<?php
    function WishList($user_wp, $rows=10, $begin=0, $par='', $what=''){
      global $USER, $COMMENTS, $MYSQL;
      $GLOBALS['PHP_FILE']=__FILE__;
      $GLOBALS['FUNCTION']=__FUNCTION__;

      $myWishes=$USER->ShowIHochu($user_wp,$rows,$begin, $par);
      $stamp=time();

      //Комментарии
      $maxCount=3; //Количество выводимых комментариев, если их больше заданного числа

      if (is_array($myWishes)){
        $html='';
        $cart='';

        for ($i=0; $i<count($myWishes); $i++){
          $arr_id[] = $myWishes[$i]['akcia_id'];
        }
        $photo = ShowFotoAkcia($arr_id,130,91);

        for ($i=0; $i<count($myWishes); $i++){
          $cart = array(
            "wish-img-path" => $photo[$i]['foto'],
            "wish-id"       => $myWishes[$i]['akcia_id'],
            "wish-reason"   => $myWishes[$i]['reason'],
            "wish-date"     => ShowDateRus($myWishes[$i]['adddata'])
          );

          //Место
          $place_array=$USER->ShowPlace($myWishes[$i]['akcia_id']);

          //Комментарии
          $_comments='';
          $_comCount=$COMMENTS->CountComments(array('akcia_id'=>$myWishes[$i]['akcia_id']));
          $_numBegin=$_comCount>$maxCount?$_comCount-$maxCount:0;
          $_fullList=$COMMENTS->ShowComments(array('akcia_id'=>$myWishes[$i]['akcia_id']),$maxCount,$_numBegin,'ASC');

          if(is_array($_fullList))
                    foreach($_fullList as $k=>$v)
                        $_comments.='
                            <div class="wishlist-comment group" id="comments-'.$v['id'].'-id">
                                <img src="'.$v['user']['photo'].'" class="small-avatar-img fl_l">
                                <a href="javascript:;" onclick="CommentsAction('.$myWishes[$i]['akcia_id'].',\'delete\','.$v['id'].')" class="opacity_link fl_r">
                                    <i class="small-icon icon-delete active"></i>
                                </a>
                                <div class="comment-content wrapped">
                                    <span class="comment-author">'.$v['user']['firstname'].' '.$v['user']['lastname'].'</span><em class="comment-date">'.$v['date'].'</em>
                                    <br>
                                    <span class="comment-text">
                                        '.$v['msg'].'
                                    </span>
                                </div>
                            </div>';

          $html .= "<div id=\"wish".$myWishes[$i]['akcia_id']."\" class=\"wish-item group toggle-stop\">
                      <div class=\"big-avatar bordered fl_l\">
                          <img src=\"".$photo[$i]['foto']."\">
                      </div>
                      <div class=\"fl_r\">
                          <ul class=\"wishlist-actions\">
                              <li>
                                  <a href=\"#\" class=\"popover-btn wish-edit\" rel=\"".$stamp."\" data-content=\"".htmlspecialchars(json_encode($cart))."\">
                                      <i class=\"small-icon icon-edit\"></i>Редактировать<i class=\"small-icon icon-grey-arrow\"></i>
                                  </a>
                              </li>
                              <li>
                                  <a href=\"#\" id=\"del_wish\" data-wish=\"".$myWishes[$i]['akcia_id']."\" data-status=\"".$myWishes[$i]['status']."\">
                                      <i class=\"small-icon icon-delete\"></i>Удалить
                                  </a>
                              </li>
                              <li>
                                  <a href=\"#\" class=\"comments opacity_link toggle-control\">
                                    <strong>
                                        Комментариев (<span id=\"comments-".$myWishes[$i]['akcia_id']."-count0\">".$_comCount."</span>)
                                    </strong>
                                  </a>
                              </li>
                          </ul>
                      </div>

                      <div class=\"wish-content wrapped\">
                          <strong>Желание</strong><a href=\"/gift-".$myWishes[$i]['akcia_id']."\" class=\"wish-target\"><strong>".$myWishes[$i]['header']."</strong></a><span class=\"date\">".ShowDateRus($myWishes[$i]['adddata'])."</span>
                          <p>
                              <!--
                              Подарок к: <strong> Check DATE </strong>
                              <br>
                              -->
                              Место получения: <strong> ".$place_array[0]['name']." на ".$place_array[0]['adress']."</strong>
                          </p>";
                          if ($myWishes[$i]['reason'] == '')
                            $html .= "<p id=\"p_reason".$myWishes[$i]['akcia_id']."\" style=\"display: none;\">
                                          Повод: <span id=\"reason".$myWishes[$i]['akcia_id']."\" class=\"reason\"> ".$myWishes[$i]['reason']."</span>
                                      </p>";
                          else
                            $html .= "<p id=\"p_reason".$myWishes[$i]['akcia_id']."\">
                                          Повод: <span id=\"reason".$myWishes[$i]['akcia_id']."\" class=\"reason\"> ".$myWishes[$i]['reason']."</span>
                                      </p>";
            $html .= '</div>

                       <div class="wishlist-comments-container toggle-content no-margin-top">
                          '.($_comCount>$maxCount?'
                          <span class="toggle-comments tx_c pointer toggle-change" onclick="CommentsShow('.$myWishes[$i]['akcia_id'].','.$_numBegin.')">
                              <span class="c-control-text">
                                  Показать все комментарии <span class="c-control-text-2">(<span id="comments-'.$myWishes[$i]['akcia_id'].'-count1">'.$_numBegin.'</span>)</span>
                                  <br>
                                  <i class="small-icon icon-green-arrow"></i>
                              </span>
                              <span class="c-control-text imp-hide toggle-control-2">
                                  Скрыть все комментарии <span class="c-control-text-2">(<span id="comments-'.$myWishes[$i]['akcia_id'].'-count2">'.$_numBegin.'</span>)</span>
                                  <br>
                                  <i class="small-icon icon-green-arrow-down"></i>
                              </span>
                          </span>':'').'
                          <div id="comments-'.$myWishes[$i]['akcia_id'].'-full"></div>
                          '.$_comments.'
                          <div id="comments-'.$myWishes[$i]['akcia_id'].'"></div>
                          <div class="feed-status-top group">
                              <button class="btn btn-green fl_r no-margin" onclick="CommentsAction('.$myWishes[$i]['akcia_id'].',\'add\',\'akcia_id\')">Отправить</button>
                              <div class="feed-status2 wrapped">
                                  <div class="group">
                                      <span class="arrow_box2 fl_l">Комментировать</span>
                                      <div class="wrapped">
                                          <input id="comments-'.$myWishes[$i]['akcia_id'].'-add" type="text" class="no-margin" style="width: 100%;" placeholder="Оставь свое сообщение или отзыв">
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>

                      </div>
                      <div id="separator'.$myWishes[$i]['akcia_id'].'" class="separator"></div>';

                      /*
                      <!--
                      <div class=\"wishlist-comments-container\">
                          <a href=\"#\" class=\"toggle-comments tx_c\">
                              <span>
                                  Показать все комментарии <span>(17)</span>
                                  <br>
                                  <i class=\"small-icon icon-green-arrow\"></i>
                              </span>
                              <span class=\"hide\">
                                  Скрыть все комментарии <span>(17)</span>
                                  <br>
                                  <i class=\"small-icon icon-green-arrow-down\"></i>
                              </span>
                          </a>
                          <div class=\"wishlist-comment group\">
                              <img src=\"pic/small-avatar.png\" class=\"small-avatar-img fl_l\">
                              <a href=\"#\" class=\"opacity_link fl_r\">
                                  <i class=\"small-icon icon-delete\"></i>
                              </a>
                              <div class=\"comment-content wrapped\">
                                  <span class=\"comment-author\">Леонид Р.</span><em class=\"comment-date\">Июнь 12, 2012</em>
                                  <br>
                                  <span class=\"comment-text\">
                                      Легендарное место от А.Соркина и И.Ланцмана на Красном Октябре. Реки алкоголя, море девушек и
                                      океан сумасшествия. Только для членов клуба DIVES: face control, 50% дисконт, гарантия наличия стола.
                                  </span>
                              </div>
                          </div>
                          <div class=\"wishlist-comment group\">
                              <img src=\"pic/small-avatar.png\" class=\"small-avatar-img fl_l\">
                              <a href=\"#\" class=\"opacity_link fl_r\">
                                  <i class=\"small-icon icon-delete\"></i>
                              </a>
                              <div class=\"comment-content wrapped\">
                                  <span class=\"comment-author\">Леонид Р.</span><em class=\"comment-date\">Июнь 12, 2012</em>
                                  <br>
                                  <span class=\"comment-text\">
                                      Легендарное место от А.Соркина и И.Ланцмана на Красном Октябре. Реки алкоголя, море девушек и
                                      океан сумасшествия. Только для членов клуба DIVES: face control, 50% дисконт, гарантия наличия стола.
                                  </span>
                              </div>
                          </div>
                          <div class=\"comment-send fl_l group\">
                              <div class=\"arrow-block-wrap\">
                                  Комментировать
                                  <div class=\"arrow-block-inner-2\"></div>
                                  <div class=\"arrow-block-inner\"></div>
                              </div>
                                  <input type=\"text\">
                          </div>
                          <div class=\"tx_r wrapped\">
                              <a href=\"#\" class=\"btn btn-small btn-green\">Отправить</a>
                          </div>
                      </div>

                   -->
                   */

        }

        $resultArray=array(
				"html"     =>$html,
				"uid"      =>$stamp,
                "num_rows" =>count($myWishes)
		);

        if($what=='json')echo json_encode($resultArray);
		else             echo $html;

      }
      else echo "У Вас нет исполненных желаний!";
    }

    if(isset($_POST['list'])){
	  WishList($_POST['wp'], $_POST['items'], $_POST['list'], $_POST['param'],'json');
	}

//удалить желание
	if(isset($_POST['del'])){
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbusers = "pfx_users_hochu";
		$result=$MYSQL->query("DELETE FROM `".$tbusers."` WHERE `user_wp`=".(int)$_SESSION['WP_USER']['user_wp']." AND `akcia_id`=".(int)$_POST['del']);
        echo "ok";
	}

//сохранение изменений
    if(isset($_POST['wish'])){
		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;
		$tbusers = "pfx_users_hochu";
        $reason = $_POST['w_reason'];
		$result=$MYSQL->query("UPDATE $tbusers SET `reason`='$reason' WHERE `user_wp`=".(int)$_SESSION['WP_USER']['user_wp']." AND `akcia_id`=".(int)$_POST['wish']);
        echo "ok";
	}

?>
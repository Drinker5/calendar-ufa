<?php
    function WishList($user_wp, $rows=10, $begin=0, $par='', $what='', $wish_pos=0){
      global $USER, $COMMENTS, $MYSQL;
      $GLOBALS['PHP_FILE']=__FILE__;
      $GLOBALS['FUNCTION']=__FUNCTION__;

      //Параметр для загрузки желаний
      $wish_cnt = $wish_pos;

      $myWishes=$USER->ShowIHochu($user_wp,$rows,$begin, $par, $wish_cnt);
      $stamp=time();

      //Комментарии
      $maxCount=3; //Количество выводимых комментариев, если их больше заданного числа

      if (is_array($myWishes)){
        $html   ='';
        $cart   ='';
        $action = '';
        //идентификатор желания или вишлиста
        $id='';

      foreach($myWishes as $k => $v){
        //echo "key=".$k."; value=".$v['akcia_id']."<br />";
      }

        for ($i=0; $i<count($myWishes); $i++){
            $arr_id[] = $myWishes[$i]['akcia_id'];
        }

        $photo = ShowFotoAkcia($arr_id,130,91);
        //$photo = '\pic\wishlist.png';

        //print_r($myWishes);

        for ($i=0; $i<count($myWishes);$i++){

              if ($myWishes[$i]['akcia_id'] == 0){
                $id = $myWishes[$i]['id'];
                $action = 'wlist';
              }
              else{
                $id = $myWishes[$i]['akcia_id'];
                $action = 'akcia';
              }

              $cart = array(
                "wish-img-path" => $photo[$i]['foto'],
                "wish-id"       => $id,
                "wish-reason"   => $myWishes[$i]['reason'],
                "wish-date"     => ShowDateRus($myWishes[$i]['adddata'])
              );


              //Место
              $place_array=$USER->ShowPlace($myWishes[$i]['adress_id']);

              //Комментарии
              $_comments='';
              if ($myWishes[$i]['akcia_id'] == 0){
                  $_comCount=$COMMENTS->CountComments(array('wlist_id'=>$id));
                  $_numBegin=$_comCount>$maxCount?$_comCount-$maxCount:0;
                  $_fullList=$COMMENTS->ShowComments(array('wlist_id'=>$id),$maxCount,$_numBegin,'ASC');
              }
              else{
                  $_comCount=$COMMENTS->CountComments(array('akcia_id'=>$id));
                  $_numBegin=$_comCount>$maxCount?$_comCount-$maxCount:0;
                  $_fullList=$COMMENTS->ShowComments(array('akcia_id'=>$id),$maxCount,$_numBegin,'ASC');
              }

              if(is_array($_fullList))
                        foreach($_fullList as $k=>$v)
                            $_comments.='
                                <div class="wishlist-comment group" id="comments-'.$v['id'].'-id">
                                    <img src="'.$v['user']['photo'].'" class="small-avatar-img fl_l">
                                    <a href="javascript:;" onclick="CommentsAction('.$id.',\'delete\','.$v['id'].')" class="opacity_link fl_r">
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

              if ($myWishes[$i]['akcia_id'] == 0){
                $html .= "<div id=\"wish".$id."\" class=\"wish-item group toggle-stop\">
                            <div class=\"big-avatar bordered fl_l\">
                                <img src=\"/pic/wishlist.png\">
                            </div>
                            <div class=\"fl_r\">
                                <ul class=\"wishlist-actions\">
                                    <li>
                                        <a href=\"#\" class=\"comments opacity_link toggle-control\">
                                          <strong>
                                              Комментариев (<span id=\"comments-".$id."-count0\">".$_comCount."</span>)
                                          </strong>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class=\"wish-content wrapped\">
                                <strong>Wishlist: </strong><a href=\"/gift-".$id."\" class=\"wish-target\"><strong>".$myWishes[$i]['wlist_name']."</strong></a><span class=\"date\">".ShowDateRus($myWishes[$i]['adddata'])."</span>
                                <p>
                                    <!--
                                    Подарок к: <strong> Check DATE </strong>
                                    <br>
                                    -->
                                    Место получения: <strong> ".$place_array[0]['name']." на ".$place_array[0]['adress']."</strong>
                                </p>";
                                if ($myWishes[$i]['reason'] == '')
                                  $html .= "<p id=\"p_reason".$id."\" style=\"display: none;\">
                                                Повод: <span id=\"reason".$id."\" class=\"reason\"> ".$myWishes[$i]['reason']."</span>
                                            </p>";
                                else
                                  $html .= "<p id=\"p_reason".$id."\">
                                                Повод: <span id=\"reason".$id."\" class=\"reason\"> ".$myWishes[$i]['reason']."</span>
                                            </p>";

              }
              else {
                $wish_cnt++;
                $html .= "<div id=\"wish".$id."\" class=\"wish-item group toggle-stop\">
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
                                        <a href=\"#\" id=\"del_wish\" data-wish=\"".$id."\" data-status=\"".$myWishes[$i]['status']."\">
                                            <i class=\"small-icon icon-delete\"></i>Удалить
                                        </a>
                                    </li>
                                    <li>
                                        <a href=\"#\" class=\"comments opacity_link toggle-control\">
                                          <strong>
                                              Комментариев (<span id=\"comments-".$id."-count0\">".$_comCount."</span>)
                                          </strong>
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class=\"wish-content wrapped\">
                                <strong>Желание: </strong><a href=\"/gift-".$id."\" class=\"wish-target\"><strong>".$myWishes[$i]['header']."</strong></a><span class=\"date\">".ShowDateRus($myWishes[$i]['adddata'])."</span>
                                <p>
                                    <!--
                                    Подарок к: <strong> Check DATE </strong>
                                    <br>
                                    -->
                                    Место получения: <strong> ".$place_array[0]['name']." на ".$place_array[0]['adress']."</strong>
                                </p>";
                                if ($myWishes[$i]['reason'] == '')
                                  $html .= "<p id=\"p_reason".$id."\" style=\"display: none;\">
                                                Повод: <span id=\"reason".$id."\" class=\"reason\"> ".$myWishes[$i]['reason']."</span>
                                            </p>";
                                else
                                  $html .= "<p id=\"p_reason".$id."\">
                                                Повод: <span id=\"reason".$id."\" class=\"reason\"> ".$myWishes[$i]['reason']."</span>
                                            </p>";
              }

              $html .= '</div>
                             <div class="wishlist-comments-container toggle-content no-margin-top">
                                '.($_comCount>$maxCount?'
                                <span class="toggle-comments tx_c pointer toggle-change" onclick="CommentsShow('.$id.','.$_numBegin.',\''.$action.'\')">
                                    <span class="c-control-text">
                                        Показать все комментарии <span class="c-control-text-2">(<span id="comments-'.$id.'-count1">'.$_numBegin.'</span>)</span>
                                        <br>
                                        <i class="small-icon icon-green-arrow"></i>
                                    </span>
                                    <span class="c-control-text imp-hide toggle-control-2">
                                        Скрыть все комментарии <span class="c-control-text-2">(<span id="comments-'.$id.'-count2">'.$_numBegin.'</span>)</span>
                                        <br>
                                        <i class="small-icon icon-green-arrow-down"></i>
                                    </span>
                                </span>':'').'
                                <div id="comments-'.$id.'-full"></div>
                                '.$_comments.'
                                <div id="comments-'.$id.'"></div>
                                <div class="feed-status-top group">';

                                if ($myWishes[$i]['akcia_id'] == 0)
                                    $html .= '<button class="btn btn-green fl_r no-margin" onclick="CommentsAction('.$id.',\'add\',\'akcia_id\', \'wlist\')">Отправить</button>';
                                else
                                    $html .= '<button class="btn btn-green fl_r no-margin" onclick="CommentsAction('.$id.',\'add\',\'akcia_id\', \'akcia\')">Отправить</button>';

              $html .= '            <div class="feed-status2 wrapped">
                                        <div class="group">
                                            <span class="arrow_box2 fl_l">Комментировать</span>
                                            <div class="wrapped">
                                                <input id="comments-'.$id.'-add" type="text" class="no-margin" style="width: 100%;" placeholder="Оставь свое сообщение или отзыв">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            </div>
                            <div id="separator'.$id.'" class="separator"></div>';
        }


        $resultArray=array(
			"html"     =>$html,
			"uid"      =>$stamp,
            "num_rows" =>count($myWishes),
            "wish_cnt" =>$wish_cnt
		);

        if($what=='json')echo json_encode($resultArray);
		else             echo $html;

        $return_array = array(
            "wish_cnt" =>$wish_cnt
        );

        return $return_array;
      }
      else echo "У Вас нет исполненных желаний!";
    }

    if(isset($_POST['list'])){
	  WishList($_POST['wp'], $_POST['items'], $_POST['list'], $_POST['param'],'json', $_POST['wish_num']);
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
<div id="center">
    <div id="content" class="fl_r">
        <div class="title margin">
            <h2>Wishlist</h2>
        </div>
<?php
 $user_wp   = $_SESSION['WP_USER']['user_wp'];
 $html      = '';
 $_comments = '';
 //require_once ('jquery/jq_mywishes.php');
 $wl_array      = $USER->GetWlistData($wlist_id);
 $wl_count      = $USER->CountWlist();
 $wl_perf_count = $USER->CountWlist($user_wp, 'perf');

 //Комментарии
 $maxCount=3; //Количество выводимых комментариев, если их больше заданного числа
 $_comCount=$COMMENTS->CountComments(array('wlist_id'=>$wlist_id));
 $_numBegin=$_comCount>$maxCount?$_comCount-$maxCount:0;
 $_fullList=$COMMENTS->ShowComments(array('wlist_id'=>$wlist_id),$maxCount,$_numBegin,'ASC');

 if(is_array($_fullList))
        foreach($_fullList as $k=>$v)
            $_comments.='
                <div class="wishlist-comment group" id="comments-'.$v['id'].'-id">
                    <img src="'.$v['user']['photo'].'" class="small-avatar-img fl_l">
                    <a href="javascript:;" onclick="CommentsAction('.$wlist_id.',\'delete\','.$v['id'].')" class="opacity_link fl_r">
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

?>
  <div class="nav-panel group">
      <ul class="fl_r right">
          <li class="opacity_link"><a href="#">Wishlist (<?php echo $wl_count;?>)</a></li>
          <li class="opacity_link"><a href="#">Wishlist исполненный (<?php echo $wl_perf_count;?>)</a></li>
      </ul>
  </div>
<?php
 function ShowWlist($wl_array, $user_wp=0){
    global $USER, $AKCIA, $_comCount, $maxCount, $_numBegin, $_comments;

    $checked = "";
    $link    = "";
    $header  = "";
    //Место
    $place_array=$USER->ShowPlace($wl_array['adress_id']);


    $html = "<div class=\"tools_block\">
                <div class=\"wish-item group toggle-stop\">
                    <div class=\"tx_r fl_r\">
                        <a href=\"#\" class=\"make-gift show opacity_link group\">
                            <span class=\"big-circle-icon circle-icon-make-gift fl_r tx_c\"></span>
                            <div class=\"wrapped\">
                                Разослать<br>друзьям
                            </div>
                        </a>
                    </div>
                <div class=\"wish-content wrapped\">
                <strong>Wishlist: </strong><a href=\"#\" class=\"wish-target\"><strong> ".$wl_array['wlist_name']." </strong></a><span class=\"date\">".ShowDateRus($wl_array['adddata'])."</span>
                    <p>
                        <!--
                        Подарок к: <strong>  </strong>
                        <br>
                        -->
                        Место получения: <strong class=\"popover-btn name\"> ".$place_array['name']." на ".$place_array['adress']." </strong>
                    </p>
                    <p>
                        Повод: <span class=\"reason\"> ".$wl_array['reason']." </span>
                    </p>
                </div>
                <div class=\"cleared\"></div>
                <div class=\"tools wish-text\">
                    <div class=\"to-do-list\">
                        <div class=\"head-list\">
                            <div class=\"menu_tools\" id=\"menu_wish\">
                                <h1>".$wl_array['wlist_name']."</h1>
                            </div>
                            <div class=\"punktir\"></div>
                        </div>
                        <div class=\"line-list\"></div>
                        <div class=\"table-list\">
                            <table>";

                        /*структура хранения желаний: айди желания, подарено 1 или нет 0
                        $a[0] = array(1803,1);
                        */

                        $wishes_array = unserialize($wl_array['wishes_id']);
                        foreach($wishes_array as $k => $v){
                            /*
                            $v[0] - id желания
                            $v[1] - подарено или нет
                            */
                            $w_array = $AKCIA->Show($v[0],130,130);
                            if ($v[1] == 0){
                                $link    = "<a href=\"#\" class=\"blue-color\">
                                              Подарить
                                              <i class=\"small-icon icon-5\"></i>
                                            </a>";
                                $checked = "";
                                $header  = "<span>".$w_array['header']."</span>";
                            }
                            else{
                                $link    = "Подаренно";
                                $checked = "checked";
                                $header  = $w_array['header'];
                            }

                            $html .= "<tr>
                                          <td class=\"add\"><input type=\"checkbox\" $checked/></td>
                                          <td class=\"text\">
                                              $header
                                          </td>
                                          <td class=\"podarok\">
                                              $link
                                          </td>
                                      </tr>";
                        }

                  $html .= "    <tr>
                                    <td class=\"add\"></td>
                                    <td class=\"text\"></td>
                                    <td class=\"podarok\"></td>
                                </tr>
                            </table>
                            <div class=\"podlojka-list\">
                                <div class=\"podlojka-list1\">
                                    <div class=\"podlojka-list2\">
                                        <table>
                                            <tr>
                                                <td class=\"add\"></td>
                                                <td class=\"text\"></td>
                                                <td class=\"podarok\"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class=\"tools_comments tx_r\">
                        <a href=\"#\" class=\"comments opacity_link grey-dark-color-bold toggle-control\">Комментариев (<span id=\"comments-".$wl_array['id']."-count0\">".$_comCount."</span>)</a>
                    </div>";

                    $html .= '<div class="wishlist-comments-container toggle-content no-margin-top">
                                  '.($_comCount>$maxCount?'
                                  <span class="toggle-comments tx_c pointer toggle-change" onclick="CommentsShow('.$wl_array['id'].','.$_numBegin.',\'wlist\')">
                                      <span class="c-control-text">
                                          Показать все комментарии <span class="c-control-text-2">(<span id="comments-'.$wl_array['id'].'-count1">'.$_numBegin.'</span>)</span>
                                          <br>
                                          <i class="small-icon icon-green-arrow"></i>
                                      </span>
                                      <span class="c-control-text imp-hide toggle-control-2">
                                          Скрыть все комментарии <span class="c-control-text-2">(<span id="comments-'.$wl_array['id'].'-count2">'.$_numBegin.'</span>)</span>
                                          <br>
                                          <i class="small-icon icon-green-arrow-down"></i>
                                      </span>
                                  </span>':'').'
                                  <div id="comments-'.$wl_array['id'].'-full"></div>
                                  '.$_comments.'
                                  <div id="comments-'.$wl_array['id'].'"></div>
                                  <div class="feed-status-top group">
                                      <button class="btn btn-green fl_r no-margin" onclick="CommentsAction('.$wl_array['id'].',\'add\',\'akcia_id\', \'wlist\')">Отправить</button>
                                      <div class="feed-status2 wrapped">
                                         <div class="group">
                                              <span class="arrow_box2 fl_l">Комментировать</span>
                                              <div class="wrapped">
                                                  <input id="comments-'.$wl_array['id'].'-add" type="text" class="no-margin" style="width: 100%;" placeholder="Оставь свое сообщение или отзыв">
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>';

          $html .= "<div class=\"cleared\"></div>
                </div>
               </div>
             </div>";

    echo $html;


 }

 ShowWlist($wl_array);

?>
    </div>
</div>
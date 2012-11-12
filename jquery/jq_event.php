<?php
    $tbevent        = "pfx_users_event";
    $tbuserdeystvie = "pfx_users_deystvie";

	$user_wp=varr_int($_POST['user_wp']);
	$whom=varr_int($_POST['whom']);
	$userInfo=$USER->Info_min($user_wp);
	$html='';

    $stID=$MYSQL->query("INSERT INTO $tbevent (`event`,`user_wp`,`date`) VALUES ('".mysql_real_escape_string($_POST['status'])."', '".$user_wp."', NOW())");
    $id=$MYSQL->query("INSERT INTO $tbuserdeystvie (`data_add`,`user_wp`,`deystvie`,`id_deystvie`) VALUES (NOW(), ".$whom.", 10, ".$stID.")");

	if($id>0)
		$html.='
                    <div class="timeline-elem toggle-stop group">
                        <div class="p_r">
                            <div class="right-menu fl_r">
                            </div>
                            <div class="wrapped">
                                <i class="small-icon active icon-status fl_l"></i>
                                <a href="/'.$user_wp.'" class="s-medium-avatar fl_l">
                                    <img src="'.$userInfo['photo'].'" alt="">
                                </a>
                                '.OnlineStatus($userInfo['status_chat'],'-small fl_l').'
                                <div class="centered wrapped">
                                    <div class="group">
                                        <h3 class="name fl_l"><a href="/'.$userInfo['user_wp'].'">'.$userInfo['firstname'].' '.$userInfo['lastname'].'</a></h3>
                                        <em class="date fl_r">'.ShowDateRus(date('Y-M-d H:i:s')).'</em>
                                    </div>
                                    <p class="action">'.($user_wp==$whom?'У меня новости':'Оставил сообщение').'</p>
                                    <div class="content group">
                                        '.ToText($_POST['status']).'
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
                            <div id="comments-'.$id.'-full"></div>
                            <div id="comments-'.$id.'"></div>
                            <div class="feed-status-top group">
                                <form action="" onsubmit="CommentsAction('.$id.',\'add\',\'\',0); return false;">
                                    <div class="leave-comment">
                                        <img src="'.$userInfo['photo'].'" alt="" class="commenter-avatar" width="50px" height="50px"><textarea id="comments-'.$id.'-add" placeholder="Комментировать..."></textarea> 
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

		$resultArray=array(
				"html"=>$html
		);

		echo json_encode($resultArray);
?>
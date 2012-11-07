<?php
switch(@$_POST['type'])
{
    case'show':
		if(isset($_POST['id']) && $_POST['id'] > 0 && isset($_POST['num']) && $_POST['num'] > 0){
		  if (isset($_POST['page']) && $_POST['page'] == 'akcia'){
            if (isset($_POST['par']) && $_POST['par'] == 'akcia')
                $_fullList=$COMMENTS->ShowComments(array('akcia_id'=>$_POST['id']),$_POST['num'],0,'ASC');
            else
                $_fullList=$COMMENTS->ShowComments(array('wlist_id'=>$_POST['id']),$_POST['num'],0,'ASC');
          }
          else
			$_fullList=$COMMENTS->ShowComments(array('lenta_id'=>$_POST['id']),$_POST['num'],0,'ASC');

			$_html='';
			if(is_array($_fullList))
			{
				foreach($_fullList as $k=>$v)
					$_html.='
                            <div class="wishlist-comment group" id="comments-'.$v['id'].'-id">
                                <img src="'.$v['user']['photo'].'" class="small-avatar-img fl_l">
                                <a href="javascript:;" onclick="CommentsAction('.varr_int($_POST['id']).',\'delete\','.$v['id'].',1)" class="opacity_link fl_r">
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
				echo json_encode(array('html'=>$_html));
			}
            else echo json_encode(array('html'=>"xxx"));
		}
	break;
	case'add':
		if(isset($_POST['id']) && $_POST['id'] > 0 && isset($_POST['msg']) && strlen(trim($_POST['msg'])) > 0){
    		if (isset($_POST['page']) && $_POST['page'] == 'akcia'){
    		  if (isset($_POST['par']) && $_POST['par'] == 'wlist')
                $comment_id = $COMMENTS->Add(array('wlist_id'=>$_POST['id']),$_POST['msg']);
              else
                $comment_id = $COMMENTS->Add(array('akcia_id'=>$_POST['id']),$_POST['msg']);
            }
            else
                $comment_id = $COMMENTS->Add(array('lenta_id'=>$_POST['id']),$_POST['msg']);

			if($comment_id > 0){
                $uInfo=$USER->Info_min($_SESSION['WP_USER']['user_wp'],40,40);
				echo json_encode(array('html'=>'
                            <div class="wishlist-comment group" id="comments-'.$comment_id.'-id">
                                <img src="'.$uInfo['photo'].'" class="small-avatar-img fl_l">
                                <a href="javascript:;" onclick="CommentsAction('.varr_int($_POST['id']).',\'delete\','.$comment_id.')" class="opacity_link fl_r">
                                    <i class="small-icon icon-delete active"></i>
                                </a>
                                <div class="comment-content wrapped">
                                    <span class="comment-author">'.$uInfo['firstname'].' '.$uInfo['lastname'].'</span><em class="comment-date">'.date('Y-m-d H:i:s').'</em>
                                    <br>
                                    <span class="comment-text">
                                        '.ToText($_POST['msg']).'
                                    </span>
                                </div>
                            </div>'));
			}
		}
	break;
	case'delete':
		if(isset($_POST['n']) && $_POST['n'] > 0){
        	$COMMENTS->Delete(varr_int($_POST['n']));
        	echo json_encode(array('status'=>'success'));
        }
	break;
}
?>
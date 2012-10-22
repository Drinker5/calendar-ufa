<?php

function ShowListPhotoAlbums($album_id,$user_wp,$rows){
	global $USER;
	
	$user_wp   = (int) $user_wp;
	$return    = "";
	$add_album = "";
	$i         = 0;
	
	if($album_id > 0)
	 $albums  = $USER->ShowLogoPhotoAlbum($album_id);
	else
	 $albums  = $USER->ShowListPhotoAlbums($user_wp,$rows);	
	
	
	if($_SESSION['WP_USER']['user_wp'] == $user_wp){
		$add_album = "<div class=\"roundedbutton greenbutton\" id=\"btnAddAlbum\" onClick=\"xajax_ShowAddAlbum(); return false;\"><sub></sub><div>Добавить фотоальбом</div><sup></sup></div>";
		$add_album2 = "<div class=\"roundedbutton greenbutton\" id=\"btnAddAlbum2\" onClick=\"xajax_ShowAddAlbum(); return false;\"><sub></sub><div>Добавить фотоальбом</div><sup></sup></div>";
	}
	
	if(is_array($albums)){
		foreach($albums as $key=>$value){
			
			if($value['security'] == 1){
			
			if($value['user_wp'] == (int)$_SESSION['WP_USER']['user_wp']){
				$links = "<li style=\"border-top:1px solid #ccc; border-bottom:none; padding: 4px 0; text-align:right;\"><a href=\"#\" onClick=\"xajax_ShowEditPhotoAlbum(".$value['album_id']."); xajax.$('idLenta').innerHTML='<center>".loading_clock."</center>'; return false;\">Редактировать</a> <a href=\"#\" onClick=\"if(confirm('Вы действительно хотите удалить фотоальбом? \\n".htmlspecialchars($value['header'])."')){ xajax_DeletePhotoAlbum(".$value['album_id']."); xajax.$('idLenta').innerHTML='<center>".loading_clock."</center>'; } return false;\">Удалить</a></li>";
			}
			
			$return .= "
			<div class=\"block\">
			  <p class=\"header\">".$value['header']."&nbsp;&nbsp; ".$value['data']."&nbsp;&nbsp;&nbsp;</p>
			<ul>
			 <li id=\"idAlbum_".$value['album_id']."\" style=\"border-bottom:none;\">
			  <div class=\"imgfriend\">
			   <a href=\"#\" onClick=\"xajax_ShowPhotoAlbum(".$value['user_wp'].",".$value['album_id']."); xajax.$('idAlbum_".$value['album_id']."').innerHTML='<center>".loading_clock."</center>'; return false;\"><img style=\"border:7px solid #ccc;\" src=\"".$value['photo']."\"><br /><font>Просмотр</font></a>
			  </div>
			 </li>
			 ".@$links."
			</ul>
			</div>
			";
			} else {
				$i++;
				/*
				 $return .= "
			       <div class=\"block\">
			        <p class=\"header\">Фотоальбом закрыт для просмотра&nbsp;&nbsp; ".$value['data']."&nbsp;&nbsp;&nbsp;</p>
			        <ul>
			         <li><center><b style=\"color:red\">Пользователь ограничил доступ к этому фотоальбому</b></center></li>
			        </ul>
			       </div>";
			       */
			}
		}
		
		$return = $add_album.$return.$add_album2;
		
		if($i == count($albums))
		 $return = "
		   <div class=\"block\">
		    <p class=\"header\"></p>
		    <ul>
		     <li style=\"border-bottom:none;\"><center><b style=\"color:red\">Пользователь ограничил доступ к своим фотографиям</b></center></li>
		    </ul>
		   </div>";
		
	} else {
		$return = "
		 <div class=\"block\">
		  <p class=\"header\"></p>
		 <ul>
		  <li style=\"border-bottom:none;\"><center><b style=\"color:red\">Фотоальбомы отсутствуют</b></center></li>		  
		 </ul>
		 </div>
		 $add_album";
	}
	
	$objResponse = new xajaxResponse();
	$objResponse->assign('idLenta', 'innerHTML', $return);
	if($album_id > 0 && @$value['security'] == 1) $objResponse->script("xajax_ShowPhotoAlbum($user_wp,$album_id)");
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowListPhotoAlbums');


function xShowLogoPhotoAlbum($album_id){
	global $USER;
	
	$return   = "";
	$album_id = (int) $album_id;
	$result   = $USER->ShowLogoPhotoAlbum($album_id);
	
	if(is_array($result)){
		foreach($result as $key=>$value){
	$return .= "			
			  <div class=\"imgfriend\">
			   <a href=\"#\" onClick=\"xajax_ShowPhotoAlbum(".$value['user_wp'].",".$value['album_id']."); xajax.$('idAlbum_".$value['album_id']."').innerHTML='<center>".loading_clock."</center>'; return false;\"><img style=\"border:7px solid #ccc;\" src=\"".$value['photo']."\"></a>
			  <div>
			";
		}
	}
	
	$objResponse = new xajaxResponse();
	$objResponse->assign('idAlbum_'.$album_id, 'innerHTML', $return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'xShowLogoPhotoAlbum');


function ShowPhotoAlbum($user_wp,$album_id){
	global $USER;
	
	$album_id = (int) $album_id;	
	$return = "";
	$photos = $USER->ShowPhotoAlbum($album_id);
	
	if(is_array($photos)){
		
		include(path_root.'js/stars/RatingManager.inc.php');
      	$ratingManager = RatingManager::getInstance();
		
		$return = "
		<div class=\"infobar_$album_id\" id=\"idinfobar_$album_id\">
            <span id=\"description_$album_id\"></span>
            <span id=\"loading_$album_id\">Загрузка фото </span>
            <span class=\"reference_$album_id\">
                <a href=\"#\" id=\"id_prev_link_$album_id\" onClick=\"return false;\">Закрыть</a>
            </span>
        </div>
		<div id=\"thumbsWrapper_$album_id\"><div id=\"content_$album_id\">";
		
		foreach($photos as $key=>$value){
			$return .= "<img src=\"".$value['photo']."\" alt=\"".$value['photo_original']."\" title=\"".$value['header']."\" comments=\"".htmlspecialchars('<a style="color:yellow" href="#" onClick="CommentsPhoto('.$value['photo_id'].'); return false;" title="'.$value['header'].'">Комментариев: <span id="idCountComments2_'.$value['photo_id'].'">'.$USER->CountComments(array("photo_id"=>$value['photo_id'])).'</span></a>')."\" rating=\"".htmlspecialchars($ratingManager->drawStars($value['photo_id']))."\"/> ";
		}
		$return .= "</div></div><div id=\"panel_$album_id\"><div id=\"wrapperalbum_$album_id\"><a id=\"prev_$album_id\"></a><a id=\"next_$album_id\"></a></div></div>";
	}
	
	$files = ShowStylePhotoAlbum($album_id,path_tmp);
	
	$objResponse = new xajaxResponse();
	$objResponse->includeCSS($files['style']);
	$objResponse->includeScript($files['script']);
	$objResponse->includeScript("js/stars/js.js");
	$objResponse->assign('idAlbum_'.$album_id, 'innerHTML', $return);
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowPhotoAlbum');


function ShowStylePhotoAlbum($album_id,$path){
	
$style = "

span.reference_$album_id{
    position:fixed;
    left:30px;
    bottom:0px;
    font-size:9px;
}

span.reference_$album_id a{
    color:#aaa;
}

span.reference_$album_id a:hover{
    color:#ddd;
    text-decoration:none;
}

.infobar_$album_id{
    background-color:#000;
    height:28px;
    line-height:28px;
    right:20px;
    position:fixed;
    bottom:0px;
    left:20px;
    z-index:9999;
    text-align:center;
    color:#ddd;
    -moz-border-radius:10px 10px 0px 0px;
    -webkit-border-top-left-radius:10px;
    -webkit-border-top-right-radius:10px;
    border-top-left-radius:10px;
    border-top-right-radius:10px;
    text-shadow:0px 0px 1px #ccc;
    display:none;
}
span#description_$album_id{
    text-shadow:1px 1px 1px #000;
    display:none;
}
span#loading_$album_id{
    display:none;
    padding-right: 30px;
    background:transparent url(../../pic/loading_album.gif) no-repeat center right;
}


#content_$album_id{
    text-align: center;
}

#content_$album_id img{
    border:5px solid #ccc;
    cursor:pointer;
    opacity:0.4;
    filter:progid:DXImageTransform.Microsoft.Alpha(opacity=40);
}

#panel_$album_id{
    z-index:9998;
    background-color:#222;
    width:100%;
    position:fixed;
    bottom:0px;
    left:0px;
    right:0px;
    height:0px;
    text-align:center;
}
#panel_$album_id img{
    cursor:pointer;
    position:relative;
    border:1px solid #000;
    -moz-box-shadow:0px 0px 10px #111;
    -webkit-box-shadow:0px 0px 10px #111;
    box-shadow:0px 0px 10px #111;
    display:none;
}

#wrapperalbum_$album_id{
    position:relative;
    margin:40px auto 0px auto;
}
a#next_$album_id,
a#prev_$album_id{
    width:40px;
    height:40px;
    position:fixed;
    cursor:pointer;
    outline:none;
    display:none;
    background:#aaa url(../../pic/nav.png) no-repeat top left;
}
a#next_$album_id:hover, a#prev_$album_id:hover{
    background-color:#fff;
}
a#next_$album_id{
    right:0px;
    top:50%;
    margin-top:-20px;
    background-position: 0px 0px;
}
a#prev_$album_id{
    left:0px;
    top:50%;
    margin-top:-20px;
    background-position: 0px -40px;
}	
";


$script = '
$(function() {
                var current = -1;
                var totalpictures = $(\'#content_'.$album_id.' img\').size();
                var speed 	= 500;
                
                $(window).bind(\'resize\', function() {
                    var $picture = $(\'#wrapperalbum_'.$album_id.'\').find(\'img\');
                    resize($picture);
                });
                
                $(\'#content_'.$album_id.' > img\').hover(function () {
                    var $this   = $(this);
                    $this.stop().animate({\'opacity\':\'1.0\'},200);
                },function () {
                    var $this   = $(this);
                    $this.stop().animate({\'opacity\':\'0.4\'},200);
                }).bind(\'click\',function(){
                    var $this   = $(this);
                    
                    $(\'#idinfobar_'.$album_id.'\').show(); /*it is my*/
                    $(\'#loading_'.$album_id.'\').show();
                    
                    $(\'<img/>\').load(function(){
                        $(\'#loading_'.$album_id.'\').hide();
                        
                        if($(\'#wrapperalbum_'.$album_id.'\').find(\'img\').length) return;
                        current 	= $this.index();
                        var $theImage   = $(this);
                                                
                        resize($theImage);

                        $(\'#wrapperalbum_'.$album_id.'\').append($theImage);
                        
                        $theImage.fadeIn(800);
                                                
                        $(\'#panel_'.$album_id.'\').animate({\'height\':\'100%\'},speed,function(){
                                                                                    
                            var rating = $this.attr(\'rating\');
                            var comments = $this.attr(\'comments\');
                            if(rating != \'\'){
                                $(\'#description_'.$album_id.'\').html(rating+comments).show();
                            }
                            else 
                                $(\'#description_'.$album_id.'\').empty().hide();
                            
                            if(current==0)
                                $(\'#prev_'.$album_id.'\').hide();
                            else
                                $(\'#prev_'.$album_id.'\').fadeIn();
                            if(current==parseInt(totalpictures-1))
                                $(\'#next_'.$album_id.'\').hide();
                            else
                                $(\'#next_'.$album_id.'\').fadeIn();
                            
                            $(\'#thumbsWrapper_'.$album_id.'\').css({\'z-index\':\'0\',\'height\':\'0px\'});
                        });
                    }).attr(\'src\', $this.attr(\'alt\'));
                });
                
                $(\'#wrapperalbum_'.$album_id.' > img\').live(\'click\',function(){
                    $this = $(this);
                    $(\'#description_'.$album_id.'\').empty().hide();
                    
                    $(\'#thumbsWrapper_'.$album_id.'\').css(\'z-index\',\'10\')
                    .stop()
                    .animate({\'height\':\'100%\'},speed,function(){
                        var $theWrapper = $(this);
                        $(\'#panel_'.$album_id.'\').css(\'height\',\'0px\');
                        $theWrapper.css(\'z-index\',\'0\');
                        
                        $this.remove();
                        $(\'#prev_'.$album_id.'\').hide();
                        $(\'#next_'.$album_id.'\').hide();
                        $(\'#idinfobar_'.$album_id.'\').hide(); /*it is my*/
                        
                        xajax_xShowLogoPhotoAlbum('.$album_id.');
                        xajax.$(\'idAlbum_'.$album_id.'\').innerHTML=\'<center>'.loading_clock.'</center>\';
                    });
                });
                
                $(\'#id_prev_link_'.$album_id.'\').live(\'click\',function(){
                    $this = $(this);
                    $(\'#description_'.$album_id.'\').empty().hide();
                    
                    $(\'#thumbsWrapper_'.$album_id.'\').css(\'z-index\',\'10\')
                    .stop()
                    .animate({\'height\':\'100%\'},speed,function(){
                        var $theWrapper = $(this);
                        $(\'#panel_'.$album_id.'\').css(\'height\',\'0px\');
                        $theWrapper.css(\'z-index\',\'0\');
                        
                        $this.remove();
                        $(\'#prev_'.$album_id.'\').hide();
                        $(\'#next_'.$album_id.'\').hide();
                        $(\'#idinfobar_'.$album_id.'\').hide(); /*it is my*/
                        
                        xajax_xShowLogoPhotoAlbum('.$album_id.');
                        xajax.$(\'idAlbum_'.$album_id.'\').innerHTML=\'<center>'.loading_clock.'</center>\';
                    });
                });
                
                $(\'#next_'.$album_id.'\').bind(\'click\',function(){
                    var $this           = $(this);
                    var $nextimage 		= $(\'#content_'.$album_id.' img:nth-child(\'+parseInt(current+2)+\')\');
                    navigate($nextimage,\'right\');
                });
                $(\'#prev_'.$album_id.'\').bind(\'click\',function(){
                    var $this           = $(this);
                    var $previmage 		= $(\'#content_'.$album_id.' img:nth-child(\'+parseInt(current)+\')\');
                    navigate($previmage,\'left\');
                });

                
                function navigate($nextimage,dir){
                
                    if(dir==\'left\' && current==0)
                        return;
                    if(dir==\'right\' && current==parseInt(totalpictures-1))
                        return;
                        
                    $(\'#description_'.$album_id.'\').empty().fadeOut();
                    $(\'#loading_'.$album_id.'\').show();
                    $(\'<img/>\').load(function(){
                        var $theImage = $(this);
                        $(\'#loading_'.$album_id.'\').hide();
                        $(\'#description_'.$album_id.'\').empty().fadeOut();
                         
                        $(\'#wrapperalbum_'.$album_id.' img\').stop().fadeOut(500,function(){
                            var $this = $(this);
							
                            $this.remove();
                            resize($theImage);
                            
                            $(\'#wrapperalbum_'.$album_id.'\').append($theImage.show());
                            $theImage.stop().fadeIn(800);

                            
                            var rating = $nextimage.attr(\'rating\');
                            var comments = $nextimage.attr(\'comments\');
                            if(rating != \'\'){
                                $(\'#description_'.$album_id.'\').html(rating+comments).show();
                            }
                            else 
                                $(\'#description_'.$album_id.'\').empty().hide();
                                
                            if(current==0)
                                $(\'#prev_'.$album_id.'\').hide();
                            else
                                $(\'#prev_'.$album_id.'\').show();
                            if(current==parseInt(totalpictures-1))
                                $(\'#next_'.$album_id.'\').hide();
                            else
                                $(\'#next_'.$album_id.'\').show();
                        });
                        
                        if(dir==\'right\')
                            ++current;
                        else if(dir==\'left\')
                            --current;
                    }).attr(\'src\', $nextimage.attr(\'alt\'));
                }

                
                function resize($image){
                    var windowH      = $(window).height()-100;
                    var windowW      = $(window).width()-80;
                    var theImage     = new Image();
                    theImage.src     = $image.attr("src");
                    var imgwidth     = theImage.width;
                    var imgheight    = theImage.height;

                    if((imgwidth > windowW)||(imgheight > windowH)){
                        if(imgwidth > imgheight){
                            var newwidth = windowW;
                            var ratio = imgwidth / windowW;
                            var newheight = imgheight / ratio;
                            theImage.height = newheight;
                            theImage.width= newwidth;
                            if(newheight>windowH){
                                var newnewheight = windowH;
                                var newratio = newheight/windowH;
                                var newnewwidth =newwidth/newratio;
                                theImage.width = newnewwidth;
                                theImage.height= newnewheight;
                            }
                        }
                        else{
                            var newheight = windowH;
                            var ratio = imgheight / windowH;
                            var newwidth = imgwidth / ratio;
                            theImage.height = newheight;
                            theImage.width= newwidth;
                            if(newwidth>windowW){
                                var newnewwidth = windowW;
                                var newratio = newwidth/windowW;
                                var newnewheight =newheight/newratio;
                                theImage.height = newnewheight;
                                theImage.width= newnewwidth;
                            }
                        }
                    }
                    $image.css({\'width\':theImage.width+\'px\',\'height\':theImage.height+\'px\'});
                }
            });
';

    if(is_dir($path)){
    	
    	$fstyle = fopen($path.$album_id.".css","w+");
    	          fwrite($fstyle,$style);
    	          fclose($fstyle);
    	          
    	$fscript = fopen($path.$album_id.".js","w+");
    	           fwrite($fscript,$script);
    	           fclose($fscript);
    	           
        return array('style' => str_replace(path_root,'',$path.$album_id.".css"), 'script' => str_replace(path_root,'',$path.$album_id.".js"));
    }
}



function ShowAddAlbum(){
	
	$return = "
			<div class=\"block\">
			  <p class=\"header\">Создание нового фотоальбома | ".MyDataTime(@date(),'date')."&nbsp;&nbsp;&nbsp;</p>
			<ul>
			 <li style=\"border-bottom:none;\">
			  <table style=\"width:98%\">
			   <tr><td>Название фотоальбома </td></tr>
			   <tr><td><input type=\"text\" id=\"album_name\" style=\"width:100%\"></td></tr>
			   <tr><td><br /></td></tr>
			   <tr><td>Выберите Ваши настройки конфиденциальности (альбом смогут видеть)</td></tr>
			   <tr><td id=\"idSelectSecurity\"><center>".loading_clock."</center></td></tr>
			   <tr><td><br /></td></tr>
			   <tr><td><div class=\"roundedbutton greenbutton\" id=\"btnext1\" onClick=\"xajax_AddPhotoAlbum(xajax.$('album_name').value,xajax.$('user_security').value,0,0); return false;\"><sub></sub><div>Далее</div><sup></sup></div></td></tr>
			  </table>
			 </li>
			</ul>
			</div>
			
			<div class=\"f_block\" id=\"f_block\" style=\"display:none;\">
			  <p class=\"header\">Свои настройки конфиденциальности&nbsp;&nbsp;&nbsp;</p>
			<ul>
			 <li id=\"idSecurity\"></li>
			 <li id=\"btnext2\"><table width=\"97%\"><tr><td><div class=\"roundedbutton greenbutton\" onClick=\"xajax_AddPhotoAlbum(xajax.$('album_name').value,xajax.$('user_security').value,xajax.$('id_yes').value,xajax.$('id_no').value); return false;\"><sub></sub><div>Далее</div><sup></sup></div></td></tr></table></li>
			</ul>
			</div>
			";
		
	$objResponse = new xajaxResponse();
	$objResponse->assign('idLenta', 'innerHTML', $return);
	$objResponse->script("xajax_ShowSelectSecurity('photoalbum')");
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'ShowAddAlbum');




function AddPhotoAlbum($name,$pravo,$yes,$no){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$return = "";
	$pravo  = (int) $pravo;
	$er     = 0;
	$yes    = trim($yes);
	$no     = trim($no);
	
	$objResponse = new xajaxResponse();
	
	if($pravo == 5){
		
		if(strlen($yes) > 0)
		 $yes = explode(',', substr($yes, 0, strlen($yes)-1));
		else $yes = "";
		
		if(strlen($no) > 0)
		 $no  = explode(',', substr($no,  0, strlen($no)-1));
		else $no = "";
		
		if(!is_array($no) or !is_array($yes)){
			$objResponse->alert("Ошибка! Укажите пользователей");
			$er++;
		}
		
		$security = array(
		   'security' => 5,
		   'no'       => $no,
		   'yes'      => $yes,
		);
	} else {
		$security = array(
		   'security' => $pravo,
		   'no'       => '',
		   'yes'      => '',
		);
	}
	
	if(strlen($name) == 0)
	   $name = MyDataTime(@date(),'date');
	
	
    if($er == 0 && is_array($security)){
    	$security = serialize($security);
    	$result = $MYSQL->query("INSERT INTO pfx_users_photos_album (data_add,user_wp,header,pravo) VALUES (now(),".(int)$_SESSION['WP_USER']['user_wp'].",'".mysql_real_escape_string($name)."','".mysql_real_escape_string($security)."')");
    	if($result > 0){
    		
   if($pravo == 1 or $pravo == 3){ // Если видеть можно всем или друзьям, то на стену вешаем
   	$MYSQL->query("INSERT INTO pfx_users_deystvie (data_add,user_wp,deystvie,id_deystvie,view) VALUES (now(),".(int)$_SESSION['WP_USER']['user_wp'].",4,$result,0)");
   }
    		
   $script = "
	$(function() {
     $(\"#uploadify\").uploadify({
	    'uploader'       : 'js/uploadify/uploadify.swf',
		'script'         : 'jquery-upload.php',
		'cancelImg'      : 'js/uploadify/cancel.png',
		'buttonImg'      : 'pic/add_photo_".LANG_SITE.".png',
		'queueID'        : 'fileQueue',
		'scriptData'     : {'type':'photoalbum','par':'".$_SESSION['WP_USER']['user_wp'].",$result'},
		'auto'           : true,
		'buttonText'     : 'Add photo',
		'queueSizeLimit' : 1,
		'multi'          : false,
		'width'          : 126,
		'height'         : 40,
		'fileDesc'		 : 'Images (jpg,jpeg,gif,png)',
		'fileExt'		 : '*.jpg;*.jpeg;*.gif;*.png',
		'sizeLimit'      : '".max_file_size."',
		'onComplete'  	 : function(event,queueID,fileObj,response,data){
								
								var dataresponse = $.parseJSON(response);
									
								if(dataresponse.result == '1'){
									$('#files').append('<li id=\"'+dataresponse.photoId+'\" style=\"float:left; width:50%; border-bottom:none;\"><a href=\"#\" onClick=\"if(confirm(\'Удалить фотографию?\')){ xajax_DeletePhoto('+dataresponse.photoId+'); } return false;\" class=\"delete24\" title=\"Удалить\"></a><img src=\"'+dataresponse.photo+'\" id=\"preview\"></li>');
								}
								else
									alert(dataresponse.error);
							},
							
		'onError'		 : function(){
								alert('Ошибка загрузки файла');
						   }
      });
    });
	";
    		
    		$return = "
    		<style>
    		 #files img {border:5px solid #ccc;}
    		</style>
			<div class=\"block\">
			  <p class=\"header\">$name | ".MyDataTime(@date(),'date')."&nbsp;&nbsp;&nbsp;</p>
			 <ul>
			  <li style=\"border-bottom:none; text-align:center;\">
			   <div id=\"thumbsWrapper_$result\"><div id=\"content_$result\"><div id=\"files\"><ul style=\"width:100%\"></ul></div></div></div>
			  </li>
			 </ul>
			</div>
			<center><input type=\"file\" name=\"uploadify\" id=\"uploadify\" /><div id=\"fileQueue\"></div></center>
			";
    		$objResponse->includeCSS("js/uploadify/uploadify.css");
    		$objResponse->assign('idLenta', 'innerHTML', $return);
    		$objResponse->script($script);
    	}
    }
	
	return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'AddPhotoAlbum');


function ShowEditPhotoAlbum($album_id){
	global $USER;
	
	$album_id = (int) $album_id;
	
	$album  = $USER->ShowLogoPhotoAlbum($album_id);
	$photos = $USER->ShowPhotoAlbum($album_id);
	
	$return = "";
	$script = "";
	
	if(is_array($album) && is_array($photos)){
		$return = "
		    <style>
		    #files img {
		     border:5px solid #ccc;
             cursor:default;
             opacity: 100;
		    }
    		</style>
			<div class=\"block\">
			  <p class=\"header\"><input style=\"width:70%;\" type=\"text\" id=\"album_name\" value=\"".$album[0]['header']."\"> | ".$album[0]['data']."&nbsp;&nbsp;&nbsp;</p>
			 <ul>
			  <li style=\"border-bottom:none; text-align:center;\">
			   <div id=\"thumbsWrapper_$album_id\"><div id=\"content_$album_id\"><div id=\"files\"><ul style=\"width:100%\">";
		
		foreach($photos as $key=>$value){
			$return .= "<li id=\"".$value['photo_id']."\" style=\"float:left; width:50%; border-bottom:none;\"><a href=\"#\" onClick=\"if(confirm('Удалить фотографию?')){ xajax_DeletePhoto(".$value['photo_id']."); } return false;\" class=\"delete24\" title=\"Удалить\"></a><img src=\"".$value['photo']."\" id=\"preview\"></li>";
		}
		
		$return .= "
    		</ul></div></div></div>
			  </li>
			 </ul>
			</div>
			<center><input type=\"file\" name=\"uploadify\" id=\"uploadify\" /><div id=\"fileQueue\"></div></center>
			
			
			<div class=\"f_block\">
			  <p class=\"header\">Настройки конфиденциальности&nbsp;&nbsp;&nbsp;</p>
			<ul>
			 <li id=\"idSelectSecurity\"></li>
			 <li id=\"f_block\"><div id=\"idSecurity\"></div></li>
			 <li id=\"btnext2\"><table width=\"97%\"><tr><td><div class=\"roundedbutton greenbutton\" onClick=\"xajax_SavePhotoAlbum($album_id,$('#album_name').val(),$('#user_security').val(),$('#id_yes').val(),$('#id_no').val()); return false;\"><sub></sub><div>Сохранить</div><sup></sup></div></td></tr></table></li>
			</ul>
			</div>
			";
		
	
	
	$script = "
	$(function() {
     $(\"#uploadify\").uploadify({
	    'uploader'       : 'js/uploadify/uploadify.swf',
		'script'         : 'jquery-upload.php',
		'cancelImg'      : 'js/uploadify/cancel.png',
		'buttonImg'      : 'pic/add_photo_".LANG_SITE.".png',
		'queueID'        : 'fileQueue',
		'scriptData'     : {'type':'photoalbum','par':'".$_SESSION['WP_USER']['user_wp'].",$album_id'},
		'auto'           : true,
		'buttonText'     : 'Add photo',
		'queueSizeLimit' : 1,
		'multi'          : false,
		'width'          : 126,
		'height'         : 40,
		'fileDesc'		 : 'Images (jpg,jpeg,gif,png)',
		'fileExt'		 : '*.jpg;*.jpeg;*.gif;*.png',
		'sizeLimit'      : '".max_file_size."',
		'onComplete'  	 : function(event,queueID,fileObj,response,data) {
			
								var dataresponse = $.parseJSON(response);
									
								if(dataresponse.result == '1'){
									$('#files').append('<li id=\"'+dataresponse.photoId+'\" style=\"float:left; width:50%; border-bottom:none;\"><a href=\"#\" onClick=\"if(confirm(\'Удалить фотографию?\')){ xajax_DeletePhoto('+dataresponse.photoId+'); } return false;\" class=\"delete24\" title=\"Удалить\"></a><img src=\"'+dataresponse.photo+'\" id=\"preview\"></li>');
								}
								else
									alert(dataresponse.error);
							},
							
		'onError'		 : function(){
								alert('Ошибка загрузки файла');
						   }
      });
    });
	";	
	
   }
	
   $objResponse = new xajaxResponse();
   $objResponse->includeCSS("js/uploadify/uploadify.css");
   $objResponse->assign('idLenta', 'innerHTML', $return);
   $objResponse->script($script);
   $objResponse->script("xajax_ShowSelectSecurity('photoalbum',$album_id)");
   return $objResponse;	
}
$xajax->register(XAJAX_FUNCTION,'ShowEditPhotoAlbum');


function SavePhotoAlbum($album_id,$name,$pravo,$yes,$no){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$return = "";
	$pravo  = (int) $pravo;
	$er     = 0;
	$yes    = trim($yes);
	$no     = trim($no);
	
	$objResponse = new xajaxResponse();
	
	if($pravo == 5){
		
		if(strlen($yes) > 0)
		 $yes = explode(',', substr($yes, 0, strlen($yes)-1));
		else $yes = "";
		
		if(strlen($no) > 0)
		 $no  = explode(',', substr($no,  0, strlen($no)-1));
		else $no = "";
		
		if(is_array($no) or is_array($yes)){} else {
			$objResponse->alert("Ошибка! Укажите пользователей");
			$er++;
		}
		
		$security = array(
		   'security' => 5,
		   'no'       => $no,
		   'yes'      => $yes,
		);
	} else {
		$security = array(
		   'security' => $pravo,
		   'no'       => '',
		   'yes'      => '',
		);
	}
	
	if(strlen($name) == 0)
	   $name = MyDataTime(@date(),'date');
	
	if($er == 0 && is_array($security)){
	   $MYSQL->query("UPDATE pfx_users_photos_album SET header='$name', pravo='".mysql_real_escape_string(serialize($security))."' WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND id=".$album_id);
	   $objResponse->script("location.href='".$_SERVER['REQUEST_URI']."'");
	} else {
		$objResponse->alert("Ошибка! Не удалось сохранить данные");
	}
	   
    return $objResponse;
}
$xajax->register(XAJAX_FUNCTION,'SavePhotoAlbum');


function DeletePhotoAlbum($album_id){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$album_id = (int) $album_id;
	
	$MYSQL->query("DELETE FROM pfx_users_deystvie WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND deystvie=4 AND id_deystvie=".$album_id);
	$MYSQL->query("DELETE FROM pfx_users_photos_album WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND id=".$album_id);
	$photos = $MYSQL->query("SELECT id, album_id FROM pfx_users_photos WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND album_id = $album_id");
	if(is_array($photos)){
		$MYSQL->query("DELETE FROM pfx_users_photos WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND album_id=".$album_id);
		foreach($photos as $key=>$value){
			$MYSQL->query("DELETE FROM pfx_ratings_photos WHERE id=".(int)$value['id']);
			$comments = $MYSQL->query("SELECT id FROM pfx_users_comments WHERE photo_id=".(int)$value['id']);
		    if(is_array($comments)){
			   $MYSQL->query("DELETE FROM pfx_users_comments WHERE photo_id=".(int)$value['id']);
			     foreach($comments as $key2=>$value2){
				   $MYSQL->query("DELETE FROM pfx_users_deystvie WHERE deystvie = 7 AND id_deystvie=".$value2['id']);
			     }
		    }
		}
	}
	return ShowListPhotoAlbums(0,$_SESSION['WP_USER']['user_wp'],20);
}
$xajax->register(XAJAX_FUNCTION,'DeletePhotoAlbum');


function DeletePhoto($photoId){
	global $MYSQL;
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$photo = $MYSQL->query("SELECT album_id FROM pfx_users_photos WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND id = ".(int)$photoId);
	if(is_array($photo)){
		$MYSQL->query("DELETE FROM pfx_users_photos WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']." AND id = ".(int)$photoId);
		$MYSQL->query("DELETE FROM pfx_ratings_photos WHERE id=".(int)$photoId);
		
		$comments = $MYSQL->query("SELECT id FROM pfx_users_comments WHERE photo_id=".(int)$photoId);
		if(is_array($comments)){
			$MYSQL->query("DELETE FROM pfx_users_comments WHERE photo_id=".(int)$photoId);
			foreach($comments as $key=>$value){
				$MYSQL->query("DELETE FROM pfx_users_deystvie WHERE deystvie = 7 AND id_deystvie=".$value['id']);
			}
		}
	}
	$objResponse = new xajaxResponse();
	$objResponse->script("$('#$photoId').remove()");
    return $objResponse;	
}
$xajax->register(XAJAX_FUNCTION,'DeletePhoto');

?>
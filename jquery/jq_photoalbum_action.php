<?php
$album_id = varr_int(@$_POST['id']);
if($album_id > 0){
	$GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
	$pravo = $MYSQL->query("SELECT IFNULL(pravo,'') security FROM pfx_users_photos_album WHERE id = $album_id AND user_wp=".varr_int($_SESSION['WP_USER']['user_wp']));
	if(!is_array($pravo)) die('Error!');
	$pravo = $pravo[0]['security'];
	if(strlen($pravo) > 0) {$pravo = unserialize($pravo);} else {$pravo[] = array('krug_id'=>0);} // По умолчанию альбом доступен всем
	$checked = "";
	echo "
	  <ul class=\"popup-album-actions\" id=\"chks\">
		<li><a href=\"/my-photoalbums-edit?album_id=$album_id\" class=\"with-icons edit\">Редактировать</a></li>
		<li><a href=\"#\" class=\"with-icons comment\">Комментарии</a></li>
		<li><a href=\"#\" class=\"with-icons delete\" id=\"btnDel\" onClick=\"return false;\">Удалить</a></li>
		<li>Могут видеть</li>
		<li><label><input type=\"checkbox\" value=\"0\" id=\"chk_all\" class=\"frnchck\" ".str_replace('0','checked',@$pravo[0]['krug_id']).">Все</label></li>
		<li><label><input type=\"checkbox\" value=\"1\" id=\"chk_i\" class=\"frnchck\" ".str_replace('1','checked',@$pravo[0]['krug_id']).">Только Я</label></li>";
	
	    if($_SESSION['WP_USER']['zvezda'] == 1)
   	 	   $where = "WHERE krug_id <> 9 ";
   	    else 
           $where = "WHERE krug_id <> 10 "; // без фанатов
	    $result = $MYSQL->query("SELECT krug_id, name_".LANG_SITE." name FROM pfx_krugi $where AND krug_id <> 1 ORDER BY sort");
	    if(is_array($result)){
		   foreach($result as $key=>$value){
		   	 if(is_array($pravo))
               foreach($pravo as $key2=>$value2)
            	 if($value['krug_id'] == $value2['krug_id'])
            	 {$checked = "checked"; break;} else $checked = "";
	 	     echo "<li><label><input type=\"checkbox\" class=\"frnchck\" value=\"".$value['krug_id']."\" $checked>".$value['name']."</label></li>";
		   }
	    }
		echo "
		<li><a href=\"#\" id=\"btnSave\" class=\"with-icons save\">Сохранить</a></li>
	 </ul>
	";
	
	
	
	
	echo "
	 <script type=\"text/javascript\">
	   $(document).ready(function(){
		$('#album-actions input:checkbox').checkbox({cls:'jquery-safari-checkbox-mini'});
		$('input.frnchck').change(function(){
		   if($(this).attr('id') != undefined){
		      var allCheckboxes = $('#chks input:checkbox:enabled');
		      allCheckboxes.removeAttr('checked');
		      $(this).attr('checked','checked');
		   } else {
		      $('#chk_all').removeAttr('checked');
		      $('#chk_i').removeAttr('checked');
		   }
	    });
	    $('#btnSave').click(function(){
	      var circles = new Array();
	      var i = 0;
	      $('input.frnchck').each(function(key,input){
	         if(input.checked == true)
	         circles[i++] = input.value;
	      });
	      $.ajax({
	        url:'/jquery-photoalbumaction',
	        cache:false, type:'POST',
	        data: {type:'security',album_id:$album_id,circles:circles},
	        success:function(data){
	  	      $('#msg$album_id').html(data);
	  	      $('#album-actions').remove();
	        }
	      });
	    });
	    $('#btnDel').click(function(){
	       if(confirm('Удалить выбранный Вами фотоальбом?')){
	         $.ajax({
	           url:'/jquery-photoalbumaction',
	           cache:false, type:'POST',
	           data: {type:'delete',album_id:$album_id},
	           success:function(data){
	             if(data == 'ok'){
	  	            $('#album_$album_id').remove();
	  	            $('#album-actions').remove();
	             }
	           }
	         });
	       }
	    });
	   });
     </script>
	";
}
elseif(isset($_POST['type'])){
	switch($_POST['type']){
		case 'security': // Сохраняем секъюрити для фотоальбома
			if(!is_array(@$_POST['circles']) && isset($_POST['album_id'])){
	           echo "<font style=\"color:red\">Ошибка передачи параметров</font>";
	           exit();
            }            

            foreach($_POST['circles'] as $key=>$value){
	          $pravo[] = array('krug_id'=>varr_int($value));
            }

            if(isset($pravo) && is_array($pravo)){
	           $pravo = serialize($pravo);
	           $GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
	           $MYSQL->query("UPDATE pfx_users_photos_album SET pravo='$pravo' WHERE id = ".varr_int($_POST['album_id'])." AND user_wp=".varr_int($_SESSION['WP_USER']['user_wp']));
	           echo "<font style=\"color:green\">Настройки конфиденциальности сохранены</font>";
            }
		break;
		
		case 'add': // Добавляем новый фотоальбом
			if(isset($_POST['title']) && strlen($_POST['title']) > 0 && isset($_POST['photos']) && is_array($_POST['photos']) /*&& is_array($_POST['circles'] && isset($_POST['circles'])*/ ){
				if(isset($_POST['circles'])){
					foreach($_POST['circles'] as $key=>$value)
					  $pravo[] = array('krug_id'=>varr_int($value));
				}
				else $pravo[] = array('krug_id'=>'0');
					$pravo = serialize($pravo);
					$GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
					$album_id = $MYSQL->query("INSERT INTO pfx_users_photos_album (data_add,user_wp,header,opis,pravo) VALUES (now(),".varr_int($_SESSION['WP_USER']['user_wp']).",'".varr_str($_POST['title'])."','".varr_str($_POST['opis'])."','$pravo')");
					if($album_id > 0){
					   foreach($_POST['photos'] as $key=>$value){
						$MYSQL->query("INSERT INTO pfx_users_photos (album_id,user_wp,header,domen,photo,logo) VALUES ($album_id,".varr_int($_SESSION['WP_USER']['user_wp']).",'".varr_str($value['opis'])."','".upload_url."','".basename($value['img'])."',".varr_int($value['main']).")");
					   }
					   $USER->AddDeystvie(0,0,4,$album_id);
					   echo 'ok';
					}
			} else {
				echo "Выберите настройки приватности";
	            exit();
			}
		break;
		
		case 'edit':
			if(isset($_POST['album_id']) && $_POST['album_id'] > 0 && isset($_POST['title']) && strlen($_POST['title']) > 0 && isset($_POST['photos']) && is_array($_POST['photos'])){
				$GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
				$album_id = varr_int($_POST['album_id']);
				$photos = $MYSQL->query("SELECT id FROM pfx_users_photos WHERE album_id=$album_id AND user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
				if(is_array($photos)){
					$MYSQL->query("UPDATE pfx_users_photos_album SET header='".varr_str($_POST['title'])."', opis='".varr_str(@$_POST['opis'])."' WHERE id=$album_id AND user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
					$where = "";
					foreach($photos as $key=>$value){
						foreach($_POST['photos'] as $key2=>$value2){
							if($value2['id'] <= 0) continue;
							if($value['id'] == $value2['id']){
								$where .= " id <> ".$value['id']." AND ";
								$MYSQL->query("UPDATE pfx_users_photos SET header='".varr_str($value2['opis'])."',logo=".varr_int($value2['main'])." WHERE id=".$value['id']);
							}
						}
					}
					if($where != ""){
						$MYSQL->query("DELETE FROM pfx_users_photos WHERE $where album_id=$album_id AND user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
					}
					foreach($_POST['photos'] as $key=>$value){ // Вставляем новые фотографии
					  if($value['id'] <= 0)
	            	     $MYSQL->query("INSERT INTO pfx_users_photos (album_id,user_wp,header,domen,photo,logo) VALUES ($album_id,".varr_int($_SESSION['WP_USER']['user_wp']).",'".varr_str($value['opis'])."','".upload_url."','".basename($value['img'])."',".varr_int($value['main']).")");
	                }
	                echo 'ok';
				} else {
				   echo "<font style=\"color:red\">Ошибка! Фографий в альбоме не найдено</font>";
	               exit();
			    }
			} else {
				echo "<font style=\"color:red\">Ошибка передачи обязательных параметров</font>";
	            exit();
			}			
		break;
		
		case 'delete': // Удалить фотоальбом
			if(isset($_POST['album_id']) && $_POST['album_id'] > 0){
				$GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
				$album_id = varr_int($_POST['album_id']);
				// Удаляем из ленты
				$MYSQL->query("DELETE FROM pfx_users_deystvie WHERE id_deystvie=$album_id AND deystvie=4 AND user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
				// Удаляем фотки
				$MYSQL->query("DELETE FROM pfx_users_photos WHERE album_id = $album_id AND user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
				// Удаляем альбом
				$MYSQL->query("DELETE FROM pfx_users_photos_album WHERE id = $album_id AND user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
				//Прописать удаление комментариев
				echo 'ok';				
			}
		break;
	}
}

?>
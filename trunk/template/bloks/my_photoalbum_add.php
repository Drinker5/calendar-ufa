<?php
require_once('jquery/jq_myphotoalbums.php');
$rows      =20;
if($_URLP[0]=='my')$user_wp=$_SESSION['WP_USER']['user_wp'];
else               $user_wp=(int)$_URLP[0];
$subs_count=$USER->CountPhotoAlbums($user_wp);
?>
<div class="title margin tx_r">
	<!--h2 class="tx_l">Фотоальбомы <span class="title-count">(</?=$subs_count?>)</span></h2-->
	<h3 class="tx_l"> Добавить фотоальбом</h3>
	<div class="separator"></div>
</div>
<?PhotoAlbumsEdit($user_wp)?>
<!--div class="photoalbum group"> Убрано в jq_myphotoalbums.php
	<div class="bordered medium-avatar fl_l">
		<img id="logo" alt="" src="pic/camera.jpg">
	</div>
	<div class="add-album-description wrapped">
		<table>
			<thead></thead>
			<tbody>
				<tr>
					<td>Альбом:</td>
					<td>
						<input id="title" class="bordered" type="text" placeholder="Название альбома..." name="header">
					</td>
				</tr>
				<tr>
					<td>Описание:</td>
					<td>
						<textarea id="albopis" class="bordered" placeholder="Введите описание альбома" name="opis"></textarea>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="tx_r">
			<span class="popover-btn actionscomm opacity_link">
				Разрешить просмотр альбома
				<i class="small-icon icon-grey-arrow"></i>
			</span>
			
			<span class="popover-btn actionscomm opacity_link">
				Разрешить комментировать альбом
				<i class="small-icon icon-grey-arrow"></i>
			</span>
		</div>
	</div>
</div-->
			<table id="chks">
	 <tr>
		<td style="vertical-align:top;">
		
		<?
		/* вынесено в jq_myphotoalbums
		//	$GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
		//	if($_SESSION['WP_USER']['zvezda'] == 1) $where = " AND krug_id <> 9 "; else $where = " AND krug_id <> 10 ";
		//	$result = $MYSQL->query("SELECT krug_id, name_".LANG_SITE." name FROM pfx_krugi WHERE show=1 AND krug_id <> 1 $where ORDER BY sort");
		//	$pravo  = $MYSQL->query("SELECT IFNULL(pravo,'') security FROM pfx_users WHERE user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
		//	$pravo  = @$pravo[0]['security'];
		//	if(strlen($pravo) > 0) {$pravo = unserialize($pravo);} else {$pravo[] = array('krug_id'=>0);} // По умолчанию страница доступна всем
		//	$checked = "";
		*/
        /*    if(is_array($result)){
				echo "<p><label><input type=\"checkbox\" name=\"circle1\" value=\"0\" id=\"chk_all\" class=\"frnchck\"". str_replace('0','checked',@$pravo[0]['krug_id']).">Все</label></p>";
				echo "<p><label><input type=\"checkbox\" name=\"circle1\" value=\"0\" id=\"chk_all\" class=\"frnchck\"". str_replace('1','checked',@$pravo[0]['krug_id']).">Только Я</label></p>";
            	foreach($result as $key=>$value){
            		if(is_array($pravo))
            		foreach($pravo as $key2=>$value2)
            		 if($value['krug_id'] == $value2['krug_id'])
            		 {$checked = "checked"; break;} else $checked = "";
            		
		// 	echo "<p><label><input type=\"checkbox\" name=\"circle1\" value=\"".$value['krug_id']."\" class=\"frnchck\" $checked>".$value['name']."</label></p>"; //вынесено в head
            	}
            }*/
		?>
		
		</td>
		<td style="vertical-align:top; padding-left:60px; background:url('pic/bonus-table-th.png') no-repeat 20px -20px;">
		</td>
	 </tr>
    </table>





<div class="title margin group">
	<div class="separator clear"></div>
	<h3>Добавить фотографии</h3>
	<?php if($_URLP[0]=='my')echo '<a href="#" id="add-photo-but" class="btn btn-green" style="position: absolute; right: 0; top: 6px;"><i class="small-icon icon-white-plus"></i>Добавить фото</a>';?>
</div>
<div class="photoalbum group">
	<div id="files"></div>
	<div id="photos"></div>
	<?php $USER->ShowPhotosIsAlbum($rows);?>
	<div class="tx_r">
		<button type="submit" class="btn btn-green" name="add" id="save-photo-changes">Сохранить</button>
	</div>
</div>

<script type="text/javascript" src="js/fileuploader.js"></script>
<script type="text/javascript" src="js/jquery.selectBox.min.js"></script>
<script type="text/javascript">

 var j=0;
 //$('input.frnchck').checkbox({cls:'jquery-safari-checkbox-box'});
 function createUploader(){
   var uploader = new webimg.FileUploader({ 
       element: document.getElementById('files'),
       button:  document.getElementById('add-photo-but'),
       action: '/jquery-upload.php?type=photoalbum',
       allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
       sizeLimit: <?=max_file_size?>,
       maxConnections: 2,
       multiple: true,
       onComplete: function(id, fileName, responseJSON){
			//$('.webimg-upload-list').html('');
			if(responseJSON.success){
				//$('#photos').append('<div class="photoalbum-elem" id="div'+j+'"><div class="desire-photo"><img class="photo" src="'+responseJSON.photo+'" width="'+responseJSON.w+'" height="'+responseJSON.h+'"><span></span></div><div class="album-foto-description"><h4>Описание</h4><textarea rows="5" class="opis"></textarea></div><ul class="album-foto-actions"><li><a href="#" class="delete" rel="'+j+'" onClick="return false;"><img src="pic/delete-icon.png">Удалить</a></li><li><label><input type="radio" name="make-cover" class="checkbox" safari="1" onClick="$(\'#logo\').attr(\'src\',\''+responseJSON.photo+'\')"> Сделать обложкой</label></li></ul></div>');
				$('#photos').append('<div class="photoalbum-elem" id="div'+j+'"><a id="delete" class="fl_r opacity_link" href="#" rel="'+j+'" onClick="return false;"><i class="small-icon"><img src="pic/delete.png"></i><strong>Удалить фотографию</strong></a><div class="bordered big-avatar fl_l"><img class="photo" alt="" src="'+responseJSON.photo+'"></div><div class="add-album-description wrapped"><table><thead></thead><tbody><tr><td>Описание:</td><td><textarea id="opis" class="bordered" placeholder="Введите описание фотографии"></textarea></td></tr><tr><td>Альбом:</td><td><select class="selectBox" name="" style="display: none;"><option value="1">Название альбома...</option></select><a class="selectBox selectBox-dropdown" style="width: 283px; display: inline-block; -moz-user-select: none;" title="" tabindex="0"><span class="selectBox-label" style="width: 260px;">Название альбома...</span><span class="selectBox-arrow"></span></a></td></tr><tr><td></td><td><label><input type="radio" class="checkbox" name="make-cover"  onClick="$(\'#logo\').attr(\'src\',\''+responseJSON.photo+'\')">Сделать обложкой</label></div></div>');
				//$('.photoalbum input[type=radio]').checkbox({cls:'jquery-safari-checkbox-box'});
				j++;
			}
			
			else alert(responseJSON.error);
      }
	  
    });
 }
 window.onload = createUploader;
 
 //$(document).ready(function(){

    $('#delete').live('click',function(){
 		if(confirm('Удалить выбранную Вами фотографию из альбома?')){
 		   var id = parseInt($(this).attr('rel'));
 		   $('#div'+id).remove();
 		}
    });
 	$('#save-photo-changes').click(function(){
 		var photos = new Array();
 		$('img.photo').each(function(key,input){
	        photos[photos.length] = input.src;
	    });
 		
 	    if(photos.length > 0){
 	  	  var i=0;
 	  	  $('textarea#opis').each(function(key,input){
	          photos[i] = {'img':photos[i],'opis':input.value};
	          i++;
	      });
	      var i=0; var check = 0; var ch = 1;
 	  	  $('input.checkbox').each(function(key,input){
 	  		  if(input.checked){ check++; ch = 0;} else {ch=1;}
	          photos[i] = {'img':photos[i].img,'opis':photos[i].opis,'main':ch};
	          i++;
	      });
	      if($('#title').val().length == 0){ alert('Укажите название альбома'); return; }
 	  	  if(check != 1){ alert('Выберите обложку для фотоальбома'); return; }
 	  	  
 	  	  var i = 0; var circles = new Array();
	      $('input.frnchck').each(function(key,input){
	        if(input.checked == true)
	           circles[i++] = input.value;
	      });
 		  $.ajax({
	         url:'/jquery-photoalbumaction',
	         cache:false, type:'POST',
	         data: {type:'add',title:$('#title').val(),'opis':$('#albopis').val(),photos:photos,circles:circles},
	         success:function(result){
	      	    if(result == 'ok'){
	      		  location.href = '/my-photoalbums';
	      	    } else {alert(result);}
	         }
	      });
 	    }
		else{alert ('Нет ни одной фотографии');}
 	});
 //});
</script>
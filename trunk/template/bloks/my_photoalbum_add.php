<div id="center">
	<h1><?=$TITLE?></h1>
	<div class="album-edit group">
		<div class="desire-photo">
				<img src="pic/album.png" id="logo" width="113" height="95">
			<span></span>
		</div>
		<div class="album-edit-controls">
			<h4>Название</h4>
			<input type="text" id="title">
			<h4>Описание</h4>
			<textarea rows="3" id="albopis"></textarea>
			<div class="but-container">
			    <div id="add-photo-but">
				 <a href="#" class="green-plus-but-small group">
					<div id="plus"></div>
					<span>Добавить фото</span>
					<sup></sup>
				 </a>
			    </div>
				<div id="save-photo-changes" class="clr-but clr-but-blue-nb group">
					<sub></sub>
					<a href="#" onClick="return false;">Создать фотоальбом</a>
					<sup></sup>
				</div>
			</div>
		</div>
	</div>
    
	<h4>Альбом смогут видеть</h4>
	<table id="chks">
	 <tr>
		<td style="vertical-align:top;">
		<?php
		 $GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
	     if($_SESSION['WP_USER']['zvezda'] == 1) $where = " AND krug_id <> 9 "; else $where = " AND krug_id <> 10 ";
            $result = $MYSQL->query("SELECT krug_id, name_".LANG_SITE." name FROM pfx_krugi WHERE krug_id <> 1 $where ORDER BY sort");
            $pravo  = $MYSQL->query("SELECT IFNULL(pravo,'') security FROM pfx_users WHERE user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
            $pravo  = @$pravo[0]['security'];
            if(strlen($pravo) > 0) {$pravo = unserialize($pravo);} else {$pravo[] = array('krug_id'=>0);} // По умолчанию страница доступна всем
            $checked = "";
            if(is_array($result)){
            	foreach($result as $key=>$value){
            		if(is_array($pravo))
            		foreach($pravo as $key2=>$value2)
            		 if($value['krug_id'] == $value2['krug_id'])
            		 {$checked = "checked"; break;} else $checked = "";
            		
            	  echo "<p><label><input type=\"checkbox\" name=\"circle1\" value=\"".$value['krug_id']."\" class=\"frnchck\" $checked>".$value['name']."</label></p>";
            	}
            }
		?>
		</td>
		<td style="vertical-align:top; padding-left:60px; background:url('pic/bonus-table-th.png') no-repeat 20px -20px;">
			<p><label><input type="checkbox" name="circle1" value="0" id="chk_all" class="frnchck" <?=str_replace('0','checked',@$pravo[0]['krug_id'])?>>Все</label></p>
			<p><label><input type="checkbox" name="circle1" value="1" id="chk_i" class="frnchck" <?=str_replace('1','checked',@$pravo[0]['krug_id'])?>>Только Я</label></p>
		</td>
	 </tr>
    </table>
    <p>&nbsp;</p>
	<h4>Добавленные фотографии</h4>
	<div id="files"></div>
	<div id="photos"></div>
</div>
<script src="js/fileuploader.js" type="text/javascript"></script>
<script type="text/javascript">
 var j=0;
 $('input.frnchck').checkbox({cls:'jquery-safari-checkbox-box'});
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
       	  	$('#photos').append('<div class="album-foto-edit group" id="div'+j+'"><div class="desire-photo"><img class="photo" src="'+responseJSON.photo+'" width="'+responseJSON.w+'" height="'+responseJSON.h+'"><span></span></div><div class="album-foto-description"><h4>Описание</h4><textarea rows="5" class="opis"></textarea></div><ul class="album-foto-actions"><li><a href="#" class="delete" rel="'+j+'" onClick="return false;"><img src="pic/delete-icon.png">Удалить</a></li><li><label><input type="radio" name="make-cover" class="checkbox" safari="1" onClick="$(\'#logo\').attr(\'src\',\''+responseJSON.photo+'\')"> Сделать обложкой</label></li></ul></div>');
       	  	j++;
		  }
		else alert(responseJSON.error);
      }
    });
 }
 window.onload = createUploader;
 
 $(document).ready(function(){
 	$('input.frnchck').change(function(){
		if($(this).attr('id') != undefined){
		   var allCheckboxes = $("#chks input:checkbox:enabled");
		   allCheckboxes.removeAttr('checked');
		   $(this).attr('checked','checked');
		} else {
		   $('#chk_all').removeAttr('checked');
		   $('#chk_i').removeAttr('checked');
		}
	});
    $('.delete').live('click',function(){
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
 	  	  $('textarea.opis').each(function(key,input){
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
 	});
 });
</script>
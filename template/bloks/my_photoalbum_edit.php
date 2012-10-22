<div id="center">
	<h1><?=$TITLE?></h1>
	
	<div class="album-edit group">
		<div class="desire-photo">
				<img src="<?=$photos[0]['photo']?>" id="logo" width="113" height="95">
			<span></span>
		</div>
		<div class="album-edit-controls">
			<h4>Название</h4>
			<input type="text" id="title" value="<?=$album['header']?>">
			<h4>Описание</h4>
			<textarea rows="3" id="albopis"><?=$album['opis']?></textarea>
			<div class="but-container">
			   <div id="add-photo-but">
				<a href="#" id="add-photo-but" class="green-plus-but-small group">
					<div id="plus"></div>
					<span>Добавить фотографию</span>
					<sup></sup>
				</a>
			   </div>
				<div id="save-photo-changes" class="clr-but clr-but-blue-nb group">
					<sub></sub>
					<a href="#" onClick="return false;">Сохранить изменения</a>
					<sup></sup>
				</div>
			</div>
		</div>
	</div>

	<h4>Редактирование фотографий</h4>
	<div id="files"></div>
	<div id="photos">
	<?php
	   foreach($photos as $key=>$value){
	   	  echo "
	   	    <div class=\"album-foto-edit group\" id=\"div".$value['photo_id']."\">
		      <div class=\"desire-photo\">
			    <a href=\"#\">
				 <img class=\"photo\" id=\"".$value['photo_id']."\" src=\"".$value['photo']."\" width=\"113\" height=\"95\">
			    </a>
			    <span></span>
		      </div>
		      <div class=\"album-foto-description\">
			    <h4>Описание</h4>
			    <textarea rows=\"5\" class=\"opis\">".$value['header']."</textarea>
		      </div>
		      <ul class=\"album-foto-actions\">
			   <li>
				<a href=\"#\" class=\"delete\" rel=\"".$value['photo_id']."\" onClick=\"return false;\">
					<img src=\"pic/delete-icon.png\">
					Удалить
				</a>
			   </li>
			   <li>
				<label>
					<input type=\"radio\" name=\"make-cover\" class=\"checkbox\" safari=\"1\" ".str_replace("0","checked",$value['logo'])." onClick=\"$('#logo').attr('src','".$value['photo']."')\">
					Сделать обложкой
				</label>
			   </li>
		     </ul>
	        </div>
	   	  ";
	   }
	?>
	</div>
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
       	  	$('#photos').append('<div class="album-foto-edit group" id="div'+j+'"><div class="desire-photo"><img class="photo" id="'+j+'" src="'+responseJSON.photo+'" width="'+responseJSON.w+'" height="'+responseJSON.h+'"><span></span></div><div class="album-foto-description"><h4>Описание</h4><textarea rows="5" class="opis"></textarea></div><ul class="album-foto-actions"><li><a href="#" class="delete" rel="'+j+'" onClick="return false;"><img src="pic/delete-icon.png">Удалить</a></li><li><label><input type="radio" name="make-cover" class="checkbox" safari="1" onClick="$(\'#logo\').attr(\'src\',\''+responseJSON.photo+'\')"> Сделать обложкой</label></li></ul></div>');
       	  	j--;
		  }
		else alert(responseJSON.error);
      }
    });
 }
 window.onload = createUploader;

 $(document).ready(function(){
 	$('.delete').live('click',function(){
 		if(confirm('Удалить выбранную Вами фотографию из альбома?')){
 		   var photo_id = parseInt($(this).attr('rel'));
 		   $('#div'+photo_id).remove();
 		}
 	});
 	$('#save-photo-changes').click(function(){
 		var photos = new Array();
 	  	$('img.photo').each(function(key,input){
	        photos[photos.length] = {'id':input.id,'img':input.src};
	    });
 	    if(photos.length > 0){
 	  	  var i=0;
 	  	  $('textarea.opis').each(function(key,input){
	          photos[i] = {'id':photos[i].id,'img':photos[i].img,'opis':input.value};
	          i++;
	      });
	      var i=0; var check = 0; var ch = 1;
 	  	  $('input.checkbox').each(function(key,input){
 	  		  if(input.checked){ check++; ch=0;} else {ch=1;}
	          photos[i] = {'id':photos[i].id,'img':photos[i].img,'opis':photos[i].opis,'main':ch};
	          i++;
	      });
	      if($('#title').val().length == 0){ alert('Укажите название альбома'); return; }
 	  	  if(check != 1){ alert('Выберите обложку для фотоальбома'); return; }
 		  $.ajax({
	         url:'/jquery-photoalbumaction',
	         cache:false, type:'POST',
	         data: {type:'edit',album_id:<?=$album_id?>,title:$('#title').val(),'opis':$('#albopis').val(),photos:photos},
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
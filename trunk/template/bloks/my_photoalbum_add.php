<?php
require_once('jquery/jq_myphotoalbums.php');
$rows      =20;
if($_URLP[0]=='my')$user_wp=$_SESSION['WP_USER']['user_wp'];
else               $user_wp=(int)$_URLP[0];
$subs_count=$USER->CountPhotoAlbums($user_wp);
?>
<div class="title margin tx_r">
	<h2 class="tx_l">Фотоальбомы 
	<?php if($subs_count>0) echo '<span class="title-count">('.$subs_count.')</span>';?>
	</h2>
	<h3 class="tx_l"> Добавить фотоальбом</h3>
	<?php if($_URLP[0]=='my')echo '<a href="#" id="add-photo-but" class="btn btn-green" style="position: absolute; right: 0; top: 0;"><i class="small-icon icon-white-plus"></i>Добавить фото</a>';?>
	<div class="separator"></div>
</div>
<?PhotoAlbumsEdit($user_wp);?>
<div class="separator clear"></div>
<div class="title margin group">
	<h3>Добавить фотографии</h3>
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
				$('#photos').append('<div class="photoalbum-elem" id="div'+j+'"><a id="delete" class="fl_r opacity_link" href="#" rel="'+j+'" onClick="return false;"><i class="small-icon"><img src="pic/delete.png"></i><strong>Удалить фотографию</strong></a><div class="bordered big-avatar fl_l"><img class="photo" alt="" src="'+responseJSON.photo+'"></div><div class="add-album-description wrapped"><table><thead></thead><tbody><tr><td>Описание:</td><td><textarea id="opis" class="bordered" placeholder="Введите описание фотографии"></textarea></td></tr><tr><td>Альбом:</td><td><select name="albums" id="albumname"><option value="#">Название альбома...</option><?=Options($user_wp);?></select></td></tr><tr><td></td><td><label><input type="radio" class="checkbox" name="make-cover"  onClick="$(\'#logo\').attr(\'src\',\''+responseJSON.photo+'\')">Сделать обложкой</label></div></div>');
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
 	  	 
		  //$('#albumname').val()
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
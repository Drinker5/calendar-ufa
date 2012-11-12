<?php
require_once('jquery/jq_myphotoalbums.php');
$rows      =20;
if($_URLP[0]=='my')$user_wp=$_SESSION['WP_USER']['user_wp'];
else               $user_wp=(int)$_URLP[0];
$opt = Options($user_wp,1);

?>
<div class="title margin tx_r">
	<h2 class="tx_l">Редактирование фотоальбома<h2>
	<?php if($_URLP[0]=='my')echo '<a href="#" id="add-photo-but" class="btn btn-green" style="position: absolute; right: 0; top: 6px;"><i class="small-icon icon-white-plus"></i>Добавить фото</a>';?>
	<div class="separator"></div>
</div>
<div class="photoalbum group">
	<div class="bordered medium-avatar fl_l">
		<img src="<?=$photos[0]['photo']?>" id="logo" width="113" height="95">
	</div>
	<div class="add-album-description wrapped">
		<table>
			<thead></thead>
			<tbody>
				<tr>
					<td>Альбом:</td>
					<td>
						<input type="text" id="title" value="<?=$album['header']?>">
					</td>
				</tr>
				<tr>
					<td>Описание:</td>
					<td>
						<textarea rows="3" id="albopis"><?=$album['opis']?></textarea>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

	<?php $USER->ShowPhotosIsAlbum($rows);?>
<div class="separator clear"></div>
<div class="title margin group">
	<h3>Редактирование фотографий</h3>
</div>
<div class="photoalbum group">
	<div id="files"></div>
	<div id="photos">
	<?php
	   foreach($photos as $key=>$value){
	   	  
		  $html="
	   	    <div class=\"photoalbum-elem\" id=\"div".$value['photo_id']."\">
			<a id=\"delete\" class=\"fl_r opacity_link\" href=\"#\" rel=".$value['photo_id']." onClick=\"return false;\"><i class=\"small-icon\"><img src=\"pic/delete.png\"></i><strong>Удалить фотографию</strong></a>
		      <div class=\"bordered big-avatar fl_l\">
			    <a href=\"#\">
				 <img class=\"photo\" id=\"".$value['photo_id']."\" src=\"".$value['photo']."\" width=\"113\" height=\"95\">
			    </a>
			    <span></span>
		      </div>
			  <div class=\"add-album-description wrapped\">
				<table>
					<thead></thead>
					<tbody>
						<tr>
							<td>Описание:</td>
							<td>  <textarea rows=\"5\" class=\"opis\">".$value['header']."</textarea></td>
						</tr>
						
						<tr>
							<td>Альбом:</td>
							<td>
								<select>";
									foreach($opt as $key=>$value){
										$html.= '<option value=\"'.$key.'\">'.$value.'</option>';
									}
								$html.="
								</select>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
								<label>
									<input type=\"radio\" name=\"make-cover\" class=\"checkbox\" safari=\"1\" ".str_replace("0","checked",$value['logo'])." onClick=\"$('#logo').attr('src','".$value['photo']."')\">
									Сделать обложкой
								</label>
							</td>
						</tr>
					</tbody>
				</table>
			  </div>
			  
	        </div>
	   	  ";
		
		echo $html;
	   }
	?>
	</div>
	<div class="tx_r">
		<button type="submit" class="btn btn-green" name="add" id="save-photo-changes">Сохранить</button>
	</div>
	</div>
</div>
	
<script src="js/fileuploader.js" type="text/javascript"></script>
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
       	  	$('#photos').append('<div class="album-foto-edit group" id="div'+j+'"><div class="desire-photo"><img class="photo" id="'+j+'" src="'+responseJSON.photo+'" width="'+responseJSON.w+'" height="'+responseJSON.h+'"><span></span></div><div class="album-foto-description"><h4>Описание</h4><textarea rows="5" class="opis"></textarea></div><ul class="album-foto-actions"><li><a href="#" class="delete" rel="'+j+'" onClick="return false;"><img src="pic/delete-icon.png">Удалить</a></li><li><label><input type="radio" name="make-cover" class="checkbox" safari="1" onClick="$(\'#logo\').attr(\'src\',\''+responseJSON.photo+'\')"> Сделать обложкой</label></li></ul></div>');
       	  	j--;
		  }
		else alert(responseJSON.error);
      }
    });
 }
 window.onload = createUploader;

 //$(document).ready(function(){
 	$('#delete').live('click',function(){
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
 //});
</script>
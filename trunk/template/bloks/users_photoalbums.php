<div id="center">
	<h1>Альбомы</h1>
	<?php
	  $rows = 10;
	  $albums = $USER->ShowListPhotoAlbums($USER_INFO['user_wp'],$rows);
	  if(is_array($albums)){
	  	foreach($albums as $key=>$value){
	  		echo "
	  		  <div class=\"album-post group\">
		        <div class=\"desire-photo\">
			      <a href=\"/jquery-photoview?album=".$value['album_id']."&photo=".$value['photo_id']."\" rel=\"photoview\"><img src=\"".$value['logo']."\" width=\"113\" height=\"95\"/></a>
			      <span></span>
		        </div>
		        <div class=\"album-desc\">
			      <a class=\"album-name\" href=\"#\">".$value['header']."</a>
			      <p class=\"update-date\">добавлен: <strong>".$value['data']."</strong></p>
			      <p class=\"foto-count\"><img src=\"pic/fotocamera.png\"><strong>".$value['count_photos']."</strong> фото</p>
		       </div>
	         </div>
	         <p class=\"hr-des\"></p>
	  		";
	  	}
	  }
	?>
</div>
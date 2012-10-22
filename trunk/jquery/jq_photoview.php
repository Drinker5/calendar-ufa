<?php
$album_id = varr_int(@$_GET['album']);
$photo_id = varr_int(@$_GET['photo']);
$photo    = "";
$prevLink = "";
$nextLink = "";

if($album_id > 0 && $photo_id > 0){ // Если это фотоальбом
	$album = $USER->InfoPhotoAlbum(-1,$album_id);
	if(is_array($album)){ // Если альбом существует
		$GLOBALS['PHP_FILE'] = __FILE__; $GLOBALS['FUNCTION'] = __FUNCTION__;
		$photos_all = $MYSQL->query("SELECT id, header FROM pfx_users_photos WHERE album_id=".$album['id']." AND user_wp=".$album['user_wp']." ORDER BY logo, id");
		if(is_array($photos_all)){ // Если в нем есть фотографии
			$photo = ShowPhotoAlbums($album['user_wp'],null,array(array('id'=>$photo_id,'w'=>0,'h'=>0,'center'=>false)));
			if(is_array($photo)){ // Если фотка получена
				$photo = $photo[0]['photo_original'];
				$count = count($photos_all);
				for($i=0; $i < $count; $i++){
					if($photo_id == $photos_all[$i]['id']){
						if($i == 0 && $count > 1){
						   $prevLink = "/jquery-photoview?album=$album_id&photo=".$photos_all[$count-1]['id']; // Последняя фотка
						   $nextLink = "/jquery-photoview?album=$album_id&photo=".$photos_all[$i+1]['id']; // Слудующая
						   break;
						}
						elseif($i < $count){
						   $prevLink = "/jquery-photoview?album=$album_id&photo=".$photos_all[$i-1]['id']; // Предыдущая фотка
						   $nextLink = "/jquery-photoview?album=$album_id&photo=".$photos_all[$i+1]['id']; // Слудующая
						   break;
						}
						elseif($i == $count-1){
						   $prevLink = "/jquery-photoview?album=$album_id&photo=".$photos_all[$i-1]['id']; // Предыдущая фотка
						   $nextLink = "/jquery-photoview?album=$album_id&photo=".$photos_all[0]['id']; // Слудующая
						   break;
						}
					}
				}
			}
		}
	}
}
elseif($photo_id > 0){ // Если это одна фотография
	
}






if($photo == "") die('Фотография не найдена');
?>
<div id="photo-view">
	<img src="<?=$photo?>" class="cboxPhoto">
	<div id="allPhoto">
		<div id="allPhotoClose"></div>
		<div id="allPhotoWrap">
			<h3>День в Париже 2012 апрель.</h3>
			<p>Все фото (23)</p>
			<div id="allPhotoList" class="group">
				<!-- <a href="ajax/gallery-photo.html">
					<img src="img/diablo3.jpg">
				</a>
				<a href="ajax/gallery-photo-2.html">
					<img src="img/diablo_3-2.jpg">
				</a>
				<a href="ajax/gallery-photo-3.html">
					<img src="img/viewdemo.png">
				</a>
				<a href="ajax/gallery-photo-4.html">
					<img src="img/6.jpg">
				</a>
				<a href="ajax/gallery-photo-5.html">
					<img src="img/vader.jpg">
				</a>
				-->
			</div>
		</div>
	</div>
</div>
<div id="comment-view">
	<div id="comment-view-inner">
		<div class="head group">
			<a href="#" class="avatar-inner">
				<img src="img/gallery-avatar.png">
			</a>
			<div class="description">
				<h3>Александр Мартиросян</h3>
				<p>28 апреля</p>
			</div>
		</div>
		<h3>Барселона 2012 апрель бла бла</h3>
		<ul class="comment-actions">
			<li><a href="#" class="with-icons like">Мне нравится</a></li>
			<li><a href="#" class="with-icons mark">Отметить человека</a></li>
			<li><a href="#" class="with-icons location">Местоположение</a></li>
		</ul>
		<div id="comments-list">
			<div class="comment-message group">
				<div class="avatar">
					<img src="img/ava-mini.jpg">
				</div>
				<div class="comment-content">
					<h4>Леонид Ривилис</h4>
					<p>
	Правильное использование CSS свойства float может стать непростой задачей даже для опытного верстальщика. В этой статье собраны варианты применения float, а также некоторые ошибки, с наглядными примерами.
					</p>
					<span class="date">21 фев. в 23:04</span>
				</div>
			</div>
			<div class="comment-message group">
				<div class="avatar">
					<img src="img/ava-mini.jpg">
				</div>
				<div class="comment-content">
					<h4>Леонид Ривилис</h4>
					<p>
						Lorem ipsum dolor sit amet, consetetur sadipscing
						elitr, sed diam nonumy eirmod tempor invidunt ut
						labore et dolore magna aliquyam erat, sed diam
						voluptua. At vero eos et accusam et justo duo
						dolores et ea rebum.
					</p>
					<span class="date">21 фев. в 23:04</span>
				</div>
			</div>
			<div class="comment-message group">
				<div class="avatar">
					<img src="img/ava-mini.jpg">
				</div>
				<div class="comment-content">
					<h4>Леонид Ривилис</h4>
					<p>
						Lorem ipsum dolor sit amet, consetetur sadipscing
						elitr, sed diam nonumy eirmod tempor invidunt ut
						labore et dolore magna aliquyam erat, sed diam
						voluptua. At vero eos et accusam et justo duo
						dolores et ea rebum.
					</p>
					<span class="date">21 фев. в 23:04</span>
				</div>
			</div>
			<div class="comment-message group">
				<div class="avatar">
					<img src="img/ava-mini.jpg">
				</div>
				<div class="comment-content">
					<h4>Леонид Ривилис</h4>
					<p>
						Lorem ipsum dolor sit amet, consetetur sadipscing
						elitr, sed diam nonumy eirmod tempor invidunt ut
						labore et dolore magna aliquyam erat, sed diam
						voluptua. At vero eos et accusam et justo duo
						dolores et ea rebum.
					</p>
					<span class="date">21 фев. в 23:04</span>
				</div>
			</div>
			<div class="comment-message group">
				<div class="avatar">
					<img src="img/ava-mini.jpg">
				</div>
				<div class="comment-content">
					<h4>Леонид Ривилис</h4>
					<p>
						Lorem ipsum dolor sit amet, consetetur sadipscing
						elitr, sed diam nonumy eirmod tempor invidunt ut
						labore et dolore magna aliquyam erat, sed diam
						voluptua. At vero eos et accusam et justo duo
						dolores et ea rebum.
					</p>
					<span class="date">21 фев. в 23:04</span>
				</div>
			</div>
		</div>
		<div id="photo-comment-controls" class="group">
			<a id="photo-comment-send" href="#">
				<img src="pic/photo-send-comment.png">
			</a>
			<a href="#" id="photo-comment-ava">
				<img src="img/chat-ava.png">
			</a>
			<div id="photo-comment-chat">
				<textarea></textarea>
				<ul>
					<li>
						<label>
							<input name="press-enter" type="checkbox" class="safari">
							<img src="pic/press-enter.png">
						</label>
					</li>
					<li>
						<div id="smiles">
							<img src="pic/smiles-icon.png">

						</div>
					</li>
				</ul>
			</div>

			<div id="smiles-window">
				<div id="smiles-top"></div>
				<div id="smiles-mid" class="group">
					<img src="pic/emoticons/emoticon_smile.png" alt=":)" title="Улыбка">
					<img src="pic/emoticons/emoticon_unhappy.png" alt=":(" title="Печаль">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
					<img src="pic/emoticons/emoticon_evilgrin.png" alt=":)" title="Злой">
				</div>
				<div id="smiles-bottom"></div>
			</div>

			<a href="#" class="green-plus-but-small group" id="showAllPhoto">
				<div></div>
				<sub></sub>
				<span>Показать все фото</span>
				<sup></sup>
			</a>
		</div>
	</div>
</div>
<?php
 if($prevLink != "" && $nextLink != ""){
 	echo "
      <script type=\"text/javascript\">
        jQuery(document).ready(function() {
	      //добавляем свои кнопки Вперед-Назад, вместо стандартных. Стандартные прячем в onComplete
	      $('#cboxContent').append('<div id=\"Next\"></div><div id=\"Prev\"></div>');
	
         /* Обработчики наших кнопок вперед-назад */
	     $('#Next').live('click', function() {
		   $.fn.colorbox({
			   href: '$prevLink',
			   opacity:0.5, rel: 'nofollow', fixed: true, current: '',
			   width: settings.minWidth, height: settings.minHeight, transition: 'none',
			   onOpen: callbacks.onOpen,
			   onLoad: callbacks.onLoad,
			   onComplete: callbacks.onComplete
		   });
	     });

	     $('#Prev').live('click', function() {
		   $.fn.colorbox({
			   href: '$nextLink',
			   opacity:0.5, rel: 'nofollow', fixed: true, current: '',
			   width: settings.minWidth, height: settings.minHeight, transition: 'none',
			   onOpen: callbacks.onOpen,
			   onLoad: callbacks.onLoad,
			   onComplete: callbacks.onComplete
		   });
	     });
      });
     </script> 	
 	";
 }
?>
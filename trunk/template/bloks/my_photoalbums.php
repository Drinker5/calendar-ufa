<?php
	require_once('jquery/jq_myphotoalbums.php');
    if($_URLP[0]=='my')$user_wp=$_SESSION['WP_USER']['user_wp'];
	else               $user_wp=(int)$_URLP[0];

	$rows      = 20;
    $subs_count= $USER->CountPhotoAlbums($user_wp);
    $par       = '';
    $type_id   = '';
    $html      = '';

    if (isset($_REQUEST['album_id']) && ($_REQUEST['album_id'] != 0)){
      $album_id    = (int)$_REQUEST['album_id'];
      $album_array = $USER->InfoPhotoAlbum($user_wp,$album_id);
      $photo_array = $USER->ShowPhotosIsAlbum($user_wp,$album_id,$w=117,$h=102);
?>
    <div class="title margin">
        <h2>Фотографии</h2>
    </div>
    <span class="photo-edit fl_r bold"><i class="small-icon icon-comments"></i>Редактировать</span>
    <div class="separator clear"></div>
    <span class="btn btn-green photo-add fl_r"><i class="add-photo"></i>Добавить фото в альбом</span>
    <div class="photo-info">
        <span class="c-asphalt px13">Фотоальбом: </span><span class="blue px13 bold"><?=$album_array['header']?></span>
            <a href="javascript:;" class="px11 block underline bold c-asphalt">Комментарии ()</a>
    </div>
    <div class="photo-grid">
<?php
      for ($i=0; $i<count($photo_array);$i++){
        $html .= '<a rel="original" href="'.$photo_array[$i]['photo_original'].'" alt="'.$photo_array[$i]['header'].'"><img src="'.$photo_array[$i]['photo'].'"></a>';
      }
?>
	<?=$html?>
	</div>
<?php
    }
    else {
?>
    <div class="title margin tx_r">
	    <h2 class="tx_l">Фотоальбомы <span class="title-count">(<?=$subs_count?>)</span></h2>
	    <?php if($_URLP[0]=='my')echo '<a href="/my-photoalbums-add" class="btn btn-green"><i class="small-icon icon-white-plus"></i>Добавить фотоальбом</a>';?>
	    <div class="separator"></div>
    </div>
    <div class="photoalbum group">
	    <?=PhotoAlbumsList($user_wp,$rows)?>
    </div>
<?php
    }

?>
<script type="text/javascript">
    $("a[rel=original]").fancybox({
	    'transitionIn'	: 'none',
		'transitionOut'	: 'none',
	});
	var page=1, max=<?=ceil($subs_count/$rows)?>, rows=<?=$rows?>, begin=rows;
    $('#delete').live('click',function(){
 		if(confirm('Удалить выбранный альбом?')){
			 $.ajax({
	         url:'/jquery-photoalbumaction',
	         cache:false, type:'POST',
	         data: {type:'delete',album_id:$('#СЮДА ВПИСАТЬ ID БЛОКА').val()},
	         success:function(result){
	      	    if(result == 'ok'){
	      		  location.href = '/my-photoalbums';
	      	    } else {alert(result);}
	         }
	      });
			
 		   var id = parseInt($(this).attr('rel'));
 		   $('#div'+id).remove();
 		}
    });
	
	function gifts(){
		//$('div#loadmoreajaxloader').show();
		$.ajax({
			url:'/jquery-mygifts', type:'POST', data:{list:begin, items:rows, type_id:'<?=$type_id?>', par:'<?=$par?>'}, cache:false,
			success: function(data){
				var html,
					idLenta=$('#idLenta'),
					newElems;

				if(data){
					if(max>page){
						//$('div#loadmoreajaxloader').hide();
						html = jQuery.parseJSON(data);
						idLenta.append(html.html);
						idLenta.find('[rel="'+html.uid+'"]').popover({
							trigger: 'none',
							autoReposition: false
							})
							.popover('content', $('#gift-all-info-template').html(), true)
							.popover('setOption', 'position', 'bottom')
						page =page+1;
						begin=begin+rows;
						//alert(page+' '+begin);
					}
					else{
						//$('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
					}
				}
			}
		});
	}

	$(window).scroll(function(){
		if($(window).scrollTop()==$(document).height()-$(window).height()){
			gifts();
		}
	});
</script>
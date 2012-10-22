<?php
	require_once('jquery/jq_myphotoalbums.php');
	$rows      =20;
	if($_URLP[0]=='my')$user_wp=$_SESSION['WP_USER']['user_wp'];
	else               $user_wp=(int)$_URLP[0];
	$subs_count=$USER->CountPhotoAlbums($user_wp);
?>

<div class="title margin tx_r">
	<h2 class="tx_l">Фотоальбомы <span class="title-count">(<?=$subs_count?>)</span></h2>
	<?php if($_URLP[0]=='my')echo '<a href="/my-photoalbums-add" class="btn btn-green"><i class="small-icon icon-white-plus"></i>Добавить фотоальбом</a>';?>
	<div class="separator"></div>
</div>
<div class="photoalbum group">
	<?=PhotoAlbumsList($user_wp,$rows)?>
</div>

<script type="text/javascript">
	var page=1, max=<?=ceil($subs_count/$rows)?>, rows=<?=$rows?>, begin=rows;

	function gifts(){
		//$('div#loadmoreajaxloader').show();
		$.ajax({
			url:'/jquery-mygifts', type:'POST', data:{list:begin, items:rows, type_id:<?=$type_id?>, par:'<?=$par?>'}, cache:false,
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
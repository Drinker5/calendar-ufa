<?php
	require_once('jquery/jq_mygifts.php');
	$t=''; $c=''; $type_id=0; $par='';
	if(isset($_REQUEST['t'])){
		//$t='t='.$_REQUEST['t'].'&';
		$type_id=$_REQUEST['t'];
	}
	if(isset($_REQUEST['c'])){
		//$c='&c='.$_REQUEST['c'];
		$par=$_REQUEST['c'];
	}
	$subs_count=$USER->CountPodarki($type_id,0,$par);
	$rows=10;
?>
				<div class="title margin">
					<h2>Мои подарки</h2>
				</div> <!-- /.title -->

				<div class="nav-panel group">
					<ul class="fl_l left">
						<li<?php if(!isset($_REQUEST['t']) && !isset($_REQUEST['c']))echo ' class="active"';?>><a href="/my-gifts">Все <span class="black">(<?=$USER->CountPodarki(0,0)?>)</span></a></li>
<?php
	$types=$MYSQL->query("SELECT `id`, `name_".LANG_SITE."` `name` FROM `discount_type` WHERE `active`=1 AND `gift`=1");
	if(is_array($types)){
		foreach($types as $key=>$value){
			if(isset($_REQUEST['t']) && $_REQUEST['t']==$value['id'])
				echo '<li class="active"><a href="/my-gifts?t='.$value['id'].'"><i class="small-icon icon-'.$value['id'].'"></i>'.$value['name'].' <span class="black">('.$USER->CountPodarki($value['id']).')</span></a></li>';
			else
				echo '<li><a href="/my-gifts?t='.$value['id'].'"><i class="small-icon icon-'.$value['id'].'"></i>'.$value['name'].' <span class="black">('.$USER->CountPodarki($value['id']).')</span></a></li>';
		}
	}
?>
					</ul>
					<ul class="fl_r right">
						<li<?php if($_REQUEST['c']=='new')echo ' class="active"';?>><a href="/my-gifts?c=new">Ожидаемые (<?=$USER->CountPodarki(0,0,'new')?>)</a></li>
						<li<?php if($_REQUEST['c']=='recieved')echo ' class="active"';?>><a href="/my-gifts?c=recieved">Полученные (<?=$USER->CountPodarki(0,0,'recieved')?>)</a></li>
						<li<?php if($_REQUEST['c']=='expired')echo ' class="active"';?>><a href="/my-gifts?c=expired">Просроченные (<?=$USER->CountPodarki(0,0,'expired')?>)</a></li>
					</ul>
				</div> <!-- /.nav-panel -->

				<div id="idLenta">
					<?=GiftList($rows,0,$type_id,$par)?>
				</div>
				<div id="loadmoreajaxloader" style="display:none; text-align:center;"><img src="/pic/loader.gif" alt="loader" width="32" height="32" /></div>
				<script type="text/javascript">
					var page=1, max=<?=ceil($subs_count/$rows)?>, rows=<?=$rows?>, begin=rows, loading=true;

					function gifts(){
						if(loading==true){
							loading=false;
							if(max>page)$('div#loadmoreajaxloader').show();
							$.ajax({
								url:'/jquery-mygifts', type:'POST', data:{list:begin, items:rows, type_id:<?=$type_id?>, par:'<?=$par?>'}, cache:false,
								success: function(data){
									var html,
										idLenta=$('#idLenta'),
										newElems;
	
									if(data){
										if(max>page){
											$('div#loadmoreajaxloader').hide();
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
											loading=true;
											//alert(page+' '+begin);
										}
										else{
											$('div#loadmoreajaxloader').hide();
											//$('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
										}
									}
								}
							});
						}
					}

					$(window).scroll(function(){
						if($(window).scrollTop()==$(document).height()-$(window).height()){
							gifts();
						}
					});
				</script>
<?php
	$subs_count=$USER->CountPodpiska();
	$rows=12;
	require_once('jquery/jq_subscribe.php');
?>
				<div class="title">
					<h2 id="count_subscribers">Мои подписки&nbsp;<span class="title-count">(<?=$subs_count?>)</span></h2>
				</div>
				<div class="separator"></div>
				<div id="idLenta">
					<?php SubscribesList($rows);?>
				</div>
				<script type="text/javascript">
					var page=1, max=<?=ceil($subs_count/$rows)?>, rows=<?=$rows?>, begin=rows;

					function subscribes(){
						//$('div#loadmoreajaxloader').show();
						$.ajax({
							url:'/jquery-subscribe', type:'POST', data:{list:window.begin, items:rows}, cache:false,
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
											.popover('content', $('#subscribe-info').html(), true)
											.popover('setOption', 'position', 'bottom');
										page =page+1;
										window.begin=window.begin+rows;
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
							subscribes();
						}
					});
				</script>
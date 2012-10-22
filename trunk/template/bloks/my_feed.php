<?php
	require_once('jquery/jq_feed.php');
	$type=isset($_REQUEST['t'])?$_REQUEST['t']:'';
	$circle=1;
	switch($type)
	{
		case'friends':
			$circle=2;
		break;
		case'colleagues':
			$circle=5;
		break;
		case'family':
			$circle=3;
		break;
		case'stars':
			$circle=9;
		break;
	}
	$subs_count=$USER->CountMassHistoryLenta($circle);
	$rows=5;
?>
            <div id="content" class="fl_r">
                <div class="title margin">
                    <h2>Лента новостей</h2>
                </div>
                <div class="nav-panel no-margin-bottom group">
                    <ul class="fl_r right">
<?php
	$a=array(''=>'Все','friends'=>'Друзья','colleagues'=>'Коллеги','family'=>'Семья','stars'=>'Кумиры'); //Табы
	foreach($a as $k=>$v)
		echo '<li class="'.($k==$type?'active':'').'"><a href="/my-feed'.($k?'?t='.$k:'').'">'.$v.'</a></li>';
?>
                    </ul>
                </div>
                <div class="feed-box2 no-border-top">
                    <div class="toggle-group group">
                        <div class="feed-status-top group">
                            <button class="btn btn-green fl_r no-margin" type="submit">Отправить</button>
                            <div class="feed-status2 wrapped">
                                <div class="group">
                                    <i id="photo" class="small-icon icon-photo active fl_r pointer"></i>
                                    <input type="file" multiple="true" accept="image/jpeg,image/png,image/gif">
                                    <span class="arrow_box2 fl_l">Что нового?</span>
                                    <div class="wrapped">
                                        <input type="text" class="no-margin" style="width: 100%;" placeholder="Оставь свое сообщение или отзыв">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="group">
                            <label class="fl_r"><input type="checkbox">Только друзья</label>
                            <div class="fl_l toggle-link pointer">
                                <span>Поиск по новостям</span>
                            </div>
                        </div>
                    </div>

                    <div class="toggle-group hide-elem hide group">
                        <div class="feed-status-top group">
                            <button class="btn btn-green fl_r no-margin" type="submit">Поиск</button>
                            <div class="feed-status2 wrapped">
                                <div class="group">
                                    <i id="photo" class="small-icon icon-search active fl_r pointer"></i>
                                    <span class="arrow_box2 fl_l">Поиск по новостям</span>
                                    <div class="wrapped">
                                        <input type="text" class="mo-margin" style="width: 100%;" placeholder="Введите имя, название или ключевое слово">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="group">
                            <div class="fl_l toggle-link pointer">
                                <span>Моя лента</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="timeline">
                	<?=FeedList($rows,0,$circle)?>
                	<div id="idFeed"></div>
                	<div id="loadmoreajaxloader" style="display:none; text-align:center;"><img src="/pic/loader.gif" alt="loader" width="32" height="32" /></div>
                </div>
            </div>

			<script type="text/javascript">
				var page=1, max=<?=ceil($subs_count/$rows)?>, rows=<?=$rows?>, begin=rows;

				function feed(){
					if(max>page)$('div#loadmoreajaxloader').show();
					$.ajax({
						url:'/jquery-feed',
						type:'POST',
						data:{list:begin,items:rows,circle:<?=$circle?>},
						cache:false,
						success: function(data){
							var html,
								idFeed=$('#idFeed'),
								newElems;

							if(data){
								if(max>page){
									$('div#loadmoreajaxloader').hide();
									html = jQuery.parseJSON(data);
									idFeed.append(html.html);
									idFeed.find('[rel="'+html.uid+'"]').popover({
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
									$('div#loadmoreajaxloader').hide();
									//$('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
								}
							}
						}
					});
				}

				$(window).scroll(function(){
					if($(window).scrollTop()==$(document).height()-$(window).height()){
						feed();
					}
				});
			</script>
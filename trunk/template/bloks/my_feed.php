<?php
	require_once('jquery/jq_feed.php');

	$online=0; //0 - Все, 1 - Онлайн
	$user_wp=$_SESSION['WP_USER']['user_wp'];
	$subs_count=$USER->CountMassHistoryLenta($user_wp,$online);
	$subs_count_online=$USER->CountMassHistoryLenta($user_wp,1);
	$subs_count_friends=$USER->CountFriends(1,$user_wp);
	$rows=5;

	//$type=isset($_REQUEST['t'])?$_REQUEST['t']:'';
	//switch($type)
	//{
	//	case'friends':
	//		$circle=2;
	//	break;
	//	case'colleagues':
	//		$circle=5;
	//	break;
	//	case'family':
	//		$circle=3;
	//	break;
	//	case'stars':
	//		$circle=9;
	//	break;
	//}
?>
<!--
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
-->
            <div id="content" class="fl_r">
                <div class="title margin">
                    <h2>Лента новостей</h2>
                </div>
                <div class="nav-panel no-margin-bottom group">
                    <ul class="right">
                        <li class="active all"><a href="javascript:;" onclick="getFeedAllUser()">Все <span class="title-count">(<?=$subs_count?>)</span></a></li>
                        <li class="online"><a href="javascript:;" onclick="getFeedOnlineUser()">Друзья онлайн <span class="title-count">(<?=$subs_count_online?>)</span></a></li>
                        <li><a href="/my-friends?t=request">Заявки в друзья <span class="green_stiker"><?=$subs_count_friends>0?'+':''?><?=$subs_count_friends?></span></a></li>
                    </ul>
                </div>
<!--
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
-->

                <div class="timeline">
                	<div id="idFeed"><?=FeedList($user_wp,$rows,0,$online)?></div>
                	<div id="loadmoreajaxloader" style="display:none; text-align:center;"><img src="/pic/loader.gif" alt="loader" width="32" height="32" /></div>
                </div>
            </div>

			<script type="text/javascript">
				var page   = 1,
					max    = <?=ceil($subs_count/$rows)?>,
					rows   = <?=$rows?>,
					begin  = rows,
					cToggle= 1;

				function myFeed(){
					if(max>page)$('div#loadmoreajaxloader').show();
					$.ajax({
						url:'/jquery-feed',
						type:'POST',
						data:{user_wp:<?=$user_wp?>,list:begin,items:rows,online:<?=$online?>},
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

				function getFeedAllUser(){
					page=1;
					max=<?=ceil($subs_count/$rows)?>;
					begin=0;
					$('.all').attr('class','active all');
					$('.online').attr('class','online');
					$('#idFeed').html('');
					myFeed();
				}

				function getFeedOnlineUser(){
					page=1;
					max=<?=ceil($subs_count_online/$rows)?>;
					begin=0;
					$('.all').attr('class','all');
					$('.online').attr('class','active online');
					$('#idFeed').html('');
					myFeed();
				}

					function CommentsAction(id,type,n,t){
						var msg     = $("#comments-" + id + "-add").val(),
							msgCount= $("#comments-" + id + "-add").val().length,
							//count   = $("#comments-" + id + "-count").get(0),
							count0  = $("#comments-" + id + "-count-other").get(0)||-1;

						if(type=='add' && msgCount<3)
							alert("CommentsAction: НЕ МЕНЕЕ 3 СИМВОЛОВ");
						else if(type=='add' && msgCount>70)
							alert("CommentsAction: НЕ БОЛЕЕ 70 СИМВОЛОВ");
						else
						{
							$.ajax({
								url:'/jquery-comments',
								type:'POST',
								data:{type:type,id:id,msg:msg,n:n},
								cache:false,
								success: function(data){
									var html,
										//nCount         = count.innerHTML,
										nCountOther    = count0.innerHTML,
										idComments     = $('#comments-' + id);

									if(data){
										if(type=='add'){
											//nCount++;
											nCountOther++;
											html = jQuery.parseJSON(data);
											idComments.append(html.html);
											$("#comments-" + id + "-add").val('');
										} else if(type=='delete'){
											//nCount--;
											nCountOther--;
											$('#comments-' + n + '-id').slideUp('slow',function(){
												$(this).remove();
											});
											if(t==1)
												$('<span id="comments-' + id + '-count-other">' + nCountOther +'</span>').replaceAll('span#comments-' + id + '-count-other');
											if(nCountOther==0)
												$('.toggle-change').remove();
										}
										//$('#comments-' + id + '-count').html(nCount);
									}
								}
							});
						}
					}

					function CommentsShow(id,num){
						var idCommentsFull = $('#comments-' + id + '-full');

						//$(this).toggle(function(){
							if(cToggle==1){
								$.ajax({
									url:'/jquery-comments',
									type:'POST',
									data:{type:'show',id:id,num:num},
									cache:false,
									success: function(data){
										var html;

										if(data){
											cToggle=0;
											html=jQuery.parseJSON(data);
											idCommentsFull.append(html.html);
										}
									}
								});
							}
							else
								idCommentsFull.slideToggle('slow');
							//else
							//	idCommentsFull.slideDown('slow');
						//},
						//function(){
							//idCommentsFull.slideUp('slow');
						//});
					}

				$(window).scroll(function(){
					if($(window).scrollTop()==$(document).height()-$(window).height()){
						myFeed();
					}
				});
			</script>
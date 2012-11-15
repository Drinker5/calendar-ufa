<?php require_once('bloks/root.php')?>
<!DOCTYPE html>
<html>
	<head>
<?php
		require_once('bloks/head.php');
		//Маленький аватар
		$avatar_mini=ShowAvatar(array($_SESSION['WP_USER']['user_wp']),25,25);
		if(is_array($avatar_mini))$avatar_mini=$avatar_mini[0]['avatar'];
		else $avatar_mini=no_foto;
?>
	</head>
	<body class="group">
		<div id="wrap" class="group">
			<div id="nav" class="group">
				<a href="/" class="top-logo fl_l"></a>
				
				<div id="menu" class="wrapped">
					<div class="tabs">
						<ul>
							<a href="/type-5"><li<?php if($_URLP[0]=='type' && $_URLP[1]=='5')echo ' class="active"'; ?>><span class="m-icon m-gift"></span>Подарок</li></a>
							<a href="/starslist"><li<?php if($_URLP[0]=='starslist')echo ' class="active"'; ?>><span class="m-icon m-stars"></span>Кумиры</li></a>
							<a href="/type-3"><li<?php if($_URLP[0]=='type' && $_URLP[1]=='3')echo ' class="active"'; ?>><span class="m-icon m-sale"></span>Sale</li></a>
						</ul>
					</div>
					<div class="r-tools">
						<form>
							<input type="search" placeholder="Поиск">
							<input type="submit" value="">
						</form>
						<div class="small-avatar fl-l">
							<a href="/<?=$_SESSION['WP_USER']['user_wp']?>"><img src="<?=$avatar_mini?>"></a>
							<i class="small-icon icon-white-arrow popover-btn"></i>
						</div>
					</div>
				</div>
			</div><!--!end of #nav-->

			<!--!Левое меню-->
			<?php require_once('bloks/left_menu.php') ?>
			<div id="notify"></div>

			<!--Контент-->
			<div id="content" class="fl_r<?php
				if(@$_URLP[1]=='gifts')      echo ' my_gift';
				elseif(@$_URLP[1]=='friends')echo ' online_friends';
				elseif(@$_URLP[1]=='findfriends')echo ' search_friends_page';
			?><?=@$USER_INFO['zvezda']==1?' cumir':''?>">
				<?php isset($file)?require_once($file):''; ?>
			</div><!--end of content-->

			<!--Подвал-->
			<?php
				//require_once('bloks/footer.php')
			?>

			<!--Окно для пополнения баланса-->
			<script type="text/javascript">
				function WindowPayBalance(user_wp,country_id){
					var newWin = window.open("<?=pay_url?>?user_wp="+user_wp+"&country_id="+country_id,"winpay","width=900,height=540,resizable=no,scrollbars=no,status=no,toolbar=no,location=no,menubar=no");
					newWin.focus();	return false;
				}
			</script>

			<script type="text/javascript">
				var fLast=0,
					cLast=0;

				function myNotify(){
					$.ajax({
						url:'/jquery-notifications',
						type:'POST',
						data:{user_wp:<?=$_SESSION['WP_USER']['user_wp']?>,time:'<?=date('Y-m-d H:i:s')?>'},
						cache:false,
						success: function(data){
							var html;

							if(data){
								html=jQuery.parseJSON(data);console.log(html);
								if(html.fCount>fLast || html.cCount>cLast)
									$.titleAlert('Новые уведомления',{stopOnMouseMove:true,interval:600});
								if(html.fCount>fLast){
									for(var n = fLast; n < html.fCount; ++ n) //alert(html.friends[n].fname);
										$.jnotify(html.friends[n].fname + ' ' + html.friends[n].lname,'хочет дружить',html.friends[n].photo,{lifeTime:8000,click:function(){ window.location.href = '/my-friends?t=request'; }});
										//$('#notify').delay(1000).append('<a href="#"><div id="popup-bottom"><img src="' + html.friends[n].photo + '" /><div class="fl_l"><span class="p-name">' + html.friends[n].fname + ' ' + html.friends[n].lname + '</span><span class="p-purpose">хочет дружить</span></div></div></a>').hide().fadeIn('slow').delay(8000).fadeOut('slow');
									fLast=html.fCount;
								}
								if(html.cCount>cLast){
									for(var n = cLast; n < html.cCount; ++ n)
										$('#notify').delay(1000).append('<a href="#"><div id="popup-bottom"><img src="' + html.friends[n].photo + '" /><div class="fl_l"><span class="p-name">' + html.friends[n].fname + ' ' + html.friends[n].lname + '</span><span class="p-purpose">оставил комментарий</span></div></div></a>').hide().fadeIn('slow').delay(8000).fadeOut('slow');
									cLast=html.cCount;
								}
							}
						}
					});
				}
				myNotify();
				setInterval(function(){
					myNotify();
				},30000);
			</script>
			<a href="#" class="scrollup"></a>
		</div><!--!end of #wrap-->
		<script type="text/javascript" src="/js/bottom.js"></script>
	</body>
</html>
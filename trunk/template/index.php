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
							<a href="/type-5"><li<?php if($_URLP[0]=='type' && $_URLP[1]=='5')echo ' class="active"'; ?>><span class="m-icon m-apple"></span>Подарок</li></a>
							<a href="/starslist"><li<?php if($_URLP[0]=='starslist')echo ' class="active"'; ?>><span class="m-icon m-apple"></span>Кумиры</li></a>
							<a href="/type-3"><li<?php if($_URLP[0]=='type' && $_URLP[1]=='3')echo ' class="active"'; ?>><span class="m-icon m-apple"></span>Sale</li></a>
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

			<!--Контент-->
			<div id="content" class="fl_r">
				<?php require_once($file)?>
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
		</div><!--!end of #wrap-->
	</body>
</html>
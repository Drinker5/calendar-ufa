$(function(){
	$('#slider').bxSlider({
		displaySlideQty:40,
		ticker:true,
		tickerSpeed:7000,
		tickerHover:true
	});
});

$(document).ready(function(){
	//Кастомные селекты
	$('#ret-form-mid select').selectBox();

	//Замена картинок в кнопках при наведении
	$('.clr-but').live('mouseover', function(){
		var acl=$(this).attr('class').replace('clr-but clr-but','clr-but hover-clr-but');
		$(this).attr('class',acl);
	});
	//Замена картинок в кнопках после того, как указатель убирается
	$('.clr-but').live('mouseout', function(){
		var acl=$(this).attr('class').replace('hover-','');
		$(this).attr('class',acl);
	});

	//Выбор языка
	$('#lang').click(function(){
		if($('#lang-list').length>0){}
		else{
			$('#geofilter').append('<div id="lang-list"><div id="lang-list-top"></div><div id="lang-list-mid">Загрузка...</div><div id="lang-list-bot"></div></div>');
			$.ajax({
				url:'/jquery-sellang.php',
				cache:false,
				success:function(data){
					$('#lang-list-mid').html(data);
					$('#lang-close').click(function(){$('#lang-list').remove()});
				}
			});
		}
	});

	$('#login-but').click(function(e){
		e.preventDefault();
		if($('#login-form').length>0){}
		else{
			$('#enter').html('<div style="padding-top:130px"><img src="/pic/loader_clock.gif"></div>');
			$.ajax({
				url:'/jquery-login.php',
				cache:false,
				success:function(data){
					$('#reg-form, #forgot-form').remove();
					$('#enter').html(data);
					$("#login-form input").placeholder();
				}
			});
		}
	});

	$('#reg-but').click(function(e){
		e.preventDefault();
		if($('#reg-form').length>0){}
		else{
			$('#enter').html('<div style="padding-top:130px"><img src="/pic/loader_clock.gif"></div>');
			$.ajax({
				url:'/jquery-register.php',
				cache:false,
				success:function(data){
					$('#login-form, #forgot-form').remove();
					$('#enter').html(data);
					$("#reg-form input").placeholder();
					$('input[safari]:checkbox').checkbox({cls:'jquery-safari-checkbox'});
				}
			});
		}
	});

	$('#forgot-but').live('click',function(e){
		e.preventDefault();
		if($('#forgot-form').length>0){}
		else{
			$('#enter').html('<div style="padding-top:130px"><img src="/pic/loader_clock.gif"></div>');
			$.ajax({
				url:'/jquery-forgot.php',
				cache:false,
				success:function(data){
					$('#login-form, #reg-form').remove();
					$('#enter').html(data);
					$("#forgot-form input").placeholder();
				}
			});
		}
	});

	$('#playdemo').live('click',function(e){
		e.preventDefault();
		if($('#playframe').length>0){}
		else{
			$('#enter').html('<div style="padding-top:130px"><img src="/pic/loader_clock.gif"></div>');
			$.ajax({
				url:'/jquery-video.php',
				cache:false,
				success:function(data){
					$('#enter').html(data);
				}
			});
		}
	});
});
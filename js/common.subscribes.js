jQuery(document).ready(function($){
	//Операции с подписками
	$('.add_subscribe').live('click', function(){
		var that=$(this), id=that.data('shop'), span=$('#count_subscribers').find('span');
		$.ajax({
			url:'/jquery-subscribe', cache:false, type:'POST', data:{subscribe:id},
			success:function(result){
				if(result=='ok'){
					if(that.is('.circle-icon-subscribe')){
						var str   =that.parent('li').html(),
								newstr=str.replace(str.substr(str.indexOf('<br>')+4),'Отписаться');
						that.parent('li').html(newstr.replace('add_subscribe', 'del_subscribe'));
						span.html(parseInt(span.text())+1);
					}
					else{
						that.removeClass('add_subscribe').addClass('del_subscribe');
					}
				}
			}
		});
	});
	$('.del_subscribe').live('click', function(){
		var that=$(this), id=that.data('shop'), span=$('#count_subscribers').find('span');
		if(confirm('Вы действительно хотите отменить подписку?')){
			$.ajax({
				url:'/jquery-subscribe', cache:false, type:'POST', data:{unsubscribe:id},
				success:function(result){
					if(result=='ok'){
						if(that.is('.circle-icon-subscribe')){
							var str   =that.parent('li').html(),
									newstr=str.replace(str.substr(str.indexOf('<br>')+4),'Подписаться');
							that.parent('li').html(newstr.replace('del_subscribe', 'add_subscribe'));
							span.html(parseInt(span.text())-1);
						}
						else if(that.is('.delete_subscr')){
							that.parents('.subscr_block').remove();
							span.html('('+(parseInt(span.text().match(/\((.+)\)/i)[1])-1)+')');
							window.begin=window.begin-1;
							$(window).trigger('scroll');
						}
						else{
							that.removeClass('del_subscribe').addClass('add_subscribe');
						}
					}
				}
			});
		}
	});
});
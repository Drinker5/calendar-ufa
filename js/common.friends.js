//Операции с друзьями
//!Удаление друга и игнорирование
function delfriend(that, list){
	var id=that.data('user'),
			name=that.data('name'),
			frblock=that.parents('.fr-requests');
	if(list==false)span=$('#fr-requests');
	else           span=$('#fr-all');
	if(frblock.length>0)var msg='Вы действительно не хотите принимать дружбу пользователя '+name+'?';
	else                var msg='Вы действительно хотите удалить из друзей пользователя '+name+'?';
	if(confirm(msg)){
		$.ajax({
			url:'/jquery-friendaction', cache:false, type:'POST', data:{friend_del:id},
			success:function(result){
				if(result=='ok'){
					if(frblock.length>0){
						frblock.remove();
						span.html('('+(parseInt(span.text().match(/\((.+)\)/i)[1])-1)+')');
					}
					else if(list==true){
						$('#u'+id).remove();
						that.parents('div.friend-action-popover').remove();
						span.html('('+(parseInt(span.text().match(/\((.+)\)/i)[1])-1)+')');
						begin=begin-1;
					}
					else that.removeClass('id_friend_del').addClass('add_new_friend').html('<i class="small-icon icon-add-friend"></i> Добавить в друзья');
				}
			}
		});
	}
	return false;
}

jQuery(document).ready(function($) {
	$('.id_friend_del').live('click', function(e){
		e.preventDefault();
		delfriend($(this), false);
	});

	//!Добавление друга
	$('.add_new_friend').live('click', function(e){
		e.preventDefault();
		var that=$(this), id=that.data('user'), name=that.data('name');
		console.log('Нажал на кнопку добавления друга. id='+id+'. name='+name+'.');
		$.ajax({
			url:'/jquery-friendaction', cache:false, type:'POST', data:{friend_add:id},
			success:function(result){
				console.log(result);
				if(result=='ok')that.removeClass('add_new_friend').html('<i class="small-icon icon-man-near"></i> Уже приглашён');
			}
		});
	});

	//!Подтверждение дружбы
	$('.save_new_friend').live('click', function(e){
		e.preventDefault();
		var that=$(this), id=that.data('user'), name=that.data('name'), span=$('#fr-requests'), aspan=$('#fr-all');
		$.ajax({
			url:'/jquery-friendaction', cache:false, type:'POST', data:{friend_ok:id},
			success:function(result){
				if(result=='ok'){
					if(that.parents('.friend-item').length>0){
						that.parents('.friend-item').remove();
						span.html('('+(parseInt(span.text().match(/\((.+)\)/i)[1])-1)+')');
						aspan.html('('+(parseInt(aspan.text().match(/\((.+)\)/i)[1])+1)+')');
					}
					else that.removeClass('save_new_friend').addClass('id_friend_del').html('<i class="small-icon icon-delete-friend"></i> Удалить из друзей');
				}
			}
		});
	});

	//Редактирование кругов
	//console.log('Количество объектов с классом .crcledt: '+$('.crcledt').length);

	$('body').on('click', 'label.crcledt input', function(){
		that=$(this), circle=that.val(), friend=that.parents('.friend-id').attr('data-id'), chk=that.attr('checked')||'unchecked';;
		//console.log('Значение в инпуте: '+circle+'. Друг: '+friend+'. Чекан: '+chk+'.');
		$.ajax({
			type: "POST",
			url: "/jquery-friendaction",
			data:{circle:circle, friend:friend, action:chk},
			cache: false,
			success: function(data){
				console.log('Значение в инпуте: '+circle+'. Друг: '+friend+'. Чекан: '+chk+'. Пришли данные: '+data);
			}
		});
	});
});
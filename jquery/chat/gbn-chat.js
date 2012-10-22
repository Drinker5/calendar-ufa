$(function(){
	$('.online-man img').tipsy({gravity:'w', live: true});
});

//Отправка сообщений
function sendMessage(){
	var mess=$('#sended-text'), api=$('#online-bar-max-people #chat-messages').data('jsp');
	if(mess.val().length>0){
		$.ajax({
			url:'/jquery-gbnchat-chatwindow',
			type:'post',
			data:'type=newmess&chat='+$('#chatid').val()+'&avatar='+$('#myava').val()+'&mess='+mess.val()+'&user='+$('#mywp').val()+'&first='+$('#chat-messages .chat-msg').length,
			success:function(data){
				$('#chat-messages .jspPane').append(data);
				mess.val('');
				mess.focus();
				api.reinitialise();
				api.scrollToBottom();
				return false;
			}
		});
	}
}

//Обновление списка комментариев
var load_in_process=false;
function reloadComments(){
	if(!load_in_process){
		var mess=$('#sended-text'), api=$('#online-bar-max-people #chat-messages').data('jsp');
		load_in_process=true;
		$.ajax({
			url:'/jquery-gbnchat-chatwindow',
			type:'post',
			data:'type=reload&chat='+$('#chatid').val()+'&user='+$('#mywp').val(),
			success:function(data){
				$('#chat-messages .jspPane').append(data);
				mess.focus();
				if(data.length>0){
					api.reinitialise();
					api.scrollToBottom();
				}
				load_in_process=false;
				return false;
			}
		});
	}
}

//Обновление списка чатов
function reloadChats(){
	$.ajax({
		url:'/jquery-gbnchat-list',
		type:'post',
		data:'type=reload',
		//dataType:'json',
		success:function(data){
			$('body').append(data);
		}
	});
}

$(document).ready(function(){
	//Подсказки

	var chatlist=$('#online-bar-people');
	$.ajax({
		url:'/jquery-gbnchat-list',
		type:'post',
		data:'type=list',
		success:function(data){
			chatlist.append(data);
			chatlist.jScrollPane({showArrows:false, autoReinitialise:true});
			reloadChats();
		}
	});

	$('#chat-contacts-list').jScrollPane({showArrows:false, autoReinitialise:true});
	$('#chat-contacts-list td').live('click', function(){
		var that=$(this),
				tr=that.parent('tr'),
				rel=tr.attr('rel'),
				exist=$('#online-bar-people').find('.online-man[rel='+rel+']'),
				name=tr.find('.msg b').text(),
				api=$('#online-bar-max-people #chat-messages').data('jsp');
		if(exist.length!=0){
			$('#online-bar-people .online-man').removeClass('online-man-cur');
			exist.addClass('online-man-cur');
			exist.find('sup').remove();
		}
		else{
			$('#online-bar-people .online-man').removeClass('online-man-cur');
			$('#online-bar-people .jspPane').append('<div class="online-man online-man-cur" rel="'+rel+'"><span class=""></span><img src="'+tr.find('.avatar img').attr('src')+'" original-title="'+name+'" width="28" height="28" /></div>');
		}
		$.ajax({
			url:'/jquery-gbnchat-chatwindow',
			type:'post',
			data:'id='+rel+'&type=list',
			success:function(data){
				$('#chat-messages').find('.jspPane').empty().html(data);
				$('#online-bar-max-status p').html(name.replace(' ','<br />'));
				//$('#chat-insert-block').find('.jspPane').css({'bottom':0})
				$('#chat-contacts-list').hide();
				$('#chat-insert-block').show();
				$('.chat-send-btn').attr({'rel':rel});
				$('#sended-text').focus();
				api.reinitialise();
				api.scrollToBottom();
				setInterval("reloadComments();", 5000);
			}
		});
	});

	popupMenu('#online-bar-status img', 'online-bar-status-window', '/jquery-gbnchat-status', 'Загрузка…', 15, 'left');
	popupMenu('#chat-settings', 'chat-settings-window', '/jquery-gbnchat-settings', 'Загрузка…', 7, 'left');
	popupMenu('#online-conference', 'chat-group', '/jquery-gbnchat-group', 'Загрузка…', 7, 'center');

	$('.my-status-mini').live('click', function(){
		var status=$(this).attr('rel');
		$.ajax({
			type:'POST',
			url:'/jquery-gbnchat-status',
			data:'type=switch&status='+status,
			success:function(){
				$('#online-bar-status img').attr('src', '/pic/online-bar-my-'+status+'.png');
				$('#online-bar-status-window').remove();
			}
		});
		return false;
	});

	$('.chat-friend').live('click', function(){
		var n=$('#chat-icons div').size();
		//if(n<7){
			var item=$(this), rel=item.attr('rel'), insert=$('#chat-icons'), img=item.children('img').attr('src');

			if(insert.find('div[rel='+rel+']').length>0){}
			else{
				insert.append('<div rel="'+rel+'"><img src="'+img+'" width="32" height="32" /><img src="/pic/chat-delete.png" class="chat-delete" width="12" height="12" /></div>');
				item.addClass('chat-friend-added');
			}
		//}
		//else{
		//	var info=$('#more10');
		//	info.css({'display':'block','left':(511-info.width())/2+193+50, 'top':310});
		//}
	});
	$('#chat-icons div').live('click', function(){
		var item=$(this), rel=item.attr('rel');
		item.remove();
		$('.chat-friend[rel='+rel+']').removeClass('chat-friend-added');
	});
	$('#chat-cancel').live('click', function(){$('#chat-group').remove()})

	//Смайлики
	var smileWindow = $('#smiles-window');
	$('#smiles > img').click(function() {
		smileWindow.slideToggle(0);
	});

	$('#smiles-window img').click(function() {
		var smileText=$(this).attr('alt'),
				inputElem=$('#sended-text'),
				inputText=inputElem.attr('value'),
				textPos  =getCaretPosition(inputElem.get(0)),
				newText;
		newText=inputText.slice(0,textPos)+' '+smileText+' '+inputText.slice(textPos);
		inputElem.attr('value',newText);
		smileWindow.hide();
		setCaretPosition(inputElem.get(0),textPos+smileText.length+2);
		inputElem.focus();
	});
	$('body').click(function(e) {
		if($(e.target).closest('#smiles').length<=0){
			smileWindow.hide();
		}
	});

	$('input.safari[type=checkbox]').checkbox({cls:'jquery-safari-checkbox'});

	$('.chat-send-btn').click(function(){
		sendMessage();
	});
	$('#sended-text').keyup(function(eventObject){
		if($('#press-enter').prop('checked')==true){
			if(eventObject.which==13)sendMessage();
		}
	});
});


jQuery(document).ready(function($) {
	var onlineBarMax     =$('#online-bar-max'),
			onlineBarStatus  =$('#online-bar-status'),
			onlineBarSettings=$('#online-bar-settings'),
			onlineBarPeople  =$('#online-bar-people'),
			onlineWrap       =$('#online-wrap'),
			showDialog       =onlineBarStatus.children('#show-dialog'),
			chatListMsg      =$('#online-bar-max-people #chat-messages'),
			expireDate       =new Date(),
			onlineWrapTop    =getCookie('wrap-top'),
			onlineWrapLeft   =getCookie('wrap-left'),
			barMaxWidth      =getCookie('barMax-width'),
			barMaxHeight     =getCookie('barMax-height'),
			expanded         =getCookie('expanded');

	//Показать большой блок с чатом
	function showChatBlock(){
		onlineWrap.css({
			width: (parseInt(onlineBarMax.width()) + 52)+'px'
		});
		onlineBarMax.show('slide', {direction: 'left'}, 100);
			onlineBarStatus.addClass('online-bar-status-expand');
			onlineBarSettings.addClass('online-bar-settings-expand');
			onlineBarPeople.addClass('online-bar-people-expand');
		showDialog.hide();
		$('#online-wrap > .ui-resizable-e').show();
		setCookie('expanded', '1', expireDate);
	}
	//Скрыть большой блок с чатом
	function closeChatBlock(){
		onlineBarMax.hide('slide', {direction: 'left'}, 100, function() {onlineWrap.width('auto');});
		onlineBarStatus.removeClass('online-bar-status-expand');
		onlineBarSettings.removeClass('online-bar-settings-expand');
		onlineBarPeople.removeClass('online-bar-people-expand');
		$('#online-wrap > .ui-resizable-e').hide();
		showDialog.show();
		setCookie('expanded', '', expireDate);
	}

	if(barMaxWidth){
		onlineBarMax.css({
			width:barMaxWidth
		});
	}

	if(barMaxHeight) {
		chatListMsg.css({
			height:(parseInt(barMaxHeight) - 70)+'px'
		});

		onlineBarPeople.css({
			height:barMaxHeight
		});

		$('#online-bar-max-people').css({
			height:barMaxHeight
		});
	}

	if (onlineWrapTop && onlineWrapLeft) {
		onlineWrap.css({
			top: onlineWrapTop,
			left: onlineWrapLeft
		});
	}

	expireDate.setDate( expireDate.getDate() + 365 );

	chatListMsg.jScrollPane({showArrows: false, autoReinitialise:true, animateScroll:true});

	$("#online-wrap").draggable({
		cancel: "#chat-messages, input, .ui-resizable-handle",containment: 'document',
		stop: function(event, ui) {
			setCookie('wrap-top', onlineWrap.css('top'), expireDate);
			setCookie('wrap-left', onlineWrap.css('left'), expireDate);
		}
	});

	onlineBarMax.resizable({
		handles: 's', minHeight: 520, alsoResize: '#chat-messages, #online-bar-people, #online-bar-max-people, #online-wrap',
		stop: function(event, ui) {
			setCookie('barMax-height', onlineBarPeople.css('height'), expireDate);
			$( "#online-wrap" ).draggable( "option", "containment", [0, 260, $('body').width()-260, $('body').height()-275] );
		}
	});

	onlineWrap.resizable({
		handles: 'e', alsoResize: '#online-bar-max, #online-bar-max-people', minWidth: 250,
		create: function(event, ui) {
			$('.ui-resizable-e').css('z-index', '2002');
		},
		start: function(event, ui) {
			$('#chat-messages').css('width', '100%');
		},
		stop: function(event, ui) {
			setCookie('barMax-width', onlineBarMax.css('width'), expireDate);
			$("#online-wrap").draggable( "option", "containment", [0, 260, $('body').width()-260, $('body').height()-275] );
		}
	});

	$('#magnifier').click(function() {
		if(!onlineBarMax.is(':visible'))showChatBlock();
		else closeChatBlock();
	});

	showDialog.click(function(){
		showChatBlock();
	});

	$('#online-bar-people .online-man').live('click', function() {
		var rel=$(this).attr('rel'), api=$('#online-bar-max-people #chat-messages').data('jsp');
		if(!onlineBarMax.is(':visible')){
			showChatBlock();
		}
		if(rel=='ab'){
			$('#chat-insert-block').hide();
			$('#chat-contacts-list').show();
		}
		else{
			$.ajax({
				url:'/jquery-gbnchat-chatwindow',
				type:'post',
				data:'id='+rel+'&type=list',
				success:function(data){
					$('#chat-contacts-list').hide();
					$('#chat-insert-block').show();
					chatListMsg.find('.jspPane').empty().html(data);
					$('.chat-send-btn').attr({'rel':rel});
					$('#sended-text').focus();
					api.reinitialise();
					api.scrollToBottom();
					setInterval("reloadComments();", 5000);
				}
			});
		}
		$('#online-bar-people .online-man').removeClass('online-man-cur');
		$(this).addClass('online-man-cur');
		$(this).find('sup').remove();
	});

	$('#online-bar-max-status #close-dialog').click(function(){
		closeChatBlock();
		return false;
	});

	if(expanded){
		showDialog.click();
		$('#online-wrap > .ui-resizable-e').show();
	}
	else{
		$('#online-wrap > .ui-resizable-e').hide();
	}
});
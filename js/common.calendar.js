jQuery(document).ready(function() {
	/* глобальные переменные */
	var event_start = $('#event_start');
	var event_end = $('#event_end');
	var event_after = $('#event_after');
	var event_title = $('#event_title');
	var event_place = $('#event_place');
	
	var place_form = $("#event_place-form");
	var event_repeat = $("#event_repeat");
	var event_finish = $("#event_finish");
	var event_remind = $("#event_remind");
	var event_notes = $("#event_notes");
	var privacy_friends = $("#privacy_friends");
	var textsearch = $("#textforsearch");
	var calendar = $('#calendar');
	var form = $('#dialog-form');
	var event_id = $('#event_id');
	var format = "dd.MM.yyyy HH:mm";
	var format_for_datepicker = "dd.mm.yy";
	var format_for_after = "dd.mm.yy";
	var format_day = "dd";
	var format_month = "MM";
	var format_year = "yyyy";
	var Months = ['','января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
	var lastView; 
	var suggestions = [];
	var placelist = [];
	
	var privacy = 0;
	/* Источники данных */
	var fcSources = { 
		akcia : { 
			type: 'POST',
			url: '/jquery-calendar',cache:true, 
			data: {	op: 'source',	type: 'akcia', textsearch: ''},
			error: function(){ alert(' Ошибка соединения, акции'); }
		},
		user_events : {
			type: 'POST',
			url: '/jquery-calendar',cache:false, 
			data: {	op: 'source', type: 'user_events', textsearch: ''},
			error: function(){ alert(' Ошибка соединения, события пользователя'); }
		},
		user_friends_events : {
			type: 'POST',
			url: '/jquery-calendar',cache:false, 
			data: {	op: 'source',	type: 'user_friends_events', textsearch: ''},
			error: function(){ alert('Ошибка соединения, события друзей'); }
		},
		birthdays : {
			type: 'POST',
			url: '/jquery-calendar',cache:false, 
			data: {	op: 'source',	type: 'birthday', textsearch: ''},
			error: function(){ alert('Ошибка соединения, дни рождения'); }
		}
	};
	/* Обработчик фильтра эвентов*/
	$('#show_combobox').live('change', function () {
		if ($('#show_combobox :selected').val() == 'show_birthdays') {
			calendar.fullCalendar( 'removeEventSources' );
			calendar.fullCalendar( 'removeEvents' );
			calendar.fullCalendar( 'addEventSource', fcSources.birthdays );
		}
		if ($('#show_combobox :selected').val() == 'show_all'){
			calendar.fullCalendar( 'removeEventSources' );
			calendar.fullCalendar( 'removeEvents' );
			calendar.fullCalendar( 'addEventSource', fcSources.akcia );
			calendar.fullCalendar( 'addEventSource', fcSources.user_events );
			calendar.fullCalendar( 'addEventSource', fcSources.user_friends_events );
			calendar.fullCalendar( 'addEventSource', fcSources.birthdays );
		}
	});
	
	/* функция очистки формы */
	function emptyForm() {
		event_start.val("");
		event_end.val("");
		event_after.val("");
		event_title.val("");
		event_id.val("");
		event_notes.val("");
		event_place.val(""); event_place.removeAttr("place_id");
		for(var p in suggestions){	suggestions[p].added = false; }
		privacy_friends.children('span').remove();
		
		event_repeat.selectBox("value", 0);
		event_finish.selectBox("value", 0);
		event_remind.selectBox("value", 0);
	};
	
	/* отображение формы После даты: */
	function finish_afterdata_display(param){
		if(param==1){ event_after.parent().css("display","block"); }
		else { event_after.parent().css("display","none"); } 
	};
	function repeat_finish_activate(param){
		if(param!=0){ event_finish.selectBox('enable');}
		else { event_finish.selectBox('disable');}
	};
	event_finish.change( function() {
			finish_afterdata_display($(this).val());
			event_after.datepicker("hide"); 
	});
	event_repeat.change( function() {
			repeat_finish_activate($(this).val());
	});
	/* режимы открытия формы */
	function formOpen(mode) {
		if(mode == 'add') {
			/* скрываем кнопки Удалить, Изменить и отображаем Добавить*/
			$('#add').show();
			$('#edit').hide();
			$("#delete").button("option", "disabled", true);
			finish_afterdata_display(0);
			repeat_finish_activate(0);			
		}
		else if(mode == 'edit') {
			/* скрываем кнопку Добавить, отображаем Изменить и Удалить*/
			$('#edit').show();
			$('#add').hide();
			$("#delete").button("option", "disabled", false);
		}
		form.dialog('open');
		
		$('.place.group').click(function(){ //клик на место из списка
			//получаем id места
			var place_id = $(this).children().children('a').attr('href').split("-")[1];
			//получаем название места
			var place_name = $(this).children().children('a:eq(1)').text()
			event_place.attr("place_id",place_id);
			event_place.val(place_name);
			place_form.dialog("close");
		});
		event_place.click(function(){
			privacy_friends.autocomplete("close");
			place_form.dialog('open');
		});
		
	};
	/* инициализируем Datetimepicker */
	$.datepicker.regional['ru'] = {
		closeText: 'Закрыть',
		prevText: 'Пред',
		nextText: 'След',
		currentText: 'Сегодня',
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
		monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
		dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
		dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
		dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
		weekHeader: 'Не',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''
	};
	$.datepicker.setDefaults($.datepicker.regional['ru']);
	$.timepicker.regional['ru'] = {
		timeOnlyTitle: 'Выберите время',
		timeText: 'Время',
		hourText: 'Часы',
		minuteText: 'Минуты',
		secondText: 'Секунды',
		millisecText: 'Миллисекунды',
		timezoneText: 'Часовой пояс',
		currentText: 'Сейчас',
		closeText: 'Закрыть',
		timeFormat: 'hh:mm tt',
		amNames: ['AM', 'A'],
		pmNames: ['PM', 'P'],
		ampm: false
	};
	$.timepicker.setDefaults($.timepicker.regional['ru']);
	event_start.datetimepicker({hourGrid: 4, minuteGrid: 10, dateFormat: format_for_datepicker, hourMin:8, hour: 8});
	event_end.datetimepicker(
		{hourGrid: 4, minuteGrid: 10, dateFormat: format_for_datepicker, hourMin:8, hour: 8,
		beforeShow: function(input, inst) {
			var mindate = event_start.datepicker('getDate');
			$(this).datepicker('option', 'minDate', mindate);
		}
		}
	);
	event_after.datepicker({dateFormat: format_for_datepicker, showButtonPanel: true});
	/* инициализируем FullCalendar */
	calendar.fullCalendar({
		firstDay: 1,
		weekMode: 'variable',
		slotMinutes: 60,   
		height: 500,
		editable: true,
		allDaySlot: false,
		minTime: 8,
		maxTime: 24,
		ignoreTimezone: false,
		lazyFetching: true,
		 /* формат времени выводимый перед названием события */
		timeFormat: '',
		header: {
			left: 'prev,next',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		columnFormat: {
			month: 'ddd',
			week: 'ddd d/M',
			day: 'dddd d/M'
		}, 
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
		monthNamesShort: ['Янв.','Фев.','Март','Апр.','Май','Июнь','Июль','Авг.','Сент.','Окт.','Ноя.','Дек.'],
		dayNames: ["Воскресенье","Понедельник","Вторник","Среда","Четверг","Пятница","Суббота"],
		dayNamesShort: ["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],
		buttonText: {
			prev: "",
			next: "",
			prevYear: "&nbsp;&lt;&lt;&nbsp;",
			nextYear: "&nbsp;&gt;&gt;&nbsp;",
			today: "Сегодня",
			month: "Месяц",
			week: "Неделя",
			day: "День"
		},
		selectable: true,
		selectHelper: true,
		
		/* обработчик события клика по определенному дню */
		dayClick: function(event, allDay, jsEvent, view) {

		},
		
		/* обработчик клика по дню/выбора промежутка для создания эвента */
		select: function(start, end, allDay) {
			emptyForm();
			var DateStart = $.fullCalendar.formatDate(start, format);
			var DateEnd = $.fullCalendar.formatDate(end, format);
			event_start.datetimepicker('setDate', start);
			event_end.datetimepicker('setDate', end);
			event_after.val($.datepicker.formatDate(format_for_after, end));
			

			
			var Day = parseInt($.fullCalendar.formatDate(start, format_day),10);
			var Month = parseInt($.fullCalendar.formatDate(start, format_month),10);
			var Year = $.fullCalendar.formatDate(start, format_year);
			$('#dialog-form').dialog('option', 'title', 'Событие '+ Day + ' ' + Months[Month] + ' ' + Year);
			formOpen('add');
			calendar.fullCalendar('unselect');
		},

		/* обработчик кликов по событию */
		eventClick: function(event, jsEvent, view) {
			if(event.editable == false) return;
			var DateStart = $.fullCalendar.formatDate(event.start, format);
			var DateEnd = $.fullCalendar.formatDate(event.end, format);
			
			event_id.val(event.id);
			event_title.val(event.title);
			event_place.attr("place_id",event.place);
			for(var i in placelist){
				if(placelist[i].id == event.place) event_place.val(placelist[i].name);
			}
			event_start.val(DateStart);
			event_end.val(DateEnd);
			event_after.val($.datepicker.formatDate(format_for_after, new Date(event.after)));
			repeat_finish_activate(event.repeat);
			finish_afterdata_display(event.finish);
			event_repeat.selectBox("value", event.repeat);
			event_finish.selectBox("value", event.finish);
			event_remind.selectBox("value", event.remind);
			
			for(var i in event.friends){ //массив id друзей в списке приватности
				var name = 'err';
				for(var p in suggestions){ //Поиск имени друза по id 
					if(suggestions[p].friend_wp == event.friends[i]){ 
					
						name = suggestions[p].name;
						//Удаление из списка добавленного друга
						suggestions[p].added = true; 
						break; 
					}
				}
				addtoprivacy(name,event.friends[i]);
			}
			
			event_notes.val(event.notes);
			
			var Day = parseInt($.fullCalendar.formatDate(event.start, format_day),10);
			var Month = parseInt($.fullCalendar.formatDate(event.start, format_month),10);
			var Year = $.fullCalendar.formatDate(event.start, format_year);
			$('#dialog-form').dialog('option', 'title', 'Событие '+ Day + ' ' + Months[Month] + ' ' + Year);
			formOpen('edit');
		},
		/* событие мышка навелась на эвент */
		eventMouseover: function(event, jsEvent, view) {
			 layer = '';
			var pict_name, pict_title;
			if(event.editable)
			{
				pict_name = '../pic/edit.png';
				pict_title = 'Редактировать';						
			}						
			else
			{
				pict_name = '../pic/e_info.png'; 					
				pict_title = 'Показать всю информацию';						
			}						
			var layer = '<span id="events-layer"  style="float:right">'+
				'<img src="'+pict_name+'" title="'+pict_title+'" id="edbut'+event.id+'" style="vertical-align: bottom;" /></span>';
			 
			 $(this).children('.fc-event-inner').append(layer);
			 $("#edbut"+event.id).hide();
			 $("#edbut"+event.id).fadeIn(300);
			 $("#edbut"+event.id).click(function() {
			  //var title = alert('Молодец');
			});
			
		},   
	   
		/* событие мышка ушла из эвента */
		eventMouseout : function( event, jsEvent, view ) 
		{  
			$('#events-layer').remove();
		},
		/* событие начала перетаскивания */
		eventDragStart: function( event, jsEvent, ui, view ) 
		{  
			
		},
		/* событие конца перетаскивания */
		eventDrop: function( event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view ) 
		{ 
			$.ajax({
				type: 'POST',
				url: '/jquery-calendar',cache:false, 
				data: {
					id: event.id,
					start: $.fullCalendar.formatDate(event.start, format),
					end: $.fullCalendar.formatDate(event.end, format),
					op: 'editdate'
				},
				success: function(id){
					calendar.fullCalendar('refetchEvents');
				}
			});
		},
		
		/* индикатор загрузки (под календарём)*/
		loading: function(bool) {
			if (bool) calendar.after('<div id="loading" style="padding-top:30px; text-align: center;"><img src="/pic/loader_clock.gif"></div>');
			else $('#loading').hide();
		},

		/* событие начала изменения размера */
		eventResizeStart : function( event, jsEvent, ui, view )
		{ 
	
		},
		/* событие конца изменения размера */
		eventResize : function( event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view )   
		{ 
			$.ajax({
				type: 'POST',
				url: '/jquery-calendar',cache:false, 
				data: {
					id: event.id,
					start: $.fullCalendar.formatDate(event.start, format),
					end: $.fullCalendar.formatDate(event.end, format),
					op: 'editdate'
				},
				success: function(id){
					calendar.fullCalendar('refetchEvents');
				}
			});
		},

		
		/* источник записей, изначальные события которые будут отображаться в календаре */
		eventSources: [
			fcSources.akcia, 
			fcSources.user_events,
			fcSources.user_friends_events,
			fcSources.birthdays
		],
		
		viewDisplay: function(view) {
			if (lastView == undefined) { lastView = 'firstRun';  }
			if (view.name != lastView )
			{
				if (view.name == 'agendaDay') 
				{ 
					lastView = view.name;
					var layer1 = '<span id="days-layer1" style="float:right;">'+
								'<div title="Добавить" class="small-icon icon-green-plus" id="qwer1" style="height:15px !important"> </div></span>';
					$(".fc-view-agendaDay .fc-widget-content").hover(
						function(){
							$(this).children("div").append(layer1);
							$("#qwer1").hide();
							$("#qwer1").fadeIn(300);
						},
						function(){
							$('#days-layer1').remove();
						}
					);
				}
			}
		},

	});
	
	/* отображение + в дне при наведении */
	var layer = '<span id="days-layer" style="float:right;">'+
						'<div title="Добавить" class="small-icon icon-green-plus" id="qwer" style=""> </div></span>';
	$(".fc-view-month .fc-widget-content").hover(
		function(){
			$(this).children("div").children(".fc-day-number").append(layer);
			$("#qwer").hide();
			$("#qwer").fadeIn(300);
		},
		function(){
			$('#days-layer').remove();
		}
	);

	

	/* обработчик формы добавления */
	form.dialog({ 
		draggable: false,
		resizable: false,
		//hide: 'title', //анимация при закрытии
		width: 630,
		height: 550,
		modal: true,
		autoOpen: false,
		close: function(){ privacy_friends.autocomplete("close"); },
		buttons: [{
			id: 'add',
			text: 'Добавить',
			click: function() {
			
				for(var i in suggestions){
					if(suggestions[i].added == true){ privacy = 0; break; }
				}
				
				$.ajax({
					type: 'POST',
					url: '/jquery-calendar',cache:false, 
					data: {
						start: event_start.val(),
						end: event_end.val(),
						after: event_after.val(),
						title: event_title.val(),
						place: event_place.attr("place_id"),
						repeat:	$("#event_repeat :selected").val(),
						finish: $("#event_finish :selected").val(),
						remind: $("#event_remind :selected").val(),
						privacy: privacy,
						PrivFriends: suggestions,
						notes: event_notes.val(),
						op: 'add'
					},
					success: function(id){
/*						calendar.fullCalendar('renderEvent', {
																id: id,
																title: event_title.val(),
																start: event_start.val(),
																end: event_end.val(),
																allDay: false,
															});*/
						calendar.fullCalendar('refetchEvents');
					},
				});
				$(this).dialog('close');
				emptyForm();
			}
		},
		{   id: 'edit',
			text: 'Изменить',
			click: function() {
				
				privacy = 1;
				for(var i in suggestions){
					if(suggestions[i].added == true){ privacy = 0; break; }
				}
				
				$.ajax({
					type: 'POST',
					url: '/jquery-calendar',cache:false, 
					data: {
						id: event_id.val(),
						start: event_start.val(),
						end: event_end.val(),
						after: event_after.val(),
						title: event_title.val(),
						place: event_place.attr("place_id"),
						repeat:	$("#event_repeat :selected").val(),
						finish: $("#event_finish :selected").val(),
						remind: $("#event_remind :selected").val(),
						privacy: privacy,
						PrivFriends: suggestions,
						notes: event_notes.val(),
						op: 'edit'
					},
					success: function(id){
						calendar.fullCalendar('refetchEvents');
						
					}
				});
				$(this).dialog('close');
				emptyForm();
			}
		},
		{   id: 'cancel',
			text: 'Отмена',
			click: function() { 
				$(this).dialog('close');
				emptyForm();
			}
		},
		{   id: 'delete',
			text: 'Удалить',
			click: function() { 
			
				$.ajax({
					type: 'POST',
					url: '/jquery-calendar',cache:false, 
					data: {
						id: event_id.val(),
						op: 'delete'
					},
					success: function(id){
						calendar.fullCalendar('removeEvents', id);
					}
				});
				$(this).dialog('close');
				emptyForm();
			},
			disabled: true
		}]
	});
	
	function addtoprivacy(friendname,friendwp) {
		span = $("<span>").text(friendname);
		$("<a>").addClass("remove").attr({href: "javascript:"}).text("x").appendTo(span);
		span.attr("wp",friendwp);
		span.insertBefore("#to");
	};
	$(function(){
		//Получаем список друзей
		$.getJSON("/jquery-calendar?friendlist", function(data) { suggestions = data; });
		//Присоединяем автозаполнение
		privacy_friends.autocomplete({
			minLength:0,
			
			source: function(req, add){	add(suggestions); },
				
			//Определяем обработчик селектора
			select: function(e, ui) {
				//Добавляем в списсок
				addtoprivacy(ui.item.name,ui.item.friend_wp);
			
				//Удаление из списка добавленного друга
				for(var p in suggestions){
					if(suggestions[p].friend_wp == ui.item.friend_wp){ suggestions[p].added = true; break; }
				}
				
				return false;
			},
			//Определяем обработчик выбора
			change: function() {
				//Сохраняем поле 'Кому' без изменений и в правильной позиции
				$("#to").val("").css("top", 2);
			}
		}).data( "autocomplete" )._renderItem = function( ul, item ) {
			//Если друг не добавлен в список и фильтрация по введенному
			if(item.added == false && item.name.toLowerCase().search($("#to").val().toLowerCase()) != -1 ){
				return $( "<li>" ).data( "item.autocomplete", item )
									/* "<img src='"+item.userpic+"'>" */
					.append( "<a>" + "<img src='../pic/nofoto-big.jpg'>" + item.name + "<br>" + item.friend_wp + "</a>" )
					.appendTo( ul );
			}
        };
		
		
		privacy_friends.click(function(){
			//Фокусируемся на поле 'Кому'
			$(this).autocomplete( "search", "" );
			$("#to").focus();
		});
		

		var layer = '<div id="privacy-layer" style="float:right;">'+
						'<div title="Добавить" class="small-icon icon-green-plus" id="qwer" style=""> </div></div>';

		privacy_friends.hover(
			function(){
				$(this).append(layer);
				$("#qwer").hide();
				$("#qwer").fadeIn(300);
			},
			function(){
				$('#privacy-layer').remove();
			}
		);
		
		$("#to").keyup(function(){
			privacy_friends.autocomplete( "search", "" );
		});
		//Добавляем обработчик для события click удаленным ссылкам
		$(".remove", document.getElementById("privacy_friends")).live("click", function(){
			$('#privacy-layer').remove();
			privacy_friends.autocomplete("close");
			//Восстанавливаем в списке друзей для выбора
			var wp = $(this).parent().attr("wp");
			for(var p in suggestions){
				if(suggestions[p].friend_wp == wp){ suggestions[p].added = false; break; }
			}
			
			//Удаляем текущее поле
			$(this).parent().remove();
			//Корректируем положение поля 'Кому'
			if($("#privacy_friends span").length === 0) {
				$("#to").css("top", 0);
			}			
			privacy_friends.data("autocomplete").close.apply(this);
		});				
	});	
	
	$("#privacy_nobody").click(function(){
		$("#privacy_friends_div").hide();
		privacy = 0;
	});

	$("#privacy_all").click(function(){
		$("#privacy_friends_div").show();
		privacy = 1;
	});
	
	$("#textforsearch").keyup(function(){
		/*if(textsearch.val().length >= 3) 
		{*/
			fcSources.akcia.data['textsearch'] = textsearch.val();
			fcSources.user_events.data['textsearch'] = textsearch.val();
			fcSources.user_friends_events.data['textsearch'] = textsearch.val();
			fcSources.birthdays.data['textsearch'] = textsearch.val();
			calendar.fullCalendar('refetchEvents');
		/*}
		else
		{
			fcSources.akcia.data['textsearch'] = "";
			fcSources.user_events.data['textsearch'] = "";
			fcSources.user_friends_events.data['textsearch'] = "";
			fcSources.birthdays.data['textsearch'] = "";
			calendar.fullCalendar('refetchEvents');
		}*/
		
	});
	
	//Получаем список мест
	$(function(){
		$.getJSON("/jquery-calendar?placelist", function(data) { placelist = data; });
	});
	place_form.dialog({
		zIndex: 1010,
		height: 600,
		width: 800,
		autoOpen: false,
		buttons: {
			"Убрать": function() {
				event_place.removeAttr("place_id");
				event_place.val("");
				$( this ).dialog( "close" );
			},
			"Отмена": function() {
				$( this ).dialog( "close" );
			}
		}
		
	});

});


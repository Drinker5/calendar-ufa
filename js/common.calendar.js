jQuery(document).ready(function() {
		    /* глобальные переменные */
            var event_start = $('#event_start');
            var event_end = $('#event_end');
			var event_after = $('#event_after');
            var event_type = $('#event_type');
            var calendar = $('#calendar');
            var form = $('#dialog-form');
            var event_id = $('#event_id');
            var format = "MM/dd/yyyy HH:mm";
			var format_day = "dd";
			var format_month = "MM";
			var Months = ['','января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
			var format_year = "yyyy";
			
            /* функция очистки формы */
            function emptyForm() {
                event_start.val('Событие...');
                event_end.val("");
				event_after.val("99.99.9999");
                event_type.val("");
                event_id.val("");
            } 
            /* режимы открытия формы */
            function formOpen(mode) {
                if(mode == 'add') {
                    /* скрываем кнопки Удалить, Изменить и отображаем Добавить*/
                    $('#add').show();
                    $('#edit').hide();
                    $("#delete").button("option", "disabled", true);
                }
                else if(mode == 'edit') {
                    /* скрываем кнопку Добавить, отображаем Изменить и Удалить*/
                    $('#edit').show();
                    $('#add').hide();
                    $("#delete").button("option", "disabled", false);
                }
				
				form.dialog('open');
            }
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
            event_start.datetimepicker({hourGrid: 4, minuteGrid: 10, dateFormat: 'mm/dd/yy'});
            event_end.datetimepicker({hourGrid: 4, minuteGrid: 10, dateFormat: 'mm/dd/yy'});
			event_after.datetimepicker({hourGrid: 4, minuteGrid: 10, dateFormat: 'mm/dd/yy'});
            /* инициализируем FullCalendar */
            calendar.fullCalendar({
                firstDay: 1,
                height: 500,
                editable: true,
				allDaySlot: false,
				ignoreTimezone: true,
                 /* формат времени выводимый перед названием события */
                timeFormat: 'H:mm',
				
				selectable: true,
				selectHelper: true,
				
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
				
				/* обработчик события клика по определенному дню */
		
                dayClick: function(event, allDay, jsEvent, view) {
                    emptyForm();
					var newDate = $.fullCalendar.formatDate(event, format);
                    event_start.val(newDate);
                    event_end.val(newDate);
					var Month = parseInt($.fullCalendar.formatDate(event, format_month),10);
					$('#dialog-form').dialog('option', 'title', 'Событие '+parseInt($.fullCalendar.formatDate(event, format_day),10) + ' ' + Months[Month] + ' ' + $.fullCalendar.formatDate(event, format_year));
                    formOpen('add');
                },
				
                /* обработчик кликов по событию */
                eventClick: function(event, jsEvent, view) {
					var DateStart = $.fullCalendar.formatDate(event.start, format);
					var DateEnd = $.fullCalendar.formatDate(event.end, format);
                    event_id.val(event.id);
                    event_type.val(event.title);
                    event_start.val(DateStart);
                    event_end.val(DateEnd);
					var Month = parseInt($.fullCalendar.formatDate(event.start, format_month),10);
					$('#dialog-form').dialog('option', 'title', 'Событие '+parseInt($.fullCalendar.formatDate(event.start, format_day),10) + ' ' + Months[Month] + ' ' + $.fullCalendar.formatDate(event.start, format_year));
                    formOpen('edit');
                },
				/* событие мышка навелась на эвент */
				eventMouseover: function(event, jsEvent, view) {

				     var layer = '<div id="events-layer"  style="position:absolute; width:100%; height:100%; top:-1px; text-align:right; z-index:100"><a><img src="../pic/edit.png" title="edit" width="14" id="edbut'+event.id+'" border="0" style="padding-right:5px; padding-top:5px;" /></a></div>';
				     $(this).append(layer);
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
                            type: event.title,
                            op: 'edit'
                        },
                        success: function(id){
                            calendar.fullCalendar('refetchEvents');
                        }
                    });
				},
				
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
                            type: event.title,
                            op: 'edit'
                        },
                        success: function(id){
                            calendar.fullCalendar('refetchEvents');
                        }
                    });
				},

                /* источник записей */

                eventSources: [{
						type: 'POST',
						url: '/jquery-calendar',cache:false, 
						data: {
							op: 'source'
						},
						error: function() {
							alert('Ошибка соединения с источником данных!');
						}
				}],
	
				
				viewDisplay: function(view) {
					

			    },

            });
			var layer = '<div id="days-layer" style="position:relative; text-align:right; width:100%; height:100%; bottom:0;">'+
								'<div title="add" class="small-icon icon-green-plus" id="qwer" style="" </div></div></div>';
			$(".fc-widget-content").hover(
				function(){
					$(this).children("div").children(".fc-day-content").children("div").append(layer);
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
                buttons: [{
                    id: 'add',
                    text: 'Добавить',
                    click: function() {
                        $.ajax({
                            type: 'POST',
							url: '/jquery-calendar',cache:false, 
                            data: {
                                start: event_start.val(),
                                end: event_end.val(),
                                type: event_type.val(),
                                op: 'add'
                            },
                            success: function(id){
                                calendar.fullCalendar('renderEvent', {
                                                                        id: id,
                                                                        title: event_type.val(),
                                                                        start: event_start.val(),
                                                                        end: event_end.val(),
                                                                        allDay: false,
                                                                    });
                                calendar.fullCalendar('refetchEvents');
                            }
                        });
						$(this).dialog('close');
			emptyForm();
                    }
                },
                {   id: 'edit',
                    text: 'Изменить',
                    click: function() {
                        $.ajax({
                            type: 'POST',
							url: '/jquery-calendar',cache:false, 
                            data: {
                                id: event_id.val(),
                                start: event_start.val(),
                                end: event_end.val(),
                                type: event_type.val(),
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
});
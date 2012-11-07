<div id="content">
	<div class="title margin fl_l">
		<h2>Мой календарь</h2>
	</div>

	<div id="show_combobox">Показать:&nbsp&nbsp
		<select>
			<option value="show_all" selected>все</option>
			<option value="show_idols">кумиры</option>
			<option value="show_birthdays">дни рождения</option>
			<option value="show_meetings">встречи</option>
			<option value="show_reminders">напоминания</option>
		</select> 
	</div>
	<hr/>
	<div class="search-events group">
		<div class="p_r fl_l">
			<input type="text" class="bordered" placeholder="Введите имя или название события" id="fio" onkeyup="/*this.value=this.value.replace(/[^a-zA-Zа-я-А-ЯёЁ0-9 ]+/ig,'');*/ search=true; friends(); return false;" />
			<i class="small-icon icon-search"></i>
		</div>
	</div>

	<br>
	
	<div class="whead"><h5>Календарь</h5><div class="clear"></div></div>
	<div id="calendar"></div>
	<!-- Форма для эвентов  -->
	<div id="dialog-form" title="Событие" >
		<form>
			<hr/> <!-- -------------------------------- -->
		   
			<input type="text" id="event_title" name="event_title" placeholder="Событие...">
			<input type="text" id="event_place" name="event_place" placeholder="Место...">

			<label for="event_start" id="lbl">c</label> <input type="text" name="event_start" id="event_start"/>
			<label for="event_start" id="lbl">по</label> <input type="text" name="event_end" id="event_end"/>
			
			<hr/> <!-- -------------------------------- -->
			
				<div>
					<div class="small-icon icon-wish section"></div>
					<div class="small-icon icon-like section"></div>
					<div class="small-icon icon-notice section"></div>
				</div>
			
				
				 <select id="event_repeat">
											<option value="0">Не повторять</option>
											<option value="1">Каждый день</option>
											<option value="7">Каждую неделю</option>
											<option value="30">Каждый месяц</option>
											<option value="365">Каждый год</option>
											</select>
				 <select id="event_finish">
											<option value="0">Не завершать</option>
											<option value="2">После 2 раз</option>
											<option value="3">После 3 раз</option>
											<option value="1">После даты:</option>
											</select>
				 <select id="event_reminder">
											<option value="not_remind">Не напоминать</option>
											<option value="by_email">По e-mail</option>
											<option value="in_notif">В уведомлениях</option>
											</select>
				
			<p style="margin-top:10px; display:none">
			После даты: <input type="text" name="event_after" id="event_after"/>
			</p>
			<hr/> <!-- -------------------------------- -->
			Приватность:
			<div style="color:grey">
				<input id="privacy_all" type="radio" name="privacy" value="privacy_all" checked>Показать в ленте новостей друзей
				<input id="privacy_nobody" type="radio" name="privacy" value="privacy_nobody">Не показывать никому
			</div>
			<div id="privacy_friends_div" >
				Показать событие определенным друзьям: 
				<br>
				<div id="privacy_friends" class="ui-helper-clearfix">
					<input placeholder="Работает!" id="to" type="text">
				</div>
			</div>
			<textarea name="event_notes" id="event_notes" placeholder="Заметки..."></textarea>
			<input type="hidden" name="event_id" id="event_id" value="">
		</form>
	</div>
</div>
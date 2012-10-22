<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#chat-friends').resizable({handles:'s', minHeight:176, alsoResize:'#chat-friends-list'});
		$('#chat-friends-list').jScrollPane({showArrows: false, autoReinitialise:true});
	});
</script>
<div id="group-wrap">
	<div style="padding-top:10px;">Тема конференции:</div>
	<div class="chat-search">
		<sub></sub>
		<input type="text" placeholder="Введите тему конференции..." style="width:226px;" />
		<sup></sup>
	</div>
	<div style="background:url('pic/online-conference.png') no-repeat; padding:3px 0 3px 30px; font-weight:bold;">Пригласить собеседников:</div>
	<div class="chat-search" style="padding-bottom:10px; margin-bottom:0;">
		<sub></sub>
		<input type="text" placeholder="Введите имя друга..." style="width:208px;" />
		<img src="pic/chat-field-search.png" alt="chat-field-search" width="18" height="23" />
		<sup></sup>
	</div>
</div>

<!--frends's icons-->
<div id="chat-icons"></div>
<div class="clear"></div>

<p align="center" style="line-height:1px; padding:0 0 6px 0;"><img src="../pic/chat-hr.png" alt="chat-hr" width="164" height="1" /></p>

<!--friends's list-->
<div id="chat-friends">
	<div id="chat-friends-list">
		<div class="chat-friend" rel="1">
			<sub></sub><img src="img/chat-ava.png" width="32" height="32"><div>Катя Иванова</div>
		</div>
		<div class="chat-friend" rel="2">
			<sub></sub><img src="img/chat-ava.png" width="32" height="32"><div>Ванюшка Суходрищев</div>
		</div>
		<div class="chat-friend" rel="3">
			<sub></sub><img src="img/chat-ava.png" width="32" height="32"><div>Люция Францевна Пферд</div>
		</div>
		<div class="chat-friend" rel="4">
			<sub></sub><img src="img/chat-ava.png" width="32" height="32"><div>Антошка Копайкартошку</div>
		</div>
		<div class="chat-friend" rel="5">
			<sub></sub><img src="img/chat-ava.png" width="32" height="32"><div>Рафик Равикович</div>
		</div>
		<div class="chat-friend" rel="6">
			<sub></sub><img src="img/chat-ava.png" width="32" height="32"><div>Изя Шнипельсон</div>
		</div>
		<div class="chat-friend" rel="7">
			<sub></sub><img src="img/chat-ava.png" width="32" height="32"><div>Роман Алмазов</div>
		</div>
		<div class="chat-friend" rel="8">
			<sub></sub><img src="img/chat-ava.png" width="32" height="32"><div>Карен Абзац</div>
		</div>
		<div class="chat-friend" rel="9">
			<sub></sub><img src="img/chat-ava.png" width="32" height="32"><div>Василий Казюльский</div>
		</div>
	</div>

	<div style="height:22px; margin:10px 10px 0;">
		<span class="clr-but clr-but-blue-nb"><sub></sub><a href="#" id="chat-cancel">Отменить</a><sup></sup></span>
		<span class="clr-but clr-but-blue-nb"><sub></sub><a href="#" id="chat-confirm">Подтвердить конференцию</a><sup></sup></span>
	</div>

	<div id="chat-resize"><div class="ui-icon"></div></div>
</div>

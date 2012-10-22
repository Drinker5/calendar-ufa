<?php
	function lastfirstblock($arrAKCIA,$page){
		switch($arrAKCIA['moderator']){
			case 0:$icon='<img src="admin/pic/mess-yell.png" alt="На модерации" title="На модерации" height="12"> На модерации'; break;
			case 1:$icon='<img src="admin/pic/mess-green.png" alt="Промодерированно" title="Промодерированно" height="12"> Промодерированно'; break;
			case 2:$icon='<img src="admin/pic/mess-red.png" alt="Недопущен" title="Недопущен" height="12"> Недопущен'; break;
		}
		$arr_photo[0]=$arrAKCIA['akcia_id'];
		$foto=ShowFotoAkcia($arr_photo,150,120);
		echo '
		<div class="company" sort="'.$arrAKCIA['akcia_id'].'">'.$okmess.'<a name="'.$arrAKCIA['akcia_id'].'"></a>
			<div class="checkitem"><input type="checkbox" id="idcheck" value="'.$arrAKCIA['akcia_id'].'" /></div>
			<div class="img-in"><a href="/gift-'.$arrAKCIA['akcia_id'].'.php"><img src="'.@$foto[0]['foto'].'" alt="'.$arrAKCIA['header'].'" /></a></div>
			<div class="companyd">
				<h3><a href="/gift-'.$arrAKCIA[$i]['akcia_id'].'.php">'.$arrAKCIA['header'].'</a></h3><p>'.$icon.'</p>';

	if($arrAKCIA['dogovor']==1)echo '<p>Стоимость: '.$arrAKCIA['amount'].' '.$arrAKCIA['currency'].'</p>';
	else echo '<p>С: '.$arrAKCIA['datastart'].'  по: '.$arrAKCIA['datastop'].'</p>';

	echo '<p class="viwed">Просмотров: '.$arrAKCIA['preview'].'</p>
				<p>
					<button onClick="DeleteAkcia('.$arrAKCIA['akcia_id'].')"><img src="/admin/pic/delete16.png" height="12"> Удалить</button>
					<button onClick="location.href=\'/admin-akcia.php?shop_id='.$arrAKCIA['shop_id'].'&akcia='.$arrAKCIA['akcia_id'].'&page='.$page.'\'"><img src="/admin/pic/pencil16.png" height="12"> Редактировать</button>
					<a class="up" href="#"><img src="/admin/pic/arrow_up32.png" width="32" height="32" /></a> <a class="down" href="#"><img src="/admin/pic/arrow_down32.png" width="32" height="32" /></a>
				</p>
			</div>
			<div class="clr"></div>
		</div>';
	}

	require_once(path_kabinet.'modules/ini.admin_akcia.php');

	$tbakcia="pfx_akcia";//Таблица с предложениями

	$action=$_POST['action'];//Направление перемещения
	$shop_id=$_POST['shop_id'];//Компания
	$type_id=$_POST['type_id'];//Тип предложения
	$blocker=$_POST['blocker'];//Первый или последний блок
	$page=$_POST['page'];//Страница

	$sort_arr=$MYSQL->query("SELECT `sort` FROM $tbakcia WHERE `shop_id`=".(int)$shop_id." AND `idtype`=".(int)$type_id." ORDER BY `sort` DESC LIMIT 0,1");
	$lastitem=$sort_arr[0]['sort'];//Последний элемент у этой компании с таким типом предложения

	$moveditem=$_POST['moveditem'];//Перемещаемое предложение

	$moved_arr=$MYSQL->query("SELECT `sort` FROM $tbakcia WHERE `id`=".(int)$moveditem." LIMIT 0,1");
	$movedsort=$moved_arr[0]['sort'];//Текущий порядковый номер перемещаемого предложения

	if($action=='up'){
		if($lastitem!=$movedsort){
			$prev_arr=$MYSQL->query("SELECT `id` FROM $tbakcia WHERE `shop_id`=".(int)$shop_id." AND `idtype`=".(int)$type_id." AND `sort`=".((int)$movedsort+1)." ORDER BY `sort` DESC LIMIT 0,1");
			$previtem=$prev_arr[0]['id']; //Предыдущий элемент, который опускается вниз
			$MYSQL->query("UPDATE $tbakcia SET `sort`=".((int)$movedsort+1)." WHERE `id`=".(int)$moveditem."");//Перемещаемый элемент
			$MYSQL->query("UPDATE $tbakcia SET `sort`=".((int)$movedsort)." WHERE `id`=".(int)$previtem."");//Опускаемый элемент
			if($blocker=='first'){
				$arrAKCIA=$AKCIAS->Show((int)$previtem);
				if(is_array($arrAKCIA))lastfirstblock($arrAKCIA,$page);
			}
		}
	}

	if($action=='down'){
		if($movedsort!=1){
			$prev_arr=$MYSQL->query("SELECT `id` FROM $tbakcia WHERE `shop_id`=".(int)$shop_id." AND `idtype`=".(int)$type_id." AND `sort`=".((int)$movedsort-1)." ORDER BY `sort` DESC LIMIT 0,1");
			$nextitem=$prev_arr[0]['id']; //Предыдущий элемент, который опускается вниз
			$MYSQL->query("UPDATE $tbakcia SET `sort`=".((int)$movedsort-1)." WHERE `id`=".(int)$moveditem."");//Перемещаемый элемент
			$MYSQL->query("UPDATE $tbakcia SET `sort`=".((int)$movedsort)." WHERE `id`=".(int)$nextitem."");//Поднимаемый элемент
			if($blocker=='last'){
				$arrAKCIA=$AKCIAS->Show((int)$nextitem);
				if(is_array($arrAKCIA))lastfirstblock($arrAKCIA,$page);
			}
		}
	}
?>
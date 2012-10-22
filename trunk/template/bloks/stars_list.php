<div id="center">
	<h1>Звезды</h1>
<?php
	$stars_all=$USER->ShowStars();
	if(is_array($stars_all)){
		echo '<div id="idItems">';
		for($i=0; $i<count($stars_all); $i++){
			$arr_users[]=$stars_all[$i]['user_wp'];
		}
		$avatar=ShowAvatar($arr_users,70,70);
		for($i=0; $i<count($stars_all); $i++){
?>
			<div class="myfriend" rel="<?=$stars_all[$i]['user_wp']?>">
				<a href="/<?=$stars_all[$i]['user_wp']?>" class="frlistav"><img src="<?=$avatar[$i]['avatar']?>" width="70" height="70" /></a>
				<a href="/<?=$stars_all[$i]['user_wp']?>"><?=trim($stars_all[$i]['firstname'].' '.$stars_all[$i]['lastname'])?></a><br />
			</div>
<?php
		}
		echo '</div>';
	}

	//echo '<pre>';
	//print_r($USER->ShowStars());
	//echo '</pre>';
?>
	<div class="clear"></div>
</div>
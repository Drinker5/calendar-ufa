<?php
 $user_wp = $_SESSION['WP_USER']['user_wp'];
 $result  = $USER->ShowIHochu($user_wp,10,$_POST['page']);
 if(is_array($result)){
 	$count = count($result);
	for($i=0; $i < $count; $i++){
		$arr_id[] = $result[$i]['akcia_id'];
	}	
	$photo = ShowFotoAkcia($arr_id,130,91);
	for($i=0; $i < $count; $i++){
	
      echo "
	   <div class=\"post\" id=\"".$result[$i]['akcia_id']."_akcia\">
	    <h1>".$result[$i]['header']."</h1>
		<div class=\"desire\">
			<div class=\"desire-photo\"><a href=\"/gift-".$result[$i]['akcia_id']."\"><img src=\"".$photo[$i]['foto']."\" width=\"130\" height=\"91\" /><br />".($result[$i]['amount']/100)." ".$result[$i]['currency']."</a><span></span></div>
			<div class=\"desire-text\">
				".str_replace("\n","<br />",$result[$i]['mtext'])."
			</div>
			<div class=\"desire-buttons\">
				<span class=\"clr-but clr-but-blue-nb\"><sub></sub><a href=\"#\" id=\"reg-form-but\" onClick=\"HideItem(".$result[$i]['akcia_id']."); return false;\">Удалить из списка желаний</a><i></i><sup></sup></span>
				<span class=\"clr-but clr-but-green-nb\"><sub></sub><a href=\"/gift-".$result[$i]['akcia_id']."?present=$user_wp\" id=\"reg-form-but\">Подарить</a><u></u><sup></sup></span>
			</div>
		</div>
		<div class=\"clear\"></div>
	    </div>
	    <p class=\"hr-des\" id=\"".$result[$i]['akcia_id']."_hr\"></p>
	  ";
	}
 }
?>
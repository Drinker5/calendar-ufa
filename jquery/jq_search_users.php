<?php
	if(isset($varr['type']) && $varr['type']=='sf'){
		$letters=strlen($varr['searchword']);
		if($letters>2){
			$limit=$varr['rows'];
			$start=$varr['start'];
			$result=$USER->SearchFriends(@$varr['searchword'],@$varr['p'],$limit,$start);
	
			if(is_array($result)){
				$total = $USER->SearchFriends(@$varr['searchword'],@$varr['p'],0);
				$nrtotal=count($total);
	
				for($i=0; $i < count($result); $i++){
					$arr_users[] = $result[$i]['user_wp'];
				}
				$avatar = ShowAvatar($arr_users,52,52);
				
				for($i=0; $i < count($result); $i++){
	?>
					<div class="display_box" comics="<?=$result[$i]['user_wp']?>">
						<a href="/<?=$result[$i]['user_wp']?>">
							<table border="0" cellpadding="0">
								<tr>
									<td><img src="<?=$avatar[$i]['avatar']?>" class="displayboximg" height="52" width="52"></td>
									<td><?=trim($result[$i]['firstname'].' '.$result[$i]['lastname'])?></td>
								</tr>
							</table>
						</a>
					</div>
	<?php
				}
				echo '<div style="background:#BBB; height:16px;">';
				if($start!=0)echo '<div class="morebox moreboxleft" start="'.($start-$limit).'">вперёд</div>';
				if($nrtotal>($start+$limit))echo '<div class="morebox moreboxright" start="'.($start+$limit).'">назад</div>';
				echo '</div>';
			}
			else echo '';
		}
		else echo '';
	}

	else{
		$result = $USER->SearchFriends(@$varr['fio'],@$varr['p'],10);
		header('Content-type: application/json');
		
		if(is_array($result)){
			echo '['.PHP_EOL;
			for($i=0; $i < count($result); $i++){
				if($i == count($result)-1)
				 echo '{"id":"'.$result[$i]['user_wp'].'","name":"'.trim($result[$i]['firstname'].' '.$result[$i]['lastname']).'"}'.PHP_EOL;
				else
				 echo '{"id":"'.$result[$i]['user_wp'].'","name":"'.trim($result[$i]['firstname'].' '.$result[$i]['lastname']).'"},'.PHP_EOL;
			}
			echo ']';
		} else echo '[]';
	}
?>
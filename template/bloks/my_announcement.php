<div class="title margin">
	<h2>Мои уведомления</h2>
</div>
<div class="notice">
	<table>
		<tr>
			<td></td>
			<td></td>
			<td class="info"></td>
			<td class="cursive"><b>Удалить все</b></td>
			<td class="remove"><i class="small-icon icon-delete" id="all"></i></td>
		</tr>

<?php
	$list=$USER->UvedomList();
	if(is_array($list)){
		$count=count($list);
		$tbusers_deystvie   ='pfx_users_deystvie';
		$tbphotoalbum       ='pfx_users_photos_album';
		$tbuserphotos       ='pfx_users_photos';
		$tbfriends          ='pfx_users_friends';
		$tbpodpiska         ='pfx_podpiska';
		$tbcountry          ='pfx_country';
		$tbshops            ='pfx_shops';
		$tbusers            ='pfx_users';
		$tbhistorypay       ='pfx_historypay';
		$tbakcia            ='pfx_akcia';
		$tbhochu            ='pfx_users_hochu';
		$tbdeystvie         ='pfx_deystvie';
		$tbsettings_deystvie='pfx_users_settings_lenta';
		$tbuserskrugi       ='pfx_users_krugi';

		for($i=0;$i<$count;$i++){
			//Подарок
			if($list[$i]['deystvie']==2){
				$result=$MYSQL->query("
					SELECT `".$tbhistorypay."`.`podarok`
					FROM `".$tbhistorypay."`
					WHERE `".$tbhistorypay."`.`id`=".varr_int($list[$i]['id_deystvie'])."
				");
				if(is_array($result)){
					$giftdata=unserialize($result[0]['podarok']);
					if(is_array($giftdata)){
						$userfrom=$USER->Info_min($list[$i]['user_wp']);
						if(is_array($userfrom)){
?>
						<tr class="uveditem">
							<td><i class="small-icon icon-gift"></i></td>
							<td><?=OnlineStatus($userfrom['status_chat'],'')?></td>
							<td class="info"><b><a href="/<?=$list[$i]['user_wp']?>"><?=trim($userfrom['firstname'].' '.$userfrom['lastname'])?></a></b>  сделал подарок <b><a href="/gift-<?=$giftdata['id']?>"><?=$giftdata['header']?></a> в <a href="/shop-<?=$giftdata['shop_id']?>"><?=$giftdata['shop_name']?></a></b></td>
							<td class="cursive"><?=$list[$i]['date']?></td>
							<td class="remove opacity_link"><i class="small-icon icon-delete" id="u<?=$list[$i]['id']?>" class="chkuv"></i></td>
						</tr>
<?php
						}
					}
				}
			}

			//Приглашение дружить
			elseif($list[$i]['deystvie']==9){
				$userfrom=$USER->Info_min($list[$i]['user_wp']);
				if(is_array($userfrom)){
?>
					<tr class="uveditem">
						<td><i class="small-icon icon-whos-near"></i></td>
						<td><?=OnlineStatus($userfrom['status_chat'],'')?></td>
						<td class="info"><b><a href="/<?=$list[$i]['user_wp']?>"><?=trim($userfrom['firstname'].' '.$userfrom['lastname'])?></a></b> хочет дружить</td>
						<td class="cursive"><?=$list[$i]['date']?>&nbsp;&nbsp;<a href="/my-friends?t=request"><i class="small-icon icon-mark"></i></a></td>
						<td class="remove opacity_link"><i class="small-icon icon-delete" id="u<?=$list[$i]['id']?>"></i></td>
					</tr>
<?php
				}
			}

		}

		//PreArray($list);//Массив с уведомлениями
	}
?>
	</table>
</div>
<script type="text/javascript">
		$('.icon-delete').live('click', function(){
			var id=$(this).attr('id'), block=$(this).parents('tr');
			$.ajax({
				url:'/jquery-checkuved',
				cache:false,
				type: "POST",
				data: {id:id},
				success:function(data){
					if(id=='all')$('.uveditem').remove();
					else block.remove();
				}
			});
		});
</script>
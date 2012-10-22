<?php

function Search($rows){
	global $MYSQL, $varr;
	
	$rows   = (int) $rows;
	$page   = (int) page()-1;
	$begin  = $page*$rows;
	
	$_SESSION['count_all'] = 0;
	
	$tbakcia        = "pfx_akcia";
	$tbtype         = "pfx_type";
	$tbcurr         = "pfx_currency";
	$tbcountryshops = "pfx_country_shops";
	
	if(strlen($varr['search_words']) < 2) return '';
	
	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	
	$where1 = ""; $where2 = "";
	if((int)@$varr['search_type'] > 0){
		$where1 = " AND idtype=".(int)@$varr['search_type'];
		$where2 = " AND $tbakcia.idtype = ".(int)@$varr['search_type'];
	}
	
			
		  $akcia_count = $MYSQL->query("SELECT Count(*) FROM $tbakcia
		                                INNER JOIN $tbcountryshops ON $tbcountryshops.shop_id = $tbakcia.shop_id
		                                INNER JOIN $tbtype ON $tbtype.id = $tbakcia.idtype
		                              WHERE $tbcountryshops.country_id = ".(int)$_SESSION['TOWN_ID']." AND $tbtype.active=1 $where1 AND ($tbakcia.header LIKE '%".$varr['search_words']."%' OR $tbakcia.keywords LIKE '%".$varr['search_words']."%') AND $tbakcia.moderator=1");
          $_SESSION['count_all'] = $akcia_count[0]['count'];
          
          
          $result = $MYSQL->query("SELECT $tbakcia.id, $tbakcia.header, $tbakcia.mtext, $tbakcia.discdata1, $tbakcia.discdata2, $tbakcia.discdata2, $tbakcia.amount, $tbakcia.currency_id, IFNULL($tbtype.dogovor,0) dogovor, $tbakcia.idtype, $tbtype.name_".LANG_SITE." type_name, $tbtype.img_small, $tbakcia.shop_id
		                       FROM $tbakcia
		                       INNER JOIN $tbtype ON $tbtype.id = $tbakcia.idtype
		                       INNER JOIN $tbcountryshops ON $tbcountryshops.shop_id = $tbakcia.shop_id
		                      WHERE $tbcountryshops.country_id = ".(int)$_SESSION['TOWN_ID']."  AND $tbtype.active=1 $where2 AND ($tbakcia.header LIKE '%".$varr['search_words']."%' OR $tbakcia.keywords LIKE '%".$varr['search_words']."%') AND $tbakcia.moderator=1
		                      ORDER BY $tbakcia.adddata DESC
		                      LIMIT $begin,$rows");
		
		if(is_array($result))
        foreach($result as $key=>$value){
        	
        	$currency = $MYSQL->query("SELECT currency, mask FROM $tbcurr WHERE id=".(int)$value['currency_id']);
        	if(is_array($currency) && count($currency) == 1)
        	   $currency = $currency[0]['mask'];
        	else 
        	   $currency = '';
        	   
        	$array[] = array(
        	   'akcia_id'  => $value['id'],
        	   'header'    => $value['header'],
        	   'opis'      => $value['mtext'],
        	   'dogovor'   => $value['dogovor'],        	   
        	   'shop_id'   => $value['shop_id'],
        	   'type_id'   => $value['idtype'],
        	   'type_name' => $value['type_name'],
        	   'type_img'  => $value['img_small'],
        	   'amount'    => $value['amount'],
        	   'currency'  => $currency,
        	   'datastart' => MyDataTime($value['discdata1'],'date2'),
        	   'datastop'  => MyDataTime($value['discdata2'],'date2'),
        	);
        }
        return @$array;
}
?>
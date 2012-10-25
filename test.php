<?php
//Соединение с базой и установка кодировки
	mysql_connect('localhost','root','') or die(mysql_error());
	mysql_select_db('discount') or die(mysql_error());
	mysql_query("set character_set_client='utf8'");
	mysql_query("set character_set_results='utf8'");
	mysql_query("set collation_connection='utf8_unicode_ci'");

	//$q=mysql_query("SELECT * FROM `discount_users` LIMIT 0,1");
	//echo mysql_result($q,0,0).'<br />';

	//define('pfx_passw','wseLAHhVoPj9lt8YcrifXlDEpVU1NSboYLlB8hk-NE8'); // Не изменять и не удалять!!! (old)
	define('pfx_passw','aVQXB1LKB41Oi7sgP5R9gvVUjy1tKfLJu926wDUxSefFAil0ND'); // Не изменять и не удалять!!! (new)

	function tep_rand($min=null, $max=null){
		static $seeded;
		if(!isset($seeded)){
			mt_srand((double)microtime()*1000000);
			$seeded=true;
		}

		if(isset($min) && isset($max)){
			if($min>=$max)return $min;
			else return mt_rand($min, $max);
		}
		else return mt_rand();
	}

	function tep_encrypt_password($plain){
		$password='';
		for($i=0; $i<10; $i++)$password.=tep_rand();
		$salt=substr(md5($password), 0, 2);
		$password=md5($salt . $plain) . ':' . $salt;
		return $password;
	}

	echo tep_encrypt_password('1'.pfx_passw);
?>
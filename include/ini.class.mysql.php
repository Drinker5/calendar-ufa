<?php
class T_MySQL {
	//Соединение с базой данных или вывод сообщения об ошибке соединения
   function Connect(){
   	 global $db_ses;
   	 $db_ses = mysql_connect('localhost','root','') or $this->ErrorQuery();
   	 mysql_select_db('discount',$db_ses) or $this->ErrorQuery();
   }
   
   //Закрытие подключения
   function Close(){
   	 global $db_ses;
   	 mysql_close($db_ses);
   }
   
   function _query($query){
   	   mysql_query("SET time_zone = '+03:00'");
       mysql_query("SET NAMES 'utf8'");
       mysql_query("SET CHARACTER SET 'utf8'");       
       $query = mysql_query($query) or $this->ErrorQuery();   	
       if(mysql_insert_id() > 0) return mysql_insert_id();       
   	   while(@$fetch = mysql_fetch_assoc($query)){
   	     foreach($fetch as $pole=>$value){
   		   $data[preg_replace("/[^_a-zA-Z0-9]/", "", strtolower($pole))] = $value;
   	     }
   	     $array[] = $data;
   	   }
   	   return @$array;
   }
   
   function getmicrotime(){
    list($usec, $sec) = explode(" ", substr(microtime(), 2));
    return substr($sec.$usec, 0, 15);
   }   
   
   function tep_get_ip_address(){
    if (isset($_SERVER)) {
      if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
      } else {
        $ip = $_SERVER['REMOTE_ADDR'];
      }
    } else {
      if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
      } elseif (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
      } else {
        $ip = getenv('REMOTE_ADDR');
      }
    }
    //var_dump(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4));
    $ip_s = explode(" ",$ip);
    return str_replace(",","",trim($ip_s[0]));
   }
      
   function ErrorQuery($msg=''){
   	
   	$mysql_errno = mysql_errno();
	$mysql_error = mysql_error();
	
	$ip   = $this->tep_get_ip_address();
	$host = gethostbyaddr($ip);	
	
	foreach($_REQUEST as $var => $value){
      if(!is_array($value)){		
	   @$VARR .= $var.'='.@$value."\n";
	 }
	}
	
	$log  = "************************************************************************************\n";
	$log .= "Time:            ".date("H:i:s")." \n";
	$log .= "IP:              ".$ip." \n";
	$log .= "HOST:            ".$host." \n";
	$log .= "Method:          ".@$_SERVER['REQUEST_METHOD']." \n";
	$log .= "REQUEST:         ".@$_SERVER['REQUEST_URI']." \n";	
	$log .= "REFERER:         ".@$_SERVER['HTTP_REFERER']." \n";
	$log .= "SCRIPT_FILENAME: ".@$_SERVER['SCRIPT_FILENAME']."\n";
	$log .= "PHP FILE:        ".@$GLOBALS['PHP_FILE']."\n";
	$log .= "FUNCTION:        ".@$GLOBALS['FUNCTION']."\n";
	$log .= "************************************************************************************\n";
	$log .= "MySQL kod: ".$mysql_errno." \n";
	$log .= "Message:   ".$mysql_error." \n";
	$log .= "************************************************************************************\n";	
	$log .= "VARR: \n\n";
	$log .= @$VARR."\n";
	$log .= "************************************************************************************\n\n";
	if($msg != ''){
		$log .= "************************************************************************************\n";	
		$log .= $msg."\n";
		$log .= "************************************************************************************\n\n";
	}
	
	$filename = path_log.date("d_m_Y").".log";
	$fileopen = fopen($filename,'a');
	            fwrite($fileopen,$log);
	            fclose($fileopen);
	
	echo '<pre>'.$log.'</pre>';
	unset($log);
    exit();
   }
   /*****************************************************************************************/
   function query($query){
   	  $query = str_replace("pfx_",'discount_',$query);
      return $this->_query($query);   	
   }
}
$MYSQL = new T_MySQL();
$MYSQL->Connect();
?>
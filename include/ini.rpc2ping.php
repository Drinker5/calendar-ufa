<?php
/********************************/
/*  Module RPC2 ping v.1.0.0.1  */
/********************************/

ini_set('safe_mode','0');

function pingServer($link, $title){	
	
	$url[0] = 'http://blogsearch.google.com.ua/ping/RPC2';
	$url[1] = 'http://ping.blogs.yandex.ru/RPC2';
	
	for($i=0; $i < count($url); $i++){
		switch($i){
			case 0: // google
			 $xml = "<?xml version=\"1.0\"?>\r\n
		             <methodCall>\r\n
  			           <methodName>weblogUpdates.ping</methodName>\r\n
	  		           <params>\r\n
	    		         <param>\r\n
	      			       <value>".$title."</value>\r\n
	    		         </param>\r\n
	    		         <param>\r\n
	      			       <value>".$link."</value>\r\n
	    		         </param>\r\n
	  		           </params>\r\n
		             </methodCall>";		 
			break;
			
			case 1: // yandex
			  $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>".PHP_EOL
		       ."<methodCall>".PHP_EOL
  			   ."    <methodName>weblogUpdates.ping</methodName>".PHP_EOL
	  		   ."    <params>".PHP_EOL
	    	   ."        <param>".PHP_EOL
	      	   ."            <value>".$title."</value>".PHP_EOL
	    	   ."        </param>".PHP_EOL
	    	   ."        <param>".PHP_EOL
	      	   ."            <value>".$link."</value>".PHP_EOL
	    	   ."        </param>".PHP_EOL
	  		   ."    </params>".PHP_EOL
		       ."</methodCall>";
			break;
			
		}
		
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url[$i]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);
		if (curl_errno($ch) != 0) {			
			$result  = "<errno>".curl_errno($ch)."</errno>\n";
			$result .= "<error>".curl_error($ch)."</error>\n";
			return $result;
		};
		curl_close($ch);
		
		$return[] = $result;
	}
	return @$return;
}
?>
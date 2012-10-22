<?php
	ini_set('safe_mode','0');
	ini_set('max_execution_time',30);

	define('MMG_API_URL',pay_url.'xml.php');

	$PAYMENT=new MMG_APIClient('de2774f45a8d8df7ac3f676e8d722e6e','Tm54rFrWtQWY6FEJ','UTF-8');

	class MMG_APIClient{
		var $MMG_SID;
		var $MMG_PASSW;
		var $MMG_MKTIME;
		var $API_VERSION;
		var $CHARSET;

		/* API Constructor */
		function MMG_APIClient($MMG_SID, $MMG_PASSW, $CHARSET){
			$this->MMG_SID = $MMG_SID;
			$this->MMG_PASSW = $MMG_PASSW;
			$this->CHARSET = $CHARSET;
			$this->MMG_MKTIME = $this->getmicrotime();
			$this->API_VERSION = '1.0.0.1';
		}

		function getmicrotime(){
			list($usec, $sec) = explode(" ", substr(microtime(), 2));
			return substr($sec.$usec, 0, 15);
		}

		/* Change string encoding */
		function _change_encoding($text, $entities=false){
			$text = $entities ? htmlspecialchars($text, ENT_QUOTES) : $text;
			return mb_convert_encoding($text, 'UTF-8', $this->CHARSET);
		}	


		function _request($url, $xml, $namefun, $parse = true) {
			$err = 0;
			$ch = curl_init();
			$url_base = $url;
			$url_query = 'xml='.$this->_change_encoding(urlencode($xml));
	        curl_setopt($ch, CURLOPT_URL, $url_base);        
	        curl_setopt($ch, CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $url_query);
			curl_setopt($ch, CURLOPT_HEADER, 0);
	        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$result = curl_exec($ch);
			if (curl_errno($ch) != 0) {			
				$result  = "<errno>".curl_errno($ch)."</errno>\n";
				$result .= "<error>".curl_error($ch)."</error>\n";
				$err = 1;
			};
			curl_close($ch);
			
			//echo '<pre>' . htmlspecialchars(stripslashes(str_replace("\r",'', urldecode($result)))) . '</pre>';
			
			if($parse && $err == 0){
	            require_once('ini.parser.XML.php');
	            $array = parserXML(urldecode($result));
	            
	            $Hash = md5($this->API_VERSION.':'.$namefun.':'.$this->MMG_SID.':'.$this->MMG_PASSW.':'.@$array['MMG']['MkTime']);
	              if($Hash == @$array['MMG']['Hash']){
	            	return $array;
	            } else {
	            	// Описание ошибки в поле Desc
	            	@$array['MMG']['Hash'] = 'Failed Hash';
	            	return $array;
	              }
			} elseif($err > 0) {
	            @$array['Error']['ErrorId'] = '11';
	            @$array['Error']['ErrorDesc'] = 'couldn\'t connect to host';
	            return $array;
			} else {
				return mb_convert_encoding(urldecode($result), $this->CHARSET, 'UTF-8');
			}
		}
	
/*********************************************************************************************************************/		
	
	/****************************************/
	/*          !Балансы Кошельков          */
	/****************************************/
	function Balance($user_wp,$country_id){
		$xml = '<MMG Version="'.$this->API_VERSION.'" Switch="Balance">' . PHP_EOL
             . '  <Auth>' . PHP_EOL
             . '    <SID>' . $this->MMG_SID . '</SID>' . PHP_EOL
             . '    <MkTime>' . $this->MMG_MKTIME . '</MkTime>' . PHP_EOL
             . '    <Hash>' . md5($this->MMG_SID.':'.$this->MMG_MKTIME.':'.$this->MMG_PASSW) . '</Hash>' . PHP_EOL
             . '  </Auth>' . PHP_EOL        
             . '  <Settings>' . PHP_EOL
             . '    <UserID>' . $user_wp . '</UserID>' . PHP_EOL
             . '    <Country_ID>' . $country_id . '</Country_ID>' . PHP_EOL
             . '  </Settings>' . PHP_EOL
             . '</MMG>';
        //echo '<pre>' . htmlspecialchars(stripslashes(str_replace("\r",'', $xml))) . '</pre>';
		return $this->_request(MMG_API_URL, $xml, 'Balance');
	}	
	
	
	/****************************************/
	/*                !Оплата               */
	/****************************************/
	function PayGift($user_wp,$amount,$currency_id,$pin){
		$xml = '<MMG Version="'.$this->API_VERSION.'" Switch="PayGift">' . PHP_EOL
             . '  <Auth>' . PHP_EOL
             . '    <SID>' . $this->MMG_SID . '</SID>' . PHP_EOL
             . '    <MkTime>' . $this->MMG_MKTIME . '</MkTime>' . PHP_EOL
             . '    <Hash>' . md5($this->MMG_SID.':'.$this->MMG_MKTIME.':'.$this->MMG_PASSW) . '</Hash>' . PHP_EOL
             . '  </Auth>' . PHP_EOL        
             . '  <Settings>' . PHP_EOL
             . '    <UserID>' . $user_wp . '</UserID>' . PHP_EOL
             . '    <Amount>' . $amount . '</Amount>' . PHP_EOL
             . '    <Currency_ID>' . $currency_id . '</Currency_ID>' . PHP_EOL
             . '    <Pin>' . $pin . '</Pin>' . PHP_EOL
             . '  </Settings>' . PHP_EOL
             . '</MMG>';
        //echo '<pre>' . htmlspecialchars(stripslashes(str_replace("\r",'', $xml))) . '</pre>';
		return $this->_request(MMG_API_URL, $xml, 'PayGift');
	}	
	
	
	/****************************************/
	/*             !Смена пина              */
	/****************************************/
	function ChangePIN($user_wp,$user_passw,$pin){
		$xml = '<MMG Version="'.$this->API_VERSION.'" Switch="ChangePIN">' . PHP_EOL
             . '  <Auth>' . PHP_EOL
             . '    <SID>' . $this->MMG_SID . '</SID>' . PHP_EOL
             . '    <MkTime>' . $this->MMG_MKTIME . '</MkTime>' . PHP_EOL
             . '    <Hash>' . md5($this->MMG_SID.':'.$this->MMG_MKTIME.':'.$this->MMG_PASSW) . '</Hash>' . PHP_EOL
             . '  </Auth>' . PHP_EOL        
             . '  <Settings>' . PHP_EOL
             . '    <UserID>' . $user_wp . '</UserID>' . PHP_EOL
             . '    <Passw>' . $user_passw . '</Passw>' . PHP_EOL
             . '    <Pin>' . $pin . '</Pin>' . PHP_EOL
             . '  </Settings>' . PHP_EOL
             . '</MMG>';
        //echo '<pre>' . htmlspecialchars(stripslashes(str_replace("\r",'', $xml))) . '</pre>';
		return $this->_request(MMG_API_URL, $xml, 'ChangePIN');
	}
	
	/****************************************/
	/*          !Балансы Кошельков          */
	/****************************************/
	function VIPDiscount($user_wp,$shop_id){
		$xml = '<MMG Version="'.$this->API_VERSION.'" Switch="VIPDiscount">' . PHP_EOL
             . '  <Auth>' . PHP_EOL
             . '    <SID>' . $this->MMG_SID . '</SID>' . PHP_EOL
             . '    <MkTime>' . $this->MMG_MKTIME . '</MkTime>' . PHP_EOL
             . '    <Hash>' . md5($this->MMG_SID.':'.$this->MMG_MKTIME.':'.$this->MMG_PASSW) . '</Hash>' . PHP_EOL
             . '  </Auth>' . PHP_EOL        
             . '  <Settings>' . PHP_EOL
             . '    <UserID>' . $user_wp . '</UserID>' . PHP_EOL
             . '    <ShopID>' . $shop_id . '</ShopID>' . PHP_EOL
             . '  </Settings>' . PHP_EOL
             . '</MMG>';
        //echo '<pre>' . htmlspecialchars(stripslashes(str_replace("\r",'', $xml))) . '</pre>';
		return $this->_request(MMG_API_URL, $xml, 'VIPDiscount');
	}
	
	/****************************************/
	/*    !Зачисление бонусного процента    */
	/****************************************/
	function IsInvitation($from_user,$to_user,$orderId){
		$xml = '<MMG Version="'.$this->API_VERSION.'" Switch="IsInvitation">' . PHP_EOL
             . '  <Auth>' . PHP_EOL
             . '    <SID>' . $this->MMG_SID . '</SID>' . PHP_EOL
             . '    <MkTime>' . $this->MMG_MKTIME . '</MkTime>' . PHP_EOL
             . '    <Hash>' . md5($this->MMG_SID.':'.$this->MMG_MKTIME.':'.$this->MMG_PASSW) . '</Hash>' . PHP_EOL
             . '  </Auth>' . PHP_EOL        
             . '  <Settings>' . PHP_EOL
             . '    <FromUser>' . $from_user . '</FromUser>' . PHP_EOL
             . '    <ToUser>' . $to_user . '</ToUser>' . PHP_EOL
             . '    <OrderId>' . $orderId . '</OrderId>' . PHP_EOL
             . '  </Settings>' . PHP_EOL
             . '</MMG>';
        //echo '<pre>' . htmlspecialchars(stripslashes(str_replace("\r",'', $xml))) . '</pre>';
		return $this->_request(MMG_API_URL, $xml, 'IsInvitation');
	}	
}
?>
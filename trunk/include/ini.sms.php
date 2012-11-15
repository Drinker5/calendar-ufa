<?php

/** Кодировка  WINDOWS-1251  **/

class T_SMS{
    var $user = 'mymobigift';
    var $password = 'sms3452255';
    var $response = 'Tooeezzy';
    //var $response = 'MyMobiGift';
    //var $response = 'WebPassport';    
    var $status = 1;
    var $flash = 0;
    var $time = 1439;
    var $KOD_SMS;
    
	function BalanceSMS(){
		
		require_once('DEVINOSMS.Class.php');
        $devino = new DEVINOSMS();        
        $Balance = $devino->GetCreditBalance($this->user, $this->password);
        return $Balance['Balance'];
	}
	
/*-----SendSMS-----*/
	//Отправка SMS
	function SendSMS($mobile,$text){
		//Проверка баланса на devinotele.com
		$sms_balance=$this->BalanceSMS();

		if($sms_balance==False or $sms_balance==0)return False;

		$devino=new DEVINOSMS();

		$to=str_replace(' ','',$mobile);//Номер для отправки SMS
		$str=$text.PHP_EOL.'www.tooeezzy.com';

		//Проверка кодировки и если UTF, то переводим текст в CP1251
		if(is_utf8($str))$str=UTF8toCP1251($str);

		$str=$this->translit_ru(strip_tags($str));//Делаем транслитерацию
		$str=str_replace('\$', 'USD', $str);
		$str=preg_replace('/&.+?;/', '', $str);//Kill entities
		$str=preg_replace('|[^a-zA-Z0-9 _.:;\n\-@]|i', '', $str);//ASCII filter
		$str=preg_replace('/ +/', ' ', $str);//Cut all spaces
		$str=substr($str, 0, 160);//Максимум 160 символов
		$str=iconv('WINDOWS-1251', 'UTF-8', $str);

		//Отправка SMS
		$result=$devino->SendTextMessage($this->user,$this->password,$to,$str,$this->response,$this->status,$this->flash,$this->time);

          /*echo '<pre>';
        print_r($result);
        echo '</pre>';*/

		//Результат отправки SMS
		if(isset($result['IDSMS']) && $result['IDSMS']!=''){
			$this->KOD_SMS=$result['IDSMS'];
			return True;
		}
		else return False;
	}

/*-----State-----*/
	function State($kodsms){
		require_once('DEVINOSMS.Class.php');
		$devino = new DEVINOSMS();
		return $devino->GetMessageState($this->user,$this->password,$kodsms);
	}
	
	
	
	function translit_ru($st) {
     // Сначала заменяем "односимвольные" фонемы.
     $st = strtr($st,"абвгдеёзийклмнопрстуфхъыэ_",
                      "abvgdeeziyklmnoprstufh'iei");
     $st = strtr($st,"АБВГДЕЁЗИЙКЛМНОПРСТУФХЪЫЭ_",
                     "ABVGDEEZIYKLMNOPRSTUFH'IEI");
     // Затем - "многосимвольные".
     $st = strtr($st, array(
                 "ж"=>"zh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", 
                 "щ"=>"shch","ь"=>"", "ю"=>"yu", "я"=>"ya",
                 "Ж"=>"ZH", "Ц"=>"TS", "Ч"=>"CH", "Ш"=>"SH", 
                 "Щ"=>"SHCH","Ь"=>"", "Ю"=>"YU", "Я"=>"YA",
                 "ї"=>"i", "Ї"=>"Yi", "є"=>"ie", "Є"=>"Ye"
                )
     );
     return $st;
    }
}
$SMS = new T_SMS();
?>
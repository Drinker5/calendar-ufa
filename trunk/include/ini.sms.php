<?php

/** ���������  WINDOWS-1251  **/

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
	//�������� SMS
	function SendSMS($mobile,$text){
		//�������� ������� �� devinotele.com
		$sms_balance=$this->BalanceSMS();

		if($sms_balance==False or $sms_balance==0)return False;

		$devino=new DEVINOSMS();

		$to=str_replace(' ','',$mobile);//����� ��� �������� SMS
		$str=$text.PHP_EOL.'www.tooeezzy.com';

		//�������� ��������� � ���� UTF, �� ��������� ����� � CP1251
		if(is_utf8($str))$str=UTF8toCP1251($str);

		$str=$this->translit_ru(strip_tags($str));//������ ��������������
		$str=str_replace('\$', 'USD', $str);
		$str=preg_replace('/&.+?;/', '', $str);//Kill entities
		$str=preg_replace('|[^a-zA-Z0-9 _.:;\n\-@]|i', '', $str);//ASCII filter
		$str=preg_replace('/ +/', ' ', $str);//Cut all spaces
		$str=substr($str, 0, 160);//�������� 160 ��������
		$str=iconv('WINDOWS-1251', 'UTF-8', $str);

		//�������� SMS
		$result=$devino->SendTextMessage($this->user,$this->password,$to,$str,$this->response,$this->status,$this->flash,$this->time);

          /*echo '<pre>';
        print_r($result);
        echo '</pre>';*/

		//��������� �������� SMS
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
     // ������� �������� "��������������" ������.
     $st = strtr($st,"������������������������_",
                      "abvgdeeziyklmnoprstufh'iei");
     $st = strtr($st,"�����Ũ������������������_",
                     "ABVGDEEZIYKLMNOPRSTUFH'IEI");
     // ����� - "���������������".
     $st = strtr($st, array(
                 "�"=>"zh", "�"=>"ts", "�"=>"ch", "�"=>"sh", 
                 "�"=>"shch","�"=>"", "�"=>"yu", "�"=>"ya",
                 "�"=>"ZH", "�"=>"TS", "�"=>"CH", "�"=>"SH", 
                 "�"=>"SHCH","�"=>"", "�"=>"YU", "�"=>"YA",
                 "�"=>"i", "�"=>"Yi", "�"=>"ie", "�"=>"Ye"
                )
     );
     return $st;
    }
}
$SMS = new T_SMS();
?>
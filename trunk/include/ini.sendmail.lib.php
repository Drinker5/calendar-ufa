<?php
	function send_mail($to_array, $msg, $subject='', $fromaddress=sys_email, $fromname=sys_copy, $attachments=false){
		include('Mail.php');

		if(!is_array($to_array) && is_email($to_array))$to_array=array($to_array);

		if(!is_array($to_array))return false;

		if(count($to_array)==1){
			foreach($to_array as $key=>$email)@$emails.=$email.',';

			$emails=substr($emails, 0, strlen($emails)-1);

			//$fromname="Пупкин Вася";

			/* mail setup recipients, subject etc */
			$recipients                          =$to_array;
			$headers["To"]                       =$emails;
			$headers["From"]                     =$fromaddress.' ('.$fromname.')';
			$headers["Content-Type"]             ='text/html; charset=UTF-8; format=flowed';
			$headers["MIME-Version"]             ='1.0';
			$headers["Content-Transfer-Encoding"]='8bit';
			$headers["Reply-To"]                 =$fromaddress;
			$headers["Return-Path"]              =$fromaddress;
			$headers["X-Mailer"]                 ='PHP';
			$headers["Subject"]                  ='=?UTF-8?B?'.base64_encode($subject).'?=';
			$mailmsg                             =$msg;

			$smtpinfo["host"]    ='mail.giftlandia.com';
			$smtpinfo["port"]    ='25';
			$smtpinfo["auth"]    =false;
			$smtpinfo["username"]='';
			$smtpinfo["password"]='';

			/* Create the mail object using the Mail::factory method */
			$mail_object=& Mail::factory('smtp', $smtpinfo);

			//Вывод массива с email, чтобы проверить, что в нем
			//echo '<pre>';
			//print_r($recipients);
			//echo '</pre>'.$fromaddress;
			//echo $emails;

			/* Ok send mail */
			return $mail_object->send($recipients, $headers, $mailmsg);
		}
		else return false;
	}
?>
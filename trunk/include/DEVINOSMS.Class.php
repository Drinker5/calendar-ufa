<?php
/*******************************************************************************

                   W W W . S M S - S C R I P T . R U

  www.sms-script.ru  www.aliasms.com  www.sms-notify.ru          (c) 2008

 +----------------------------------------------------------------------+
 | PHP Version 4                                                        |
 +----------------------------------------------------------------------+
 | Copyright (c) 2008 www.sms-script.ru                                 |
 +----------------------------------------------------------------------+
 | Данный класс предназначен для работы с сервером DEVINO.SMS           |
 | Скрипт распространяется бесплатно "так, как есть"                    |
 +----------------------------------------------------------------------+
 | Authors: Martin Jansen <mj@php.net>                                  |
 +----------------------------------------------------------------------+

 FileName: DEVINOSMS.Class.php
 Version: 1.00
 Date: 2008/01/11 $

*******************************************************************************/

Class DEVINOSMS {
    /**
    * Расшифровка ответ сервера на запрос
    *
    * @param $status string Статус комманды от сервера
    *
    * @return string Расшифровка статус комманды от сервера
    */
    function GetCommandStatus($status){
      switch($status){
        case 'OK_Operation_Completed':
          return 'Операция выполнена';
        break;

        case 'Error_Not_Enough_Credits':
          return 'Ошибка: недостаточно кредитов';
        break;

        case 'Error_Message_Rejected':
          return 'Ошибка: сообщение отклонено';
        break;

        case 'Error_Invalid_Destination_Address':
          return 'Ошибка: некорректный номер получателя сообщения';
        break;

        case 'Error_Invalid_Source_Address':
          return 'Ошибка: некорректный адрес отправителя сообщения';
        break;

        case 'Error_SMS_User_Disabled':
          return 'Ошибка: СМС-пользователь заблокирован';
        break;

        case 'Error_Invalid_MessageID':
          return 'Ошибка: некорректный идентификатор сообщения';
        break;

        case 'Error_Invalid_Login':
          return 'Ошибка: неправильный логин';
        break;

        case 'Error_Invalid_Password':
          return 'Ошибка: неправильный пароль';
        break;

        case 'Error_Unauthorised_IP_Address':
          return 'Ошибка: неавторизованный IP-адрес';
        break;

        case 'Error_Message_Queue_Full':
          return 'Ошибка: очередь сообщений полна';
        break;

        case 'Error_Gateway_Offline':
          return 'Ошибка: сервер недоступен';
        break;

        case 'Error_Gateway_Busy':
          return 'Ошибка: сервер занят другим запросом';
        break;

        case 'Error_Database_Offline':
          return 'Ошибка: сервер базы данных недоступен';
        break;

        default:
          return 'Ответ не распознан';
        break;

      }

    }

    /**
    * Расшифровка статуса сообщения
    *
    * @param $status string Статус сообщения
    *
    * @return string Расшифровка статуса сообщения
    */
    function GetMessageStatus($status){
      switch($status){
        case 'Enqueued':
          return 'Сообщение ожидает отправки';
        break;

        case 'Delivered_To_Gateway':
          return 'Сообщение доставлено на сервер';
        break;

        case 'Sent':
          return 'Сообщение передано в мобильную сеть';
        break;

        case 'Delivered_To_Recipient':
          return 'Сообщение доставлено получателю';
        break;

        case 'Error_Invalid_Destination_Address':
          return 'Ошибка: некорректный номер получателя сообщения';
        break;

        case 'Error_Invalid_Source_Address':
          return 'Ошибка: некорректный адрес отправителя сообщения';
        break;

        case 'Error_Rejected':
          return 'Ошибка: сообщение отклонено';
        break;

        case 'Error_Expired':
          return 'Ошибка: истек срок жизни сообщения';
        break;

        default:
          return 'Статус не распознан';
        break;

      }

    }

    /**
    * Формирования и отправка запроса на сервер через cURL
    *
    * @param $xml_data string XML-запрос к серверу (SOAP)
    * @param $headers string Заголовки запроса к серверу (SOAP)
    *
    * @return string XML-ответ от сервера (SOAP)
    */
    function SendToServer($xml_data,$headers){
    	$ch = curl_init();
    	//$url = 'http://ws.devinosms.com/SmsService.asmx';
    	$url = 'http://webservice.devinosms.com/WebService.asmx';
        curl_setopt($ch, CURLOPT_URL,$url);        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_data);
        $data = curl_exec($ch);
        
        if (curl_errno($ch)) {
            return False; 
            //die("Error: " . curl_error($ch));
        } else {
            curl_close($ch);
            return $data;
        }
    }

    /**
    * GetCreditBalance – запрос на получение баланса пользователя
    *
    * @param $login string Логин пользователя
    * @param $password string Пароль пользователя
    *
    * @return array("Ответ сервера" => (string), "Балланс" => (decimal)) Ответ сервера в виде массива данных
    */
    function GetCreditBalance($login,$password){
        $xml_data = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xmlns:xsd="http://www.w3.org/2001/XMLSchema"
        xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
          <soap:Body>
            <GetCreditBalance xmlns="http://gw1.devinosms.com/WebService.asmx">
              <smsUser>'.$login.'</smsUser>
              <password>'.$password.'</password>
            </GetCreditBalance>
          </soap:Body>
        </soap:Envelope>';
        $headers = array(
            "POST /WebService.asmx HTTP/1.1",
            "HOST gw1.devinosms.com",
            "Content-Type: text/xml; charset=utf-8",
            "Content-length: ".strlen($xml_data),
            "SOAPAction: http://gw1.devinosms.com/WebService.asmx/GetCreditBalance"
        );
        $data = $this->SendToServer($xml_data,$headers);
        if($data == False) return False;
        // Show me the result
        $p = xml_parser_create();
        xml_parse_into_struct($p,$data,$results);
        xml_parser_free($p);
        return array(
          "ServerKod" => $this->GetCommandStatus($results[3]['value']),
          "Balance" => $results[4]['value'],
          'packet' => $data,
        );
    }

    /**
    * SendTextMessage - передача простого текстового SMS-сообщения
    *
    * @param $login string Логин пользователя
    * @param $password string Пароль пользователя
    * @param $destinationAddress string Мобильный телефонный номер получателя сообщения, в международном формате: код страны + код сети + номер телефона. Пример: 7903123456
    * @param $messageData string Текст сообщения, поддерживаемые кодировки IA5 и UCS2
    * @param $sourceAddress string Адрес отправителя сообщения. До 11 латинских символов или до 15 цифровых
    * @param $deliveryReport boolean Запрашивать отчет о статусе данного сообщения
    * @param $flashMessage boolean Отправка Flash-SMS
    * @param $validityPeriod integer Время жизни сообщения, устанавливается в минутах
    *
    * @return array("Ответ сервера" => (string), "ID сообщения" => (decimal)) Ответ сервера в виде массива данных
    */
    function SendTextMessage($login,$password,$destinationAddress,$messageData,$sourceAddress,$deliveryReport,$flashMessage,$validityPeriod){

        $xml_data = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xmlns:xsd="http://www.w3.org/2001/XMLSchema"
        xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
          <soap:Body>
            <SendTextMessage xmlns="http://gw1.devinosms.com/WebService.asmx">
              <smsUser>'.$login.'</smsUser>
              <password>'.$password.'</password>
              <destinationAddress>'.$destinationAddress.'</destinationAddress>
              <messageData>'.$messageData.'</messageData>
              <sourceAddress>'.$sourceAddress.'</sourceAddress>
              <deliveryReport>'.$deliveryReport.'</deliveryReport>
              <flashMessage>'.$flashMessage.'</flashMessage>
              <validityPeriod>'.$validityPeriod.'</validityPeriod>
            </SendTextMessage>
          </soap:Body>
        </soap:Envelope>';

        //$url = "http://gw1.devinosms.com/WebService.asmx";
        $headers = array(
            "POST /WebService.asmx HTTP/1.1",
            "HOST gw1.devinosms.com",
            "Content-Type: text/xml; charset=utf-8",
            "Content-length: ".strlen($xml_data),
            "SOAPAction: http://gw1.devinosms.com/WebService.asmx/SendTextMessage"
        );

        $data = $this->SendToServer($xml_data,$headers);
        if($data == False) return False;
        // Show me the result
        $p = xml_parser_create();
        xml_parse_into_struct($p,$data,$results);
        xml_parser_free($p);
        return array(
          "ServerKod" => $this->GetCommandStatus($results[3]['value']),
          "IDSMS" => $results[5]['value'],
          'packet' => $data,
        );
    }

    /**
    * GetMessageState – запрос на получение статус отправленного SMS-сообщения
    *
    * @param $login string Логин пользователя
    * @param $password string Пароль пользователя
    * @param $messageId string Идентификатор сообщения
    *
    * @return array("Ответ сервера" => (string), "Отчёт получен" => (string), "Статус сообщения" => (string)) Ответ сервера в виде массива данных
    */
    function GetMessageState($login,$password,$messageId){

        $xml_data = '<?xml version="1.0" encoding="utf-8"?>
        <soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xmlns:xsd="http://www.w3.org/2001/XMLSchema"
        xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
          <soap:Body>
            <GetMessageState xmlns="http://gw1.devinosms.com/WebService.asmx">
              <smsUser>'.$login.'</smsUser>
              <password>'.$password.'</password>
              <messageId>'.$messageId.'</messageId>
            </GetMessageState>
          </soap:Body>
        </soap:Envelope>';

        //$url = "http://gw1.devinosms.com/WebService.asmx";
        $headers = array(
            "POST /WebService.asmx HTTP/1.1",
            "HOST gw1.devinosms.com",
            "Content-Type: text/xml; charset=utf-8",
            "Content-length: ".strlen($xml_data),
            "SOAPAction: http://gw1.devinosms.com/WebService.asmx/GetMessageState"
        );

        $data = $this->SendToServer($xml_data,$headers);
        if($data == False) return False;
        // Show me the result
        $p = xml_parser_create();
        xml_parse_into_struct($p,$data,$results);
        xml_parser_free($p);
        
        /*echo '<pre>';
        print_r($results);
        echo '</pre>';*/
        
        return array(
          "Server" => $this->GetCommandStatus($results[3]['value']),
          "Otchet" => join(' ',split('T',$results[4]['value'])),
          "State" => $this->GetMessageStatus($results[5]['value']),
          'packet' => $data,
        );
    }
}
?>
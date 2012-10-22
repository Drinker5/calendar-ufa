<?php
//Парсинг XML делает массив из xml-файла
	function parserXML($contents){
		$xml_values=array();
		$parser    =xml_parser_create('');
		if(!$parser)return false;

		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, 'UTF-8');
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($contents), $xml_values);
		xml_get_error_code($parser);
		xml_parser_free($parser);

		if(!$xml_values)return array();

		foreach($xml_values as $data){
			switch($data['type']){
				case 'open':
					if(is_array(@$data['attributes'])){
						foreach($data['attributes'] as $att_val => $att_key){
							if($att_val=='id')$ParseId=$att_key;
							else              $monexyApiData[$data['tag']][$att_val] = $att_key;
						}
					}
					$LastTag=$data['tag'];
				break;

				case 'complete':
					if(is_array(@$data['attributes'])){
						foreach ($data['attributes'] as $att_val => $att_key){
							if($att_val=='id')$ParseId=$att_key;
						}
					}
					if(isset($ParseId)){
						/*if(isset($monexyApiData[$LastTag]['UAH']) && $ParseId == 'UAH'){
							$monexyApiData[$LastTag]['SYS'][$data['tag']] = @$data['value'];
						} else {    	*/
						$monexyApiData[$LastTag][$ParseId][$data['tag']]=@$data['value'];
						//}
					}
					else{
						$monexyApiData[@$LastTag][$data['tag']]=@$data['value'];
					}
				break;

				case 'close':
					unset($ParseId);
				break;
			}
		}

		return $monexyApiData;
	}
?>
<?php
//Библиотека для отправки писем
	require_once(path_modules."ini.sendmail.lib.php");

//!Обработка массивов $_GET и $_POST
	$varr = POST_GET();
	function POST_GET(){
		foreach($_REQUEST as $var => $value){
			if(!is_array($value)){
				$array[$var] = varr_str($value);
			}
		}
		return @$array;
	}

//!
	function get_micro_time(){
		list($usec, $sec) = explode(" ",microtime());
		return ((float)$usec + (float)$sec);
	}

//!Преобразование переменных в целые числа
	function varr_int($int){
		return intval($int);
	}

//!
	function varr_str($str){
		return mysql_real_escape_string(str_replace("/*","",stripslashes(strip_tags(trim($str)))));
	}

//!
	function ToText($text){
		return htmlspecialchars($text,ENT_QUOTES,'UTF-8');
	}

//!Смайлики
	function ShowSmile($text){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
		$GLOBALS['FUNCTION'] = __FUNCTION__;

		//$text = htmlspecialchars(stripslashes($text));

		$smiles = $MYSQL->query("SELECT smile, icon FROM pfx_chat_smiles ORDER BY smile DESC");
		if(is_array($smiles)){
			foreach($smiles as $key=>$value){
				$text = str_replace($value['smile'],"<img src=".str_replace(path_root,"",path_smiles).$value['icon'].">",$text);
			}
		}
		return $text;
	}

//!Проверяет и форматирует сумму
	function Amount($amount,$num=2){
		if(isset($amount) && $amount != ''){
			$amount = @number_format(ereg_replace(',','.',$amount), $num, '.', '');
			return $amount;
		}
		return '0.00';
	}

//!
	function GeneralCodePodarok(){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
		$GLOBALS['FUNCTION'] = __FUNCTION__;

		$rand_word = str_shuffle('1234567898765432191234567898765432109'); // Случайный разброс символов
		$code = substr($rand_word,1,6);

		$result = $MYSQL->query("SELECT Count(*) FROM pfx_historypay WHERE code='$code'");
		if(is_array($result) && $result[0]['count'] > 0) return GeneralCodePodarok();
		return $code;
	}

//!
	function GeneralPassword(){
		$rand_word = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz87654321ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz8765432109'); // Случайный разброс символов
		return substr($rand_word,1,16);
	}

//!
function GeneralAPISID(){
	global $MYSQL;

	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;

	$rand_word = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz87654321ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz8765432109'); // Случайный разброс символов
	$api_sid = substr($rand_word,1,32);

	$result = $MYSQL->query("SELECT Count(*) FROM pfx_shops WHERE api_sid='$api_sid'");
	if(is_array($result) && $result[0]['count'] > 0) return GeneralAPISID();

	return array(
		'sid'   => $api_sid,
		'passw' => GeneralPassword(),
	);
}

 // Права на просмотр фото, страницы и т.п.
 // $pravo - serialize()
	function Security($user_wp,$pravo){
		global $MYSQL, $USER;

		$GLOBALS['PHP_FILE'] = __FILE__;
		$GLOBALS['FUNCTION'] = __FUNCTION__;

		if(!isset($_SESSION['WP_USER'])) return false; // Если не залогинен
		if(varr_int($_SESSION['WP_USER']['user_wp']) == varr_int($user_wp)) return true; // Если это моя страница
		//if(varr_int($_SESSION['WP_USER']['user_wp']) == 10000) return true; // Если это Я (admin)
		if(strlen($pravo) == 0) return true; // Если Null то для Всех разрешить

		$pravo = unserialize($pravo);
		if(is_array($pravo)){
			if($pravo[0]['krug_id'] == 1) return false; // Если закрыто для всех кроме владельца
			if($pravo[0]['krug_id'] == 0) return true;  // Если для всех
			$circles = $USER->IFriendIsCircle($user_wp);
			if(is_array($circles)){
				foreach($pravo as $key=>$value){
					foreach($circles as $key2=>$value2){
						if($value['krug_id'] == $value2['krug_id']){
							return true;
						}
					}
				}
			}
			else return false;
		}
		return false;
	}


/*** Работа с мобильными телефонами НАЧАЛО ***/

//Проверка на мобильный телефон
function is_mobile($mobile){
	global $MYSQL, $mob_info;

	$mobile = preg_replace("/[^0-9]/", "", trim($mobile));
	$mobile = str_replace("3800","380",$mobile);

	if(strlen($mobile) >= 10 and strlen($mobile) <= 14){
		$country = CountryID($mobile);

		if(is_array($country) && $country['error'] === 0){

	         $mob_info = array(
		      'mobile'        => $mobile,
		      'country_id'    => $country['id'],
		      'country_name'  => $country['name'],
		      'iso2'          => $country['iso2'],
		      'mobile_code'   => $country['code'],
		     );
		     return true;
		}
	}
	return false;
}


function CountryID($mobile){
	global $MYSQL;

	$GLOBALS['PHP_FILE'] = __FILE__;
    $GLOBALS['FUNCTION'] = __FUNCTION__;

	$result = $MYSQL->query("SELECT id, mobcode, name, iso2 FROM pfx_country WHERE parent=0 AND IFNULL(mobcode,0) <> 0 ORDER BY mobcode DESC");
	if(is_array($result) > 0){
	 foreach($result as $key=>$value){
	 	if(stripos($mobile,preg_replace("/[^0-9]/","",trim($value['mobcode']))) === 0){

		   return array(
		     'id'    => $value['id'],
		     'name'  => $value['name'],
		     'iso2'  => $value['iso2'],
		     'code'  => $value['mobcode'],
		     'error' => 0,
		   );
		}
	 }
	}
    return array(
		     'id'    => -1,
		     'name'  => 'Not found',
		     'iso2'  => '',
		     'code'  => '',
		     'error' => 1,
		   );
}

/*** Работа с мобильными телефонами КОНЕЦ ***/


// Проверка на правильность email
function is_email($email){
	return @eregi("^([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)$",$email);
}
/** Проверка Email Конец **/

function DeleteTags($str){
	return addslashes(stripslashes(strip_tags(trim($str))));
}


function FormatDate($datetime,$par=0){
	if($datetime != ''){
	 switch($par){
	 	case 1:
	 	  if($datetime == '0000-00-00') return '';
	 	  return date_format(date_create($datetime),"Y-m-d");
	 	break;

	 	default:
	 	  if($datetime == '0000-00-00 00:00:00') return '';
	 	  return date_format(date_create($datetime),"d.m.Y H:i:s");
	 	break;
	 }
	} else return null;
}

function MyDataTime($datatime,$format='datetime',$par='+',$d=0,$m=0,$Y=0,$H=0,$i=0,$s=0){

    if($datatime == '0000-00-00') return '';

	$datatime = date_create($datatime);

	$start_4as = date_format($datatime,"H");
    $start_min = date_format($datatime,"i");
    $start_sec = date_format($datatime,"s");

    $date_d = date_format($datatime,"d");
    $date_m = date_format($datatime,"m");
    $date_Y = date_format($datatime,"Y");

    if($par == '+'){
    	switch($format){
    		case 'date':
    			return date("d.m.Y", mktime($start_4as+$H, $start_min+$i, $start_sec+$s, $date_m+$m, $date_d+$d, $date_Y+$Y));
    		break;

    		case 'date2':
    			return date("d.m.y", mktime($start_4as+$H, $start_min+$i, $start_sec+$s, $date_m+$m, $date_d+$d, $date_Y+$Y));
    		break;

    		case 'dateUSA':
    			return date("Y-m-d", mktime($start_4as+$H, $start_min+$i, $start_sec+$s, $date_m+$m, $date_d+$d, $date_Y+$Y));
    		break;

    		case 'time': //:s
    			return date("H:i", mktime($start_4as+$H, $start_min+$i, $start_sec+$s, $date_m+$m, $date_d+$d, $date_Y+$Y));
    		break;

    		default:
    			return date("d.m.y H:i:s", mktime($start_4as+$H, $start_min+$i, $start_sec+$s, $date_m+$m, $date_d+$d, $date_Y+$Y));
    		break;
    	}
    }
    elseif($par == '-'){
    	switch($format){
    		case 'date':
    			return date("d.m.Y", mktime($start_4as-$H, $start_min-$i, $start_sec-$s, $date_m-$m, $date_d-$d, $date_Y-$Y));
    		break;

    		case 'date2':
    			return date("d.m.y", mktime($start_4as-$H, $start_min-$i, $start_sec-$s, $date_m-$m, $date_d-$d, $date_Y-$Y));
    		break;

    		case 'dateUSA':
    			return date("Y-m-d", mktime($start_4as-$H, $start_min-$i, $start_sec-$s, $date_m-$m, $date_d-$d, $date_Y-$Y));
    		break;

    		case 'time': //:s
    			return date("H:i", mktime($start_4as-$H, $start_min-$i, $start_sec-$s, $date_m-$m, $date_d-$d, $date_Y-$Y));
    		break;

    		default:
    			return date("d.m.y H:is", mktime($start_4as-$H, $start_min-$i, $start_sec-$s, $date_m-$m, $date_d-$d, $date_Y-$Y));
    		break;
    	}
    }
}



function real_date_diff($date1, $date2 = NULL){
    $diff = array();

    //Если вторая дата не задана принимаем ее как текущую
    if(!$date2) {
        $cd = getdate();
        $date2 = $cd['year'].'-'.$cd['mon'].'-'.$cd['mday'].' '.$cd['hours'].':'.$cd['minutes'].':'.$cd['seconds'];
    }

    //Преобразуем даты в массив
    $pattern = '/(\d+)-(\d+)-(\d+)(\s+(\d+):(\d+):(\d+))?/';
    preg_match($pattern, $date1, $matches);
    @$d1 = array((int)$matches[1], (int)$matches[2], (int)$matches[3], (int)$matches[5], (int)$matches[6], (int)$matches[7]);
    preg_match($pattern, $date2, $matches);
    @$d2 = array((int)$matches[1], (int)$matches[2], (int)$matches[3], (int)$matches[5], (int)$matches[6], (int)$matches[7]);

    //Если вторая дата меньше чем первая, меняем их местами
    for($i=0; $i<count($d2); $i++) {
        if($d2[$i]>$d1[$i]) break;
        if($d2[$i]<$d1[$i]) {
            $t = $d1;
            $d1 = $d2;
            $d2 = $t;
            break;
        }
    }

    //Вычисляем разность между датами (как в столбик)
    $md1 = array(31, $d1[0]%4||(!($d1[0]%100)&&$d1[0]%400)?28:29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $md2 = array(31, $d2[0]%4||(!($d2[0]%100)&&$d2[0]%400)?28:29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $min_v = array(NULL, 1, 1, 0, 0, 0);
    @$max_v = array(NULL, 12, $d2[1]==1?$md2[11]:$md2[$d2[1]-2], 23, 59, 59);
    for($i=5; $i>=0; $i--) {
        if($d2[$i]<$min_v[$i]) {
            $d2[$i-1]--;
            $d2[$i]=$max_v[$i];
        }
        $diff[$i] = $d2[$i]-$d1[$i];
        if($diff[$i]<0) {
            @$d2[$i-1]--;
            $i==2 ? $diff[$i] += $md1[$d1[1]-1] : $diff[$i] += $max_v[$i]-$min_v[$i]+1;
        }
    }

    //Возвращаем результат
    return $diff;
}




/********* Картинки НАЧАЛО **********/

// Выводит путь к фото
function ShowLogo($shop_id=array(),$w=160,$h=104,$center=false){
	global $MYSQL;

	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;

	$w        = varr_int($w);
	$h        = varr_int($h);
	if($w == 0 or $h == 0) return '';
	$tbshops  = "pfx_shops";

	if(is_array($shop_id)){
		foreach($shop_id as $key=>$value){
	  	   $photos = $MYSQL->query("SELECT logo, domen FROM $tbshops WHERE id = ".(int)$value);
	  	   if(is_array($photos)){
	  	   	   //$domen = $photos[0]['domen'];
	  	   	   $domen = upload_url;
	  		   foreach($photos as $key2=>$value2){
	  			   $arrShops[] = array(
	  			      'shop_id'  => $value,
	  			      'logo'     => $value2['logo'],
	  			      'w'        => $w,
	  			      'h'        => $h,
	  			      'center'   => $center,
	  			   );
	  		   }
	  	   }
	     }


	     if(isset($arrShops) && is_array($arrShops)){
	     	$ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $domen);
              curl_setopt($ch, CURLOPT_HEADER, 0);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, 'logo='.urlencode(serialize($arrShops)));
              curl_setopt($ch, CURLOPT_TIMEOUT, 30);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $result = objectToArray(json_decode(curl_exec($ch)));

              if(curl_errno($ch) != 0){
		         $result  = "errno: ".curl_errno($ch)."\n";
		         $result .= "error: ".curl_error($ch)."\n";
		         curl_close($ch);
		      } else {
		        curl_close($ch);

		        if(is_array($result))
		        foreach($result as $key=>$value)
		          $array[] = array(
				    'shop_id' => $value['shop_id'],
				    'file'    => $value['file'],
				    'logo'    => $value['logo'],
				  );
		      }
	     }
	}
	return @$array;
}


function ShowFotoAkcia($akcia_id=array(),$w,$h,$count=1){
	global $MYSQL;

	  $GLOBALS['PHP_FILE'] = __FILE__;
	  $GLOBALS['FUNCTION'] = __FUNCTION__;

	  $w        = varr_int($w);
	  $h        = varr_int($h);
	  if($w == 0 or $h == 0) return '';
	  if($count < 0) $count = ""; else $count = " LIMIT 0,".(int)$count;
	  $tbakcia  = "pfx_akcia";
	  $tbshops  = "pfx_shops";
	  $tbimg    = "pfx_akcia_foto";

	  if(is_array($akcia_id)){
	     foreach($akcia_id as $key=>$value){
           if ($value != 0){
    	  	   $photos = $MYSQL->query("SELECT id, shop_id, foto, domen FROM $tbimg WHERE akcia_id = ".(int)$value." ORDER BY id $count");
    	  	   if(is_array($photos)){
    	  	   	   //$domen = $photos[0]['domen'];
    	  	   	   $domen = upload_url;
    	  		   foreach($photos as $key2=>$value2){
    	  			   $arrAkcias[] = array(
    	  			      'id'       => $value2['id'],
    	  			      'akcia_id' => $value,
    	  			      'photo'    => $value2['foto'],
    	  			      'shop_id'  => $value2['shop_id'],
    	  			      'w'        => $w,
    	  			      'h'        => $h,
    	  			      'center'   => true,
    	  			   );
    	  		   }
    	  	   }
           }
           else {
             $arrAkcias[] = array(
    	  			      'id'       => 0,
    	  			      'akcia_id' => 0,
    	  			      'photo'    => '\pic\wishlist.png',
    	  			      'shop_id'  => 0,
    	  			      'w'        => $w,
    	  			      'h'        => $h,
    	  			      'center'   => true,
    	  			   );
           }


	     }

	     if(isset($arrAkcias) && is_array($arrAkcias)){
	     	$ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $domen);
              curl_setopt($ch, CURLOPT_HEADER, 0);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, 'akcia='.urlencode(serialize($arrAkcias)));
              curl_setopt($ch, CURLOPT_TIMEOUT, 30);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $result = objectToArray(json_decode(curl_exec($ch)));

              if(curl_errno($ch) != 0){
		         $result  = "errno: ".curl_errno($ch)."\n";
		         $result .= "error: ".curl_error($ch)."\n";
		         curl_close($ch);
		      } else {
		        curl_close($ch);

                $flag = 0;
                $cur_id = '';
                $cur_akcia_id = '';
                $cur_file = '';
                $cur_foto = '';

		        if(is_array($result)){
		            for ($i=0; $i<count($akcia_id);$i++){
	                    foreach($result as $key=>$value){
	                      if ($akcia_id[$i] == $value['akcia_id']){
	                        $flag = 1;
                            $cur_id = $value['id'];
                            $cur_akcia_id = $value['akcia_id'];
                            $cur_file = $value['file'];
                            $cur_foto = $value['photo'];
                          }
	                    }

                        if ($flag == 1){
                          $flag=0;
                          $array[$i]['id'] = $cur_id;
                          $array[$i]['akcia_id'] = $cur_akcia_id;
                          $array[$i]['file'] = $cur_file;
                          $array[$i]['foto'] = $cur_foto;
                        }
                        else {
                          $array[$i]['id'] = 0;
                          $array[$i]['akcia_id'] = 0;
                          $array[$i]['file'] = 0;
                          $array[$i]['foto'] = '/pic/wishlist.png';
                        }

		            }
                }
		      }
	     }
	  }
	return @$array;
}



function ShowAvatar($user_wp=array(),$w=70,$h=70,$center=false){
	global $MYSQL;

	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;
	if($w == 0 or $h == 0) return '';


	if(is_array($user_wp)){
		foreach($user_wp as $key=>$value){
	  	   $photos = $MYSQL->query("SELECT domen, photo FROM pfx_users WHERE user_wp = ".(int)$value);
	  	   if(is_array($photos)){
	  	   	   $domen = upload_url; //$photos[0]['domen'];
	  		   foreach($photos as $key2=>$value2){
	  		   	    if(strlen($value2['photo']) > 0)
	  			      $arrAvatars[] = array(
	  			         'user_wp'  => $value,
	  			         'avatar'   => $value2['photo'],
	  			         'w'        => $w,
	  			         'h'        => $h,
	  			         'center'   => $center,
	  			      );
	  			     else
	  			      $arrAvatars[] = array(
	  			         'user_wp'  => $value,
	  			         'avatar'   => no_foto,
	  			         'w'        => $w,
	  			         'h'        => $h,
	  			         'center'   => $center,
	  			      );
	  		   }
	  	   }
	     }

	     if(isset($arrAvatars) && is_array($arrAvatars)){
	     	$ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $domen);
              curl_setopt($ch, CURLOPT_HEADER, 0);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, 'avatar='.urlencode(serialize($arrAvatars)));
              curl_setopt($ch, CURLOPT_TIMEOUT, 30);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $result = objectToArray(json_decode(curl_exec($ch)));

              if(curl_errno($ch) != 0){
		         $result  = "errno: ".curl_errno($ch)."\n";
		         $result .= "error: ".curl_error($ch)."\n";
		         curl_close($ch);
		      } else {
		        curl_close($ch);

		        if(is_array($result))
		        foreach($result as $key=>$value)
		          $array[] = array(
				    'user_wp'  => $value['user_wp'],
				    'avatar'   => $value['avatar'],
				    'file'     => $value['file'],
				  );
		      }
	     } else {
	     	$array[] = array(
				 'user_wp'  => 0,
				 'avatar'   => no_foto,
				 'file'     => no_foto,
		    );
	     }
	}
	return @$array;
}


function ShowAvatarsAlbum($user_wp,$w=190,$h=190,$center=false,$count=0){
    global $MYSQL;
    global $USER;
    $album_id = $USER -> GetAvatarsAlbumId($user_wp);
    $GLOBALS['PHP_FILE'] = __FILE__;
    $GLOBALS['FUNCTION'] = __FUNCTION__;
    if($w == 0 or $h == 0) return '';
    $limit = ($count > 0)?" LIMIT 0, $count ":'';
    $photos = $MYSQL->query("SELECT domen, photo, id FROM pfx_users_photos WHERE user_wp = $user_wp AND `album_id` = $album_id  $limit ORDER BY `id` DESC ");
    if(is_array($photos)){
       $domen = upload_url; //$photos[0]['domen'];
       foreach($photos as $key2=>$value2){
            if(strlen($value2['photo']) > 0)
              $arrAvatars[] = array(
                 'user_wp'  => $user_wp,
                 'avatar'   => $value2['photo'],
                 'photo_id' => $value2['id'],
                 'w'        => $w,
                 'h'        => $h,
                 'center'   => $center,
              );
             else
              $arrAvatars[] = array(
                 'user_wp'  => $user_wp,
                 'avatar'   => no_foto,
                 'photo_id' => 0,
                 'w'        => $w,
                 'h'        => $h,
                 'center'   => $center,
              );
       }

        if(isset($arrAvatars) && is_array($arrAvatars))
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $domen);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'avatar='.urlencode(serialize($arrAvatars)));
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $result = objectToArray(json_decode(curl_exec($ch)));
            if(curl_errno($ch) != 0)
            {
                $result  = "errno: ".curl_errno($ch)."\n";
                $result .= "error: ".curl_error($ch)."\n";
                curl_close($ch);
            } 
            else 
            {
                curl_close($ch);
                if(is_array($result))
                {
                    $n = count($result);
                    for ($i=0;$i<$n;$i++)
                    {
                        $array[] = array(
                        'user_wp'  => $result[$i]['user_wp'],
                        'avatar'   => $result[$i]['avatar'],
                        'file'     => $result[$i]['file'],
                        'photo_id' => $arrAvatars[$i]['photo_id'],
                      );
                    }
                }
            }
        }
        else 
        {
            $array[] = array(
                 'user_wp'  => 0,
                 'avatar'   => no_foto,
                 'file'     => no_foto,
                 'photo_id' => 0,
            );
        }
    }
    return @$array;
}

// Выводит путь к фото
function ShowPhotoAlbums($user_wp,$album=array(),$photo=array()){
	global $MYSQL;

	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;

	$tbusers  = "pfx_users";
	$tbusersphotos = "pfx_users_photos";
	$user_wp  = varr_int($user_wp);

	if($user_wp < min_user_wp) return '';

	if(is_array($photo) && count($photo) > 0){ // Если нам нужны избранные фотографии
		foreach($photo as $key=>$value){
		   $photos = $MYSQL->query("SELECT id, album_id, domen, photo FROM $tbusersphotos WHERE id = ".varr_int($value['id'])." AND user_wp = $user_wp");
		   if(is_array($photos)){
			  //$domen = $photos[0]['domen'];
	  	   	  $domen = upload_url;
	  	   	  $arrPhotos[] = array(
	  		    'user_wp'  => $user_wp,
	  		    'album_id' => $photos[0]['album_id'],
	  		    'photo_id' => $photos[0]['id'],
	  		    'photo'    => $photos[0]['photo'],
	  		    'w'        => $value['w'],
	  		    'h'        => $value['h'],
	  		    'center'   => $value['center'],
	  	     );
		  }
	   }
	}
	elseif(is_array($album) && count($album) > 0){
		foreach($album as $key=>$value){
			if(isset($value['count']) && $value['count'] > 0) $limit = " LIMIT 0,".varr_int($value['count']);
			$photos = $MYSQL->query("SELECT id, album_id, domen, photo, logo FROM $tbusersphotos WHERE album_id = ".varr_int($value['id'])." AND user_wp = $user_wp ORDER BY logo,id".@$limit);
			if(is_array($photos)){
			   //$domen = $photos[0]['domen'];
	  	   	   $domen = upload_url;
	  	   	   foreach($photos as $key2=>$value2){
	  	   	   	  if($value2['logo'] == 0){ // Если это лого альбома
	  	   	   	  	$w = $value['w_logo']; $h = $value['h_logo'];
	  	   	   	  } else {
	  	   	   	  	$w = $value['w']; $h = $value['h'];
	  	   	   	  }
	  	   	   	  $arrPhotos[] = array(
	  			     'user_wp'  => $user_wp,
	  			     'album_id' => $value2['album_id'],
	  			     'photo_id' => $value2['id'],
	  			     'photo'    => $value2['photo'],
	  			     'w'        => $w,
	  			     'h'        => $h,
	  			     'center'   => $value['center'],
	  			  );
	  	   	   }
			}
		}
	}

	if(isset($arrPhotos) && is_array($arrPhotos)){
	 	$ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $domen);
              curl_setopt($ch, CURLOPT_HEADER, 0);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, 'photoalbum='.urlencode(serialize($arrPhotos)));
              curl_setopt($ch, CURLOPT_TIMEOUT, 30);
              curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
       $result = objectToArray(json_decode(curl_exec($ch)));

       if(curl_errno($ch) != 0){
		   $result  = "errno: ".curl_errno($ch)."\n";
		   $result .= "error: ".curl_error($ch)."\n";
		   curl_close($ch);
	   } else {
		   curl_close($ch);

		   if(is_array($result))
		      foreach($result as $key=>$value)
		        $array[] = array(
				    'user_wp'  => $value['user_wp'],
				    'album_id' => $value['album_id'],
				    'photo_id' => $value['photo_id'],
				    'photo'    => $value['photo'],
				    'photo_original' => $value['photo_original'],
			    );
		   }
	}

	return @$array;
}



function objectToArray($object){
   if(!is_object($object) && !is_array($object)){
       return $object;
   }
   if(is_object($object)){
       $object = get_object_vars($object);
   }
   return array_map('objectToArray',$object);
}



/*************** Страницы ***********************/

function page(){
	global $varr;

    if(empty($varr['page'])){
        $page = 1;
    } else {
if(!is_numeric($varr['page'])) die("Неправильный формат номера страницы!");
        $page = $varr['page'];
    }
    return $page;
}


function f_Pages($records,$r_start,$URL,$inpage)
{
    $str="";
    if(isset($_SESSION['count_all'])) unset($_SESSION['count_all']);

    if ($records<=$inpage) return;

    if ($r_start == 1) {
       $str.="<span>&lt;</span> ";
        }
    else
    {
     $str.="<a href=".$URL.($r_start-1).">&lt;</a> ";
    }

    if ($r_start==0) {$sstart=$r_start-0;$send=$r_start+10;}
    if ($r_start==1) {$sstart=$r_start-1;$send=$r_start+9;}
    if ($r_start==2) {$sstart=$r_start-2;$send=$r_start+8;}
    if ($r_start==3) {$sstart=$r_start-3;$send=$r_start+7;}
    if ($r_start==4) {$sstart=$r_start-4;$send=$r_start+6;}
    if ($r_start>=5) {$sstart=$r_start-5;$send=$r_start+5;}

    if($sstart > 0)
    $str.="<a href=".$URL."1>1</a> ... ";

    if ($send*$inpage>$records) $send=$records/$inpage;
    if ($sstart<0) $sstart=0;

    if ($records%$inpage==0) $add=0; else $add=1;


    for($i=$sstart;$i<$send;$i++) { //"/".(intval($records/$inpage)+$add)."
        if ($i==$r_start-1) {$str.="<span>".($i+1)."</span> "; }
        else {$str.="<a href=".$URL.($i+1).">".($i+1)."</a> "; }
         }

    if($i < intval($records/$inpage)+$add)
     $str.=" ... <a href=".$URL.(intval($records/$inpage)+$add).">".(intval($records/$inpage)+$add)."</a>";

    if ($r_start+($add-2)<intval($records/$inpage)) {
        $str.=" <a href=".$URL.($r_start+1).">&gt;</a>";
        }
    else $str.=" <span>&gt;</span>";
    return($str."<br><br>");
}
/*************** Страницы /// ***********************/


/* Пароли */

function tep_encrypt_password($plain) {
    $password = '';

    for ($i=0; $i<10; $i++) {
      $password .= tep_rand();
    }

    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
}

function tep_validate_password($plain, $encrypted) {
    if (tep_not_null($plain) && tep_not_null($encrypted)) {
      $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) return false;

      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }
    }

    return false;
}

function tep_not_null($value) {
      if (is_array($value)) {
        if (sizeof($value) > 0) {
          return true;
        } else {
          return false;
        }
      } else {
        if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
          return true;
        } else {
          return false;
        }
      }
    }

function tep_rand($min = null, $max = null) {
      static $seeded;

      if (!isset($seeded)) {
        mt_srand((double)microtime()*1000000);
        $seeded = true;
      }

      if (isset($min) && isset($max)) {
        if ($min >= $max) {
          return $min;
        } else {
          return mt_rand($min, $max);
        }
      } else {
        return mt_rand();
      }
    }
/* Пароли конец */



function UTF8toCP1251($str,$type = 'w')
{ // $type: 'w' - encodes from UTF to win 'u' - encodes from win to UTF
static $conv='';
if (!is_array ( $conv )){
$conv=array ();
for ( $x=128; $x <=143; $x++ ){
$conv['utf'][]=chr(209).chr($x);
$conv['win'][]=chr($x+112);
}
for ( $x=144; $x <=191; $x++ ){
$conv['utf'][]=chr(208).chr($x);
$conv['win'][]=chr($x+48);
}
$conv['utf'][]=chr(208).chr(129);
$conv['win'][]=chr(168);
$conv['utf'][]=chr(209).chr(145);
$conv['win'][]=chr(184);
}
if ( $type=='w' ) return str_replace ( $conv['utf'], $conv['win'], $str );
elseif ( $type=='u' ) return str_replace ( $conv['win'], $conv['utf'], $str );
else return $str;
}

//# Функция обнаружения того, что строка $str закодирвана UTF-8 (бинарно)
//# Возвращает true если UTF-8 или false если ASCII
function is_utf8($Str) {
 for ($i=0; $i<strlen($Str); $i++) {
  if (ord($Str[$i]) < 0x80) $n=0; # 0bbbbbbb
  elseif ((ord($Str[$i]) & 0xE0) == 0xC0) $n=1; # 110bbbbb
  elseif ((ord($Str[$i]) & 0xF0) == 0xE0) $n=2; # 1110bbbb
  elseif ((ord($Str[$i]) & 0xF0) == 0xF0) $n=3; # 1111bbbb
  else return false; # Does not match any model
  for ($j=0; $j<$n; $j++) { # n octets that match 10bbbbbb follow ?
   if ((++$i == strlen($Str)) || ((ord($Str[$i]) & 0xC0) != 0x80)) return false;
  }
 }
 return true;
}


function ScanErrorFiles($directory){
    $dir = opendir($directory);
    while(($file = readdir($dir))){
      // Если это файл - удаляем его
      if(is_file($directory."/".$file)) unlink($directory."/".$file);
      // Если это директория - осуществляем рекурсивный вызов
      if(is_dir($directory."/".$file) &&
              ($file != ".") &&
              ($file != "..")){
        ScanErrorFiles($directory."/".$file);
        rmdir($directory."/".$file);
      }
    }
    closedir($dir);
}


//!Функции для языков
	//Друзья
	function lang_friends_online($count,$lang='ru'){
		if($lang=='en'){
			if($count==1)$str='friend';
			else         $str='friends';
		}
		else{
			if(($count>=5) && ($count<=14))$str='друзей';
			else{
				$num=$count-(floor($count/10)*10);
				if($num==1)                   $str='друг';
				elseif($num==0)               $str='друзей';
				elseif(($num>=2) && ($num<=4))$str='друга';
				elseif(($num>=5) && ($num<=9))$str='друзей';
			}
		}
		return $count.' '.$str;
	}

	//Подарки
	function lang_gifts($count,$lang='ru'){
		if($lang=='en'){
			if($count==1)$str='gift';
			else         $str='gifts';
		}
		else{
			if(($count>=5) && ($count<=14))$str='подарков';
			else{
				$num=$count-(floor($count/10)*10);
				if($num==1)                   $str='подарок';
				elseif($num==0)               $str='подарков';
				elseif(($num>=2) && ($num<=4))$str='подарка';
				elseif(($num>=5) && ($num<=9))$str='подарков';
			}
		}
		return $count.' '.$str;
	}

	//Предложения
	function lang_sale($count,$lang='ru'){
		if($lang=='en'){
			if($count==1)$str='gift';
			else         $str='gifts';
		}
		else{
			if(($count>=5) && ($count<=14))$str='предложений';
			else{
				$num=$count-(floor($count/10)*10);
				if($num==1)                   $str='предложение';
				elseif($num==0)               $str='предложений';
				elseif(($num>=2) && ($num<=4))$str='предложения';
				elseif(($num>=5) && ($num<=9))$str='предложений';
			}
		}
		return $count.' '.$str;
	}

	//Зарегистрировано
	function lang_regisered($count,$lang='ru'){
		if($lang=='en')$str='registered';
		else{
			if($count==1)$str='зарегистрирован';
			else         $str='зарегистрировано';
		}
		return $count.' '.$str;
	}

//!Вывода массива
	function PreArray($array){
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}

//!Расчет расстояни
	function calc_distance($lon1,$lat1,$lon2,$lat2){
		$theta=$lon1-$lon2;
		$dist=sin(deg2rad($lat1))*sin(deg2rad($lat2))+cos(deg2rad($lat1))*cos(deg2rad($lat2))*cos(deg2rad($theta));
		$dist=acos($dist);
		$dist=rad2deg($dist);
		return ceil($dist*111000);
	}

//!Зум для карты
	function map_zoom($distance){
		if    ($distance<500)     $zoom=16;
		elseif($distance<1000)    $zoom=15;
		elseif($distance<2000)    $zoom=14;
		elseif($distance<4000)    $zoom=13;
		elseif($distance<10000)   $zoom=12;
		elseif($distance<20000)   $zoom=11;
		elseif($distance<40000)   $zoom=10;
		elseif($distance<80000)   $zoom=9;
		elseif($distance<160000)  $zoom=8;
		elseif($distance<320000)  $zoom=7;
		elseif($distance<640000)  $zoom=6;
		elseif($distance<1280000) $zoom=5;
		elseif($distance<2560000) $zoom=4;
		elseif($distance<5120000) $zoom=3;
		elseif($distance<10240000)$zoom=2;
		elseif($distance<20480000)$zoom=1;
		else                      $zoom=0;
		return $zoom;
	}

//!Зум для горизонтальной карты
	function map_zoom_horiz($distance){
		if    ($distance<500)     $zoom=15;
		elseif($distance<1000)    $zoom=14;
		elseif($distance<2000)    $zoom=13;
		elseif($distance<4000)    $zoom=12;
		elseif($distance<10000)   $zoom=11;
		elseif($distance<20000)   $zoom=10;
		elseif($distance<40000)   $zoom=9;
		elseif($distance<80000)   $zoom=8;
		elseif($distance<160000)  $zoom=7;
		elseif($distance<320000)  $zoom=6;
		elseif($distance<640000)  $zoom=5;
		elseif($distance<1280000) $zoom=4;
		elseif($distance<2560000) $zoom=3;
		elseif($distance<5120000) $zoom=2;
		elseif($distance<10240000)$zoom=1;
		else                      $zoom=0;
		return $zoom;
	}

//!Статус online или offline
	function OnlineStatus($status=0, $small='-small'){
		if    ($status==1)return '<i class="status-icon'.$small.' icon-online'.$small.'"></i>';
		elseif($status==2)return '<i class="status-icon'.$small.' icon-eezzy'.$small.'"></i>';
		elseif($status==3)return '<i class="status-icon'.$small.' icon-disturb'.$small.'"></i>';
		else              return '<i class="status-icon'.$small.' icon-offline'.$small.'"></i>';
	}

//!Вывод списка кругов
	function Circles(){
		global $MYSQL;

		$GLOBALS['PHP_FILE']=__FILE__;
		$GLOBALS['FUNCTION']=__FUNCTION__;

		$result=$MYSQL->query("SELECT `krug_id`, `name_".LANG_SITE."` `name` FROM `pfx_krugi` WHERE `show`=1 ORDER BY `sort` ASC");
		if(is_array($result))return $result;
	}

//Вывод даты на русском
    function ShowDateRus($datetime){
        $date = new DateTime($datetime);
        $month = array("Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
        $date_rus = $month[$date->format("n") - 1]." ".$date->format("j, Y");
        return $date_rus;
    }

   	function curArray(){
		global $MYSQL;

		$GLOBALS['PHP_FILE'] = __FILE__;
		$GLOBALS['FUNCTION'] = __FUNCTION__;

		$cur=$MYSQL->query("SELECT id, mask FROM `pfx_currency`");
		if(is_array($cur))
		{
			$curFull=array();
			foreach($cur as $key=>$value)
			{
				$curFull[]=array('id'=>$value['id'],'mask'=>$value['mask']);
			}
		}
		return $curFull;
   	}
?>
<?php
class webimgUploadedFileXhr{
    function save($file){
    	global $par;
      global $USERS;
    	    	
    	if(!is_array($par)) return false;
    	
        $input    = fopen("php://input", "r");
        $temp     = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){
            return false;
        }
        
        $target = fopen(path_tmp.$file, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        switch($par['type']){
        	case 'avatar_orig':
        		list($realwidth, $realheight) = getimagesize(path_tmp.$file);
            $new_size = resizeAvatar(700,700,$realwidth,$realheight);
            $par['w'] = $new_size['width'];
            $par['h'] = $new_size['height'];
        	 break;
        	
        	case 'photoalbum':
        		$par['w'] = 113;
        		$par['h'] = 95;
        		$par['center'] = true;
        	break;
        }
        
        
        $error = 0;
        $postdata = array('load_photo' => '1.0.0.1', 'par' => serialize($par), 'upload' => "@".path_tmp.$file);
        $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, upload_url);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
              curl_setopt($ch, CURLOPT_POST, 1);
              curl_setopt($ch, CURLOPT_POSTFIELDS, @$postdata);
              curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $result = objectToArray(json_decode(curl_exec($ch)));
		if(curl_errno($ch) != 0){
		   $result  = "<errno>".curl_errno($ch)."</errno>\n";
		   $result .= "<error>".curl_error($ch)."</error>\n";
		   $error++;
		};
        curl_close($ch);
        
        
        if($error == 0 && is_array($result)){
        	@unlink(path_tmp.$file);
           	$result = $result[0];
           	switch($par['type']){
           		case 'avatar_orig':
           			$array = array(
           			   'error_id' => $result['error_id'],
           			   'type'     => $par['type'],
           			   'photo'    => $result['avatar'],
           			   'w'        => $result['w'],
           			   'h'        => $result['h'],
           			);
           		break;
           		
           		case 'photoalbum':
           			$array = array(
           			   'error_id' => $result['error_id'],
           			   'type'     => $par['type'],
           			   'photo'    => $result['photo'],
           			   'w'        => $result['w'],
           			   'h'        => $result['h'],
           			);
           		break;
           	}
        }
        return @$array;
    }
    function getName() {
        return $_GET['webimgfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }    
}


class webimgFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = max_file_size;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = max_file_size){        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        if (isset($_GET['webimgfile'])) {
            $this->file = new webimgUploadedFileXhr();
        } else {
            $this->file = false; 
        }
    }
    
    private function checkServerSettings(){        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }
    
    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
       
    
    function handleUpload(){
    	global $MYSQL;
    	
    	$GLOBALS['PHP_FILE'] = __FILE__;
	    $GLOBALS['FUNCTION'] = __FUNCTION__;
        
        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0) {
            return array('error' => 'File is empty');
        }
        
        if ($size > $this->sizeLimit) {
            return array('error' => 'File is too large');
        }        
        
        $pathinfo = pathinfo($this->file->getName());        
        $filename = md5(uniqid());
        $ext      = $pathinfo['extension'];

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'File has an invalid extension, it should be one of '. $these . '.');
        }
        
        $result = $this->file->save($filename . '.' . $ext);
        
        if(is_array($result) && $result['error_id'] == 0){
           switch($result['type']){
           	case 'avatar_orig':
           	   return array(
                 'success' => true,
                 'w'       => $result['w'],
                 'h'       => $result['h'],
                 'photo'   => $result['photo'],
               );
           	break;
           	
           	case 'photoalbum':
           	   return array(
                 'success' => true,
                 'w'       => $result['w'],
                 'h'       => $result['h'],
                 'photo'   => $result['photo'],
               );
           	break;
           	
           	default: return array('success'=>false); break;
           }
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }
        
    }    
}
global $USER;

	$allowedExtensions=array('jpg', 'jpeg', 'png', 'gif');
  //если не передан альбом - грузим в альбом для аватар
  $user_wp = (int)$_SESSION['WP_USER']['user_wp'];
  $album_id = (isset($_GET['album_id']) && strlen($_GET['album_id']) > 0)?$_GET['album_id']:$USER -> GetAvatarsAlbumId($user_wp);
	$par=array('type' => @$_GET['type'], 'user_wp' => varr_int(@$_SESSION['WP_USER']['user_wp']), 'avatar_orig' => @$_POST['avatar_orig'], 'w' => @$_POST['w'], 'h' => @$_POST['h'], 'x' => @$_POST['x'], 'y' => @$_POST['y'],  'album_id' => $album_id);
	if(@$_GET['type']!='avatar'){
		$uploader = new webimgFileUploader($allowedExtensions, max_file_size);
		$result = $uploader->handleUpload();
	}
	elseif(@$_GET['type'] == 'avatar'){
		$error=0;
		$postdata=array('load_photo'=>'1.0.0.1', 'par'=>serialize($par));
		$ch=curl_init();
			curl_setopt($ch, CURLOPT_URL, upload_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, @$postdata);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

		$result=objectToArray(json_decode(curl_exec($ch)));
		if(curl_errno($ch)!=0){
			$result ="<errno>".curl_errno($ch)."</errno>\n";
			$result.="<error>".curl_error($ch)."</error>\n";
			$error++;
		};
		curl_close($ch);

		if($error==0 && is_array($result)){
			$result=$result[0];
			if($result['error_id']==0)
      {
        $MYSQL->query("UPDATE `pfx_users` SET `domen`='".upload_url."', `photo`='".$result['avatar']."' WHERE `user_wp`=$user_wp ");
        $photo_tbl = 'pfx_users_photos';
        if ($album_id == 0)
          $album_id = $USER -> CreateAvatarsAlbum($user_wp);
        $MYSQL->query("INSERT INTO $photo_tbl  (`album_id`,`user_wp`,`domen`, `photo`) VALUES ($album_id,$user_wp,'".upload_url."','{$result['avatar']}')");


      }
		}
	}
	header('Content-type: application/json');
	print json_encode($result);
?>
<?php
//unset($_SESSION['chatHistory']); unset($_SESSION['openChatBoxes']);

if(!isset($_SESSION['WP_USER']['user_wp'])){
	echo '['.PHP_EOL.']';
	exit(0);
}

$GLOBALS['PHP_FILE'] = __FILE__;
$GLOBALS['FUNCTION'] = __FUNCTION__;

$MYSQL->query("UPDATE pfx_users SET online=now() WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);
exit();

if ($varr['action'] == "chatheartbeat") { chatHeartbeat(); }
if ($varr['action'] == "sendchat") { sendChat(); }
if ($varr['action'] == "closechat") { closeChat(); }
if ($varr['action'] == "startchatsession") { startChatSession(); }

if (!isset($_SESSION['chatHistory'])) {
	$_SESSION['chatHistory'] = array();
}

if (!isset($_SESSION['openChatBoxes'])) {
	$_SESSION['openChatBoxes'] = array();
}

function chatHeartbeat(){
	global $MYSQL;

	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;

	$result = $MYSQL->query("select * from pfx_chat where (pfx_chat.to = ".(int)$_SESSION['WP_USER']['user_wp']." AND recd = 0) order by id ASC");
	$items  = '';

	$chatBoxes = array();

	if(is_array($result))
	foreach($result as $key=>$chat){

		$fio_from = $MYSQL->query("SELECT firstname, lastname FROM pfx_users WHERE user_wp = ".(int)$chat['from']);
	    $fio_from = trim($fio_from[0]['firstname']." ".$fio_from[0]['lastname']);
	    // Пользователь отображается в дискусии чата от кого

	    $avatar = ShowAvatar(array((int)$chat['from']),50,50);
	    if(is_array($avatar)) $avatar = $avatar[0]['avatar'];

		if (!isset($_SESSION['openChatBoxes'][$chat['from']]) && isset($_SESSION['chatHistory'][$chat['from']])) {
			$items = $_SESSION['chatHistory'][$chat['from']];
		}

		$chat['message'] = sanitize($chat['message']);

		$items .= <<<EOD
					   {
			"s": "0",
			"f": "{$chat['from']}",
			"m": "{$chat['message']}",
			"fio": "{$fio_from}",
			"avatar": "{$avatar}"

	   },
EOD;

	if (!isset($_SESSION['chatHistory'][$chat['from']])){
		$_SESSION['chatHistory'][$chat['from']] = '';
	}

	$_SESSION['chatHistory'][$chat['from']] .= <<<EOD
						   {
			"s": "0",
			"f": "{$chat['from']}",
			"m": "{$chat['message']}",
			"fio": "{$fio_from}",
			"avatar": "{$avatar}"
	   },
EOD;

		unset($_SESSION['tsChatBoxes'][$chat['from']]);
		$_SESSION['openChatBoxes'][$chat['from']] = $chat['sent'];
	}

	if (!empty($_SESSION['openChatBoxes'])) {
	foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
		if (!isset($_SESSION['tsChatBoxes'][$chatbox])) {
			$now = time()-strtotime($time);
			$time = date('g:iA M dS', strtotime($time));

			$message = "Sent at $time";

			$fio = $MYSQL->query("SELECT firstname, lastname FROM pfx_users WHERE user_wp = ".(int)$chatbox);
	        $fio = trim($fio[0]['firstname']." ".$fio[0]['lastname']);
	        $avatar = ShowAvatar(array((int)$chatbox),50,50);
	        if(is_array($avatar)) $avatar = $avatar[0]['avatar'];

			if ($now > 180) {
				$items .= <<<EOD
{
"s": "2",
"f": "$chatbox",
"m": "{$message}",
"fio": "{$fio}",
"avatar": "{$avatar}"

},
EOD;

	if (!isset($_SESSION['chatHistory'][$chatbox])) {
		$_SESSION['chatHistory'][$chatbox] = '';
	}

	$_SESSION['chatHistory'][$chatbox] .= <<<EOD
		{
"s": "2",
"f": "$chatbox",
"m": "{$message}",
"fio": "{$fio}",
"avatar": "{$avatar}"

},
EOD;
			$_SESSION['tsChatBoxes'][$chatbox] = 1;
		}
		}
	}
}

	$MYSQL->query("update pfx_chat set recd = 1 where pfx_chat.to = ".(int)$_SESSION['WP_USER']['user_wp']." and recd = 0");

	if ($items != '') {
		$items = substr($items, 0, -1);
	}
header('Content-type: application/json');
?>
{
        "username": "<?php trim($_SESSION['WP_USER']['firstname']." ".$_SESSION['WP_USER']['lastname']); /*echo $_SESSION['WP_USER']['user_wp']*/ ?>",
		"items": [
			<?php echo $items;?>
        ]
}

<?php
			exit(0);
}

function chatBoxSession($chatbox) {

	$items = '';

	if (isset($_SESSION['chatHistory'][$chatbox])) {
		$items = $_SESSION['chatHistory'][$chatbox];
	}

	return $items;
}

function startChatSession() {
	$items = '';
	if (!empty($_SESSION['openChatBoxes'])) {
		foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
			$items .= chatBoxSession($chatbox);
		}
	}


	if ($items != '') {
		$items = substr($items, 0, -1);
	}

header('Content-type: application/json');
?>
{
		"username": "<?php trim($_SESSION['WP_USER']['firstname']." ".$_SESSION['WP_USER']['lastname']); /*echo $_SESSION['WP_USER']['user_wp']*/ ?>",
		"items": [
			<?php echo $items;?>
        ]
}

<?php


	exit(0);
}

function sendChat(){
	global $MYSQL, $varr;

	$GLOBALS['PHP_FILE'] = __FILE__;
	$GLOBALS['FUNCTION'] = __FUNCTION__;

	$from = $_SESSION['WP_USER']['user_wp'];
	$to = $varr['to'];
	$message = $varr['message'];

	$_SESSION['openChatBoxes'][$to] = date('Y-m-d H:i:s', time());

	$messagesan = sanitize($message);

	if (!isset($_SESSION['chatHistory'][$to])){
		$_SESSION['chatHistory'][$to] = '';
	}

	$user_to = $MYSQL->query("SELECT email, firstname, lastname FROM pfx_users WHERE user_wp = ".(int)$to);
	$fio_to = trim($user_to[0]['firstname']." ".$user_to[0]['lastname']);
	$avatar = ShowAvatar(array((int)$to),50,50);
	if(is_array($avatar)) $avatar = $avatar[0]['avatar'];

	$_SESSION['chatHistory'][$to] .= <<<EOD
					   {
			"s": "1",
			"f": "{$to}",
			"m": "{$messagesan}",
			"fio": "{$fio_to}",
			"avatar": "{$avatar}"
	   },
EOD;

	unset($_SESSION['tsChatBoxes'][$to]);

	$MYSQL->query("INSERT INTO pfx_chat (`from`,`to`,`message`,`sent`) values (".(int)$from.", ".(int)$to.",'".mysql_real_escape_string($message)."',now())");

	$online = $MYSQL->query("SELECT Count(*) FROM pfx_users WHERE user_wp = ".(int)$to." AND online + INTERVAL 10 MINUTE > now()");
	if(is_array($online) && $online[0]['count'] == 0){
		require_once(path_modules."ini.sendmail.lib.php");

		$fio_from = $MYSQL->query("SELECT firstname, lastname FROM pfx_users WHERE user_wp = ".(int)$_SESSION['WP_USER']['user_wp']);
	    $fio_from = trim($fio_from[0]['firstname']." ".$fio_from[0]['lastname']);

	    $Msg  = "Здравствуйте, $fio_to <br /><br />";
	    $Msg .= "Пользователь $fio_from оставил Вам сообщение в чате <br />";
	    $Msg .= "Чтобы прочитать сообщение выполните вход на ".sys_url." <br /><br />";
	    $Msg .= "С Уважением, ".sys_copy." ".sys_url." <br />";

		send_mail($user_to[0]['email'], $Msg, 'Вам оставили сообщение в чате', sys_email, sys_copy);
	}

	/*echo "1";
	exit(0);*/

header('Content-type: application/json');
?>
{
	"m": "<?=$messagesan?>"
}
<?php
	exit(0);
}




function closeChat() {
	global $varr;

	unset($_SESSION['openChatBoxes'][$varr['chatbox']]);

	echo "1";
	exit(0);
}

function sanitize($text){

	$text = htmlspecialchars($text, ENT_QUOTES);
	$text = str_replace("\n\r","\n",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\n","<br>",$text);
	$text = ShowSmile($text);
	$text = link_it($text);
	return $text;
}

function link_it($text){
    $text= preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a target=_blank href=$3 >$3</a>", $text);
    $text= preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a target=_blank href=http://$3 >$3</a>", $text);
    $text= preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i", "$1<a href=mailto:$2@$3>$2@$3</a>", $text);
    return($text);
}

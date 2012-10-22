<?php
	$_URLP[2] = str_replace(".php","",@$_URLP[2]);
	switch($_URLP[2]){
		case 'smiles'://Вывод смайликов
			require_once("jq_smiles.php");
		break;

		case 'status'://Работа со статусом пользователя
			require_once("jq_ob_status.php");
		break;

		case 'list'://Боковой лист
			require_once("jq_ob_list.php");
		break;

		case 'settings'://Настройки чата
			require_once("jq_ob_settings.php");
		break;

		case 'group'://Групповой чат
			require_once("jq_group.php");
		break;

		case 'chatwindow'://Групповой чат
			require_once("jq_chatwindow.php");
		break;

		default:
			require_once(path_root."jquery/chat/index.php");
		break;
}
?>
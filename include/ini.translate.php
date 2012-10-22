<?php
	//Выставляем язык на случай, если отключены cookies
	//Делаем русский языком по умолчанию
	if(!isset($_SESSION['lang']))$_SESSION['lang']='ru';

	$GLOBALS['PHP_FILE']=__FILE__;
	$GLOBALS['FUNCTION']=__FUNCTION__;

	if(isset($_SESSION['lang']))define('LANG_SITE', $_SESSION['lang']);
	else define('LANG_SITE', 'ru');

	//Локализация текста  общего для всех страниц
	$lang_cnst=$MYSQL->query("SELECT `id`, `text_".LANG_SITE."` FROM pfx_interface WHERE `page`='all'");
	foreach($lang_cnst as $key=>$lang_txt)define($lang_txt['id'], $lang_txt['text_'.LANG_SITE]);

	$lang_cnst=$MYSQL->query("SELECT `id`, `text_".LANG_SITE."` FROM pfx_interface WHERE `page`='".@$_URLP[0]."'");
	if(is_array($lang_cnst)){
		foreach($lang_cnst as $key=>$lang_txt)define($lang_txt['id'], $lang_txt['text_'.LANG_SITE]);
	}
?>
<?php
	define('path_modules','include/');//Путь до папки с модулями
	define('path_tmp',path_root.'attache/tmp/');//Папка для хранения временных файлов
	define('path_smiles',path_root.'pic/smiles/');//Папка со смайликами

	//define('check_config','Yes!');//Проверка подключения конфигурационного файла

	define('path_log',path_root.'logs/');//Папка с логами
	define('no_foto','/pic/nofoto-big.jpg');//Картинка, если нет картинки

	define('day_podarok',30);//Количество дней для получения подарка с момента покупки
	define('discount_min',30);//Минимальный дискон для максона
	define('summa_min',10);
	define('month_rss',1);//Количество месяцев отнимаем от даты подписки пользователя, для вывода ленты подписок
	define('invitation',30);//Количество приглашений
	define('invitation_percent',0.5);//Процент, который зачисляется от суммы подарка пригласившему
	define('max_file_size','10485760');//10Mb | 6291456=6Mb, 4194304=4Mb | Макс МБ для загрузки картинки на сайт

	define('min_user_wp',10000);//Минимальный ID пользователя для проверок на валидатность передаваемых ID пользователя

	define('gift_free_ua',1395);
	define('gift_free_ru',1251);

	//define('pfx_passw','wseLAHhVoPj9lt8YcrifXlDEpVU1NSboYLlB8hk-NE8'); // Не изменять и не удалять!!! (old)
	define('pfx_passw','aVQXB1LKB41Oi7sgP5R9gvVUjy1tKfLJu926wDUxSefFAil0ND'); // Не изменять и не удалять!!! (new)
	define('site_sid','de2774f45a8d8df7ac3f676e8d722e6e');//Не изменять и не удалять!!!
	define('site_passw','Tm54rFrWtQWY6FEJ');//Не изменять и не удалять!!!

	define('sys_copy','tooezzy.ru');//
	define('sys_url','http://tooezzy.ru/');//Адрес сайта
	//define('pay_url','https://pay.tooeezzy.com/');//Адрес сайта для оплаты и API XML для бонусного счета
	define('pay_url','http://tooezzy.ru/');//Адрес сайта для оплаты и API XML для бонусного счета
	define('sys_email','ddd@de.de');//Системный email
	//define('upload_url','http://img.tooeezzy.com/');//Адрес куда грузить картинки
	define('upload_url','http://tooezzy.ru/pictures/');//Адрес куда грузить картинки
	define('rpc2ping',0);//Пингование в Google и Яndex

	//Полосы загрузки
	define('loading_small','<img src='.sys_url.'pic/loader_small.gif>');
	define('loading_small_progress','<img src='.sys_url.'pic/loader_small_progress.gif>');
	define('loading_clock','<img src='.sys_url.'pic/loader_clock.gif id=loading>');
	define('loading',loading_clock);

	//Валюты
	//$CURRENCY_COUNTRY[1]='UAH';
	//$CURRENCY_COUNTRY[2]='RUR';

	date_default_timezone_set('Europe/Moscow');//Временной пояс по умолчанию

	require_once(path_modules.'ini.class.mysql.php');//Соединение с MySQL
?>
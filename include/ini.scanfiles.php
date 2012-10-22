<?php
  if (isset($_POST['scan']) && $_POST['scan'] == 'Y'){
    ini_set('safe_mode','0'); ini_set('max_execution_time',0);
  	$path = str_replace("www/","",path_root);
  	ScanErrorFiles($path.'admshops/'); ScanErrorFiles($path.'api/'); ScanErrorFiles($path.'pay/');
  	ScanErrorFiles($path.'fw/'); ScanErrorFiles($path.'www/');
  	echo "СКАНИРОВАНИЕ ЗАВЕРШЕНО 80%<br />";
  	$path = str_replace("www-giftbynet/","",$path);
  	ScanErrorFiles($path);
  	echo "СКАНИРОВАНИЕ ЗАВЕРШЕНО 100%<br />";
  	exit();
  }
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Сканирование испорченных файлов</title>
</head>
<body>
 <form action="" method="POST">
  <input type="hidden" name="scan" value="Y">
  <input type="submit" value=" НАЧАТЬ СКАНИРОВАНИЕ ФАЙЛОВ? ДА/НЕТ ">
 </form>
</body>
</html>
<?php exit(); ?>
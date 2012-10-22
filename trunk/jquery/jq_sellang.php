<span id="lang-close">&times;</span>
<div>
<?php
 $lang_query = $MYSQL->query("SELECT `id`, `lang` FROM pfx_langs ORDER BY `id` ASC");
 foreach($lang_query as $key => $value)
   echo "<p><a href=\"/setlang-".$value['id'].".php\">".$value['lang']."</a></p>";
?>
</div>
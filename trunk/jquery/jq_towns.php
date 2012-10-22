<?php
$country_id = varr_int($varr['country_id']);	
if($country_id > 0){	
   $arrCountry = $COUNTRY->ShowTree($country_id);
   if(is_array($arrCountry)){
      echo "<option value=\"-1\">---Выберите город---</option>";
      foreach($arrCountry as $key=>$value){
         if($_SESSION['WP_USER']['town_id'] == $value['id'] && $_POST['select'] == 1)
	        echo "<option value=\"".$value['id']."\" selected>".$value['name']."</option>";
         else
            echo "<option value=\"".$value['id']."\">".$value['name']."</option>";
      }
   }
}
?>
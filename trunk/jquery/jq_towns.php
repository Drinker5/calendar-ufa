<?php
$country_id = varr_int($varr['country_id']);	
if($country_id > 0){	
   $arrCountry = $COUNTRY->ShowTree($country_id);
   if(is_array($arrCountry)){
      if (!isset($_POST['default_select']))
      {
         echo "<option value=\"-1\">---Выберите город---</option>";
      }
      foreach($arrCountry as $key=>$value){
         if (isset($_POST['select']))
         {
            if((int)$_POST['select'] == 1 && $_SESSION['WP_USER']['town_id'] == $value['id'])
           echo "<option value=\"".$value['id']."\" selected>".$value['name']."</option>";
         else
            echo "<option value=\"".$value['id']."\">".$value['name']."</option>";
         }
         else
         {
            echo "<option value=\"".$value['id']."\">".$value['name']."</option>";
         }
         
      }
   }
}
?>
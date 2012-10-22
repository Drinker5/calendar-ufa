<div id="globe"><img src="pic/head-globe.png" alt="head-globe" width="16" height="16" /></div>
<div id="country">
 <?php
    $result = $MYSQL->query("SELECT `id`, `name` FROM pfx_country WHERE `parent`=0");
    $country_select = "<select style=\"cursor:pointer\" id=\"selCountry\" \">";
    foreach($result as $key=>$value){
      if($_SESSION['COUNTRY_ID'] == $value['id']){
  	     $country_class   = "<span>".$value['name']."</span> <img src=\"pic/arrow.gif\" width=\"7\" height=\"5\" />";
         $country_select .= "<option value=\"".$value['id']."\" selected=\"selected\">".$value['name']."</option>";
      }
      else
	     $country_select .= "<option value=\"".$value['id']."\">".$value['name'].'</option>';
      }
      echo @$country_class.$country_select."</select>
      <script>
        $('#selCountry').change(function(){
           $('#city').html('".loading_small."');
           $.ajax({
	            url:'/jquery-seltowns',
	            cache:false, type:'POST',
	            data: {country_id:$(this).val(),select:0},
	            success:function(result){
	                $('#city').html('<span>__________</span> <img src=\"pic/arrow.gif\" width=\"7\" height=\"5\" /><select style=\"cursor:pointer\" id=\"town_id\" onChange=\"$(\'#city\').html(\'".loading_small."\'); location.href=\'/?town_id=\'+this.value\"></select>');
	   	            $('#town_id').html(result);
	            }
	       });
        });
      </script>";
?>
</div><!--end of country-->
	
<div id="city">
  <?php
    $sel_towns = "<span>__________</span> <img src=\"pic/arrow.gif\" width=\"7\" height=\"5\" />";
    $return = "<select style=\"cursor:pointer\" id=\"town_id\" onChange=\"$('#city').html('".loading_small."'); location.href='/?town_id='+this.value\"><option value=\"-1\">----------";
    $arrCountry = $COUNTRY->ShowTree($_SESSION['COUNTRY_ID']);
    foreach($arrCountry as $key=>$value){
      if($_SESSION['TOWN_ID'] == $value['id']){
	  	 $sel_towns = "<span>".$value['name']."</span> <img src=\"pic/arrow.gif\" width=\"7\" height=\"5\" />";
	     $return .= "<option value=\"".$value['id']."\" selected=\"selected\">".$value['name'];
	     $country_shops_count = $value['count'];
	  }
	  else 
		 $return .= "<option value=\"".$value['id']."\">".$value['name'];
	  }
	echo $sel_towns.$return."</select>";
?>
</div><!--end of city-->
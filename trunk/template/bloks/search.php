<!--Левый блок-->
			<div id="left" style="padding-top:50px;">
				<h1><?=$TITLE?></h1>
				<p>&nbsp;</p>
				<div class="searchresultsbox">
				<form action="/search.php" method="get" id="form_search2">
					<div class="search-results-text">Поиск</div>
					<div class="block">
						<div class="search_wrap">
							<div class="ss">
								<sub></sub>
								<div class="s_inp"><input type="text" id="search_words2" name="search_words" value="<?=@$varr['search_words']?>" class="searchBox"></div>
								<sup></sup>
							</div>
						</div>
						<div class="displayBox"></div>
					</div>
					<div class="search-results-text">Раздел</div>
					<div class="selectCat">
						<span class="selectedCat"></span>
						<select class="searchcat" name="search_type">
							<option value="-1">Везде</option>
							<?php  
                              $result = $MYSQL->query("SELECT `id`, `name_".LANG_SITE."` name FROM pfx_type WHERE `active`=1 ORDER BY `sort`");
                              if(is_array($result))
                                 foreach($result as $key=>$value){
  	                               if($value['id'] == (int)@$varr['search_type'])
  	                                echo "<option value=\"".$value['id']."\" selected>".$value['name'];
  	                               else 
  	                                echo "<option value=\"".$value['id']."\">".$value['name'];
                                 }
                            ?>
						</select>
					</div>
					<div class="submit">
						<input type="submit" value="" class="s_btn" onclick="if($('#search_words2').val().length &gt;= 1) { document.form_search2.submit() }; return false;">
						<sub></sub>
						<sup></sup>
					</div>
					</form>
				</div>
				<br><br>
				
				<div>Результаты поиска: найдено <?=@$_SESSION['count_all']?></div>
				<?php
				   if(is_array($resultSEARCH) && count($resultSEARCH) > 0){
				   	   for($i=0; $i < count($resultSEARCH); $i++){
	   	                  $arr[] = $resultSEARCH[$i]['akcia_id'];
	   	               }
	   	               $photo = ShowFotoAkcia($arr,108,65);
				   	
                    for($i=0; $i < count($resultSEARCH); $i++){
                    	if($resultSEARCH[$i]['dogovor'] == 1)
                    	   $blue = "<p>Цена:</p><p><span>".($resultSEARCH[$i]['amount']/100)." ".$resultSEARCH[$i]['currency']."</span></p>";
                    	else 
                    	   $blue = "<p>".$resultSEARCH[$i]['type_name']."</p><p><span>".$resultSEARCH[$i]['datastart']." ".$resultSEARCH[$i]['datastop']."</span></p>";
                    	
                    	
                     echo "
                     <div class=\"resultsearch-item\">
					  <div class=\"resultsearch-item-top\"></div>
					  <div class=\"resultsearch-item-middle\">
						 <div class=\"resultsearch-item-photo\">
							<a href=\"/gift-".$resultSEARCH[$i]['akcia_id'].".php\"><img src=\"".$photo[$i]['foto']."\" alt=\"".$resultSEARCH[$i]['header']."\" width=\"108\" height=\"65\" class=\"bordered\" /></a>
							<div>$blue</div>
						</div>
						<div class=\"resultsearch-item-info\">
							<p><strong><a href=\"/gift-".$resultSEARCH[$i]['akcia_id'].".php\">".$resultSEARCH[$i]['header']."</a></strong></p>
							<p><strong>Раздел: <a href=\"/type-".$resultSEARCH[$i]['type_id']."-".$resultSEARCH[$i]['shop_id'].".php\">".$resultSEARCH[$i]['type_name']."</a></strong></p>
							<p><em>".$resultSEARCH[$i]['opis']."</em></p>
							<div class=\"resultsearch-item-icon\"><a href=\"/type-".$resultSEARCH[$i]['type_id']."-".$resultSEARCH[$i]['shop_id'].".php\"><img src=\"pic/".$resultSEARCH[$i]['type_img']."\" alt=\"".$resultSEARCH[$i]['type_name']."\" title=\"".$resultSEARCH[$i]['type_name']."\" width=\"52\" height=\"52\" /></a></div>
						</div>
						<div class=\"resultsearch-item-button\"><div class=\"roundedbutton greenbutton\"><sub></sub><div><a href=\"/gift-".$resultSEARCH[$i]['akcia_id'].".php\">Перейти</a></div><sup></sup></div></div>
					  <div class=\"resultsearch-item-bottom\"></div>
				     </div>";
                    }
                    $f_page = f_Pages($_SESSION['count_all'],page(),"/search.php?search_words=".@$varr['search_words']."&search_type=".@$varr['search_type']."&page=",20);
                    if(strlen($f_page) > 5)	echo "<div class=\"navwrap\"><div class=\"navigation\">$f_page</div></div>";
				   }
				?>
			</div>
			<div class="clear"></div>
			<!--Конец левого блока-->
<script>
$(".selectedCat").html($('.searchcat :selected').html());
$(".searchcat").change(function (){
	$(".selectedCat").html($('.searchcat :selected').html());
});
</script>
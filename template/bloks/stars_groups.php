<!--Левый блок-->
<div id="left" style="padding-top:50px;">
 <h1><?=$TITLE?></h1>
    <?php
      $arrSTARS = $STARS->Show($star_id);
      if(is_array($arrSTARS)){
      	 echo "<div class=\"categories-buttons\">";
         foreach($arrSTARS as $key=>$value){
      	   echo "<a href=\"/stars-".$value['id'].".php\"><div class=\"categories-button\" style=\"background-image: url(pic/".$value['img'].");\"><div>".$value['name']."</div></div></a>";
         }
         echo "</div>";
      } else {
      	echo "
      	<div class=\"searchresultsboxk\">
			<div class=\"search-results-text\">Поиск</div>
			<div class=\"block\"  id=\"searchfriend\">
				<div class=\"search_wrap\">
					<div class=\"ss\">
						<sub></sub>
						<div class=\"s_inp\"><input type=\"text\" name=\"search_friends\" value=\"\" class=\"searchBox2\" placeholder=\"\"></div>
						<sup></sup>
					</div>
				</div>
				<div class=\"displayBox\"></div>
			</div>
			<div class=\"submit\">
				<input type=\"submit\" value=\"\" class=\"s_btn\" onclick=\"if($('#search_words').val().length &gt;= 1) { document.form_search.submit() }; return false;\">
				<sub></sub>
				<sup></sup>
			</div>
		</div>
		<br><br>";
      	
      	//echo "<div><strong>Всего 5</strong></div>";
      	
      	$stars_all = $USER->ShowStars($star_id,30);
      	
      	if(is_array($stars_all)){
      		echo "<div class=\"resultsearch-kum\">";
      		
      		for($i=0; $i <  count($stars_all); $i++){
			   $arr_users[] = $stars_all[$i]['user_wp'];
		    }
		    $avatar = ShowAvatar($arr_users,130,130);
      		
      		for($i=0; $i < count($stars_all); $i++){
      			echo "
      			<div class=\"img-kum\">
				  <div class=\"img-kum-img\">
					<a href=\"/".$stars_all[$i]['user_wp']."\"><img src=\"".$avatar[$i]['avatar']."\" width=\"130\" height=\"130\" /></a>
				  </div>
				<div class=\"img-kum-text\">
					<a href=\"/".$stars_all[$i]['user_wp']."\">".$stars_all[$i]['firstname']." ".$stars_all[$i]['lastname']."</a>
				</div>
			   </div>
			   ";
      		}
      		echo "</div>";
      	}
      }
    ?>
</div>
<div class="clear"></div>
<!--Конец левого блока-->
<script type="text/javascript">
//Здесь задавать параметры количества отображения друзей
searchbox($("#searchfriend"),7);

//Searchbox
function searchbox(box,rows){
	function finditems(query,displaybox,rows,start){
		if(query=="")displaybox.hide();
		else{
			$.ajax({
				type:"POST",
				url:"/jquery-searchuser.php?p=<?=$star_id?>&type=sf",
				data:"searchword="+query+"&rows="+rows+"&start="+start,
				cache:false,
				success:function(html){
					displaybox.css("width",searchbox.width());
					displaybox.html(html).show();
				}
			});
		}
		return false;
	}
	function clearresults(){
		displaybox.hide()
	}

	var searchbox=box.find(".searchBox2"), displaybox=box.find(".displayBox"), delay=1000, result_Selected=-1, result_Count=0;

	searchbox.keyup(function(e){
		setTimeout(finditems($(this).val(),displaybox,rows,0),delay);
	});
	$("html").click(function(e){
		if(e.target.className!="displayBox")clearresults();
	});
	$(".morebox").live("click",function(){
		finditems(searchbox.val(),displaybox,rows,$(this).attr("start"));
	});
}
</script>
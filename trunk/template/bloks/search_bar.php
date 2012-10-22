<div id="searchbar">
 <?php
   if(isset($_SESSION['KLIENT']))
      echo "<a href=\"/adminmyshops.php\" style=\"background:url('pic/enter-shop.png') no-repeat;\">Мой кабинет</a>";
   /*else 
      echo "<a href=\"/window-login.php\" name=\"w_login\" style=\"background:url('pic/enter-shop.png') no-repeat;\">".LANG_IN_SHOP."</a>";*/
   
   /*
   <!-- <a href="#" style="background:url('pic/enter-partner.png') no-repeat;"><?=LANG_MENU_PARTNER?></a> -->
 <!-- <a href="#" style="background:url('pic/enter-dealer.png') no-repeat;"><?=LANG_MENU_DILER?></a> -->
 */   
 ?> 

 <div id="searchField">
  <form action="/search.php" method="get" id="form_search">
   <div class="search_wrap">
	<div class="ss">
	 <sub></sub>
	 <div class="s_inp"><input type="text" id="search_words" name="search_words" value="<?=@$varr['search_words']?>"></div>
	 <sup></sup>
	</div>
	<div class="submit">
	 <input type="submit" value="" class="s_btn" onclick="if($('#search_words').val().length &gt;= 1) { document.form_search.submit() }; return false;">
	 <sub></sub>
	 <sup></sup>
	</div>
   </div>
  </form>
 </div>
</div>
<div id="center">
<?php
 $lenta = $USER->LentaAkcia(10,1);
 print_r($lenta);
 if(is_array($lenta)){
	 foreach($lenta as $key=>$value){
	 	$akcia_arr[] = $value['akcia_id'];
	    $USER->PodpiskaViewAdd($value['akcia_id']);
	 }
	  	 
	 $photo  = ShowFotoAkcia($akcia_arr,130,91);
	 echo "<div id=\"idItems\">";
	  	 
	 for($i=0; $i < count($lenta); $i++){
	     	
	  	if($lenta[$i]['dogovor'] == 1)
            $blue = ($lenta[$i]['amount']/100)." ".$lenta[$i]['currency'];
        else 
            $blue = $lenta[$i]['datastart']." ".$lenta[$i]['datastop'];
            
            //cont-cent-icon-gift.png
            
	     	
	  echo "
	    <div class=\"post\" id=\"".$lenta[$i]['akcia_id']."_akcia\">
		<div class=\"desire\">
			<div class=\"desire-photo\"><a href=\"/gift-".$lenta[$i]['akcia_id']."\"><img src=\"".$photo[$i]['foto']."\" width=\"130\" height=\"91\" /><br />$blue</a><span></span></div>
			<div class=\"desire-text\">
				<h1>".$lenta[$i]['header']."</h1>
				<table><tr><td><a href=\"/type-".$lenta[$i]['type_id']."-0-".$lenta[$i]['shop_id']."\">".$lenta[$i]['type']."</a></td><td><!-- <img src=\"pic/".$lenta[$i]['type_img']."\" width=\"14\" height=\"18\" /> --></td></tr></table>
				<p>".str_replace("\n","<br />",$lenta[$i]['mtext'])."</p>
			</div>
			<div class=\"desire-buttons\">
				<span class=\"clr-but clr-but-blue-nb\"><sub></sub><a href=\"#\" id=\"reg-form-but\" onClick=\"HideItem(".$lenta[$i]['akcia_id']."); return false;\">Скрыть</a><i></i><sup></sup></span>
				<span class=\"clr-but clr-but-green-nb\"><sub></sub><a href=\"/gift-".$lenta[$i]['akcia_id']."\" id=\"reg-form-but\">Перейти к подписке</a><u></u><sup></sup></span>
			</div>
		</div>
		<div class=\"clear\"></div>
	    </div>
	    <p class=\"hr-des\" id=\"".$lenta[$i]['akcia_id']."_hr\"></p>
	  ";
	 }
	 echo "</div>";
 }
?>	
</div>
<script type="text/javascript">
 function HideItem(id){
 	$.ajax({
	  url:'/jquery-hideitem',
	  cache:false,
	  type: 'POST',
	  data: {akcia_id:id},
	  success:function(){
		$('#'+id+'_akcia').remove();
		$('#'+id+'_hr').remove();
	  }
	});
 }
 $(document).ready(function(){
   var scrH = $(window).height();
   var i = 2;
   $(window).scroll(function(){
     var scro = $(this).scrollTop();
     var scrHP = $('#idItems').height();
     var scrH2 = 0;
     scrH2 = scrH + scro;
     var leftH = scrHP - scrH2;
     if(leftH < 300){
     	if(leftH < 300 && <?=ceil($_SESSION['count_all'] / 10)?> >= i){
     	$('#idItems').find('.hr-des:last').after('<div id="loading" style="padding-top:30px; text-align: center;"><img src="/pic/loader_clock.gif"></div>');
        $.ajax({
	      url:'/jquery-showpodpiska',
	      cache:false,
	      type: 'POST',
	      data: {page:i++},
	      success:function(data){
	      	$('#loading').remove();
		    $('#idItems').find('.hr-des:last').after(data);
	      }
	    });
     	}
     }
   });
 });
</script>
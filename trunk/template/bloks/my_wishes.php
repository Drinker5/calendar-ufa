<div id="center">
<?php
 require_once ('jquery/jq_mywishes.php');
 $performed_wishes=0;
 $rows  = 10;
 $begin = 0;

 $wish_array = $USER -> CountIHochu($user_wp);
 $wish_num   = $USER -> CountIHochu($user_wp, 'all');

 if ($user_wp == $_SESSION['WP_USER']['user_wp']){
   $header   = "Мои желания &nbsp;";
   $link_wp  = "my";
   $wish_add_link = "<li><a href=\"/type-5\"><i class=\"small-icon icon-add-wish\"></i>Добавить желание</a></li>";
 }
 else{
   $header = "Желания &nbsp;";
   $link_wp = $user_wp;
   $wish_add_link = "";
 }

 $perf_wishes_html = '';
 $my_wishes_html = '';
 $par = '';

 if(is_array($wish_array)){
     echo "
             <div class=\"title margin\">
                <h2 id=\"count_wishes\">$header<span class=\"title-count\">(".$wish_array['all'].")</span></h2>
             </div>
             <div class=\"nav-panel group\">
                <ul class=\"fl_l left\">
                    $wish_add_link
                    <!--<li><a href=\"#\"><i class=\"small-icon icon-wish-list\"></i>Добавить wishlist</a></li>-->
                </ul>

                <ul class=\"fl_r right\">";

                if(isset($_REQUEST['t'])){
                  $my_wishes_html = "<li class=\"opacity_link\"><a id=\"my_wish_mini\" href=\"/$link_wp-wishes\">$header<span>(".$wish_array['all'].")</span></a></li>";
                  if ($_REQUEST['t'] == 'performed'){
                    $perf_wishes_html = "<li class=\"active\"><a id=\"perf_wish\" href=\"/$link_wp-wishes?t=performed\">Исполненные желания <span>(".$wish_array['performed'].")</span></a></li>";
                    $par = 'performed';
                  }
                  else {
                    $my_wishes_html = "<li class=\"active\"><a id=\"my_wish_mini\" href=\"/$link_wp-wishes\">$header<span>(".$wish_array['all'].")</span></a></li>";
                    $perf_wishes_html = "<li class=\"opacity_link\"><a id=\"perf_wish\" href=\"/$link_wp-wishes?t=performed\">Исполненные желания <span>(".$wish_array['performed'].")</span></a></li>";
                  }
                }
                else {
                  $my_wishes_html = "<li class=\"active\"><a id=\"my_wish_mini\" href=\"/$link_wp-wishes\">$header<span>(".$wish_array['all'].")</span></a></li>";
                  $perf_wishes_html = "<li class=\"opacity_link\"><a id=\"perf_wish\" href=\"/$link_wp-wishes?t=performed\">Исполненные желания <span>(".$wish_array['performed'].")</span></a></li>";
                }

                echo $my_wishes_html, $perf_wishes_html;
          echo "</ul>
             </div>
             <div id=\"idItems\">";
                $r_array = WishList($user_wp, $rows, $begin, $par);
       echo "</div>";
     echo "</div>";
 }
 else {
   echo "Не могу вывести список желаний!";
 }
?>
</div>
<div id="loadmoreajaxloader" style="display:none; text-align:center;"><img src="/pic/loader.gif" alt="loader" width="32" height="32" /></div>

<script type="text/javascript">
 var page=1, max=<?=ceil($wish_num['num_rows']/$rows)?>, rows=<?=$rows?>, begin=rows, user_wp=<?=$user_wp?>, status=1, par='<?=$par?>';
 var num_rows = rows, wish_cnt = <?=$r_array['wish_cnt']?>;

 //alert(<?=$wish_num['num_rows']?>+' '+<?=$rows?>);
 function wishes(){
    //alert(wish_cnt);
    status = 0;
    if(max>page)
        $('div#loadmoreajaxloader').show();
    $.ajax({
  		    url:'/jquery-mywishes',
            type:'POST',
            data:{list:begin, items:rows, wp:user_wp, param:par, wish_num:wish_cnt},
            cache:false,
  			success: function(data){
              			var html, idItems=$('#idItems');
                        if(data){
                          if(max>page){
                            $('div#loadmoreajaxloader').hide();
                              //console.log(data);
                              html = jQuery.parseJSON(data);
                              idItems.append(html.html);
                              idItems.find('[rel="'+html.uid+'"]').popover({
                               trigger: 'none',
                               autoReposition: false
                              })
                              .popover('content', $('#wish-edit-template').html(), true)
                              .popover('setOption', 'position', 'bottom')
                              .popover('setOption', 'horizontalOffset', -160)
                              .popover('setClasses', 'wish-edit-popover');

                              page =page+1;
                              begin=begin+rows;
                              //alert(page+' '+begin);
                              status = 1;
                              //alert(html.num_rows);
                              num_rows += parseInt(html.num_rows);
                              wish_cnt = html.wish_cnt;
                              //alert(html.wish_cnt);
                          }
                          else{
                            $('div#loadmoreajaxloader').hide();
                            //$('div#loadmoreajaxloader').html('<center>No more posts to show.</center>');
                          }
                        }
            }
    });
 }

 $(window).scroll(function(){
    if($(window).scrollTop()==$(document).height()-$(window).height()){
      if (status == 1)
        wishes();
	}
 });

 function HideItem(id){
 	$.ajax({
	  url:'/jquery-hideitem',
	  cache:false,
	  type: 'POST',
	  data: {akcia_id:id,want:1},
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
	      url:'/jquery-showwant',
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
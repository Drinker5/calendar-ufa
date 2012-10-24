<div id="center">
<?php
	$performed_wishes=0;
 $user_wp = $_SESSION['WP_USER']['user_wp'];

 require_once ('jquery/jq_mywishes.php');
 $rows  = 10;
 $begin = 0;

 $wish_array = $USER -> CountIHochu();
 $perf_wishes_html = '';
 $my_wishes_html = '';
 $par = '';

 if(is_array($wish_array)){
     echo "<div id=\"content\" class=\"fl_r\">
             <div class=\"title margin\">
                <h2 id=\"count_wishes\">Мои желания &nbsp;<span class=\"title-count\">(".$wish_array['all'].")</span></h2>
             </div>
             <div class=\"nav-panel group\">
                <ul class=\"fl_l left\">
                    <li><a href=\"#\"><i class=\"small-icon icon-add-wish\"></i>Добавить желание</a></li>
                    <li><a href=\"#\"><i class=\"small-icon icon-wish-list\"></i>Добавить wishlist</a></li>
                </ul>

                <ul class=\"fl_r right\">";

                if(isset($_REQUEST['t'])){
                  $my_wishes_html = "<li class=\"opacity_link\"><a id=\"my_wish_mini\" href=\"/my-wishes\">Мои желания <span>(".$wish_array['all'].")</span></a></li>";
                  if ($_REQUEST['t'] == 'performed'){
                    $perf_wishes_html = "<li class=\"active\"><a id=\"perf_wish\" href=\"/my-wishes?t=performed\">Исполненные желания <span>(".$wish_array['performed'].")</span></a></li>";
                    $par = 'performed';
                  }
                  else {
                    $my_wishes_html = "<li class=\"active\"><a id=\"my_wish_mini\" href=\"/my-wishes\">Мои желания <span>(".$wish_array['all'].")</span></a></li>";
                    $perf_wishes_html = "<li class=\"opacity_link\"><a id=\"perf_wish\" href=\"/my-wishes?t=performed\">Исполненные желания <span>(".$wish_array['performed'].")</span></a></li>";
                  }
                }
                else {
                  $my_wishes_html = "<li class=\"active\"><a id=\"my_wish_mini\" href=\"/my-wishes\">Мои желания <span>(".$wish_array['all'].")</span></a></li>";
                  $perf_wishes_html = "<li class=\"opacity_link\"><a id=\"perf_wish\" href=\"/my-wishes?t=performed\">Исполненные желания <span>(".$wish_array['performed'].")</span></a></li>";
                }

                echo $my_wishes_html, $perf_wishes_html;
          echo "</ul>
             </div>
             <div id=\"idItems\">";
                WishList($user_wp, $rows, $begin, $par);
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
 var page=1, max=<?=ceil($wish_array['all']/$rows)?>, rows=<?=$rows?>, begin=rows, user_wp=<?=$user_wp?>, status=1, par='<?=$par?>';
 var num_rows = rows;

 function wishes(){
    status = 0;
    if(max>page)
        $('div#loadmoreajaxloader').show();
    $.ajax({
  		    url:'/jquery-mywishes',
            type:'POST',
            data:{list:begin, items:rows, wp:user_wp, param:par},
            cache:false,
  			success: function(data){
              			var html, idItems=$('#idItems');
                        if(data){
                          if(max>page){
                            $('div#loadmoreajaxloader').hide();
                              console.log(data);
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
                              num_rows += parseInt(html.num_rows);
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

 //Comments
 function CommentsAction(id,type,n){
   var msg    = $("#comments-" + id + "-add").val(),
   	   count  = $("#comments-" + id + "-count0").get(0);
   $.ajax({
     url:'/jquery-comments',
     type:'POST',
     data:{type:type,id:id,msg:msg,n:n,page:'akcia'},
     cache:false,
     success: function(data){
       var html,
           nCount         = count.innerHTML,
           idComments     = $('#comments-' + id);

       if(data){
         if(type=='add'){
           nCount++;
           html = jQuery.parseJSON(data);
           idComments.append(html.html);
           idComments.find('.wishlist-comment group:last').slideDown('slow');
           $('#comments-' + id + '-add').attr('value', '');
         } else if(type=='delete'){
            nCount--;
            $('#comments-' + n + '-id').slideUp('slow',function(){
              $(this).remove();
            });
		 }
         $('#comments-' + id + '-count0').html(nCount);
         $('#comments-' + id + '-count1').html(nCount);
         $('#comments-' + id + '-count2').html(nCount);
       }
     }
   });
 }
 function CommentsShow(id,num){
   var idCommentsFull = $('#comments-' + id + '-full');

   $(this).toggle(function(){
     $.ajax({
       url:'/jquery-comments',
       type:'POST',
       data:{type:'show',id:id,num:num},
       cache:false,
       success: function(data){
         var html;
         if(data){
           html = jQuery.parseJSON(data);
           idCommentsFull.append(html.html);
           idCommentsFull.slideDown('slow');
         }
       }
     });
   },
   function(){
     idCommentsFull.slideUp('slow',function(){
       $(this).remove();
     });
   });
 }
</script>
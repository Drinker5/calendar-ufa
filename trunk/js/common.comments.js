//Comments
 function CommentsAction(id,type,n,par){
   var msg    = $("#comments-" + id + "-add").val(),
   	   count  = $("#comments-" + id + "-count0").get(0);
   $.ajax({
     url:'/jquery-comments',
     type:'POST',
     data:{type:type,id:id,msg:msg,n:n,par:par},
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
 function CommentsShow(id,num,par){
   var idCommentsFull = $('#comments-' + id + '-full');

   $(this).toggle(function(){
     $.ajax({
       url:'/jquery-comments',
       type:'POST',
       data:{type:'show',id:id,num:num,page:'akcia',par:par},
       cache:false,
       success: function(data){
         var html;
         if(data){
           html = jQuery.parseJSON(data);
           //console.log(html.html);
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
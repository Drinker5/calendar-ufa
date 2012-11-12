//Операции с желаниями
//добавление нового желания
$('.add_me').live('click', function(){
    var wish_id = $(this).data('id');
    var shop_id = $(this).data('shop');
    var status  = $(this).data('status');
    var html, html_ico, text;
    if (($('#wish-add-'+wish_id).attr('data-status') == 1) || (status == 1)){
       return false;
    }
    else{
        $.ajax({
            url:'/jquery-gifts',
            type:'POST',
            data:{type:'add', wish_id:wish_id, shop_id:shop_id},
            cache:false,
            success:function(data){
                    $('#wish-add-'+wish_id).addClass('disabled');
                    $('#wish-add-'+wish_id).attr('data-status','1');
                    /*
                    html    = $('#wish-add-'+wish_id).html();
                    text    = $('#wish-add-'+wish_id).text();
                    html_ico = html.replace(text," ");
                    */
                    $('#wish-add-'+wish_id).html("Добавлено в желания");

            }
        });
    }
});

// сохранение изменений
function SaveWish(id, that){
  reason = $('#wish_reason'+id).val();
  date = $('#wish_date').val();
  if (reason.length <= 500){
    $.ajax({
      url:'/jquery-mywishes',
      cache:false, type:'POST',
      //data:{wish:id, w_reason:reason, w_date:date},
      data:{wish:id, w_reason:reason},
      success:function(result){
        if(result=='ok'){
          reason = reason.replace(/&/g, "&amp;");
          reason = reason.replace(/</g, "&lt;");
          reason = reason.replace(/>/g, "&gt;");
          reason = reason.replace(/"/g, "&quot;");
          if (reason.length == 0){
            $('#reason'+id).html(reason);
            $('#p_reason'+id).css("display", "none");
          }
          else {
            $('#reason'+id).html(reason);
            if ($('#p_reason'+id).css("display") == 'none')
                $('#p_reason'+id).show();
          }
          that.parents('.wish-edit-popover').hide();
          //$(this).parents('.wish-edit-wrap').hide();
          //alert($('.tx_l').parent());
          //$('#wish_edit').hide();
          //$("#save_wish").click(function(){ alert($(this).parent().attr('id')); });
          return false;
        }
      }
    });
  }
  else alert("Сообщение должно быть менее 500 символов!");

}

//удаление желания
$('#del_wish').live('click', function(){
	var that=$(this), id=that.data('wish'), status=that.data('status'), span=$('#count_wishes').find('span');
	if(confirm('Вы действительно хотите удалить желание?')){
		$.ajax({
			url:'/jquery-mywishes', cache:false, type:'POST', data:{del:id},
			success:function(result){
				if(result=='ok'){
				  new_wish_value = parseInt((span.text()).match(/\((.+)\)/i)[1])-1;
				  span.html('('+new_wish_value+')');
                  $('#my_wish_mini').find('span').html('('+new_wish_value+')');
                  if (status == 1){
                    perf_span = $('#perf_wish').find('span');
                    new_perf_wish_value = parseInt((perf_span.text()).match(/\((.+)\)/i)[1])-1;
                    perf_span.html('('+new_perf_wish_value+')');
                  }
                  $('#wish'+id).remove();
                  $('#separator'+id).remove();
                  num_rows--;
                  if (num_rows < rows){
                    begin = num_rows;
                    wishes();
                  }
				}
			}
		});
	}
});
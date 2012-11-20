<?php
$count_of_avatar = 0;
function getAvatarView()
{
    global $USER;
    global $count_of_avatar;
    $default_avatar = $_SESSION['WP_USER']['photo'];
    $photos = $USER-> ShowAvatarAlbum();
    if ($photos == 0)
        return '';
    //Временный output для всех аватар, кроме default
    $tmp_output= '';
    //Общий output
    $output = '';
    foreach ($photos as $value) {
        if ($value['avatar'] == $default_avatar)
            $output .= getSimpleAvatarHtml($value['avatar'],$value['photo_id'],true);
        else
            $output .= getSimpleAvatarHtml($value['avatar'],$value['photo_id']);
        $count_of_avatar += 1;
    }
    $output .= $tmp_output;
    $output .= "<div style='clear:both'></div>";
    return $output;
}
function getSimpleAvatarHtml($path,$id,$main=false)
{
    $output = "<div class='avatar-actions'><img src=$path /> <input type='hidden' name='photo_id' value='$id'/>";
    $output .= "<a href='javascript:void(null)' class='delete_avatar'><i class='small-icon icon-delete'></i>Удалить</a>";
    $checked = ($main)?'checked=checked':'';
    $output .= "<label class='set_to_default_avatar'><input type='radio' name='radio' $checked>Сделать основной</label>";
    $output .= "</div>";
    return $output;
}
?>
<div class="title margin">
    <h2>Изменить аватар</h2>
</div>
<div class="nav-panel group">
    <ul class="fl_r right">
        <li class="opacity_link"><a href="/my-profile">Мой профиль</a></li>
        <li class="opacity_link"><a href="/my-phones">Телефон</a></li>
        <!--<li class="opacity_link"><a href="/my-wallets">Счет</a></li>-->
        <li class="opacity_link"><a href="/my-alerts">Оповещения</a></li>
        <li class="opacity_link"><a class="active" href="/my-avatar">Изменить аватар</a></li>
        <li class="opacity_link"><a href="/my-password">Изменить пароль</a></li>
        <!--<li class="opacity_link"><a href="/my-subscribes">Подписки</a></li>-->
    </ul>
</div>
<div class="tools_block" style="width:auto;">
    <div class="modal_page_img" style="display: none">
        <div class="small-icon icon-close" id="close_but"></div>
        <p class='hint'>Выбранная область будет сохранена как фотография на твоей странице</p>
        <div id="img">                   
            <div id="image_container" style=""></div>
        </div>
        <div id="button-group">
            <div class="btn btn-grey" id="save_buttonus">
                <span class="clr-but clr-but-red"><sub></sub><a href="#" id="btnCancelAvatar">Отмена</a><sup></sup></span>
            </div>
            <div class="btn btn-green" id="save_buttonus">
                <span class="clr-but clr-but-green"><sub></sub><a href="#" id="btnSaveAvatar">Сохранить</a><sup></sup></span>
            </div>
            
        </div>
    </div>
</div>
<div class="upload-avatar center">
    <p class="hint px28">Загрузи свои фотографии на страницу в качестве аватара (5 штук)</p>
    <span class="arrow-next-step"></span>
    <span class="btn btn-green" id='add-photo-but'><i class="small-icon icon-to-me white"></i>Загрузить с компьютера</span>
    <span class="btn btn-green"><i class="small-icon icon-photo white"></i>Добавить фото в альбом</span>
    <div id='files'></div>
    <div class='error_messages'>Нельзя загрузить больше 5 аватар</div>
    <div class="separator"></div>

<div class='cleared'></div>
    <!--<div class="btn btn-green" id="save_buttonus">Сохранить</div>-->
    <div class='old_avatars'><?=getAvatarView()?></div>
</div>
</div>
<input type="hidden" id="x" value="0" /><input type="hidden" id="y" value="0" />
<input type="hidden" id="w" value="190" /><input type="hidden" id="h" value="190" />
<input type="hidden" id="avatar_orig" name="avatar_orig" />
<script src="js/fileuploader.js" type="text/javascript"></script>
<script type="text/javascript">
var count_of_avatar = <?=$count_of_avatar?>;
$(document).ready(function(){
    $('a.delete_avatar').click(delete_avatar);
    $('label.set_to_default_avatar').click(set_to_default_avatar);
    $('input[type=radio]').uniform();
    if (count_of_avatar >= 5)
    {
        delete createUploader;
        $('#add-photo-but').click(function(e)
        {
            e.stopPropagation();
            e.preventDefault();
            $('div.error_messages').show();

        });
    }
});
function delete_avatar()
{
    var action = 'delete';
    action_to_avatar(this,action);
}
function set_to_default_avatar()
{
    var action = 'set_to_default';
    action_to_avatar(this,action);
}
function action_to_avatar(element,action)
{
    var block = $(element).closest('.avatar-actions');
    var photo_id = $('input[name=photo_id]',block).val();
    var action = action;
    $.ajax({
          url:'/jquery-avatar',
          cache:false,
          type: "POST",
          data:{photo_id:photo_id,action:action},
          success:function(data){
            location.reload();
          }
    });
}
    $("#close_but").click(function(){
        $(".modal_page_img").hide();
        $(".upload-avatar").show();
    });
	function createUploader(){
		var uploader=new webimg.FileUploader({
					element          :document.getElementById('files'),
					button           :document.getElementById('add-photo-but'),
					action           :'/jquery-upload.php?type=avatar_orig',
					allowedExtensions:['jpg', 'jpeg', 'png', 'gif'],
					sizeLimit        :<?=max_file_size?>,
					maxConnections   :1,
					multiple         :false,
					onComplete       :function(id, fileName, responseJSON){
                                            $(".modal_page_img").show();
                                            $(".upload-avatar").hide();
						$('.webimg-upload-list').html('');
						if(responseJSON.success){
							CropAndSave(responseJSON);
						}
						else alert(responseJSON.error);
					}
		});
	}
var jcrop_api, boundx, boundy;

window.onload = createUploader;  

function updatePreview(c){
	if(parseInt(c.w) > 0){
		var rx = 190 / c.w;
		var ry = 190 / c.h;

		$('#preview').css({
			width: Math.round(rx * boundx) + 'px',
			height: Math.round(ry * boundy) + 'px',
			marginLeft: '-' + Math.round(rx * c.x) + 'px',
			marginTop: '-' + Math.round(ry * c.y) + 'px'
		});
	}
}
function updateCoords(c){
	$('#x').val(c.x);
	$('#y').val(c.y);
	$('#w').val(c.w);
	$('#h').val(c.h);
}

//Загрузка файла, Drug and Drop

var dropbox = $("#area_to_drop_file");
dropbox.bind(
    {
        drop: function(e)
        {
            var dt = e.originalEvent.dataTransfer;
            if (count_of_avatar < 5)
                getFiles(dt.files);
            else
                $('div.error_messages').show();
            return false;
        }

    }
)
function getFiles(files) 
{
    $.each(files, function(i, file) {      
      if (!file.type.match(/image.*/)) {
        // Отсеиваем не картинки
        alert('Загружать можно только изображения');
        return true;
      }
      //загружаем файл
      var response = uploadFile(file,'/jquery-upload.php?type=avatar_orig&webimgfile='+file.name);
      //возвращаем true, для предотвращения множественной загрузки
      return true;
            
    });
}
//Показываем превью с кропом и кнопками сохранения
function CropAndSave(response)
{
    $('#img').width(response.w);
    $(".modal_page_img").show();
    $(".upload-avatar").hide();
    $('#image_container').html('<img src="'+response.photo+'" width="'+response.w+'" height="'+response.h+'" id="target">');
    $('#avatar_orig').val(response.photo);
    $("#preview").remove();
    var _imgprev = $(document.createElement('img')).attr('width', 190).attr('height', 190).attr('id', 'preview').attr('src', response.photo);
    $('.rndimg').append(_imgprev);

    $('#target').Jcrop({
        onChange: updatePreview,
        onSelect: updateCoords,
        aspectRatio: 1
    },
    function(){
        jcrop_api = this;
        var bounds = jcrop_api.getBounds();
        boundx = bounds[0]; boundy = bounds[1];
        jcrop_api.setSelect([0,0,190,190]);
        
        //Корректируем стили для корректного скроллинга
        var overflow_div = $('#image_container > div');
    });
    $('#btnAvatars').show();
    $('#btnCancelAvatar').live('click',function(){location.reload()});
    $('#btnSaveAvatar').live('click',function(){
        $('#btnAvatars').hide();
        $('#image_container').html('<center><br /><br /><br /><br /><?=loading_clock?></center>');
        $.ajax({
            type: "POST",
            url: "/jquery-upload.php?type=avatar",
            dataType: "json",
            data: {'avatar_orig': $('#avatar_orig').val(), 'x': $('#x').val(), 'y': $('#y').val(), 'w': $('#w').val(), 'h': $('#h').val()},
            success:function(data){
                if(data.error_id=='0'){
                    //alert(data.error_id+' '+data.error_data);
                    location.href='/my-avatar';
                }
                else alert(data.error_id+' '+data.error_data);
            },
        });
    }); 

}
function uploadFile(file,url)
{
    var reader = new FileReader();
    var xhr = new XMLHttpRequest();
    //callback на ответ сервера
    xhr.onreadystatechange = function () 
    {
        if (this.readyState == 4) 
        {
            //загрузка прошла успешно
            if(this.status == 200) {
                var response = JSON.parse(this.responseText);
                CropAndSave(response);
                return this.responseText;
            } 
            //что-то пошло не так
            else 
            {
            /* ... ошибка! ... */
            }
        }
    };
    //передача файла
    xhr.open("POST", url, true);
    xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    xhr.setRequestHeader("X-File-Name", file.name);
    xhr.setRequestHeader("Content-Type", "application/octet-stream");
    if(xhr.sendAsBinary) {
      // только для firefox
      xhr.sendAsBinary(file);
    } else {
      // chrome (так гласит спецификация W3C)
      xhr.send(file);
  }
    
}
</script>

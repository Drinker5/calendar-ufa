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
<div class="tools_block">
    <div class="modal_page_img" style="display: none">
        <div class="small-icon icon-close" id="close_but"></div>
        
        <div id="img"> 
            <!--<img src="<?=$_SESSION['WP_USER']['photo']?>" width="190" height="190" id="preview" />-->                         
            <div id="image_container" style=""></div>
        </div>
        <div id="panel">
            Укажите область, которая будет сохранена как фотография Вашей страницы.
            <div class="btn btn-grey" id="save_buttonus">
                <span class="clr-but clr-but-green"><sub></sub><a href="#" id="btnSaveAvatar">Сохранить</a><sup></sup></span>
            </div>
            <div class="btn btn-green" id="save_buttonus">
                <span class="clr-but clr-but-red"><sub></sub><a href="#" id="btnCancelAvatar">Отмена</a><sup></sup></span>
            </div>
        </div>
    </div>


    <div class="tools">
        <div id="avatar_tools">
            <div class="left_sidebar">
                <div id="area_to_drop_file">
                <span>Перетащи фотографию сюда</span>
                <div id="photus"></div></div>
            </div>
            <div class="right_sidebar">
                <span class="clr-but clr-but-green" id="add-photo-but"><sub></sub><a href="#">Загрузить фото</a><sup></sup></span><br />
                <a href="#">Выбрать фотографию из моих альбомов</a>
                <div id="files"></div>
            </div>
            <div class="cleared"></div>
        </div>
    </div>
    <div class="btn btn-green" id="save_buttonus">Сохранить</div>
</div>
<input type="hidden" id="x" value="0" /><input type="hidden" id="y" value="0" />
<input type="hidden" id="w" value="190" /><input type="hidden" id="h" value="190" />
<input type="hidden" id="avatar_orig" name="avatar_orig" />
<script src="js/fileuploader.js" type="text/javascript"></script>
<script type="text/javascript">
    $("#close_but").click(function(){
        $(".modal_page_img").hide();
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
            getFiles(dt.files);
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
    $(".modal_page_img").show();
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
        var scroll_width = 20;
        var avatar_visible_height = 450;
        $(overflow_div).css('overflow-y','scroll');
        $(overflow_div).width($(overflow_div).width() + scroll_width);
        $(overflow_div).height(avatar_visible_height);
    });
    $('#btnAvatars').show();
    $('#btnCancelAvatar').live('click',function(){location.href='/<?=$_SESSION['WP_USER']['user_wp']?>'});
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
                    location.href='/<?=$_SESSION['WP_USER']['user_wp']?>';
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

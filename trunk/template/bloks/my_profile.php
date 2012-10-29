<style>
 .ui-widget-content { border: 1px solid #dddddd; background: #eeeeee url(/pic/ui-bg_highlight-soft_100_eeeeee_1x100.png) 50% top repeat-x; color: #333333; }
    .ui-combobox {
        min-width: 60px;
        position: relative;
        border: 1px solid #D4D7D8;
        line-height: 12px;
        text-decoration: none;
        text-align: left;
        color: #716E6B;
        outline: none;
        vertical-align: middle;
        background: white;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        display: inline-block;
        width: 235px;
    }
   
    .ui-combobox-input {
        padding: 4px 4px 5px;
    display: inline-block;
    white-space: nowrap;
    overflow: hidden;
    line-height: 12px;
    text-align: left;
    color: #716E6B;
    font-size: 10px;
    border: none;
    margin: 0;
    vertical-align: baseline;
    border: 0;
    background: transparent;
    height: 21px;
    box-sizing: border-box;
    width: 100%;
    }
    .ui-state-hover .ui-icon, .ui-state-focus .ui-icon, .ui-state-default .ui-icon 

    {
        background-image: none;
        background: none;
    }
    .ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus, .ui-widget-header .ui-state-focus
    {
        border: none;
        background: none;
        color: #1C94C4;
    }
    .ui-combobox-toggle
    {
        position: absolute;
        top: 0;
        right: 0;
        width: 15px;
        height: 100%;
        background: url('../pic/select-arrow.png') top right no-repeat;
        border:none;
    }
    .ui-autocomplete
    {
        min-width: 228px;
        position: absolute;
        cursor: default;
    }
    .ui-menu {
    list-style:none;
    padding: 2px;
    margin: 0;
    display:block;
    float: left;
}
    .ui-menu .ui-menu {
        margin-top: -3px;
    }
    .ui-menu .ui-menu-item {
        margin:0;
        padding: 0;
        zoom: 1;
        float: left;
        clear: left;
        width: 100%;
    }
    .ui-menu .ui-menu-item a {
        text-decoration:none;
        display:block;
        padding:.2em .4em;
        line-height:1.5;
        zoom:1;
    }
    .ui-menu .ui-menu-item a.ui-state-hover,
    .ui-menu .ui-menu-item a.ui-state-active {
        font-weight: normal;
        margin: -1px;
    }
    </style>

                <div class="title margin">
                    <h2>Мой профиль</h2>
                </div>
                <div class="nav-panel group">
                    <ul class="fl_r right">
                        <li class="opacity_link"><a class="active" href="/my-profile">Мой профиль</a></li>
                        <li class="opacity_link"><a href="/my-phones">Телефон</a></li>
                        <!--<li class="opacity_link"><a href="/my-wallets">Счет</a></li>-->
                        <li class="opacity_link"><a href="/my-alerts">Оповещения</a></li>
                        <li class="opacity_link"><a href="/my-avatar">Изменить аватар</a></li>
                        <li class="opacity_link"><a href="/my-password">Изменить пароль</a></li>
                        <!--<li class="opacity_link"><a href="/my-subscribes">Подписки</a></li>-->
                    </ul>
                </div>
                <div class="tools_block">
                    <p id="msg"></p>
                    <div class="tools">
                        <div class="left_sidebar fl_l">
                            <dl>
                                <dt>
                                    <label>Имя*</label>
                                    <input name="first_name_user" class="input_block" id="user_fname" type="text" value="<?=$_SESSION['WP_USER']['firstname']?>"/>                        
                                </dt>
                                 <dd>
                                    <error id="er1">Введите имя</error>
                                 </dd>
                                <dt>
                                    <label>Фамилия*</label>
                                    <input name="last_name_user" class="input_block" id="user_sname" type="text" value="<?=$_SESSION['WP_USER']['lastname']?>" />   
                                </dt>
                                 <dd>
                                    <error id="er2">Введите фамилию</error>
                                </dd>
                                <dt>
                                    <label>Отчество*</label>
                                    <input name="second_name_user" class="input_block" id="user_otchestvo" type="text" value="<?=$_SESSION['WP_USER']['otchestvo']?>"/>                        
                                </dt>
                                 <dd>
                                    <error id="er1">Введите имя</error>
                                 </dd>
                                <dt id="bd_user">
                                    <label>Дата рождения</label>
                                    <div class="grid30" style="margin-left: 0px;">
                                        <select id="user_day">
                                            <option value="">*</option>
                                            <?php print_r($_SESSION['WP_USER']['birthday'] );
					  for($i=1; $i <= 31; $i++){
					   if( $i == @(int)date_format(date_create($_SESSION['WP_USER']['birthday']),'d'))
					      echo "<option value=\"$i\" selected>$i</option>";
					   else
					      echo "<option value=\"$i\">$i</option>";
					  }
					?>
                                        </select> 
                                    </div> 
                                    <div class="grid30">
                                        <select id="user_month">
                                            <option value="">*</option>
                                            <?php
                                              $month = $MYSQL->query("SELECT id, month_".LANG_SITE." month FROM pfx_month ORDER BY id");
                                              foreach($month as $key=>$value)
                                                    if($value['id'] == (int)@date_format(date_create($_SESSION['WP_USER']['birthday']),'m'))
                                                       echo "<option value=\"".$value['id']."\" selected>".$value['month']."</option>";
                                                    else
                                                   echo "<option value=\"".$value['id']."\">".$value['month']."</option>";
                                            ?>
                                        </select>
                                    </div>
                                    <div class="grid30">
                                        <select id="user_year">
                                            <option value="">*</option>
                                            <?php 
                                              for($i=(int)date("Y")-70; $i <= (int)date("Y")-18; $i++){
                                                    if($i == (int)@date_format(date_create($_SESSION['WP_USER']['birthday']),'Y'))
                                                       echo "<option value=\"$i\" selected>$i</option>";
                                                    else
                                                       echo "<option value=\"$i\">$i</option>";
                                              }
                                            ?>
                                        </select> 
                                    </div> 
                                </dt>
                                <dt>
                                <p>Отображать возраст <input type="checkbox" class="checkbox" safari=1 id="user_year_view" <?=str_replace('1','checked',$_SESSION['WP_USER']['birthdayview'])?>/></p>
                                </dt>
                                <dt>
                                    <label>Пол*</label>
                                    <label class="inline_label"><input name="sex_name_user" class="input_block" type="radio" value="1" <?=str_replace('1','checked',$_SESSION['WP_USER']['sex'])?>/>Мужской</label>   
                                    <label class="inline_label"><input name="sex_name_user" class="input_block" type="radio" value="2" <?=str_replace('2','checked',$_SESSION['WP_USER']['sex'])?>/>Женский</label> 
                                </dt>
                                 <dd>
                                    <error id="er3">Выберите пол</error>
                                </dd>
                                <dt>
                                    <label>Семейное положение</label>
                                    <select name='marital_status'>

                                    </select>   
                                </dt>
                                <dt>
                                    <div class="grid50">
                                        <label>Страна*</label>
                                        <select id="country" class='combobox' onChange="SelTowns(this.value)">
                                            <option value="-1">--- Выберите страну ---</option>
                                                               <?php
                                                                $GLOBALS['PHP_FILE'] = __FILE__;
                                            $GLOBALS['FUNCTION'] = __FUNCTION__;

                                            $user_country = $MYSQL->query("SELECT id, name FROM pfx_country WHERE parent=0");
                                            if(is_array($user_country)){
                                                   foreach($user_country as $key=>$value){
                                                         if($value['id'] == $_SESSION['WP_USER']['country_id'])
                                                            echo "<option value=\"".$value['id']."\" selected=\"selected\"> ".$value['name']."</option>";
                                                         else
                                                            echo "<option value=\"".$value['id']."\"> ".$value['name']."</option>";
                                                   }
                                            }
                                                               ?>
                                        </select>  
                                    </div>
                                    <div class="grid50">
                                        <label>Город*</label>
                                        <select id="user_townid" name='townid' class='combobox'></select>
                                    </div>
                                </dt>
                                 <dd>
                                    <error id="er4">Выберите страну</error>
                                    <error id="er5">Выберите город</error>
                                </dd>
                                <dt>
                                    <label>Email*</label>
                                    <input name="otc_name_user" id="user_email" class="input_block" id="otc_name_user" value="<?=$_SESSION['WP_USER']['email']?>" type="text"/>   
                                </dt>
                                 <dd>
                                    <error id="er6">Введите email</error>
                                </dd>
                                <dt>
                                    <div class="grid30">
                                        <label>ICQ</label>
                                        <input type="text" value="<?=$_SESSION['WP_USER']['icq']?>" id="user_icq" class="brdrd" style="width:118px;" />
                                    </div> 
                                    <div class="grid30">
                                        <label>Skype</label>
                                        <input type="text" value="<?=$_SESSION['WP_USER']['skype']?>" id="user_skype" class="brdrd" style="width:118px;" />
                                    </div>
                                    <div class="grid30">
                                        <label>URL</label>
                                        <input type="text" value="<?=$_SESSION['WP_USER']['url']?>" id="user_url" class="brdrd" style="width:118px;" /> 
                                    </div>
                                </dt>
                                <dt>
                                    <label>Образование</label>
                                    <input name="education_user" id="education_user" type="text" value="<?=$_SESSION['WP_USER']['education']?>"/>    
                                </dt>
                                <dt>
                                    <label>Карьера</label>
                                    <input name="career_user" id="career_user" type="text" value="<?=$_SESSION['WP_USER']['career']?>"/>    
                                </dt>
                                <dt>
                                    <label>О себе</label>
                                    <textarea name="about_user" id='about_user' value="<?=$_SESSION['WP_USER']['about']?>"><?=$_SESSION['WP_USER']['about']?></textarea>   
                                </dt>
                            </dl>

                        </div>
                        <div class="right_sidebar">
                            <p>Настройки конфиденциальности (Страницу смогут видеть) *</p>
                            <br /><br />
                            <p>Разрешить:</p>
                            <br />
                            <?php
                            function GetSecurityList()
                            {
                                global $MYSQL;
                                $result = '';
                                $circles = array(
                                    '0' => array('name' => 'friends', 'name_ru' => 'Близкие друзья', 'circle_id' => '2'),
                                    '1' => array('name' => 'colleagues', 'name_ru' => 'Коллеги', 'circle_id' => '5'),
                                    '2' => array('name' => 'family', 'name_ru' => 'Семья', 'circle_id' => '3'),
                                    '3' => array('name' => 'kumirs', 'name_ru' => 'Кумиры', 'circle_id' => '9'),
                                    '4' => array('name' => 'familiar', 'name_ru' => 'Знакомые', 'circle_id' => '4'),
                                    '5' => array('name' => 'all', 'name_ru' => 'Разрешить всем', 'circle_id' => '0'),
                                    '6' => array('name' => 'nothing', 'name_ru' => 'Только мне', 'circle_id' => '1'),
                                    );
                                $pravo = $MYSQL->query("SELECT IFNULL(pravo,'') security FROM pfx_users WHERE user_wp = ".varr_int($_SESSION['WP_USER']['user_wp']));
                                $pravo = @$pravo[0]['security'];
                                if(strlen($pravo) > 0) {$pravo = unserialize($pravo);} else {$pravo[] = array('krug_id'=>0);} // По умолчанию страница доступна всем
                                foreach ($circles as $key => $circle) {
                                    $checked = '';
                                    foreach ($pravo as $key2 => $value) 
                                        if ($circle['circle_id'] == $value['krug_id']){ $checked = "checked='checked'"; break;}
                                    $result.="<label><input type='checkbox' name='circles_".$circle['name']."' $checked/>".$circle['name_ru']."</label>";
                                }
                                return $result;
                            }

                            echo GetSecurityList();
                            ?>
                        </div>
                        <div class="cleared"></div>
                    </div>
                    <button  class="btn btn-green fl_r" onClick="SaveProfile(); return false;">Сохранить</button>
                </div>
            <!--</form>-->
           
<script>
    (function( $ ) {
        $.widget( "ui.combobox", {
            _create: function() {
                var input,
                    self = this,
                    select = this.element.hide(),
                    selected = select.children( ":selected" ),
                    value = selected.val() ? selected.text() : "",
                    wrapper = this.wrapper = $( "<span>" )
                        .addClass( "ui-combobox" )
                        .insertAfter( select );
                        
                input = $( "<input>" )
                    .appendTo( wrapper )
                    .val( value )
                    .addClass( "ui-state-default ui-combobox-input" )
                    .autocomplete({
                        delay: 0,
                        minLength: 0,
                        source: function( request, response ) {
                            var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                            response( select.children( "option" ).map(function() {
                                var text = $( this ).text();
                                if ( this.value && ( !request.term || matcher.test(text) ) )
                                    return {
                                        label: text.replace(
                                            new RegExp(
                                                "(?![^&;]+;)(?!<[^<>]*)(" +
                                                $.ui.autocomplete.escapeRegex(request.term) +
                                                ")(?![^<>]*>)(?![^&;]+;)", "gi"
                                            ), "<strong>$1</strong>" ),
                                        value: text,
                                        option: this
                                    };
                            }) );
                        },
                        select: function( event, ui ) {
                            ui.item.option.selected = true;
                            var parent = $(this).closest('div');
                            var current_select = $('select',parent);
                            if ($(current_select).attr('id') == 'country')
                                SelTowns($('#country').val());

                            self._trigger( "selected", event, {
                                item: ui.item.option
                            });
                        },
                        change: function( event, ui ) {
                            if ( !ui.item ) {
                                var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
                                    valid = false;
                                select.children( "option" ).each(function() {
                                    if ( $( this ).text().match( matcher ) ) {
                                        this.selected = valid = true;
                                        return false;
                                    }
                                });
                                if ( !valid ) {
                                    // remove invalid value, as it didn't match anything
                                    $( this ).val( "" );
                                    select.val( "" );
                                    input.data( "autocomplete" ).term = "";
                                    return false;
                                }
                            }
                        }
                    })
                    .addClass( "ui-widget ui-widget-content ui-corner-left" );

                input.data( "autocomplete" )._renderItem = function( ul, item ) {
                    return $( "<li></li>" )
                        .data( "item.autocomplete", item )
                        .append( "<a>" + item.label + "</a>" )
                        .appendTo( ul );
                };

                $( "<a>" )
                    .attr( "tabIndex", -1 )
                    .attr( "title", "Show All Items" )
                    .appendTo( wrapper )
                    .button({
                        icons: {
                            primary: "ui-icon-triangle-1-s"
                        },
                        text: false
                    })
                    .removeClass( "ui-corner-all" )
                    .addClass( "ui-corner-right ui-combobox-toggle" )
                    .click(function() {
                        // close if already visible
                        if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
                            input.autocomplete( "close" );
                            return;
                        }

                        // work around a bug (likely same cause as #5265)
                        $( this ).blur();

                        // pass empty string as value to search for, displaying all results
                        input.autocomplete( "search", "" );
                        input.focus();
                    });
            },

            destroy: function() {
                this.wrapper.remove();
                this.element.show();
                $.Widget.prototype.destroy.call( this );
            }
        });
    })( jQuery );


$(document).ready(function(){
	//$('#center-profile select').not('#country').selectBox();
	$('input[safari]:radio').checkbox({cls:'jquery-safari-checkbox'});
	$('input[safari]:checkbox').checkbox({cls:'jquery-safari-checkbox-box'});
	SelTowns(<?=$_SESSION['WP_USER']['country_id']?>);
    $( ".combobox" ).combobox();
    var marital_status = $('select[name=marital_status]');
    GetMaritalStatus(<?=$_SESSION['WP_USER']['sex']?>,<?=$_SESSION['WP_USER']['marital_status']?>);
    $(marital_status).selectBox('refresh');
    $('input[name=sex_name_user]').click(function(){GetMaritalStatus( $(this).val(), $(marital_status).val() ); $(marital_status).selectBox('refresh'); });
});

function GetMaritalStatus(sex,defaults)
{
    var default_status = defaults || 0;
    var select_of_marital_status = $('select[name=marital_status]');
    var html=''; 
    var mail = '1';
    if (sex == mail)
    {
        var marital_status = {'Не женат':0,'Встречаюсь':1,'Помолвлен':2, 'Женат':3, 'Влюблен':4, 'Все сложно':5, 'В активном поиске':6 };
    }
    else
    {
         var marital_status = {'Не замужем':0,'Встречаюсь':1,'Помолвлена':2, 'Замужем':3, 'Влюблена':4, 'Все сложно':5, 'В активном поиске':6 };
    }
    for (status in marital_status)
    {
        var selected = '';
        if  (marital_status[status] == defaults)
            selected = 'selected = selected'
        html += '<option value="'+marital_status[status]+'" '+selected+'>'+status+'</option>';
    }
    $(select_of_marital_status).html(html);
    return true;
}

function SecurityCheckboxs(element)
{
    var names = {'circles_all':0,'circles_nothing':1};
    var current_name = $(element).attr('name');
    if (current_name in names)
    {
        $('input[name^=circles]').attr('checked',false);
        $(this).attr('checked',true);
    }
    else
    {
        for (var name in names)
        {
            $('input[name='+name+']').attr('checked',false);
        }
    }
    return true;
}

function SelTowns(country_id){
	$.ajax({
	    url:'/jquery-seltowns',
		cache:false,
        async:false,
		type: "POST",
		data: {country_id:country_id,select:1},
		success:function(data){
			$('#user_townid').html(data);
            if (country_id != <?=$_SESSION['WP_USER']['country_id']?>)
            {
                $('#user_townid option:first').attr('selected','selected');
            }
            $('#user_townid').combobox('destroy');
            $('#user_townid').combobox();
            $('input[name^=circles]').click(function()
            {
                SecurityCheckboxs(this);
            });
		}
	});
}


function isChecked(selector)
{
    return $(selector).prop("checked"); 
}


function GetSecurity()
{
    //Идентификаторы кругов
    var circles = {'friends':2,'family':3,'colleagues':5,'kumirs':9,'familiar':4};
    //Итоговые права
    var security =[];
    //Доступно всем
    if ( isChecked('input[name=circles_all]') )
    {
        security.push(0);
        return security;
    }
    //Доступно только мне
    if ( isChecked('input[name=circles_nothing]') )
    {
        security.push(1);
        return security;
    }
    //Доступы по кругам
    for (circle in circles)
    {
        if ( isChecked("input=[name=circles_"+circle+"]") ) security.push(circles[circle]);
    }
    return security;

}

function SaveProfile(){
  var user_fname     = $('#user_fname').val();
  var user_sname     = $('#user_sname').val();
  var user_otchestvo = $('#user_otchestvo').val();  
  var user_day       = $('#user_day').val();
  var user_month     = $('#user_month').val();
  var user_year      = $('#user_year').val();
  var user_year_view = 0; if($('#user_year_view').is(':checked')) {user_year_view = 1;}
  var user_sex       = $('input[name=sex_name_user]:checked').val();
  var user_town      = $("#user_townid :selected").val();
  var user_email     = $('#user_email').val();
  var user_icq       = $('#user_icq').val();
  var user_skype     = $('#user_skype').val();
  var user_url       = $('#user_url').val();
  var education      = $('#education_user').val();
  var career         = $('#career_user').val();
  var about          = $('#about_user').val();
  var marital_status = $('select[name=marital_status]').val();
  var circles = GetSecurity();

  $.ajax({
	url:'/jquery-profile',
	cache:false,
	type: "POST",
	data: {'fname':user_fname,'url':user_url,'sname':user_sname,'otchestvo':user_otchestvo,'icq':user_icq,'skype':user_skype,'email':user_email,'sex':user_sex,'town_id':user_town,'day':user_day,'month':user_month,'year':user_year,'year_view':user_year_view,'circles':circles,'education':education,'career':career,'about':about,'marital_status':marital_status},
	success:function(data){
		$('#msg').html(data);
	}
  });
}
    </script>

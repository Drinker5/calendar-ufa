<?php // print_r($_POST);
function getCountryList()
{
    global $MYSQL;
    $output ='';
    $user_country = $MYSQL->query("SELECT id, name FROM pfx_country WHERE parent=0");
    $output .= "<option value='-1'>Без учета региона</option>";
    if(is_array($user_country))
    {
        foreach($user_country as $key=>$value){
             if($value['id'] == $_SESSION['WP_USER']['country_id'])
                $output .=  "<option value=\"".$value['id']."\" selected=\"selected\"> ".$value['name']."</option>";
             else
                $output .=  "<option value=\"".$value['id']."\"> ".$value['name']."</option>";
        }
    }
    return $output;  
}

?>
<script id="find-friend-action-template" type="text/template">
            <ul class="friend-actions">
                <li>
                    <a href="#"><i class="small-icon icon-add-friend"></i>Добавить в друзья</a>
                </li>
                <li>
                    <a href="#"><i class="small-icon icon-gift"></i>Сделать подарок</a>
                </li>
                <li>
                    <a href="#"><i class="small-icon icon-chat"></i>Начать чат</a>
                </li>
                <li>
                    <a href="#"><i class="small-icon icon-invite"></i>Отправить приглашение</a>
                </li>
            </ul>
        </script>
        
                <div class="title margin">
                    <h2>Поиск друзей</h2>
                </div>
                <div class="find-friend-input fl_l clear">
                    <input type="text" name='name' id='name' placeholder="Введите имя...">
                    <i class="small-icon icon-search"></i>
                </div>
                <!-- <div class="clear"></div> -->
                <div class="search-people-block clear">
                    <table>
                        <tr>
                            <td class="first">
                                <label><strong>Регион</strong></label>
                                <select id="region-select" name='region'>
                                    <?=getCountryList()?>
                                </select>
                                <label>
                                    <input type="checkbox" name='online'>Сейчас на сайте
                                </label>
                            </td>
                            <td class="second">
                                <label><strong>Возраст</strong></label>
                                <select id="age-from" name='age-from'>
                                    <option>от</option>
                                </select>
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                <select id="age-to" name='age-to'>
                                    <option>до</option>
                                </select>
                                    <label>Пол</label>
                                    <label class="sex fl_l">
                                        <input type="checkbox" name='sex_m'/>Мужской
                                    </label>
                                    <label class="sex fl_l">
                                        <input type="checkbox" name='sex_f'/>Женский
                                    </label>
                                
                            </td>
                            <td class="third">
                                <label><strong>Семейное положение</strong></label>
                                <select name='marital-status' id='marital-status'>
                                    
                                </select>
                                <br>
                                <a href="#" id='search_button' class="btn btn-green fl_r">Поиск</a>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="friend-container group">
                </div>
                <div id="loading" style="padding-top:5px; text-align: center; display:none;"><img src="/pic/loader_clock.gif"></div>
                <input type='hidden' value='0' id='stop_rec' name='stop_rec'/>
                <input type='hidden' value='0' id='stop_search' name='stop_search'/>

<script>
$(document).ready(function()
{
    setAgeStart('#age-from','от');
    setAgeStart('#age-to','до');
    setMaritalStatus('#marital-status');
    $('#content select').selectBox('refresh');
    $('#age-from').change(function()
    {
        var age_from = '#age-from';
        var age_to = '#age-to';
        changeAgeFrom(age_from,age_to);
    });
    var offset = 0;
    var count = 30;
    var search = 0;
    var search_offset = 0;
    stop_rec = $('#stop_rec').val();
    stop_search = $('#stop_search').val();
    getRecommendUsers(offset,count);
    $(window).scroll(function()
    {
        if ( ($(document).height() - $(window).height() <= $(window).scrollTop() + 300 ) && +stop_rec == 0 )
        {
            if (search == 0)
            {
                if (+stop_rec == 0)
                {
                    var container = $('.friend-container');
                    $('#loading').show();
                    offset +=count;
                    getRecommendUsers(offset,count);
                    stop_rec = $('#stop_rec').val();
                }
                
            }
            else
            {
                if (+stop_search == 0)
                {
                    var container = $('.friend-container');
                    $('#loading').show();
                    search_offset += count;
                    searchUser(search_offset,count);
                    stop_search = $('#stop_search').val();
                }
            }
            $('.popover-btn').popover();
        }
    });
    $('#search_button').click(function()
    {
        search = 1;
        search_offset = 0;
        stop = 0;
        var container = $('.friend-container');
        $(container).html('');
        $('#loading').show();
        searchUser(search_offset,count);
    });

});
search = {params:{'search_offset':'0','count':'0','sex':'0','name':'','region':'-1','marital_status':'-1','age-from':'1','age-to':'999','online':'0'}};
search.getSex = function()
{
    var sex = 0;
    if ( (isChecked('input[name=sex_m]') && isChecked('input[name=sex_f]')) || (!isChecked('input[name=sex_m]') && !isChecked('input[name=sex_f]')) )
        sex = 0;
    else if (isChecked('input[name=sex_m]'))
        sex = 1;
    else
        sex = 2;
    return sex;
}
search.init = function(search_offset,count)
{
    this.params['search_offset'] = search_offset;
    this.params['count'] = count;
    this.params['sex'] = this.getSex();
    this.params['name'] = $('input[name=name]').val();
    this.params['marital_status'] = $('select[name=marital-status]').val();
    this.params['age-from'] = $('select[name=age-from]').val();
    this.params['age-to'] = $('select[name=age-to]').val();
    this.params['online'] =  (isChecked('input[name=online]'))?'1':'0';
    this.params['region'] = $('select[name=region]').val();
} 
var template =  
    '<div class="friend-item fl_l">'+
    '<div class="bordered medium-avatar fl_l">'+
    '<a href="/{{user_wp}}"><img src="{{photo}}" alt=""></a>'+
    '</div>'+
    '<div class="content wrapped">'+
    '{{&online_status}}'+
    '<span class="name"><a href="/{{user_wp}}">{{firstname}} {{lastname}}</a></span>'+
    '<br>'+
    '<span class="place">{{country_name}} {{town_name}}</span>'+
    '</div>'+
    '<div class="tools_block hide absolute tx_r">'+
        '<span class="fl_l">'+
            '<a href="#" class="add_new_friend opacity_link" data-user="{{user_wp}}" data-name="{{firstname}} {{lastname}}"><i original-title="Добавить в друзья" class="tipN active small-icon icon-add-friend"></i></a>'+
            '<a href="#" class="opacity_link"><i original-title="Сделать подарок" class="tipN active small-icon icon-gift"></i></a>'+
            '<a href="#" class="opacity_link"><i original-title="Написать сообщение" class="tipN active small-icon icon-chat"></i></a>'+
            '<a href="#" class="opacity_link"><i original-title="Пригласить" class="tipN active small-icon icon-invite"></i></a>'+
        '</span>'+
    '</div>'+
    '</div>';
users = [];
user = function()
{
    this.model = {};
    this.template = template;
    this.init = function(data)
    {
        this.model['user_wp'] = data['user_wp'];
        this.model['photo'] = data['photo'];
        this.model['online_status'] = data['online_status'];
        this.model['firstname'] = data['firstname'];
        this.model['lastname'] = data['lastname'];
        this.model['country_name'] = data['country_name'];
        this.model['town_name'] = data['town_name'];
    }
    this.render = function()
    {
        this.html = Mustache.to_html(this.template, this.model);
        return this.html;
    }
}


function searchUser(search_offset,count)
{
    search.init(search_offset,count);
    $.ajax({url:'/jquery-invitefriends',
            type:'POST',
            dataType:'json',
            data:search.params,
            success: function(data)
            {
                var container = $('.friend-container');
                $('#loading').hide();
                var html ='';
                if (data.found =='0'){
                    $(container).html('<p id="msg" style="color:red;">К сожалению, по вашему запросу ничего не найдено. Попробуйте изменить условия поиска и попробуйте снова</p>');
                    return 0;
                }
                var n = data.html.length;
                for (var i = 0; i < n; i++)
                {
                    tmp_user = new user();
                    tmp_user.init(data.html[i]);
                    html += tmp_user.render();
                }
                $(container).append(html);
                $('#stop_search').val(data.stop);
                $('.popover-btn').popover({
                    trigger: 'none',
                    autoReposition: false
                });
                $(".friend-item .find-friend-actions")
                .popover('content', $('#find-friend-action-template').html())
                .popover('setOption', 'position', 'bottom')
                .popover('setOption', 'horizontalOffset', -31)
                .popover('setClasses', 'friend-action-popover');

            }
       });

}
function getRecommendUsers(offset,count)
{
    $.ajax({url:'/jquery-recommendfriends',
            type:'POST',
            dataType:'json',
            data:{
                   offset:offset,
                   count:count
               },
            success: function(data)
            {
                var container = $('.friend-container');
                var html = '';
                $('#loading').hide();
                var n = data.html.length;
                for (var i = 0; i < n; i++)
                {
                    tmp_user = new user();
                    tmp_user.init(data.html[i]);
                    html += tmp_user.render();
                    
                }
                $(container).append(html);
                $('#stop_rec').val(data.stop);
                $('.popover-btn').popover({
                    trigger: 'none',
                    autoReposition: false
                });
                $(".friend-item .find-friend-actions")
                .popover('content', $('#find-friend-action-template').html())
                .popover('setOption', 'position', 'bottom')
                .popover('setOption', 'horizontalOffset', -31)
                .popover('setClasses', 'friend-action-popover');

            }
       });
}

function getAgeList(default_val)
{
    var age_list = [];
    age_list.push("<option value='0'>"+default_val+"</option>");


    for (i=12;i<=65;i++)
    {
        var one_age = "<option value = '"+i+"'>"+i+"</option>";
        age_list.push(one_age);
    }

    return age_list.join(' ');
}
function setAgeStart(selector,default_val)
{
    var age_list = getAgeList(default_val);
    $(selector).html(age_list);
}
function changeAgeFrom(age_from, age_to)
{
    var age_from_val = $(age_from).val();
    var age_to_val = $(age_to).val();
    if (age_to_val < age_from_val)
        $(age_to).selectBox('value',age_from_val);
    
}
function setMaritalStatus(selector)
{
    var marital_status = {'Не выбрано':-1,'Свободные':0,'В отношениях':1,'Помолвленные':2, 'В браке':3, 'Влюбленные':4, 'Все сложно':5, 'В активном поиске':6 };
    var output = '';
    for (status in marital_status)
    {
        output += '<option value="'+marital_status[status]+'">'+status+'</option>';
    }
    $(selector).html(output);
}
function isChecked(selector)
{
    return $(selector).prop("checked"); 
}


</script>
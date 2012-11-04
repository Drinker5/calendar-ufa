<?php
function getCountryList()
{
    global $MYSQL;
    $output ='';
    $user_country = $MYSQL->query("SELECT id, name FROM pfx_country WHERE parent=0");
    if(is_array($user_country))
    {
        foreach($user_country as $key=>$value){
                $output .=  "<option value=\"".$value['id']."\"> ".$value['name']."</option>";
        }
    }
    return $output;  
}
?>

<!DOCTYPE html>
<html>
 <title>Tooeezzy</title>
<head>
 <meta http-equiv="content-type" content="text/html; charset=utf-8" />
 <link href="/css/login.css" rel="stylesheet" type="text/css" />
 <link rel="stylesheet" type="text/css" media="screen, projection" href="/css/reset.css" />
 <link rel="stylesheet" type="text/css" media="screen, projection" href="/css/intro.css" />
 <link href="/css/register.css" rel="stylesheet" type="text/css" />
 <!--[if lt IE 9]>
  <script type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
 <![endif]-->
 <script type="text/javascript" src="/js/respond.js"></script>
 <script type="text/javascript" src="/js/jquery.min.js"></script>
 <script type="text/javascript" src="/js/jquery.parallax.min.js"></script>
 <script type="text/javascript" src="/js/jquery.cycle.js"></script>
 <script type="text/javascript" src="/js/login.js"></script>
 <script type="text/javascript" src="/js/plugins/forms/jquery.uniform.js"></script>
 <script type="text/javascript" src="/js/plugins/forms/jquery.maskedinput.min.js"></script>
 <script type="text/javascript" src="/js/plugins/forms/jquery.chosen.min.js"></script>
 <script type="text/javascript" src="/js/plugins/ui/jquery.tipsy.js"></script>
 <script type="text/javascript" src="/js/files/functions.js"></script>

 <!--[if lte IE 8]>
  <link rel="stylesheet" type="text/css" href="css/ie.css" />
 <![endif]-->

<script>
  jQuery(document).ready(function(){
    jQuery('.logo2, .iphone, .gift')
    .parallax({
      mouseport: jQuery('.parallax'),
      yparallax: false,
      xparallax: '10px'
    });
    jQuery('.iphone')
.parallax({}, {xparallax: '40px'});
  });
  </script>


<script type="text/javascript">
$(document).ready(function() {
    $('.iphone').cycle();
});
</script>

</head>

<body>
<div id="ascensorBuilding">
<section>
 <div id="container">
  <a class="logo"></a>
  
  <div class="parallax">
   <span class="logo2"></span>
   <span class="gift"></span>
   <div class="iphone">
      <img src="/pic/intro/slide1.png">
      <img src="/pic/intro/slide2.png">
      <img src="/pic/intro/slide3.png">
   </div>
  </div>

  <span class="text"></span>
  
  <div class="menu">
    <ul>
     <a class="ascensorLink ascensorLink3" href="javascript:;"><li class="m-login f1">Войти<i></i></li></a>
     <a class="ascensorLink ascensorLink4" href="javascript:;"><li class="m-facebook f2">Войти через Facebook</li></a>
     <a class="ascensorLink ascensorLink2" href="javascript:;"><li class="m-register f3"><i></i>Регистрация</li></a>
    </ul>
  </div>
  
  <a href="javascript:;" class="appstore"></a>
  
  <div class="columns">

   <div class="col cl">
    <h2>Clean</h2>
    <h3>webdesign with structure</h3>
    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
   </div>

   <div class="col cc">
    <h2>Clean</h2>
    <h3>webdesign with structure</h3>
    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
   </div>

   <div class="col last cr">
    <h2>Clean</h2>
    <h3>webdesign with structure</h3>
    <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum.</p>
   </div>

  </div>
 </div>
 
<footer class="f-i">
   <nav>
    <ul>
     <li><a href="javascript:;">О нас</a></li>
     <li><a href="javascript:;">Условия использования</a></li>
     <li><a href="javascript:;">Вакансии</a></li>
     <li><a href="javascript:;">Партнерство</a></li>
     <li><a href="javascript:;">Контакты</a></li>
     <li><a href="javascript:;">Помощь</a></li>
    </ul>
   </nav>
   

   <div class="social">
    <ul>
     <li><a href="javascript:;" class="first fb"></a></li>
     <li><a href="javascript:;" class="tw"></a></li>
     <li><a href="javascript:;" class="gp"></a></li>
    </ul>
   </div> 

</footer>

</section>

<section>
   <span class="ascensorLink ascensorLink1 back top">:</span>
   
<div id="wrapper">
   <a href="javascript:;" class="logo_reg"></a>
   <div class="clear"></div>
   <div class="forms" id='reg_form'>
      <span class="title">Регистрация</span>
      <fieldset>
         <input type="text" class="half fl_l tipE" original-title="Как вас, сударь, величать?" name="firstname" placeholder="Имя" />
         <input type="text" class="half fl_r ie-r tipW" original-title="Фамилiя?" name="lastname" placeholder="Фамилия" />
         <input type="text" class="full clear loginEmail tipW" original-title="Телеграфъ?" name="email" placeholder="Электронная почта" />
         <input type="password" class="full clear loginPassword tipW" original-title="Не менее 5 символовъ" name="password" placeholder="Пароль" />
         <span class="t-plus">+</span> <input type="text" class="t-code clear maskCode tipE" original-title="Код страны" name="phone_code" placeholder="Код" value="">
         <input type="text" class="t-phone clear maskPhone loginPhone tipW" original-title="Телефонъ?" name="phone" placeholder="Номер телефона" value=""/>
         <div class="searchDrop half m1 fl_l">
               <select data-placeholder="Страна" class="select" name='country'>
                  <option value=""></option>
                <?=getCountryList()?>
               </select>
         </div>
         <div class="searchDrop half fl_l no-m">
               <select data-placeholder="Город" class="select" name='town'>
               </select>
         </div>
         
         <div class="sex clear">Пол:
            <div id="uniform-radio1" class="i-b"><input type="radio" id="radio1" name="sex" checked="checked" style="opacity: 0; "><label for="radio1">Мужской</label></div>
            <div id="uniform-radio2" class="i-b"><span class=""><input type="radio" id="radio2" name="sex" style="opacity: 0; "></span><label for="radio2">Женский</label></div>
         </div>
         
         <span class="bday gray center">День рождения <a class="gray" href="javascript:;">(Почему?)</a></span>
         <div class="clear"></div>
         
         <div class="fl_l formRow searchDrop day">
               <select data-placeholder="День" class="select" name='birthday_day'>
                  <option value=""></option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                  <option value="11">11</option>
                  <option value="12">12</option>
                  <option value="13">13</option>
                  <option value="14">14</option>
                  <option value="15">15</option>
                  <option value="16">16</option>
                  <option value="17">17</option>
                  <option value="18">18</option>
                  <option value="19">19</option>
                  <option value="20">20</option>
                  <option value="21">21</option>
                  <option value="22">22</option>
                  <option value="23">23</option>
                  <option value="24">24</option>
                  <option value="25">25</option>
                  <option value="26">26</option>
                  <option value="27">27</option>
                  <option value="28">28</option>
                  <option value="29">29</option>
                  <option value="30">30</option>
                  <option value="31">31</option>
               </select>
         </div>
         <div class="fl_l formRow searchDrop month">
               <select data-placeholder="Месяц" class="select " name='birthday_month'>
                  <option value=""></option>
                  <option value="1">Январь</option>
                  <option value="2">Февраль</option>
                  <option value="3">Март</option>
                  <option value="4">Апрель</option>
                  <option value="5">Май</option>
                  <option value="6">Июнь</option>
                  <option value="7">Июль</option>
                  <option value="8">Август</option>
                  <option value="9">Сентябрь</option>
                  <option value="10">Октябрь</option>
                  <option value="11">Ноябрь</option>
                  <option value="12">Декабрь</option>
               </select>
         </div>
         <div class="fl_l formRow third searchDrop year">
               <select data-placeholder="Год" class="select" name='birthday_year'>
               </select>
         </div>
      </fieldset>
      <div class="reg center">
      <span class="clear">Нажимая кнопку "Зарегистрироваться",<br>вы принимаете <a class="blue line" href="javascript:;">Условия пользования сервисом</a>.</span>
      <input type="submit" name="reg_submit" value="Зарегистрироваться" class="buttonM bBlue" />
        <div class='reg_error'>
        </div> 
      </div>
   </div>

</div>
</section>

<section>
   <span class="ascensorLink ascensorLink1 back left">(</span>
   <div class="loginWrapper">

	<!-- Current user form -->
    <form action="index.html" id="login">
        <div class="loginPic">
            
            <span>Войти</span>
        </div>
        
        <input type="text" name="login" placeholder="Email или номер мобильного" class="loginEmail" />
        <input type="password" name="password" placeholder="Пароль" class="loginPassword" />
        
        <div class="logControl">
            <div class="memory flip"><i class="forgot"></i><span>Забыл пароль</span></div>
            <input type="submit" name="login_submit" value="Вход" class="buttonM bBlue" />
            <div class="clear"></div>
            <div class='login_error'></div>
        </div>
    </form>
    
    <!-- New user form -->
    <form action="index.html" id="recover">
        <div class="loginPic">
              <div class="loginActions">
                <div><a href="#" title="" class="logback flip"></a></div>
              </div> 
             <span>Забыл пароль</span>
            
        </div>
            
        <input type="text" name="forget_email" placeholder="Email" class="loginEmail" />
        
        <div class="logControl center ">
            <input type="submit" name="forget_password" value="Восстановить" class="buttonM bBlue center" />
            <div class='forget_error'></div>
        </div>
    </form>


</div>
</section>

<section>
   <span class="ascensorLink ascensorLink1 back bottom">;</span>
   <img src="/pic/facebook.png" width="256" height="256" style="position: absolute; margin-top: -128px; margin-left: -128px; top: 50%; left: 50%; opacity: 0.9;">
</section>

 <script src="/js/ascensor/plugins.js"></script>
 <script src="/js/ascensor/ascensor.js"></script>
 <script src="/js/ascensor/script.js"></script>
 <script>
 $(document).ready(function()
 {
  //Логин юзера - щелчок по кнопке submit
    $('input[name=login_submit]').click(function()
    {
      user_login(this);
      return false;
    });
    $('input[name=forget_password]').click(function()
    {
      forget_password(this);
      return false;
    });
    $('select[name=country]').change(function()
    {
      SelTowns($(this).val());
    });
    $('select[name=birthday_year]').html(getYears());
    $('select[name=birthday_year]').trigger('liszt:updated');
    $('#reg_form input[name=reg_submit]').click(function()
    {
      User.initial();
      User.getErrors();
      deleteError();
      if (User.errors.length > 0)
      {
        $('div.reg_error').html('Допущены ошибки в заполнении формы.</br> Ошибочные поля выделены цветом');
        viewError(User.errors);
        $('div.reg_error').css({'color':'red'});
      }
      else
      {
        var userinfo = JSON.stringify(User.userinfo);
        $.ajax({
          url:'/jquery-register',
          cache:false,
          dataType:'json',
          type: "POST",
          data:{userinfo:userinfo},
          success:function(data){
            if (data['status']<0){
              $('div.reg_error').html(showError(data.errors));
              $('div.reg_error').css({'color':'red'});
            }
            else
            {
              $('div.reg_error').html('<p class="success_reg">Ваша регистрация успешно завершена! На указанный email выслано письмо со ссылкой на активацию</p>');
              $('div.reg_error').css({'color':'green'});
              setTimeout("window.location = '/'",4000);
            }

          }
        });

      }
    });

 });
function forget_password(element)
{
  var email = $('#recover forget_email').val();
  $.ajax({
    url:'/jquery-forgot',
    cache:false,
    type:'POST',
    data: {email:email},
    success:function(result){
      $('div.forget_error').html('<p class="simple_forget_error">'+result+'</p>');
    }
  });
}
 function user_login(element)
 {
  var login = $('#login input[name=login]').val();
  var pass = $('#login input[name=password]').val();
  deleteError();
  $.ajax({
    url:'/jquery-login',
    cache:false,
    type:'POST',
    data: {email:login,passw:pass},
    success:function(result){
      if(result == 'ok'){
       location.href='/';
      } else {
        loginError(result);
        $('div.login_error').html('<p>'+result+'</p>');
      }
    }
  });
 }
function loginError(text)
{
  var errors = {'Некорректный email':'#login input[name=login]','Поля не должны быть пустыми':'#login input[name=login], #login input[name=password]', 'Нет такого пользователя':'#login input[name=login]', 'Пароль указан не верно':'#login input[name=password]','Заполните все поля':'#login input[name=login], #login input[name=password]'};
  $(errors[text]).addClass('error');
}
function viewError(list_of_error)
{
  var len = list_of_error.length;
  for (i=0;i<len;i++)
  {
    var type = list_of_error[i][1];
    var selector = list_of_error[i][0];
    if (type == 'input')
      $(selector).addClass('error');
    else
    {
      var par = $(selector).parent().parent();
      $('a',par).addClass('error');
    }
  }
}

function deleteError()
{
  $('.error').removeClass('error');
}

function showError(list_of_error){
  var html = '';
  var len = list_of_error.length;
  for (i=0;i<len;i++)
  {
    html += '<p class="simple_error">'+list_of_error[i]+'</p>';
  }
  return html;
}

function getYears()
{
  var date = new Date();
  var stop = date.getFullYear() - 12;
  var start = date.getFullYear() - 80;
  var result = '<option value=""></option>';
  for (var i=stop;i>=start;i--)
  {
    result += '<option value="'+i+'">'+i+'</option>';
  }
  return result;
}

 function SelTowns(country_id){
  $.ajax({
      url:'/jquery-seltowns',
    cache:false,
    async:false,
    type: "POST",
    data: {country_id:country_id,default_select:1},
    success:function(data){
      var town_select = $('select[name=town]');
      $(town_select).html(data);
      $('select[name=town]').trigger('liszt:updated');
      $.uniform.update(town_select);

    }
  });
}

function isValidEmail(email) {
   var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,6})$/;
   return reg.test(email);
}

function isEmpty(val)
{
  return (val=='' || val == '0' || val == null || val == undefined)?true:false;
}
User = {};
User.userinfo = {firstname:'', lastname:'', password:'', email:'', phone:'',country:'', town:'', sex:'', birthday_day:'', birthday_month:'', birthday_year:''};
//User.errorsMessage = {firstname_empty:'Не заполнено имя', lastname_empty:'Не заполнена фамилия', email_empty:'Не заполнен email',  password_empty:'Не заполнен пароль', phone_empty:'Не заполнен номер телефона',country_empty:'Не указана страна',town_empty:'Не указан город',sex_empty: 'Не указан пол', birthday_day_empty:'Не указан день рождения', birthday_month_empty:'Не указан месяц рождения', birthday_year_empty:'Не указан год рождения',mail_not_valid:'Введенный email некорректен',password_short:'Длина пароля минимум 5 символов'};
User.errorsMessage = {firstname_empty:['input[name=firstname]','input'], lastname_empty:['input[name=lastname]','input'], email_empty:['input[name=email]','input'],  password_empty:['input[name=password]','input'], phone_empty:['input[name=phone]','input'], phone_code_empty:['input[name=phone_code]','input'], country_empty:['select[name=country]','select'],town_empty:['select[name=town]','select'], birthday_day_empty:['select[name=birthday_day]','select'], birthday_month_empty:['select[name=birthday_month]','select'], birthday_year_empty:['select[name=birthday_year]','select'],mail_not_valid:['input[name=email]','input'],password_short:['input[name=password]','input']};
User.errors = [];


User.emptyCheck = function(){
  var postfix = '_empty';
  for (key in this['userinfo'])
  {
    if (isEmpty(this['userinfo'][key]))
      this.errors.push(this['errorsMessage'][key+postfix]); 
  }
};
User.mailCheck = function()
{
  (isValidEmail(this.userinfo.email))?'':this.errors.push(this.errorsMessage['mail_not_valid']);
}
User.passwordCheck = function()
{
  (this.userinfo.password.length < 5)?this.errors.push(this.errorsMessage['password_short']):'';
}
User.initial = function(){
  var parent = $('#reg_form');
  this.userinfo.firstname = $('input[name=firstname]',parent).val();
  this.userinfo.lastname = $('input[name=lastname]',parent).val();
  this.userinfo.password = $('input[name=password]',parent).val();
  this.userinfo.email = $('input[name=email]',parent).val();
  this.userinfo.phone = $('input[name=phone]',parent).val();
  this.userinfo.phone_code = $('input[name=phone_code]',parent).val();
  this.userinfo.country = $('select[name=country]',parent).val();
  this.userinfo.town = $('select[name=town]',parent).val();
  this.userinfo.birthday_day = $('select[name=birthday_day]',parent).val();
  this.userinfo.birthday_month = $('select[name=birthday_month]',parent).val();
  this.userinfo.birthday_year = $('select[name=birthday_year]',parent).val();
  this.userinfo.sex = ($('input[name=sex]:first',parent).prop('checked'))?1:2;
};
User.getErrors = function(){
  this.errors = [];
  this.emptyCheck();
  this.mailCheck();
  this.passwordCheck();
};

 </script>

</div>
</body>
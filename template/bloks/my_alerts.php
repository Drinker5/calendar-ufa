<?php 
$GLOBALS['PHP_FILE'] = __FILE__;
$GLOBALS['FUNCTION'] = __FUNCTION__;
if(isset($_POST['save'])){
    if(isset($_POST['text']))$alerts['text']=1;else $alerts['text']=0;
    if(isset($_POST['email']))$alerts['email']=1;else $alerts['email']=0;
    if(isset($_POST['sound']))$alerts['sound']=1;else $alerts['sound']=0;

    $alerts['email_text'] = (is_email($_POST['email_text']))?$_POST['email_text']:'';

    if(isset($_POST['x1']))$alerts['x1']=1;else $alerts['x1']=0;
    if(isset($_POST['x2']))$alerts['x2']=1;else $alerts['x2']=0;
    if(isset($_POST['x3']))$alerts['x3']=1;else $alerts['x3']=0;
    if(isset($_POST['x4']))$alerts['x4']=1;else $alerts['x4']=0;
    if(isset($_POST['x5']))$alerts['x5']=1;else $alerts['x5']=0;
    if(isset($_POST['x6']))$alerts['x6']=1;else $alerts['x6']=0;
    if(isset($_POST['x7']))$alerts['x7']=1;else $alerts['x7']=0;
    if(isset($_POST['x8']))$alerts['x8']=1;else $alerts['x8']=0;
    $count = $MYSQL->query("select count(*) from  pfx_users_alerts where user_wp=".$_SESSION['WP_USER']['user_wp']);
    if((int)$count[0]['count']==0)
        {
            $insert=$MYSQL->query("insert into pfx_users_alerts(user_wp) values(".$_SESSION['WP_USER']['user_wp'].")");
        }
        $query="update pfx_users_alerts set user_wp=user_wp";
        foreach ($alerts as $k=>$v)if($k!='email_text')$query.=','.$k.'='.$v;else $query.=','.$k."='".$v."' ";
        $query.=' where user_wp='.$_SESSION['WP_USER']['user_wp'];
    $update = $MYSQL->query($query);
    //Вывод сообщения об успешном сохранении настроек
    $msg = "<font style=\"color:green\">Настройки оповещений успешно сохранены</font>";
}
else 
{
    $select=$MYSQL->query("SELECT * FROM pfx_users_alerts where user_wp=".(int)$_SESSION['WP_USER']['user_wp']);
    $alerts = array();
    if (isset( $select[0]) )
        foreach ($select[0] as $k=>$v)
            $alerts[$k]=$v;
}

?>

<div class="title margin">
	<h2>Мои оповещения</h2>
</div>
<div class="nav-panel group">
    <ul class="fl_r right">
        <li class="opacity_link"><a href="/my-profile">Мой профиль</a></li>
        <li class="opacity_link"><a href="/my-phones">Телефон</a></li>
        <!--<li class="opacity_link"><a href="/my-wallets">Счет</a></li>-->
        <li class="opacity_link active"><a href="/my-alerts">Оповещения</a></li>
        <li class="opacity_link"><a href="/my-avatar">Изменить аватар</a></li>
        <li class="opacity_link"><a href="/my-password">Изменить пароль</a></li>
        <!--<li class="opacity_link"><a href="/my-subscribes">Подписки</a></li>-->
    </ul>
</div>
<div class="tools_block" style="width:auto;">
    <p id='msg'><?=@$msg?></p>
    <form action="/my-alerts" method="POST">
        <div class="tools">
            <div id="uved_tools">
                <div class="left_sidebar">
                    <h1>Моментальные оповещения на сайте</h1>
                    <label><input name="text" type="checkbox" <?=(@$alerts['text']==1?' checked="checked" ':'')?>/>Показывать текст сообщений</label>
                    <label><input name="sound" type="checkbox"<?=(@$alerts['sound']==1?' checked="checked" ':'')?>/>Включить звуковые оповещения</label><br />
                    <h1>Email оповещения</h1>
                    <label><input name="email" type="checkbox"<?=(@$alerts['email']==1?' checked="checked" ':'')?>/>Посылать Email оповещения на aдрес:</label>
                    <input name="email_text" class="input_block" id="phone" type="text" value="<?=(@strlen($alerts['email_text'])>0)?$alerts['email_text']:$_SESSION['WP_USER']['email']?>"/>  
                </div>
                <div class="right_sidebar">
                    <h1>Выбрать события, при которых будут email оповещения:</h1>
                    <label for=""><input name="x1"  type="checkbox"<?=(@$alerts['x1']==1?' checked="checked" ':'')?>/>Оповещать о всех событиях</label>
                    <label for=""><input name="x2" type="checkbox"<?=(@$alerts['x2']==1?' checked="checked" ':'')?>/>Личные сообщения</label>
                    <label for=""><input name="x3" type="checkbox"<?=(@$alerts['x3']==1?' checked="checked" ':'')?>/>Запрос на дружбу</label>
                    <label for=""><input name="x4" type="checkbox"<?=(@$alerts['x4']==1?' checked="checked" ':'')?>/>Получение подарка</label>
                    <label for=""><input name="x5" type="checkbox"<?=(@$alerts['x5']==1?' checked="checked" ':'')?>/>Приглашение</label>
                    <label for=""><input name="x6" type="checkbox"<?=(@$alerts['x6']==1?' checked="checked" ':'')?>/>Приближающиеся даты календаря</label>
                    <label for=""><input name="x7" type="checkbox"<?=(@$alerts['x7']==1?' checked="checked" ':'')?>/>Мои подписки</label>
                    <label for=""><input name="x8" type="checkbox"<?=(@$alerts['x8']==1?' checked="checked" ':'')?>/>Комментарии к моим событиям</label>
                </div>
                <div class="cleared"></div>
            </div>
        </div>
        <button name="save" type="submit" class="btn btn-green fl_r">Сохранить</button>
    </form>
</div>
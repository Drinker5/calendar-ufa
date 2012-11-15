<?php
	$who=$USER->Info_min($_URLP[2],75,75);
	$_SESSION['may_bring']='yes';
?>
<div class="title margin"><h2>Подарки</h2></div>
<div class="nav-panel no-margin-bottom group"></div>

<?php
	if(is_array($who)){
		//echo PreArray($AKCIA_INFO);
		$shop=$SHOP->Info($AKCIA_INFO['shop_id'],75,75);
		//echo PreArray($shop);

?>
<div class="payment-whon-where-what">
    <div class="chosen_friend">
        <p class="c_title">Кому</p>
        <p class="c_name"><?=$who['firstname']?><br /><?=$who['lastname']?></p>
        <div class="round_mask">
           <span class="r_m"></span>
           <img src="<?=$who['photo']?>">
        </div>
    </div>

    <div class="arrow"><img src="pic/next_arrow.png" alt=""></div>

    <div class="chosen_friend">
        <p class="c_title">Где</p>
        <p class="c_name"><?=$AKCIA_INFO['shopname']?></p>
        <div class="round_mask">
           <span class="r_m"></span>
           <img src="<?=$shop['logo']?>">
        </div>
    </div>

    <div class="arrow"><img src="pic/next_arrow.png" alt=""></div>

    <div class="chosen_friend">
        <p class="c_title">Что</p>
        <p class="c_name"><?=$AKCIA_INFO['header']?></p>
        <div class="round_mask">
           <span class="r_m"></span>
           <img src="<?=$AKCIA_INFO['photo']?>">
        </div>
    </div>

    <div class="summ-block">
        <span class="title">конечная сумма</span>
        <span class="summ"><?=$AKCIA_INFO['amount']/100?> <?=$AKCIA_INFO['currency']?></span>
    </div>

    <div class="payed-30-days"><div class="arrow"></div>Срок действия подарочного купона 30 дней с момента оплаты</div>

</div> <!-- /.payment-whon-where-what -->

 <div class="payment-write-message">

    <div class="write">
        <button class="green-button message-link popover-btn" id="message-link"><i class="small-icon icon-message"></i>Написать сообщение</button>
    </div>

    <div class="show group">
        <span class="message-text fl_l"></span>
        <a href="#" original-title="редактировать" class="tipE fl_r edit-message-link popover-btn" ><i class="small-icon icon-edit"></i></a>
    </div>

</div> <!-- /.payment-write-message --> 

<div class="nav-panel group geo payment">
    <span class="geo-arrow-down"></span>
    <span class="hint payment">Выбери удобный для тебя способ оплаты!</span>
</div> <!-- /.nav-panel -->

<div class="payment-methods">
    <div class="line group">
        <div class="title fl_l">Банковской картой</div>
        <div class="systems-icons fl_l group">
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="visa" class="fl_l tipN"><i class="payment-icon visa"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="mastercard" class="fl_l tipN"><i class="payment-icon mastercard"></i></a>
        </div> <!-- /.systems-icons -->
    </div> <!-- /.line -->

    <div class="line group">
        <div class="title fl_l">Электронные деньги</div>
        <div class="systems-icons fl_l">
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="yandex" class="fl_l tipN"><i class="payment-icon yandex"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="webmoney" class="fl_l tipN"><i class="payment-icon webmoney"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="kiwi" class="fl_l tipN"><i class="payment-icon kiwi"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="rbk" class="fl_l tipN"><i class="payment-icon rbk"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="mail" class="fl_l tipN"><i class="payment-icon mail"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="intelectmoney" class="fl_l tipN"><i class="payment-icon intelectmoney"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="w1" class="fl_l tipN"><i class="payment-icon w1"></i></a>
        </div> <!-- /.systems-icons -->
    </div> <!-- /.line -->

    <div class="line group">
        <div class="title fl_l">Платежные терминалы</div>
        <div class="systems-icons fl_l">
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="kiwi" class="fl_l tipN"><i class="payment-icon kiwi"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="eleksnet" class="fl_l tipN"><i class="payment-icon eleksnet"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="esgp" class="fl_l tipN"><i class="payment-icon esgp"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="pinpay" class="fl_l tipN"><i class="payment-icon pinpay"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="unikassa" class="fl_l tipN"><i class="payment-icon unikassa"></i></a>
        </div> <!-- /.systems-icons -->
    </div> <!-- /.line -->

    <div class="line group">
        <div class="title fl_l">Салоны связи</div>
        <div class="systems-icons fl_l">
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="evroset" class="fl_l tipN"><i class="payment-icon evroset"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="svyznoi" class="fl_l tipN"><i class="payment-icon svyznoi"></i></a>
        </div> <!-- /.systems-icons -->
    </div> <!-- /.line -->

    <div class="line group">
        <div class="title fl_l">Банкоматы</div>
        <div class="systems-icons fl_l">
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="evroset" class="fl_l tipN"><i class="payment-icon bankom1"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="svyznoi" class="fl_l tipN"><i class="payment-icon bankom2"></i></a>
        </div> <!-- /.systems-icons -->
    </div> <!-- /.line -->

    <div class="line group">
        <div class="title fl_l">Мобильный телефон</div>
        <div class="systems-icons fl_l">
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="mts" class="fl_l tipN"><i class="payment-icon mts"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="megafon" class="fl_l tipN"><i class="payment-icon megafon"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="beeline" class="fl_l tipN"><i class="payment-icon beeline"></i></a>
        </div> <!-- /.systems-icons -->
    </div> <!-- /.line -->

    <div class="line group">
        <div class="title fl_l">Денежные переводы</div>
        <div class="systems-icons fl_l">
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="contact" class="fl_l tipN"><i class="payment-icon contact"></i></a>
        </div> <!-- /.systems-icons -->
    </div> <!-- /.line -->

    <div class="line group">
        <div class="title fl_l">Интернет банкинг</div>
        <div class="systems-icons fl_l">
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="alfa-bank" class="fl_l tipN"><i class="payment-icon alfa-bank"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="faktura.ru" class="fl_l tipN"><i class="payment-icon faktura"></i></a>
            <a href="/gift-<?=$_URLP[1]?>-<?=$_URLP[2]?>-end" original-title="PSB" class="fl_l tipN"><i class="payment-icon promsvyz"></i></a>
        </div> <!-- /.systems-icons -->
    </div> <!-- /.line -->

</div> <!-- /.payment-methods -->

<script id="popover-message" type="text/template">
    <div class="message-popup">
        <div class="group">
            <div class="place-info wrapped">
                <textarea class="bordered payment-message-textarea" placeholder="Текст сообщения..."></textarea>
            </div>
        </div>
        <div class="group">
            <div class="place-info wrapped">
                <label>
                    <input type="checkbox" />
                    Так же показать сообщение в ленте новостей
                </label>
            </div>
        </div>
        <div class="tx_r">
            <button class="btn btn-green payment-message-submit">Готово</button>
        </div>
    </div>
</script>
<script id="popover-message-edit" type="text/template">
    <div class="message-popup">
        <div class="group">
            <div class="place-info wrapped">
                <textarea class="bordered payment-message-edit-textarea" placeholder="Текст сообщения..."></textarea>
            </div>
        </div>
        <div class="group">
            <div class="place-info wrapped">
                <label>
                    <input type="checkbox" />
                    Так же показать сообщение в ленте новостей
                </label>
            </div>
        </div>
        <div class="tx_r">
            <button class="btn btn-green payment-message-edit">Готово</button>
        </div>
    </div>
</script>
<script>
$(document).ready(function () {
    $('.payment-message-submit').live('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $('.message-link').popover('hide');

        $('.payment-write-message .write').hide();
        $('.payment-write-message .show').show().attr('display','block');
        $('.payment-write-message .message-text').text($('.payment-message-textarea').val());
        $('.payment-message-edit-textarea').val($('.payment-message-textarea').val());
       
    });

    $('.payment-message-edit').live('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        $('.edit-message-link').popover('hide');

        $('.payment-write-message .write').hide();
        $('.payment-write-message .show').show().attr('display','block');
        $('.payment-write-message .message-text').text($('.payment-message-edit-textarea').val());
       
    });

    //  $('.edit-message-link').live('click', function(event) {
    //     event.preventDefault();
    //     event.stopPropagation();
    //     // $('#message-link').popover('show');

    //     // if ($('#message-link').popover('getData').popover.is(':hidden')) {
    //     //     $('#message-link').popover('show');
    //     // }
    //     // else {
    //     //     $('#message-link').popover('hide');
    //     // }
        
    //     // return false;
    // });
});
// $(".switch").click(function () {
// $(".toggle").fadeToggle('fast');
// });

// $(".switch2").click(function () {
// $(".toggle2").fadeToggle('fast');
// });

// $(".n-switch").click(function () {
// $(".n-switch span").toggle();
// });

// $(".switch3").click(function () {
// $(".toggle3").fadeToggle('fast');
// });

$('.tabs ul li').click(function()
{
  $('.tabs ul li').removeClass('active');
  $(this).addClass('active');
});

</script>

<?php
	}
?>
jQuery(document).ready(function($) {
    var tmoutID;

    $('.profile-bottom .status-wrap .icon-wrap').hover(
        function() {
            clearTimeout(tmoutID);
            $(this)
                .parent()
                .stop(true, true)
                .find('.status-change')
                .animate(
                    {top: '-100px'},
                    {queue: false, duration: 500}
                )
                .fadeIn('slow');
        },
        function() {
            var $this = $(this);

            tmoutID = setTimeout(
                            function() {
                                $this
                                    .parent()
                                    .find('.status-change')
                                    .fadeOut('fast', function() {$('.status-change').css('top', '-150px');});
                            },
                            400
                      );

        }
    );

    $('.status-change').on('click', 'li', function() {
        var that=$(this), status=that.data('class');
        $.ajax({
            type : "POST",
            url  : "/jquery-chat-status",
            data :'type=switch&status='+status,
            cache: false,
            success:function(data){
                that.addClass('active').siblings().removeClass('active');
                $('.icon-wrap > .status-icon').removeClass().addClass('status-icon '+status);
            },
        });
    });

    $('.popover-btn').popover({
        trigger: 'none',
        autoReposition: false,
        stopChildrenPropagation: false,
        hideOnHTMLClick: true
    });

    $('#add-friend')
        .popover('content', $('#add-friend-template').html())
        .popover('setOption', 'horizontalOffset', 5)
        .popover('setOption', 'verticalOffset', -2)
        .popover('setOption', 'position', 'right');

    $(".profile-avatar .info-icon")
        .popover('content', $('#personal-info-template').html())
        .popover('setOption', 'position', 'right')
        .popover('setOption', 'verticalOffset', 55)
        .popover('setOption', 'horizontalOffset', 10)
        .popover('setClasses', 'personal-info-popover');

    $(".friend-item .subscribe-actions")
        .popover('content', $('#subscriber-action-template').html())
        .popover('setOption', 'position', 'bottom')
        .popover('setOption', 'horizontalOffset', -31)
        .popover('setClasses', 'friend-action-popover');

    $("#content .wish-edit")
        .popover('content', $('#wish-edit-template').html(), true)
        .popover('setOption', 'position', 'bottom')
        .popover('setOption', 'horizontalOffset', -160)
        .popover('setClasses', 'wish-edit-popover');

    $(".friend-item .actions")
        .popover('content', $('#friend-action-template').html())
        .popover('setOption', 'position', 'bottom')
        .popover('setOption', 'horizontalOffset', -31)
        .popover('setClasses', 'friend-action-popover');

    $(".friend-item .my-friend-actions")
        .popover('content', $('#my-friend-action-template').html(), true)
        .popover('setOption', 'position', 'bottom')
        .popover('setOption', 'horizontalOffset', -56)
        .popover('setClasses', 'friend-action-popover');

    $(".friend-item .find-friend-actions")
        .popover('content', $('#find-friend-action-template').html(), true)
        .popover('setOption', 'position', 'bottom')
        .popover('setOption', 'horizontalOffset', -31)
        .popover('setClasses', 'friend-action-popover');

    $(".friend-item .find-friend-actions-exist")
        .popover('content', $('#find-friend-action-template-exist').html(), true)
        .popover('setOption', 'position', 'bottom')
        .popover('setOption', 'horizontalOffset', -31)
        .popover('setClasses', 'friend-action-popover');

    $('.small-avatar i')
        .popover('content', $('#common-actions').html())
        .popover('setOption', 'horizontalOffset', -39)
        .popover('setOption', 'verticalOffset', 14)
        .popover('setClasses', 'personal-settings-popover')
        .popover('setOption', 'position', 'bottom');

    $('.thank-link')
        .popover('content', $('#popover-thank').html(), true)
        .popover('setOption', 'position', 'bottom');

    $('.message-link')
        .popover('content', $('#popover-message').html(), true)
        .popover('setOption', 'position', 'right')
        .popover('setOption', 'verticalOffset', 63)
        .popover('setOption', 'horizontalOffset', -136)
        .popover('setClasses', 'payment-message');

    $('.edit-message-link')
        .popover('content', $('#popover-message-edit').html(), true)
        .popover('setOption', 'position', 'right')
        .popover('setOption', 'verticalOffset', 0)
        .popover('setOption', 'horizontalOffset', 6)
        .popover('setClasses', 'payment-message edit');
        
    $('.all-info-link')
        .popover('content', $('#gift-all-info-template').html(), true)
        .popover('setOption', 'position', 'bottom');

    $('.subscr_content .name, .wish-content .name')
        .popover('content', $('#subscribe-info').html(), true)
        .popover('setOption', 'position', 'bottom');

    $('.info_right .name')
        .popover('content', $('#subscribe-info-2').html(), true)
        .popover('setOption', 'position', 'bottom');

    $('#uved_tools .settings-notify-link')
        .popover('content', $('#settings-notification-template').html())
        .popover('setOption', 'position', 'bottom');

    $('.photoalbum .actions')
        .popover('content', $('#my-fotoalbum-template').html(), true)
        .popover('setOption', 'position', 'bottom');
		
		$('.album-descriptions .actions')
        .popover('content', $('#my-fotoalbum-template').html(), true)
		.popover('setOption', 'horizontalOffset', -4)
        .popover('setOption', 'verticalOffset', 8)
        .popover('setOption', 'position', 'bottom');

    $('.photoalbum .actionscomm')
        .popover('content', $('#album-can-comment-template').html(), true)
        .popover('setOption', 'position', 'bottom');

    $('.gift-search-block .big-category-icon')
        .popover('content', $('#gifts-categories').html(), true)
        .popover('setOption', 'horizontalOffset', -2)
        .popover('setOption', 'verticalOffset', 0)
        .popover('setOption', 'position', 'bottom');
        
    $('.show_info')
        .popover('content', $('#gift_information').html(), true)
        .popover('setOption', 'horizontalOffset', -285)
        .popover('setOption', 'verticalOffset', -1)
        .popover('setOption', 'position', 'right');

    $('.currency_info')
        .popover('content', $('#currency_information').html(), true)
        .popover('setOption', 'horizontalOffset', 5)
        .popover('setOption', 'verticalOffset', -1)
        .popover('setOption', 'position', 'right');

    $('.gift-exchange-button')
        .popover('content', $('#gift-exchange-list').html())
        .popover('setOption', 'horizontalOffset', -3)
        .popover('setOption', 'verticalOffset', 5)
        .popover('setOption', 'position', 'bottom');
		
	$('.circle-icon-favorite')
        .popover('content', $('#favorite-place').html())
        .popover('setOption', 'horizontalOffset',-6)
        .popover('setOption', 'verticalOffset', 42)
        .popover('setOption', 'position', 'right')
        .popover('setClasses', 'favorite-place-popover');        


    $('body').on('click', '.popover-btn', function(e) {
        var $this = $(this);
        $('.popover-btn').not($(this)).popover('hide');

        if ($(this).popover('getData').popover.is(':hidden')) {
            $(this).popover('show');
        }
        else {
            $(this).popover('hide');
        }

        return false;
    });

    $('#content').on('click', '.toggle-control', function(e) {
        $(this)
            .closest('.toggle-stop')
            .find('.toggle-content')
            .slideToggle();

        return false;
    });

    $('#content').on('click', '.toggle-control-2', function(e) {
        $(this)
            .closest('.toggle-change')
            .find('.c-control-text')
                .toggleClass('imp-hide')
                .end()
            .closest('.toggle-content')
                .slideUp();

        return false;
    });

    $('#content').on('click', '.toggle-change', function(e) {
        $(this)
            .find('.c-control-text')
            .toggleClass('imp-hide');

    });

    $('#content .timeline-elem:odd').css('background-color', 'rgba(239,240, 243, 0.4)');

    /* Переключение "Моя-лента"/"Поиск по новостям" на моей странице и ленте новостей */
    $('.toggle-link').click(function() {
        $closest = $(this).closest('.feed-box2');

        $closest
            .find('.toggle-group:not(.hide-elem)')
            .fadeOut('fast', function() {
                $closest.find('.toggle-group.hide-elem').fadeIn('fast').removeClass('hide-elem');
                $(this).addClass('hide-elem');
            });

        return false;
    });

    $('#status-input-text').click(function() {
        $('#status-change-window')
            .show()
            .find('input[type=text]')
                .get(0)
                .select();

        return false;
    });

    $('#status-input').keyup(function (e) {
        var lngth = $(this).val().length,
            max = 70 - lngth;

        $('#letter-count').html('Осталось ' + max + ' ' + declOfNum(max, ['символ', 'символа', 'символов']));
    });

    function declOfNum(number, titles)  {
        cases = [2, 0, 1, 1, 1, 2];  
        return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];  
    }

    $('body').click(function (e) {
        if ($(e.target).closest('#status-change-window').length == 0) {
            $('#status-change-window').hide();
        }
    });
    
    $("input:checkbox, input:radio").uniform();
    
    var rAmount = $("#rangeAmount");
    
    $( ".uRange" ).slider({ /* Range slider */
        range: true,
        min: 0,
        max: 500,
        values: [ 75, 300 ],
        slide: function( event, ui ) {
            rAmount.html( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
        }
    });
    
    var percent = $('#bar1').attr('title');
    $('#bar1').animate({width: percent},2000); 
    
    $('#content select').not('#country,#user_townid').selectBox();


    $('.tipN').tipsy({gravity: 'n',fade: true, html:true, live: true});
    $('.tipS').tipsy({gravity: 's',fade: true, html:true, live: true});
    $('.tipW').tipsy({gravity: 'w',fade: true, html:true, live: true});
    $('.tipE').tipsy({gravity: 'e',fade: true, html:true, live: true});
 
	$(window).scroll(function(){
		if ($(this).scrollTop() > 700) {
			$('.scrollup').fadeIn();
		} else {
			$('.scrollup').fadeOut(0);
		}
	}); 
	
	$('.scrollup').click(function(){
		$("html, body").animate({ scrollTop: 0 }, 400);
		return false;
	});
});


function moveMarker(zoom){
    if(zoom==20)     return 0.0002;
    else if(zoom==19)return 0.0002;
    else if(zoom==18)return 0.0002;
    else if(zoom==17)return 0.0005;
    else if(zoom==16)return 0.001;
    else if(zoom==15)return 0.0019;
    else if(zoom==14)return 0.00394;
    else if(zoom==13)return 0.0075;
    else if(zoom==12)return 0.015;
    else if(zoom==11)return 0.03;
    else if(zoom==10)return 0.063;
    else if(zoom==9) return 0.12;
    else if(zoom==8) return 0.24;
    else if(zoom==7) return 0.48;
    else if(zoom==6) return 0.94;
    else if(zoom==5) return 1.9;
    else if(zoom==4) return 3.65;
    else if(zoom==3) return 7;
    else if(zoom==2) return 12.5;
    else if(zoom==1) return 22;
    else             return 30;
}
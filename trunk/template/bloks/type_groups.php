<?php
    require_once('jquery/jq_gifts.php');

    //Массив категорий
    $catFull=catArray();

    //Начальная валюта
    $cID=2;
    $cCurrent='руб';
    $cFrom='0';
    $cTo=getMaxCost($type_id,$gr_id,0,$cID); //Функция по нахождению максимальной цены?!?!
    //$cMax=getMaxCost($type_id,$gr_id,0,$cID);

    $subs_count=countGifts($type_id,$gr_id,0,$cID,$cFrom*100,$cTo*100); //Add Function
    $rows=20;
?>
            <div id="content" class="fl_r">
                <div class="title margin">
                    <h2>Подарки</h2>
                    <div class="separator"></div>
                    <h3>Поиск</h3>
                </div>

                <div class="gift-search-block group">
                    <table>
                        <tr>
                            <td class="col1">
                                <div class="arrow-box">
                                    Название подарка
                                </div>
                            </td>
                            <td class="col2"><input type="text" id="giftName" placeholder="Введите название подарка"></td>
                            <td class="col3">
                                <a href="#" class="my-friends-wish fl_l group"><i class="small-icon icon-wish" style="margin-right: 5px;"></i>Желания моих друзей</a>
                            </td>
                        </tr>
                        <tr>
                            <td class="col1">
                                <div class="arrow-box">
                                    Выберите регион
                                </div>
                            </td>
                            <td class="col2">
                                <input type="text" id="giftRegion" placeholder="Введите адрес">
                            </td>
                            <td class="col3">
                                <label><input type="checkbox" id="giftMyRegion">В моем регионе</label>
                                <label><input type="checkbox" id="giftMyPlace">В моих любимых местах</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="col1">
                                <div class="arrow-box">
                                    Цена подарка в <span class="gift-exchange-button popover-btn" style="text-decoration: underline; cursor: pointer;" id="currencyM"><?=$cCurrent?></span>
                                </div>
                            </td>
                            <td class="col2">
                                <span id="rangeAmount"><span id="cFrom"><?=$cFrom?></span> <span id="currency"><?=$cCurrent?></span> - <span id="cTo"><?=$cTo?></span> <span id="currency"><?=$cCurrent?></span></span>
                                <!-- <span id="cMax" style="display:none;"><?=$cMax?></span> -->
                                <div class="uRange"></div>
                            </td>
                            <td class="col3">
                                <label class="only-stock"><input type="checkbox" id="giftOnlyAction">Показать только акции</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="col1">
                                <div class="arrow-box">
                                    Выбор категории
                                </div>
                            </td>
                            <td class="col2" colspan="2">
					              <div class="b-icons">
<?php
    foreach($catFull as $key=>$value)
    {
        echo'
						              <div class="b-icon"><a href="javascript:;" onclick="setCat('.$key.')" class="big-category-icon category-icon-eat" data-content="'.htmlspecialchars(json_encode($value)).'"></a><span class="b-desc item'.$key.'">'.$value['mainname'].'</span></div>';
    }
?>
					              </div><span class="btn btn-green s-search fl_r"><i class="icon-search-white"></i>Начать поиск</span>
                            </td>
                        </tr>
                        </tr>
                    </table>
                </div>

                <script>
                    var page    = 1,
                        max     = <?=ceil($subs_count/$rows)?>,
                        rows    = <?=$rows?>,
                        begin   = rows,
                        category= 0,
                        currency= 2;

                    function choosenGifts(type){
                        var name      = $('#giftName').val(),
                            cFrom     = $('#cFrom').html(),
                            cTo       = $('#cTo').html(),
                            order     = $('#giftOrder').val(),
                            region    = $('#giftRegion').val(),
                            myRegion  = $('#giftMyRegion').prop('checked'),
                            myPlace   = $('#giftMyPlace').prop('checked'),
                            onlyAction= $('#giftOnlyAction').prop('checked'),
                            idGifts   = $('#idGifts');

                        if(type=='n'){
                        	max=0;
                            page=1;
                            begin=0;
                            idGifts.html('');
                            $('div#loadmoreajaxloader').show();
                        }

                        if(max>page && type=='p')
                        	$('div#loadmoreajaxloader').show();

                        if(max>page || type=='n'){
                            $.ajax({
                                url:'/jquery-gifts',
                                type:'POST',
                                data:{type_id:<?=$type_id?>,gr_id:<?=$gr_id?>,list:begin,items:rows,order:order,name:name,region:region,mR:myRegion?1:0,mP:myPlace?1:0,oA:onlyAction?1:0,cat:category,currency:currency,cFrom:cFrom,cTo:cTo},
                                cache:false,
                                success: function(data){
                                    var html;

                                    if(data){
                                        html = jQuery.parseJSON(data);

                                        if(type=='n'){
                                            idGifts.empty();
                                            max=html.max;
                                            begin=rows;
                                        }else{
                                            page =page+1;
                                            begin=begin+rows;
                                        }

                                        $('div#loadmoreajaxloader').hide();
                                        idGifts.append(html.html);
                                    }
                                }
                            });
                        }
                        else
                        	$('div#loadmoreajaxloader').hide();
                    }

                    function setCat(p){
                        $('.item' + category).attr('class','b-desc item' + category);
                        category=p;
                        $('.item' + p).attr('class','b-desc item' + p + ' active');
                    }

                    function setCurrency(p,n){ //,f,t
                        currency=p;
                        //cFrom=cFrom/f;
                        //cTo=cTo/t;
                        $('#currencyM').html(n);
                        $('<span id="currency">' + n + '</span>').replaceAll('span#currency');
                    }

                    $(window).scroll(function(){
                        if($(window).scrollTop()==$(document).height()-$(window).height()){
                            choosenGifts('p');
                        }
                    });

                    jQuery(function($){
                    	$(".s-search").click(function(){
	                    	choosenGifts('n');
                    	});
                        $("#giftOrder").change(function(){
                            choosenGifts('n');
                        });
                    });
                </script>

                <h1 class="gift-header">ТОП лучших подарков в твоем регионе</h1>
                <div class="group">
                    <div class="fl_r">
                        <strong class="gift-sort-header">Сортировка по:</strong>
                        <select name="gift-sort" id="giftOrder">
                            <option value="1">По алфавиту</option>
                            <option value="2">По максимальной цене</option>
                            <option value="3">По минимальной цене</option>
                            <option value="4">По популярности</option>
                        </select>
                    </div>
                </div>
                <div class="separator"></div>
                <div class="group" id="idGifts"><?=searchGifts($type_id,$gr_id,$rows,0,1,'html',0,$cID,$cFrom*100,$cTo*100)?></div>
                <div id="loadmoreajaxloader" style="display:none; text-align:center;"><img src="/pic/loader.gif" alt="loader" width="32" height="32" /></div>
            </div>
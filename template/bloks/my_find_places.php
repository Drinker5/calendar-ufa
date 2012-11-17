<div class='place-search'>
<div class="title margin">                    
    <h2>Куда пойти<span class="geo_icon fl_r"><span class="geo_rotate"></span></span></h2>                    
    <div class="separator"></div> 
    <span class="geo_arrow-down"></span>
    <span class="hint">Введи заведение или город и мы поможем тебе найти лучшее место для встреч</span>                   
</div>

<div class="gift-search-block group">
    <table>
        <tbody>
        <tr>
            <td class="col1">
                <div class="arrow-box">
                    Выберите регион
                </div>
            </td>
            <td class="col2">
                
                <form class="recipient">
                  <input type="text" name='search_query' placeholder="Введите адрес или название заведения">
                  <input type="submit" name='search_submit' value="">
               </form>
            </td>
            <td class="col3">
                <label><div class="checker" id="uniform-undefined"><span><input type="checkbox" class='flags' name='ihere' style="opacity: 0; "></span></div>Места, в которых я был/а</label>
                <label><div class="checker" id="uniform-undefined"><span><input type="checkbox" class='flags' name='friendhere' style="opacity: 0; "></span></div>Места, в которых были мои друзья</label>
                <label><div class="checker" id="uniform-undefined"><span><input type="checkbox" class='flags' name='act' style="opacity: 0; "></span></div>Заведения, где проходят акции</label>
            </td>
        </tr>
        <tr>
            <td class="col1">
                <div class="arrow-box">
                    Выбор категории
                </div>
            </td>
            <td class="col2" colspan="2">
                <div class="category-icons active fl_l tx_c">
                    <a href="#" class="big-category-icon category-icon-eat"></a><br>
                    <input type='hidden' name='category' value='1'/>
                    Кафе<br>Рестораны
                </div>
                <div class="category-icons fl_l tx_c">
                    <a href="#" class="big-category-icon category-icon-eat "></a><br>
                    <input type='hidden' name='category' value='39'/>
                    Бары<br>Клубы
                </div>
                <div class="category-icons fl_l tx_c">
                    <a href="#" class="big-category-icon category-icon-eat "></a><br>
                    <input type='hidden' name='category' value='89'/>
                    Доставка<br>еды
                </div>
                <div class="category-icons fl_l tx_c">
                    <a href="#" class="big-category-icon category-icon-eat "></a><br>
                    <input type='hidden' name='category' value='54'/>
                    Красота<br>Здоровье
                </div>
                <div class="category-icons fl_l tx_c">
                    <a href="#" class="big-category-icon category-icon-eat "></a><br>
                    <input type='hidden' name='category' value='66'/>
                    Активный<br>отдых
                </div>
                <div class="category-icons fl_l tx_c">
                    <a href="#" class="big-category-icon category-icon-eat "></a><br>
                    <input type='hidden' name='category' value='98'/>
                    Искусство
                </div>
                <div class="category-icons fl_l tx_c">
                    <a href="#" class="big-category-icon category-icon-eat "></a><br>
                    <input type='hidden' name='category' value='86'/>
                    Цветы<br>Подарки
                </div>
                <div class="category-icons fl_l tx_c">
                    <a href="#" class="big-category-icon category-icon-eat "></a><br>
                    <input type='hidden' name='category' value='96'/>
                    Парфюмерия<br>Косметика
                </div>
                <div class="category-icons fl_l tx_c">
                    <a href="#" class="big-category-icon category-icon-eat "></a><br>
                    <input type='hidden' name='category' value='97'/>
                    Детские<br>Товары
                </div>
            </td>
        </tr>
        
    </tbody></table>
</div>

<div class="edit-places-container sort-places">
    <div class="edit-places-container">
    <div class="map fl_l">
        <div class="map-block-top">
            <div class="map-block-bottom">
                <div class="map-block-center">
                    <div id="map" style="width:395px; height:600px;"></div>
                </div>
            </div>
        </div>
    </div> <!-- /.map -->
    <div class="places-list fl_r">
        <div class="search-block">
            <div class="select_search-block-bottom">
                <span class="name">Сортировка по: </span>
                <select id="region-select" name='shop_sort'>
                    <option value='0'>по алфавиту</option>
                    <option value='1'>по удаленности</option>
                    <option value='2'>по популярности</option>
                </select>
            </div>
        </div>
        <div class="places-wrapper">
        </div> <!-- .places-wrapper -->
    </div> <!-- /.places-list -->
</div> <!-- /.edit-places -->
</div>
<?php
    $zoom=12;//Масштаб карты
    $myPos=true;
    //$zoom=3;
    $lat=0;
    $lon=0;

    if(function_exists('geoip_record_by_name'))
    {
        $rec=geoip_record_by_name($_SERVER['REMOTE_ADDR']);
        if($rec)
        {
            $lat=$rec['latitude'];
            $lon=$rec['longitude'];
        }
    }
?>
<div id='pointselector-text'></div>
    <script>
<?php if($myPos){ ?>
    var map=mapbox.map('map').zoom(<?=$zoom?>).center({lat: <?=$lat?>, lon: <?=$lon?>});
    map.addLayer(mapbox.layer().id('jam-media.map-tckxnm3s'));
    map.ui.zoomer.add();

    var markerLayer = mapbox.markers.layer();      
    //var interaction = mapbox.markers.interaction(markerLayer);
    map.addLayer(markerLayer);

    if (!navigator.geolocation) {
      //geolocation is not available
    } else {
          navigator.geolocation.getCurrentPosition(
          function(position) {
              map.zoom(<?=$zoom?>).center({
                  lat: position.coords.latitude,
                  lon: position.coords.longitude
              });
              markerLayer.add_feature({
                  geometry: {
                      coordinates: [
                          position.coords.longitude,
                          position.coords.latitude]
                  },
                  properties: {
                      'image': 'https://dl.dropbox.com/u/23467346/pic/male-2.png',
                      'id':'pin',
                  }
              });
              markerLayer.factory(function(f){
                var img = document.createElement('img');
                img.className = 'marker-image';
                img.setAttribute('src', f.properties.image);

                //Смещение к координатам маркера
                MM.addEvent(img, 'click', function(e){
                  map.ease.location({
                    lat:position.coords.latitude,
                    lon:position.coords.longitude
                  }).zoom(map.zoom()).optimal();
                  $('.marker-image').attr({src:f.properties.image});
                  img.setAttribute('src', 'https://dl.dropbox.com/u/23467346/pic/male-2.png'); //https://dl.dropbox.com/u/23467346/pic/alien.png
                  $('.place').removeClass('active');
                  $('.place[rel='+f.properties.id+']').fadeOut(function(){
                    setTimeout(function(){
                      $('.place[rel='+f.properties.id+']').prependTo('.places-list').fadeIn(function(){
                        setTimeout(function(){
                          $('.place[rel='+f.properties.id+']').addClass('active');
                        },
                        1);
                      });
                    },
                    300);
                  });
                });
                return img;
              });
              //interaction.formatter(function(feature){
              //  var o = 'Я тут';
              //
              //  return o;
              //});
          },
          function(err) {
              //position could not be found
          });
  }
<?php }else{ ?>
        var map=mapbox.map('map').zoom(<?=$zoom?>).center({<?=$map_cnt?>});
        map.addLayer(mapbox.layer().id('jam-media.map-tckxnm3s'));
        map.ui.zoomer.add();

        mrk=new Object();

        var markerLayer=mapbox.markers.layer().features([<?=$markers?>]).factory(function(f){
            var img=document.createElement('img');
            img.className='marker-image';
            img.setAttribute('src', f.properties.image);
            img.setAttribute('id', f.properties.id);

            mrk[f.properties.id]=new Object();
            mrk[f.properties.id]['lat']=f.geometry.coordinates[1];
            mrk[f.properties.id]['lon']=f.geometry.coordinates[0];
            mrk[f.properties.id]['img']=f.properties.image;

            //Смещение к координатам маркера
            MM.addEvent(img, 'click', function(e){
                map.ease.location({
                    lat:f.geometry.coordinates[1],
                    lon:f.geometry.coordinates[0]
                }).zoom(map.zoom()).optimal();
                $('.marker-image').attr({src:f.properties.image});
                img.setAttribute('src', 'https://dl.dropbox.com/u/23467346/pic/alien.png');
                $('.place').removeClass('active');
                $('.place[rel='+f.properties.id+']').fadeOut(function(){
                    setTimeout(function(){
                        $('.place[rel='+f.properties.id+']').prependTo('#search-content').fadeIn(function(){
                            setTimeout(function(){
                                $('.place[rel='+f.properties.id+']').addClass('active');
                            },
                            1);
                        });
                    },
                    300);
                });
            });
            return img;
        });
        map.addLayer(markerLayer);
        mapbox.markers.interaction(markerLayer).formatter(function(feature){
            var o='<b>'+feature.properties.name+'</b><br />'+feature.properties.address;
            return o;
        });

        
        <?php } ?>
</script>


<script>
//обрезка слов
function word_cut(word, count)
{
    var word = word.split(' ');
    stop = word.length - count;
    word.splice(10, stop);
    word = word.join(' ');
    word += ' ...';
    return word;

}
//объект магазина/заведения

var template =  '<input type="hidden" name="shop_id" value="{{shop_id}}" />'+
                '<input type="hidden" name="number" value="{{number}}" />'+
                '<input type="hidden" name="adres" value="{{adres_id}}" />'+
                '<input type="hidden" name="lat" value="{{lat}}" />'+
                '<input type="hidden" name="lon" value="{{lon}}" />'+
                '<div class="preview fl_l">'+
                    '<a href="/shop-{{shop_id}}"><img src="{{logo}}" alt=""></a>'+
                '</div>'+
                '<div class="info fl_l">'+
                    '<a class="name" href="/shop-{{shop_id}}">{{name}}</a>'+
                    '<p>{{info}}'+
                    '</p>'+
                    '{{#fav}}'+
                    '<div class="place-love fav green"><i class="small-icon icon-favorite-place-green"></i> Любимое место</div>'+
                    '{{/fav}}'+
                    '{{^fav}}'+
                    '<div class="place-love not-fav"><i class="small-icon icon-favorite-place"></i> Добавить в любимые места</div>'+
                    '{{/fav}}'+
                '</div>'+
                '<div class="action fl_r">'+
                    '<a href="" class="small-icon icon-star action"></a>'+
                    '<a href="" class="small-icon icon-star action"></a>'+
                    '<a href="" class="small-icon icon-star action"></a>'+
                    '<a href="" class="small-icon icon-star action"></a>'+
                    '<a href="" class="small-icon icon-star"></a>'+
                '</div>';
shop = function()
{
    this.model = {};
    this.template = template;
    this.html = ''; 
    this.init = function(data)
    {
        this.model['shop_id'] = data['id'];
    this.model['lat'] = data['lat'];
    this.model['lon'] = data['lon'];
    this.model['logo'] = data['logo'];
    this.model['name'] = data['name'];
    this.model['info'] = word_cut(data['info'],10);
    this.model['fav'] = (+data['fav'] > 0)?data['fav']:'';
    this.model['adres'] = data['adres'];
    this.model['adres_id'] = data['adres_id'];
    }
    this.render = function()
    {
        this.html = Mustache.to_html(this.template, this.model);
        return this.html;
    }
}
//коллекция всех заведений
shops = []
//объект поиска
search = {params:{'search_query':'','sort':'name','category':'1','action':'search','flag-ihere':'0','flag-friend-here':'0','flag-act':'0'}}
search.init = function()
{
    this.params['search_query'] = $('input[name=search_query]').val();
    this.params['sort'] = $('select[name=shop_sort]').val();
    this.params['category'] = $('div.category-icons.active input[name=category]').val();
    this.params['flag-ihere'] = ($('input[name=ihere]').prop('checked'))?1:0;
    this.params['flag-friend-here'] = ($('input[name=friendhere]').prop('checked'))?1:0;
    this.params['flag-act'] = ($('input[name=act]').prop('checked'))?1:0;

} 

$(document).ready(function()
{
    $('input[name=search_submit]').click(search_submit);
    $('select[name=shop_sort]').change(search_submit);
    $('input.flags').click(search_submit_for_checkbox);
    $('div.place-love.not-fav').click(add_favorite_place);
    search_submit();
    $('a.big-category-icon').click(select_category);
});
function search_submit()
{


    search.init();
    $.ajax({
        url:'/jquery-findplace',
        type: "POST",
        dataType:'json',
        data: search.params,
        success:function(data){
            shops_block_render(data);
            add_markers(data);
        }
    });
    return false;
}
//wrapper для функции search_submit на checkbox'ах
function search_submit_for_checkbox()
{
    search_submit();
    return true;
}
function add_markers(data)
{
    var data = data['shops'];
    var n = data.length;
    map.removeLayer('markers');
    markers = []
    for (var i = 0; i < n; i++)
    {
        //marker = '{"geometry":{"type":"Point", "coordinates":['+data[i]['lon']+','+data[i]['lat']+']}, "properties":{"image":"https://dl.dropbox.com/u/23467346/pic/modernmonument.png", "name":"'+data[i]['name']+'", "address":"'+data[i]['adres'].replace("::",", ")+'","id":"pin'+i+'"}}';
        marker = {geometry:{type:'Point',coordinates:[data[i]['lon'],data[i]['lat']]}, properties:{image:'https://dl.dropbox.com/u/23467346/pic/modernmonument.png',name:data[i]['name'], address: data[i]['adres'].replace(/::/g,", "), id:'pin'+i}};
        markers[i] = marker; 
    }
    mrk=new Object();

    var markerLayer=mapbox.markers.layer().features(markers).factory(function(f){
        var img=document.createElement('img');
        img.className='marker-image';
        img.setAttribute('src', f.properties.image);
        img.setAttribute('id', f.properties.id);

        mrk[f.properties.id]=new Object();
        mrk[f.properties.id]['lat']=f.geometry.coordinates[1];
        mrk[f.properties.id]['lon']=f.geometry.coordinates[0];
        mrk[f.properties.id]['img']=f.properties.image;

        //Смещение к координатам маркера
        MM.addEvent(img, 'click', function(e){
            map.ease.location({
                lat:f.geometry.coordinates[1],
                lon:f.geometry.coordinates[0]
            }).zoom(map.zoom()).optimal();
            $('.marker-image').attr({src:f.properties.image});
            img.setAttribute('src', 'https://dl.dropbox.com/u/23467346/pic/alien.png');
            $('.place').removeClass('active');
            $('.place[rel='+f.properties.id+']').fadeOut(function(){
                setTimeout(function(){
                    $('.place[rel='+f.properties.id+']').prependTo('#search-content').fadeIn(function(){
                        setTimeout(function(){
                            $('.place[rel='+f.properties.id+']').addClass('active');
                        },
                        1);
                    });
                },
                300);
            });
        });
        return img;
    });
    map.addLayer(markerLayer);
    mapbox.markers.interaction(markerLayer).formatter(function(feature){
        var o='<b>'+feature.properties.name+'</b><br />'+feature.properties.address;
        return o;
    });
}
function shops_block_render(data)
{
    shops = []
    var error_message = '<span style="color:green">По вашему запросу ничего не найдено. попробуйте изменить критерии поиска и попробовать снова</span>';
    if (data.success == 0)
        $('div.places-wrapper').html(error_message);
    else
    {
        var result_html = '';
        var n = data['shops'].length;
        var tmp_shop;
        for (var i = 0; i < n; i++)
        {
            tmp_shop = new shop();
            tmp_shop.model['number'] = i;
            tmp_shop.init(data['shops'][i]);
            result_html += '<div class="place group">'+tmp_shop.render()+'</div>';
            shops[i] = tmp_shop;
        }
        $('div.places-wrapper').html(result_html);
        $('a.name').click(map_go_click);
        $('div.place-love.not-fav').click(add_favorite_place);
        click_to_first();
    }
}
function map_go_click()
{
    var parent = $(this).closest('.place');
    map_go(parent);
}
function map_go(elem)
{
    var lon = $('input[name=lon]',elem).val();
    var lat = $('input[name=lat]',elem).val();
    if (! lat || lat == 0)
    {
        return false;
    }
    map.ease.location({
                    lat:lat,
                    lon:lon
                  }).zoom(map.zoom()).optimal();
    return false;
}
function add_favorite_place()
{
    var parent = $(this).closest('.place');
    var adress_id = $('input[name=adres]',parent).val();
    var number = parseInt($('input[name=number]',parent).val());
    var action='favorite';
    $.ajax({
        url:'/jquery-findplace',
        type: "POST",
        dataType:'json',
        data: {adress_id:adress_id, action:action},
        success:function(data){
            if (data.success == 1)
            {
                shops[number]['model']['fav'] = adress_id;
                var change_block = shops[number].render();
                parent.html(change_block);
                $('a.name').click(map_go_click);
                $('div.place-love.not-fav').click(add_favorite_place);
            }

            
        }
    });
}
function select_category()
{
    var parent = $(this).parent();
    if (parent.hasClass('active'))
        return false;
    $('div.category-icons').removeClass('active');
    parent.addClass('active');
    search_submit();
    return false;
}
function click_to_first()
{
    var first = $('div.places-wrapper div.place:first');
    if (first)
        map_go(first);

}
</script>
<div id="center">
    <div id="content" class="fl_r">
        <div class="title margin">
            <h2>Wishlist</h2>
        </div>
<?php
 $user_wp = $_SESSION['WP_USER']['user_wp'];
 //require_once ('jquery/jq_mywishes.php');
?>
<div class="nav-panel group">
    <ul class="fl_r right">
        <li class="opacity_link"><a href="#">Wishlist ()</a></li>
        <li class="opacity_link"><a href="#">Wishlist исполненный ()</a></li>
    </ul>
</div>
                <div class="tools_block">
                    <div class="wish-item group">
                        <div class="tx_r fl_r">
                            <a href="#" class="make-gift show opacity_link group">
                                <span class="big-circle-icon circle-icon-make-gift fl_r tx_c"></span>
                                <div class="wrapped">
                                    Разослать<br>друзьям
                                </div>
                            </a>
                        </div>
                        <div class="wish-content wrapped">
                            <strong>Wishlist: </strong><a href="#" class="wish-target"><strong> Название </strong></a><span class="date">дата</span>
                            <p>
                                Подарок к: <strong></strong>
                                <br>
                                Место получения: <strong class="popover-btn name"> </strong>
                            </p>
                            <p>
                                Повод: <span class="reason"> </span>
                            </p>
                        </div>
                        <div class="cleared"></div>
                        <div class="tools wish-text">
                            <div class="to-do-list">
                                <div class="head-list">
                                    <div class="menu_tools" id="menu_wish">
                                        <h1>Название вишлиста</h1>
                                    </div>
                                    <div class="punktir"></div>
                                </div>

                                <div class="line-list"></div>
                                <div class="table-list">
                                    <table>
                                        <tr>
                                            <td class="add"><input type="checkbox"/></td>
                                            <td class="text">
                                                <span>Желание1</span>
                                            </td>
                                            <td class="podarok">
                                                <a href="#" class="blue-color">
                                                    Подарить
                                                    <i class="small-icon icon-5"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="add"><input type="checkbox" checked /></td>
                                            <td class="text">
                                                Желание2
                                            </td>
                                            <td class="podarok">Подаренно</td>
                                        </tr>
                                        <tr>
                                            <td class="add"><input type="checkbox"/></td>
                                            <td class="text">
                                                <span>Желание3</span>
                                            </td>
                                            <td class="podarok">
                                                <a href="#" class="blue-color">
                                                    Подарить
                                                    <i class="small-icon icon-5"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="add"><input type="checkbox" checked/></td>
                                            <td class="text">
                                                Билет на премьеру фильма “Прометей”
                                            </td>
                                            <td class="podarok">Подаренно</td>
                                        </tr>
                                        <tr>
                                            <td class="add"><input type="checkbox" checked/></td>
                                            <td class="text">
                                                Винтажная сумочка с кружевами и золотой цепочкой
                                            </td>
                                            <td class="podarok">Подаренно</td>
                                        </tr>
                                        <tr>
                                            <td class="add"><input type="checkbox"/></td>
                                            <td class="text">
                                                <span>Визитница</span>
                                            </td>
                                            <td class="podarok">
                                                <a href="#" class="blue-color">
                                                    Подарить
                                                    <i class="small-icon icon-5"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="add"></td>
                                            <td class="text"></td>
                                            <td class="podarok"></td>
                                        </tr>
                                    </table>
                                    <div class="podlojka-list">
                                        <div class="podlojka-list1">
                                            <div class="podlojka-list2">

                                        <table>
                                            <tr>
                                                <td class="add"></td>
                                                <td class="text"></td>
                                                <td class="podarok"></td>
                                            </tr>
                                        </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tools_comments tx_r">
                                <a href="#" class="comments opacity_link grey-dark-color-bold">Комментариев ()</a>
                            </div>
                            <div class="cleared"></div>
                        </div>
                    </div>
                </div>
            </div>
</div>

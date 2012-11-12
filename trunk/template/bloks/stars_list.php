<!-- <div id="center">
	<h1>Звезды</h1>
<?php
	$stars_all=$USER->ShowStars();
	if(is_array($stars_all)){
		echo '<div id="idItems">';
		for($i=0; $i<count($stars_all); $i++){
			$arr_users[]=$stars_all[$i]['user_wp'];
		}
		$avatar=ShowAvatar($arr_users,70,70);
		for($i=0; $i<count($stars_all); $i++){
?>
			<div class="myfriend" rel="<?=$stars_all[$i]['user_wp']?>">
				<a href="/<?=$stars_all[$i]['user_wp']?>" class="frlistav"><img src="<?=$avatar[$i]['avatar']?>" width="70" height="70" /></a>
				<a href="/<?=$stars_all[$i]['user_wp']?>"><?=trim($stars_all[$i]['firstname'].' '.$stars_all[$i]['lastname'])?></a><br />
			</div>
<?php
		}
		echo '</div>';
	}

	//echo '<pre>';
	//print_r($USER->ShowStars());
	//echo '</pre>';
?>
	<div class="clear"></div>
</div> -->
                <div class="title margin">
                    <h2>Кумиры</h2>
                </div>
                
                <div class="friend-container group">
                    <div class="friend-item fl_l">
                        <div class="bordered medium-avatar fl_l">
                            <a href="/10019"><img src="stars/photo/10019/_icon.png" alt=""></a>
                        </div>
                        <div class="content wrapped">
                            <i class="status-icon-small icon-online-small"></i>
                            <span class="name">
                                <a href="/10019">Гарик Харламов</a>
                            </span>
                            <br>
                            <span class="place">Москва, Россия</span>
                        </div>
                        <div class="tools_block hide absolute tx_r">
                            <span class="fl_l">
                                <a href="#" class="opacity_link"><i original-title="Сделать подарок" class="tipN active small-icon icon-gift"></i></a>
                                <a href="#" class="opacity_link"><i original-title="Написать сообщение" class="tipN active small-icon icon-chat"></i></a>
                                <a href="#" class="opacity_link"><i original-title="Пригласить" class="tipN active small-icon icon-invite"></i></a>
                            </span>
                            <span class="opacity_link popover-btn my-friend-actions">
                                 <i class="small-icon active icon-settings"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="friend-item fl_l">
                        <div class="bordered medium-avatar fl_l">
                            <a href="/10051"><img src="stars/photo/10051/_icon.png" alt=""></a>
                        </div>
                        <div class="content wrapped">
                            <i class="status-icon-small icon-online-small"></i>
                            <span class="name">
                                <a href="/10051">Вера Брежнева</a>
                            </span>
                            <br>
                            <span class="place">Украина</span>
                        </div>
                        <div class="tools_block hide absolute tx_r">
                            <span class="fl_l">
                                <a href="#" class="opacity_link"><i original-title="Сделать подарок" class="tipN active small-icon icon-gift"></i></a>
                                <a href="#" class="opacity_link"><i original-title="Написать сообщение" class="tipN active small-icon icon-chat"></i></a>
                                <a href="#" class="opacity_link"><i original-title="Пригласить" class="tipN active small-icon icon-invite"></i></a>
                            </span>
                            <span class="opacity_link popover-btn my-friend-actions">
                                 <i class="small-icon active icon-settings"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="friend-item fl_l">
                        <div class="bordered medium-avatar fl_l">
                            <a href="/10132"><img src="stars/photo/10132/_icon.png" alt=""></a>
                        </div>
                        <div class="content wrapped">
                            <i class="status-icon-small icon-online-small"></i>
                            <span class="name">
                                <a href="/10132">STING</a>
                            </span>
                            <br>
                            <span class="place">Великобритания</span>
                        </div>
                        <div class="tools_block hide absolute tx_r">
                            <span class="fl_l">
                                <a href="#" class="opacity_link"><i original-title="Сделать подарок" class="tipN active small-icon icon-gift"></i></a>
                                <a href="#" class="opacity_link"><i original-title="Написать сообщение" class="tipN active small-icon icon-chat"></i></a>
                                <a href="#" class="opacity_link"><i original-title="Пригласить" class="tipN active small-icon icon-invite"></i></a>
                            </span>
                            <span class="opacity_link popover-btn my-friend-actions">
                                 <i class="small-icon active icon-settings"></i>
                            </span>
                        </div>
                    </div>
                </div>
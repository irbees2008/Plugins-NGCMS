<?php
if (!defined('NGCMS')) die ('HAL');
add_act('index', 'mysidebar');

	function mysidebar(){
		global $tvars, $template, $tpl;

	$ref = $_SERVER['REQUEST_URI'];
	$ref = explode("/", $ref);
	$url_id = $ref[2];
$url_id = substr($url_id, 0, -5);	

if ($url_id == 'poisk-po-metro' or $url_id == 'adssearch'){
$side_show = '';
}else{
$side_show = '
<span class="r_title">О нас</span>
<ul>
	<li><a href="http://iqir.ru/static/kompaniya.html">Компания</a></li>
	<li><a href="http://iqir.ru/static/partnery.html">Партнеры</a></li>
	<li><a href="http://iqir.ru/static/proekty.html">Проекты</a></li>
	<li><a href="http://iqir.ru/static/komanda.html">Команда</a></li> 
	<li><a href="http://iqir.ru/static/istoriya.html">История</a></li> 
	<li><a href="http://iqir.ru/novosti.html">Новости</a></li> 
	<li><a href="http://iqir.ru/static/vakansii.html">Вакансии</a></li> 
</ul>
<span class="r_title">Объекты</span>
<ul>
	<li><a href="http://iqir.ru/static/poisk-po-metro.html">Москва и МО</a></li>
	<li><a href="http://iqir.ru/static/nedvizhimost-v-regionah.html">Регионы РФ</a></li>
	<li><a href="http://iqir.ru/static/nedvizhimost-za-rubezhom.html">За рубежом</a></li>
</ul>
<span class="r_title">Услуги</span>
<ul>
	<li><a href="http://iqir.ru/static/uslugi-investoram.html">Инвесторам</a></li> 
	<li><a href="http://iqir.ru/static/uslugi-vladelcam.html">Владельцам</a></li> 
	<li><a href="http://iqir.ru/static/uslugi-korporativnym-klientam.html">Корпоративным клиентам</a></li>
	<li><a href="http://iqir.ru/static/uslugi-chastnym-klientam.html">Частным клиентам</a></li> 
	<li><a href="http://iqir.ru/static/uslugi-rieltoram-i-develouperam.html">Риэлторам и девелоперам</a></li> 
</ul> 	
<span class="r_title">Спец. предложения</span>
<ul>
	<li><a href="http://iqir.ru/sp-chastnym-licam.html">Частным лицам</a></li> 
	<li><a href="http://iqir.ru/sp-organizaciyam.html">Организациям</a></li> 
</ul>
<span class="r_title">Контакты</span>
<ul>
	<li><a href="http://iqir.ru/plugin/feedback/?id=3">Москва</a></li> 
	<li><a href="http://iqir.ru/plugin/feedback/?id=4">С-Петербург</a></li> 
	<li><a href="http://iqir.ru/plugin/feedback/?id=5">Россия</a></li>
	<li><a href="http://iqir.ru/plugin/feedback/?id=6">Европа</a></li> 
</ul>
';
}


		$tvars['vars'] = array	(
				'my_data'	=>	$side_show
								);

			$tpl -> template('mysidebar', extras_dir."/mysidebar/tpl");
			$tpl -> vars('mysidebar', $tvars);
			$output .= $tpl -> show('mysidebar');
			$template['vars']['data_mysidebar'] = $output;

}
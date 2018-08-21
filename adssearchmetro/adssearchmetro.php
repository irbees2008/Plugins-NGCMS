<?php

if (!defined('NGCMS')) die ('HAL');


add_act('index', 'adstypes');

add_act('index', 'pagination');

register_plugin_page('adssearchmetro','','adssearchmetro');


function adscategoryes($str) {//категории begin
    global $mysql;

$adscats = array(
	'10'=>' все предложения по продаже жилой недвижимости',
	'37'=>' продажа комнат', 
	'38'=>' продажа 1-комнатных квартир',
	'39'=>' продажа 2-комнатных квартир',
	'40'=>' продажа 3-комнатных квартир',
	'41'=>' продажа 4- и более комнатных квартир',
	'42'=>' продажа в новостройках',	
	'11'=>' все предложения по продаже коммерческой недвижимости',
	'47'=>' продажа офисов',
	'48'=>' продажа торговых помещений',
	'49'=>' продажа складов',
	'50'=>' продажа помещений для общепита',
	'51'=>' продажа помещений для быт. услуг',
	'52'=>' продажа гаражей',
	'53'=>' продажа помещений свободного назначения',
	'54'=>' продажа зданий (ОСЗ)',
	'55'=>' продажа производственных помещений',
	'56'=>' продажа готового бизнеса',	
	'12'=>' все предложения по аренде жилой недвижимости',
	'57'=>' аренда комнат', 
	'58'=>' аренда 1-комнатных квартир',
	'59'=>' аренда 2-комнатных квартир',
	'60'=>' аренда 3-комнатных квартир',
	'61'=>' аренда 4- и более комнатных квартир',
	'62'=>' аренда в новостройках',	
	'13'=>' все предложения по аренде коммерческой недвижимости',
	'67'=>' аренда офисов',
	'68'=>' аренда торговых помещений',
	'69'=>' аренда складов',
	'70'=>' аренда помещений для общепита',
	'71'=>' аренда помещений для быт. услуг',
	'72'=>' аренда гаражей',
	'73'=>' аренда помещений свободного назначения',
	'74'=>' аренда зданий (ОСЗ)',
	'75'=>' аренда производственных помещений'
	);


if (isset($_GET['cat'])) {
	$get_cats = $_GET['cat'];
		
	if (!preg_match("/^[0-9]+$/", $get_cats) or $get_cats <= 9 or $get_cats >= 76){//metro is metro
	//пока что категории для поиска от 10 до 75, если добавятся новые то добавим
	$get_cats = '0';
	error404();//404
	}else{

		$cats_select = 'AND catid LIKE ';
		$cats_select .= db_squote('%'.$get_cats.'%');
		$result_cats = $adscats[$get_cats].' ';

	}
				
}else{
$get_cats = '0';
error404();//404
}		

		
if ($str == '1'){
$arg = $cats_select;
}else if($str == '2'){
$arg = $result_cats;
}else if($str == '3'){
$arg = $get_cats;
}

return $arg;	

}//категории end


function adsmetroname($str) {

$metroEntries = array();

  for ($i = 1; $i <= 220; $i++) { //туду, пересмотреть цикл

	$station_n = 'metro'.$i;
	
	if (isset($_GET[$station_n])){
	$get_metro = $_GET[$station_n];
	$metroEntries[] = $get_metro;	
	}

  }


$cleanEntries = array_unique($metroEntries);//проверим на дубли

//print_r ($cleanEntries);

if ($str == 'cols'){
return count($cleanEntries);//adsmetroname(cols) возвращает кол-во элементов
}else{
return $cleanEntries[$str];//adsmetroname($str) возвращает выбранный элемент
}


}


//print adsmetroname(2);
//print '<br />';
//print adsmetroname(cols);


function adsmetrotype($str) {//метро begin
    global $mysql;
//
$arr_metro = array(
'108'	=>	'Авиамоторная',
'11'	=>	'Автозаводская',
'80'	=>	'Академическая',
'52'	=>	'Александровский сад',
'71'	=>	'Алексеевская', 
'123'	=>	'Алтуфьево', 
'166'	=>	'Аннино', 
'42'	=>	'Арбатская',
'53'	=>	'Арбатская',
'4'		=>	'Аэропорт',
'75'	=>	'Бабушкинская',
'59'	=>	'Багратионовская',
'158'	=>	'Баррикадная',
'45'	=>	'Бауманская',
'98'	=>	'Беговая',
'155'	=>	'Белорусская',
'156'	=>	'Беляево',
'122'	=>	'Бибирево',
'31'	=>	'Библиотека им. Ленина',
'87'	=>	'Битцевский парк',
'112'	=>	'Боровицкая',
'189'	=>	'Боровицкое шоссе',
'73'	=>	'Ботанический сад',
'167'	=>	'Б-р Дмитрия Донского',
'151'	=>	'Братиславская',
'173'	=>	'Бульвар Адм. Ушакова',
'174'	=>	'Бунинская Аллея',
'14'	=>	'Варшавская',
'72'	=>	'ВДНХ',
'120'	=>	'Владыкино',
'1'		=>	'Водный стадион',
'2'		=>	'Войковская',
'92'	=>	'Волгоградский проспект',
'149'	=>	'Волжская',
'175'	=>	'Волоколамская',
'36'	=>	'Воробьевы горы',
'186'	=>	'Востряково',
'200'	=>	'Выставочный центр',
'96'	=>	'Выхино',
'181'	=>	'Деловой центр',
'5'		=>	'Динамо',
'117'	=>	'Дмитровская',
'138'	=>	'Добрынинская',
'19'	=>	'Домодедовская',
'192'	=>	'Достоевская',
'105'	=>	'Дубровка',
'183'	=>	'Жулебино',
'49'	=>	'Измайловская',
'83'	=>	'Калужская',
'16'	=>	'Кантемировская',
'15'	=>	'Каховская',
'13'	=>	'Каширская',
'55'	=>	'Киевская ',
'66'	=>	'Китай-город',
'147'	=>	'Кожуховская',
'12'	=>	'Коломенская',
'25'	=>	'Комсомольская',
'84'	=>	'Коньково',
'20'	=>	'Красногвардейская',
'142'	=>	'Краснопресненская',
'26'	=>	'Красносельская',
'24'	=>	'Красные ворота',
'163'	=>	'Крестьянская застава',
'32'	=>	'Кропоткинская',
'64'	=>	'Крылатское',
'88'	=>	'Кузнецкий мост',
'94'	=>	'Кузьминки',
'62'	=>	'Кунцевская',
'44'	=>	'Курская',
'57'	=>	'Кутузовская',
'79'	=>	'Ленинский проспект',
'22'	=>	'Лубянка',
'150'	=>	'Люблино',
'106'	=>	'Марксистская',
'191'	=>	'Марьина роща',
'152'	=>	'Марьино',
'6'		=>	'Маяковская',
'76'	=>	'Медведково',
'182'	=>	'Международная',
'115'	=>	'Менделеевская',
'154'	=>	'Митино',
'63'	=>	'Молодежная',
'127'	=>	'Нагатинская',
'128'	=>	'Нагорная',
'129'	=>	'Нахимовский проспект',
'184'	=>	'Никулинская',
'111'	=>	'Новогиреево',
'180'	=>	'Новокосино',
'9'		=>	'Новокузнецкая',
'190'	=>	'Новопеределкино',
'143'	=>	'Новослободская',
'82'	=>	'Новые Черемушки',
'77'	=>	'Октябрьская',
'100'	=>	'Октябрьское поле',
'185'	=>	'Олимпийская деревня',
'18'	=>	'Орехово',
'121'	=>	'Отрадное',
'21'	=>	'Охотный ряд',
'10'	=>	'Павелецкая',
'33'	=>	'Парк культуры',
'168'	=>	'Парк Победы',
'48'	=>	'Партизанская',
'50'	=>	'Первомайская',
'110'	=>	'Перово',
'119'	=>	'Петровско-Разумовская',
'148'	=>	'Печатники',
'61'	=>	'Пионерская',
'104'	=>	'Планерная',
'107'	=>	'Площадь Ильича',
'43'	=>	'Площадь революции',
'193'	=>	'Площадь Суворова',
'99'	=>	'Полежаевская',
'124'	=>	'Полянка',
'133'	=>	'Пражская',
'28'	=>	'Преображенская площадь',
'91'	=>	'Пролетарская',
'38'	=>	'Просп. Вернадского',
'69'	=>	'Проспект мира',
'81'	=>	'Профсоюзная',
'145'	=>	'Пушкинская',
'40'	=>	'Речной вокзал',
'70'	=>	'Рижская',
'162'	=>	'Римская',
'95'	=>	'Рязанский проспект',
'116'	=>	'Савеловская',
'74'	=>	'Свиблово',
'130'	=>	'Севастопольская',
'47'	=>	'Семеновская',
'125'	=>	'Серпуховская',
'41'	=>	'Смоленская',
'54'	=>	'Смоленская',
'3'		=>	'Сокол',
'27'	=>	'Сокольники',
'188'	=>	'Солнцево',
'35'	=>	'Спортивная',
'195'	=>	'Сретенский бульвар',
'165'	=>	'Строгино',
'56'	=>	'Студенческая',
'68'	=>	'Сухаревская',
'103'	=>	'Сходненская',
'90'	=>	'Таганская',
'7'		=>	'Тверская',
'8'		=>	'Театральная',
'93'	=>	'Текстильщики',
'198'	=>	'Телецентр',
'85'	=>	'Теплый стан',
'187'	=>	'Терешково',
'118'	=>	'Тимирязевская',
'196'	=>	'Тимирязевская',
'65'	=>	'Третьяковская',
'194'	=>	'Трубная',
'126'	=>	'Тульская',
'67'	=>	'Тургеневская',
'102'	=>	'Тушинская',
'199'	=>	'ул. ак. Королева',
'197'	=>	'ул. Милашенкова',
'201'	=>	'ул. Сергея Эйзенштейна',
'97'	=>	'Улица 1905 года',
'157'	=>	'Улица Академика Янгеля',
'172'	=>	'Улица Горчакова',
'30'	=>	'Улица Подбельского',
'171'	=>	'Улица Скобелевская',
'170'	=>	'Улица Старокачаловская',
'37'	=>	'Университет',
'60'	=>	'Филевский парк',
'58'	=>	'Фили',
'34'	=>	'Фрунзенская',
'17'	=>	'Царицыно',
'114'	=>	'Цветной бульвар',
'29'	=>	'Черкизовская',
'131'	=>	'Чертановская',
'113'	=>	'Чеховская',
'23'	=>	'Чистые пруды',
'160'	=>	'Чкаловская',
'78'	=>	'Шаболовская',
'109'	=>	'Шоссе Энтузиастов',
'51'	=>	'Щелковская',
'101'	=>	'Щукинская',
'46'	=>	'Электрозаводская',
'39'	=>	'Юго-западная',
'132'	=>	'Южная',
'86'	=>	'Ясенево',
'210'	=>	'Мякинино'
);
//

//if (isset($_GET['metro'])){//старый вариант с одним метро
//туду - проверить новый вариант со множественным выбором на уязвимости и баги
if (adsmetroname(cols) > 0){
/*
	$get_metro = $_GET['metro'];
	if (!preg_match("/^[0-9]+$/", $get_metro) or $get_metro < 0 or $get_metro >= 200){//metro is metro
	error404();//404
	}else{
*/
	//пока что метро от 0 до 200, если добавятся новые станции добавим

/*
		if ($get_metro == '0'){
		
			$adsmetro_select = '';
			$result_post_metro = '';
			
			}else{
*/
			

//			$adsmetro_select = 'AND xfields_dometro = ';
//			$adsmetro_select .= db_squote($arr_metro[$get_metro]);

$adsmetro_select = 'AND xfields_dometro IN (';
$adsmetro_select .= db_squote($arr_metro[adsmetroname('0')]);
//$adsmetro_select = 'AND xfields_dometro = ';
//$adsmetro_select .= db_squote($arr_metro[adsmetroname('0')]);

//print $adsmetro_select;

$result_post_metro = 'ст. метро '.$arr_metro[adsmetroname('0')];

for ($i = 1; $i <= adsmetroname(cols); $i++) {



//print adsmetroname($i).'+';

$g_metro = adsmetroname($i);
$get_metro = adsmetroname($i).'-';

		if ($i < adsmetroname(cols)){
		$adsmetro_select .= ', '.db_squote($arr_metro[$g_metro]);
		$result_post_metro .= ', '.$arr_metro[$g_metro];
		}

//if ($i == adsmetroname(cols)){$result_post_metro .= '';}else{$result_post_metro .= ', ';}

}

	$adsmetro_select .= ')';		

						
//			}
	
//		}

			
}else{
$get_metro = '0';
$adsmetro_select = '';
$result_post_metro = '';
}


if ($str == '1'){
$arg = $adsmetro_select;
}else if($str == '2'){
$arg = $result_post_metro.' ';
}else if($str == '3'){
$arg = $get_metro;
}

return $arg;

}//метро end


//print adsmetrotype(1);



function adssearchmetro() {//берем основные данные
    global $template, $mysql, $tpl, $SYSTEM_FLAGS;

$numstr = numpage()/10;
$numstr = $numstr+1;
$SYSTEM_FLAGS['info']['title']['group'] = 'Результаты поиска -'.adscategoryes(2).' '.adsmetrotype(2).'- страница '.$numstr;
$SYSTEM_FLAGS['meta']['description'] = 'Результаты поиска -'.adscategoryes(2).' '.adsmetrotype(2).'- страница '.$numstr;
$SYSTEM_FLAGS['meta']['keywords'] = adscategoryes(2).', '.adsmetrotype(2);

//кеширование
//делаем "сложный" кеш, т.е. свой для каждой страницы
//поэтому кроме md5 для разных категорий и станций используем их гет-значения:
//adsmetrotype(3).'_'.adscategoryes(3).'_'.


$cacheFileName = adsmetrotype(3).'_'.adscategoryes(3).'_'.numpage().'_'.md5('adssearchmetro'.$config['theme'].$config['default_lang'].$year.$month).'.txt';


		if (extra_get_param('adssearchmetro','cache')){
		$cacheData = cacheRetrieveFile($cacheFileName, extra_get_param('adssearchmetro','cacheExpire'), 'adssearchmetro');
		if ($cacheData != false)
			{
			// We got data from cache. Return it and stop
			$template['vars']['mainblock'] = $cacheData;
			return;
			}

		}



//
    $num = intval(extra_get_param('adssearchmetro','number'));
        if (($num < 1) || ($num > 50)) {$num = 10;}    
 
    $tpath = locatePluginTemplates(array('adssearchmetro', 'entries'), 'adssearchmetro', 1);

	foreach ($mysql->select("select postdate, title, alt_name, catid, xfields_indexpreview, xfields_idobj, xfields_adres, xfields_ploshad, xfields_etaj, xfields_dometro, xfields_indexanons, xfields_price from ".uprefix."_news WHERE approve = '1' ".adsmetrotype(1)." ".adscategoryes(1)." order by xfields_price ASC LIMIT ".numpage().", 10 ") as $row) {


if ($row['xfields_indexpreview'] == ''){
$img_display = '';
}else{
$img_display = '<img src="';
$img_display .= $row['xfields_indexpreview'];
$img_display .= '" width="100px" alt="'.$row['title'].'" title="'.$row['title'].'" />';
}

if ($row['xfields_idobj'] == ''){
$id_display = '';
}else{
$id_display = $row['xfields_idobj'];
}

if ($row['xfields_dometro'] == ''){
$metro_display = '';
}else{
$metro_display = 'Ст. метро '.$row['xfields_dometro'].'<br />';
}

if ($row['xfields_indexanons'] == ''){
$anons_display = '';
}else{
$anons_display = '<br />'.$row['xfields_indexanons'];
}





        $tvars['vars'] = array(
			'link'				=>		newsGenerateLink($row),
			'date'				=>		langdate('d.m.Y', $row['postdate']),            
            'title'				=>		$row['title'],
            'xfields_idobj'		=>		$id_display,
			'xfields_adres'		=>		$row['xfields_adres'],
			'xfields_ploshad'	=>		$row['xfields_ploshad'],
			'xfields_etaj'		=>		$row['xfields_etaj'],
            'xfields_dometro'	=>		$metro_display,
            'xvalue_indexanons'	=>		$anons_display,
            'xfields_price'		=>		$row['xfields_price'],
            'img_display'		=>		$img_display

        );


        $tpl -> template('entries', $tpath['entries']);
        $tpl -> vars('entries', $tvars);
        $v .= $tpl -> show('entries');

	}



        $tvars['vars'] = array ( 'entries' => $v);
        $tpl -> template('adssearchmetro', $tpath['adssearchmetro']);
        $tpl -> vars('adssearchmetro', $tvars);
        $output .= $tpl -> show('adssearchmetro');
		$template['vars']['mainblock'] = $output;
//
		if (extra_get_param('adssearchmetro','cache')) {
		cacheStoreFile($cacheFileName, $output, 'adssearchmetro');
		}
//



}





function adstypes() {//результаты begin
    global $template, $mysql, $tpl;

		$tvars['vars'] = array	(
				'adscatsype'		=>	adscategoryes(2),				
				'adsmetrotype'		=>	adsmetrotype(2)
		);

$tpl -> template('adstypes', extras_dir."/adssearchmetro/tpl");
$tpl -> vars('adstypes', $tvars);
$output .= $tpl -> show('adstypes');
$template['vars']['adstypes'] = $output;

}//результаты end




/*пагинация*/
function numpage() {//узнаем страницу
    global $mysql;

$def_count_srch = 10;

if (isset($_GET['page'])) {
$apage = $_GET['page'];
if (!preg_match("/^[0-9]+$/", $apage)){error404();}
	
	}else{
	$apage = 0;
	}
	
$numpage = $apage*$def_count_srch-10;

if($numpage < 1){$numpage = 0;}

return $numpage;
}


function listcounts() {//узнаем кол-во результатов
$counts = mysql_result(mysql_query("SELECT count(*) FROM ".uprefix."_news WHERE approve = '1' ".adscategoryes(1)." ".adsmetrotype(1)." "),0);
//print $counts;
return $counts;
}



/**/
function listpages() {//узнаем кол-во страниц
$l_pages = (int)(listcounts()/10);
return $l_pages;
}
/**/




function pagination() {
    global $template, $mysql, $tpl;
//
if (isset($_GET['cat'])){
	$get_cats = $_GET['cat'];
	if (!preg_match("/^[0-9]+$/", $get_cats) or $get_cats <= 9 or $get_cats >= 76){//cats is cats
	error404();//404
	}else{

		$pages_cats = '?cat=';
		$pages_cats .= $get_cats;	
	
	}

}else{
$get_cats == '10';
$pages_cats = '?cat=10';
}


//
if (isset($_GET['metro'])){
	$get_metro = $_GET['metro'];
	if (!preg_match("/^[0-9]+$/", $get_metro) or $get_metro < 0 or $get_metro >= 200){//metro is metro	
	error404();//404
	}else{
		if ($get_metro == '0'){
			$pages_metro = '&metro=0';
		}else{
			$pages_metro = '&metro=';
			$pages_metro .= $get_metro;		
		}

	}

}else{
$get_metro == '0';
$pages_metro = '&metro=0';
}
//



$chislo = listpages()+1;

if (listcounts() > 10){

  for ($i=0; $i<$chislo; $i++){

	$p_num = $i+1;
	$p_link = '';

	if ($p_num == $_GET['page'] or (!$_GET['page'] and $p_num == 1)){

		$p_link .='<b>['.$p_num.']</b> ';

	}else if($_GET['page'] > $chislo or !preg_match("/^[0-9]+$/", $_GET['page'])){
	//!$_GET['page'] or 
	// !preg_match("/^[0-9]+$/", $apage)
		error404();
	}else{
	
		$p_link .= '<a href="http://metroskop.ru/plugin/adssearchmetro/';

		$p_link .= $pages_cats;
		$p_link .= $pages_metro;
		
		$p_link .= '&page=';
		$p_link .= $p_num;
		$p_link .= '">'.$p_num.'</a> ';
	}

	$linkpages.=$p_link;

//туду - исправить пагинацию для четных результатов (сейчас если 20 то появляется 3-я страница)

  }

}
//

		$tvars['vars'] = array	(
				'linkpages'			=>	$linkpages,
				'kolvo'				=>	listcounts()
		);


$tpl -> template('pagination', extras_dir."/adssearchmetro/tpl");
$tpl -> vars('pagination', $tvars);
$output .= $tpl -> show('pagination');
$template['vars']['pagination'] = $output;

    
}
/*пагинация*/


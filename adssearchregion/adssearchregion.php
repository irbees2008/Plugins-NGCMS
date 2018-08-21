<?php

if (!defined('NGCMS')) die ('HAL');


add_act('index', 'adstypes');

add_act('index', 'pagination');

register_plugin_page('adssearchregion','','adssearchregion');


function adscategoryes($str) {//категории begin
    global $mysql;

$adscats = array(
	'78'=>' жилая',
	'79'=>' коммерческая', 
	'80'=>' загородная'
	);



if (isset($_GET['cat'])) {

	$get_cats = $_GET['cat'];
		
	if (!preg_match("/^[0-9]+$/", $get_cats) or $get_cats <= 77 or $get_cats >= 81){//cats is cats
	//категории для поиска, если добавятся новые то добавим
	$get_cats = '33';
	error404();//404
	}else{

		$cats_select = 'AND catid LIKE ';
		$cats_select .= db_squote('%'.$get_cats.'%');
		$result_cats = $adscats[$get_cats].' ';

	}
				
}else{
$get_cats = '33';
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


//print adscategoryes(1);

function adszarubejname($str) {

$zarubejEntries = array();

  for ($i = 1; $i <= 62; $i++) { //туду, пересмотреть цикл

	$country_n = 'reg'.$i;
	
	if (isset($_GET[$country_n])){
	$get_country = $_GET[$country_n];
	$zarubejEntries[] = $get_country;	
	}

  }

$cleanEntries = array_unique($zarubejEntries);//проверим на дубли

//print_r ($cleanEntries);

if ($str == 'cols'){
	return count($cleanEntries);//adszarubejname(cols) возвращает кол-во элементов
}else{
	return $cleanEntries[$str];//adszarubejname($str) возвращает выбранный элемент
}


}




function adszarubejtype($str) {//страна begin
    global $mysql;
//
$arr_region = array(
'1'		=>	'Санкт-Петербург',
'2'		=>	'Астрахань',
'3'		=>	'Алушта',
'4'		=>	'Белгород',
'5'		=>	'Брянск', 
'6'		=>	'Великий Новгород', 
'7'		=>	'Владимир', 
'8'		=>	'Волгоград',
'9'		=>	'Вологда',
'10'	=>	'Воронеж',
'11'	=>	'Геленджик',
'12'	=>	'Евпатория',
'13'	=>	'Екатеринбург',
'14'	=>	'Ессентуки',
'15'	=>	'Иваново',
'16'	=>	'Ижевск',
'17'	=>	'Йошкар-Ола',
'18'	=>	'Казань',
'19'	=>	'Калининград',
'20'	=>	'Калуга',
'21'	=>	'Керчь',
'22'	=>	'Киров',
'23'	=>	'Кисловодск',
'24'	=>	'Кострома',
'25'	=>	'Краснодар',
'26'	=>	'Крым', 
'27'	=>	'Курск', 
'28'	=>	'Липецк', 
'29'	=>	'Магнитогорск',
'30'	=>	'Набережные Челны',
'31'	=>	'Нижний Новгород',
'32'	=>	'Нижний Тагил',
'33'	=>	'Новороссийск',
'34'	=>	'Новосибирск',
'35'	=>	'Орел',
'36'	=>	'Оренбург',
'37'	=>	'Пенза',
'38'	=>	'Пермь',
'39'	=>	'Петрозаводск',
'40'	=>	'Псков',
'41'	=>	'Пятигорск',
'42'	=>	'Ростов-на-Дону',
'43'	=>	'Рязань',
'44'	=>	'Самара',
'45'	=>	'Саратов',
'46'	=>	'Севастополь',
'47'	=>	'Симферополь', 
'48'	=>	'Смоленск', 
'49'	=>	'Сочи', 
'50'	=>	'Ставрополь',
'51'	=>	'Тамбов',
'52'	=>	'Тверь',
'53'	=>	'Тольятти',
'54'	=>	'Тула',
'55'	=>	'Ульяновск',
'56'	=>	'Уфа',
'57'	=>	'Феодосия',
'58'	=>	'Чебоксары',
'59'	=>	'Челябинск',
'60'	=>	'Ярославль',
'61'	=>	'Ялта'
);
//


//туду - проверить новый вариант со множественным выбором на уязвимости и баги
if (adszarubejname(cols) > 0){


/*
		if ($get_metro == '0'){
		
			$adsmetro_select = '';
			$result_post_metro = '';
			
			}else{
*/
			

//			$adsmetro_select = 'AND xfields_dometro = ';
//			$adsmetro_select .= db_squote($arr_metro[$get_metro]);

$adsregion_select = 'AND xfields_region IN (';
$adsregion_select .= db_squote($arr_region[adszarubejname('0')]);

$result_post_region = 'регионы: '.$arr_region[adszarubejname('0')];

for ($i = 1; $i <= adszarubejname(cols); $i++) {


$g_region = adszarubejname($i);
$get_region = adszarubejname($i).'-';

		if ($i < adszarubejname(cols)){
		$adsregion_select .= ', '.db_squote($arr_region[$g_region]);
		$result_post_region .= ', '.$arr_region[$g_region];
		}

}

	$adsregion_select .= ')';		

						
//			}
	
//		}

			
}else{
$get_metro = '0';
$adsregion_select = '';
$result_post_metro = '';
}


if ($str == '1'){
$arg = $adsregion_select;
}else if($str == '2'){
$arg = $result_post_region.' ';
}else if($str == '3'){
$arg = $get_region;
}

return $arg;

}//страна end


//print adszarubejtype(1).'<br />'.adscategoryes(1);



function adssearchregion() {//берем основные данные
    global $template, $mysql, $tpl, $SYSTEM_FLAGS;

$numstr = numpage()/10;
$numstr = $numstr+1;
$SYSTEM_FLAGS['info']['title']['group'] = 'Поиск в регионах - страница '.$numstr;
$SYSTEM_FLAGS['meta']['description'] = 'Поиск в регионах - страница '.$numstr;
$SYSTEM_FLAGS['meta']['keywords'] = adscategoryes(2).', '.adszarubejtype(2);

//кеширование
//делаем "сложный" кеш, т.е. свой для каждой страницы
//поэтому кроме md5 для разных категорий и станций используем их гет-значения:
//adsmetrotype(3).'_'.adscategoryes(3).'_'.


$cacheFileName = adszarubejtype(3).'_'.adscategoryes(3).'_'.numpage().'_'.md5('adssearchregion'.$config['theme'].$config['default_lang'].$year.$month).'.txt';


		if (extra_get_param('adssearchregion','cache')){
		$cacheData = cacheRetrieveFile($cacheFileName, extra_get_param('adssearchregion','cacheExpire'), 'adssearchregion');
		if ($cacheData != false)
			{
			// We got data from cache. Return it and stop
			$template['vars']['mainblock'] = $cacheData;
			return;
			}

		}



//
    $num = intval(extra_get_param('adssearchregion','number'));
        if (($num < 1) || ($num > 50)) {$num = 10;}    
 
    $tpath = locatePluginTemplates(array('adssearchregion', 'entries'), 'adssearchregion', 1);

	foreach ($mysql->select("select postdate, title, alt_name, catid, xfields_indexpreview, xfields_idobj, xfields_adres, xfields_ploshad, xfields_etaj, xfields_region, xfields_indexanons, xfields_price from ".uprefix."_news WHERE approve = '1' ".adszarubejtype(1)." ".adscategoryes(1)." order by xfields_price ASC LIMIT ".numpage().", 10 ") as $row) {


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

if ($row['xfields_region'] == ''){
$region_display = '';
}else{
$region_display = $row['xfields_region'].'<br />';
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
            'xfields_price'		=>		$row['xfields_price'],
            'xfields_region'	=>		$region_display,
            'xvalue_indexanons'	=>		$anons_display,
            'img_display'		=>		$img_display

        );


        $tpl -> template('entries', $tpath['entries']);
        $tpl -> vars('entries', $tvars);
        $v .= $tpl -> show('entries');

	}



        $tvars['vars'] = array ( 'entries' => $v);
        $tpl -> template('adssearchregion', $tpath['adssearchregion']);
        $tpl -> vars('adssearchregion', $tvars);
        $output .= $tpl -> show('adssearchregion');
		$template['vars']['mainblock'] = $output;
//
		if (extra_get_param('adssearchregion','cache')) {
		cacheStoreFile($cacheFileName, $output, 'adssearchregion');
		}
//



}





function adstypes() {//результаты begin
    global $template, $mysql, $tpl;

		$tvars['vars'] = array	(
				'adscatsype'		=>	adscategoryes(2),				
				'adsregiontype'		=>	adszarubejtype(2)
		);

$tpl -> template('adstypes', extras_dir."/adssearchregion/tpl");
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
$counts = mysql_result(mysql_query("SELECT count(*) FROM ".uprefix."_news WHERE approve = '1' ".adscategoryes(1)." ".adszarubejtype(1)." "),0);
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
	if (!preg_match("/^[0-9]+$/", $get_cats) or $get_cats <= 77 or $get_cats >= 81){//cats is cats
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
/*
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
*/
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
//		error404();
	}else{
	
		$p_link .= '<a href="http://metroskop.ru/plugin/adssearchregion/';

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


$tpl -> template('pagination', extras_dir."/adssearchregion/tpl");
$tpl -> vars('pagination', $tvars);
$output .= $tpl -> show('pagination');
$template['vars']['pagination'] = $output;

    
}
/*пагинация*/


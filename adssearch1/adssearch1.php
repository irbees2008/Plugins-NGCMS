<?php

if (!defined('NGCMS')) die ('HAL');

add_act('index', 'adstypes');

add_act('index', 'pagination');

register_plugin_page('adssearch1','','adssearch1');


function adspricefrom($str) {//цена от begin
	global $mysql;

if (isset($_GET['pricefrom'])) {
	$get_pfrom = $_GET['pricefrom'];

		if ($get_pfrom == ''){
		$pricefrom_select = '';
		$result_pricefrom = '';		
		}else if (!preg_match("/^[0-9]+$/", $get_pfrom) ){//price is price
		$get_pfrom = '0';
		error404();//404
		}else{
		$pricefrom_select = 'AND xfields_price >= ';
		$pricefrom_select .= db_squote($get_pfrom);
		$result_pricefrom = 'от '.$get_pfrom;
		}

	}else{
	$get_pfrom = '';
	$pricefrom_select = '';
	$result_pricefrom = '';
	}


if ($str == '1'){
$arg = $pricefrom_select;
}else if($str == '2'){
$arg = $result_pricefrom.' ';
}else if($str == '3'){
$arg = $get_pfrom;
}

return $arg;

}//цена от end

function adspricefor($str) {//цена до begin
	global $mysql;

if (isset($_GET['pricefor'])) {
	$get_pfor = $_GET['pricefor'];

		if ($get_pfor == ''){
		$pricefor_select = '';
		$result_pricefor = '';		
		}else if (!preg_match("/^[0-9]+$/", $get_pfor) ){//price is price
		$get_pfor = '0';
		error404();//404
		}else{
		$pricefor_select = 'AND xfields_price <= ';
		$pricefor_select .= db_squote($get_pfor);
		$result_pricefor = 'до '.$get_pfor;
		}

	}else{
	$get_pfor = '';
	$pricefor_select = '';
	$result_pricefor = '';
	}


if ($str == '1'){
$arg = $pricefor_select;
}else if($str == '2'){
$arg = $result_pricefor.' ';
}else if($str == '3'){
$arg = $get_pfor;
}

return $arg;

}//цена до end
//туду - сделать чтобы цена "до" не могла быть больше чем цена "от"


function adsquarefrom($str) {//плошадь от begin
	global $mysql;

if (isset($_GET['squarefrom'])) {
	$get_sfrom = $_GET['squarefrom'];

		if ($get_sfrom == ''){
		$squarefrom_select = '';
		$result_squarefrom = '';		
		}else if (!preg_match("/^[0-9]+$/", $get_sfrom) ){//square is square
		$get_sfrom = '0';
		error404();//404
		}else{
		$squarefrom_select = 'AND xfields_ploshad >= ';
		$squarefrom_select .= db_squote($get_sfrom);
		$result_squarefrom = 'от '.$get_sfrom;
		}

	}else{
	$get_sfrom = '';
	$squarefrom_select = '';
	$result_squarefrom = '';
	}


if ($str == '1'){
$arg = $squarefrom_select;
}else if($str == '2'){
$arg = $result_squarefrom.' ';
}else if($str == '3'){
$arg = $get_sfrom;
}

return $arg;


}//плошадь от end
//туду - сделать чтобы плошадь "до" не могла быть больше чем плошадь "от"

function adsquarefor($str) {//плошадь до begin
	global $mysql;

if (isset($_GET['squarefor'])) {
	$get_sfor = $_GET['squarefor'];

		if ($get_sfor == ''){
		$squarefor_select = '';
		$result_squarefor = '';		
		}else if (!preg_match("/^[0-9]+$/", $get_sfor) ){//price is price
		$get_pfor = '0';
		error404();//404
		}else{
		$squarefor_select = 'AND xfields_ploshad <= ';
		$squarefor_select .= db_squote($get_sfor);
		$result_squarefor = 'до '.$get_sfor;
		}

	}else{
	$get_sfor = '';
	$squarefor_select = '';
	$result_squarefor = '';
	}


if ($str == '1'){
$arg = $squarefor_select;
}else if($str == '2'){
$arg = $result_squarefor.' ';
}else if($str == '3'){
$arg = $get_sfor;
}

return $arg;

}//плошадь до end




function adssearch1() {//берем основные данные
    global $template, $mysql, $tpl, $SYSTEM_FLAGS;

$SYSTEM_FLAGS['info']['title']['group'] = 'Результаты поиска';

//кеширование
//делаем "сложный" кеш, т.е. свой для каждой страницы
//поэтому кроме md5 для разных категорий и станций используем их гет-значения:
//adsmetrotype(3).'_'.adscategoryes(3).'_'.


$cacheFileName = adspricefrom(3).'_'.adspricefor(3).'_'.adsquarefrom(3).'_'.adsquarefor(3).'_'.numpage().'_'.md5('adssearch1'.$config['theme'].$config['default_lang'].$year.$month).'.txt';


		if (extra_get_param('adssearch1','cache')){
		$cacheData = cacheRetrieveFile($cacheFileName, extra_get_param('adssearch1','cacheExpire'), 'adssearch1');
		if ($cacheData != false)
			{
			// We got data from cache. Return it and stop
			$template['vars']['mainblock'] = $cacheData;
			return;
			}

		}

//??
    $num = intval(extra_get_param('adssearch1','number'));
        if (($num < 1) || ($num > 50)) {$num = 10;}    
 
    $tpath = locatePluginTemplates(array('adssearch1', 'entries'), 'adssearch1', 1);
//??

	foreach ($mysql->select("select postdate, title, alt_name, catid, xfields_indexpreview, xfields_idobj, xfields_adres, xfields_ploshad, xfields_etaj, xfields_region, xfields_indexanons, xfields_price from ".uprefix."_news where approve = '1' AND xfields_idobj !='' AND catid IN ('5,31', '5,32', '5,33') ".adspricefrom(1)." ".adspricefor(1)." ".adsquarefrom(1)." ".adsquarefor(1)." order by xfields_price ASC LIMIT ".numpage().", 10 ") as $row) {

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
$metro_display = '';
}else{
$metro_display = $row['xfields_region'].'<br />';
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
        $tpl -> template('adssearch1', $tpath['adssearch1']);
        $tpl -> vars('adssearch1', $tvars);
        $output .= $tpl -> show('adssearch1');
		$template['vars']['mainblock'] = $output;


//
		if (extra_get_param('adssearch1','cache')) {
		cacheStoreFile($cacheFileName, $output, 'adssearch1');
		}
//



}






function adstypes() {//результаты begin
    global $template, $mysql, $tpl;

	if (adspricefrom(3) != '' or adspricefor(3) != ''){
	$adspricetype = 'цена: '.adspricefrom(2).adspricefor(2).' &euro;';
	}else{
	$adspricetype = 'цена: любая';
	}

	if (adsquarefrom(3) != '' or adsquarefor(3) != ''){
	$adsquaretype = 'площадь: '.adsquarefrom(2).adsquarefor(2).' М2';
	}else{
	$adsquaretype = 'площадь: любая';
	}

		$tvars['vars'] = array	(
				'adspricetype'		=>	$adspricetype,
				'adsquaretype'		=>	$adsquaretype
		);

$tpl -> template('adstypes', extras_dir."/adssearch1/tpl");
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
$counts = mysql_result(mysql_query("SELECT count(*) FROM ".uprefix."_news WHERE approve = '1' AND xfields_idobj !='' AND catid IN ('5,31', '5,32', '5,33') ".adspricefrom(1)." ".adspricefor(1)." ".adsquarefrom(1)." ".adsquarefor(1)." "),0);
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
if (isset($_GET['pricefrom'])){
	$get_pfrom = $_GET['pricefrom'];
	if ($get_pfrom == ''){
	$get_pfrom == '0';
	$pages_pfrom = '?pricefrom=0';
	}else if (!preg_match("/^[0-9]+$/", $get_pfrom)){//price is price
	error404();//404
	}else{

		$pages_pfrom = '?pricefrom=';
		$pages_pfrom .= $get_pfrom;	
	
	}

}else{
$get_pfrom == '0';
$pages_pfrom = '?pricefrom=0';
}
//
if (isset($_GET['pricefor'])){
	$get_pfor = $_GET['pricefor'];
	if ($get_pfor == ''){
	$get_pfor == '9999999999999999';
	$pages_pfor = '&pricefor=9999999999999999';
	}else if (!preg_match("/^[0-9]+$/", $get_pfor)){//price is price
	error404();//404
	}else{

		$pages_pfor = '&pricefor=';
		$pages_pfor .= $get_pfor;	
	
	}

}else{
$get_pfor == '9999999999999999';
$pages_pfor = '&pricefor=9999999999999999';
}
//
if (isset($_GET['squarefrom'])){
	$get_sfrom = $_GET['squarefrom'];
	if ($get_sfrom == ''){
	$get_sfrom == '0';
	$pages_sfrom = '&squarefrom=0';
	}else if (!preg_match("/^[0-9]+$/", $get_sfrom)){//square is square
	error404();//404
	}else{

		$pages_sfrom = '&squarefrom=';
		$pages_sfrom .= $get_sfrom;	
	
	}

}else{
$get_sfrom == '0';
$pages_sfrom = '&squarefor=0';
}
//
if (isset($_GET['squarefor'])){
	$get_sfor = $_GET['squarefor'];
	if ($get_sfor == ''){
	$get_sfor == '9999999999999999';
	$pages_sfor = '&squarefor=9999999999999999';
	}else if (!preg_match("/^[0-9]+$/", $get_sfor)){//square is square
	error404();//404
	}else{

		$pages_sfor = '&squarefor=';
		$pages_sfor .= $get_sfor;	
	
	}

}else{
$get_sfor == '9999999999999999';
$pages_sfor = '&squarefor=9999999999999999';
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
	
		$p_link .= '<a href="http://metroskop.ru/plugin/adssearch1/';

		$p_link .= $pages_pfrom;
		$p_link .= $pages_pfor;
		$p_link .= $pages_sfrom;
		$p_link .= $pages_sfor;
				
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


$tpl -> template('pagination', extras_dir."/adssearch1/tpl");
$tpl -> vars('pagination', $tvars);
$output .= $tpl -> show('pagination');
$template['vars']['pagination'] = $output;

    
}
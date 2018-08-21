<?php

if (!defined('NGCMS')) exit ('HAL');

register_plugin_page('ga_stat','','ga_stat_show');
add_act('index_post', 'ga_stat_header_show');

include_once (root."/plugins/ga_stat/ga_update.php");

function ga_stat_show($params)
{
global $catz, $twig, $catmap, $mysql, $config, $userROW, $tpl, $parse, $template, $lang, $PFILTERS, $SYSTEM_FLAGS, $CurrentHandler;
	
	$tpath = locatePluginTemplates(array('ga_stat','show'), 'ga_stat', 1);
	$xt = $twig->loadTemplate($tpath['show'].'show.tpl');
	
	
	$tVars = array(
		'time' => mktime(),
		'url_data' => admin_url."/plugins/ga_stat/data",
	);
	
	$template['vars']['mainblock'] = $xt->render($tVars);
}

function ga_stat_header_show()
{
global $CurrentHandler, $SYSTEM_FLAGS, $template, $lang;
	
if(checkLinkAvailable('ga_stat', '')){
  if($CurrentHandler['handlerParams']['value']['pluginName'] == 'core')
   return error404();
}

switch ($CurrentHandler['handlerName'])
	{
	case '':
	$title_page = pluginGetVariable('ga_stat', 'title_page');

	$description_page = pluginGetVariable('ga_stat', 'description_page');
	$keywords_page = pluginGetVariable('ga_stat', 'keywords_page');
	
	$SYSTEM_FLAGS['meta']['description'] = isset($description_page)?$description_page:$SYSTEM_FLAGS['meta']['description'];
	$SYSTEM_FLAGS['meta']['keywords'] = isset($keywords_page)?$keywords_page:$SYSTEM_FLAGS['meta']['keywords'];
	
	$titles = $SYSTEM_FLAGS['info']['title']['header']." / ".$SYSTEM_FLAGS['info']['title']['group']." / ".$title_page;
	break;
	}
	
	$template['vars']['titles'] = trim($titles);
	
}


function plugin_ga_stat_cron()
{
//учетная запись GA
$u = pluginGetVariable('ga_stat', 'username');
$p = pluginGetVariable('ga_stat', 'password');
$id = pluginGetVariable('ga_stat', 'id_site'); 

//текущая дата
$currentdate=date("Ymd");
//дата, начиная с которой необходимо получить данные из GA для отчета. Формат YYYY-MM-DD
$datestart = pluginGetVariable('ga_stat', 'date_start');
//дата, заканчивая которой
//$datefinish="";
//или вычисляем дату - конец предыдущего месяца
$currentday=date("d");$currentmonth=date("m");$currentyear=date("Y");
$datefinish=date("Y-m-d",mktime(0,0,0,$currentmonth,0,$currentyear));

//дата 3 месяца назад
$date3MonthStart=date("Y-m-d",mktime(0,0,0,$currentmonth-3,$currentday-1,$currentyear));
$date3MonthFinish=date("Y-m-d",mktime(0,0,0,$currentmonth,$currentday-1,$currentyear));

//дата месяц назад
$date1MonthStart=date("Y-m-d",mktime(0,0,0,$currentmonth-1,$currentday-1,$currentyear));
$date1MonthFinish=date("Y-m-d",mktime(0,0,0,$currentmonth,$currentday-1,$currentyear));

//количество стран
$countryRows=pluginGetVariable('ga_stat', 'country_rows');
//количество городов
$cityRows=pluginGetVariable('ga_stat', 'city_rows');
//количество реферов
$referrersRows=pluginGetVariable('ga_stat', 'referrers_rows');
//количество браузеров
$browsersRows=pluginGetVariable('ga_stat', 'browsers_rows');
//количество ОС
$osRows=pluginGetVariable('ga_stat', 'os_rows');


//csv-файл для отчета Посетители
$visitorsCSV="visitors.csv";
//csv-файл для отчета Посетители за посл. 3 месяца
$visitors3CSV="visitors_3.csv";
//csv-файл для отчета География по странам
$countryCSV="country.csv";
//csv-файл для отчета География по городам
$cityCSV="city.csv";
//csv-файл для отчета Источники
$sourceCSV="referrers.csv";
//csv-файл для отчета Броузеры
$browsersCSV="browsers.csv";
//csv-файл для отчета ОС
$osCSV="os.csv";
//файл со статистикой до начала использования GA. Формат: дата;посетители;просмотры
//$addFile="default.csv";
$addFile=false;

//полный пусть к директории со скриптом (слэш в конце обязателен!)
$path=root."plugins/ga_stat/data/";

//echo $path;

//подключаем класс GA API
include(root."/plugins/ga_stat/gapi.class.php");

$ga = new gapi($u,$p);


//////получаем пользователи/просмотры за все время
$ga->requestReportData($id,array('month','year'),array('visitors','pageviews'),'year',null,$datestart, $datefinish,1,1000);

//переменная для записи резалта
$output="";
if($addFile) {$add=file_get_contents($path.$addFile); $output.=trim($add)."\n";}



//получаем и обрабатываем результаты
foreach($ga->getResults() as $result)
{
$m=$result; //месяц год
$visitors=$result->getVisitors(); //посетители
$pageviews=$result->getPageviews(); //просмотры

//приводим дату к удобочитаемому виду ,мменяем пробелы на точки
$m=str_replace(" ",".",$m);

//формируем строку
$output.=$m.";".$visitors.";".$pageviews."\n";
}

//пишем в файл
$fp=fopen($path.$visitorsCSV,"w");
fputs($fp,trim($output));
fclose($fp);




//////получаем пользователи/просмотры/посещения за последние 3 месяца
$ga->requestReportData($id,array('day','month','year'),array('visitors','visits','pageviews'),array('year','month'),null,$date3MonthStart, $date3MonthFinish,1,1000);

//переменная для записи резалта
$output="";


//получаем и обрабатываем результаты
foreach($ga->getResults() as $result)
{
$d=$result; //день
$visitors=$result->getVisitors(); //посетители
$pageviews=$result->getPageviews(); //просмотры
$visits=$result->getVisits(); //посещения

//приводим дату к удобочитаемому виду ,мменяем пробелы на точки
$d=str_replace(" ",".",$d);

//формируем строку
$output.=$d.";".$visitors.";".$pageviews.";".$visits."\n";
}

//пишем в файл
$fp=fopen($path.$visitors3CSV,"w");
fputs($fp,trim($output));
fclose($fp);






//////получаем географию посещений за все время
$ga->requestReportData($id,array('country'),array('visits'),null,null,null,null,1,$countryRows);

//переменная для записи резалта
$output="";

//получаем общее число посещений для всех стран
$total_visits=$ga->getVisits();

//получаем и обрабатываем результаты
foreach($ga->getResults() as $result)
{
$country=$result->getCountry(); //страна
$visits=$result->getVisits(); //кол-во посещений

//нот сет переводим на русский
//$country=str_replace("(not set)","не определено",$country);

//формируем строку
$output.=$country.";".$visits."\n";
}

//пишем в файл
$fp=fopen($path.$countryCSV,"w");
fputs($fp,trim($output));
fclose($fp);




//////получаем ГОРОДА за всё время
$ga->requestReportData($id,array('city'),array('visits'),null,null,null,null,1,$cityRows);

//переменная для записи резалта
$output="";

//получаем общее число посещений для всех стран
$total_visits=$ga->getVisits();

//получаем и обрабатываем результаты
foreach($ga->getResults() as $result)
{
$city=$result->getCity(); //страна
$visits=$result->getVisits(); //кол-во посещений

//нот сет переводим на русский
//$city=str_replace("(not set)","не определено",$city);

//формируем строку
$output.=$city.";".$visits."\n";
}

//пишем в файл
$fp=fopen($path.$cityCSV,"w");
fputs($fp,trim($output));
fclose($fp);





//////получаем ИСТОЧНИКИ за все время
$ga->requestReportData($id,array('source'),array('visits'),null,null,null,null,1,$referrersRows);

//переменная для записи резалта
$output="";

//получаем общее число посещений для всех стран
$total_visits=$ga->getVisits();

//получаем и обрабатываем результаты
foreach($ga->getResults() as $result)
{
$source=$result->getSource(); //страна
$visits=$result->getVisits(); //кол-во посещений

//нот сет переводим на русский
//$source=str_replace("(direct)","закладки",$source);

//формируем строку
$output.=$source.";".$visits."\n";
}

//пишем в файл
$fp=fopen($path.$sourceCSV,"w");
fputs($fp,trim($output));
fclose($fp);



//////получаем БРАУЗЕРЫ за все время
$ga->requestReportData($id,array('browser'),array('visits'),null,null,null,null,1,$browsersRows);

//переменная для записи резалта
$output="";

//получаем общее число посещений для всех стран
$total_visits=$ga->getVisits();

//получаем и обрабатываем результаты
foreach($ga->getResults() as $result)
{
$browser=$result->getBrowser(); //страна
$visits=$result->getVisits(); //кол-во посещений

//нот сет переводим на русский
//$browser=str_replace("(not set)","не определено",$browser);

//формируем строку
$output.=$browser.";".$visits."\n";
}

//пишем в файл
$fp=fopen($path.$browsersCSV,"w");
fputs($fp,trim($output));
fclose($fp);



//////получаем ОС за всё время
$ga->requestReportData($id,array('operatingSystem'),array('visits'),null,null,null,null,1,$osRows);

//переменная для записи резалта
$output="";

//получаем общее число посещений для всех стран
$total_visits=$ga->getVisits();

//получаем и обрабатываем результаты
foreach($ga->getResults() as $result)
{
$operatingSystem=$result->getOperatingSystem(); //страна
$visits=$result->getVisits(); //кол-во посещений

//нот сет переводим на русский
//$operatingSystem=str_replace("(not set)","не определено",$operatingSystem);

//формируем строку
$output.=$operatingSystem.";".$visits."\n";
}

//пишем в файл
$fp=fopen($path.$osCSV,"w");
fputs($fp,trim($output));
fclose($fp);

}
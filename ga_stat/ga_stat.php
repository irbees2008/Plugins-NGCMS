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
//������� ������ GA
$u = pluginGetVariable('ga_stat', 'username');
$p = pluginGetVariable('ga_stat', 'password');
$id = pluginGetVariable('ga_stat', 'id_site'); 

//������� ����
$currentdate=date("Ymd");
//����, ������� � ������� ���������� �������� ������ �� GA ��� ������. ������ YYYY-MM-DD
$datestart = pluginGetVariable('ga_stat', 'date_start');
//����, ���������� �������
//$datefinish="";
//��� ��������� ���� - ����� ����������� ������
$currentday=date("d");$currentmonth=date("m");$currentyear=date("Y");
$datefinish=date("Y-m-d",mktime(0,0,0,$currentmonth,0,$currentyear));

//���� 3 ������ �����
$date3MonthStart=date("Y-m-d",mktime(0,0,0,$currentmonth-3,$currentday-1,$currentyear));
$date3MonthFinish=date("Y-m-d",mktime(0,0,0,$currentmonth,$currentday-1,$currentyear));

//���� ����� �����
$date1MonthStart=date("Y-m-d",mktime(0,0,0,$currentmonth-1,$currentday-1,$currentyear));
$date1MonthFinish=date("Y-m-d",mktime(0,0,0,$currentmonth,$currentday-1,$currentyear));

//���������� �����
$countryRows=pluginGetVariable('ga_stat', 'country_rows');
//���������� �������
$cityRows=pluginGetVariable('ga_stat', 'city_rows');
//���������� �������
$referrersRows=pluginGetVariable('ga_stat', 'referrers_rows');
//���������� ���������
$browsersRows=pluginGetVariable('ga_stat', 'browsers_rows');
//���������� ��
$osRows=pluginGetVariable('ga_stat', 'os_rows');


//csv-���� ��� ������ ����������
$visitorsCSV="visitors.csv";
//csv-���� ��� ������ ���������� �� ����. 3 ������
$visitors3CSV="visitors_3.csv";
//csv-���� ��� ������ ��������� �� �������
$countryCSV="country.csv";
//csv-���� ��� ������ ��������� �� �������
$cityCSV="city.csv";
//csv-���� ��� ������ ���������
$sourceCSV="referrers.csv";
//csv-���� ��� ������ ��������
$browsersCSV="browsers.csv";
//csv-���� ��� ������ ��
$osCSV="os.csv";
//���� �� ����������� �� ������ ������������� GA. ������: ����;����������;���������
//$addFile="default.csv";
$addFile=false;

//������ ����� � ���������� �� �������� (���� � ����� ����������!)
$path=root."plugins/ga_stat/data/";

//echo $path;

//���������� ����� GA API
include(root."/plugins/ga_stat/gapi.class.php");

$ga = new gapi($u,$p);


//////�������� ������������/��������� �� ��� �����
$ga->requestReportData($id,array('month','year'),array('visitors','pageviews'),'year',null,$datestart, $datefinish,1,1000);

//���������� ��� ������ �������
$output="";
if($addFile) {$add=file_get_contents($path.$addFile); $output.=trim($add)."\n";}



//�������� � ������������ ����������
foreach($ga->getResults() as $result)
{
$m=$result; //����� ���
$visitors=$result->getVisitors(); //����������
$pageviews=$result->getPageviews(); //���������

//�������� ���� � �������������� ���� ,������� ������� �� �����
$m=str_replace(" ",".",$m);

//��������� ������
$output.=$m.";".$visitors.";".$pageviews."\n";
}

//����� � ����
$fp=fopen($path.$visitorsCSV,"w");
fputs($fp,trim($output));
fclose($fp);




//////�������� ������������/���������/��������� �� ��������� 3 ������
$ga->requestReportData($id,array('day','month','year'),array('visitors','visits','pageviews'),array('year','month'),null,$date3MonthStart, $date3MonthFinish,1,1000);

//���������� ��� ������ �������
$output="";


//�������� � ������������ ����������
foreach($ga->getResults() as $result)
{
$d=$result; //����
$visitors=$result->getVisitors(); //����������
$pageviews=$result->getPageviews(); //���������
$visits=$result->getVisits(); //���������

//�������� ���� � �������������� ���� ,������� ������� �� �����
$d=str_replace(" ",".",$d);

//��������� ������
$output.=$d.";".$visitors.";".$pageviews.";".$visits."\n";
}

//����� � ����
$fp=fopen($path.$visitors3CSV,"w");
fputs($fp,trim($output));
fclose($fp);






//////�������� ��������� ��������� �� ��� �����
$ga->requestReportData($id,array('country'),array('visits'),null,null,null,null,1,$countryRows);

//���������� ��� ������ �������
$output="";

//�������� ����� ����� ��������� ��� ���� �����
$total_visits=$ga->getVisits();

//�������� � ������������ ����������
foreach($ga->getResults() as $result)
{
$country=$result->getCountry(); //������
$visits=$result->getVisits(); //���-�� ���������

//��� ��� ��������� �� �������
//$country=str_replace("(not set)","�� ����������",$country);

//��������� ������
$output.=$country.";".$visits."\n";
}

//����� � ����
$fp=fopen($path.$countryCSV,"w");
fputs($fp,trim($output));
fclose($fp);




//////�������� ������ �� �� �����
$ga->requestReportData($id,array('city'),array('visits'),null,null,null,null,1,$cityRows);

//���������� ��� ������ �������
$output="";

//�������� ����� ����� ��������� ��� ���� �����
$total_visits=$ga->getVisits();

//�������� � ������������ ����������
foreach($ga->getResults() as $result)
{
$city=$result->getCity(); //������
$visits=$result->getVisits(); //���-�� ���������

//��� ��� ��������� �� �������
//$city=str_replace("(not set)","�� ����������",$city);

//��������� ������
$output.=$city.";".$visits."\n";
}

//����� � ����
$fp=fopen($path.$cityCSV,"w");
fputs($fp,trim($output));
fclose($fp);





//////�������� ��������� �� ��� �����
$ga->requestReportData($id,array('source'),array('visits'),null,null,null,null,1,$referrersRows);

//���������� ��� ������ �������
$output="";

//�������� ����� ����� ��������� ��� ���� �����
$total_visits=$ga->getVisits();

//�������� � ������������ ����������
foreach($ga->getResults() as $result)
{
$source=$result->getSource(); //������
$visits=$result->getVisits(); //���-�� ���������

//��� ��� ��������� �� �������
//$source=str_replace("(direct)","��������",$source);

//��������� ������
$output.=$source.";".$visits."\n";
}

//����� � ����
$fp=fopen($path.$sourceCSV,"w");
fputs($fp,trim($output));
fclose($fp);



//////�������� �������� �� ��� �����
$ga->requestReportData($id,array('browser'),array('visits'),null,null,null,null,1,$browsersRows);

//���������� ��� ������ �������
$output="";

//�������� ����� ����� ��������� ��� ���� �����
$total_visits=$ga->getVisits();

//�������� � ������������ ����������
foreach($ga->getResults() as $result)
{
$browser=$result->getBrowser(); //������
$visits=$result->getVisits(); //���-�� ���������

//��� ��� ��������� �� �������
//$browser=str_replace("(not set)","�� ����������",$browser);

//��������� ������
$output.=$browser.";".$visits."\n";
}

//����� � ����
$fp=fopen($path.$browsersCSV,"w");
fputs($fp,trim($output));
fclose($fp);



//////�������� �� �� �� �����
$ga->requestReportData($id,array('operatingSystem'),array('visits'),null,null,null,null,1,$osRows);

//���������� ��� ������ �������
$output="";

//�������� ����� ����� ��������� ��� ���� �����
$total_visits=$ga->getVisits();

//�������� � ������������ ����������
foreach($ga->getResults() as $result)
{
$operatingSystem=$result->getOperatingSystem(); //������
$visits=$result->getVisits(); //���-�� ���������

//��� ��� ��������� �� �������
//$operatingSystem=str_replace("(not set)","�� ����������",$operatingSystem);

//��������� ������
$output.=$operatingSystem.";".$visits."\n";
}

//����� � ����
$fp=fopen($path.$osCSV,"w");
fputs($fp,trim($output));
fclose($fp);

}
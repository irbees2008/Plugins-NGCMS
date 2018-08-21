<?php

if (!defined('NGCMS')) die ('HAL');


add_act('index', 'adstypes');

add_act('index', 'pagination');

register_plugin_page('adssearchmetro','','adssearchmetro');


function adscategoryes($str) {//��������� begin
    global $mysql;

$adscats = array(
	'10'=>' ��� ����������� �� ������� ����� ������������',
	'37'=>' ������� ������', 
	'38'=>' ������� 1-��������� �������',
	'39'=>' ������� 2-��������� �������',
	'40'=>' ������� 3-��������� �������',
	'41'=>' ������� 4- � ����� ��������� �������',
	'42'=>' ������� � ������������',	
	'11'=>' ��� ����������� �� ������� ������������ ������������',
	'47'=>' ������� ������',
	'48'=>' ������� �������� ���������',
	'49'=>' ������� �������',
	'50'=>' ������� ��������� ��� ��������',
	'51'=>' ������� ��������� ��� ���. �����',
	'52'=>' ������� �������',
	'53'=>' ������� ��������� ���������� ����������',
	'54'=>' ������� ������ (���)',
	'55'=>' ������� ���������������� ���������',
	'56'=>' ������� �������� �������',	
	'12'=>' ��� ����������� �� ������ ����� ������������',
	'57'=>' ������ ������', 
	'58'=>' ������ 1-��������� �������',
	'59'=>' ������ 2-��������� �������',
	'60'=>' ������ 3-��������� �������',
	'61'=>' ������ 4- � ����� ��������� �������',
	'62'=>' ������ � ������������',	
	'13'=>' ��� ����������� �� ������ ������������ ������������',
	'67'=>' ������ ������',
	'68'=>' ������ �������� ���������',
	'69'=>' ������ �������',
	'70'=>' ������ ��������� ��� ��������',
	'71'=>' ������ ��������� ��� ���. �����',
	'72'=>' ������ �������',
	'73'=>' ������ ��������� ���������� ����������',
	'74'=>' ������ ������ (���)',
	'75'=>' ������ ���������������� ���������'
	);


if (isset($_GET['cat'])) {
	$get_cats = $_GET['cat'];
		
	if (!preg_match("/^[0-9]+$/", $get_cats) or $get_cats <= 9 or $get_cats >= 76){//metro is metro
	//���� ��� ��������� ��� ������ �� 10 �� 75, ���� ��������� ����� �� �������
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

}//��������� end


function adsmetroname($str) {

$metroEntries = array();

  for ($i = 1; $i <= 220; $i++) { //����, ������������ ����

	$station_n = 'metro'.$i;
	
	if (isset($_GET[$station_n])){
	$get_metro = $_GET[$station_n];
	$metroEntries[] = $get_metro;	
	}

  }


$cleanEntries = array_unique($metroEntries);//�������� �� �����

//print_r ($cleanEntries);

if ($str == 'cols'){
return count($cleanEntries);//adsmetroname(cols) ���������� ���-�� ���������
}else{
return $cleanEntries[$str];//adsmetroname($str) ���������� ��������� �������
}


}


//print adsmetroname(2);
//print '<br />';
//print adsmetroname(cols);


function adsmetrotype($str) {//����� begin
    global $mysql;
//
$arr_metro = array(
'108'	=>	'������������',
'11'	=>	'�������������',
'80'	=>	'�������������',
'52'	=>	'��������������� ���',
'71'	=>	'������������', 
'123'	=>	'���������', 
'166'	=>	'������', 
'42'	=>	'���������',
'53'	=>	'���������',
'4'		=>	'��������',
'75'	=>	'������������',
'59'	=>	'���������������',
'158'	=>	'�����������',
'45'	=>	'����������',
'98'	=>	'�������',
'155'	=>	'�����������',
'156'	=>	'�������',
'122'	=>	'��������',
'31'	=>	'���������� ��. ������',
'87'	=>	'���������� ����',
'112'	=>	'����������',
'189'	=>	'���������� �����',
'73'	=>	'������������ ���',
'167'	=>	'�-� ������� ��������',
'151'	=>	'�������������',
'173'	=>	'������� ���. �������',
'174'	=>	'��������� �����',
'14'	=>	'����������',
'72'	=>	'����',
'120'	=>	'���������',
'1'		=>	'������ �������',
'2'		=>	'����������',
'92'	=>	'������������� ��������',
'149'	=>	'��������',
'175'	=>	'�������������',
'36'	=>	'��������� ����',
'186'	=>	'����������',
'200'	=>	'����������� �����',
'96'	=>	'������',
'181'	=>	'������� �����',
'5'		=>	'������',
'117'	=>	'�����������',
'138'	=>	'������������',
'19'	=>	'�������������',
'192'	=>	'�����������',
'105'	=>	'��������',
'183'	=>	'��������',
'49'	=>	'������������',
'83'	=>	'���������',
'16'	=>	'��������������',
'15'	=>	'���������',
'13'	=>	'���������',
'55'	=>	'�������� ',
'66'	=>	'�����-�����',
'147'	=>	'�����������',
'12'	=>	'�����������',
'25'	=>	'�������������',
'84'	=>	'��������',
'20'	=>	'�����������������',
'142'	=>	'�����������������',
'26'	=>	'��������������',
'24'	=>	'������� ������',
'163'	=>	'������������ �������',
'32'	=>	'�������������',
'64'	=>	'����������',
'88'	=>	'��������� ����',
'94'	=>	'���������',
'62'	=>	'����������',
'44'	=>	'�������',
'57'	=>	'�����������',
'79'	=>	'��������� ��������',
'22'	=>	'�������',
'150'	=>	'�������',
'106'	=>	'������������',
'191'	=>	'������� ����',
'152'	=>	'�������',
'6'		=>	'����������',
'76'	=>	'����������',
'182'	=>	'�������������',
'115'	=>	'�������������',
'154'	=>	'������',
'63'	=>	'����������',
'127'	=>	'�����������',
'128'	=>	'��������',
'129'	=>	'����������� ��������',
'184'	=>	'�����������',
'111'	=>	'�����������',
'180'	=>	'����������',
'9'		=>	'�������������',
'190'	=>	'���������������',
'143'	=>	'��������������',
'82'	=>	'����� ���������',
'77'	=>	'�����������',
'100'	=>	'����������� ����',
'185'	=>	'����������� �������',
'18'	=>	'�������',
'121'	=>	'��������',
'21'	=>	'������� ���',
'10'	=>	'����������',
'33'	=>	'���� ��������',
'168'	=>	'���� ������',
'48'	=>	'������������',
'50'	=>	'������������',
'110'	=>	'������',
'119'	=>	'���������-�����������',
'148'	=>	'���������',
'61'	=>	'����������',
'104'	=>	'���������',
'107'	=>	'������� ������',
'43'	=>	'������� ���������',
'193'	=>	'������� ��������',
'99'	=>	'������������',
'124'	=>	'�������',
'133'	=>	'��������',
'28'	=>	'�������������� �������',
'91'	=>	'������������',
'38'	=>	'�����. �����������',
'69'	=>	'�������� ����',
'81'	=>	'�����������',
'145'	=>	'����������',
'40'	=>	'������ ������',
'70'	=>	'�������',
'162'	=>	'�������',
'95'	=>	'��������� ��������',
'116'	=>	'�����������',
'74'	=>	'��������',
'130'	=>	'���������������',
'47'	=>	'�����������',
'125'	=>	'������������',
'41'	=>	'����������',
'54'	=>	'����������',
'3'		=>	'�����',
'27'	=>	'����������',
'188'	=>	'��������',
'35'	=>	'����������',
'195'	=>	'���������� �������',
'165'	=>	'��������',
'56'	=>	'������������',
'68'	=>	'�����������',
'103'	=>	'�����������',
'90'	=>	'���������',
'7'		=>	'��������',
'8'		=>	'�����������',
'93'	=>	'������������',
'198'	=>	'���������',
'85'	=>	'������ ����',
'187'	=>	'���������',
'118'	=>	'�������������',
'196'	=>	'�������������',
'65'	=>	'�������������',
'194'	=>	'�������',
'126'	=>	'��������',
'67'	=>	'������������',
'102'	=>	'���������',
'199'	=>	'��. ��. ��������',
'197'	=>	'��. �����������',
'201'	=>	'��. ������ �����������',
'97'	=>	'����� 1905 ����',
'157'	=>	'����� ��������� ������',
'172'	=>	'����� ���������',
'30'	=>	'����� ������������',
'171'	=>	'����� ������������',
'170'	=>	'����� ����������������',
'37'	=>	'�����������',
'60'	=>	'��������� ����',
'58'	=>	'����',
'34'	=>	'�����������',
'17'	=>	'��������',
'114'	=>	'������� �������',
'29'	=>	'������������',
'131'	=>	'������������',
'113'	=>	'���������',
'23'	=>	'������ �����',
'160'	=>	'����������',
'78'	=>	'�����������',
'109'	=>	'����� �����������',
'51'	=>	'����������',
'101'	=>	'���������',
'46'	=>	'����������������',
'39'	=>	'���-��������',
'132'	=>	'�����',
'86'	=>	'�������',
'210'	=>	'��������'
);
//

//if (isset($_GET['metro'])){//������ ������� � ����� �����
//���� - ��������� ����� ������� �� ������������� ������� �� ���������� � ����
if (adsmetroname(cols) > 0){
/*
	$get_metro = $_GET['metro'];
	if (!preg_match("/^[0-9]+$/", $get_metro) or $get_metro < 0 or $get_metro >= 200){//metro is metro
	error404();//404
	}else{
*/
	//���� ��� ����� �� 0 �� 200, ���� ��������� ����� ������� �������

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

$result_post_metro = '��. ����� '.$arr_metro[adsmetroname('0')];

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

}//����� end


//print adsmetrotype(1);



function adssearchmetro() {//����� �������� ������
    global $template, $mysql, $tpl, $SYSTEM_FLAGS;

$numstr = numpage()/10;
$numstr = $numstr+1;
$SYSTEM_FLAGS['info']['title']['group'] = '���������� ������ -'.adscategoryes(2).' '.adsmetrotype(2).'- �������� '.$numstr;
$SYSTEM_FLAGS['meta']['description'] = '���������� ������ -'.adscategoryes(2).' '.adsmetrotype(2).'- �������� '.$numstr;
$SYSTEM_FLAGS['meta']['keywords'] = adscategoryes(2).', '.adsmetrotype(2);

//�����������
//������ "�������" ���, �.�. ���� ��� ������ ��������
//������� ����� md5 ��� ������ ��������� � ������� ���������� �� ���-��������:
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
$metro_display = '��. ����� '.$row['xfields_dometro'].'<br />';
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





function adstypes() {//���������� begin
    global $template, $mysql, $tpl;

		$tvars['vars'] = array	(
				'adscatsype'		=>	adscategoryes(2),				
				'adsmetrotype'		=>	adsmetrotype(2)
		);

$tpl -> template('adstypes', extras_dir."/adssearchmetro/tpl");
$tpl -> vars('adstypes', $tvars);
$output .= $tpl -> show('adstypes');
$template['vars']['adstypes'] = $output;

}//���������� end




/*���������*/
function numpage() {//������ ��������
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


function listcounts() {//������ ���-�� �����������
$counts = mysql_result(mysql_query("SELECT count(*) FROM ".uprefix."_news WHERE approve = '1' ".adscategoryes(1)." ".adsmetrotype(1)." "),0);
//print $counts;
return $counts;
}



/**/
function listpages() {//������ ���-�� �������
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

//���� - ��������� ��������� ��� ������ ����������� (������ ���� 20 �� ���������� 3-� ��������)

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
/*���������*/


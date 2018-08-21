<?php

if (!defined('NGCMS')) die ('HAL');

add_act('index', 'adstypes');

register_plugin_page('adssearch_id','','adssearch_id');

function adsidtype($str) {//узнаем id
    global $mysql;

if (isset($_POST['idobj'])) {	
	$post_id = preg_replace("/\D/","",$_POST['idobj']);
	
	if ($post_id == ''){
			$adsid_select = '';
			$result_post_id = '';
		}else{
			$adsid_select = 'AND xfields_idobj = ';
			$adsid_select .= db_squote($post_id);
			$result_post_id = 'id '.$post_id;
		}
	}else{
		$adsid_select = '';
		$result_post_id = '';
	}

	if ($str == '1'){
	$arg = $adsid_select;
	}else if($str == '2'){
	$arg = $result_post_id;
	}

	return $arg;

}


//print adsidtype(1);


function adssearch_id($str) {
    global $template, $mysql, $tpl, $SYSTEM_FLAGS;

$SYSTEM_FLAGS['info']['title']['group'] = 'Поиск по id ('.adsidtype(2).')';

    $tpath = locatePluginTemplates(array('adssearch_id', 'entries'), 'adssearch_id', 1);

	$query = "select postdate, title, alt_name, catid, xfields_indexpreview, xfields_idobj, xfields_adres, xfields_ploshad, xfields_etaj, xfields_dometro, xfields_indexanons, xfields_price from ".uprefix."_news where approve = '1' ".adsidtype(1)." LIMIT 1 ";
	$row = $mysql->record($query );

//определяю показывать или нет картинку, если первой картинки нету то не указываю

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
            'xfields_dometro'	=>		$row['xfields_dometro'],
            'xfields_price'		=>		$row['xfields_price'],
            'img_display'		=>		$img_display,
            'anons_display'		=>		$anons_display            

        );


        $tpl -> template('entries', $tpath['entries']);
        $tpl -> vars('entries', $tvars);
        $v .= $tpl -> show('entries');


        $tvars['vars'] = array ( 'entries' => $v);
        $tpl -> template('adssearch_id', $tpath['adssearch_id']);
        $tpl -> vars('adssearch_id', $tvars);
        $output .= $tpl -> show('adssearch_id');
		$template['vars']['mainblock'] = $output;


  return $arg;

}
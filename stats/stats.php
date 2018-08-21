<?php
if (!defined('NGCMS')) die ('HAL');
add_act('index', 'stats');
error_reporting(0);

function stats(){
global $config, $tpl, $mysql, $template;

//����� �������� ��� �������
if (pluginGetVariable('stats','en_dbprefix')) {
	$config['uprefix'] = pluginGetVariable('stats','dbprefix');
}

if (pluginGetVariable('stats','en_dbprefix1')) {
	//������� ���������� ����������� �������
	$text['1'] = $mysql->record("SELECT COUNT(*) AS count FROM ".uprefix."_static");
}

if (pluginGetVariable('stats','en_dbprefix2')) {
	//������� ���������� ���������
	$text['2'] = $mysql->record("SELECT COUNT(*) AS count FROM ".uprefix."_category");
}

if (pluginGetVariable('stats','en_dbprefix3')) {
	//������� ���������� ��������
	$text['3'] = $mysql->record("SELECT COUNT(*) AS count FROM ".uprefix."_news");
}

if (pluginGetVariable('stats','en_dbprefix4')) {
	//������� ���������������� ��������
	$text['4'] = $mysql->record("SELECT COUNT(*) AS count FROM ".uprefix."_news WHERE approve='0'");
}

if (pluginGetVariable('stats','en_dbprefix5')) {
	//������� ������������
	$text['5'] = $mysql->record("SELECT COUNT(*) AS count FROM ".uprefix."_comments");
}

if (pluginGetVariable('stats','en_dbprefix6')) {
	//������� ������������������ �������������
	$text['6'] = $mysql->record("SELECT COUNT(*) AS count FROM ".uprefix."_users");
}

if (pluginGetVariable('stats','en_dbprefix7')) {
	//������� ���������� �������������
	$text['7'] = $mysql->record("SELECT COUNT(*) AS count FROM ".uprefix."_users WHERE activation != ''");
}
	
if (pluginGetVariable('stats','en_dbprefix8')) {
	//������� ����������� �����������
	$text['8'] = $mysql->record("SELECT COUNT(*) AS count FROM ".uprefix."_images");
}
	
if (pluginGetVariable('stats','en_dbprefix9')) {
	//������� ����������� ������
	$text['9'] = $mysql->record("SELECT COUNT(*) AS count FROM ".uprefix."_files");
}

if (pluginGetVariable('stats','en_dbprefix10')) {
	//������� ���������� ����� �� ����
	$text['10'] = $mysql->record("SELECT COUNT(*) AS count FROM ".uprefix."_ipban");
}

$ipblock=intval(pluginGetVariable('stats','iplock'));

for ($i=1; $i<=10; $i++)
{
	if ($text[$i]['count']=='0'){
		switch ($ipblock) {
		case 0:
			$text[$i]['count']='0';
			break;
		case 1:
			$text[$i]['count']='���';
			break;
		}
	}

}
////////////////////////////////////////////////////////////////////
/////////////////////// ������� ��������� //////////////////////////
////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////
///////////////// ������� ��������� ���������� /////////////////////
////////////////////////////////////////////////////////////////////

unset($tvars);
	$tvars['vars'] = array (
		'static_pages' => $text['1']['count'],
		'categoriy' => $text['2']['count'],
		'news' => $text['3']['count'],
		'news_na' => $text['4']['count'],
		'comments' => $text['5']['count'],
		'users' => $text['6']['count'],
		'users_na' => $text['7']['count'],
		'images' => $text['8']['count'],
		'files' => $text['9']['count'],
		'bans' => $text['10']['count'],
	);
	
	$tpl->template('stats',extras_dir.'/stats/tpl');
	$tpl -> vars('stats',$tvars);
	
	$output = $tpl -> show('stats');
	$template['vars']['stats'] = $output;
}
?>
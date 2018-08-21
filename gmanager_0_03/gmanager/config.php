<?php
if(!defined('NGCMS')) exit('HAL');

plugins_load_config();
LoadPluginLang('gmanager', 'config', '', '', ':');

switch ($_REQUEST['action']) {
	case 'list': showlist(); break;
	case 'update': update(); showlist(); break;
	case 'edit': edit(); break;
	case 'edit_submit': edit_submit(); showlist();break;
	case 'dell': delete(); break;
	case 'move_up': move('up'); showlist(); break;
	case 'move_down': move('down'); showlist(); break;
	case 'clear_cash': clear_cash(); main(); break;
	case 'general_submit': general_submit(); main(); break;
	case 'widget_list': showwidgetlist(); break;
	case 'widget_add': widgetedit(); break;
	case 'widget_edit_submit': widgeteditsubmit(); showwidgetlist();break;
	case 'widget_dell': widgetdelete(); break;
	
	default: main();
}

function main()
{
	global $tpl, $lang;
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.general.form'), 'gmanager', 1);
	$tvars['vars']['locate_tpl_list'] = MakeDropDown(array(0 => $lang['gmanager:label_site'], 1 => $lang['gmanager:label_plugin']), 'locate_tpl', pluginGetVariable('gmanager', 'locate_tpl'));
	$tvars['vars']['skin'] = pluginGetVariable('gmanager', 'skin');
	$tvars['vars']['if_auto_cash_list'] = MakeDropDown(array(0 => $lang['gmanager:label_no'], 1 => $lang['gmanager:label_yes']), 'if_auto_cash', pluginGetVariable('gmanager', 'if_auto_cash'));
	$tvars['vars']['cash_time'] = pluginGetVariable('gmanager', 'cash_time');
	$tvars['vars']['if_description_list'] = MakeDropDown(array(0 => $lang['gmanager:label_no'], 1 => $lang['gmanager:label_yes']), 'if_description', pluginGetVariable('gmanager', 'if_description'));
	$tvars['vars']['if_keywords_list'] = MakeDropDown(array(0 => $lang['gmanager:label_no'], 1 => $lang['gmanager:label_yes']), 'if_keywords', pluginGetVariable('gmanager', 'if_keywords'));
	$tvars['vars']['main_row'] = pluginGetVariable('gmanager', 'main_row');
	$tvars['vars']['main_cell'] = pluginGetVariable('gmanager', 'main_cell');
	$tvars['vars']['main_page_list'] = MakeDropDown(array(0 => $lang['gmanager:label_no'], 1 => $lang['gmanager:label_yes']), 'main_page', pluginGetVariable('gmanager', 'main_page'));

	$tpl->template('conf.general.form', $tpath['conf.general.form']);
	$tpl->vars('conf.general.form', $tvars);
	$tvars['vars']['entries'] = $tpl->show('conf.general.form');
	
	$tvars['vars']['action'] = $lang['gmanager:button_general'];

	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function general_submit()
{
	global $lang;
	pluginSetVariable('gmanager', 'locate_tpl', intval($_POST['locate_tpl']));
	pluginSetVariable('gmanager', 'skin', trim(secure_html($_POST['skin'])));
	pluginSetVariable('gmanager', 'if_auto_cash', intval($_POST['if_auto_cash']));
	pluginSetVariable('gmanager', 'cash_time', intval($_POST['cash_time']));
	pluginSetVariable('gmanager', 'if_description', intval($_POST['if_description']));
	pluginSetVariable('gmanager', 'if_keywords', intval($_POST['if_keywords']));
	pluginSetVariable('gmanager', 'main_row', intval($_POST['main_row']));
	pluginSetVariable('gmanager', 'main_cell', intval($_POST['main_cell']));
	pluginSetVariable('gmanager', 'main_page', intval($_POST['main_page']));
	pluginsSaveConfig();
	if (pluginGetVariable('gmanager', 'if_auto_cash')) clear_cash();
	msg(array('type' => 'info', 'info' => $lang['gmanager:info_save_general']));
}

function showlist()
{
	global $tpl, $lang, $mysql;
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.list', 'conf.list.row'), 'gmanager', 1);
	
	$output = '';
	foreach($mysql->select('select * from '.prefix.'_gmanager order by iorder') as $row)
	{
		$pvars['regx']['/\[if_active\](.*?)\[\/if_active\]/si'] = $row['if_active']?'$1':'';
		$pvars['regx']['/\[if_not_active\](.*?)\[\/if_not_active\]/si'] = $row['if_active']?'':'$1';
		$pvars['vars']['id'] = $row['id'];
		$pvars['vars']['name'] = $row['name'];
		$pvars['vars']['title'] = $row['title'];
		$pvars['vars']['skin'] = $row['skin'];
		$pvars['vars']['grid'] = intval($row['count_cells']).'x'.intval($row['count_rows']);
		$pvars['vars']['page'] = $row['if_number'] ? $lang['gmanager:label_yes'] : $lang['gmanager:label_no'];
		
		$tpl->template('conf.list.row', $tpath['conf.list.row']);
		$tpl->vars('conf.list.row', $pvars);
		$output .= $tpl->show('conf.list.row');
	}

	$ttvars['vars']['entries'] = $output;
	
	$tpl->template('conf.list', $tpath['conf.list']);
	$tpl->vars('conf.list', $ttvars);
	$tvars['vars']['entries'] = $tpl->show('conf.list');
	
	$tvars['vars']['action'] = $lang['gmanager:button_list'];
	
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function update()
{
	global $mysql;
	$galery = $mysql->select('select name from '.prefix.'_gmanager');
	$next_order = count($galery) + 1;
	$dir = opendir(images_dir);
	if ($dir = opendir(images_dir)) {
		while($file = readdir($dir)) {
			if (!is_dir(images_dir."/".$file) || $file == "." || $file == ".." || GetKeyFromName($file, $galery) !== false)
				continue;
			$mysql->query('insert '.prefix.'_gmanager '.
				'(name, iorder) values '.
				'('.db_squote($file).', '.db_squote($next_order).')');
				$next_order ++;
		}
		closedir($dir);
	}
}

function GetKeyFromName($name, $array)
{
	$count = count($array);
	for ($i = 0; $i < $count; $i ++)
		if ($array[$i]['name'] == $name)
			return $i;
	return false;
}

function edit()
{
	global $mysql, $tpl, $lang;
	
	if (!isset($_REQUEST['id'])) return;
	$id = intval($_REQUEST['id']);
	
	$galery = $mysql->record('select * from '.prefix.'_gmanager where `id`='.db_squote($id).' limit 1');
	if (!$galery) return;
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.edit.form'), 'gmanager', 1);
	
	$icon_list = array();
	foreach($mysql->select('select id, name from '.prefix.'_images where folder='.db_squote($galery['name'])) as $row)
		$icon_list[$row['id']] = $row['name'];
	
	$pvars['vars']['id'] = $galery['id'];
	$pvars['vars']['id_icon_list'] = MakeDropDown($icon_list, 'id_icon', $galery['id_icon']);
	$pvars['vars']['if_active_list'] = MakeDropDown(array(0 => $lang['gmanager:label_off'], 1 => $lang['gmanager:label_on']), 'if_active', $galery['if_active']);
	$pvars['vars']['name'] = $galery['name'];
	$pvars['vars']['skin'] = $galery['skin'];
	$pvars['vars']['count_cells'] = intval($galery['count_cells']);
	$pvars['vars']['count_rows'] = intval($galery['count_rows']);
	$pvars['vars']['if_number_list'] = MakeDropDown(array(0 => $lang['gmanager:label_no'], 1 => $lang['gmanager:label_yes']), 'if_number', $galery['if_number']);
	$pvars['vars']['title'] = $galery['title'];
	$pvars['vars']['description'] = $galery['description'];
	$pvars['vars']['keywords'] = $galery['keywords'];
		
	$tpl->template('conf.edit.form', $tpath['conf.edit.form']);
	$tpl->vars('conf.edit.form', $pvars);
	$output .= $tpl->show('conf.edit.form');

	$tvars['vars']['entries'] = $output;
	$tvars['vars']['action'] = $lang['gmanager:button_edit'];
	
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function edit_submit()
{
	global $mysql, $lang, $parse;
	
	//print_r($_POST);
	//die();
	if (!isset($_REQUEST['id']) || !isset($_POST['title']) || !isset($_POST['if_active'])  || !isset($_POST['skin']) || !isset($_POST['id_icon']) || !isset($_POST['description']) || !isset($_POST['keywords']) || !isset($_POST['count_cells']) || !isset($_POST['count_rows']) || !isset($_POST['if_number'])) return;
	$id = intval($_REQUEST['id']);
	
	$galery = $mysql->record('select * from '.prefix.'_gmanager where `id`='.db_squote($id).' limit 1');
	if (!$galery) return;

	$title = trim(secure_html($_POST['title']));
	$skin = $parse->translit(trim($_POST['skin']), 1);
	$count_cells = intval($_POST['count_cells']);
	$count_rows = intval($_POST['count_rows']);
	$if_number = intval($_POST['if_number']);
	$if_active = intval($_POST['if_active']);
	$id_icon = intval($_POST['id_icon']);
	$description = trim($_POST['description']);
	$keywords = trim(secure_html($_POST['keywords']));
	
	$t_update = '';
	if ($title != $galery['title']) 
		$t_update .= (($t_update?', ':'').'`title`='.db_squote($title));
	if ($skin != $galery['skin']) 
		$t_update .= (($t_update?', ':'').'`skin`='.db_squote($skin));
	if ($count_cells != $galery['count_cells']) 
		$t_update .= (($t_update?', ':'').'`count_cells`='.db_squote($count_cells));
	if ($count_rows != $galery['count_rows']) 
		$t_update .= (($t_update?', ':'').'`count_rows`='.db_squote($count_rows));
	if ($if_number != $galery['if_number']) 
		$t_update .= (($t_update?', ':'').'`if_number`='.db_squote($if_number));
	if ($if_active != $galery['if_active']) 
		$t_update .= (($t_update?', ':'').'`if_active`='.db_squote($if_active));
	if ($id_icon != $galery['id_icon']) 
		$t_update .= (($t_update?', ':'').'`id_icon`='.db_squote($id_icon));
	if ($description != $galery['description']) 
		$t_update .= (($t_update?', ':'').'`description`='.db_squote($description));
	if ($keywords != $galery['keywords']) 
		$t_update .= (($t_update?', ':'').'`keywords`='.db_squote($keywords));
	
	if ($t_update)
	{
		$mysql->query('update '.prefix.'_gmanager set '.$t_update.' where id = '.db_squote($id).' limit 1');
		msg(array('type' => 'info', 'info' => $lang['gmanager:info_update_record']));
	}
	
	if (pluginGetVariable('gmanager', 'if_auto_cash')) clear_cash();
}

function move($action)
{
	global $mysql, $lang;

	if (!isset($_REQUEST['id'])) return;
	$id = intval($_REQUEST['id']);

	$galery = $mysql->record('select id, iorder from '.prefix.'_gmanager where `id`='.db_squote($id).' limit 1');
	if (!$galery) return;
	$count = 0;
	if (is_array($pcnt = $mysql->record('select count(*) as cnt from '.prefix.'_gmanager')))
		$count = $pcnt['cnt'];

	if ($action == 'up')
	{
		if ($galery['iorder'] == 1)
			return;

		$galery2 = $mysql->record('select id, iorder from '.prefix.'_gmanager where iorder='.db_squote($galery['iorder'] - 1).' limit 1');

		$mysql->query('update '.prefix.'_gmanager set iorder='.db_squote($galery['iorder']).'where `id`='.db_squote($galery2['id']).' limit 1');
		$mysql->query('update '.prefix.'_gmanager set iorder='.db_squote($galery2['iorder']).'where `id`='.db_squote($galery['id']).' limit 1');
	}
	else if ($action == 'down')
	{
		if ($galery['iorder'] == $count)
			return;

		$galery2 = $mysql->record('select id, iorder from '.prefix.'_gmanager where iorder='.db_squote($galery['iorder'] + 1).' limit 1');

		$mysql->query('update '.prefix.'_gmanager set iorder='.db_squote($galery['iorder']).'where `id`='.db_squote($galery2['id']).' limit 1');
		$mysql->query('update '.prefix.'_gmanager set iorder='.db_squote($galery2['iorder']).'where `id`='.db_squote($galery['id']).' limit 1');
	}
	if (pluginGetVariable('gmanager', 'if_auto_cash')) clear_cash();
}

function delete()
{
	global $mysql, $tpl, $lang;

	if (!isset($_REQUEST['id'])) return;
	$id = intval($_REQUEST['id']);
	
	$galery = $mysql->record('select `title` from '.prefix.'_gmanager where `id`='.db_squote($id).' limit 1');
	
	if (isset($_POST['commit']))
	{
		if ($_POST['commit'] == 'yes')
		{
			$mysql->query('delete from '.prefix.'_gmanager where `id`='.db_squote($id));
			$next_order = 1;
			foreach($mysql->select('select id from '.prefix.'_gmanager order by iorder') as $row)
			{
				$dir = opendir(images_dir);
				$mysql->query('update '.prefix.'_gmanager set iorder='.db_squote($next_order).'where `id`='.db_squote($row['id']).' limit 1');
				$next_order ++;
			}
			
			msg(array('type' => 'info', 'info' => $lang['gmanager:info_delete']));
			if (pluginGetVariable('gmanager', 'if_auto_cash')) clear_cash();
		}
		showlist();
		return true;
	}
	$tpath = locatePluginTemplates(array('conf.main', 'conf.commit.form'), 'gmanager', 1);
	$tvars['vars']['id'] = $id;
	$tvars['vars']['commit'] = sprintf($lang['gmanager:desc_commit'], $galery['title']);
	
	$tpl->template('conf.commit.form', $tpath['conf.commit.form']);
	$tpl->vars('conf.commit.form', $tvars);
	$tvars['vars']['entries'] = $tpl->show('conf.commit.form');
	
	$tvars['vars']['action'] = $lang['gmanager:title_commit'];

	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function clear_cash()
{
	global $lang;
	if (($dir = get_plugcache_dir('gmanager'))) {
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) { 
				if ($file == "." || $file == "..")
					continue;
				unlink ($dir.$file);
			}
			closedir($handle); 
		}
		msg(array('type' => 'info', 'info' => $lang['gmanager:info_cash_clear']));
	}
}

function showwidgetlist()
{
	global $tpl, $lang;
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.widget.list', 'conf.widget.list.row'), 'gmanager', 1);
	$widgets = pluginGetVariable('gmanager', 'widgets');
	
	$output = '';
	if (is_array($widgets)){
		foreach($widgets as $id=>$row)
		{
			$pvars['regx']['/\[if_active\](.*?)\[\/if_active\]/si'] = $row['if_active']?'$1':'';
			$pvars['regx']['/\[if_not_active\](.*?)\[\/if_not_active\]/si'] = $row['if_active']?'':'$1';
			$pvars['vars']['id'] = $id;
			$pvars['vars']['name'] = $row['name'];
			$pvars['vars']['title'] = $row['title'];
			$pvars['vars']['galery'] = $row['galery'];
			$pvars['vars']['skin'] = $row['skin'];
			$pvars['vars']['grid'] = intval($row['cells']).'x'.intval($row['rows']);
			$pvars['vars']['rand'] = $row['if_rand'] ? $lang['gmanager:label_yes'] : $lang['gmanager:label_no'];
			
			$tpl->template('conf.widget.list.row', $tpath['conf.widget.list.row']);
			$tpl->vars('conf.widget.list.row', $pvars);
			$output .= $tpl->show('conf.widget.list.row');
		}
	}

	$ttvars['vars']['entries'] = $output;
	
	$tpl->template('conf.widget.list', $tpath['conf.widget.list']);
	$tpl->vars('conf.widget.list', $ttvars);
	$tvars['vars']['entries'] = $tpl->show('conf.widget.list');
	
	$tvars['vars']['action'] = $lang['gmanager:button_widget_list'];
	
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function widgetedit()
{
	global $tpl, $lang;
	
	$id = -1;
	$if_active = 0;
	$name = '';
	$title = '';
	$skin = '';
	$cells = 2;
	$rows = 2;
	$if_rand = 0;
	$output_method = 0;
	$description = '';
	$keywords = '';
	$galery = '';
	if (isset($_GET['id'])){
		$id = intval($_GET['id']);
		$widgets = pluginGetVariable('gmanager', 'widgets');
		if (!isset($widgets[$id])) $id = -1;
		else{
			$if_active = $widgets[$id]['if_active'];
			$name = $widgets[$id]['name'];
			$title = $widgets[$id]['title'];
			$skin = $widgets[$id]['skin'];
			$cells = $widgets[$id]['cells'];
			$rows = $widgets[$id]['rows'];
			$if_rand = $widgets[$id]['if_rand'];
			$output_method = $widgets[$id]['output_method'];
			$description = $widgets[$id]['description'];
			$keywords = $widgets[$id]['keywords'];
			$galery = $widgets[$id]['galery'];
		}
	}
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.widget.edit.form'), 'gmanager', 1);
	
	$pvars['vars']['id'] = $id;
	$pvars['vars']['if_active_list'] = MakeDropDown(array(0 => $lang['gmanager:label_off'], 1 => $lang['gmanager:label_on']), 'if_active', $if_active);
	$pvars['vars']['name'] = $name;
	$pvars['vars']['title'] = $title;
	$pvars['vars']['skin'] = $skin;
	$pvars['vars']['cells'] = $cells;
	$pvars['vars']['rows'] = $rows;
	$pvars['vars']['if_rand_list'] = MakeDropDown(array(0 => 'по умолчанию', 1 => 'случайно', 2 => 'просмотры', 3 => 'комментарии'), 'if_rand', $if_rand);
	$pvars['vars']['output_method_list'] = MakeDropDown(array(0 => 'виджет', 1 => 'отдельная страница'), 'output_method', $output_method);
	$pvars['vars']['description'] = $description;
	$pvars['vars']['keywords'] = $keywords;
	$pvars['vars']['galery'] = $galery;
		
	$tpl->template('conf.widget.edit.form', $tpath['conf.widget.edit.form']);
	$tpl->vars('conf.widget.edit.form', $pvars);
	$output .= $tpl->show('conf.widget.edit.form');

	$tvars['vars']['entries'] = $output;
	$tvars['vars']['action'] = $lang['gmanager:button_widget_edit'];
	
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function widgeteditsubmit()
{
	global $parse, $lang;
	
	if (!isset($_POST['name']) || !isset($_POST['title']) || !isset($_POST['if_active'])  || !isset($_POST['skin']) || !isset($_POST['cells']) || !isset($_POST['rows']) || !isset($_POST['if_rand']) || !isset($_POST['output_method']) || !isset($_POST['galery'])) return;
	
	$id = -1;
	if (isset($_POST['id']))$id = intval($_POST['id']);
	$name = $parse->translit(trim($_POST['name']), 1);
	$title = trim(secure_html($_POST['title']));
	$if_active = intval($_POST['if_active']);
	$skin = $parse->translit(trim($_POST['skin']), 1);
	$cells = intval($_POST['cells']);
	$rows = intval($_POST['rows']);
	$if_rand = intval($_POST['if_rand']);
	$output_method = intval($_POST['output_method']);
	$description = trim(secure_html($_POST['description']));
	$keywords = trim(secure_html($_POST['keywords']));
	$galery = trim(secure_html($_POST['galery']));

	$widgets = pluginGetVariable('gmanager', 'widgets');
	if (is_array($widgets)){
		if (!isset($widgets[$id])) $id = count($widgets);
	}
	else $id = 0;

	$widgets[$id]['name'] = $name;
	$widgets[$id]['title'] = $title;
	$widgets[$id]['if_active'] = $if_active;
	$widgets[$id]['skin'] = $skin;
	$widgets[$id]['cells'] = $cells;
	$widgets[$id]['rows'] = $rows;
	$widgets[$id]['if_rand'] = $if_rand;
	$widgets[$id]['output_method'] = $output_method;
	$widgets[$id]['description'] = $description;
	$widgets[$id]['keywords'] = $keywords;
	$widgets[$id]['galery'] = $galery;
	
	pluginSetVariable('gmanager', 'widgets', $widgets);
	pluginsSaveConfig();
	
	msg(array('type' => 'info', 'info' => $lang['gmanager:info_update_record']));
	
	if (pluginGetVariable('gmanager', 'if_auto_cash')) clear_cash();
}

function widgetdelete()
{
	global $mysql, $tpl, $lang;

	if (!isset($_REQUEST['id'])) return;
	$id = intval($_REQUEST['id']);
	
	$widgets = pluginGetVariable('gmanager', 'widgets');
	
	if (!isset($widgets[$id])){
		showwidgetlist();
		return true;
	}
	
	if (isset($_POST['commit']))
	{
		if ($_POST['commit'] == 'yes')
		{
			if (isset($widgets[$id])){
				unset($widgets[$id]);
				pluginSetVariable('gmanager', 'widgets', $widgets);
				pluginsSaveConfig();
			}
			
			msg(array('type' => 'info', 'info' => $lang['gmanager:info_delete']));
			if (pluginGetVariable('gmanager', 'if_auto_cash')) clear_cash();
		}
		showwidgetlist();
		return true;
	}
	$tpath = locatePluginTemplates(array('conf.main', 'conf.widget.commit.form'), 'gmanager', 1);
	$tvars['vars']['id'] = $id;
	$tvars['vars']['commit'] = sprintf($lang['gmanager:desc_widget_commit'], $widgets[$id]['title']);
	
	$tpl->template('conf.widget.commit.form', $tpath['conf.widget.commit.form']);
	$tpl->vars('conf.widget.commit.form', $tvars);
	$tvars['vars']['entries'] = $tpl->show('conf.widget.commit.form');
	
	$tvars['vars']['action'] = $lang['gmanager:title_widget_commit'];

	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}
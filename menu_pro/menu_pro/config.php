<?php
if(!defined('NGCMS')) exit('HAL');

plugins_load_config();
LoadPluginLang('menu_pro', 'config', '', '', ':');
if (pluginGetVariable('menu_pro', 'localize')) LoadPluginLang('menu_pro', 'menu', '', '', ':');

if (getPluginStatusActive('menu_pro')){
	switch ($_REQUEST['action']) {
		case 'list_menu': showlist(); break;
		case 'add_form': add(); break;
		case 'move_up': move('up'); showlist(); break;
		case 'move_down': move('down'); showlist(); break;
		case 'dell': delete(); break;
		case 'general_submit': general_submit(); main(); break;
		case 'clear_cash': clear_cash();
		default: main();
	}
}
else not_access();

function not_access()
{
	global $tpl, $lang;
	$tpath = locatePluginTemplates(array('conf.notaccess'), 'menu_pro', 1);
	$tvars['vars']['entries'] = $lang['menu_pro:title_plugin_on'];
	$tvars['vars']['action'] = $lang['menu_pro:button_general'];
	$tpl->template('conf.notaccess', $tpath['conf.notaccess']);
	$tpl->vars('conf.notaccess', $tvars);
	print $tpl->show('conf.notaccess');
}

function validate($string){
	$chars = 'abcdefghijklmnopqrstuvwxyz_.0123456789';
	foreach(str_split($string) as $char)
		if (stripos($chars, $char) === false)
			return false;
	return true;
}

function general_submit(){
	global $lang;
	pluginSetVariable('menu_pro', 'localize', intval($_POST['localize']));
	pluginSetVariable('menu_pro', 'locate_tpl', intval($_POST['locate_tpl']));
	pluginSetVariable('menu_pro', 'if_auto_cash', intval($_POST['if_auto_cash']));
	pluginsSaveConfig();
	if (pluginGetVariable('menu_pro', 'if_auto_cash')) clear_cash();
	msg(array('type' => 'info', 'info' => $lang['menu_pro:info_save_general']));
}

function main(){
	global $tpl, $lang;
	$tpath = locatePluginTemplates(array('conf.main', 'conf.general.form'), 'menu_pro', 1);
	$ttvars['vars']['localize_list'] = MakeDropDown(array(0 => $lang['menu_pro:label_no'], 1 => $lang['menu_pro:label_yes']), 'localize', pluginGetVariable('menu_pro', 'localize'));
	$ttvars['vars']['locate_tpl_list'] = MakeDropDown(array(0 => $lang['menu_pro:label_site'], 1 => $lang['menu_pro:label_plugin']), 'locate_tpl', pluginGetVariable('menu_pro', 'locate_tpl'));
	$ttvars['vars']['if_auto_cash_list'] = MakeDropDown(array(0 => $lang['menu_pro:label_no'], 1 => $lang['menu_pro:label_yes']), 'if_auto_cash', pluginGetVariable('menu_pro', 'if_auto_cash'));
	$ttvars['vars']['if_description_list'] = MakeDropDown(array(0 => $lang['menu_pro:label_no'], 1 => $lang['menu_pro:label_yes']), 'if_description', pluginGetVariable('menu_pro', 'if_description'));
	$ttvars['vars']['action'] = $lang['menu_pro:button_general'];
	$tpl->template('conf.general.form', $tpath['conf.general.form']);
	$tpl->vars('conf.general.form', $ttvars);
	$tvars['vars']['entries'] = $tpl->show('conf.general.form');
	$tvars['vars']['action'] = $lang['menu_pro:button_general'];
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function showlist(){
	global $mysql, $tpl, $lang;
	@include_once root.'includes/classes/dbtree.class.ng.php';
	$tree = new dbtree(prefix.'_menu_pro', array('id' => 'id', 'left' => 'tree_left', 'right' => 'tree_right', 'level' => 'tree_level'), $mysql, true);
	$tpath = locatePluginTemplates(array('conf.main', 'conf.list', 'conf.list.row'), 'menu_pro', 1);
	$full_tree = $tree->Full(array('id', 'tree_left', 'tree_level', 'if_active', 'name', 'title', 'access', 'url', 'plugin', 'handler', 'params'));
	$output = '';
	$parent_level = 0;
	foreach ($full_tree as $node) {
		if ($node['tree_left'] == 1) continue;
		$pvars['vars']['id'] = $node['id'];
		if ($node['tree_level'] == 1) $pvars['vars']['title'] = '<u>'.(pluginGetVariable('menu_pro', 'localize')?($lang['rmenu:item_'.$node['name']]?$lang['rmenu:item_'.$node['name']]:'LANG LOST >> '.'rmenu:item_'.$node['name']):$node['title']).'</u>';
		else $pvars['vars']['title'] = str_repeat('&nbsp;', ($node['tree_level'] - 1) * 4).(pluginGetVariable('menu_pro', 'localize')?($lang['rmenu:item_'.$node['name']]?$lang['rmenu:item_'.$node['name']]:'LANG LOST >> '.'rmenu:item_'.$node['name']):$node['title']);
		$pvars['vars']['name'] = $node['name'];
		$pvars['regx']['/\[if_active\](.*?)\[\/if_active\]/si'] = '$1';
		$pvars['regx']['/\[if_not_active\](.*?)\[\/if_not_active\]/si'] = '';
		if ($parent_level && $parent_level >= $node['tree_level']){
			$parent_level = 0;
		}
		else if ($parent_level && $parent_level < $node['tree_level']){
			$pvars['regx']['/\[if_active\](.*?)\[\/if_active\]/si'] = '';
			$pvars['regx']['/\[if_not_active\](.*?)\[\/if_not_active\]/si'] = '$1';
		}
		if (!$parent_level && !$node['if_active']){
			$pvars['regx']['/\[if_active\](.*?)\[\/if_active\]/si'] = '';
			$pvars['regx']['/\[if_not_active\](.*?)\[\/if_not_active\]/si'] = '$1';
			$parent_level = $node['tree_level'];
		}
		$access = '';
		if (!($node['access'] & 0x01)) $access .= '<font color="red">Ã</font>'; 
		else $access .= '<font color="green">Ã</font>'; 
		if (!($node['access'] & 0x02)) $access .= '<font color="red">Ê</font>'; 
		else $access .= '<font color="green">Ê</font>'; 
		if (!($node['access'] & 0x04)) $access .= '<font color="red">Æ</font>'; 
		else $access .= '<font color="green">Æ</font>'; 
		if (!($node['access'] & 0x08)) $access .= '<font color="red">Ð</font>'; 
		else $access .= '<font color="green">Ð</font>'; 
		if (!($node['access'] & 0x10)) $access .= '<font color="red">À</font>'; 
		else $access .= '<font color="green">À</font>'; 
		$pvars['vars']['access'] = $access;
		$t_params = unserialize($node['params']);
		$pvars['vars']['url'] = $node['url']?$node['url']:generatePluginLink($node['plugin'], $node['handler'], $t_params);
		$tpl->template('conf.list.row', $tpath['conf.list.row']);
		$tpl->vars('conf.list.row', $pvars);
		$output .= $tpl->show('conf.list.row');
	}
	$ttvars['vars']['entries'] = $output;
	$tpl->template('conf.list', $tpath['conf.list']);
	$tpl->vars('conf.list', $ttvars);
	$tvars['vars']['entries'] = $tpl->show('conf.list');
	$tvars['vars']['action'] = $lang['menu_pro:button_list'];
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function add(){
	global $mysql, $tpl, $lang;
	@include_once root.'includes/classes/dbtree.class.ng.php';
	$tree = new dbtree(prefix.'_menu_pro', array('id' => 'id', 'left' => 'tree_left', 'right' => 'tree_right', 'level' => 'tree_level'), $mysql, true);
	$id = isset($_REQUEST['id'])?intval($_REQUEST['id']):0;
	$name = '';
	$title = '';
	$description = '';
	$skin = '';
	$icon = '';
	$if_active = 0;
	$access = 0;
	$url = '';
	$plugin = '';
	$handler = '';
	$params = array();
	$parent = 0;
	if (isset($_POST['parent'])) {
		$if_error = false;
		if (isset($_POST['name'])) $name = strip_tags($_POST['name']);
		if (isset($_POST['title'])) $title = strip_tags($_POST['title']);
		if (isset($_POST['description'])) $description = strip_tags($_POST['description']);
		if (isset($_POST['skin'])) $skin = strip_tags($_POST['skin']);
		if (isset($_POST['icon'])) $icon = strip_tags($_POST['icon']);
		if (isset($_POST['if_active'])) $if_active = intval($_POST['if_active']);
		if (isset($_POST['access'])) {
			foreach($_POST['access'] as $val) {
				switch ($val){
					case 'G': $access = $access | 0x01; break;
					case 'T': $access = $access | 0x02; break;
					case 'R': $access = $access | 0x04; break;
					case 'C': $access = $access | 0x08; break;
					case 'P': $access = $access | 0x10; break;
				}
			}
		}
		if (isset($_POST['url']))$url = strip_tags($_POST['url']);
		if (isset($_POST['rep_plugin'])) $plugin = strip_tags($_POST['rep_plugin']);
		if (isset($_POST['handler'])) $handler = strip_tags($_POST['handler']);
		if (isset($_POST['params'])) {
			foreach ($_POST['params'] as $val) {
				if (!$val['key']) continue;
				$params[$val['key']] = $val['value'];
			}
		}
		$params = serialize($params);
		$parent = intval($_POST['parent']);
		if ($skin && !validate($skin)) {
			msg(array('type' => 'error', 'info' => sprintf($lang['menu_pro:error_validate'], $lang['menu_pro:label_skin']), 'text' => $lang['menu_pro:error_val_title']));
			$if_error = true;
		}
		if ($icon && !validate($icon)) {
			msg(array('type' => 'error', 'info' => sprintf($lang['menu_pro:error_validate'], $lang['menu_pro:label_icon']), 'text' => $lang['menu_pro:error_val_title']));
			$if_error = true;
		}
		$par_node = $tree->GetNode($parent);
		if ($par_node === false){
			msg(array('type' => 'error', 'info' => $lang['menu_pro:error_parent'], 'text' => $lang['menu_pro:error_val_title']));
			$if_error = true;
		}
		else if (!$par_node['tree_level'] || pluginGetVariable('menu_pro', 'localize')){
			if (!strlen($name)){
				msg(array('type' => 'error', 'info' => sprintf($lang['menu_pro:error_empty'], $lang['menu_pro:label_name']), 'text' => $lang['menu_pro:error_val_title']));
				$if_error = true;
			}
			else if (!validate($name)) {
				msg(array('type' => 'error', 'info' => sprintf($lang['menu_pro:error_validate'], $lang['menu_pro:label_name']), 'text' => $lang['menu_pro:error_val_title']));
				$if_error = true;
			}
		}
		if (!pluginGetVariable('menu_pro', 'localize') && !strlen($title)){
			msg(array('type' => 'error', 'info' => sprintf($lang['menu_pro:error_empty'], $lang['menu_pro:label_title']), 'text' => $lang['menu_pro:error_val_title']));
			$if_error = true;
		}

		$cur_node = 1;
		if ($id) $cur_node = $tree->GetNode($id);
		if ($cur_node === false){
			msg(array('type' => 'error', 'info' => $lang['menu_pro:error_curent'], 'text' => $lang['menu_pro:error_val_title']));
			$if_error = true;
		}

		if (!$if_error){
			if ($id) {
				$t_update = '';
				if (isset($_POST['name']) && $name != $cur_node['name']) 
					$t_update .= (($t_update?', ':'').'`name`='.db_squote($name));
				if (isset($_POST['title']) && $title != $cur_node['title']) 
					$t_update .= (($t_update?', ':'').'`title`='.db_squote($title));
				if (isset($_POST['description']) && $description != $cur_node['description']) 
					$t_update .= (($t_update?', ':'').'`description`='.db_squote($description));
				if ($skin != $cur_node['skin']) 
					$t_update .= (($t_update?', ':'').'`skin`='.db_squote($skin));
				if ($icon != $cur_node['icon']) 
					$t_update .= (($t_update?', ':'').'`icon`='.db_squote($icon));
				if ($if_active != $cur_node['if_active']) 
					$t_update .= (($t_update?', ':'').'`if_active`='.db_squote($if_active));
				if ($access != $cur_node['access']) 
					$t_update .= (($t_update?', ':'').'`access`='.db_squote($access));
				if ($url != $cur_node['url']) 
					$t_update .= (($t_update?', ':'').'`url`='.db_squote($url));
				if ($plugin != $cur_node['plugin']) 
					$t_update .= (($t_update?', ':'').'`plugin`='.db_squote($plugin));
				if ($handler != $cur_node['handler']) 
					$t_update .= (($t_update?', ':'').'`handler`='.db_squote($handler));
				if ($params != $cur_node['params']) 
					$t_update .= (($t_update?', ':'').'`params`='.db_squote($params));
				if ($t_update)
					$mysql->query('update '.prefix.'_menu_pro set '.$t_update.' where id='.db_squote($id).' limit 1');
				$t_parent = $tree->GetParent($id);
				if ($t_parent['id'] != $parent_node['id']) $tree->MoveAll($id, $parent_node['id']);
				msg(array('type' => 'info', 'info' => $lang['menu_pro:info_update_menu']));
			}
			else{
				$tree->Insert($par_node['id'], array(
						'name' => $name,
						'title' => $title,
						'description' => $description,
						'skin' => $skin,
						'icon' => $icon,
						'if_active' => $if_active,
						'access' => $access,
						'url' => $url,
						'plugin' => $plugin,
						'handler' => $handler,
						'params' => $params,
					));
				msg(array('type' => 'info', 'info' => $lang['menu_pro:info_insert_menu']));
			}
			if (pluginGetVariable('menu_pro', 'if_auto_cash')) clear_cash();
			showlist();
			return;
		}
	}
	else {
		$cur_node = $tree->GetNode($id);
		if (is_array($cur_node) && count($cur_node)) {
			$name = $cur_node['name'];
			$title = $cur_node['title'];
			$description = $cur_node['description'];
			$skin = $cur_node['skin'];
			$icon = $cur_node['icon'];
			$if_active = $cur_node['if_active'];
			$access = $cur_node['access'];
			$url = $cur_node['url'];
			$plugin = $cur_node['plugin'];
			$handler = $cur_node['handler'];
			$params = $cur_node['params'];
			$par_node = $tree->GetParent($id);
			if (is_array($par_node) && count($par_node))
				$parent = $par_node['id'];
		}
	}
	$full_tree = $tree->Full(array('id', 'tree_left', 'tree_level', 'name', 'title'));
	$t_node_list = array();
	foreach ($full_tree as $node) {
		if ($node['id'] == $id)
			continue;
		if ($node['tree_left'] == 1) {
			$t_node_list[$node['id']] = $lang['menu_pro:label_group_menu'];
			continue;
		}
		$t_node_list[$node['id']] = str_repeat('&nbsp;', ($node['tree_level'] - 1) * 4).(isset($lang['menu_pro:menu_item_'.$node['name']])?$lang['menu_pro:menu_item_'.$node['name']]:$node['title']);
	}

	$ttvars['vars']['id'] = $id;		
	$ttvars['vars']['name'] = $name;		
	$ttvars['vars']['title'] = $title;		
	$ttvars['vars']['description'] = $description;		
	$ttvars['vars']['skin'] = $skin;		
	$ttvars['vars']['icon'] = $icon;		
	$ttvars['vars']['if_active_list'] = MakeDropDown(array(0 => $lang['menu_pro:label_off'], 1 => $lang['menu_pro:label_on']), 'if_active', $if_active);
	$ttvars['vars']['access_list'] = '<input type="checkbox" name="access[]" value="G" '.(($access & 0x01)?'checked ':' ').'/>';
	$ttvars['vars']['access_list'] .= '<input type="checkbox" name="access[]" value="T" '.(($access & 0x02)?'checked ':' ').'/>';
	$ttvars['vars']['access_list'] .= '<input type="checkbox" name="access[]" value="R" '.(($access & 0x04)?'checked ':' ').'/>';
	$ttvars['vars']['access_list'] .= '<input type="checkbox" name="access[]" value="C" '.(($access & 0x08)?'checked ':' ').'/>';
	$ttvars['vars']['access_list'] .= '<input type="checkbox" name="access[]" value="P" '.(($access & 0x10)?'checked ':' ').'/>';
	$ttvars['vars']['url'] = $url;		
	$ttvars['vars']['plugin'] = $plugin;		
	$ttvars['vars']['handler'] = $handler;		
	$ttvars['vars']['params_list'] = '';		
	$t_params = unserialize($params);
	if (is_array($t_params)) {
		$t_iter = 1;
		foreach($t_params as $key=>$val) {
			$ttvars['vars']['params_list'] .= '<tr><td>'.$t_iter.': </td><td align="left"><input type="text" name="params['.$t_iter.'][key]" value="'.$key.'" />'.'<input type="text" name="params['.$t_iter.'][value]" value="'.$val.'" /></td></tr>';
			$t_iter ++;
		}
	}
	$ttvars['vars']['parent_list'] = MakeDropDown($t_node_list, 'parent', $parent);		
	$tpath = locatePluginTemplates(array('conf.main', 'conf.add_edit.form'), 'menu_pro', 1);
	$ttvars['regx']['/\[add\](.*?)\[\/add\]/si'] = $id?'':'$1';
	$ttvars['regx']['/\[edit\](.*?)\[\/edit\]/si'] = $id?'$1':'';
	$ttvars['regx']['/\[show_name\](.*?)\[\/show_name\]/si'] = (!$id || pluginGetVariable('menu_pro', 'localize') || (isset($par_node) && !$par_node['tree_level']))?'$1':'';
	$ttvars['regx']['/\[show_title\](.*?)\[\/show_title\]/si'] = pluginGetVariable('menu_pro', 'localize')?'':'$1';
	$tpl->template('conf.add_edit.form', $tpath['conf.add_edit.form']);
	$tpl->vars('conf.add_edit.form', $ttvars);
	$tvars['vars']['entries'] = $tpl->show('conf.add_edit.form');
	$tvars['vars']['action'] = $id?$lang['menu_pro:button_add_submit']:$lang['menu_pro:button_edit_submit'];
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function move($action)
{
	global $mysql;
	@include_once root.'includes/classes/dbtree.class.ng.php';
	$tree = new dbtree(prefix.'_menu_pro', array('id' => 'id', 'left' => 'tree_left', 'right' => 'tree_right', 'level' => 'tree_level'), $mysql, true);
	$id = intval($_REQUEST['id']);
	$node = $tree->GetNode($id, array('id', 'tree_left', 'tree_right', 'tree_level'));
	if ($node == false) return;
	if (is_array($row = $mysql->record('select id, tree_level from '.prefix.'_menu_pro where '.($action == 'up'?'tree_right='.db_squote($node['tree_left'] - 1):'tree_left='.db_squote($node['tree_right'] + 1)).' limit 1'))) {
		if ($row['tree_level'] != $node['tree_level']) return;
		$tree->ChangePositionAll($node['id'], $row['id'], $action == 'up'?'before':'after');
		if (pluginGetVariable('menu_pro', 'if_auto_cash')) clear_cash();	
	}
}

function delete()
{
	global $mysql, $tpl, $lang;
	@include_once root.'includes/classes/dbtree.class.ng.php';
	$tree = new dbtree(prefix.'_menu_pro', array('id' => 'id', 'left' => 'tree_left', 'right' => 'tree_right', 'level' => 'tree_level'), $mysql, true);
	if (!isset($_REQUEST['id'])) return;
	$id = intval($_REQUEST['id']);
	$cur_node = $tree->GetNode($id);
	if ($cur_node === false) return;
	if (isset($_POST['commit'])) {
		if ($_POST['commit'] == 'yes' || $_POST['commit'] == 'all') {
			if ($_POST['commit'] == 'yes') $tree->Delete($id);
			else $tree->DeleteAll($id);
			msg(array('type' => 'info', 'info' => $lang['menu_pro:info_delete']));
			if (pluginGetVariable('menu_pro', 'if_auto_cash')) clear_cash();
		}
		showlist();
		return true;
	}
	$tpath = locatePluginTemplates(array('conf.main', 'conf.commit.form'), 'menu_pro', 1);
	$tvars['vars']['id'] = $id;
	$tvars['vars']['commit'] = sprintf($lang['menu_pro:desc_commit'], (pluginGetVariable('menu_pro', 'localize')?($lang['rmenu:item_'.$cur_node['name']]?$lang['rmenu:item_'.$cur_node['name']]:'LANG LOST >> '.'rmenu:item_'.$cur_node['name']):$cur_node['title']));
	$tpl->template('conf.commit.form', $tpath['conf.commit.form']);
	$tpl->vars('conf.commit.form', $tvars);
	$tvars['vars']['entries'] = $tpl->show('conf.commit.form');
	$tvars['vars']['action'] = $lang['menu_pro:title_commit'];
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function clear_cash() {
	global $lang;
	if (($dir = get_plugcache_dir('menu_pro'))) {
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) { 
				if ($file == "." || $file == "..")
					continue;
				unlink ($dir.$file);
			}
			closedir($handle); 
		}
		msg(array('type' => 'info', 'info' => $lang['menu_pro:info_cash_clear']));
	}
}
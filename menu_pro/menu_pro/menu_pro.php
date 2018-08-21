<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

add_act('index', 'plugin_menu_pro');
if (pluginGetVariable('menu_pro', 'localize')) LoadPluginLang('menu_pro', 'menu', '', '', ':');

function plugin_menu_pro_get_access($access)
{
	global $userROW;
	$flag = 0;
	if (is_array($userROW)){
		switch($userROW['status']){
			case 1: $flag = 0x10;
			case 2: $flag = 0x08;
			case 3: $flag = 0x04;
			case 4: $flag = 0x02;
		}
	}
	else $flag = 0x01;
	if (!$flag) return false;
	if ($access & $flag) return true;
	return false;
}

function plugin_menu_pro_get_nodes(&$nodes, &$pos, $count, &$dir, &$url, &$icon_dir, &$icon_url)
{
	global $tpl, $lang;
	$if_continue = false;
	$output = '';
	$level = $nodes[$pos]['tree_level'];
	for ($pos; $pos < $count; $pos ++){
		if ($level < $nodes[$pos]['tree_level'] && $if_continue) continue;
		if ($level > $nodes[$pos]['tree_level']) return $output;
		$if_continue = false;
		if (!plugin_menu_pro_get_access($nodes[$pos]['access']) || !$nodes[$pos]['if_active']) {
			$if_continue = true;
			continue;
		}
		plugin_menu_pro_get_dir($dir, $url, $nodes[$pos]['skin'], $nodes[$pos]['tree_level']);
		$entries = '';
		$next_pos = $pos + 1;
		$cur_pos = $pos;
		if ($next_pos < $count) {
			if ($level < $nodes[$next_pos]['tree_level']){
				$entries = plugin_menu_pro_get_nodes($nodes, ++$pos, $count, $dir, $url, $icon_dir, $icon_url);
				$pos --;
			}
		}
		$pvars['vars']['entries'] = $entries;
		$pvars['regx']['/\[if_entries\](.*?)\[\/if_entries\]/si'] = $entries?'$1':'';
		$pvars['vars']['tpl_url'] = $url[$nodes[$cur_pos]['tree_level']];
		$pvars['vars']['id'] = $nodes[$cur_pos]['id'];
		$pvars['vars']['level'] = $nodes[$cur_pos]['tree_level' - 1];
		$pvars['vars']['title'] = $if_localeze?($lang['rmenu:item_'.$nodes[$cur_pos]['name']]?$lang['rmenu:item_'.$nodes[$cur_pos]['name']]:'LANG LOST >> '.'rmenu:item_'.$nodes[$cur_pos]['name']):$nodes[$cur_pos]['title'];
		$pvars['vars']['description'] = $if_localeze?($lang['rmenu:desc_'.$nodes[$cur_pos]['name']]?$lang['rmenu:desc_'.$nodes[$cur_pos]['name']]:'LANG LOST >> '.'rmenu:desc_'.$nodes[$cur_pos]['name']):$nodes[$cur_pos]['description'];
		$pvars['vars']['icon'] = '#';
		$pvars['regx']['/\[icon\](.*?)\[\/icon\]/si'] = '';
		if (file_exists($icon_dir.$nodes[$cur_pos]['icon'])) {
			$pvars['vars']['icon'] = $icon_url.$nodes[$cur_pos]['icon'];
			$pvars['regx']['/\[icon\](.*?)\[\/icon\]/si'] = '$1';
		}
		$t_params = unserialize($nodes[$cur_pos]['params']);
		$pvars['vars']['url'] = $nodes[$cur_pos]['url']?$nodes[$cur_pos]['url']:generatePluginLink($nodes[$cur_pos]['plugin'], $nodes[$cur_pos]['handler'], $t_params);
		$tpl->template('item', $dir[$nodes[$cur_pos]['tree_level']]);
		$tpl->vars('item', $pvars);
		$output .= $tpl->show('item');
	}
	return $output;
}

function plugin_menu_pro()
{
	global $mysql, $tpl, $lang, $template;
	@include_once root.'includes/classes/dbtree.class.ng.php';
	$tree = new dbtree(prefix.'_menu_pro', array('id' => 'id', 'left' => 'tree_left', 'right' => 'tree_right', 'level' => 'tree_level'), $mysql, true);
	$if_cache = pluginGetVariable('menu_pro', 'if_auto_cash');
	$if_locate = pluginGetVariable('menu_pro', 'locate_tpl');
	$if_localeze = pluginGetVariable('menu_pro', 'localize');
	$dir = array();
	$url = array();
	$icon_dir = $if_locate?extras_dir.'/menu_pro/tpl/icons/':tpl_site.'plugins/menu_pro/icons/';
	$icon_url = $if_locate?admin_url.'/menu_pro/tpl/icons/':tpl_url.'plugins/menu_pro/icons/';
	foreach ($mysql->select('select * from '.prefix.'_menu_pro where tree_level=1') as $row) {	
		if (!plugin_menu_pro_get_access($row['access']) || !$row['if_active']) {
			$template['vars']['plugin_menu_pro_'.$row['name']] = '';
			continue;
		}
		if ($if_cache) {
			$cacheFileName = md5('menu_pro'.$row['name']).'.txt';
			$cacheData = cacheRetrieveFile($cacheFileName, 30000, 'menu_pro');
			if ($cacheData != false) {
				$template['vars']['plugin_menu_pro_'.$row['name']] = $cacheData;
				continue;
			}
		}
		plugin_menu_pro_get_dir($dir, $url, $row['skin'], 1);
		$nodes = $tree->Branch($row['id'], '');
		$pos = 1;
		$entries = plugin_menu_pro_get_nodes($nodes, $pos, count($nodes), $dir, $url, $icon_dir, $icon_url);
		$tvars['vars']['entries'] = $entries;
		$tvars['regx']['/\[if_entries\](.*?)\[\/if_entries\]/si'] = $entries?'$1':'';
		$tvars['vars']['tpl_url'] = $url[$row['tree_level']];
		$tvars['vars']['id'] = $row['id'];
		$tvars['vars']['level'] = $row['tree_level' - 1];
		$tvars['vars']['title'] = $if_localeze?($lang['rmenu:item_'.$row['name']]?$lang['rmenu:item_'.$row['name']]:'LANG LOST >> '.'rmenu:item_'.$row['name']):$row['title'];
		$tvars['vars']['description'] = $if_localeze?($lang['rmenu:desc_'.$row['name']]?$lang['rmenu:desc_'.$row['name']]:'LANG LOST >> '.'rmenu:desc_'.$row['name']):$row['description'];
		$tvars['vars']['icon'] = '#';
		$tvars['regx']['/\[icon\](.*?)\[\/icon\]/si'] = '';
		if (file_exists($icon_dir.$row['icon'])) {
			$tvars['vars']['icon'] = $icon_url.$row['icon'];
			$tvars['regx']['/\[icon\](.*?)\[\/icon\]/si'] = '$1';
		}
		$t_params = unserialize($row['params']);
		$tvars['vars']['url'] = $row['url']?$row['url']:generatePluginLink($row['plugin'], $row['handler'], $t_params);
		$tpl->template('container', $dir[$row['tree_level']]);
		$tpl->vars('container', $tvars);
		$template['vars']['plugin_menu_pro_'.$row['name']] .= $tpl->show('container');
		if (pluginGetVariable('menu_pro', 'if_auto_cash')) cacheStoreFile($cacheFileName, $template['vars']['plugin_menu_pro_'.$row['name']], 'menu_pro');
	}
}

function plugin_menu_pro_get_dir(&$dir, &$url, $skin, $level)
{
	$if_locate = pluginGetVariable('menu_pro', 'locate_tpl');
	$dir[$level] = $if_locate?extras_dir.'/menu_pro/tpl/skins/':tpl_site.'plugins/menu_pro/skins/';
	$url[$level] = $if_locate?admin_url.'/menu_pro/tpl/skins/':tpl_url.'plugins/menu_pro/skins/';
	if (file_exists($dir[$level].$skin.'/container.tpl') && file_exists($dir[$level].$skin.'/container.tpl')){
		$dir[$level] .= $skin.'/';
		$url[$level] .= $skin.'/';
	}
	else if ($level == 1){
		$dir[$level] = extras_dir.'/menu_pro/tpl/skins/default/';
		$url[$level] = admin_url.'/menu_pro/tpl/skins/default/';
	}
	else{
		$dir[$level] = $dir[$level - 1];
		$url[$level] = $url[$level - 1];
	}
}

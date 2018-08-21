<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('Galaxy in danger');
	
// Preload config file    
pluginsLoadConfig();		
	
// Load lang files    
LoadPluginLang($plugin, 'config', '', '', ':');

$count = pluginGetVariable($plugin, 'count');
if ((intval($count) < 1) || (intval($count) > 20))
	$count = 1;
	
$cfg = array();	
array_push($cfg, array('name' => 'count', 'title' => $lang[$plugin.':count'], 'type' => 'input', 'value' => $count));

for ($i = 1; $i <= $count; $i++) {
	$cfgX = array(); 
	array_push($cfgX, array('name' => $i.'_number', 'title' => $lang[$plugin.':number'],'type' => 'input', 'value' => pluginGetVariable($plugin, $i.'_number')));
	array_push($cfgX, array('name' => $i.'_news_length', 'title' => $lang[$plugin.':news_length'],'type' => 'input', 'value' => pluginGetVariable($plugin, $i.'_news_length')));
	array_push($cfgX, array('name' => $i.'_categories', 'title' => $lang[$plugin.':categories'], 'type' => 'input', 'value' => pluginGetVariable($plugin, $i.'_categories')));
	array_push($cfgX, array('name' => $i.'_orderby', 'title' => $lang[$plugin.':orderby_title'], 'descr' => '', 'type' => 'select', 'values' => array ('rand' => $lang[$plugin.':orderby_rand'], 'asc' => $lang[$plugin.':orderby_asc'], 'desc' => $lang[$plugin.':orderby_desc']), 'value' => pluginGetVariable($plugin, $i.'_orderby')));
	array_push($cfgX, array('name' => $i.'_short_news', 'title' => $lang[$plugin.':short_news'], 'type' => 'checkbox', value => pluginGetVariable($plugin, $i.'_short_news')));
	array_push($cfgX, array('name' => $i.'_img', 'title' => $lang[$plugin.':img'], 'type' => 'checkbox', value => pluginGetVariable($plugin, $i.'_img')));
	array_push($cfgX, array('name' => $i.'_view_short', 'title' => $lang[$plugin.':view_short'], 'type' => 'checkbox', value => pluginGetVariable($plugin, $i.'_view_short')));
	array_push($cfgX, array('name' => $i.'_view_full', 'title' => $lang[$plugin.':view_full'], 'type' => 'checkbox', value => pluginGetVariable($plugin, $i.'_view_full')));
	if(getPluginStatusActive('xfields'))
		array_push($cfgX, array('name' => $i.'_pcall', 'title' => $lang[$plugin.':pcall'], 'type' => 'checkbox', value => pluginGetVariable($plugin, $i.'_pcall')));
	array_push($cfg,  array('mode' => 'group', 'title' => '<b>'.$lang[$plugin.':settings'].$i.' {same_category_news_'.$i.'}</b>', 'entries' => $cfgX));
}

$cfgX = array();
array_push($cfgX, array('name' => 'localsource', 'title' => $lang[$plugin.':template_location'], 'type' => 'select', 'values' => array ( '0' => 'Шаблон сайта', '1' => 'Плагин'), 'value' => intval(pluginGetVariable($plugin, 'localsource'))));
array_push($cfg,  array('mode' => 'group', 'title' => $lang[$plugin.':settings_view'], 'entries' => $cfgX));

if ($_REQUEST['action'] == 'commit') {
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page($plugin, $cfg);
}
<?php

plugins_load_config();
LoadPluginLang('news_messaging', 'news_messaging', '', 'nmes');

$cfg = array();
array_push($cfg, array('descr' => $lang['nmes_descr']));

array_push($cfg, array('name' => 'nsubject', 'title' =>  $lang['nmes_subject'], 'type' => 'input', 'html_flags' => 'size=40', 'value' => pluginGetVariable($plugin,'nsubject')));
array_push($cfg, array('name' => 'ncontent', 'title' =>  $lang['nmes_content'], 'type' => 'text', 'html_flags' => 'rows=8 cols=60 name=ncontent id=ncontent', 'value'  => pluginGetVariable($plugin,'ncontent')));


/*
if ($_REQUEST['action'] == 'commit') {
	messaging($_REQUEST['subject'], $_REQUEST['content']);
} else {
	generate_config_page('messaging', $cfg);
}
*/

// RUN
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page($plugin, $cfg);
}


?>
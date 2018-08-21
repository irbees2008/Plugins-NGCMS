<?php

plugins_load_config();
LoadPluginLang('twitter_post', 'config', '', 'nmes');

$cfg = array();
array_push($cfg, array('descr' => $lang['nmes_descr']));

array_push($cfg, array('name' => 'consumer_key', 'title' =>  $lang['nmes_consumer_key'], 'type' => 'input', 'html_flags' => 'size=140', 'value' => pluginGetVariable($plugin,'consumer_key')));
array_push($cfg, array('name' => 'consumer_secret', 'title' =>  $lang['nmes_consumer_secret'], 'type' => 'input', 'html_flags' => 'size=140', 'value' => pluginGetVariable($plugin,'consumer_secret')));
array_push($cfg, array('name' => 'access_token', 'title' =>  $lang['nmes_access_token'], 'type' => 'input', 'html_flags' => 'size=140', 'value' => pluginGetVariable($plugin,'access_token')));
array_push($cfg, array('name' => 'access_token_secret', 'title' =>  $lang['nmes_access_token_secret'], 'type' => 'input', 'html_flags' => 'size=140', 'value' => pluginGetVariable($plugin,'access_token_secret')));
array_push($cfg, array('name' => 'message_template', 'title' =>  $lang['nmes_message_template'], 'type' => 'text', 'html_flags' => 'rows=8 cols=160 name=message_template id=message_template', 'value'  => pluginGetVariable($plugin,'message_template')?pluginGetVariable($plugin,'message_template'):'{news_title} :: {link_to_news}'));

// RUN
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page($plugin, $cfg);
}


?>

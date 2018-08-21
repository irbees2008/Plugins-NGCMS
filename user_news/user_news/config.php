<?php

//
// Configuration file for plugin
//

// Preload config file
plugins_load_config();


// Fill configuration parameters
$cfg = array();
array_push($cfg, array('name' => 'templ', 'title' => 'Имя файла шаблона выводимых новостей','type' => 'input', 'html_flags' => 'style="width: 290px;"', 'value' => extra_get_param('user_news','templ')));
array_push($cfg, array('name' => 'c_num', 'title' => 'Количество выводимых в блоге новостей','type' => 'input', 'html_flags' => 'style="width: 290px;"', 'value' => extra_get_param('user_news','c_num')));

// RUN 
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes('user_news', $cfg);
	print "Changes commited<br>\n";
} else {
	generate_config_page('user_news', $cfg);
}


?>
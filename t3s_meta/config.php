<?php

//
// Configuration file for plugin
//

// Preload config file
plugins_load_config();


// Fill configuration parameters

$cfg = array();

$cfgX = array();
array_push($cfgX, array('name' => 'cache', 'title' => "Использовать кеширование<br /><small><b>Да</b> - кеширование используется<br /><b>Нет</b> - кеширование не используется</small>", 'type' => 'select', 'values' => array ( '1' => 'Да', '0' => 'Нет'), 'value' => intval(extra_get_param('t3s_meta','cache'))));
array_push($cfgX, array('name' => 'cacheExpire', 'title' => 'Период обновления кеша (в секундах)<br /><small>(через сколько секунд происходит обновление кеша. Значение по умолчанию: <b>10800</b>, т.е. 3 часа)', 'type' => 'input', 'value' => intval(extra_get_param('t3s_meta','cacheExpire'))?extra_get_param('t3s_meta','cacheExpire'):'10800'));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки кеширования</b>', 'entries' => $cfgX));
// RUN 
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes('t3s_meta', $cfg);
	print_commit_complete('t3s_meta');
} else {
	generate_config_page('t3s_meta', $cfg);
}

?>
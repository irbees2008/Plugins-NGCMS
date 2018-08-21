<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

//
// Configuration file for plugin
//

// Preload config file
plugins_load_config();

// Load lang files
LoadPluginLang('csmonitor', 'config', '', 'csmt', ':');

$count = extra_get_param($plugin,'count');
if ((intval($count) < 1)||(intval($count) > 20))
$count = 3;


// Fill configuration parameters
$cfg = array();
array_push($cfg, array('descr' => 'Плагин мониторинга CS серверов.<br />После включения плагина станут доступны переменные {plugin_csmonitor_1}, {plugin_csmonitor_2}, ..., {plugin_csmonitor_<b>N</b>}.'));
array_push($cfg, array('name' => 'count', 'title' => "Кол-во блоков", 'type' => 'input', 'value' => $count));

for ($i = 1; $i <= $count; $i++) {
$cfgX = array();
array_push($cfgX, array('name' => 'server'.$i, 'title' => $lang['csmt:ip'], 'type' => 'input', 'value' => extra_get_param($plugin,'server'.$i)));
array_push($cfgX, array('name' => 'port'.$i, 'title' => $lang['csmt:port'], 'type' => 'input', 'value' => extra_get_param($plugin,'port'.$i)));
array_push($cfgX, array('name' => 'cache'.$i, 'title' => "Использовать кеширование данных<br /><small><b>Да</b> - кеширование используется<br /><b>Нет</b> - кеширование не используется</small>", 'type' => 'select', 'values' => array ( '1' => 'Да', '0' => 'Нет'), 'value' => intval(extra_get_param($plugin,'cache'.$i))));
array_push($cfgX, array('name' => 'cacheExpire'.$i, 'title' => "Период обновления кеша<br /><small>(через сколько секунд происходит обновление кеша. Значение по умолчанию: <b>60</b>)</small>", 'type' => 'input', 'value' => intval(extra_get_param($plugin,'cacheExpire'.$i))?extra_get_param($plugin,'cacheExpire'.$i):'60'));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки блока № <b>'.$i.'</b>', 'entries' => $cfgX));

}

// RUN
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page($plugin, $cfg);
}


?>
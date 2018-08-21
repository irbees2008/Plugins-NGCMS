<?php

# protect against hack attempts
if (!defined('NGCMS')) die ('Galaxy in danger');

# preload config file
PluginsLoadConfig();

# fill configuration parameters
$cfg = array();

$cfgX = array();
array_push($cfgX, array('name' => 'localsource', 'title' => 'Выберите каталог из которого плагин будет брать шаблоны для отображения<br /><small><b>Шаблон сайта</b> - плагин будет пытаться взять шаблоны из общего шаблона сайта; в случае недоступности - шаблоны будут взяты из собственного каталога плагина<br /><b>Плагин</b> - шаблоны будут браться из собственного каталога плагина</small>', 'type' => 'select', 'values' => array ( '0' => 'Шаблон сайта', '1' => 'Плагин'), 'value' => intval(pluginGetVariable($plugin,'localsource'))));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки отображения</b>', 'entries' => $cfgX));

$cfgX = array();
array_push($cfgX, array('name' => 'timeout', 'title' => 'Таймаут<br /><small>Онлайн пользователи за последние N секунд<br />Значение по умолчанию: <b>300</b></small>', 'type' => 'input', 'value' => intval(pluginGetVariable($plugin, 'timeout'))?pluginGetVariable($plugin ,'timeout'):'300'));
array_push($cfgX, array('name' => 'time_clear', 'title' => 'Таймаут очистки БД от старых записей<br /><small>Значение по умолчанию: <b>3600</b></small>', 'type' => 'input', 'value' => intval(pluginGetVariable($plugin, 'time_clear'))?pluginGetVariable($plugin ,'time_clear'):'3600'));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>Общие</b>', 'entries' => $cfgX));


// RUN 
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page($plugin, $cfg);
}

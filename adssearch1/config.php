<?php
//
// Configuration file for plugin
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');
// Preload config file
plugins_load_config();
// Fill configuration parameters
$cfg = array();
array_push($cfg, array('descr' => 'Плагин поиска по цене и по площади. Ниже можно настроить некоторые параметры.'));


$cfgX = array();
array_push($cfgX, array('name' => 'cache', 'title' => "Использовать кеширование<br /><small><b>Да</b> - кеширование используется<br /><b>Нет</b> - кеширование не используется</small>", 'type' => 'select', 'values' => array ( '1' => 'Да', '0' => 'Нет'), 'value' => intval(extra_get_param('adssearch1','cache'))));
array_push($cfgX, array('name' => 'cacheExpire', 'title' => 'Период обновления кеша (в секундах)<br /><small>(через сколько секунд происходит обновление кеша. Значение по умолчанию: <b>10800</b>, т.е. 3 часа)', 'type' => 'input', 'value' => intval(extra_get_param('adssearch1','cacheExpire'))?extra_get_param('adssearch1','cacheExpire'):'10800'));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки кеширования</b>', 'entries' => $cfgX));
// RUN 
if ($_REQUEST['action'] == 'commit') {
       // If submit requested, do config save
       commit_plugin_config_changes($plugin, $cfg);
       print_commit_complete($plugin);
} else {
       generate_config_page($plugin, $cfg);
}
?>
<?

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');
//
// Configuration file for plugin
//
// Preload config file
plugins_load_config();
// Fill configuration parameters
$cfg = array();
$cfgX = array();
array_push($cfgX, array('name' => 'number', 'title' => 'Количество случайных новостей', 'descr' => 'По умолчанию стоит: 10', 'type' => 'input', 'value' => intval(extra_get_param($plugin,'number'))));
array_push($cfg,  array('mode' => 'group', 'title' => 'Настройки плагина', 'entries' => $cfgX));
 
// RUN 
if ($_REQUEST['action'] == 'commit') {
       // If submit requested, do config save
       commit_plugin_config_changes($plugin, $cfg);
       print_commit_complete($plugin);
} else {
       generate_config_page($plugin, $cfg);
}
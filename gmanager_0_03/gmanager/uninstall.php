<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');
loadPluginLang($plugin, 'config', '', '', ':');

$db_update = array(
 array(
  'table'  => 'gmanager',
  'action' => 'drop',
  ),
);


$ULIB = new urlLibrary();
$ULIB->loadConfig();
$ULIB->removeCommand('gmanager', '');
$ULIB->removeCommand('gmanager', 'gallery');
$ULIB->removeCommand('gmanager', 'image');

if ($_REQUEST['action'] == 'commit') {
	fixdb_plugin_install($plugin, $db_update, 'deinstall');
	unset($PLUGINS['config'][$plugin]);
	pluginsSaveConfig();
	$ULIB->saveConfig();
	plugin_mark_deinstalled($plugin);
} else {
	generate_install_page($plugin, $lang['gmanager:desc_deinstall'], 'deinstall');
}
?>
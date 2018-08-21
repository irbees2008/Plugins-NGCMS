<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

//
// Configuration file for plugin
//

plugins_load_config();
loadPluginLang('videothumb', 'config', '', '', ':');

$db_update = array(
 array(
  'table'  => 'news',
  'action' => 'modify',
  'fields' => array(
    array('action' => 'drop', 'name' => 'videothumb_link'),
    array('action' => 'drop', 'name' => 'videothumb_img'),
  )
 )
);

if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	if (fixdb_plugin_install('videothumb', $db_update, 'deinstall')) {
		plugin_mark_deinstalled('videothumb');
	}	
} else {
	$text = $lang['videothumb:desc_install'];
	generate_install_page('videothumb', $text, 'deinstall');
}

?>
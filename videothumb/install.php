<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

//
// Configuration file for plugin
//

//
// Install script for plugin.
// $action: possible action modes
// 	confirm		- screen for installation confirmation
//	apply		- apply installation, with handy confirmation
//	autoapply       - apply installation in automatic mode [INSTALL script]
//
function plugin_videothumb_install($action) {
	global $lang;

	if ($action != 'autoapply')
		loadPluginLang('videothumb', 'config', '', '', ':');

	// Fill DB_UPDATE configuration scheme
	$db_update = array(
	 array(
	  'table'  => 'news',
	  'action' => 'cmodify',
	  'fields' => array(
	    array('action' => 'cmodify', 'name' => 'videothumb_link', 'type' => 'text', 'params' => "default ''"),
	    array('action' => 'cmodify', 'name' => 'videothumb_img', 'type' => 'text', 'params' => "default ''"),
	  )
	 )

	);

	// Apply requested action
	switch ($action) {
		case 'confirm':
			generate_install_page('videothumb', $lang['videothumb:desc_install']);
			break;
		case 'autoapply':
		case 'apply':
			if (fixdb_plugin_install('videothumb', $db_update, 'install', ($action=='autoapply')?true:false)) {
				plugin_mark_installed('videothumb');
			} else {
				return false;
			}
			
			pluginsSaveConfig();

			break;
	}
	return true;
}

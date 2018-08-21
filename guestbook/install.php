<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

//
// Configuration file for plugin
//

plugins_load_config();
//LoadPluginLang($plugin, 'main');


$db_update = array(
	array(
		'table'		=>	'guestbook',
		'action'	=>	'create',
		'key'		=>	'primary key(`id`)',
		'fields'	=>	array(
			array('action' => 'create', 'name' => 'id', 'type' => 'int', 'params' => 'not null auto_increment'),
			array('action' => 'create', 'name' => 'postdate',  'type' => 'int', 'params' => "not null default '0'"),
			array('action' => 'create', 'name' => 'message', 'type' => 'text', 'params' => 'not null'),
			array('action' => 'create', 'name' => 'author', 'type' => 'varchar(50)', 'params' => "not null default ''"),
			array('action' => 'create', 'name' => 'ip', 'type' => 'varchar(40)', 'params' => "not null default ''"),
		)
	),	
);


if ($_REQUEST['action'] == 'commit') {
	if (fixdb_plugin_install($plugin, $db_update)) {
		plugin_mark_installed($plugin);
	}	
} else {
	$text = 'Плагин позволяет организовать гостевую книгу на вашем сайте<br />';
	generate_install_page($plugin, $text);
}

?>
<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');
plugins_load_config();
$db_update = array(
	array(
		'table'		=>	'blokmanager',
		'action'	=>	'drop',
	),
);
if ($_REQUEST['action'] == 'commit') {
	if (fixdb_plugin_install($plugin, $db_update, 'deinstall')) {
		plugin_mark_deinstalled($plugin);
	}
} else {
	$text = 'Cейчас плагин будет удален';
	generate_install_page('blokmanager', $text, 'deinstall');
}

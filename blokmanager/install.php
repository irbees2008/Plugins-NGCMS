<?php
if (!defined('NGCMS'))die ('HAL');

function plugin_blokmanager_install($action) {
	$db_create = array(
		array(
			'table' => 'blokmanager',
			'action' => 'cmodify',
			'key' => 'primary key (`id`)',
			'fields' => array(
				array('action' => 'cmodify', 'name' => '`id`', 'type' => 'int(11)', 'params' => 'UNSIGNED NOT NULL AUTO_INCREMENT'),
				array('action' => 'cmodify', 'name' => '`blokcode`', 'type' => 'text', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => '`outerblok`', 'type' => 'text', 'params' => 'NOT NULL DEFAULT \'\''),
			)
		)
	);
	
	switch ($action) {
		case 'confirm':generate_install_page('blokmanager', 'Cейчас плагин будет установлен');break;
		case 'autoapply':
		case 'apply':
			if (fixdb_plugin_install('blokmanager', $db_create, 'install', ($action=='autoapply')?true:false)) {
				plugin_mark_installed('blokmanager');
			} else {
				return false;
			}
			break;
	}
	return true;
}

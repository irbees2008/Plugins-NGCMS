<?php
if (!defined('NGCMS'))die ('HAL');

function plugin_menu_pro_install($action) {
	global $mysql; 
	$db_create = array(
		array(
			'table' => 'menu_pro',
			'action' => 'cmodify',
			'key' => 'primary key (`id`)',
			'engine' => 'innodb',
			'fields' => array(
				array('action' => 'cmodify', 'name' => 'id', 'type' => 'int(11)', 'params' => 'UNSIGNED NOT NULL AUTO_INCREMENT'), 
				array('action' => 'cmodify', 'name' => 'tree_left', 'type' => 'int(11)', 'params' => 'UNSIGNED NOT NULL DEFAULT 0'), 
				array('action' => 'cmodify', 'name' => 'tree_right', 'type' => 'int(11)', 'params' => 'UNSIGNED NOT NULL DEFAULT 0'), 
				array('action' => 'cmodify', 'name' => 'tree_level', 'type' => 'int(11)', 'params' => 'UNSIGNED NOT NULL DEFAULT 0'), 
				array('action' => 'cmodify', 'name' => 'name', 'type' => 'varchar(20)', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => 'title', 'type' => 'varchar(20)', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => 'description', 'type' => 'varchar(100)', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => 'skin', 'type' => 'varchar(20)', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => 'icon', 'type' => 'varchar(20)', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => 'if_active', 'type' => 'int(1)', 'params' => 'UNSIGNED NOT NULL DEFAULT 0'), 	
				array('action' => 'cmodify', 'name' => 'access', 'type' => 'int(1)', 'params' => 'UNSIGNED NOT NULL DEFAULT 0'), 	
				array('action' => 'cmodify', 'name' => 'url', 'type' => 'varchar(50)', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => 'plugin', 'type' => 'varchar(20)', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => 'handler', 'type' => 'varchar(20)', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => 'params', 'type' => 'text', 'params' => 'NOT NULL DEFAULT \'\''),
				)
			),
		);

	switch ($action) {
		case 'confirm':generate_install_page('menu_pro', 'Cейчас плагин будет установлен');break;
		case 'autoapply':
		case 'apply':
			if (fixdb_plugin_install('menu_pro', $db_create, 'install', ($action=='autoapply')?true:false)) {
				$row = $mysql->record('select count(id) from '.prefix.'_menu_pro');
				if (!is_array($row) || !$row[0])
				{
					@include_once root.'includes/classes/dbtree.class.ng.php';
					$tree = new dbtree(prefix.'_menu_pro', array('id' => 'id', 'left' => 'tree_left', 'right' => 'tree_right', 'level' => 'tree_level'), $mysql, true);
					$tree->Clear();
				}
				plugin_mark_installed('menu_pro');
			} else {
				return false;
			}
			$params = array(
				'localize'	=> 0,
				'locate_tpl'	=> 1,
				'if_auto_cash'	=> 0,
				'if_description'=> 0,
				'if_keywords'	=> 0,
			);

			foreach ($params as $k => $v) {
				pluginSetVariable('gmanager', $k, $v);
			}
			pluginsSaveConfig();
			break;
	}
	return true;
}

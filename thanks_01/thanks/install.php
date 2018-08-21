<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');
 
function plugin_thanks_install($action) {
	global $lang;
	
	if ($action != 'autoapply')
	
	$db_create = array(
		array(
			'table' => 'thanks',
			'action' => 'cmodify',
			'key' => 'primary key (`id`)',
			'fields' => array(
				array('action' => 'cmodify', 'name' => '`id`', 'type' => 'int(11)', 'params' => "UNSIGNED NOT NULL AUTO_INCREMENT"),
				array('action' => 'cmodify', 'name' => '`id_post`', 'type' => 'int(11)', 'params' => "NOT NULL default '0'"),
				array('action' => 'cmodify', 'name' => '`user_id`', 'type' => 'int(11)', 'params' => "NOT NULL default '0'"),
				array('action' => 'cmodify', 'name' => '`user_name`', 'type' => 'varchar(255)', 'params' => "NOT NULL default ''"),
				array('action' => 'cmodify', 'name' => '`host_ip`', 'type' => 'varchar(100)', 'params' => "NOT NULL default ''"),
			)
		),

		array(
			 'table'  => 'users',
			 'action' => 'cmodify',
			 'fields' => array(
				array('action' => 'cmodify', 'name' => 'thx_num', 'type' => 'int(11)', 'params' => "NOT NULL default '0'"),
			)
		),
	);

	switch ($action) {
		case 'confirm': 
			 generate_install_page('thanks', '<font color=red>Плагин реализует кнопку/ссылку "Спасибо", нажав на которую, пользователь благодарит автора новости/статьи.</font>');
			 break;
		case 'autoapply':
		case 'apply':
			if (fixdb_plugin_install('thanks', $db_create, 'install', ($action=='autoapply')?true:false)) {
				plugin_mark_installed('thanks');
			} else {
				return false;
			}
			break;
	}
	return true;
}	

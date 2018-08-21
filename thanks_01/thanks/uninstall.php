<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');


$db_update = array(
	array(
		'table'		=>	'thanks',
		'action'	=>	'drop',
	),
	
	array(
		'table'  => 'users',
		'action' => 'modify',
		'fields' => array(
					array('action' => 'drop', 'name' => 'thx_num'),
		)
	),
);

if ($_REQUEST['action'] == 'commit') {
	if (fixdb_plugin_install('thanks', $db_update, 'deinstall')) {
		plugin_mark_deinstalled('thanks');
	}
} else {
	generate_install_page('thanks', '<font color=red>Внимание! Удаление плагина приведёт к удалению всех данных из базы данных. Вы уверены?</font>', 'deinstall');
}
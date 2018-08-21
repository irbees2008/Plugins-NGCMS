<?php

/*
 * Install plugin "who_online" for NextGeneration CMS (http://ngcms.ru/)
 * Copyright (C) 2011 Alexey N. Zhukov (http://digitalplace.ru)
 * http://digitalplace.ru
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */
 
# protect against hack attempts
if (!defined('NGCMS')) die ('Galaxy in danger');

function plugin_who_online_install($action) {
	global $lang;
	
	if ($action != 'autoapply')
		loadPluginLang('who_online', 'config', '', '', ':');
		
	$db_create = array(
		array(
			'table' => 'online',
			'action' => 'cmodify',
			'key' => 'primary key (`session`)',
			'fields' => array(
				array('action' => 'cmodify', 'name' => 'session', 'type' => 'varchar(35)', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => 'id', 'type' => 'int(11)', 'params' => 'UNSIGNED NOT NULL DEFAULT 0'),
				array('action' => 'cmodify', 'name' => 'lasttime', 'type' => 'int(10)', 'params' => 'UNSIGNED NOT NULL DEFAULT 0'),
				array('action' => 'cmodify', 'name' => 'ip', 'type' => 'varchar(30)', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => 'agent', 'type' => 'varchar(255)', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => 'login', 'type' => 'varchar(30)', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => 'status', 'type' => 'tinyint(1)', 'params' => 'NOT NULL DEFAULT 0'),
				array('action' => 'cmodify', 'name' => 'avatar', 'type' => 'varchar(100)', 'params' => ''),
				array('action' => 'cmodify', 'name' => 'reg', 'type' => 'int(10)', 'params' => 'NOT NULL DEFAULT 0'),
				array('action' => 'cmodify', 'name' => 'com', 'type' => 'int(11)', 'params' => 'NOT NULL DEFAULT 0')
			)
		)
	);

	switch ($action) {
		case 'confirm': 
			 generate_install_page('who_online', $lang['who_online:install']);
			 break;
		case 'autoapply':
		case 'apply':
			if (fixdb_plugin_install('who_online', $db_create, 'install', ($action=='autoapply')?true:false)) {
				plugin_mark_installed('who_online');
				pluginSetVariable('who_online', 'timeout', '300');
				pluginSetVariable('who_online', 'time_clear', '3600');
				pluginsSaveConfig();
			} else {
				return false;
			}
			break;
	}
	return true;
}

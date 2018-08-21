<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

//
// Voting plugin installer
//

plugins_load_config();
function plugin_newsvotes_install($action) {
	global $lang;

	if ($action != 'autoapply')
			loadPluginLang('newsvotes', 'config', '', '', ':');


	$db_update = array(
     array(
			'table'		=>	'news',
			'action'	=>	'modify',
			'fields'	=>	array(
				array('action' => 'cmodify', 'name' => 'nvote', 'type' => 'int', 'params' => 'not null default 0'),
			)
		),
	 array(
	  'table'  => 'newsvote',
	  'action' => 'cmodify',
	  'key'    => 'primary key(id)',
	  'fields' => array(
	    array('action' => 'cmodify', 'name' => 'id', 'type' => 'int', 'params' => 'not null auto_increment'),
	    array('action' => 'cmodify', 'name' => 'newsid', 'type' => 'int', 'params' => 'default 0'),
	    array('action' => 'cmodify', 'name' => 'title', 'type' => 'char(200)', 'params' => 'not null default \'\''),
        array('action' => 'cmodify', 'name' => 'count', 'type' => 'int', 'params' => 'default 0'),
	  )
	 ),
	 array(
	  'table'  => 'newsanswer',
	  'action' => 'cmodify',
	  'key'    => 'primary key(id), KEY `n_ansvote` (`voteid`)',
	  'fields' => array(
	    array('action' => 'cmodify', 'name' => 'id', 'type' => 'int', 'params' => 'not null auto_increment'),
	    array('action' => 'cmodify', 'name' => 'voteid', 'type' => 'int', 'params' => 'default 0'),
	    array('action' => 'cmodify', 'name' => 'name', 'type' => 'char(100)'),
	    array('action' => 'cmodify', 'name' => 'number', 'type' => 'int', 'params' => 'default 0'),
	  )
	 ),
	 array(
	  'table'  => 'newsvoted',
	  'action' => 'cmodify',
	  'key'	   => 'primary key(id), KEY `n_vote` (`voteid`)',
	  'fields' => array(
	   array('action' => 'cmodify', 'name' => 'id', 'type' => 'int', 'params' => 'not null auto_increment'),
	   array('action' => 'cmodify', 'name' => 'voteid', 'type' => 'int', 'params' => 'default 0'),
       array('action' => 'cmodify', 'name' => 'ip', 'type' => 'char(15)'),
	  ),
	 ),
	);


	// Apply requested action
	switch ($action) {
		case 'confirm':
			generate_install_page('newsvotes', $lang['newsvotes:desc_install']);
			break;
		case 'autoapply':
		case 'apply':
			if (fixdb_plugin_install('newsvotes', $db_update, 'install', ($action=='autoapply')?true:false)) {
				plugin_mark_installed('newsvotes');
			} else {
				return false;
			}            

			break;
	}
	return true;
}
<?php
if (!defined('NGCMS'))die ('HAL');

function plugin_gmanager_install($action) {
	global $mysql, $config, $lang;

	if ($action != 'autoapply')
		loadPluginLang('gmanager', 'config', '', '', ':');

	// Fill DB_UPDATE configuration scheme
	$db_update = array(
	 array(
	  'table'  => 'gmanager',
	  'action' => 'cmodify',
	  'key'	   => 'primary key(id)',
	  'fields' => array(
	    array('action' => 'cmodify', 'name' => 'id', 			'type' => 'int(11)', 'params' => 'auto_increment'),
	    array('action' => 'cmodify', 'name' => 'iorder', 		'type' => 'int(11)', 'params' => "default 0"),
	    array('action' => 'cmodify', 'name' => 'id_icon', 		'type' => 'int(11)', 'params' => "default 0"),
	    array('action' => 'cmodify', 'name' => 'count_cells',	'type' => 'smallint(2)', 'params' => "default 5"),
	    array('action' => 'cmodify', 'name' => 'count_rows',	'type' => 'smallint(2)', 'params' => "default 5"),
	    array('action' => 'cmodify', 'name' => 'if_active', 	'type' => 'tinyint(1)', 'params' => "default 0"),
	    array('action' => 'cmodify', 'name' => 'if_number', 	'type' => 'tinyint(1)', 'params' => "default 1"),
	    array('action' => 'cmodify', 'name' => 'skin', 			'type' => 'varchar(25)', 'params' => "default ''"),
	    array('action' => 'cmodify', 'name' => 'name', 			'type' => 'varchar(25)', 'params' => "default ''"),
	    array('action' => 'cmodify', 'name' => 'title', 		'type' => 'varchar(50)', 'params' => "default ''"),
	    array('action' => 'cmodify', 'name' => 'description', 	'type' => 'text', 'params' => "default ''"),
	    array('action' => 'cmodify', 'name' => 'keywords', 		'type' => 'text', 'params' => "default ''"),
	  )
	 ),
	 array(
	  'table'  => 'comments',
	  'action' => 'cmodify',
	  'fields' => array(
	    array('action' => 'cmodify', 'name' => 'module', 'type' => 'char(100)', 'params' => "default 'news'"),
	  )
	 ),
	 array(
	  'table'  => 'images',
	  'action' => 'cmodify',
	  'fields' => array(
	    array('action' => 'cmodify', 'name' => 'com', 		'type' => 'int(11)', 'params' => "default 0"),
	    array('action' => 'cmodify', 'name' => 'views', 		'type' => 'int(11)', 'params' => "default 0"),
	  )
	 ),
	);
	
	$ULIB = new urlLibrary();
	$ULIB->loadConfig();
	$ULIB->registerCommand('gmanager', '',
		array ('vars' => array(
				'' => array(
					'matchRegex' => '.+?', 
					'descr' => array(
						$config['default_lang'] => $lang['gmanager:ULIB_main']
					)
				),
				'page' => array(
					'matchRegex' => '\d{1,4}', 
					'descr' => array(
						$config['default_lang'] => $lang['gmanager:ULIB_page']
					)
				),
			),
			'descr'	=> array ($config['default_lang'] => $lang['gmanager:ULIB_main_d']),
		)
	);
	$ULIB->registerCommand('gmanager', 'gallery',
		array ('vars' => array(
				'name' => array(
					'matchRegex' => '.+?', 
					'descr' => array(
						$config['default_lang'] => $lang['gmanager:ULIB_name']
						)
					),
				'id' => array(
					'matchRegex' => '\d{1,4}', 
					'descr' => array(
						$config['default_lang'] => $lang['gmanager:ULIB_id']
						)
					),
				'page' => array(
					'matchRegex' => '\d{1,4}', 
					'descr' => array(
						$config['default_lang'] => $lang['gmanager:ULIB_page']
					)
				),
			),
			'descr'	=> array ($config['default_lang'] => $lang['gmanager:ULIB_gallery_d']),
		)
	);
	$ULIB->registerCommand('gmanager', 'widget',
		array ('vars' => array(
				'name' => array(
					'matchRegex' => '.+?', 
					'descr' => array(
						$config['default_lang'] => $lang['gmanager:label_widget_name']
						)
					),
				'id' => array(
					'matchRegex' => '\d{1,4}', 
					'descr' => array(
						$config['default_lang'] => 'Код виджета'
						)
					),
				'sort' => array(
					'matchRegex' => '\d{1,4}', 
					'descr' => array(
						$config['default_lang'] => 'Сортировка'
					)
				),
			),
			'descr'	=> array ($config['default_lang'] => $lang['gmanager:ULIB_gallery_d']),
		)
	);
	$ULIB->registerCommand('gmanager', 'image',
		array ('vars' => array(
				'gallery' => array(
					'matchRegex' => '.+?', 
					'descr' => array(
						$config['default_lang'] => $lang['gmanager:ULIB_name']
						)
					),
				'name' => array(
					'matchRegex' => '.+?', 
					'descr' => array(
						$config['default_lang'] => $lang['gmanager:ULIB_image_name']
						)
					),
				'id' => array(
					'matchRegex' => '\d{1,4}', 
					'descr' => array(
						$config['default_lang'] => $lang['gmanager:ULIB_image_id']
						)
					),
			),
			'descr'	=> array ($config['default_lang'] => $lang['gmanager:ULIB_image_d']),
		)
	);

	// Apply requested action
	switch ($action) {
		case 'confirm':
			generate_install_page('gmanager', $lang['gmanager:desc_install']);
			break;
		case 'autoapply':
		case 'apply':
			if (fixdb_plugin_install('gmanager', $db_update, 'install', ($action=='autoapply')?true:false)) {
				$mysql->query("update ".prefix."_comments set module='news' where module=''");
				plugin_mark_installed('gmanager');
				$params = array(
					'locate_tpl'	=> 1,
					'skin'			=> 'default',
					'if_auto_cash'	=> 0,
					'if_description'=> 0,
					'if_keywords'	=> 0,
					'main_row'		=> 5,
					'main_cell'		=> 5,
					'main_page'		=> 1,
				);

				foreach ($params as $k => $v) {
					pluginSetVariable('gmanager', $k, $v);
				}
				pluginsSaveConfig();
				$ULIB->saveConfig();
			} else {
				return false;
			}
			break;
	}
	return true;
}

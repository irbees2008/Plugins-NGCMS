<?php
/*
=====================================================
 NG Quotes v0.01
-----------------------------------------------------
 Author: Nail' R. Davydov (ROZARD)
-----------------------------------------------------
 Jabber: ROZARD@ya.ru
 E-mail: ROZARD@list.ru
-----------------------------------------------------
 © Настоящий программист никогда не ставит 
 комментариев. То, что писалось с трудом, должно 
 пониматься с трудом. :))
-----------------------------------------------------
 Данный код защищен авторскими правами
=====================================================
*/
if (!defined('NGCMS'))
{
	die ('HAL');
}

function plugin_quotes_install($action) {
	global $lang;
	
	if ($action != 'autoapply')
		loadPluginLang('quotes', 'config', '', '', ':');
	$db_update = array(
		array(
			'table' => 'quotes',
			'action' => 'cmodify',
			'key' => 'primary key(id), key `approve` (`approve`), key `postdate` (`postdate`), key `rating` (`rating`)',
			'fields' => array(
				array('action' => 'cmodify', 'name' => 'id', 'type' => 'int(11)', 'params' => 'UNSIGNED NOT NULL AUTO_INCREMENT'),
				array('action' => 'cmodify', 'name' => 'postdate', 'type' => 'int(10)', 'params' => 'UNSIGNED NOT NULL DEFAULT \'0\''),
				array('action' => 'cmodify', 'name' => 'content', 'type' => 'text', 'params' => 'NOT NULL'),
				array('action' => 'cmodify', 'name' => 'approve', 'type' => 'tinyint(1)', 'params' => 'UNSIGNED DEFAULT \'0\''),
				array('action' => 'cmodify', 'name' => 'rating', 'type' => 'int(10)', 'params' => 'NOT NULL DEFAULT \'0\''),
				array('action' => 'cmodify', 'name' => 'author', 'type' => 'varchar(100)', 'params' => 'NOT NULL DEFAULT \'\''),
				array('action' => 'cmodify', 'name' => 'author_id', 'type' => 'int(11)', 'params' => 'UNSIGNED NOT NULL DEFAULT \'0\''),
			)
		),
		array(
			'table' => 'quotes_flood',
			'action' => 'cmodify',
			'key' => 'primary key(id_quotes)',
			'fields' => array(
				array('action' => 'cmodify', 'name' => 'id_user', 'type' => 'int(11)', 'params' => 'DEFAULT NULL'),
				array('action' => 'cmodify', 'name' => 'time', 'type' => 'int(10)', 'params' => 'DEFAULT NULL'),
				array('action' => 'cmodify', 'name' => 'id_quotes', 'type' => 'int(11)', 'params' => 'DEFAULT NULL'),
			)
		)
	);
	$ULIB = new urlLibrary();
	$ULIB->loadConfig();
	
	$UHANDLER = new urlHandler();
	$UHANDLER->loadConfig();
	
	$ULIB->registerCommand('quotes', '',
		array ('vars' =>
				array(	'sort' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'Сортировка по лучшим/худшим')),
						'page' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => 'Постраничная навигация')),
				),
				'descr'	=> array ('russian' => 'Главная страница цитатника'),
		)
	);
	
	$ULIB->registerCommand('quotes', 'show',
		array ('vars' =>
				array(	'id' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID цитаты')),
				),
				'descr'	=> array ('russian' => 'Ссылка на цитату'),
		)
	);
	
	$ULIB->registerCommand('quotes', 'add',
		array ('vars' =>
				array(),
				'descr'	=> array ('russian' => 'Добавить новость'),
		)
	);
	
	$ULIB->registerCommand('quotes', 'rss',
		array ('vars' =>
				array(),
				'descr'	=> array ('russian' => 'RSS цитатника'),
		)
	);
	
	$ULIB->saveConfig();
	
	switch ($action) {
		case 'confirm':generate_install_page('quotes', 'Cейчас плагин будет установлен');break;
		case 'autoapply':
		case 'apply':
			if (fixdb_plugin_install('quotes', $db_update, 'install', ($action=='autoapply')?true:false)) {
				plugin_mark_installed('quotes');
			} else {
				return false;
			}
			
			// Now we need to set some default params
			$params = array(
				'users_rating' => 0,
				'flood' => 10800,
				'count' => 5,
				'adm_count' => 10,
				'redirect_delay'	=> 5,
				'date'=>			'j.m.Y - H:i',
				'description' => 'Описание',
				'keywords' => 'Ключевые слова',
				'max_char' => 80,
				'rand' => 0,
				'cache' => 0,
				'cacheExpire' => 10800,
			);
			foreach ($params as $k => $v) {
				extra_set_param('quotes', $k, $v);
			}
			extra_commit_changes();
			break;
	}
	return true;
}

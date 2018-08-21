<?php

if(!defined('NGCMS'))
	exit('HAL');

plugins_load_config();
LoadPluginLang('events', 'config', '', '', '#');

include_once(dirname(__FILE__).'/cache.php');

switch ($_REQUEST['action']) {
	case 'list_announce': list_announce();	break;
	case 'edit_announce': edit_announce();		break;
	case 'list_cat': list_cat();								break;
//	case 'caching': caching();									break;
	case 'send_cat': send_cat();								break;
	case 'cat_name_del': cat_name_del(); list_cat();			break;
	case 'cat_edit': cat_edit(); 								break;
	case 'modify': modify(); list_announce();						break;
//	case 'about': about();										break;
	case 'url': url();											break;
	default: main();
}

/*
function caching()
{
global $tpl, $config, $mysql;
	$tpath = locatePluginTemplates(array('config/main', 'config/caching'), 'events', 1);
	
	if (isset($_REQUEST['submit']))
	{
		pluginSetVariable('events', 'cache', intval($_REQUEST['cache']));
		pluginSetVariable('events', 'cacheExpire', intval($_REQUEST['time']));
		pluginsSaveConfig();
		
		redirect_events('?mod=extra-config&plugin=events&action=caching');
	}
	
	if (isset($_REQUEST['clear_cache']))
	{
		unlink(dirname(__FILE__).'/cache/sql_index.php');
		
		redirect_events('?mod=extra-config&plugin=events&action=caching');
	}
	
	$cache = pluginGetVariable('events', 'cache');
	$cache = '<option value="0" '.($cache==0?'selected':'').'>Нет</option><option value="1" '.($cache==1?'selected':'').'>Да</option>';
	
	$pvars['vars']= array(
		'cache' => $cache,
		'time' => pluginGetVariable('events', 'cacheExpire'),
	);
	
	$count = $mysql->result('SELECT COUNT(id) FROM '.prefix.'_events WHERE active = \'0\' ');
	
	$tpl->template('caching', $tpath['config/caching'].'config');
	$tpl->vars('caching', $pvars);
	$tvars['vars']= array (
		'active' => !empty($count)?'[ '.$count.' ]':'',
		'entries' => $tpl->show('caching'),
		'global' => 'Настройка кэша'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}
*/

function cat_edit()
{
global $tpl, $mysql;
	$tpath = locatePluginTemplates(array('config/main', 'config/send_cat'), 'events', 1);
	
	$id = intval($_REQUEST['id']);
	
	$row = $mysql->record('SELECT * FROM '.prefix.'_events_cat WHERE id = '.db_squote($id).' LIMIT 1');
	
	if (isset($_REQUEST['submit']))
	{
		$parent_id = intval($_REQUEST['parent']);
		$cat_name = input_filter_com(convert($_REQUEST['cat_name']));
		if(empty($cat_name))
		{
			$error_text[] = '?Название категории не задано';
		}
		$description = input_filter_com(convert($_REQUEST['description']));
		if(empty($description))
		{
			$error_text[] = 'Описание категории не задано';
		}
		$keywords = input_filter_com(convert($_REQUEST['keywords']));
		if(empty($keywords))
		{
			$error_text[] = 'Ключевые слова не заданы';
		}
		//$position = intval($_REQUEST['position']);
		$position = 1;
		if(empty($position))
		{
			$error_text[] = 'Не задана позиция';
		}
		
		if(empty($error_text))
		{
			//	position = '.intval($position).'
			
			$mysql->query('UPDATE '.prefix.'_events_cat SET  
				cat_name = '.db_squote($cat_name).',
				description = '.db_squote($description).', 
				keywords = '.db_squote($keywords).',
				parent_id = '.db_squote($parent_id).',				
				position = 1
				WHERE id = '.$id.'
			');
				
			generate_catz_cache(true);
			
			redirect_events('?mod=extra-config&plugin=events&action=list_cat');
		}
	}
	
	if(!empty($error_text))
	{
		foreach($error_text as $error)
		{
			$error_input .= msg(array("type" => "error", "text" => $error), 0, 2);
		}
	} else {
		$error_input ='';
	}
	
	$res = mysql_query("SELECT * FROM ".prefix."_events_cat ORDER BY id");
	$cats = getCats($res);
	
	
	$pvars['vars'] = array (
		'cat_name' => $row['cat_name'],
		'keywords' => $row['keywords'],
		'description' => $row['description'],
		'parent_id' => $row['parent_id'],
		'position' => $row['position'],
		'error' => $error_input,
		'catz' => getTree($cats, $row['parent_id'], 0),
	);
	
	$count = $mysql->result('SELECT COUNT(id) FROM '.prefix.'_events WHERE active = \'0\' ');
	
	$tpl->template('send_cat', $tpath['config/send_cat'].'config');
	$tpl->vars('send_cat', $pvars);
	$tvars['vars']= array (
		'active' => !empty($count)?'[ '.$count.' ]':'',
		'entries' => $tpl->show('send_cat'),
		'global' => 'Редактировать категорию'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}

function getCats($res){

    $levels = array();
    $tree = array();
    $cur = array();

    while($rows = mysql_fetch_assoc($res)){

        $cur = &$levels[$rows['id']];
        $cur['parent_id'] = $rows['parent_id'];
        $cur['cat_name'] = $rows['cat_name'];

        if($rows['parent_id'] == 0){
            $tree[$rows['id']] = &$cur;
        }

        else{
            $levels[$rows['parent_id']]['children'][$rows['id']] = &$cur;
        }
    }
    return $tree;
}


function getTree($arr, $flg, $l){
	$flg;
    $out = '';
	$ft = '&#8212; ';
    foreach($arr as $k=>$v){

	if($k==$flg) { $out .= '<option value="'.$k.'" selected>'.str_repeat($ft, $l).$v['cat_name'].'</option>'; }
	else { $out .= '<option value="'.$k.'">'.str_repeat($ft, $l).$v['cat_name'].'</option>'; }
        if(!empty($v['children'])){ 	
			//$l = $l + 1;
            $out .= getTree($v['children'], $flg, $l + 1);
            //$l = $l - 1;
        }
    }
    return $out;
}

function send_cat($params)
{
global $tpl, $template, $config, $mysql, $lang;
	$tpath = locatePluginTemplates(array('config/main', 'config/send_cat'), 'events', 1);
	
	if (isset($_REQUEST['submit']))
	{
	
		$cat_name = input_filter_com(convert($_REQUEST['cat_name']));
		$parent_id = intval($_REQUEST['parent']);
		
		if(empty($cat_name))
		{
			$error_text[] = 'Название категории не задано';
		}
		$description = input_filter_com(convert($_REQUEST['description']));
		if(empty($description))
		{
			$error_text[] = 'Описание категории не задано';
		}
		$keywords = input_filter_com(convert($_REQUEST['keywords']));
		if(empty($keywords))
		{
			$error_text[] = 'Ключевые слова не заданы';
		}
		//$position = intval($_REQUEST['position']);
		$position = 1;
		if(empty($position))
		{
			$error_text[] = 'Не задана позиция';
		}
		
		if(empty($error_text))
		{
			$mysql->query('INSERT INTO '.prefix.'_events_cat (cat_name, description, keywords, parent_id, position) 
				VALUES 
				('.db_squote($cat_name).',
					'.db_squote($description).',
					'.db_squote($keywords).',
					'.db_squote($parent_id).',
					'.intval($position).'
				)
			');
			
			generate_catz_cache(true);
			
			redirect_events('?mod=extra-config&plugin=events&action=list_cat');
		}

	}
	
		if(!empty($error_text))
		{
			foreach($error_text as $error)
			{
				$error_input .= msg(array("type" => "error", "text" => $error));
			}
		} else {
			$error_input ='';
		}
		
$res = mysql_query("SELECT * FROM ".prefix."_events_cat ORDER BY id");
$cats = getCats($res);

	$pvars['vars'] = array (
		'cat_name' => $cat_name,
		'keywords' => $keywords,
		'description' => $description,
		'position' => $position,
		'parent' => $parent,
		'error' => $error_input,
		'catz' => getTree($cats),
	);
	
	$count = $mysql->result('SELECT COUNT(id) FROM '.prefix.'_events WHERE active = \'0\' ');
	
	$tpl->template('send_cat', $tpath['config/send_cat'].'config');
	$tpl->vars('send_cat', $pvars);
	$tvars['vars']= array (
		'active' => !empty($count)?'[ '.$count.' ]':'',
		'entries' => $tpl->show('send_cat'),
		'global' => 'Добавить категорию'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}

function list_cat()
{
global $tpl, $mysql;
	$tpath = locatePluginTemplates(array('config/main', 'config/list_cat', 'config/list_cat_entries'), 'events', 1);
	
	foreach ($mysql->select('SELECT cat_id, COUNT(id) as num FROM '.prefix.'_events GROUP BY cat_id') as $rows)
	{
		$cat[$rows['cat_id']] .= $rows['num'];
	}
	

	foreach ($mysql->select('SELECT * from '.prefix.'_events_cat ORDER BY position ASC') as $row)
	{
		$gvars['vars'] = array (
			'num' => $cat[$row['id']],
			'id' => $row['id'],
			'cat_name' => '<a href="?mod=extra-config&plugin=events&action=cat_edit&id='.$row['id'].'"  />'.$row['cat_name'].'</a>',
			'cat_name_del' => '<a href="?mod=extra-config&plugin=events&action=cat_name_del&id='.$row['id'].'"  /><img title="???????" alt="???????" src="/engine/skins/default/images/delete.gif"></a>',
		);
		
		$tpl->template('list_cat_entries', $tpath['config/list_cat_entries'].'config');
		$tpl->vars('list_cat_entries', $gvars);
		$entries .= $tpl -> show('list_cat_entries');
	}
	
	$count = $mysql->result('SELECT COUNT(id) FROM '.prefix.'_events WHERE active = \'0\' ');
	

	
	
	$pvars['vars']['entries'] = isset($entries)?$entries:'';
	$tpl->template('list_cat', $tpath['config/list_cat'].'config');
	$tpl->vars('list_cat', $pvars);
	$tvars['vars']= array (
		'active' => !empty($count)?'[ '.$count.' ]':'',
		'entries' => $tpl->show('list_cat'),
		'global' => 'Список категорий'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}

function url()
{global $tpl, $mysql;
	$tpath = locatePluginTemplates(array('config/main', 'config/url'), 'events', 1);
	
	if (isset($_REQUEST['submit']))
	{
		if(isset($_REQUEST['url']) && !empty($_REQUEST['url']))
		{
 			$ULIB = new urlLibrary();
			$ULIB->loadConfig();
			
			$ULIB->registerCommand('events', '',
				array ('vars' =>
						array( 	'cat' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'Категории')),
								'page' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => 'Постраничная навигация'))
						),
						'descr'	=> array ('russian' => 'Главная страница'),
				)
			);
			
			$ULIB->registerCommand('events', 'show',
				array ('vars' =>
						array(	'id' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID объявления')),
						),
						'descr'	=> array ('russian' => 'Ссылка на объявление'),
				)
			);
			
			$ULIB->registerCommand('events', 'send',
				array ('vars' =>
						array(),
						'descr'	=> array ('russian' => 'Добавить объявлдение'),
				)
			);
			
			$ULIB->registerCommand('events', 'search',
				array ('vars' =>
						array(),
						'descr'	=> array ('russian' => 'Поиск по объявлениям'),
				)
			);
			
			$ULIB->registerCommand('events', 'list',
				array ('vars' =>
						array( 'page' => array('matchRegex' => '\d{1,4}', 'descr' => array('russian' => 'Постраничная навигация'))
						),
						'descr'	=> array ('russian' => 'Список объявлений добавленных пользователем'),
				)
			);
			
			$ULIB->registerCommand('events', 'edit',
				array ('vars' =>
						array(	'id' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID объявления')),
						),
						'descr'	=> array ('russian' => 'Ссылка для редактирования'),
				)
			);
			
			$ULIB->registerCommand('events', 'del',
				array ('vars' =>
						array(	'id' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID объявления')),
						),
						'descr'	=> array ('russian' => 'Ссылка для удаления'),
				)
			);
			
			$ULIB->registerCommand('events', 'expend',
				array ('vars' =>
						array(	'id' => array('matchRegex' => '\d+', 'descr' => array('russian' => 'ID объявления')),
								'hashcode' => array('matchRegex' => '.+?', 'descr' => array('russian' => 'Hashcode объявления')),
						),
						'descr'	=> array ('russian' => 'Ссылка для продления'),
				)
			);
			
			$ULIB->saveConfig();
			
			$UHANDLER = new urlHandler();
			$UHANDLER->loadConfig();
			
			$UHANDLER->registerHandler(0,
				array (
				'pluginName' => 'events',
				'handlerName' => '',
				'flagPrimary' => true,
				'flagFailContinue' => false,
				'flagDisabled' => false,
				'rstyle' => 
				array (
				  'rcmd' => '/events/[cat/{cat}/][page/{page}/]',
				  'regex' => '#^/events/(?:cat/(\\d+)/){0,1}(?:page/(\\d{1,4})/){0,1}$#',
				  'regexMap' => 
				  array (
					1 => 'cat',
					2 => 'page',
				  ),
				  'reqCheck' => 
				  array (
				  ),
				  'setVars' => 
				  array (
				  ),
				  'genrMAP' => 
				  array (
					0 => 
					array (
					  0 => 0,
					  1 => '/events/',
					  2 => 0,
					),
					1 => 
					array (
					  0 => 0,
					  1 => 'cat/',
					  2 => 1,
					),
					2 => 
					array (
					  0 => 1,
					  1 => 'cat',
					  2 => 1,
					),
					3 => 
					array (
					  0 => 0,
					  1 => '/',
					  2 => 1,
					),
					4 => 
					array (
					  0 => 0,
					  1 => 'page/',
					  2 => 3,
					),
					5 => 
					array (
					  0 => 1,
					  1 => 'page',
					  2 => 3,
					),
					6 => 
					array (
					  0 => 0,
					  1 => '/',
					  2 => 3,
					),
				  ),
				),
			  )
			);
			
			$UHANDLER->registerHandler(0,
				array (
				'pluginName' => 'events',
				'handlerName' => 'show',
				'flagPrimary' => true,
				'flagFailContinue' => false,
				'flagDisabled' => false,
				'rstyle' => 
				array (
				  'rcmd' => '/events/{id}/',
				  'regex' => '#^/events/(\\d+)/$#',
				  'regexMap' => 
				  array (
					1 => 'id',
				  ),
				  'reqCheck' => 
				  array (
				  ),
				  'setVars' => 
				  array (
				  ),
				  'genrMAP' => 
				  array (
					0 => 
					array (
					  0 => 0,
					  1 => '/events/',
					  2 => 0,
					),
					1 => 
					array (
					  0 => 1,
					  1 => 'id',
					  2 => 0,
					),
					2 => 
					array (
					  0 => 0,
					  1 => '/',
					  2 => 0,
					),
				  ),
				),
			  )
			);
			
			$UHANDLER->registerHandler(0,
				array (
				'pluginName' => 'events',
				'handlerName' => 'send',
				'flagPrimary' => true,
				'flagFailContinue' => false,
				'flagDisabled' => false,
				'rstyle' => 
				array (
				  'rcmd' => '/events/send/',
				  'regex' => '#^/events/send/$#',
				  'regexMap' => 
				  array (
				  ),
				  'reqCheck' => 
				  array (
				  ),
				  'setVars' => 
				  array (
				  ),
				  'genrMAP' => 
				  array (
					0 => 
					array (
					  0 => 0,
					  1 => '/events/send/',
					  2 => 0,
					),
				  ),
				),
			  )
			);
			
			$UHANDLER->registerHandler(0,
				array (
				'pluginName' => 'events',
				'handlerName' => 'search',
				'flagPrimary' => true,
				'flagFailContinue' => false,
				'flagDisabled' => false,
				'rstyle' => 
				array (
				  'rcmd' => '/events/search/',
				  'regex' => '#^/events/search/$#',
				  'regexMap' => 
				  array (
				  ),
				  'reqCheck' => 
				  array (
				  ),
				  'setVars' => 
				  array (
				  ),
				  'genrMAP' => 
				  array (
					0 => 
					array (
					  0 => 0,
					  1 => '/events/search/',
					  2 => 0,
					),
				  ),
				),
			  )
			);
			
			$UHANDLER->registerHandler(0,
				array (
				'pluginName' => 'events',
				'handlerName' => 'list',
				'flagPrimary' => true,
				'flagFailContinue' => false,
				'flagDisabled' => false,
				'rstyle' => 
				array (
				  'rcmd' => '/events/list/[page/{page}/]',
				  'regex' => '#^/events/list/(?:page/(\\d{1,4})/){0,1}$#',
				  'regexMap' => 
				  array (
					1 => 'page',
				  ),
				  'reqCheck' => 
				  array (
				  ),
				  'setVars' => 
				  array (
				  ),
				  'genrMAP' => 
				  array (
					0 => 
					array (
					  0 => 0,
					  1 => '/events/list/',
					  2 => 0,
					),
					1 => 
					array (
					  0 => 0,
					  1 => 'page/',
					  2 => 1,
					),
					2 => 
					array (
					  0 => 1,
					  1 => 'page',
					  2 => 1,
					),
					3 => 
					array (
					  0 => 0,
					  1 => '/',
					  2 => 1,
					),
				  ),
				),
			  )
			);
			
			$UHANDLER->registerHandler(0,
				array (
				'pluginName' => 'events',
				'handlerName' => 'edit',
				'flagPrimary' => true,
				'flagFailContinue' => false,
				'flagDisabled' => false,
				'rstyle' => 
				array (
				  'rcmd' => '/events/edit/{id}/',
				  'regex' => '#^/events/edit/(\\d+)/$#',
				  'regexMap' => 
				  array (
					1 => 'id',
				  ),
				  'reqCheck' => 
				  array (
				  ),
				  'setVars' => 
				  array (
				  ),
				  'genrMAP' => 
				  array (
					0 => 
					array (
					  0 => 0,
					  1 => '/events/edit/',
					  2 => 0,
					),
					1 => 
					array (
					  0 => 1,
					  1 => 'id',
					  2 => 0,
					),
					2 => 
					array (
					  0 => 0,
					  1 => '/',
					  2 => 0,
					),
				  ),
				),
			  )
			);
			
			$UHANDLER->registerHandler(0,
				array (
				'pluginName' => 'events',
				'handlerName' => 'del',
				'flagPrimary' => true,
				'flagFailContinue' => false,
				'flagDisabled' => false,
				'rstyle' => 
				array (
				  'rcmd' => '/events/del/{id}/',
				  'regex' => '#^/events/del/(\\d+)/$#',
				  'regexMap' => 
				  array (
					1 => 'id',
				  ),
				  'reqCheck' => 
				  array (
				  ),
				  'setVars' => 
				  array (
				  ),
				  'genrMAP' => 
				  array (
					0 => 
					array (
					  0 => 0,
					  1 => '/events/del/',
					  2 => 0,
					),
					1 => 
					array (
					  0 => 1,
					  1 => 'id',
					  2 => 0,
					),
					2 => 
					array (
					  0 => 0,
					  1 => '/',
					  2 => 0,
					),
				  ),
				),
			  )
			);
			
			
			$UHANDLER->registerHandler(0,
				array (
				'pluginName' => 'events',
				'handlerName' => 'expend',
				'flagPrimary' => true,
				'flagFailContinue' => false,
				'flagDisabled' => false,
				'rstyle' => 
				array (
				  'rcmd' => '/events/expend/[id/{id}/][hashcode/{hashcode}/]',
				  'regex' => '#^/events/expend/(?:id/(\\d+)/){0,1}(?:hashcode/(.+?)/){0,1}$#',
				  'regexMap' => 
				  array (
					1 => 'id',
					2 => 'hashcode',
				  ),
				  'reqCheck' => 
				  array (
				  ),
				  'setVars' => 
				  array (
				  ),
				  'genrMAP' => 
				  array (
					0 => 
					array (
					  0 => 0,
					  1 => '/events/',
					  2 => 0,
					),
					1 => 
					array (
					  0 => 0,
					  1 => 'id/',
					  2 => 1,
					),
					2 => 
					array (
					  0 => 1,
					  1 => 'id',
					  2 => 1,
					),
					3 => 
					array (
					  0 => 0,
					  1 => '/',
					  2 => 1,
					),
					4 => 
					array (
					  0 => 0,
					  1 => 'hashcode/',
					  2 => 3,
					),
					5 => 
					array (
					  0 => 1,
					  1 => 'hashcode',
					  2 => 3,
					),
					6 => 
					array (
					  0 => 0,
					  1 => '/',
					  2 => 3,
					),
				  ),
				),
			  )
			);
			
			$UHANDLER->saveConfig();
		} else {
			$ULIB = new urlLibrary();
			$ULIB->loadConfig();
			$ULIB->removeCommand('events', '');
			$ULIB->removeCommand('events', 'show');
			$ULIB->removeCommand('events', 'send');
			$ULIB->removeCommand('events', 'search');
			$ULIB->removeCommand('events', 'list');
			$ULIB->removeCommand('events', 'edit');
			$ULIB->removeCommand('events', 'del');
			$ULIB->removeCommand('events', 'expend');
			$ULIB->saveConfig();
			$UHANDLER = new urlHandler();
			$UHANDLER->loadConfig();
			$UHANDLER->removePluginHandlers('events', '');
			$UHANDLER->removePluginHandlers('events', 'show');
			$UHANDLER->removePluginHandlers('events', 'send');
			$UHANDLER->removePluginHandlers('events', 'search');
			$UHANDLER->removePluginHandlers('events', 'list');
			$UHANDLER->removePluginHandlers('events', 'edit');
			$UHANDLER->removePluginHandlers('events', 'del');
			$UHANDLER->removePluginHandlers('events', 'expend');
			$UHANDLER->saveConfig();
		}
		
		pluginSetVariable('events', 'url', intval($_REQUEST['url']));
		pluginsSaveConfig();
		
		redirect_events('?mod=extra-config&plugin=events&action=url');
	}
	$url = pluginGetVariable('events', 'url');
	$url = '<option value="0" '.(empty($url)?'selected':'').'>Нет</option><option value="1" '.(!empty($url)?'selected':'').'>Да</option>';
	$pvars['vars']['info'] = $url;
	
	$count = $mysql->result('SELECT COUNT(id) FROM '.prefix.'_events WHERE active = \'0\' ');
	
	$tpl->template('url', $tpath['config/url'].'config');
	$tpl->vars('url', $pvars);
	$tvars['vars']= array (
		'active' => !empty($count)?'[ '.$count.' ]':'',
		'entries' => $tpl->show('url'),
		'global' => 'Настройка ЧПУ'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}

function list_announce()
{
global $tpl, $mysql, $lang;
	$tpath = locatePluginTemplates(array('config/main', 'config/list_announce', 'config/list_entries'), 'events', 1);
	
	$news_per_page = pluginGetVariable('events', 'admin_count');
	
	if (($news_per_page < 2)||($news_per_page > 2000)) $news_per_page = 2;
	
	$pageNo		= intval($_REQUEST['page'])?$_REQUEST['page']:0;
	if ($pageNo < 1)	$pageNo = 1;
	if (!$start_from)	$start_from = ($pageNo - 1)* $news_per_page;
	
	$count = $mysql->result('SELECT count(id) from '.prefix.'_events');
	$countPages = ceil($count / $news_per_page);
	
	foreach ($mysql->select('SELECT * from '.prefix.'_events ORDER BY date DESC LIMIT '.$start_from.', '.$news_per_page) as $row)
	{
		switch ($row['active'])
		{
			case 1: $active = 'Да'; break;
			case 0: $active = 'Нет'; break;
			default: $active = 'Ошибка';
		}
		
		switch ($row['expired'])
		{
			case 1: $expired = 'Да'; break;
			case 0: $expired = 'Нет'; break;
			default: $expired = 'Ошибка';
		}
		
		foreach ($mysql->select('SELECT id, cat_name FROM '.prefix.'_events_cat where id='.$row['cat_id'].'') as $cat)
		{
			$options = $cat['cat_name'];
		}
		
		foreach ($mysql->select('SELECT id, city FROM '.prefix.'_events_cities where id='.$row['city'].'') as $city)
		{
			$cities = $city['city'];
		}
		
		foreach ($mysql->select('SELECT id, name FROM '.prefix.'_users where id='.$row['author_id'].'') as $author_id)
		{
			$uid = $author_id['name'];
		}
		
		$gvars['vars'] = array (
			'id' => $row['id'],
			'announce_name' => '<a href="?mod=extra-config&plugin=events&action=edit_announce&id='.$row['id'].'"  />'.$row['announce_name'].'</a>',
			'announce_period' => $row['announce_period'],
			'announce_description' => $row['announce_description'],
			'announce_contacts' => $row['announce_contacts'],
			'date' => (empty($row['date']))?'Дата не указана':date(pluginGetVariable('events', 'date'), $row['date']),
			'editdate' => (empty($row['editdate']))?'Дата не указана':date(pluginGetVariable('events', 'date'), $row['editdate']),
			'city'	=> $cities,
			'category' => $options,
			'archive' =>  $expired,
			'active' => $active,
			'author' => $uid,
		);
		
		$tpl->template('list_entries', $tpath['config/list_entries'].'config');
		$tpl->vars('list_entries', $gvars);
		$entries .= $tpl -> show('list_entries');
	}
	
	$count = $mysql->result('SELECT COUNT(id) FROM '.prefix.'_events WHERE active = \'0\' ');
	
	$pvars['vars']['pagesss'] = generateAdminPagelist( array('current' => $pageNo, 'count' => $countPages, 'url' => admin_url.'/admin.php?mod=extra-config&plugin=events&action=list_announce'.($_REQUEST['news_per_page']?'&news_per_page='.$news_per_page:'').($_REQUEST['author']?'&author='.$_REQUEST['author']:'').($_REQUEST['sort']?'&sort='.$_REQUEST['sort']:'').($postdate?'&postdate='.$postdate:'').($author?'&author='.$author:'').($status?'&status='.$status:'').'&page=%page%'));
	$pvars['vars']['entries'] = $entries;
	$tpl->template('list_announce', $tpath['config/list_announce'].'config');
	$tpl->vars('list_announce', $pvars);
	$tvars['vars']= array (
		'active' => !empty($count)?'[ '.$count.' ]':'',
		'entries' => $tpl->show('list_announce'),
		'global' => 'Список объявлений'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}

function edit_announce()
{
global $tpl, $lang, $mysql, $config;
	$tpath = locatePluginTemplates(array('config/main', 'config/edit_announce', 'config/list_images'), 'events', 1);
	
	$id = intval($_REQUEST['id']);
	if (!empty($id))
	{
		
		$row = $mysql->record('SELECT * FROM '.prefix.'_events WHERE id = '.db_squote($id).' LIMIT 1');

		$res = $mysql->select("SELECT * FROM ".prefix."_events_cities ORDER BY city");	
		foreach($res as $v){
			$cities .= '<option value="'.$v['id'].'">'.$v['city'].'</option>';
		}
		
		foreach ($mysql->select('SELECT id, name FROM '.prefix.'_users where id='.$row['author_id'].'') as $author_id)
		{
			$uid = $author_id['name'];
		}
		/*
		$options = '<option disabled>---------</option>';
		foreach ($mysql->select('SELECT id, cat_name FROM '.prefix.'_events_cat') as $cat)
		{
			$options .= '<option value="' . $cat['id'] . '"'.(($row['cat_id']==$cat['id'])?'selected':'').'>' . $cat['cat_name'] . '</option>';
		}
		*/
			$res = mysql_query("SELECT * FROM ".prefix."_events_cat ORDER BY id");
			$cats = getCats($res);
			$options = getTree($cats, $row['cat_id'], 0);
	
	
	
		if (isset($_REQUEST['submit']))
		{
			$SQL['editdate'] = time() + ($config['date_adjust'] * 60);
			
			$SQL['announce_name'] = input_filter_com(convert($_REQUEST['announce_name']));
			if(empty($SQL['announce_name']))
				$error_text[] = 'Название объявления пустое';

			
			$SQL['announce_place'] = input_filter_com(convert($_REQUEST['announce_place']));
			if(empty($SQL['announce_place']))
				$error_text[] = 'Поле место сбора не заполнено';
			
			
			$SQL['cat_id'] = intval($_REQUEST['cat_id']);
			if(!empty($SQL['cat_id']))
			{
				$cat = $mysql->result('SELECT 1 FROM '.prefix.'_events_cat WHERE id = \'' . $SQL['cat_id'] . '\' LIMIT 1');
				
				if(empty($cat))
				{
					$error_text[] = 'Такой категории не существует';
				}
			} else {
				$error_text[] = 'Вы не выбрали категорию';
			}
			
			$SQL['city'] = intval($_REQUEST['city_id']);
			if(!empty($SQL['city']))
			{
				$city = $mysql->result('SELECT 1 FROM '.prefix.'_events_cities WHERE id = \'' . $SQL['city'] . '\' LIMIT 1');
				
				if(empty($city))
				{
					$error_text[] = 'Такого города не существует';
				}
			} else {
				$error_text[] = 'Вы не выбрали город';
			}
			
			
			$SQL['announce_description'] = str_replace(array("\r\n", "\r"), "\n",input_filter_com(convert($_REQUEST['announce_description'])));
			if(empty($SQL['announce_description']))
			{
				$error_text[] = 'Нет описания к объявлению';
			}
			
			$SQL['active'] = $_REQUEST['announce_activeme'];
			$SQL['expired'] = $_REQUEST['announce_arhiveme'];
			
			if(is_array($SQLi)){
				$vnamess = array();
				foreach ($SQLi as $k => $v) { $vnamess[] = $k.' = '.db_squote($v); }
				$mysql->query('update '.prefix.'_events set '.implode(', ',$vnamess).' where  id = \''.intval($id).'\'');
			}
			
			if(empty($error_text))
			{
				$vnames = array();
				foreach ($SQL as $k => $v) { $vnames[] = $k.' = '.db_squote($v); }
				$mysql->query('update '.prefix.'_events set '.implode(', ',$vnames).' where  id = \''.intval($id).'\'');
				
				//generate_entries_cnt_cache(true);
				//generate_catz_cache(true);
				
				redirect_events('?mod=extra-config&plugin=events&action=list_announce');
			}
			
		}
		
		if(!empty($error_text))
		{
			foreach($error_text as $error)
			{
				$error_input .= msg(array("type" => "error", "text" => $error));
			}
		} else {
			$error_input ='';
		}
		
		if($row['active'] == 1) { $checked = 'checked'; } else  { $checked = ''; }
		if($row['expired'] == 1) { $expired = 'checked'; } else  { $expired = ''; }

		$pvars['vars'] = array (
			'date' => (empty($row['date']))?'Дата не указана':date(pluginGetVariable('events', 'date'), $row['date']),
			'editdate' => (empty($row['editdate']))?'Дата не указана':date(pluginGetVariable('events', 'date'), $row['editdate']),
			'cities' => $cities,
			'options' => $options,
			'announce_arhiveme' => $expired,
			'announce_activeme' => $checked,
			'announce_place' => $row['announce_place'],
			'announce_name' => $row['announce_name'],
			'author' => $row['author'],
			'announce_description' => $row['announce_description'],
			'tpl_url' => home.'/events/'.$config['theme'],
			'tpl_home' => admin_url,
			'id' => $id,
			'error' => $error_input,
		);
	} else {
		msg(array("type" => "error", "text" => "Вы выбрали неверное id"));
	}

	
	if (isset($_REQUEST['delme']))
		{

		$mysql->query('delete from '.prefix.'_events where id = '.db_squote($id));
		
		//generate_entries_cnt_cache(true);
		
		redirect_events('?mod=extra-config&plugin=events&action=list_announce');
		}
	
	$count = $mysql->result('SELECT COUNT(id) FROM '.prefix.'_events WHERE active = \'0\' ');
	
	$tpl->template('edit_announce', $tpath['config/edit_announce'].'config');
	$tpl->vars('edit_announce', $pvars);
	$tvars['vars']= array (
		'active' => !empty($count)?'[ '.$count.' ]':'',
		'entries' => $tpl->show('edit_announce'),
		'global' => 'Редактирование: '.$row['announce_name']
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}

/*
function about()
{
global $tpl, $mysql;
	$tpath = locatePluginTemplates(array('config/main', 'config/about'), 'events', 1);
	
	$count = $mysql->result('SELECT COUNT(id) FROM '.prefix.'_events WHERE active = \'0\' ');
	
	$tpl->template('about', $tpath['config/about'].'config');
	$tpl->vars('about', $pvars);
	$tvars['vars']= array (
		'active' => !empty($count)?'[ '.$count.' ]':'',
		'entries' => $tpl->show('about'),
		'global' => 'О плагине'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}
*/

function cat_name_del()
{global $mysql;
	
	$id = intval($_REQUEST['id']);
	
	if( empty($id) )
	{
		return msg(array("type" => "error", "text" => "Ошибка, вы не выбрали что хотите удалить"));
	}
	
	$mysql->query("delete from ".prefix."_events_cat where id = {$id}");
	
	generate_catz_cache(true);
	
	msg(array("type" => "info", "info" => "Категория удалена"));
	
}

function modify()
{
global $mysql;
	
	$selected_news = $_REQUEST['selected_files'];
	$subaction	=	$_REQUEST['subaction'];
	
	if( empty($selected_news) )
	{
		return msg(array("type" => "error", "text" => "Ошибка, вы не выбрали объявление"));
	}
	
	switch($subaction) {
		case 'mass_approve'      : $active = 'active = 1'; break;
		case 'mass_forbidden'    : $active = 'active = 0'; break;
		case 'mass_delete'       : $del = true; break;
	}
	
	foreach ($selected_news as $id)
	{
		if(isset($active))
		{
			$mysql->query('update '.prefix.'_events 
					set '.$active.'
					WHERE id = '.db_squote($id).'
					');
			$result = 'Объявления Активированы/Деактивированы';
		}
		if(isset($del))
		{
			$mysql->query('delete from '.prefix.'_events where id = '.db_squote($id));
			$result = 'Объявления удалены';
		}
	}
	//generate_entries_cnt_cache(true);
	//generate_catz_cache(true);
	msg(array("type" => "info", "info" => $result));
}

function main()
{
global $tpl, $mysql, $cron;
	
	$tpath = locatePluginTemplates(array('config/main', 'config/general.from'), 'events', 1);
	
	if (isset($_REQUEST['submit']))
	{
		pluginSetVariable('events', 'list_period', secure_html(trim($_REQUEST['list_period'])));
		pluginSetVariable('events', 'count', intval($_REQUEST['count']));
		pluginSetVariable('events', 'count_filter', intval($_REQUEST['count_filter']));
		pluginSetVariable('events', 'count_filter_archive', intval($_REQUEST['count_filter_archive']));
		pluginSetVariable('events', 'main_template', trim($_REQUEST['main_template']));
		pluginSetVariable('events', 'max_image_size', intval($_REQUEST['max_image_size']));
		pluginSetVariable('events', 'width_thumb', intval($_REQUEST['width_thumb']));
		pluginSetVariable('events', 'width', intval($_REQUEST['width']));
		pluginSetVariable('events', 'height', intval($_REQUEST['height']));
		pluginSetVariable('events', 'ext_image', secure_html(trim($_REQUEST['ext_image'])));
		pluginSetVariable('events', 'admin_count', intval($_REQUEST['admin_count']));
		pluginSetVariable('events', 'date',  secure_html($_REQUEST['date']));
		pluginSetVariable('events', 'notice_mail', intval($_REQUEST['notice_mail']));
		pluginSetVariable('events', 'send_guest', intval($_REQUEST['send_guest']));
		pluginSetVariable('events', 'template_mail',  secure_html($_REQUEST['template_mail']));
		pluginSetVariable('events', 'description',  secure_html($_REQUEST['description']));
		pluginSetVariable('events', 'keywords',  secure_html($_REQUEST['keywords']));
		pluginSetVariable('events', 'cat_id',  secure_html($_REQUEST['cat_id']));
		pluginSetVariable('events', 'count_list',  secure_html($_REQUEST['count_list']));
		pluginSetVariable('events', 'count_search',  secure_html($_REQUEST['count_search']));
		pluginSetVariable('events', 'info_send', $_REQUEST['info_send']);
		pluginSetVariable('events', 'info_edit', $_REQUEST['info_edit']);
		pluginSetVariable('events', 'use_recaptcha', $_REQUEST['use_recaptcha']);
		pluginSetVariable('events', 'views_count', $_REQUEST['views_count']);
		pluginSetVariable('events', 'use_expired', $_REQUEST['use_expired']);
		pluginSetVariable('events', 'public_key', $_REQUEST['public_key']);
		pluginSetVariable('events', 'private_key', $_REQUEST['private_key']);
		pluginsSaveConfig();
		
		redirect_events('?mod=extra-config&plugin=events');
	}
	
		$views_cnt = intval(pluginGetVariable('events', 'views_count'));
		$expired = intval(pluginGetVariable('events', 'use_expired'));
		
		if( $views_cnt == 2 ) {
		
		$cron_row = $cron->getConfig();
		foreach($cron_row as $key=>$value) { 
		if( ($value['plugin']=='events') && ($value['handler']=='events_views') ) {  $cron_min = $value['min']; $cron_hour = $value['hour']; $cron_day = $value['day']; $cron_month = $value['month']; }
		}
		if(!isset($cron_min)) { $cron_min = '0,15,30,45'; }
		if(!isset($cron_hour)) { $cron_hour = '*'; } 
		if(!isset($cron_day)) { $cron_day = '*'; } 
		if(!isset($cron_month)) { $cron_month = '*'; } 

		$cron->unregisterTask('events', 'events_views');
		$cron->registerTask('events', 'events_views', $cron_min, $cron_hour, $cron_day, $cron_month, '*');
		}
		else{
			$cron->unregisterTask('events', 'events_views');
		}
		
		
		if( $expired == 1 ) {
		
		$cron_row_1 = $cron->getConfig();
		foreach($cron_row_1 as $key_1=>$value_1) { 
		if( ($value_1['plugin']=='events') && ($value_1['handler']=='events_expired') ) {  $cron_min = $value_1['min']; $cron_hour = $value_1['hour']; $cron_day = $value_1['day']; $cron_month = $value_1['month']; }
		}
		if(!isset($cron_min)) { $cron_min = '0,15,30,45'; }
		if(!isset($cron_hour)) { $cron_hour = '*'; } 
		if(!isset($cron_day)) { $cron_day = '*'; } 
		if(!isset($cron_month)) { $cron_month = '*'; } 

		$cron->unregisterTask('events', 'events_expired');
		$cron->registerTask('events', 'events_expired', $cron_min, $cron_hour, $cron_day, $cron_month, '*');
		}
		else{
			$cron->unregisterTask('events', 'events_expired');
		}
	
	$cat_id = pluginGetVariable('events', 'cat_id');
	$options = '<option disabled>---------</option>';
	foreach ($mysql->select('SELECT id, cat_name FROM '.prefix.'_events_cat') as $row)
	{
		$options .= '<option value="' . $row['id'] . '"'.(($cat_id==$row['id'])?'selected':'').'>' . $row['cat_name'] . '</option>';
	}
	$list_period = pluginGetVariable('events', 'list_period');
	$count = pluginGetVariable('events', 'count');
	$count_filter = pluginGetVariable('events', 'count_filter');
	$count_filter_archive = pluginGetVariable('events', 'count_filter_archive');
	$max_image_size = pluginGetVariable('events', 'max_image_size');
	$width_thumb = pluginGetVariable('events', 'width_thumb');
	$width = pluginGetVariable('events', 'width');
	$height = pluginGetVariable('events', 'height');
	$ext_image = pluginGetVariable('events', 'ext_image');
	$admin_count = pluginGetVariable('events', 'admin_count');
	$date = pluginGetVariable('events', 'date');
	$notice_mail = pluginGetVariable('events', 'notice_mail');
	$notice_mail = '<option value="0" '.($notice_mail==0?'selected':'').'>Нет</option><option value="1" '.($notice_mail==1?'selected':'').'>Да</option>';
	$send_guest = pluginGetVariable('events', 'send_guest');
	$send_guest = '<option value="0" '.($send_guest==0?'selected':'').'>Нет</option><option value="1" '.($send_guest==1?'selected':'').'>Да</option>';
	$template_mail = pluginGetVariable('events', 'template_mail');
	$description = pluginGetVariable('events', 'description');
	$keywords = pluginGetVariable('events', 'keywords');
	$count_list = pluginGetVariable('events', 'count_list');
	$count_search = pluginGetVariable('events', 'count_search');
	$info_send = pluginGetVariable('events', 'info_send');
	$info_edit = pluginGetVariable('events', 'info_edit');
	$use_recaptcha = pluginGetVariable('events', 'use_recaptcha');
	$use_recaptcha = '<option value="0" '.($use_recaptcha==0?'selected':'').'>Нет</option><option value="1" '.($use_recaptcha==1?'selected':'').'>Да</option>';
	$views_count = pluginGetVariable('events', 'views_count');
	$views_count = '<option value="0" '.($views_count==0?'selected':'').'>Нет</option><option value="1" '.($views_count==1?'selected':'').'>Да</option><option value="2" '.($views_count==2?'selected':'').'>Отложенное</option>';
	$use_expired = pluginGetVariable('events', 'use_expired');
	$use_expired = '<option value="0" '.($use_expired==0?'selected':'').'>Нет</option><option value="1" '.($use_expired==1?'selected':'').'>Да</option>';
	$public_key = pluginGetVariable('events', 'public_key');
	$private_key = pluginGetVariable('events', 'private_key');
	

	/*
	if(empty($max_image_size))
		msg(array("type" => "error", "text" => "Критическая ошибка <br /> Размер для изображений не указан"), 1);
	if(empty($width))
		msg(array("type" => "error", "text" => "Критическая ошибка <br /> Ширина изображения не указана"), 1);
	if(empty($height))
		msg(array("type" => "error", "text" => "Критическая ошибка <br /> Высота изображения не указана"), 1);
	if(empty($ext_image))
		msg(array("type" => "error", "text" => "Критическая ошибка <br /> Расширения для изображений не указано"), 1);
*/
	
	$pvars['vars'] = array (
		'cat_id' => $options,
		'list_period' => $list_period,
		'count' => $count,
		'count_filter' => $count_filter,
		'count_filter_archive' => $count_filter_archive,
		'main_template' => pluginGetVariable('events', 'main_template'),
		'max_image_size' => $max_image_size,
		'width_thumb' => $width_thumb,
		'width' => $width,
		'height' => $height,
		'ext_image' => $ext_image,
		'admin_count' => $admin_count,
		'date' => $date,
		'notice_mail' => $notice_mail,
		'send_guest' => $send_guest,
		'template_mail' => $template_mail,
		'description' => $description,
		'keywords' => $keywords,
		'count_list' => $count_list,
		'count_search' => $count_search,
		'info_send' => $info_send,
		'info_edit' => $info_edit,
		'use_recaptcha' => $use_recaptcha,
		'views_count' => $views_count,
		'use_expired' => $use_expired,
		'public_key' => $public_key,
		'private_key' => $private_key,
	);
	
	$count = $mysql->result('SELECT COUNT(id) FROM '.prefix.'_events WHERE active = \'0\' ');
	
	$tpl->template('general.from', $tpath['config/general.from'].'config');
	$tpl->vars('general.from', $pvars);
	$tvars['vars']= array (
		'active' => !empty($count)?'[ '.$count.' ]':'',
		'entries' => $tpl->show('general.from'),
		'global' => 'Общие'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}

function redirect_events($url)
{
	if (headers_sent()) {
		echo "<script>document.location.href='{$url}';</script>\n";
		exit;
	} else {
		header('HTTP/1.1 302 Moved Permanently');
		header("Location: {$url}");
		exit;
	}
}

function input_filter_com($text)
{
	$text = trim($text);
	$search = array("<", ">");
	$replace = array("&lt;", "&gt;");
	$text = preg_replace("/(&amp;)+(?=\#([0-9]{2,3});)/i", "&", str_replace($search, $replace, $text));
	return $text;
}
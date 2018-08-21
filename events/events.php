<?php

if (!defined('NGCMS'))
	exit('HAL');

LoadPluginLang('events', 'main', '', '', '#');
add_act('index', 'events_header_show');
register_plugin_page('events','','events');
register_plugin_page('events','show','show_events');
register_plugin_page('events','search','search_events');
//register_plugin_page('events','list','list_events');
register_plugin_page('events','edit','edit_events');
register_plugin_page('events','unpublish','unpublish_events');
register_plugin_page('events','expend','expend_events');
register_plugin_page('events','del','del_events');

include_once(dirname(__FILE__).'/cache.php');
include_once(dirname(__FILE__).'/block_events.php');

function events_header_show()
{
global $CurrentHandler, $SYSTEM_FLAGS, $template, $lang;
	
	/* print '<pre>';
	print_r ($CurrentHandler);
	print '</pre>'; */
	
	/* print '<pre>';
	print_r ($SYSTEM_FLAGS);
	print '</pre>';  */
	
	if(empty($_REQUEST['page']))
	{
		$page = $CurrentHandler['params']['page'];
	} else {
		$page = $_REQUEST['page'];
	}
	
	$pageNo = isset($page)?str_replace('%count%',intval($page), '/ Страница %count%'):'';
	
	switch ($CurrentHandler['handlerName'])
	{
		case '':
			$titles = str_replace(
				array ('%name_site%', '%separator%', '%group%', '%others%', '%num%'),
				array ($SYSTEM_FLAGS['info']['title']['header'], $SYSTEM_FLAGS['info']['title']['separator'], $SYSTEM_FLAGS['info']['title']['group'],  $SYSTEM_FLAGS['info']['title']['others'], $pageNo),
				$lang['events']['titles']);
			break;
		case 'show':
			$titles = str_replace(
				array ('%name_site%', '%group%', '%others%'),
				array ($SYSTEM_FLAGS['info']['title']['header'],  $SYSTEM_FLAGS['info']['title']['group'], $SYSTEM_FLAGS['info']['title']['others']),
				$lang['events']['titles_show']);
			break;
		case 'search':
			$titles = str_replace(
				array ('%name_site%', '%group%', '%others%'),
				array ($SYSTEM_FLAGS['info']['title']['header'], $SYSTEM_FLAGS['info']['title']['group'], $SYSTEM_FLAGS['info']['title']['others']),
				$lang['events']['titles_search']);
			break;
		case 'list':
			$titles = str_replace(
				array ('%name_site%', '%group%', '%others%'),
				array ($SYSTEM_FLAGS['info']['title']['header'], $SYSTEM_FLAGS['info']['title']['group'], $SYSTEM_FLAGS['info']['title']['others']),
				$lang['events']['titles_list']);
			break;
		case 'edit':
			$titles = str_replace(
				array ('%name_site%', '%group%', '%others%'),
				array ($SYSTEM_FLAGS['info']['title']['header'], $SYSTEM_FLAGS['info']['title']['group'], $SYSTEM_FLAGS['info']['title']['others']),
				$lang['events']['titles_edit']);
			break;
	}

	

	$template['vars']['titles'] = trim($titles);
}

function del_events($params)
{global $userROW, $mysql;
	$id = isset($params['id'])?abs(intval($params['id'])):abs(intval($_REQUEST['id']));
	if(empty($id))
	{
		redirect_events(link_events_list());
	}
	if(isset($userROW) && !empty($userROW)){
	
		if($row = $mysql->record('SELECT * FROM '.prefix.'_events WHERE id = '.db_squote($id).' and author_id = \''.intval($userROW['id']).'\' LIMIT 1'))
			{
				$mysql->query('UPDATE '.prefix.'_events SET  
							active = \'0\'
							WHERE id = \''.$id.'\' and author_id = \''.$userROW['id'].'\'
				');
				
				foreach ($mysql->select('select * from '.prefix.'_events_images where zid='.db_squote($id).'') as $row2)
				{
				unlink($_SERVER['DOCUMENT_ROOT'] . '/uploads/events/' . $row2['filepath']);
				unlink($_SERVER['DOCUMENT_ROOT'] . '/uploads/events/thumb/' . $row2['filepath']);
				}
				$mysql->query("delete from ".prefix."_events_images where zid = ".db_squote($id)."");

				$mysql->query('delete from '.prefix.'_events where id = '.db_squote($id));
				
				$_SESSION['events']['info'] = 'Объявление удалено.';
				
				generate_entries_cnt_cache(true);
				generate_catz_cache(true);
				
				redirect_events(link_events_list());
			}
			else
			{
			$_SESSION['events']['info'] = 'Вы пытаетесь удалить не свое объявление.';
			redirect_events(link_events_list());
			}
	} else {
		$_SESSION['events']['info'] = 'У вас нет прав для удаления объявлений.';
		redirect_events(link_events());
	}
}


function unpublish_events($params)
{global $userROW, $mysql;
	$id = isset($params['id'])?abs(intval($params['id'])):abs(intval($_REQUEST['id']));
	if(empty($id))
	{
		redirect_events(home);
	}
	if(isset($userROW) && !empty($userROW)){
	
		if($row = $mysql->record('SELECT * FROM '.prefix.'_events WHERE id = '.db_squote($id).' and author_id = \''.intval($userROW['id']).'\' LIMIT 1'))
			{
				$mysql->query('UPDATE '.prefix.'_events SET  
							expired = \'1\'
							WHERE id = \''.$id.'\' and author_id = \''.$userROW['id'].'\'
				');

				$_SESSION['events']['info'] = 'Объявление снято с публикации.';
				
				generate_entries_cnt_cache(true);
				generate_catz_cache(true);
				
				redirect_events(home);
			}
			else
			{
			$_SESSION['events']['info'] = 'Вы пытаетесь снять с публикации не свое объявление.';
			redirect_events(home);
			}
	} else {
		$_SESSION['events']['info'] = 'У вас нет прав для снятия с публикации этого объявления.';
		redirect_events(link_events());
	}
}

function edit_events($params)
{global $tpl, $template, $twig, $mysql, $SYSTEM_FLAGS, $config, $userROW, $lang, $CurrentHandler;
	$tpath = locatePluginTemplates(array('edit_events', 'no_access'), 'events', pluginGetVariable('events', 'localsource'), pluginGetVariable('events','localskin'));
	$xt = $twig->loadTemplate($tpath['edit_events'].'edit_events.tpl');
	
	$SYSTEM_FLAGS['info']['title']['group'] = $lang['events']['name_plugin'];
	$SYSTEM_FLAGS['info']['title']['others'] = 'Редактирование';
	$id = isset($params['id'])?abs(intval($params['id'])):abs(intval($_REQUEST['id']));
	
	if(empty($id))
	{
		redirect_events(link_events());
	}
	
	if(isset($userROW) && !empty($userROW))
	{
		if($row = $mysql->record('SELECT * FROM '.prefix.'_events WHERE id = '.db_squote($id).' and author_id = \''.intval($userROW['id']).'\' LIMIT 1'))
		{
		
			$res = $mysql->select("SELECT * FROM ".prefix."_events_cities ORDER BY city");
			
			foreach($res as $v){
				if($row['city'] == $v['id']) {
					$cities .= '<option value="'.$v['id'].'" selected>'.$v['city'].'</option>';
				}else {
					$cities .= '<option value="'.$v['id'].'">'.$v['city'].'</option>';
				}
			}
	
			$res = mysql_query("SELECT * FROM ".prefix."_events_cat ORDER BY id");
			$cats = getCats($res);
			$options = getTree($cats, $row['cat_id'], 0);
	
		
		if (isset($_REQUEST['submit']))
		{

			$SQL['editdate'] = time() + ($config['date_adjust'] * 60);
			
			$SQL['announce_name'] = input_filter_com(convert($_REQUEST['announce_name_edit']));
			if(empty($SQL['announce_name']))
				$error_text[] = 'Название объявления пустое';

			
			$SQL['announce_place'] = input_filter_com(convert($_REQUEST['announce_place_edit']));
			if(empty($SQL['announce_place']))
				$error_text[] = 'Поле место сбора не заполнено';
			
			$SQL['cat_id'] = intval($_REQUEST['announce_type_edit']);
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
			
			$SQL['city'] = intval($_REQUEST['announce_city_edit']);
			
			$timestamp = strtotime(secure_html($_REQUEST['announce_datepicker_edit'])." ".secure_html($_REQUEST['announce_timepicker_edit']));
			$SQL['date'] = $timestamp;
			
			$editdate = time() + ($config['date_adjust'] * 60);
			
			$SQL['editdate'] = $editdate;
			
			$SQL['announce_description'] = str_replace(array("\r\n", "\r"), "\n",input_filter_com(convert($_REQUEST['announce_description_edit'])));
			if(empty($SQL['announce_description']))
			{
				$error_text[] = 'Нет описания к объявлению';
			}

			
			//$SQL['active'] = $_REQUEST['announce_activeme'];
			$SQL['active'] = 1;

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
				
				$_SESSION['events']['info'] = str_replace( array('%user%'), array(input_filter_com(convert($_REQUEST['author']))), pluginGetVariable('events', 'info_edit'));
				
				generate_entries_cnt_cache(true);
				generate_catz_cache(true);
				
				redirect_events(home);
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

		$tVars = array(
			'entriesImg' => isset($entriesImg)?$entriesImg:'',
			'options' => $options,
			'cities' => $cities,
			'announce_activeme' => $checked,
			'announce_name' => $row['announce_name'],
			'announce_place' => $row['announce_place'],
			'date'	=> date('d.m.Y', $row['date']),
			'time'	=> date('H:i', $row['date']),
			'author' => $row['author'],
			'announce_description' => $row['announce_description'],
			'tpl_url' => home.'/templates/'.$config['theme'],
			'bb_tags' => events_bbcode(),
			'tpl_home' => admin_url,
			'id' => intval($id),
			'error' => $error_input,
		);
		
		
		$template['vars']['mainblock'] .= $xt->render($tVars);
		$template['vars']['pages'] = '';
		
		} else {
			header('HTTP/1.1 403 Forbidden');
			$SYSTEM_FLAGS['info']['title']['others'] = 'Вы не являетесь автором этого объявления';
			$xt = $twig->loadTemplate($tpath['no_access'].'no_access.tpl');
		
			$tVars['vars']['home'] = home;
			$template['vars']['mainblock'] .= $xt->render($tVars);
		}

	} else {
			header('HTTP/1.1 403 Forbidden');
			$SYSTEM_FLAGS['info']['title']['others'] = 'Доступ разрешен только авторизированным';
			$xt = $twig->loadTemplate($tpath['no_access'].'no_access.tpl');
			
			$tVars['vars']['home'] = home;
			$template['vars']['mainblock'] .= $xt->render($tVars);
	}

}


function expend_events($params)
{global $tpl, $template, $twig, $mysql, $SYSTEM_FLAGS, $config, $userROW, $lang, $CurrentHandler;
	$tpath = locatePluginTemplates(array('edit_events', 'no_access'), 'events', pluginGetVariable('events', 'localsource'), pluginGetVariable('events','localskin'));
	
	$xt = $twig->loadTemplate($tpath['edit_events'].'edit_events.tpl');
	
	$SYSTEM_FLAGS['info']['title']['group'] = $lang['events']['name_plugin'];
	$SYSTEM_FLAGS['info']['title']['others'] = 'Продление';
	$id = isset($params['id'])?abs(intval($params['id'])):abs(intval($_REQUEST['id']));
	$hashcode = isset($params['hashcode'])?$params['hashcode']:$_REQUEST['hashcode'];
	
	if( empty($id) || empty($hashcode) || !(isset($hashcode)) )
	{
		redirect_events(link_events());
	}
	
		if($row = $mysql->record('SELECT * FROM '.prefix.'_events WHERE id = '.db_squote($id).' and expired = '.db_squote($hashcode).' LIMIT 1'))
		{
		
		foreach (explode("|",pluginGetVariable('events', 'list_period')) as $line) {
			$list_period .= str_replace( array('{line}', '{activ}'), array($line, ($line==$row['announce_period']?'selected':'')), $lang['events']['list_period_edit']);
		}
		
		$options = '<option disabled>---------</option>';
		foreach ($mysql->select('SELECT id, cat_name FROM '.prefix.'_events_cat') as $cat)
		{
			$options .= '<option value="' . $cat['id'] . '"'.(($row['cat_id']==$cat['id'])?'selected':'').'>' . $cat['cat_name'] . '</option>';
		}
		if (isset($_REQUEST['submit']))
		{
			$SQL['editdate'] = time() + ($config['date_adjust'] * 60);
			
			$SQL['announce_name'] = input_filter_com(convert($_REQUEST['announce_name']));
			if(empty($SQL['announce_name']))
				$error_text[] = 'Название объявления пустое';

			
			$SQL['author'] = input_filter_com(convert($_REQUEST['author']));
			if(empty($SQL['author']))
				$error_text[] = 'Поле автор не заполнено';
			
			$SQL['announce_period'] = input_filter_com(convert($_REQUEST['announce_period']));
			if(!empty($SQL['announce_period']))
			{
				if(!in_array($SQL['announce_period'], explode("|",pluginGetVariable('events', 'list_period'))))
				{
					$error_text[] = 'Поле период задано неверно '.$SQL['announce_period'];
				}
				
			} else {
				$error_text[] = 'Поле период не заполнено';
			}
			
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
			
			
			$SQL['announce_description'] = str_replace(array("\r\n", "\r"), "\n",input_filter_com(convert($_REQUEST['announce_description'])));
			if(empty($SQL['announce_description']))
			{
				$error_text[] = 'Нет описания к объявлению';
			}
			
			$SQL['announce_contacts'] = str_replace(array("\r\n", "\r"), "\n",input_filter_com(convert($_REQUEST['announce_contacts'])));
			if(empty($SQL['announce_contacts']))
			{
				$error_text[] = 'Нет контактов к объявлению';
			}
			
			//$SQL['active'] = $_REQUEST['announce_activeme'];
			$SQL['active'] = 0;
			$SQL['expired'] = '';
			
			
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
				
				$_SESSION['events']['info'] = str_replace( array('%user%'), array(input_filter_com(convert($_REQUEST['author']))), pluginGetVariable('events', 'info_edit'));
				redirect_events(link_events());
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
		
		foreach ($mysql->select('select * from '.prefix.'_events_images where zid='.$id.'') as $row2)
		{
		
			$entriesImg[] = array (
				'home' => home,
				'del' => home.'/plugin/events/expend/?id='.$id.'&hashcode='.$hashcode.'&delimg='.$row2['pid'].'&filepath='.$row2['filepath'].'',
				'pid' => $row2['pid'],
				'filepath' => $row2['filepath'],
				'zid' => $row2['zid'],
			);

		}

		
		$tVars = array(
			'entriesImg' => isset($entriesImg)?$entriesImg:'',
			'options' => $options,
			'announce_activeme' => $checked,
			'announce_name' => $row['announce_name'],
			'list_period' => $list_period,
			'announce_contacts' => $row['announce_contacts'],
			'author' => $row['author'],
			'announce_description' => $row['announce_description'],
			'tpl_url' => home.'/templates/'.$config['theme'],
			'bb_tags' => events_bbcode(),
			'tpl_home' => admin_url,
			'id' => intval($id),
			'error' => $error_input,
		);
		

	if (isset($_REQUEST['delimg']) && isset($_REQUEST['filepath']))
		{
		$imgID = intval($_REQUEST['delimg']);
		$imgPath = $_REQUEST['filepath'];
		$mysql->query("delete from ".prefix."_events_images where pid = ".db_squote($imgID)."");
		//echo root . '/uploads/events/' . $imgPath;
		unlink($_SERVER['DOCUMENT_ROOT'] . '/uploads/events/' . $imgPath);
		unlink($_SERVER['DOCUMENT_ROOT'] . '/uploads/events/thumb/' . $imgPath);
		//redirect_events($url)
		redirect_events(home.'/plugin/events/expend/?id='.$id.'&hashcode='.$hashcode.'');
		}
	
	if (isset($_REQUEST['delme']))
		{
		
		foreach ($mysql->select('select * from '.prefix.'_events_images where zid='.db_squote($id).'') as $row2)
		{
		unlink($_SERVER['DOCUMENT_ROOT'] . '/uploads/events/' . $row2['filepath']);
		unlink($_SERVER['DOCUMENT_ROOT'] . '/uploads/events/thumb/' . $row2['filepath']);
		}
		$mysql->query("delete from ".prefix."_events_images where zid = ".db_squote($id)."");

		$mysql->query('delete from '.prefix.'_events where id = '.db_squote($id));
		
		redirect_events(link_events());
		}
		
		
			$template['vars']['mainblock'] .= $xt->render($tVars);
		
		} else {
			header('HTTP/1.1 403 Forbidden');
			$xt = $twig->loadTemplate($tpath['no_access'].'no_access.tpl');
			$SYSTEM_FLAGS['info']['title']['others'] = 'Вы не являетесь автором этого объявления';
			$tVars['vars']['home'] = home;
			$template['vars']['mainblock'] .= $xt->render($tVars);
		}

}

/*
function list_events($params)
{
global $tpl, $template, $twig, $mysql, $SYSTEM_FLAGS, $config, $userROW, $lang, $CurrentHandler;
	$tpath = locatePluginTemplates(array('list_events', 'no_access'), 'events', pluginGetVariable('events', 'localsource'));
	$xt = $twig->loadTemplate($tpath['list_events'].'list_events.tpl');
	
	$SYSTEM_FLAGS['info']['title']['group'] = $lang['events']['name_plugin'];
	$SYSTEM_FLAGS['info']['title']['others'] = 'Список ваших событий';
	$SYSTEM_FLAGS['template.main.name'] = pluginGetVariable('events', 'main_template')?pluginGetVariable('events', 'main_template'):'main';
	
	
	$url = pluginGetVariable('events', 'url');
		
	switch($CurrentHandler['handlerParams']['value']['pluginName'])
	{
		case 'core': 
			if(isset($url) && !empty($url))
			{
				return redirect_events(generateLink('events', 'list'));
			}
			break;
		case 'events': 
			if(empty($url))
			{
				return redirect_events(generateLink('core', 'plugin', array('plugin' => 'events')));
			}
			break;
	}
	
	if(isset($userROW) && !empty($userROW))
	{
		if(isset($_SESSION['events']['info']) && !empty($_SESSION['events']['info']))
		{
			$info = $_SESSION['events']['info'];
			unset($_SESSION['events']['info']);
		} else {
			$info = '';
		}
		$limitCount = intval(pluginGetVariable('events', 'count_list'));
		
		$pageNo		= intval($params['page'])?intval($params['page']):intval($_REQUEST['page']);
		if ($pageNo < 1) $pageNo = 1;
		if (!isset($limitStart)) $limitStart = ($pageNo - 1)* $limitCount;
		
		$count = $mysql->result('SELECT COUNT(id) FROM '.prefix.'_events WHERE active = \'1\' and author_id = \''.intval($userROW['id']).'\'');
		
		$countPages = ceil($count / $limitCount);
		
		if($countPages < $pageNo)
		return msg(array("type" => "error", "text" => "Подстраницы не существует"));
		
		if ($countPages > 1 && $countPages >= $pageNo){
			$paginationParams = checkLinkAvailable('events', '')?
				array('pluginName' => 'events', 'pluginHandler' => 'list', 'params' => array(), 'xparams' => array(), 'paginator' => array('page', 0, false)):
				array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'events', 'handler' => 'list'), 'xparams' => array(), 'paginator' => array('page', 1, false));

			$navigations = LoadVariables();
			$pages = generatePagination($pageNo, 1, $countPages, 10, $paginationParams, $navigations);
		}
		
		foreach ($mysql->select('SELECT *, c.id as cid, n.id as nid FROM '.prefix.'_events n LEFT JOIN '.prefix.'_events_cat c ON n.cat_id = c.id LEFT JOIN '.prefix.'_events_images i ON n.id = i.zid WHERE n.active = \'1\' GROUP BY n.id ORDER BY editdate DESC LIMIT '.intval($limitStart).', '.intval($limitCount)) as $row)
		{
			$fulllink = checkLinkAvailable('events', 'show')?
				generateLink('events', 'show', array('id' => $row['nid'])):
				generateLink('core', 'plugin', array('plugin' => 'events', 'handler' => 'show'), array('id' => $row['nid']));
			$catlink = checkLinkAvailable('events', '')?
				generateLink('events', '', array('cat' => $row['cid'])):
				generateLink('core', 'plugin', array('plugin' => 'events'), array('cat' => $row['cid']));

			$edit = checkLinkAvailable('events', 'edit')?
				generateLink('events', 'edit', array('id' => $row['nid'])):
				generateLink('core', 'plugin', array('plugin' => 'events', 'handler' => 'edit'), array('id' => $row['nid']));
			
			$del = checkLinkAvailable('events', 'del')?
				generateLink('events', 'del', array('id' => $row['nid'])):
				generateLink('core', 'plugin', array('plugin' => 'events', 'handler' => 'del'), array('id' => $row['nid']));
			
			$tEntry[] = array (
				'nid'					=>	$row['nid'],
				'cid'					=>	$row['cid'],
				'date'					=>	$row['date'],
				'editdate'				=>	$row['editdate'],
				'views'					=>	$row['views'],
				'announce_name'			=>	$row['announce_name'],
				'author'				=>	$row['author'],
				'author_id'				=>	$row['author_id'],
				'author_email'			=>	$row['author_email'],
				'announce_period'		=>	$row['announce_period'],
				'announce_description'	=>	events_bbcode_p($row['announce_description']),
				'announce_contacts'		=>	$row['announce_contacts'],
				'fulllink'				=>	$fulllink,
				'catlink'				=>	$catlink,
				'cat_name'				=>	$row['cat_name'],
				'pid'					=>	$row['pid'],
				'filepath'				=>	$row['filepath'],
				'edit' => $edit,
				'del' => $del,
			);

		}
		
		
		if ($limitStart)
		{
			$prev = floor($limitStart / $limitCount);
			$PageLink = checkLinkAvailable('events', '')?
				generatePageLink(array('pluginName' => 'events', 'pluginHandler' => 'list', 'params' => array(), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev):
				generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'events', 'handler' => 'list'), 'xparams' => array(), 'paginator' => array('page', 1, false)), $prev);
		
			$gvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',$PageLink, $navigations['prevlink']));
		} else {
			$gvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = "";
			$prev = 0;
		}
		
		if (($prev + 2 <= $countPages))
		{
			$PageLink = checkLinkAvailable('events', '')?
				generatePageLink(array('pluginName' => 'events', 'pluginHandler' => 'list', 'params' => array(), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev+2):
				generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'events', 'handler' => 'list'), 'xparams' => array(), 'paginator' => array('page', 1, false)), $prev+2);
			$gvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',$PageLink, $navigations['nextlink']));
		} else {
			$gvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = "";
		}

		
		$tVars = array(
		'entries' => isset($tEntry)?$tEntry:'',
		'info' =>	isset($info)?$info:'',
		'pages' => array(
			'true' => (isset($pages) && $pages)?1:0,
			'print' => isset($pages)?$pages:''
		),
		'prevlink' => array(
					'true' => !empty($limitStart)?1:0,
					'link' => str_replace('%page%',
											"$1",
											str_replace('%link%', 
												checkLinkAvailable('events', '')?
				generatePageLink(array('pluginName' => 'events', 'pluginHandler' => 'list', 'params' => array(), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev = floor($limitStart / $limitCount)):
				generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'events', 'handler' => 'list'), 'xparams' => array(), 'paginator' => array('page', 1, false)),$prev = floor($limitStart / $limitCount)), 
												isset($navigations['prevlink'])?$navigations['prevlink']:''
											)
					),
		),
		'nextlink' => array(
					'true' => ($prev + 2 <= $countPages)?1:0,
					'link' => str_replace('%page%',
											"$1",
											str_replace('%link%', 
												checkLinkAvailable('events', '')?
				generatePageLink(array('pluginName' => 'events', 'pluginHandler' => 'list', 'params' => array(), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev+2):
				generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'events', 'handler' => 'list'), 'xparams' => array(), 'paginator' => array('page', 1, false)), $prev+2), 
												isset($navigations['nextlink'])?$navigations['nextlink']:''
											)
					),
		),
		
		'tpl_url' => home.'/templates/'.$config['theme'],
		);
		
		$template['vars']['mainblock'] .= $xt->render($tVars);
	} else {
		header('HTTP/1.1 403 Forbidden');
		$SYSTEM_FLAGS['info']['title']['others'] = 'Доступ разрешен только авторизированным';
		$xt = $twig->loadTemplate($tpath['no_access'].'no_access.tpl');
		
		$tVars['vars']['home'] = home;
		$template['vars']['mainblock'] .= $xt->render($tVars);
	}
}
*/
function events($params)
{
global $tpl, $template, $twig, $mysql, $SYSTEM_FLAGS, $config, $userROW, $lang, $CurrentHandler, $TemplateCache;
	
	//plugin_events_cron();
	//var_dump(getcat());
	$sort = array();
	$cat = isset($params['cat'])?$params['cat']:$_REQUEST['cat'];
	
	if(isset($cat) && !empty($cat))
	{
		$cat = input_filter_com($cat);
		$cat_id = ' and cat_id = '.db_squote($cat);
		$sort['cat'] = $cat;
	} else {
		//$cat = pluginGetVariable('events', 'cat_id');
		$cat_id = '';
	}
	
	
	$sorting = $cat_id;
	
	$url = pluginGetVariable('events', 'url');
	//var_dump($sort);
	switch($CurrentHandler['handlerParams']['value']['pluginName'])
	{
	case 'core': 
			if(isset($url) && !empty($url) && empty($params['page']) && empty($_REQUEST['page']) && empty($sort))
			{
				return redirect_events(generateLink('events', ''));
			}else if(isset($url) && !empty($url) or (!empty($params['page']) or !empty($_REQUEST['page']) or !empty($sort)))
			{
				//return redirect_events(generatePageLink(array('pluginName' => 'events', 'pluginHandler' => '', 'params' => array('cat' => $sort['cat']), 'xparams' => array(), 'paginator' => array('page', 0, false)), intval($_REQUEST['page'])));
			}
			break;
	}
	
	if(isset($_SESSION['events']['info']) && !empty($_SESSION['events']['info']))
	{
		$info = $_SESSION['events']['info'];
		unset($_SESSION['events']['info']);
	} else {
		$info = '';
	}
	
	$SYSTEM_FLAGS['info']['title']['group'] = $lang['events']['name_plugin'];
	$SYSTEM_FLAGS['template.main.name'] = pluginGetVariable('events', 'main_template')?pluginGetVariable('events', 'main_template'):'main';
	$tpath = locatePluginTemplates(array('events'), 'events', pluginGetVariable('events', 'localsource'), pluginGetVariable('events','localskin'));
	$xt = $twig->loadTemplate($tpath['events'].'events.tpl');
	
	/*
	$catt = array(); 
	foreach ($mysql->select('SELECT cat_id, COUNT(id) as num FROM '.prefix.'_events WHERE active = \'1\' GROUP BY cat_id ') as $rows)
	{
		$catt[$rows['cat_id']] .= $rows['num'];
	}
	*/
	
	
	foreach ($mysql->select('SELECT * FROM '.prefix.'_events_cat ORDER BY position ASC') as $cat_row)
	{
			if($_REQUEST['cat'] == $cat_row['id'] or $params['cat'] == $cat_row['id'])
			{
				$SYSTEM_FLAGS['meta']['description']	= ($cat_row['description'])?$cat_row['description']:pluginGetVariable('events', 'description');
				$SYSTEM_FLAGS['meta']['keywords']		= ($cat_row['keywords'])?$cat_row['keywords']:pluginGetVariable('events', 'keywords');
				$SYSTEM_FLAGS['info']['title']['others'] = str_replace( array( '{name}' ), array($cat_row['cat_name']), $lang['events']['sorting']);
				$SYSTEM_FLAGS['info']['title']['separator'] =  $lang['events']['separator'];
			} else if($cat_row['id'] == $cat)
			{
				$SYSTEM_FLAGS['info']['title']['separator'] =  $lang['events']['separator'];
				$SYSTEM_FLAGS['meta']['description']	= ($cat_row['description'])?$cat_row['description']:pluginGetVariable('events', 'description');
				$SYSTEM_FLAGS['meta']['keywords']		= ($cat_row['keywords'])?$cat_row['keywords']:pluginGetVariable('events', 'keywords');
				$SYSTEM_FLAGS['info']['title']['others'] = '';
			}
			else {
				$SYSTEM_FLAGS['info']['title']['separator'] =  $lang['events']['separator'];
				$SYSTEM_FLAGS['meta']['description']	= pluginGetVariable('events', 'description');
				$SYSTEM_FLAGS['meta']['keywords']		= pluginGetVariable('events', 'keywords');
			}
		
		/*
		$catlink = checkLinkAvailable('events', '')?
				generateLink('events', '', array('cat' => $cat_row['id'])):
				generateLink('core', 'plugin', array('plugin' => 'events'), array('cat' => $cat_row['id']));
		
		if($cat_row['id']==$cat)
		{
			$count = $catt[$cat_row['id']];
			//print_r ($cat_row);
		}
		
		$entriesCatz[] = array (
				'selected' => ($cat_row['id']==$cat)?'selected':'',
				'url' => $catlink,
				'id' => $cat_row['id'],
				'cat_name' => $cat_row['cat_name'],
				'num' => $catt[$cat_row['id']]?$catt[$cat_row['id']]:'0',
			);
		
		$cats_ID[$cat_row['id']][] = $cat_row;
        $cats[$cat_row['parent_id']][$cat_row['id']] =  $cat_row;
		$cats[$cat_row['parent_id']][$cat_row['id']]['url'] =  $catlink;
		$cats[$cat_row['parent_id']][$cat_row['id']]['num'] =  $catt[$cat_row['id']]?$catt[$cat_row['id']]:'0';
		*/
	}
//	var_dump($cats);
//	var_dump(build_tree($cats,0));
	
	$limitCount = pluginGetVariable('events', 'count');
	
	$pageNo		= intval($params['page'])?intval($params['page']):intval($_REQUEST['page']);
	if ($pageNo < 1)	$pageNo = 1;
	if (!$limitStart)	$limitStart = ($pageNo - 1)* $limitCount;
	
	$count = $mysql->result('SELECT COUNT(id) FROM '.prefix.'_events WHERE expired != \'1\' '.$sorting);
	
	if($count == 0)
		return msg(array("type" => "error", "text" => "В данной категории пока что нету объявлений"));
		
	
	$countPages = ceil($count / $limitCount);
	
	if($countPages < $pageNo)
		return msg(array("type" => "error", "text" => "Подстраницы не существует"));
		
	
	if ($countPages > 1 && $countPages >= $pageNo)
	{
		$paginationParams = checkLinkAvailable('events', '')?
			array('pluginName' => 'events', 'pluginHandler' => '', 'params' => $sort, 'xparams' => array(), 'paginator' => array('page', 0, false)):
			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'events'), 'xparams' => $sort, 'paginator' => array('page', 1, false));
		
		$navigations = LoadVariables();
		$pages = generatePagination($pageNo, 1, $countPages, 10, $paginationParams, $navigations);
	}

		// Preload template configuration variables
		templateLoadVariables();

		// Use default <noavatar> file
		// - Check if noavatar is defined on template level
		$tplVars = $TemplateCache['site']['#variables'];
		$noAvatarURL = (isset($tplVars['configuration']) && is_array($tplVars['configuration']) && isset($tplVars['configuration']['noAvatarImage']) && $tplVars['configuration']['noAvatarImage'])?(tpl_url."/".$tplVars['configuration']['noAvatarImage']):(avatars_url."/noavatar.jpg");
			
	foreach ($mysql->select('SELECT e.date as edate, e.views as eviews, e.announce_name as eannounce_name, e.announce_place as eannounce_place, e.announce_description as eannounce_description, e.city as ecity, u.name as uname, u.xfields_ucity as xfields_ucity, u.xfields_ubirthdate as xfields_ubirthdate, u.avatar as uavatar, u.id as uid, e.id as eid, c.id as cid FROM '.prefix.'_events e LEFT JOIN '.prefix.'_events_cat c ON e.cat_id = c.id LEFT JOIN '.prefix.'_users u ON e.author_id = u.id WHERE e.expired != \'1\' '.$sorting.' ORDER BY e.editdate DESC LIMIT '.intval($limitStart).', '.intval($limitCount)) as $row)
	{
		$fulllink = checkLinkAvailable('events', 'show')?
			generateLink('events', 'show', array('id' => $row['nid'])):
			generateLink('core', 'plugin', array('plugin' => 'events', 'handler' => 'show'), array('id' => $row['nid']));
		$catlink = checkLinkAvailable('events', '')?
			generateLink('events', '', array('cat' => $row['cid'])):
			generateLink('core', 'plugin', array('plugin' => 'events'), array('cat' => $row['cid']));
		
		// $irow = $mysql->record('select * from '.prefix.'_events_images where zid='.$row['nid'].' LIMIT 1');
		
		/*
		$irow = $mysql->record('select * from '.prefix.'_events_images where zid='.$row['nid'].' LIMIT 1');
		
		foreach ($mysql->select('select * from '.prefix.'_events_images where zid='.$row['nid'].' LIMIT 1') as $row2)
		{
		$gvars['vars'] = array (
			'home' => home,
			'pid' => $row2['pid'],
			'filepath' => $row2['filepath'],
			'zid' => $row2['zid'],
		);
	
		$tpl->template('list_images', $tpath['config/list_images'].'config');
		$tpl->vars('list_images', $gvars);
		$entriesImg .= $tpl -> show('list_images');
		}
		$pvars['vars']['entriesImg'] = $entriesImg;
		*/
		
		
		$fulllink = checkLinkAvailable('events', 'show')?
			generateLink('events', 'show', array('id' => $row['eid'])):
			generateLink('core', 'plugin', array('plugin' => 'events', 'handler' => 'show'), array('id' => $row['eid']));
		
		$catlink = checkLinkAvailable('events', '')?
			generateLink('events', '', array('cat' => $row['cid'])):
			generateLink('core', 'plugin', array('plugin' => 'events'), array('cat' => $row['cid']));
			
		// If avatar is set
		if ($row['uavatar'] != '') {
			$avatar = avatars_url.'/'.((preg_match('/^'.$row['uid'].'\./', $row['uavatar']))?($row['uid'].'.'):'').$row['uavatar'];
		} else {
			$avatar = $noAvatarURL;
		}
		
		//var_dump($row);
		$entries[] = array (
				'id' => $row['id'],
				'date' => $row['edate'],
				'views' => $row['eviews'],
				'announce_name' => $row['eannounce_name'],
				'announce_place' => $row['eannounce_place'],
				'announce_description' => $row['eannounce_description'],
				'city' => $row['ecity'],
				'uname' => $row['uname'],
				'ucity' => $row['xfields_ucity'],
				'uage' =>  dataDiff($row['xfields_ubirthdate']),
				'wordage' => ruDecline(dataDiff($row['xfields_ubirthdate']),"год","года","лет"),
				'author_link' => checkLinkAvailable('uprofile', 'show')?
									generateLink('uprofile', 'show', array('name' => $row['uname'], 'id' => $row['uid'])):
									generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('name' => $row['uname'], 'id' => $row['uid'])),
				'avatar' => $avatar,
				'fulllink' => $fulllink,
				'catlink' => $catlink,
			'home' => home,
			'full' => str_replace( array( '{url}', '{name}'), array($fulllink, $row['announce_name']), $lang['events']['fulllink']),
			'tpl_url' => home.'/templates/'.$config['theme'],
		);

	}
		
		if ($limitStart)
		{
			$prev = floor($limitStart / $limitCount);
			$PageLink = checkLinkAvailable('events', '')?
				generatePageLink(array('pluginName' => 'events', 'pluginHandler' => '', 'params' => array(), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev):
				generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'events'), 'xparams' => array(), 'paginator' => array('page', 1, false)), $prev);
		
			$gvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',$PageLink, $navigations['prevlink']));
		} else {
			$gvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = "";
			$prev = 0;
		}
		
		if (($prev + 2 <= $countPages))
		{
			$PageLink = checkLinkAvailable('events', '')?
				generatePageLink(array('pluginName' => 'events', 'pluginHandler' => '', 'params' => array(), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev+2):
				generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'events'), 'xparams' => array(), 'paginator' => array('page', 1, false)), $prev+2);
			$gvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',$PageLink, $navigations['nextlink']));
		} else {
			$gvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = "";
		}
		
		
		$tVars = array(
			'info' =>	isset($info)?$info:'',
	//		'entriesCatz' => isset($entriesCatz)?$entriesCatz:'',
			'entries' => isset($entries)?$entries:'',
	//		'entries_cat_tree' => build_tree($cats,0),
			'pages' => array(
			'true' => (isset($pages) && $pages)?1:0,
			'print' => isset($pages)?$pages:''
							),
			'prevlink' => array(
					'true' => !empty($limitStart)?1:0,
					'link' => str_replace('%page%',
											"$1",
											str_replace('%link%', 
												checkLinkAvailable('events', '')?
				generatePageLink(array('pluginName' => 'events', 'pluginHandler' => '', 'params' => array(), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev = floor($limitStart / $limitCount)):
				generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'events'), 'xparams' => array(), 'paginator' => array('page', 1, false)), $prev = floor($limitStart / $limitCount)), 
												isset($navigations['prevlink'])?$navigations['prevlink']:''
											)
					),
								),
			'nextlink' => array(
					'true' => ($prev + 2 <= $countPages)?1:0,
					'link' => str_replace('%page%',
											"$1",
											str_replace('%link%', 
												checkLinkAvailable('events', '')?
				generatePageLink(array('pluginName' => 'events', 'pluginHandler' => '', 'params' => array(), 'xparams' => array(), 'paginator' => array('page', 0, false)), $prev+2):
				generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'events'), 'xparams' => array(), 'paginator' => array('page', 1, false)), $prev+2), 
												isset($navigations['nextlink'])?$navigations['nextlink']:''
											)
					),
								),
			'tpl_url' => home.'/templates/'.$config['theme'],
			'tpl_home' => admin_url,
		);
		
			$template['vars']['mainblock'] .= $xt->render($tVars);
}

function search_events($params)
{
global $tpl, $template, $twig, $mysql, $SYSTEM_FLAGS, $config, $userROW, $CurrentHandler, $lang;

	$url = pluginGetVariable('events', 'url');
	switch($CurrentHandler['handlerParams']['value']['pluginName'])
	{
		case 'core': 
			if(isset($url) && !empty($url))
			{
				return redirect_events(generateLink('events', 'search'));
			}
			break;
		case 'events': 
			if(empty($url))
			{
				return redirect_events(generateLink('core', 'plugin', array('plugin' => 'events')));
			}
			break;
	}

	$SYSTEM_FLAGS['info']['title']['group'] = $lang['events']['name_plugin'];
	$SYSTEM_FLAGS['info']['title']['others'] = $lang['events']['name_search'];
	$SYSTEM_FLAGS['template.main.name'] = pluginGetVariable('events', 'main_template')?pluginGetVariable('events', 'main_template'):'main';
	$SYSTEM_FLAGS['meta']['description']	= (pluginGetVariable('events', 'description'))?pluginGetVariable('events', 'description'):$SYSTEM_FLAGS['meta']['description'];
	$SYSTEM_FLAGS['meta']['keywords']		= (pluginGetVariable('events', 'keywords'))?pluginGetVariable('events', 'keywords'):$SYSTEM_FLAGS['meta']['keywords'];
	
	$tpath = locatePluginTemplates(array('search_events'), 'events', pluginGetVariable('events', 'localsource'), pluginGetVariable('events','localskin'));
	$xt = $twig->loadTemplate($tpath['search_events'].'search_events.tpl');
	
	if(isset($_REQUEST['submit']) && $_REQUEST['submit']){
		$keywords = secure_search_events($_REQUEST['keywords']);
		$cat_id = intval($_REQUEST['cat_id']);
		if(empty($cat_id))
			$cat_id = 0;
		
		$search_in = secure_search_events($_REQUEST['search_in']);
		if(empty($search_in))
			$search_in = 'all';
		
		$search = substr($keywords, 0, 64);
 		if( strlen($search) < 3 )
			$output = msg(array("type" => "error", "text" => "Слишком короткое слово"), 1, 2);

		$keywords = array();
		
		$get_url = $search;
		
		$search = str_replace(" +", " ", $search);
		$stemmer = new Lingua_Stem_Ru();
		
		$tmp = explode( " ", $search );
		
		foreach ( $tmp as $wrd )
			$keywords[] = $stemmer->stem_word($wrd);
		
		$string = implode( "* ", $keywords );
		$string = $string.'*';
		
		$text = implode('|', $keywords);
		
		if(isset($params['page']))
			$pageNo = isset($_REQUEST['page'])?intval($_REQUEST['page']):0;
		else
			$pageNo = isset($_REQUEST['page'])?intval($_REQUEST['page']):0;
		
		$limitCount = intval(pluginGetVariable('events', 'count_search'));

		if (($limitCount < 2)||($limitCount > 2000)) $limitCount = 2;
		
		if($cat_id)
			$cats_id = " AND a.`cat_id` = '{$cat_id}'";
		else
			$cats_id = NULL;
		
		switch($search_in){
			case 'all':$sql_count = "SELECT COUNT(*) FROM ".prefix."_events AS a 
									WHERE MATCH (a.announce_name, a.announce_description) AGAINST ('{$string}' IN BOOLEAN MODE){$cats_id} and a.active = 1 ";
									break;
			case 'text':$sql_count = "SELECT COUNT(*) FROM ".prefix."_events AS a 
									WHERE MATCH (a.announce_description) AGAINST ('{$string}' IN BOOLEAN MODE){$cats_id} and a.active = 1 ";
									break;
			case 'title':$sql_count = "SELECT COUNT(*) FROM ".prefix."_events AS a
									WHERE MATCH (a.announce_name) AGAINST ('{$string}' IN BOOLEAN MODE){$cats_id} and a.active = 1 ";
									break;
		}
		
		$count = $mysql->result($sql_count);
		
		$countPages = ceil($count / $limitCount);
		if($countPages < $pageNo)
			$output = msg(array("type" => "error", "text" => "Подстраницы не существует"), 1, 2);
		
		if ($pageNo < 1) $pageNo = 1;
		if (!isset($limitStart)) $limitStart = ($pageNo - 1)* $limitCount;
		
		if ($countPages > 1 && $countPages >= $pageNo){
	
			$paginationParams = checkLinkAvailable('events', 'search')?
				array('pluginName' => 'events', 'pluginHandler' => 'search', 'params' => array('keywords' => $get_url, 'cat_id' => $cat_id, 'search_in' => $search_in, 'submit'=> 'Отправить'), 'xparams' => array('keywords' => $get_url, 'cat_id' => $cat_id, 'search_in' => $search_in, 'submit'=> 'Отправить'), 'paginator' => array('page', 1, false)):
				array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'events', 'handler' => 'search'), 'xparams' => array('keywords' => $get_url, 'cat_id' => $cat_id, 'search_in' => $search_in, 'submit'=> 'Отправить'), 'paginator' => array('page', 1, false));
			
			$navigations = LoadVariables();
			$pages = generatePagination($pageNo, 1, $countPages, 10, $paginationParams, $navigations);
		}
		
		switch($search_in){
			case 'all': $sql_two = 'SELECT *, a.id as aid, b.id as bid FROM '.prefix.'_events a LEFT JOIN '.prefix.'_events_cat b ON a.cat_id = b.id LEFT JOIN '.prefix.'_users c ON a.author_id = c.id WHERE MATCH (a.announce_name, a.announce_description) AGAINST (\''.$string.'\' IN BOOLEAN MODE)'.$cats_id.' and a.active = \'1\' GROUP BY a.id ORDER BY MATCH (a.announce_name, a.announce_description) AGAINST (\''.$string.'\' IN BOOLEAN MODE) DESC LIMIT '.$limitStart.', '.$limitCount; break;
			case 'text':$sql_two = 'SELECT *, a.id as aid, b.id as bid FROM '.prefix.'_events a LEFT JOIN '.prefix.'_events_cat b ON a.cat_id = b.id LEFT JOIN '.prefix.'_users c ON a.author_id = c.id WHERE MATCH (a.announce_description) AGAINST (\''.$string.'\' IN BOOLEAN MODE)'.$cats_id.' and a.active = \'1\' GROUP BY a.id ORDER BY MATCH (a.announce_description) AGAINST (\''.$string.'\' IN BOOLEAN MODE) DESC LIMIT '.$limitStart.', '.$limitCount; break;
			case 'title':$sql_two = 'SELECT *, a.id as aid, b.id as bid FROM '.prefix.'_events a LEFT JOIN '.prefix.'_events_cat b ON a.cat_id = b.id LEFT JOIN '.prefix.'_users c ON a.author_id = c.id WHERE MATCH (a.announce_name) AGAINST (\''.$string.'\' IN BOOLEAN MODE)'.$cats_id.' and a.active = \'1\' GROUP BY a.id ORDER BY MATCH (a.announce_name) AGAINST (\''.$string.'\' IN BOOLEAN MODE) DESC LIMIT '.$limitStart.', '.$limitCount; break;
		}
		
		foreach ($mysql->select($sql_two) as $row_two){ 
			/* print '<pre>';
			print_r ($row_two);
			print '</pre>'; */
			
			$fulllink = checkLinkAvailable('events', 'show')?
				generateLink('events', 'show', array('id' => $row_two['aid'])):
				generateLink('core', 'plugin', array('plugin' => 'events', 'handler' => 'show'), array('id' => $row_two['aid']));
			$catlink = checkLinkAvailable('events', '')?
				generateLink('events', '', array('cat' => $row_two['bid'])):
				generateLink('core', 'plugin', array('plugin' => 'events'), array('cat' => $row_two['bid']));

			
			$tEntry[] = array (
				'aid'					=>	$row_two['aid'],
				'bid'					=>	$row_two['bid'],
				'date'					=>	$row_two['date'],
				'editdate'				=>	$row_two['editdate'],
				'views'					=>	$row_two['views'],
				'announce_name'			=>	$row_two['announce_name'],
				'author'				=>	$row_two['name'],
				'ulink' => checkLinkAvailable('uprofile', 'show')?
									generateLink('uprofile', 'show', array('name' => $row_two['name'], 'id' => $row_two['author_id'])):
									generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('name' => $row_two['name'], 'id' => $row_two['author_id'])),
				'author_id'				=>	$row_two['author_id'],
				'author_email'			=>	$row_two['author_email'],
				'announce_period'		=>	$row_two['announce_period'],
				'announce_description'	=>	preg_replace("/\b(".$text.")(.*?)\b/i", "<span style='color:red; font-weight:bold'>\\0</span>", events_bbcode_p($row_two['announce_description'])),
				'announce_contacts'		=>	$row_two['announce_contacts'],
				'fulllink'				=>	$fulllink,
				'catlink'				=>	$catlink,
				'cat_name'				=>	$row_two['cat_name'],
				'pid'					=>	$row_two['pid'],
				'filepath'				=>	$row_two['filepath'],
			);
			
		}
		
		if( empty($row_two) )
			$output = msg(array("type" => "error", "text" => "По вашему запросу <b>".$get_url."</b> ничего не найдено"), 1, 2);
	}else{
			$res = mysql_query("SELECT * FROM ".prefix."_events_cat ORDER BY id");
			$cats = getCats($res);
			$options = getTree($cats, $row['cat_id'], 0);
			
		//	$tVars['options'] = $options;
			
		/*foreach ($mysql->select('SELECT `id`, `title` FROM `'.prefix.'_forum_forums` ORDER BY `position`') as $row){
			$tEntry[] = array (
				'forum_id' => $row['id'],
				'forum_name' => $row['title'],
			);
		}*/
		
	}

	$tVars = array(
		'entries' => isset($tEntry)?$tEntry:'',
		'options' => isset($options)?$options:'',
		'output'	  =>  $output,
		'get_url'	  =>  $get_url,
		'submit' => (isset($_REQUEST['submit']) && $_REQUEST['submit'])?0:1,
		'pages' => array(
			'true' => (isset($pages) && $pages)?1:0,
			'print' => isset($pages)?$pages:''
		),
		'prevlink' => array(
					'true' => !empty($limitStart)?1:0,
					'link' => str_replace('%page%',
											"$1",
											str_replace('%link%', 
												checkLinkAvailable('events', 'search')?
												generatePageLink(array('pluginName' => 'events', 'pluginHandler' => 'search', 'params' => array('keywords' => $get_url?$get_url:'', 'cat_id' => $cat_id, 'search_in' => $search_in, 'submit'=> 'Отправить'), 'xparams' => array('keywords' => $get_url?$get_url:'', 'cat_id' => $cat_id, 'search_in' => $search_in, 'submit'=> 'Отправить'), 'paginator' => array('page', 1, false)), $prev = floor($limitStart / $limitCount)):
												generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'events', 'handler' => 'search'), 'xparams' => array('keywords' => isset($get_url)?$get_url:'', 'cat_id' => isset($cat_id)?$cat_id:'', 'search_in' => isset($search_in)?$search_in:'', 'submit'=> 'Отправить'), 'paginator' => array('page', 1, false)), 
													$prev = floor((isset($limitStart) && $limitStart)?$limitStart:10 / (isset($limitCount) && $limitCount)?$limitCount:'5')), 
												isset($navigations['prevlink'])?$navigations['prevlink']:''
											)
					),
		),
		'nextlink' => array(
					'true' => ($prev + 2 <= $countPages)?1:0,
					'link' => str_replace('%page%',
											"$1",
											str_replace('%link%', 
												checkLinkAvailable('events', 'search')?
												generatePageLink(array('pluginName' => 'events', 'pluginHandler' => 'search', 'params' => array('keywords' => $get_url, 'cat_id' => $cat_id, 'search_in' => $search_in, 'submit'=> 'Отправить'), 'xparams' => array('keywords' => $get_url?$get_url:'', 'cat_id' => $cat_id, 'search_in' => $search_in, 'submit'=> 'Отправить'), 'paginator' => array('page', 1, false)), $prev+2):
												generatePageLink(array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'events', 'handler' => 'search'), 'xparams' => array('keywords' => $get_url, 'cat_id' => $cat_id, 'search_in' => $search_in, 'submit'=> 'Отправить'), 'paginator' => array('page', 1, false)), $prev+2), 
												isset($navigations['nextlink'])?$navigations['nextlink']:''
											)
					),
		),
	);
	
	//$output = $xt->render($tVars);
	$template['vars']['mainblock'] .= $xt->render($tVars);

}

function show_events($params)
{
global $tpl, $template, $twig, $mysql, $SYSTEM_FLAGS, $config, $userROW, $CurrentHandler, $lang, $TemplateCache;
	$id = isset($params['id'])?abs(intval($params['id'])):abs(intval($_REQUEST['id']));
//	$name = preg_match('/^[a-zA-Z0-9_\xC0-\xD6\xD8-\xF6]+$/', $params['name'])?input_filter_com(convert($params['name'])):'';
	
	$url = pluginGetVariable('events', 'url');
	switch($CurrentHandler['handlerParams']['value']['pluginName'])
	{
		case 'core': 
			if(isset($url) && !empty($url))
			{
				return redirect_events(generateLink('events', 'show', array('id' => $id)));
			}
			break;
		case 'events': 
			if(empty($url))
			{
				return redirect_events(generateLink('core', 'plugin', array('plugin' => 'events', 'handler' => 'show'), array('id' => $id)));
			}
			break;
	}
	
	if( !empty($id) )
		$sql = 'e.id = '.db_squote($id).'';
	else
		redirect_events(link_events());
	
	$SYSTEM_FLAGS['template.main.name'] = pluginGetVariable('events', 'main_template')?pluginGetVariable('events', 'main_template'):'main';
	
	$tpath = locatePluginTemplates(array('show_events'), 'events', pluginGetVariable('events', 'localsource'), pluginGetVariable('events','localskin'));
	$xt = $twig->loadTemplate($tpath['show_events'].'show_events.tpl');
	

	$row = $mysql->record('SELECT e.date as edate, e.expired as eexpired, e.views as eviews, e.announce_name as eannounce_name, e.announce_place as eannounce_place, e.announce_description as eannounce_description, e.city as ecity, u.name as uname, u.xfields_ucity as xfields_ucity, u.xfields_ubirthdate as xfields_ubirthdate, u.avatar as uavatar, u.id as uid, e.id as eid, c.id as cid from '.prefix.'_events e LEFT JOIN '.prefix.'_events_cat c ON e.cat_id = c.id LEFT JOIN '.prefix.'_users u ON e.author_id = u.id WHERE '.$sql.' ORDER BY date DESC LIMIT 1');

	if( ($row['eexpired'] == 1) && ($userROW['id'] != $row['uid']) ) {
		error404();
		return;
	}
	
	$SYSTEM_FLAGS['info']['title']['others'] = $row['announce_name'];
	$SYSTEM_FLAGS['info']['title']['group'] = $lang['events']['name_plugin'];
	$SYSTEM_FLAGS['meta']['description']	= ($row['description'])?$row['description']:pluginGetVariable('events', 'description');
	$SYSTEM_FLAGS['meta']['keywords']		= ($row['keywords'])?$row['keywords']:pluginGetVariable('events', 'keywords');
	
	if(isset($row) && !empty($row))
	{
	
		$cmode = intval(pluginGetVariable('events', 'views_count'));
		if ($cmode > 1) {
			// Delayed update of counters
			$mysql->query("insert into ".prefix."_events_view (id, cnt) values (".db_squote($row['eid']).", 1) on duplicate key update cnt = cnt + 1");
		} else if ($cmode > 0) {
			$mysql->query("update ".prefix."_events set views=views+1 where id = ".db_squote($row['eid']));
		}
	
			// Preload template configuration variables
		templateLoadVariables();

		// Use default <noavatar> file
		// - Check if noavatar is defined on template level
		$tplVars = $TemplateCache['site']['#variables'];
		$noAvatarURL = (isset($tplVars['configuration']) && is_array($tplVars['configuration']) && isset($tplVars['configuration']['noAvatarImage']) && $tplVars['configuration']['noAvatarImage'])?(tpl_url."/".$tplVars['configuration']['noAvatarImage']):(avatars_url."/noavatar.jpg");
	
		$fulllink = checkLinkAvailable('events', 'show')?
			generateLink('events', 'show', array('id' => $row['eid'])):
			generateLink('core', 'plugin', array('plugin' => 'events', 'handler' => 'show'), array('id' => $row['eid']));
		
		$catlink = checkLinkAvailable('events', '')?
			generateLink('events', '', array('cat' => $row['cid'])):
			generateLink('core', 'plugin', array('plugin' => 'events'), array('cat' => $row['cid']));
	
			// If avatar is set
		if ($row['uavatar'] != '') {
			$avatar = avatars_url.'/'.((preg_match('/^'.$row['uid'].'\./', $row['uavatar']))?($row['uid'].'.'):'').$row['uavatar'];
		} else {
			$avatar = $noAvatarURL;
		}
	
		$tVars = array (
			'id' => $row['id'],
			'date' => $row['edate'],
			'views' => $row['eviews']+1,
			'announce_name' => $row['eannounce_name'],
			'announce_place' => $row['eannounce_place'],
			'announce_description' => $row['eannounce_description'],
			'city' => $row['ecity'],
			'uname' => $row['uname'],
			'ucity' => $row['xfields_ucity'],
			'uage' =>  dataDiff($row['xfields_ubirthdate']),
			'wordage' => ruDecline(dataDiff($row['xfields_ubirthdate']),"год","года","лет"),
			'author_link' => checkLinkAvailable('uprofile', 'show')?
								generateLink('uprofile', 'show', array('name' => $row['uname'], 'id' => $row['uid'])):
								generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('name' => $row['uname'], 'id' => $row['uid'])),
			'avatar' => $avatar,
			'fulllink' => $fulllink,
			'catlink' => $catlink,
			'home' => home,
			'full' => str_replace( array( '{url}', '{name}'), array($fulllink, $row['announce_name']), $lang['events']['fulllink']),
			'tpl_url' => home.'/templates/'.$config['theme'],
		);
		
		$template['vars']['mainblock'] .= $xt->render($tVars);
	} else {
		error404();
	}
}

function build_tree($cats,$parent_id,$only_parent = false){
    if(is_array($cats) and isset($cats[$parent_id])){
        $tree = '<ul>';
        if($only_parent==false){
            foreach($cats[$parent_id] as $cat){
                $tree .= '<li><a href="'.$cat['url'].'">'.$cat['cat_name'].'</a> ('.$cat['num'].')';
                $tree .=  build_tree($cats,$cat['id']);
                $tree .= '</li>';
            }
        }elseif(is_numeric($only_parent)){
            $cat = $cats[$parent_id][$only_parent];
            $tree .= '<li>'.$cat['cat_name'].' #'.$cat['id'];
            $tree .=  build_tree($cats,$cat['id']);
            $tree .= '</li>';
        }
        $tree .= '</ul>';
    }
    else return null;
    return $tree;
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


function input_filter_com($text)
{
	$text = trim($text);
	$search = array("<", ">");
	$replace = array("&lt;", "&gt;");
	$text = preg_replace("/(&amp;)+(?=\#([0-9]{2,3});)/i", "&", str_replace($search, $replace, $text));
	return $text;
}

function link_events()
{
	$eventsURL = checkLinkAvailable('events', '')?
					generateLink('events', ''):
					generateLink('core', 'plugin', array('plugin' => 'events'));
	
	return $eventsURL;
}


function link_events_list()
{
	$eventsURL = checkLinkAvailable('events', 'list')?
					generateLink('events', 'list'):
					generateLink('core', 'plugin', array('plugin' => 'events', 'handler' => 'list'));
	
	return $eventsURL;
}

function LoadVariables()
{
	$tpath = locatePluginTemplates(array(':'), 'events', pluginGetVariable('events', 'localsource'));
	return parse_ini_file($tpath[':'].'/variables.ini', true);
	//return parse_ini_file(extras_dir.'/events/tpl/variables.ini', true);
}

function redirect_events($url)
{
	if (headers_sent()) {
		echo "<script>document.location.href='{$url}';</script>\n";
		exit;
	} else {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: {$url}");
		exit;
	}
}

function events_bbcode()
{global $tpl, $twig;
	$tpath = locatePluginTemplates(array('bb_tags'), 'events', pluginGetVariable('events', 'localsource'), pluginGetVariable('events','localskin'));
	$xt = $twig->loadTemplate($tpath['bb_tags'].'bb_tags.tpl');

	return $xt->render($tVars);
}

function events_bbcode_p($text, $replace = true)
{
	$bb = array(
				'[b]'				=>	'[/b]',
				'[i]'				=>	'[/i]',
				'[s]'				=>	'[/s]',
				'[u]'				=>	'[/u]',
	);  
	
	$tag = array(
				'<b>'				=>	'</b>',
				'<i>'				=>	'</i>',
				'<s>'				=>	'</s>',
				'<u>'				=>	'</u>',
	); 
	
	$bb_open = array_keys($bb);
	$bb_close = array_values($bb);
	$tag_open = array_keys($tag);
	$tag_close = array_values($tag);
	
	$text = str_replace(array("\r\n", "\r"), "\n", $text);
	
	if($replace)
	{
		$open_cnt = array();
		
		$text = split_text($text, 200);	 
		
		$text = preg_replace_callback('#\[url=http(s*)://([^\] ]+?)\](.+?)\[/url\]#si', 'UrlLink1', $text);
		$text = preg_replace_callback('#\[url\]http(s*)://(.+?)\[/url\]#si', 'UrlLink2', $text);
		$text = preg_replace_callback('#\[img\]http://([^\] \?]+?)\[/img\]#si', 'ImgLink', $text); 
		
		$text = str_ireplace($bb_open, $tag_open, $text);
		$text = str_ireplace($bb_close, $tag_close, $text);
		$text = str_ireplace($smile_open, $smile_close, $text);
		
		
		
		$text = str_replace("\t", "    ", $text);
		$text = str_replace('  ', '&nbsp;&nbsp;', $text);
		$text = nl2br($text); 
	} else {
		$text = str_replace($bb_open, '', $text);
		$text = str_replace($bb_close, '', $text);
		$text = str_replace($smile_open, '', $text);
	}
	
	return descript($text);         
}

function split_text($text, $width = 90, $break = "\n") 
{
	return preg_replace('#([^\s]{'. $width .'})#s', '$1'. $break , $text);
}

function UrlLink1($match)
{  
	$match[2] = str_replace("\n", "", $match[2]);
	return '<a href="http'. descript($match[1]) .'://'. descript($match[2])
	. '" target="_blanck" >'. descript($match[3]) .'</a>';
}

function UrlLink2($match)
{  
	$match[2] = str_replace("\n", "", $match[2]);
	return '<a href="http'. descript($match[1]) .'://'. descript($match[2])
	. '" target="_blanck" >'. descript($match[2]) .'</a>'; 
}

function ImgLink($match)
{
	$match[1] = str_replace("\n", "", $match[1]);
	return '<img src="http://'. descript($match[1]) .'" border="0" />'; 
}

function descript($text, $striptags = true) {
	$search = array("40","41","58","65","66","67","68","69","70",
		"71","72","73","74","75","76","77","78","79","80","81",
		"82","83","84","85","86","87","88","89","90","97","98",
		"99","100","101","102","103","104","105","106","107",
		"108","109","110","111","112","113","114","115","116",
		"117","118","119","120","121","122"
		);
	$replace = array("(",")",":","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z"
		);
	$entities = count($search);
	for ($i=0; $i < $entities; $i++) {
		$text = preg_replace("#(&\#)(0*".$search[$i]."+);*#si", $replace[$i], $text);
	}
	$text = preg_replace('#(&\#x)([0-9A-F]+);*#si', "", $text);
	$text = preg_replace('#(<[^>]+[/\"\'\s])(onmouseover|onmousedown|onmouseup|onmouseout|onmousemove|onclick|ondblclick|onfocus|onload|xmlns)[^>]*>#iU', ">", $text);
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)script:#iU', '$1=$2nojscript...', $text);
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)javascript:#iU', '$1=$2nojavascript...', $text);
	$text = preg_replace('#([a-z]*)=([\'\"]*)vbscript:#iU', '$1=$2novbscript...', $text);
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU', "$1>", $text);
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU', "$1>", $text);
	if ($striptags) {
		do {
			$thistext = $text;
			$text = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', "", $text);
		} while ($thistext != $text);
	}
	return $text;
}

function plugin_events_cron($isSysCron, $handler)
{
global $tpl, $cron, $mysql, $config, $lang, $parse, $PFILTERS;

	if ($handler == 'events_expired') {
		eventsUpdateExpiredAnnounces();
	}

	if ($handler == 'events_views') {
		eventsUpdateDelayedCounters();
	}

}

// Update expired announces
function eventsUpdateExpiredAnnounces() {
global $tpl, $cron, $mysql, $config, $lang, $parse, $PFILTERS;


		foreach ($mysql->select("select * from ".prefix."_events where expired != 1 AND datediff(FROM_UNIXTIME(date), NOW()) < 0") as $irow) {
			$mysql->query("UPDATE ".prefix."_events SET expired = 1 WHERE id = '".$irow['id']."' ");
		}
		
		generate_entries_cnt_cache(true);
		generate_catz_cache(true);

}

// Update delayed news counters
function eventsUpdateDelayedCounters() {
	global $mysql;

	// Lock tables
	$mysql->query("lock tables ".prefix."_events_view write, ".prefix."_events write");

	// Read data and update counters
	foreach ($mysql->select("select * from ".prefix."_events_view") as $vrec) {
		$mysql->query("update ".prefix."_events set views = views + ".intval($vrec['cnt'])." where id = ".intval($vrec['id']));
	}

	// Truncate view table
	//$mysql->query("truncate table ".prefix."_events_view");
	// DUE TO BUG IN MYSQL - USE DELETE + OPTIMIZE
	$mysql->query("delete from ".prefix."_events_view");
	$mysql->query("optimize table ".prefix."_events_view");

	// Unlock tables
	$mysql->query("unlock tables");
	
	return true;
}

function secure_search_events($text)
{
	$text = convert(trim($text));
	$text = preg_replace("/[^\w\x7F-\xFF\s]/", "", $text);
	return secure_html($text);
}
 
class Lingua_Stem_Ru
{
	public $VERSION = "0.02";
	public $Stem_Caching = 0;
	public $Stem_Cache = array();
	public $VOWEL = '/аеиоуыэюя/';
	public $PERFECTIVEGROUND = '/((ив|ивши|ившись|ыв|ывши|ывшись)|((?<=[ая])(в|вши|вшись)))$/';
	public $REFLEXIVE = '/(с[яь])$/';
	public $ADJECTIVE = '/(ее|ие|ые|ое|ими|ыми|ей|ий|ый|ой|ем|им|ым|ом|его|ого|ему|ому|их|ых|ую|юю|ая|яя|ою|ею)$/';
	public $PARTICIPLE = '/((ивш|ывш|ующ)|((?<=[ая])(ем|нн|вш|ющ|щ)))$/';
	public $VERB = '/((ила|ыла|ена|ейте|уйте|ите|или|ыли|ей|уй|ил|ыл|им|ым|ен|ило|ыло|ено|ят|ует|уют|ит|ыт|ены|ить|ыть|ишь|ую|ю)|((?<=[ая])(ла|на|ете|йте|ли|й|л|ем|н|ло|но|ет|ют|ны|ть|ешь|нно)))$/';
	public $NOUN = '/(а|ев|ов|ие|ье|е|иями|ями|ами|еи|ии|и|ией|ей|ой|ий|й|иям|ям|ием|ем|ам|ом|о|у|ах|иях|ях|ы|ь|ию|ью|ю|ия|ья|я)$/';
	public $RVRE = '/^(.*?[аеиоуыэюя])(.*)$/';
	public $DERIVATIONAL = '/[^аеиоуыэюя][аеиоуыэюя]+[^аеиоуыэюя]+[аеиоуыэюя].*(?<=о)сть?$/';
	
	public function s(&$s, $re, $to)
	{
		$orig = $s;
		$s = preg_replace($re, $to, $s);
		return $orig !== $s;
	}
	
	public function m($s, $re)
	{
		return preg_match($re, $s);
	}
	
	public function stem_word($word)
	{
		$word = strtolower($word);
		$word = strtr($word, 'ё', 'е');
		
		if($this->Stem_Caching && isset($this->Stem_Cache[$word]))
		{
			return $this->Stem_Cache[$word];
		}
		$stem = $word;
		do
		{
			if(!preg_match($this->RVRE, $word, $p)) break;
			$start = $p[1];
			$RV = $p[2];
			if(!$RV) break;
			
			if(!$this->s($RV, $this->PERFECTIVEGROUND, ''))
			{
				$this->s($RV, $this->REFLEXIVE, '');
				
				if($this->s($RV, $this->ADJECTIVE, ''))
				{
					$this->s($RV, $this->PARTICIPLE, '');
				} else {
					if(!$this->s($RV, $this->VERB, ''))
					{
						$this->s($RV, $this->NOUN, '');
					}
				}
			}
			
			$this->s($RV, '/и$/', '');
			
			
			if($this->m($RV, $this->DERIVATIONAL))
			{
				$this->s($RV, '/ость?$/', '');
			}
			
			if(!$this->s($RV, '/ь$/', ''))
			{
				$this->s($RV, '/ейше?/', '');
				$this->s($RV, '/нн$/', 'н');
			}
			
			$stem = $start.$RV;
		} while(false);
			if($this->Stem_Caching) $this->Stem_Cache[$word] = $stem;
			return $stem;
	}
	
	public function stem_caching($parm_ref)
	{
		$caching_level = @$parm_ref['-level'];
		if($caching_level)
		{
			if(!$this->m($caching_level, '/^[012]$/'))
			{
				die(__CLASS__ . "::stem_caching() - Legal values are '0','1' or '2'. '$caching_level' is not a legal value");
			}
			$this->Stem_Caching = $caching_level;
		}
		return $this->Stem_Caching;
	}
	
	public function clear_stem_cache()
	{
		$this->Stem_Cache = array();
	}
}
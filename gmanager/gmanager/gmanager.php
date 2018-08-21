<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

LoadPluginLang('gmanager', 'main', '', '', ':');

register_plugin_page('gmanager','','plugin_gmanager_main');
register_plugin_page('gmanager','gallery','plugin_gmanager_gallery');
add_act('index', 'plugin_gmanager_category');
add_act('index_post', 'plugin_gmanager_widget', 1, 9999);

function plugin_gmanager_main($params)
{
	global $lang, $userROW, $template, $tpl, $mysql, $TemplateCache, $SYSTEM_FLAGS;

	$page = 1;
	if(isset($params['page']) && $params['page']) { $page = intval($params['page']);}
	else if(isset($_REQUEST['page']) && $_REQUEST['page']) { $page = intval($_REQUEST['page']);}

	$SYSTEM_FLAGS['info']['title']['group'] = $lang['gmanager:title'].($page > 1?' - '.$lang['gmanager:page'].' '.$page:'');
	
	if (pluginGetVariable('gmanager', 'if_auto_cash'))
	{
		$cacheFileName = md5('gmanager'.'main'.$page).'.txt';
		$cacheData = cacheRetrieveFile($cacheFileName, pluginGetVariable('gmanager', 'cash_time'), 'gmanager');
		if ($cacheData != false) {
			$template['vars']['mainblock'] .= $cacheData;
			return true;
		}
	}
	
	@include_once root.'includes/classes/upload.class.php';
	$fmanager = new file_managment();
	$tpath = locatePluginTemplates(array('main', 'main.row', 'main.cell'), 'gmanager', pluginGetVariable('gmanager', 'locate_tpl'), pluginGetVariable('gmanager', 'skin'));
	$tpl_url = $tpath['url:main'];
	
	$fmanager->get_limits('image');
	$row_count = 0;
	$cell = 0;
	$max_row = pluginGetVariable('gmanager', 'main_row');
	$max_cell = pluginGetVariable('gmanager', 'main_cell');
	$entries_row = '';
	$entries_cell = '';
	
	$limit = '';
	if ($max_row && $max_cell)
		$limit = 'limit '.($page - 1) * $max_row * $max_cell.', '.$max_row * $max_cell;
	
	foreach($mysql->select('select * from '.prefix.'_gmanager where if_active=1 order by iorder '.$limit) as $row)
	{
		$icon = $mysql->record('select name, folder from '.prefix.'_images where `id`='.db_squote($row['id_icon']).' limit 1');
		$folder		=	$icon['folder']?$icon['folder'].'/':'';
		$fileurl	=	$fmanager->uname.'/'.$folder.$icon['name'];
		$thumburl	=	file_exists($fmanager->dname.$folder.'/thumb/'.$icon['name'])?$fmanager->uname.'/'.$folder.'/thumb/'.$icon['name']:$fileurl;

		$pvars['regx']['/\[empty\](.*?)\[\/empty\]/si'] = '';
		$pvars['regx']['/\[not_empty\](.*?)\[\/not_empty\]/si'] = '$1';
		
		$pvars['vars']['tpl_url'] = $tpl_url;
		$pvars['vars']['url_gallery'] = generatePluginLink('gmanager', 'gallery', array('id' => $row['id'], 'name' => $row['name']));
		
		$pvars['vars']['id'] = $row['id'];
		$pvars['vars']['icon'] = $fileurl;
		$pvars['vars']['icon_thumb'] = $thumburl;
		$pvars['vars']['name'] = $row['name'];
		$pvars['vars']['title'] = $row['title'];
		$pvars['vars']['description'] = $row['description'];
		$pvars['vars']['keywords'] = $row['keywords'];
		
		$tpl->template('main.cell', $tpath['main.cell']);
		$tpl->vars('main.cell', $pvars);
		$entries_cell .=  $tpl->show('main.cell');

		if ($max_cell && $cell >= $max_cell - 1)
		{
			$ppvars['vars']['tpl_url'] = $tpl_url;
			$ppvars['vars']['entries'] = $entries_cell;
			
			$tpl->template('main.row', $tpath['main.row']);
			$tpl->vars('main.row', $ppvars);
			$entries_row .=  $tpl->show('main.row');
			
			$entries_cell = '';
			$cell = 0;
			$row_count ++;
			if ($max_row && $row_count >=  $max_row)
				break;
		}
		else
		{
			$cell ++;
		}
	}
	if ($cell)
	{
		$tcount = $max_cell?$max_cell - $cell:0;
		unset($pvars);
		for ($i = 0; $i < $tcount; $i ++)
		{
			$pvars['vars']['tpl_url'] = $tpl_url;
			$pvars['regx']['/\[empty\](.*?)\[\/empty\]/si'] = '$1';
			$pvars['regx']['/\[not_empty\](.*?)\[\/not_empty\]/si'] = '';
			$tpl->template('main.cell', $tpath['main.cell']);
			$tpl->vars('main.cell', $pvars);
			$entries_cell .=  $tpl->show('main.cell');
		}
		$ppvars['vars']['tpl_url'] = $tpl_url;
		$ppvars['vars']['entries'] = $entries_cell;
		$tpl->template('main.row', $tpath['main.row']);
		$tpl->vars('main.row', $ppvars);
		$entries_row .=  $tpl->show('main.row');
	}

	$tvars['vars']['pages'] = '';

	if (pluginGetVariable('gmanager', 'main_page') && $max_row && $max_cell)
	{
		$count = 0;
		if (is_array($pcnt = $mysql->record('select count(*) as cnt from '.prefix.'_gmanager where if_active=1')))
			$count = $pcnt['cnt'];
		$page_count = ceil($count / $max_row / $max_cell);
		if ($page_count > 1) {
			$paginationParams = checkLinkAvailable('gmanager', '')?array('pluginName' => 'gmanager', 'pluginHandler' => '', 'params' => array(), 'xparams' => array(), 'paginator' => array('page', 0, false)):array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'gmanager'), 'xparams' => array(), 'paginator' => array('page', 1, false));
			templateLoadVariables(true); 
			$navigations = $TemplateCache['site']['#variables']['navigation'];
			$tvars['vars']['pages'] .= generatePagination($page, 1, $page_count, 10, $paginationParams, $navigations);
		}
	}
	
	$tvars['vars']['tpl_url'] = $tpl_url;
	$tvars['vars']['entries'] = $entries_row;

	$tpl->template('main', $tpath['main']);
	$tpl->vars('main', $tvars);
	$output = $tpl->show('main');
	$template['vars']['mainblock'] .= $output;
	if (pluginGetVariable('gmanager', 'if_auto_cash')) cacheStoreFile($cacheFileName, $output, 'gmanager');
}

function plugin_gmanager_gallery($params)
{
	global $lang, $userROW, $template, $tpl, $mysql, $TemplateCache, $SYSTEM_FLAGS;

	$page = 1;
	if(isset($params['page']) && $params['page']) { $page = intval($params['page']);}
	else if(isset($_REQUEST['page']) && $_REQUEST['page']) { $page = intval($_REQUEST['page']);}
	$id = 0;
	if(isset($params['id']) && $params['id']) { $id = intval($params['id']);}
	else if(isset($_REQUEST['id']) && $_REQUEST['id']) { $id = intval($_REQUEST['id']);}
	$name = '';
	if(isset($params['name']) && $params['name']) { $name = trim(secure_html(convert($params['name'])));}
	else if(isset($_REQUEST['name']) && $_REQUEST['name']) { $name = trim(secure_html(convert($_REQUEST['name'])));}
	if (!$name && !$id) return false;
	$where = 'where id='.db_squote($id);
	if (!$id)
		$where = 'where name='.db_squote($name);
	$gallery = $mysql->record('select * from '.prefix.'_gmanager '.$where.' limit 1');
	if (!is_array($gallery)) return false;
	$id = $gallery['id'];
	$name = $gallery['name'];
	
	$SYSTEM_FLAGS['info']['title']['group'] = $lang['gmanager:title'].' '.$gallery['title'].($page > 1?' - '.$lang['gmanager:page'].' '.$page:'');
	if (pluginGetVariable('gmanager', 'if_description')) $SYSTEM_FLAGS['meta']['description'] = $gallery['description'];
	if (pluginGetVariable('gmanager', 'if_keywords')) $SYSTEM_FLAGS['meta']['keywords'] = $gallery['keywords'];

	if (pluginGetVariable('gmanager', 'if_auto_cash'))
	{
		$cacheFileName = md5('gmanager'.$id.$name.$page).'.txt';
		$cacheData = cacheRetrieveFile($cacheFileName, pluginGetVariable('gmanager', 'cash_time'), 'gmanager');
		if ($cacheData != false) {
			$template['vars']['mainblock'] .= $cacheData;
			return true;
		}
	}

	@include_once root.'includes/classes/upload.class.php';
	$fmanager = new file_managment();

	if ($gallery['skin']) $skin = $gallery['skin'];
	else $skin = pluginGetVariable('gmanager', 'skin');
	
	$tpath = locatePluginTemplates(array('gallery', 'gallery.row', 'gallery.cell'), 'gmanager', pluginGetVariable('gmanager', 'locate_tpl'), $skin);
	$tpl_url = $tpath['url:gallery'];
	
	$fmanager->get_limits('image');
	$row_count = 0;
	$cell = 0;
	$max_row = $gallery['count_rows'];
	$max_cell = $gallery['count_cells'];
	$entries_row = '';
	$entries_cell = '';
	
	$limit = '';
	if ($max_row && $max_cell)
		$limit = 'limit '.($page - 1) * $max_row * $max_cell.', '.$max_row * $max_cell;
	
	foreach($mysql->select('select name, description from '.prefix.'_images where folder='.db_squote($name).' order by `date` '.$limit) as $row)
	{
		$fileurl	=	$fmanager->uname.'/'.$name.'/'.$row['name'];
		$thumburl	=	file_exists($fmanager->dname.$name.'/thumb/'.$row['name'])?$fmanager->uname.'/'.$name.'/'.'thumb/'.$row['name']:$fileurl;

		$pvars['regx']['/\[empty\](.*?)\[\/empty\]/si'] = '';
		$pvars['regx']['/\[not_empty\](.*?)\[\/not_empty\]/si'] = '$1';
		
		$pvars['vars']['tpl_url'] = $tpl_url;
		
		$pvars['vars']['url_image'] = $fileurl;
		$pvars['vars']['url_image_thumb'] = $thumburl;
		
		$pvars['vars']['name'] = $row['name'];
		$pvars['vars']['description'] = $row['description'];
		
		$tpl->template('gallery.cell', $tpath['gallery.cell']);
		$tpl->vars('gallery.cell', $pvars);
		$entries_cell .=  $tpl->show('gallery.cell');

		if ($max_cell && $cell >= $max_cell - 1)
		{
			$ppvars['vars']['tpl_url'] = $tpl_url;
			$ppvars['vars']['entries'] = $entries_cell;
			
			$tpl->template('gallery.row', $tpath['gallery.row']);
			$tpl->vars('gallery.row', $ppvars);
			$entries_row .=  $tpl->show('gallery.row');
			
			$entries_cell = '';
			$cell = 0;
			$row_count ++;
			if ($max_row && $row_count >=  $max_row)
				break;
		}
		else
		{
			$cell ++;
		}
	}
	if ($cell)
	{
		$tcount = $max_cell?$max_cell - $cell:0;
		unset($pvars);
		for ($i = 0; $i < $tcount; $i ++)
		{
			$pvars['vars']['tpl_url'] = $tpl_url;
			$pvars['regx']['/\[empty\](.*?)\[\/empty\]/si'] = '$1';
			$pvars['regx']['/\[not_empty\](.*?)\[\/not_empty\]/si'] = '';
			$tpl->template('gallery.cell', $tpath['gallery.cell']);
			$tpl->vars('gallery.cell', $pvars);
			$entries_cell .=  $tpl->show('gallery.cell');
		}
		$ppvars['vars']['tpl_url'] = $tpl_url;
		$ppvars['vars']['entries'] = $entries_cell;
		$tpl->template('gallery.row', $tpath['gallery.row']);
		$tpl->vars('gallery.row', $ppvars);
		$entries_row .=  $tpl->show('gallery.row');
	}

	$tvars['vars']['pages'] = '';
	if ($gallery['if_number'] && $max_row && $max_cell)
	{
		$count = 0;
		if (is_array($pcnt = $mysql->record('select count(*) as cnt from '.prefix.'_images where folder='.db_squote($name))))
			$count = $pcnt['cnt'];
		
		$page_count = ceil($count / $max_row / $max_cell);
		if ($page_count > 1) {
			$paginationParams = checkLinkAvailable('gmanager', 'gallery')?array('pluginName' => 'gmanager', 'pluginHandler' => 'gallery', 'params' => array('id' => $id, 'name' => $name), 'xparams' => array(), 'paginator' => array('page', 0, false)):array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'gmanager', 'handler' => 'gallery'), 'xparams' => array('id' => $id, 'name' => $name), 'paginator' => array('page', 1, false));
			templateLoadVariables(true); 
			$navigations = $TemplateCache['site']['#variables']['navigation'];
			$tvars['vars']['pages'] .= generatePagination($page, 1, $page_count, 10, $paginationParams, $navigations);
		}
	}
	
	$tvars['vars']['tpl_url'] = $tpl_url;
	$tvars['vars']['entries'] = $entries_row;
	$tvars['vars']['gallery_title'] = $gallery['title'];
	$tvars['vars']['url_main'] = generatePluginLink('gmanager', '', array());
	$tvars['vars']['description'] = $gallery['description'];
	$tvars['vars']['keywords'] = $gallery['keywords'];

	$tpl->template('gallery', $tpath['gallery']);
	$tpl->vars('gallery', $tvars);
	$output = $tpl->show('gallery');
	$template['vars']['mainblock'] .= $output;
	if (pluginGetVariable('gmanager', 'if_auto_cash')) cacheStoreFile($cacheFileName, $output, 'gmanager');
}

function plugin_gmanager_category($params)
{
	global $tpl, $lang, $mysql, $template;
	
	if (pluginGetVariable('gmanager', 'if_auto_cash'))
	{
		$cacheFileName = md5('gmanager'.'category').'.txt';
		$cacheData = cacheRetrieveFile($cacheFileName, pluginGetVariable('gmanager', 'cash_time'), 'gmanager');
		if ($cacheData != false) {
			$template['vars']['plugin_gmanager_category'] .= $cacheData;
			return true;
		}
	}

	$tpath = locatePluginTemplates(array('category', 'category.row'), 'gmanager', pluginGetVariable('gmanager', 'locate_tpl'));
	$tpl_url = $tpath['url:category'];
	
	$output = '';
	foreach($mysql->select('select '.prefix.'_gmanager.id, '.prefix.'_gmanager.name, '.prefix.'_gmanager.title, '.prefix.'_gmanager.description, (select count(*) from '.prefix.'_images where folder='.prefix.'_gmanager.name) as count from '.prefix.'_gmanager where '.prefix.'_gmanager.if_active=1 order by '.prefix.'_gmanager.iorder') as $row)
	{
		$pvars['vars']['tpl_url'] = $tpl_url;
		$pvars['vars']['url_gallery'] = generatePluginLink('gmanager', 'gallery', array('id' => $row['id'], 'name' => $row['name']));

		$pvars['vars']['id'] = $row['id'];
		$pvars['vars']['name'] = $row['name'];
		$pvars['vars']['title'] = $row['title'];
		$pvars['vars']['description'] = $row['description'];
		$pvars['vars']['count'] = $row['count'];
		
		$tpl->template('category.row', $tpath['category.row']);
		$tpl->vars('category.row', $pvars);
		$output .= $tpl->show('category.row');
	}

	$tvars['vars']['tpl_url'] = $tpl_url;
	$tvars['vars']['entries'] = $output;
	$tvars['vars']['url_main'] = generatePluginLink('gmanager', null);
	
	$tpl->template('category', $tpath['category']);
	$tpl->vars('category', $tvars);
	$template['vars']['plugin_gmanager_category'] = $tpl->show('category');
	if (pluginGetVariable('gmanager', 'if_auto_cash')) cacheStoreFile($cacheFileName, $template['vars']['plugin_gmanager_category'], 'gmanager');
}

function plugin_gmanager_widget($params){
	global $tpl, $lang, $mysql, $template;

	$widgets = pluginGetVariable('gmanager', 'widgets');
	
	if (!is_array($widgets)) return;
	
	foreach($widgets as $id=>$widg){
		if (!$widg['if_active']){
			if (!isset($template['vars'][$widg['name']])) $template['vars'][$widg['name']] = '';
			continue;
		}
		
		if (pluginGetVariable('gmanager', 'if_auto_cash'))
		{
			$cacheFileName = md5('gmanagerwidget'.$id).'.txt';
			$cacheData = cacheRetrieveFile($cacheFileName, pluginGetVariable('gmanager', 'cash_time'), 'gmanager');
			if ($cacheData != false) {
				$template['vars'][$widg['name']] .= $cacheData;
				continue;
			}
		}

		@include_once root.'includes/classes/upload.class.php';
		$fmanager = new file_managment();

		if ($widg['skin']) $skin = $widg['skin'];
		else $skin = pluginGetVariable('gmanager', 'skin');
		$tpath = locatePluginTemplates(array('widget', 'widget.row', 'widget.cell'), 'gmanager', pluginGetVariable('gmanager', 'locate_tpl'), $skin);
		$tpl_url = $tpath['url:widget'];
		
		$fmanager->get_limits('image');
		$row_count = 0;
		$cell = 0;
		$max_row = $widg['rows'];
		$max_cell = $widg['cells'];
		$entries_row = '';
		$entries_cell = '';
		
		$limit = '';
		if ($max_row && $max_cell)
			$limit = 'limit '.$max_row * $max_cell;
		

		$where = '';
		if ($widg['galery']){
			$t_gallery_array = explode(',', $widg['galery']);
			$t_gallery = '';
			foreach($t_gallery_array as $gal){
				if ($t_gallery) $t_gallery .= ', ';
				$t_gallery .= '\''.trim($gal).'\'';
			}
			$where .= ' where folder in ('.$t_gallery.')';
		}
		
		if ($widg['if_rand']){
			$image_key = $mysql->select('select id from '.prefix.'_images '.$where);
			if (count($image_key)){
				shuffle($image_key);
				if ($limit) $image_key = array_slice($image_key, 0, $max_row * $max_cell);
				if ($where) $where .= ' and ';
				else $where = ' where ';
				$t_key_list = '';
				foreach($image_key as $img){
					if ($t_key_list) $t_key_list .= ', ';
					$t_key_list .= $img['id'];
				}
				$where .= ' id in ('.$t_key_list.')';
			}
		}

		foreach($mysql->select('select name, folder, description from '.prefix.'_images '.$where.' '.$limit) as $row)
		{
			$fileurl	=	$fmanager->uname.'/'.$row['folder'].'/'.$row['name'];
			$thumburl	=	file_exists($fmanager->dname.$row['folder'].'/thumb/'.$row['name'])?$fmanager->uname.'/'.$row['folder'].'/'.'thumb/'.$row['name']:$fileurl;

			$pvars['regx']['/\[empty\](.*?)\[\/empty\]/si'] = '';
			$pvars['regx']['/\[not_empty\](.*?)\[\/not_empty\]/si'] = '$1';
			
			$pvars['vars']['tpl_url'] = $tpl_url;
			
			$pvars['vars']['url_image'] = $fileurl;
			$pvars['vars']['url_image_thumb'] = $thumburl;
			
			$pvars['vars']['name'] = $row['name'];
			$pvars['vars']['description'] = $row['description'];
			
			$tpl->template('widget.cell', $tpath['widget.cell']);
			$tpl->vars('widget.cell', $pvars);
			$entries_cell .=  $tpl->show('widget.cell');

			if ($max_cell && $cell >= $max_cell - 1)
			{
				$ppvars['vars']['tpl_url'] = $tpl_url;
				$ppvars['vars']['entries'] = $entries_cell;
				
				$tpl->template('widget.row', $tpath['widget.row']);
				$tpl->vars('widget.row', $ppvars);
				$entries_row .=  $tpl->show('widget.row');
				
				$entries_cell = '';
				$cell = 0;
				$row_count ++;
				if ($max_row && $row_count >=  $max_row)
					break;
			}
			else
			{
				$cell ++;
			}
		}
		if ($cell)
		{
			$tcount = $max_cell?$max_cell - $cell:0;
			unset($pvars);
			for ($i = 0; $i < $tcount; $i ++)
			{
				$pvars['vars']['tpl_url'] = $tpl_url;
				$pvars['regx']['/\[empty\](.*?)\[\/empty\]/si'] = '$1';
				$pvars['regx']['/\[not_empty\](.*?)\[\/not_empty\]/si'] = '';
				$tpl->template('widget.cell', $tpath['widget.cell']);
				$tpl->vars('widget.cell', $pvars);
				$entries_cell .=  $tpl->show('widget.cell');
			}
			$ppvars['vars']['tpl_url'] = $tpl_url;
			$ppvars['vars']['entries'] = $entries_cell;
			$tpl->template('widget.row', $tpath['widget.row']);
			$tpl->vars('widget.row', $ppvars);
			$entries_row .=  $tpl->show('widget.row');
		}

		$tvars['vars']['tpl_url'] = $tpl_url;
		$tvars['vars']['entries'] = $entries_row;
		$tvars['vars']['widget_title'] = $widg['title'];
		$tvars['vars']['url_main'] = generatePluginLink('gmanager', '', array());

		$tpl->template('widget', $tpath['widget']);
		$tpl->vars('widget', $tvars);
		$output = $tpl->show('widget');
		$template['vars'][$widg['name']] .= $output;
		if (pluginGetVariable('gmanager', 'if_auto_cash')) cacheStoreFile($cacheFileName, $output, 'gmanager');
	}
}
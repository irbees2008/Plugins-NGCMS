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
	exit('HAL');
}

$lang = LoadPluginLang('quotes', 'index');

register_plugin_page('quotes','','plugin_show_quotes');
register_plugin_page('quotes','add','plugin_add_quotes');
register_plugin_page('quotes','url','screen_rating_quotes');
register_plugin_page('quotes','rss','plugin_rss_quotes');
register_plugin_page('quotes','show','plugin_fullshow_quotes');

function plugin_show_quotes($params)
{global $tpl, $template, $SUPRESS_TEMPLATE_SHOW, $SYSTEM_FLAGS, $mysql, $config, $parse, $userROW, $TemplateCache, $CurrentHandler;
	
	//$SUPRESS_TEMPLATE_SHOW = 1;
	
	if($CurrentHandler['params']['page'] == true)
	{
		$title = " : {$CurrentHandler['params']['page']} Страница";
	}
	
	if($_REQUEST['page'] == true)
	{
		$title = " : ${_REQUEST['page']} Страница";
	}
	
	$sort = isset($params['sort'])?$params['sort']:$_REQUEST['sort'];
	
	switch ($sort) {
		case 'top': $sorting = 'rating desc'; $top_low = array('sort' => 'top'); break;
		case 'low': $sorting = 'rating asc'; $top_low = array('sort' => 'low'); break;
		default: $sorting = 'postdate DESC'; $top_low = array();
	}
	
	$SYSTEM_FLAGS['info']['title']['group'] = "Цитатник{$title}";
	$SYSTEM_FLAGS['meta']['description'] = pluginGetVariable('quotes', 'description');
	$SYSTEM_FLAGS['meta']['keywords'] = pluginGetVariable('quotes', 'keywords');
	
	$page = isset($params['page'])?intval($params['page']):intval($_REQUEST['page']);
	$cacheFileName = md5('quotes'.$config['home_url'].$config['theme'].$config['default_lang'])."{$page}.txt";
	
	if (pluginGetVariable('quotes', 'cache')) {
		$cacheData = cacheRetrieveFile($cacheFileName, pluginGetVariable('quotes','cacheExpire'), 'quotes');
		if ($cacheData != false) {
			$template['vars']['mainblock'] = $cacheData;
			return;
		}
	}
	
	$res = $mysql->result("select count(*) from ".prefix."_quotes where approve = 1");
	
	$num = pluginGetVariable('quotes', 'count');
	
	$limitStart = 0;
	$limitCount = $num;
	$multi_scount = intval($num);
	if (($multi_scount > 0) && ($res > $multi_scount)) {
		$pageCount = ceil($res / $multi_scount);
		if ($page < 1) { $page = 1; }
			$limitCount = intval($num);
			$limitStart = ($page-1) * intval($num);
	}
	
	$tvars['vars']['pages']  = '';
	if ($pageCount > 1) {
		$paginationParams = checkLinkAvailable('quotes', '')?
			array('pluginName' => 'quotes', 'pluginHandler' => '', 'params' => $top_low, 'xparams' => array(), 'paginator' => array('page', 0, false)):
			array('pluginName' => 'core', 'pluginHandler' => 'plugin', 'params' => array('plugin' => 'quotes'), 'xparams' => $top_low, 'paginator' => array('page', 1, false));

		templateLoadVariables(true); 
		$navigations = $TemplateCache['site']['#variables']['navigation'];
		
		$tvars['vars']['pages'] .= generatePagination($page, 1, $pageCount, 10, $paginationParams, $navigations);
	}
	
	$tpath = locatePluginTemplates(array('showquotes', 'shortquotes'), 'quotes', pluginGetVariable('quotes', 'localsource'));
	
	foreach ($mysql->select("select id, content, rating, approve, postdate, author, author_id from ".prefix."_quotes where approve = 1 order by ${sorting} LIMIT ".abs(intval($limitStart)).", ".abs(intval($limitCount))) as $row)
	{
		$content = $row['content'];
		if ($config['blocks_for_reg'])		{ $content = userblocks_quotes_page($content); }
		if ($config['use_htmlformatter'])	{ $content = $parse -> htmlformatter($content); }
		if ($config['use_bbcodes'])			{ $content = $parse -> bbcodes($content); }
		if ($config['use_smilies'])			{ $content = $parse -> smilies($content); }
		
		$userlink = checkLinkAvailable('uprofile', 'show')?
				generateLink('uprofile', 'show', array('name' => $row['author'], 'id' => $row['author_id'])):
				generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('name' => $row['author'], 'id' => $row['author_id']));
		
		$showlink = checkLinkAvailable('quotes', 'show')?
			generateLink('quotes', 'show', array('id' => $row['id'])):
			generateLink('core', 'plugin', array('plugin' => 'quotes', 'handler' => 'show'), array('id' => $row['id']));
		
		$pvars['vars'] = array (
			'content' => $content,
			'rating' => rating_show_quotes($row['id'], $row['rating']),
			'date' => LangDate(pluginGetVariable('quotes', 'date'), $row['postdate']),
			'author' => "<a href='${userlink}'>${row['author']}</a>",
			'show' => "<a href='${showlink}'>Ccылка на цитату</a>"
		);
		
		$tpl -> template('shortquotes', $tpath['shortquotes']);
		$tpl -> vars('shortquotes', $pvars);
		$output .= $tpl -> show('shortquotes');
	}
	
	$toplink = checkLinkAvailable('quotes', '')?
			generateLink('quotes', '', array('sort' => 'top')):
			generateLink('core', 'plugin', array('plugin' => 'quotes'), array('sort' => 'top'));
		
	$lowlink = checkLinkAvailable('quotes', '')?
			generateLink('quotes', '', array('sort' => 'low')):
			generateLink('core', 'plugin', array('plugin' => 'quotes'), array('sort' => 'low'));
	
	$addlink = checkLinkAvailable('quotes', 'add')?
		generateLink('quotes', 'add'):
		generateLink('core', 'plugin', array('plugin' => 'quotes', 'handler' => 'add'));
	
	$tvars['vars']['top'] = "<a href='${toplink}'>Лучшие цитаты</a>";
	$tvars['vars']['low'] = "<a href='${lowlink}'>Плохие цитаты</a>";
	$tvars['vars']['add'] = "<a href='${addlink}'>Добавить цитату</a>";
	
	if ($output != null)
	{
		$tvars['vars']['short'] = $output;
	} else {
		return error404();
	}
	
	$tpl -> template('showquotes', $tpath['showquotes']);
	$tpl -> vars('showquotes', $tvars);
	$result = $tpl -> show('showquotes');
	$template['vars']['mainblock'] = $result;
	if (pluginGetVariable('quotes','cache'))
	{
		cacheStoreFile($cacheFileName, $result, 'quotes');
	}
}

function plugin_fullshow_quotes($params)
{global $tpl, $template, $SUPRESS_TEMPLATE_SHOW, $SYSTEM_FLAGS, $mysql, $config, $parse, $userROW;
	
	//$SUPRESS_TEMPLATE_SHOW = 1;
	
	$id = isset($params['id'])?abs(intval($params['id'])):abs(intval($_REQUEST['id']));
	
	$quoteslink = checkLinkAvailable('quotes', '')?
		generateLink('quotes', ''):
		generateLink('core', 'plugin', array('plugin' => 'quotes'));
	
	if ($id == null)
	{
		header("HTTP/1.0 404 Not Found");
		return announcement_quotes('Не передан id цитаты', $quoteslink, 1);
	}
	
	$SYSTEM_FLAGS['info']['title']['group'] = "Цитата №${id}";
	$SYSTEM_FLAGS['meta']['description'] = pluginGetVariable('quotes', 'description');
	$SYSTEM_FLAGS['meta']['keywords'] = pluginGetVariable('quotes', 'keywords');
	
	$tpath = locatePluginTemplates(array('fullquotes'), 'quotes', pluginGetVariable('quotes', 'localsource'));
	
	foreach ($mysql->select("select id, content, rating, approve, postdate, author, author_id from ".prefix."_quotes where approve = 1 and id = ".db_squote("{$id}")) as $row)
	{
		$content = $row['content'];
		if ($config['blocks_for_reg'])		{ $content = userblocks_quotes_page($content); }
		if ($config['use_htmlformatter'])	{ $content = $parse -> htmlformatter($content); }
		if ($config['use_bbcodes'])			{ $content = $parse -> bbcodes($content); }
		if ($config['use_smilies'])			{ $content = $parse -> smilies($content); }
		
		$userlink = checkLinkAvailable('uprofile', 'show')?
				generateLink('uprofile', 'show', array('name' => $row['author'], 'id' => $row['author_id'])):
				generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('name' => $row['author'], 'id' => $row['author_id']));
		
		$pvars['vars'] = array (
			'content' => $content,
			'rating' => rating_show_quotes($row['id'], $row['rating']),
			'date' => LangDate(pluginGetVariable('quotes', 'date'), $row['postdate']),
			'author' => "<a href='${userlink}'>${row['author']}</a>",
			'title' => "Цитата №${id}"
		);
		
		$tpl -> template('fullquotes', $tpath['fullquotes']);
		$tpl -> vars('fullquotes', $pvars);
		$output = $tpl -> show('fullquotes');
	}
	
	if ($output == null)
	{
		return error404();
	} else {
		$template['vars']['mainblock'] = $output;
	}
}

function plugin_add_quotes()
{global $tpl, $template, $SYSTEM_FLAGS, $SUPRESS_TEMPLATE_SHOW, $userROW, $mysql, $config;
	
	//$SUPRESS_TEMPLATE_SHOW = 1;
	
	if (!is_array($userROW))
	{
		header("HTTP/1.0 404 Not Found");
		return announcement_quotes('Для самых умных дарога лишь на сайт', home, 0);
	}
	
	$SYSTEM_FLAGS['info']['title']['group'] = 'Добавить сообщение в цитатник';
	
	$num_max = pluginGetVariable('quotes','max_char');
	$time['time'] = time() + ($config['date_adjust'] * 60);
	
	$content = secure_html(convert($_REQUEST['content']));
	$data['content'] = str_replace("\r\n", "\n", $content);
	
	if($num_max == true)
	{
		$data['content'] = substr($data['content'],0,$num_max);
	}
	
	$addlink = checkLinkAvailable('quotes', '')?
		generateLink('quotes', ''):
		generateLink('core', 'plugin', array('plugin' => 'quotes'));
	
	if($userROW['status'] == 1)
	{
		$approve = 1;
	} else {
		$approve = 0;
	}
	
	if (isset($_REQUEST['addpost']))
	{
		if (empty($data['content'])) { $error_flag = true; $error_text[] = msg(array("type" => "error", "text" => "Вы не добавили цитату"), 0, 2); }
		if (!empty($data['content']))
		{
			$mysql->query("insert into ".prefix."_quotes
			(postdate, content, approve, author, author_id)
			values
			('${time['time']}', ".db_squote($data['content']).", '${approve}', '${userROW['name']}', '${userROW['id']}')
			");
		return announcement_quotes('Данные внесены', $addlink, 2);
		}
	}
	
	$tvars['vars']['error'] = '';
	if ($error_flag)
	{
		$tvars['vars']['error'] = implode('<br />', $error_text);
	}
	
	$tvars['vars']['smilies'] = InsertSmilies('comments', 10);
	$tvars['vars']['bbcodes'] = BBCodes();
	$tvars['regx']["'\[latent\](.*?)\[/latent\]'si"] = ($num_max == 0)?'':'$1';
	$tvars['vars']['max_char'] = $num_max;
	
	$tpath = locatePluginTemplates(array('addquotes'), 'quotes', pluginGetVariable('quotes', 'localsource'));
	
	$tpl -> template('addquotes', $tpath['addquotes']);
	$tpl -> vars('addquotes', $tvars);
	$template['vars']['mainblock'] = $tpl -> show('addquotes');
}

function plugin_rss_quotes()
{global $template, $config, $SUPRESS_TEMPLATE_SHOW, $SUPRESS_MAINBLOCK_SHOW, $mysql, $SYSTEM_FLAGS;
	
	$SUPRESS_TEMPLATE_SHOW = 1;
	$SUPRESS_MAINBLOCK_SHOW = 1;
	
	$SYSTEM_FLAGS['info']['title']['group'] = 'RSS из цитатника';
	
	$output = '<?xml version="1.0" encoding="windows-1251"?>'."\n";
	$output .= '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/">'."\n";
	$output .= "<channel>\n";
	$output .= "<title>RSS цитатника</title>\n";
	$output .= "<link><![CDATA[".$config['home_url']."]]></link>\n";
	$output .= "<language>ru</language>\n";
	$output .= "<description><![CDATA[".$config['description']."]]></description>\n";
	$output .= "<generator><![CDATA[Plugin rss_quotes (beta7) // Next Generation CMS (".engineVersion.")]]></generator>\n";
	
	foreach ($mysql->select("select content, postdate from ".prefix."_quotes") as $row)
	{
		$output .= "  <item>\n";
		$output .= "   <title>Цитата</title>\n";
		$output .= "   <link></link>\n";
		$output .= "   <description><![CDATA['${row['content']}']]></description>\n";
		$output .= "   <pubDate>".strftime('%a, %d %b %Y %H:%M:%S GMT',$row['postdate'])."</pubDate>\n";
		$output .= "  </item>\n";
	}
	$output .= " </channel>\n</rss>\n";
	print $output;
}

function update_rating_quotes()
{global $mysql, $tpl, $userROW;
	
	$id = intval($_REQUEST['id']);
	$rating = intval($_REQUEST['rating']);
	
	if( pluginGetVariable('quotes', 'users_rating') == true )
	{
		if (!is_array($userROW))
		{
			return 'Вы должны зарегистрироваться';
		}
		if( flood_quotes(false, $id) )
		{
			return 'Вы уже голосовали';
		}
		flood_quotes(true, $id);
	}
	
	switch ($rating)
	{
		case 1: $rating = 1; break;
		case -1: $rating = -1; break;
		default: return 'Не допустимое значение';
	}
	
	session_start();
	
	if ( ($_SESSION['id'][$id] == true) or ($_COOKIE['rating_quotes'.$id] == true) )
	{
		return 'Вы уже голосовали';
	}
	
	$_SESSION['id'][$id] = $id;
	@setcookie('rating_quotes'.$id, 'voted', (time() + 31526000), '/');
	$mysql->query("update ".prefix."_quotes set rating = rating + {$rating} where id = ".db_squote("{$id}"));
	$data = $mysql->record("select rating from ".prefix."_quotes where id = ".db_squote("{$id}"));
	
	$tvars['vars']['rating'] = $data['rating'];
	$tvars['vars']['admin_url'] = admin_url;
	
	switch ($data['rating'])
	{
		case 0: $tvars['vars']['color'] = 'zero'; break;
		case $data['rating'] > 0: $tvars['vars']['color'] = 'plus'; break;
		case $data['rating'] < 0: $tvars['vars']['color'] = 'less'; break;
	}
	
	$tpath = locatePluginTemplates(array('rating', ':rating.css'), 'quotes', pluginGetVariable('quotes', 'localsource'));
	register_stylesheet($tpath['url::rating.css'].'/rating.css');
	
	$tpl -> template('rating', $tpath['rating']);
	$tpl -> vars('rating', $tvars);
	return $tpl -> show('rating');
}

function rating_show_quotes($id, $rating)
{global $tpl, $userROW;
	
	$tpath = locatePluginTemplates(array('rating', 'rating.form', ':rating.css'), 'quotes', pluginGetVariable('quotes', 'localsource'));
	register_stylesheet($tpath['url::rating.css'].'/rating.css');
	
	$tvars['vars']['ajax_url'] = generateLink('core', 'plugin', array('plugin' => 'quotes', 'handler' => 'url'), array());
	$tvars['vars']['id'] = $id;
	$tvars['vars']['rating'] = $rating;
	$tvars['vars']['admin_url'] = admin_url;
	
	switch ($rating)
	{
		case 0: $tvars['vars']['color'] = 'zero'; break;
		case $rating > 0: $tvars['vars']['color'] = 'plus'; break;
		case $rating < 0: $tvars['vars']['color'] = 'less'; break;
	}
	if ( ($_SESSION['id'][$id] == true) or ($_COOKIE['rating_quotes'.$id] == true) )
	{
		$tpl -> template('rating', $tpath['rating']);
		$tpl -> vars('rating', $tvars);
		return $tpl -> show('rating');
	} else {
		$tpl -> template('rating.form', $tpath['rating.form']);
		$tpl -> vars('rating.form', $tvars);
		return $tpl -> show('rating.form');
	}
	return;
}

function screen_rating_quotes()
{global $SUPRESS_TEMPLATE_SHOW, $template;
	@header('Content-type: text/html; charset="windows-1251"');
	if ($_REQUEST['id']) {
		$template['vars']['mainblock'] = update_rating_quotes();
		$SUPRESS_TEMPLATE_SHOW = 1;
	} else {
		$template['vars']['mainblock'] = 'unsupported action';
	}
}

function announcement_quotes( $message, $url, $error = 0 )
{global $template, $tpl, $SUPRESS_TEMPLATE_SHOW, $SYSTEM_FLAGS;
	
	//$SUPRESS_TEMPLATE_SHOW = 1;
	
	$tpath = locatePluginTemplates(array('infomessage'), 'quotes', pluginGetVariable('quotes', 'localsource'));
	
	switch ($error) {
		case 2: $error = "Запрос обработан: ${message}"; break;
		case 1: $error = "Ошибка 404: ${message}"; break;
		case 0:
		default: $error = $message;
	}
	
	$SYSTEM_FLAGS['info']['title']['group'] = $error;
	
	header( 'Refresh: '.pluginGetVariable('quotes','redirect_delay').'; url='.$url );
	
	$tvars['vars']= array (
		'title' => $error,
		'infomessage' => $message,
		'url' => "<a href='{$url}'>Вернуться назад</a>"
	);
	$tpl -> template('infomessage', $tpath['infomessage']);
	$tpl -> vars('infomessage', $tvars);
	$template['vars']['mainblock'] = $tpl -> show('infomessage');
}

function userblocks_quotes_page($content){
	global $config, $lang, $userROW;
	if (!$config['blocks_for_reg']) return $content;
	return preg_replace("#\[hide\]\s*(.*?)\s*\[/hide\]#is", is_array($userROW)?"$1":str_replace("{text}", $lang['quotes_not_logged'], $lang['quotes_not_logged_html']), $content);
}

function flood_quotes($mode, $id)
{global $mysql, $userROW, $ip, $config;
	
	$this_time = time() + ($config['date_adjust'] * 60) - pluginGetVariable('quotes', 'flood');
	
	if ($mode)
	{
		$this_time = time() + ($config['date_adjust'] * 60);
		$mysql->query("insert into ".prefix."_quotes_flood
		(id_user, time, id_quotes) values ('{$userROW['id']}', '{$this_time}', ".db_squote("{$id}").")");
		return false;
	}
	
	$mysql->query("DELETE FROM ".prefix."_quotes_flood WHERE time < ".db_squote("{$this_time}"));
	
	foreach ($mysql->select("SELECT id_user FROM ".prefix."_quotes_flood WHERE id_quotes = ".db_squote("{$id}")) as $row)
	{
		if($row['id_user'] == $userROW['id'])
		{
			return true;
		}
	}
	return false;
}
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
if(!defined('NGCMS'))
{
	exit('HAL');
}

plugins_load_config();

switch ($_REQUEST['action']) {
	case 'showlist': sortform(); showlist(); break;
	case 'edit': editform(); break;
	case 'add': addform(); break;
	case 'del': delform(); sortform(); showlist(); break;
	case 'modify': massQuotesModify(); sortform(); showlist(); break;
	default: showpage();
}

function sortform()
{global $tpl, $template, $mysql, $f3, $r, $f2;
	
	$postdate = intval($_REQUEST['postdate']);
	
	foreach ($mysql->select("SELECT DISTINCT FROM_UNIXTIME(postdate,'%b %Y') as monthes, COUNT(id) AS cnt FROM ".prefix."_quotes GROUP BY monthes ORDER BY postdate DESC") as $row){
		$ifselected = '';
		$post_date['ru']	=	str_replace($f3, $r, $row['monthes']);
		$post_date['en']	=	str_replace($f3, $f2, $row['monthes']);
		$arch_url			=	explode (" ", $post_date['en']);
		$post_date['en']	=	$arch_url[1].$arch_url[0];
		
		if ($post_date['en'] == $postdate) {
			$ifselected = "selected";
		}
		
		$options_date .= "<option value='${post_date['en']}' ${ifselected}>${post_date['ru']}</option>";
	}
	
	$options .= "<option value='0' selected>Все</option>";
	foreach ($mysql->select("select COUNT(u.id) AS cnt, u.id, u.name from ".prefix."_users as u, ".prefix."_quotes as q where u.id = q.author_id group by author") as $row)
	{
		$options .= "<option value='${row['id']}'".($row['id']==intval($_REQUEST['author'])?' selected':'').">${row['name']} [${row['cnt']}]</option>\n";
	}
	$status = intval($_REQUEST['status']);
	
	$tvars['vars'] = array (
		'selectdate' => $options_date,
		'author' => $options,
		'status'	=> 	'<option value="0" selected>Все</option><option value="1"'.(($status==1)?' selected':'').'>Да</option><option value="2"'.(($status==2)?' selected':'').'>Нет</option>',
		'sort'	=>	makeSortList($_REQUEST['sort']),
	);
	
	$tpath = locatePluginTemplates(array('config/conf.sort'), 'quotes', pluginGetVariable('quotes', 'localsource'));
	
	$tpl -> template('conf.sort', $tpath['config/conf.sort'].'config');
	$tpl -> vars('conf.sort', $tvars);
	print $tpl -> show('conf.sort');
}

function showlist()
{global $mysql, $tpl, $template, $config, $parse;
	$tpath = locatePluginTemplates(array('config/conf.quotes', 'config/conf.quotes.row'), 'quotes', pluginGetVariable('quotes', 'localsource'));

	$author = intval($_REQUEST['author']);
	$status = intval($_REQUEST['status']);
	$postdate = intval($_REQUEST['postdate']);
	
	$postyear		=	substr($postdate, 0, 4);
	$postmonth		=	substr($postdate, 4, 2);
	
	$sort = array();
	if ($postdate == true) {
		array_push($sort, "postdate > '".mktime(0, 0, 0, $postmonth, 1, $postyear)."'");
		array_push($sort, "postdate < '".mktime(23,59,59,$postmonth,date("t",mktime(0, 0, 0, $postmonth, 1, $postyear)), $postyear)."'");
	}
	
	if ($author == true)
	{
		array_push($sort, "author_id = ${author}");
	}
	
	if($status == 1 or $status == 2)
	{
		array_push($sort, "approve = ".(($status == 1)?'1':'0'));
	}
	
	$sortBy = '';
	switch($_REQUEST['sort']){
		case 'id':				$sortBy = 'id';				break;
		case 'id_desc':			$sortBy = 'id desc';		break;
		case 'postdate':		$sortBy = 'postdate';		break;
		case 'postdate_desc':	$sortBy = 'postdate desc';	break;
		case 'rating': 			$sortBy = 'rating';			break;
		case 'rating_desc': 	$sortBy = 'rating desc';	break;
	}
	
	if ($sortBy) {
		$sortBy = " order by ${sortBy}";
	} else {
		$sortBy = "order by id desc";
	}
	
	$news_per_page = intval(pluginGetVariable('quotes', 'adm_count'));
	
	if (($news_per_page < 2)||($news_per_page > 2000)) $news_per_page = 10;

	$pageNo		= intval($_REQUEST['page'])?$_REQUEST['page']:0;
	if ($pageNo < 1)	$pageNo = 1;
	if (!$start_from)	$start_from = ($pageNo - 1)* $news_per_page;
	
	$sql_endr = "from ".prefix."_quotes ".(count($sort)?"where ".implode(" AND ", $sort):'')." ${sortBy}";
	$sql_count = "select count(id) as cid ${sql_endr}";
	$sql = "select * ${sql_endr}";
	
	$cnt = $mysql->record($sql_count);
	$all_count_news = $cnt['cid'];
	$countPages = ceil($all_count_news / $news_per_page);

	$result = "${sql} LIMIT ${start_from}, ${news_per_page}";
	
	foreach ($mysql->select($result) as $row)
	{
		$content = $row['content'];
		if ($config['use_htmlformatter'])	{ $content = $parse -> htmlformatter($content); }
		if ($config['use_bbcodes'])			{ $content = $parse -> bbcodes($content); }
		if ($config['use_smilies'])			{ $content = $parse -> smilies($content); }
		
		switch ($row['approve'])
		{
			case 1: $active = 'Да'; break;
			case 0: $active = 'Нет'; break;
			default: $active = 'Ошибка';
		}
		
		$pvars['vars'] = array (
			'id' => $row['id'],
			'edit' => '<a href="?mod=extra-config&plugin=quotes&action=edit&id='.$row['id'].'" onclick="return confirm('."'Вы хотите приступить к редактированию?'".')";><img src="{skins_url}/images/configuration.gif" alt="DEL" width="12" height="12" /></a>',
			'del' => '<a href="?mod=extra-config&plugin=quotes&action=del&id='.$row['id'].'" onclick="return confirm('."'Вы уверены что хотите удалить'".')";><img src="{skins_url}/images/delete.gif" alt="DEL" width="12" height="12" /></a>',
			'content' => $content,
			'rating' => $row['rating'],
			'date' => LangDate(pluginGetVariable('quotes', 'date'), $row['postdate']),
			'active' => $active,
			'author' => $row['author'],
			'author_id' => $row['author_id'],
		);
		
		$tpl->template('conf.quotes.row', $tpath['config/conf.quotes.row'].'config');
		$tpl -> vars('conf.quotes.row', $pvars);
		$output .= $tpl -> show('conf.quotes.row');
	}
	$tvars['vars']['pagesss'] = generateAdminPagelist( array('current' => $pageNo, 'count' => $countPages, 'url' => admin_url.'/admin.php?mod=extra-config&plugin=quotes&action=showlist'.($_REQUEST['news_per_page']?'&news_per_page='.$news_per_page:'').($_REQUEST['author']?'&author='.$_REQUEST['author']:'').($_REQUEST['sort']?'&sort='.$_REQUEST['sort']:'').($postdate?'&postdate='.$postdate:'').($author?'&author='.$author:'').($status?'&status='.$status:'').'&page=%page%'));
	$tvars['vars']['entries'] = $output;
	
	$tpl->template('conf.quotes', $tpath['config/conf.quotes'].'config');
	$tpl->vars('conf.quotes', $tvars);
	print $tpl->show('conf.quotes');
}

function makeSortList($selected)
{
	return	'<option value="id_desc"'.($selected == "id_desc"?' selected':'').">ID цитаты / убыв</option>".
			'<option value="id"'.($selected == "id"?' selected':'').">ID цитаты</option>".
			'<option value="postdate_desc"'.($selected == "postdate_desc"?' selected':'').">Дата размещения / убыв</option>".
			'<option value="postdate"'.($selected == "postdate"?' selected':'').">Дата размещения</option>".
			'<option value="rating_desc"'.($selected == "rating_desc"?' selected':'').">Рейтингу / убыв</option>".
			'<option value="rating"'.($selected == "rating"?' selected':'').">Рейтингу</option>";
}

function massQuotesModify()
{global $mysql;
	
	$selected_news = $_REQUEST['selected_news'];
	$subaction	=	$_REQUEST['subaction'];
	
	$id = implode( ',', $selected_news );
	
	if( empty($id) )
	{
		return msg(array("type" => "error", "text" => "Ошибка, вы не выбрали цитаты"));
	}
	
	switch($subaction) {
		case 'do_mass_approve'      : $approve = 'approve = 1'; break;
		case 'do_mass_forbidden'    : $approve = 'approve = 0'; break;
		case 'do_mass_delete'       : $del = true; break;
	}
	if(isset($approve))
	{
		$mysql->query("update ".prefix."_quotes 
				set {$approve}
				WHERE id in ({$id})
				");
		msg(array("type" => "info", "info" => "Цитаты с ID${id} Активированы/Деактивированы"));
	}
	if(isset($del))
	{
		$mysql->query("delete from ".prefix."_quotes where id in ({$id})");
		msg(array("type" => "info", "info" => "Цитаты с ID${id} удалены"));
	}
}

function addform()
{global $tpl, $template, $mysql, $userROW;
	$tpath = locatePluginTemplates(array('config/conf.add'), 'quotes', pluginGetVariable('quotes', 'localsource'));
	
	$num_max = pluginGetVariable('quotes','max_char');
	
	$time['time'] = time() + ($config['date_adjust'] * 60);
	$content = secure_html(convert($_REQUEST['content']));
	$data['content'] = str_replace("\r\n", "\n", $content);
	$data['active'] = intval($_REQUEST['active']);
	$data['rating'] = intval($_REQUEST['rating']);
	
	if($num_max == true)
	{
		$data['content'] = substr($data['content'],0,$num_max);
	}
	
	if (isset($_REQUEST['actions']))
	{
		if (empty( $data['content'] )) { $error_flag = true; $error_text[] = msg(array("type" => "error", "text" => "Вы не добавили цитату")); }
		
		if (!empty( $data['content'] ))
		{
			$mysql->query("insert ".prefix."_quotes 
				(postdate, content, approve, rating, author, author_id)
				values
				('${time['time']}', ".db_squote($data['content']).", ".db_squote($data['active']).", ".db_squote($data['rating']).", '${userROW['name']}', '${userROW['id']}')
				");
			return msg(array("type" => "info", "info" => "Цитата Добавлена<br /><a href='?mod=extra-config&plugin=quotes&action=add'><b>Хотите добавить ещё?</b></a>")).showlist(); 
		}
	}
	
	$tvars['regx']["'\[latent\](.*?)\[/latent\]'si"] = ($num_max == 0)?'':'$1';
	
	$tvars['vars']['max_char'] = $num_max;
	$tvars['vars']['active'] = '<option value="1" selected>Разрешен</option><option value="0">Запрещен</option>';
	$tvars['vars']['smilies'] = InsertSmilies('comments', 10);
	$tvars['vars']['bbcodes'] = BBCodes();
	$tvars['vars']['error'] = '';
	
	if ($error_flag)
	{
		$tvars['vars']['error'] = implode('<br />', $error_text);
	}
	
	$tpl -> template('conf.add', $tpath['config/conf.add'].'config');
	$tpl -> vars('conf.add', $tvars);
	print $tpl -> show('conf.add');
}

function editform()
{global $tpl, $template, $mysql;
	$tpath = locatePluginTemplates(array('config/conf.edit'), 'quotes', pluginGetVariable('quotes', 'localsource'));
	
	$id = intval($_REQUEST['id']);
	$num_max = pluginGetVariable('quotes','max_char');
	
	if ($id != null)
	{
		foreach ($mysql->select('SELECT * FROM '.prefix.'_quotes WHERE id = '.db_squote($id)) as $row)
		{
			$tvars['vars']= array (
				'id' => $row['id'],
				'content' => $row['content'],
				'active' => '<option value="1" '.(($row['approve']==1)?' selected':'').'>Разрешен</option><option value="0" '.(($row['approve']==0)?' selected':'').'>Запрещен</option>',
				'rating' => $row['rating'],
				'max_char' => $num_max,
				
			);
		}
		
		$tvars['vars']['smilies'] = InsertSmilies('comments', 10);
		$tvars['vars']['bbcodes'] = BBCodes();
		
		$content = secure_html(convert($_REQUEST['content']));
		$data['content'] = str_replace("\r\n", "\n", $content);
		
		if($num_max == true)
		{
			$data['content'] = substr($data['content'],0,$num_max);
		}
		$data['active'] = intval($_REQUEST['active']);
		$data['rating'] = intval($_REQUEST['rating']);
		
		if (isset($_REQUEST['actions']))
		{
			if (empty( $data['content'] )) { $error_flag = true; $error_text[] = msg(array("type" => "error", "text" => "Вы не добавили цитату")); }
			
			if (!empty( $data['content'] ))
			{
				$mysql->query('update '.prefix.'_quotes 
				set content = '.db_squote($data['content']).', approve = '.db_squote($data['active']).', rating = '.db_squote($data['rating']).' 
				WHERE id = '.db_squote($id).'
				LIMIT 1');
				return msg(array("type" => "info", "info" => "Цитата c ID${id} отредактирована")).sortform().showlist();
			}
		}
		
		$tvars['regx']["'\[latent\](.*?)\[/latent\]'si"] = ($num_max == 0)?'':'$1';
		$tvars['vars']['error'] = '';
		if ($error_flag)
		{
			$tvars['vars']['error'] = implode('<br />', $error_text);
		}
		
		$tpl -> template('conf.edit', $tpath['config/conf.edit'].'config');
		$tpl -> vars('conf.edit', $tvars);
		print $tpl -> show('conf.edit');
	} else {
		msg(array("type" => "error", "text" => "Не передан id"));
	}
}

function delform()
{global $mysql;
	$mysql->query("delete from ".prefix."_quotes where id = ".intval($_REQUEST['id']));
	msg(array("type" => "info", "info" => "Цитата с id = ${_REQUEST['id']} удалена"));
}

function showpage()
{global $plugin;
	$cfg = array();
	$cfgX = array();
	array_push($cfgX, array('name' => 'localsource', 'title' => 'Выберите каталог из которого плагин будет брать шаблоны для отображения', 'descr' => '', 'type' => 'select', 'values' => array ( '0' => 'Шаблон сайта', '1' => 'Плагин'), 'value' => intval(pluginGetVariable($plugin,'localsource'))));
	array_push($cfgX, array('name' => 'max_char', 'title' => 'Максимальное возможное количество символов в одной цитате', 'descr' => '<b>0</b> - снять ограничение', 'type' => 'input', 'html_flags' => 'size="4"', 'value' => pluginGetVariable($plugin, 'max_char')));
	array_push($cfgX, array('name' => 'count', 'title' => "Количество цитат на странице", 'descr' => "", 'type' => 'input', 'html_flags' => 'size="4"', 'value' => pluginGetVariable($plugin, 'count')));
	array_push($cfgX, array('name' => 'redirect_delay', 'title' => "Время для редиректа", 'descr' => "Устанавливать в секундах...", 'type' => 'input', 'html_flags' => 'size="4"', 'value' => pluginGetVariable($plugin, 'redirect_delay')));
	array_push($cfgX, array('name' => 'date', 'title' => "Формат даты на главной странице", 'descr' => "", 'type' => 'input', 'html_flags' => 'size="10"', 'value' => pluginGetVariable($plugin, 'date')));
	array_push($cfgX, array('name' => 'description', 'title' => "Описание блога", 'descr' => "", 'type' => 'input', 'value' => pluginGetVariable($plugin, 'description')));
	array_push($cfgX, array('name' => 'keywords', 'title' => "Ключевые слова", 'descr' => "", 'type' => 'input', 'value' => pluginGetVariable($plugin, 'keywords')));
	array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки страниц</b>', 'entries' => $cfgX));
	
	$cfgX = array();
	array_push($cfgX, array('name' => 'users_rating', 'title' => "Рейтингом может пользоваться только зарегистрированый", 'descr' => "<b>Да</b> - Только зарегистроованые<br /><b>Нет</b> - все", 'type' => 'select', 'values' => array ( '1' => 'Да', '0' => 'Нет'), 'value' => pluginGetVariable($plugin, 'users_rating')));
	array_push($cfgX, array('name' => 'flood', 'title' => "Время через которое зарегистированый пользователь сможет снова проголосовать за цитату", 'descr' => "Время указывать в секундах<br />По умолчанию стоит 3 часа", 'type' => 'input', 'html_flags' => 'size="10"', 'value' => pluginGetVariable($plugin, 'flood')));
	array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки рейтинга</b>', 'entries' => $cfgX));
	
	
	$cfgX = array();
	array_push($cfgX, array('name' => 'rand', 'title' => "Какой вид сортировки использовать", 'descr' => "<b>Легкий</b> - не нагружает базу, но может показывать пустой <b>блок</b><br /><b>Тыжелый</b> - Качественная выюока, но при большом количестве цитат будет создавать большую нагрузку", 'type' => 'select', 'values' => array ( '0' => 'Легкий', '1' => 'Тяжелый'), 'value' => pluginGetVariable($plugin, 'rand')));
	array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки Блока</b>', 'entries' => $cfgX));
	
	$cfgX = array();
	array_push($cfgX, array('name' => 'adm_count', 'title' => "Количество цитат в админке на одной странице", 'descr' => "", 'type' => 'input', 'html_flags' => 'size="4"', 'value' => pluginGetVariable($plugin, 'adm_count')));
	array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки Админки</b>', 'entries' => $cfgX));
	
	$cfgX = array();
	array_push($cfgX, array('name' => 'cache', 'title' => "Использовать кеширование для цитатника<br /><small><b>Да</b> - кеширование используется<br /><b>Нет</b> - кеширование не используется</small>", 'type' => 'select', 'values' => array ( '1' => 'Да', '0' => 'Нет'), 'value' => intval(pluginGetVariable($plugin,'cache'))));
	array_push($cfgX, array('name' => 'cacheExpire', 'title' => 'Период обновления кеша (в секундах)<br /><small>(через сколько секунд происходит обновление кеша. Значение по умолчанию: <b>10800</b>, т.е. 3 часа)', 'type' => 'input', 'value' => intval(pluginGetVariable($plugin,'cacheExpire'))?pluginGetVariable($plugin,'cacheExpire'):'10800'));
	array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки кеширования</b>', 'entries' => $cfgX));
	if ($_REQUEST['action'] == 'commit') {
		commit_plugin_config_changes($plugin, $cfg);
		print_commit_complete($plugin);
	} else {
		generate_config_page($plugin, $cfg);
	}
	$skins_url = skins_url;
	print <<<DONE
	<table border="0" cellspacing="1" cellpadding="1" class="content">
	<tr>
	<td colspan="2" width=100% class="contentHead"><img src="${skins_url}/images/nav.gif" hspace="8">Работа с цитатами</td>
	</tr>
	</table>
	<tr> 
	<td colspan=2> 
	<fieldset> 
	<legend><b>Управление цитатами</b></legend> 
	<table width="100%" border="0"> 
	<tr> 
	<td class="contentEntry1" valign=top><a href="?mod=extra-config&plugin=quotes&action=showlist"><b>К списку всех цитат</b></a></td> 
	<td class="contentEntry2" valign=top></td> 
	</tr><tr> 
	<td class="contentEntry1" valign=top><a href="?mod=extra-config&plugin=quotes&action=add"><b>Добавить цитату</b></a></td> 
	<td class="contentEntry2" valign=top></td> 
	</tr>
	</table> 
	</fieldset> 
DONE;
}
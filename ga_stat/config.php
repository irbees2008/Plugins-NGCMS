<?php

if(!defined('NGCMS'))
{
	exit('HAL');
}

plugins_load_config();
LoadPluginLang('ga_stat', 'config', '', '', '#');

include_once (root."/plugins/ga_stat/ga_stat.php");

switch ($_REQUEST['action']) {
	case 'update_data': update_data(); break;
	case 'url': url(); break;
	default: main();
}

function url()
{
global $tpl;
	$tpath = locatePluginTemplates(array('config/main', 'config/url'), 'ga_stat', 1);
	
	if (isset($_REQUEST['submit']))
	{
		if(isset($_REQUEST['url']) && !empty($_REQUEST['url']))
		{
			$ULIB = new urlLibrary();
			$ULIB->loadConfig();
			
			$ULIB->registerCommand('ga_stat', '',
				array ('vars' =>
						array(),
						'descr'	=> array ('russian' => 'Статистика посещений GA'),
				)
			);
			
			$ULIB->saveConfig();
		} else {
			$ULIB = new urlLibrary();
			$ULIB->loadConfig();
			$ULIB->removeCommand('ga_stat', '');
			$ULIB->saveConfig();
		}
		
		
		pluginSetVariable('ga_stat', 'url', intval($_REQUEST['url']));
		pluginsSaveConfig();
		
		redirect_ga_stat('?mod=extra-config&plugin=ga_stat&action=url');
	}
	$url = pluginGetVariable('ga_stat', 'url');
	$url = '<option value="0" '.(empty($url)?'selected':'').'>Нет</option><option value="1" '.(!empty($url)?'selected':'').'>Да</option>';
	$pvars['vars']['info'] = $url;
	
	$tpl->template('url', $tpath['config/url'].'config');
	$tpl->vars('url', $pvars);
	$tvars['vars']= array (
		'entries' => $tpl->show('url'),
		'global' => 'Настройка ЧПУ'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}

function update_data()
{
global $tpl, $cron;
	$tpath = locatePluginTemplates(array('config/main', 'config/update_data'), 'ga_stat', 1);
	
	if (isset($_REQUEST['submit']))
	{
	pluginSetVariable('ga_stat', 'cron_time_min', trim($_REQUEST['cron_time_min']));
    pluginSetVariable('ga_stat', 'cron_time_hour', trim($_REQUEST['cron_time_hour']));
	
	pluginSetVariable('ga_stat', 'cron_on', intval($_REQUEST['cron_on']));
	pluginsSaveConfig();
	
	if(isset($_REQUEST['cron_on']) && !empty($_REQUEST['cron_on']))
	{
	
	$cron_time_min = pluginGetVariable('ga_stat', 'cron_time_min');
	$cron_time_hour = pluginGetVariable('ga_stat', 'cron_time_hour');
	
	$cron->unregisterTask('ga_stat');
	$cron->registerTask('ga_stat', 'run', $cron_time_min, $cron_time_hour, '*', '*', '*');
	}
	else
	{
	$cron->unregisterTask('ga_stat');
	}
	
	redirect_ga_stat('?mod=extra-config&plugin=ga_stat&action=update_data');
	}
	
	
	if (isset($_REQUEST['update_me']))
	{
    plugin_ga_stat_cron();	
	redirect_ga_stat('?mod=extra-config&plugin=ga_stat&action=update_data');
	}
	
	
	$cron_on = pluginGetVariable('ga_stat', 'cron_on');
	$cron_on = '<option value="0" '.(empty($cron_on)?'selected':'').'>Нет</option><option value="1" '.(!empty($cron_on)?'selected':'').'>Да</option>';
	
	$cron_time_min = pluginGetVariable('ga_stat', 'cron_time_min')?pluginGetVariable('ga_stat', 'cron_time_min'):'*';
	$cron_time_hour = pluginGetVariable('ga_stat', 'cron_time_hour')?pluginGetVariable('ga_stat', 'cron_time_hour'):'*';

	
	$pvars['vars'] = array (
		'cron_time_min' => $cron_time_min,
		'cron_time_hour' => $cron_time_hour,
		'cron_on' => $cron_on,
	);

	
	$tpl->template('update_data', $tpath['config/update_data'].'config');
	$tpl->vars('update_data', $pvars);
	$tvars['vars']= array (
		'entries' => $tpl->show('update_data'),
		'global' => 'Обновление данных'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}

function main()
{
global $tpl;
	
	$tpath = locatePluginTemplates(array('config/main', 'config/general.from'), 'ga_stat', 1);
	
	if (isset($_REQUEST['submit']))
	{
		pluginSetVariable('ga_stat', 'username', trim($_REQUEST['username']));
		pluginSetVariable('ga_stat', 'password', trim($_REQUEST['password']));
		pluginSetVariable('ga_stat', 'id_site', trim($_REQUEST['id_site']));
		
		pluginSetVariable('ga_stat', 'date_start', trim($_REQUEST['date_start']));
		pluginSetVariable('ga_stat', 'country_rows', trim($_REQUEST['country_rows']));
		pluginSetVariable('ga_stat', 'city_rows', trim($_REQUEST['city_rows']));
		pluginSetVariable('ga_stat', 'referrers_rows', trim($_REQUEST['referrers_rows']));
		pluginSetVariable('ga_stat', 'browsers_rows', trim($_REQUEST['browsers_rows']));
		pluginSetVariable('ga_stat', 'os_rows', trim($_REQUEST['os_rows']));
		
		pluginSetVariable('ga_stat', 'title_page', trim($_REQUEST['title_page']));
		pluginSetVariable('ga_stat', 'description_page',  secure_html($_REQUEST['description_page']));
		pluginSetVariable('ga_stat', 'keywords_page',  secure_html($_REQUEST['keywords_page']));
		pluginsSaveConfig();
		
		redirect_ga_stat('?mod=extra-config&plugin=ga_stat');
	}
	
	$username = pluginGetVariable('ga_stat', 'username');
	$password = pluginGetVariable('ga_stat', 'password');
	$id_site = pluginGetVariable('ga_stat', 'id_site');
	
	$date_start = pluginGetVariable('ga_stat', 'date_start');
	$country_rows = pluginGetVariable('ga_stat', 'country_rows');
	$city_rows = pluginGetVariable('ga_stat', 'city_rows');
	$referrers_rows = pluginGetVariable('ga_stat', 'referrers_rows');
	$browsers_rows = pluginGetVariable('ga_stat', 'browsers_rows');
	$os_rows = pluginGetVariable('ga_stat', 'os_rows');
	
	$title_page = pluginGetVariable('ga_stat', 'title_page');
	$description_page = pluginGetVariable('ga_stat', 'description_page');
	$keywords_page = pluginGetVariable('ga_stat', 'keywords_page');
	
	if(empty($username))
		msg(array("type" => "error", "text" => "Критическая ошибка. <br /> Не задано имя пользователя Google аккуанта"), 1);
		
	if(empty($password))
		msg(array("type" => "error", "text" => "Критическая ошибка. <br /> Не задан пароль Google аккуанта"), 1);
		
	if(empty($id_site))
		msg(array("type" => "error", "text" => "Критическая ошибка. <br /> Не задан ID сайта в Google Analytics"), 1);
	
	$pvars['vars'] = array (
		'username' => $username,
		'password' => $password,
		'id_site' => $id_site,
		
		'date_start' => $date_start,
		'country_rows' => $country_rows,
		'city_rows' => $city_rows,
		'referrers_rows' => $referrers_rows,
		'browsers_rows' => $browsers_rows,
		'os_rows' => $os_rows,
		
		'title_page' => $title_page,
		'description_page' => $description_page,
		'keywords_page' => $keywords_page,
		
	);
	
	$tpl->template('general.from', $tpath['config/general.from'].'config');
	$tpl->vars('general.from', $pvars);
	$tvars['vars']= array (
		'entries' => $tpl->show('general.from'),
		'global' => 'Общие'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}

function redirect_ga_stat($url)
{
	if (headers_sent()) {
		echo "<script>document.location.href='{$url}';</script>\n";
	} else {
		header('HTTP/1.1 302 Moved Permanently');
		header("Location: {$url}");
	}
}
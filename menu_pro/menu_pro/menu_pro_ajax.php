<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

LoadPluginLang('menu_pro', 'config', '', '', ':');

rpcRegisterFunction('menu_pro_get_plugin', 'plugin_menu_pro_ajax_get_plugin');
rpcRegisterFunction('menu_pro_get_handler', 'plugin_menu_pro_ajax_get_handler');
rpcRegisterFunction('menu_pro_get_icon', 'plugin_menu_pro_ajax_get_icon');
rpcRegisterFunction('menu_pro_get_skin', 'plugin_menu_pro_ajax_get_skin');

// Admin panel: search for users
function plugin_menu_pro_ajax_get_plugin($params){
	global $userROW, $PLUGINS;

	// Check for permissions
	if (!is_array($userROW) || ($userROW['status'] > 3)) {
		// ACCESS DENIED
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied');
	}

	$searchName = iconv('UTF-8', 'Windows-1251', $params);
	// Return a list of users


	$plugin_list = get_extras_list();
	$output = array();
	foreach ($plugin_list as $key => $val)
	{
		if (stripos($key, $searchName) === FALSE && stripos($val['name'], $searchName) === FALSE)
			continue;
		if (strlen($val['name']) > 11)
		{
			$t_str = substr($val['name'], 0, 10).'...';
		}
		else
		{
			$t_str = $val['name'];
		}
		$output[] = array(iconv('Windows-1251', 'UTF-8', $key), iconv('Windows-1251', 'UTF-8', $t_str));
	}

	return array('status' => 1, 'errorCode' => 0, 'data' => array($params, $output));
}

// Admin panel: search for users
function plugin_menu_pro_ajax_get_handler($params){
	global $userROW, $PLUGINS, $config;

	// Check for permissions
	if (!is_array($userROW) || ($userROW['status'] > 3)) {
		// ACCESS DENIED
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied');
	}

	$rep_plugin = $params['rep_plugin'];
	$rep_handler = $params['rep_handler'];

	$s_rep_plugin = iconv('UTF-8', 'Windows-1251', $rep_plugin);
	$s_rep_handler = iconv('UTF-8', 'Windows-1251', $rep_handler);

	$ULIB = new urlLibrary();
	$ULIB->loadConfig();

	$output = array();
	if (isset($ULIB->CMD[$s_rep_plugin]))
	{
		foreach ($ULIB->CMD[$s_rep_plugin] as $key => $val)
		{
			$t_str = '';
			if (isset($val['descr'][$config['default_lang']]))
			{
				$t_str = $val['descr'][$config['default_lang']];
				if (strlen($t_str) > 11)
				{
					$t_str = substr($t_str, 0, 10).'...';
				}
			}
			$output[] = array(iconv('Windows-1251', 'UTF-8', $key), iconv('Windows-1251', 'UTF-8', $t_str));
		}
	}

	return array('status' => 1, 'errorCode' => 0, 'data' => array($rep_handler, $output));
}

function plugin_menu_pro_ajax_get_icon($params)
{
	global $userROW, $PLUGINS;

	// Check for permissions
	if (!is_array($userROW) || ($userROW['status'] > 3)) {
		// ACCESS DENIED
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied');
	}

	$searchName = iconv('UTF-8', 'Windows-1251', $params);
	// Return a list of users
	
	$dir = pluginGetVariable('menu_pro', 'locate_tpl')?extras_dir.'/menu_pro/tpl/icons/':tpl_site.'plugins/menu_pro/icons/';
	$output = array();
	if (file_exists($dir))
	{
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) { 
				if ($file == "." || $file == "..")
					continue;
				$output[] = array(iconv('Windows-1251', 'UTF-8', $file));
			}
			closedir($handle); 
		}
	}
	return array('status' => 1, 'errorCode' => 0, 'data' => array($params, $output));
}

function plugin_menu_pro_ajax_get_skin($params)
{
	global $userROW, $PLUGINS;

	// Check for permissions
	if (!is_array($userROW) || ($userROW['status'] > 3)) {
		// ACCESS DENIED
		return array('status' => 0, 'errorCode' => 3, 'errorText' => 'Access denied');
	}

	$searchName = iconv('UTF-8', 'Windows-1251', $params);
	// Return a list of users
	
	$dir = pluginGetVariable('menu_pro', 'locate_tpl')?extras_dir.'/menu_pro/tpl/skins/':tpl_site.'plugins/menu_pro/skins/';
	$output = array();
	if (file_exists($dir))
	{
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) { 
				if ($file == "." || $file == ".." || !is_dir($dir.$file))
					continue;
				$output[] = array(iconv('Windows-1251', 'UTF-8', $file));
			}
			closedir($handle); 
		}
	}
	return array('status' => 1, 'errorCode' => 0, 'data' => array($params, $output));
}
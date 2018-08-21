<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');
	
//
// Configuration file for plugin
//

pluginsLoadConfig();
LoadPluginLang('addnews', 'config', '', '', ':');

switch ($_REQUEST['action']) {
	case 'about':          about(); break;
	case 'categories':     showList(); break;
	case 'setCats':	       setCats(); showList(); break;
	case 'generalSubmit':  generalSubmit(); main(); break;
	default: main();
}

function main() {
	global $tpl, $lang;

	$skList = array();
	if ($skDir = opendir(extras_dir.'/addnews/tpl/skins')) {
		while ($skFile = readdir($skDir)) {
			if (!preg_match('/^\./', $skFile)) {
				$skList[$skFile] = $skFile;
			}
		}
		closedir($skDir);
	}
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.general.form'), 'addnews', 1);

	$ttvars['vars'] = array(
		'localsource'		=> MakeDropDown(array(0 => $lang['addnews:localsource.site'], 1 => $lang['addnews:localsource.plugin']), 'localsource', pluginGetVariable('addnews', 'localsource')),
		'skin'				=> MakeDropDown($skList, 'skin', pluginGetVariable('addnews', 'skin'))
	);

	$perm = pluginGetVariable('addnews', 'perm');

	if (is_array($perm)) {
		foreach ($perm as $action => $status_arr) {
			if ($status_arr == "null")
				break;

			foreach ($status_arr as $key => $status)
				$ttvars['vars']['ch_'.$action.'_'.$status] = ' checked="checked"';
		}
	}

	$tpl->template('conf.general.form', $tpath['conf.general.form']);
	$tpl->vars('conf.general.form', $ttvars);
	
	$tvars['vars'] = array(
		'entries'	=> $tpl->show('conf.general.form'),
		'action'	=> $lang['addnews:buttonGeneral']
	);

	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function showList() {
	global $tpl, $lang, $catz, $catmap;
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.categories', 'conf.categories.row'), 'addnews', 1);
	
	$categories = pluginGetVariable('addnews', 'categories');
	
	$output = '';
	foreach($catmap as $key=>$val){
		$pvars['vars']['category'] = $key;
		$pvars['vars']['category_name'] = $catz[$val]['name'];
		$pvars['vars']['ch_category'] = (in_array($key, $categories))?' checked="checked"':'';

		$tpl->template('conf.categories.row', $tpath['conf.categories.row']);
		$tpl->vars('conf.categories.row', $pvars);
		$output .= $tpl->show('conf.categories.row');
	}

	$ttvars['vars']['entries'] = $output?$output:$lang['addnews:cats.noCategories'];
	
	$tpl->template('conf.categories', $tpath['conf.categories']);
	$tpl->vars('conf.categories', $ttvars);

	$tvars['vars'] = array(
		'entries'	=> $tpl->show('conf.categories'),
		'action'	=> $lang['addnews:buttonCategories']
	);

	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function about() {
	global $tpl, $lang;
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.about'), 'addnews', 1);
	
	$tpl->template('conf.about', $tpath['conf.about']);
	$tpl->vars('conf.about');

	$tvars['vars'] = array(
		'entries'	=> $tpl->show('conf.about'),
		'action'	=> $lang['addnews:buttonAbout']
	);

	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}


function setCats() {
	global $lang;

	pluginSetVariable('addnews', 'categories', $_POST['categories']);		
	pluginsSaveConfig();

	msg(array('type' => 'info', 'info' => $lang['addnews:msgi.setCats']));
}

function generalSubmit() {
	global $lang;

	pluginSetVariable('addnews', 'perm', $_POST['perm']);
	pluginSetVariable('addnews', 'localsource', intval($_POST['localsource']));		
	pluginSetVariable('addnews', 'skin', trim(secure_html($_POST['skin'])));
	pluginsSaveConfig();

	msg(array('type' => 'info', 'info' => $lang['addnews:msgi.generalSubmit']));
}
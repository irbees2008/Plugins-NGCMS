<?php
/*
=====================================================
 Добавление новостей с фронта v 2.01
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
	exit('HAL');
	
switch ($_REQUEST['action']){
	case 'about':	about(); break;
	case 'user_rights':	user_rights(); break;
	case 'general':	general(); break;
	default:	general();
}

function about()
{global $tpl;
	$tpath = locatePluginTemplates(array('config/main', 'config/about'), 'addnews_2', 1);
	
	$tpl->template('about', $tpath['config/about'].'config');
	$tpl->vars('about', $pvars);
	$tvars['vars'] = array (
		'entries' => $tpl->show('about'),
		'global' => 'О плагине'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}

function general()
{global $tpl;
	$tpath = locatePluginTemplates(array('config/main', 'config/general', 'config/info'), 'addnews_2', 1);
	
	$pvars['vars']['info'] = '';
	if(isset($_SESSION['addnews_2']['info']) && !empty($_SESSION['addnews_2']['info']))
	{
		$pvars['vars']['info'] = msg(array("type" => "info", "info" => str_replace(array('{url}'), array(generateLink('addnews_2', '')), $_SESSION['addnews_2']['info'])), 0, 2);
		unset($_SESSION['addnews_2']['info']);
	}
	
	if (isset($_REQUEST['submit']))
	{
		pluginSetVariable('addnews_2', 'titles', secure_html($_REQUEST['titles']));
		pluginsSaveConfig();
		$pvars['vars']['info'] = msg(array("type" => "info", "info" => 'Настройки сохранены'));
	}
	
	$pvars['vars']['titles'] = pluginGetVariable('addnews_2', 'titles');
	
	$tpl->template('general', $tpath['config/general'].'config');
	$tpl->vars('general', $pvars);
	$tvars['vars'] = array (
		'entries' => $tpl->show('general'),
		'global' => 'Общие'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}

function user_rights()
{global $tpl;
	
	$tpath = locatePluginTemplates(array('config/main', 'config/setting.rights'), 'addnews_2', 1);
	
	//print "<pre>".var_export(pluginGetVariable('addnews_2', 'access'), true)."</pre>";
	
	$pvars['vars']['info'] = '';
	if (isset($_REQUEST['submit']))
	{
		array_walk_recursive($_REQUEST['access'], intval);
		pluginSetVariable('addnews_2', 'access', $_REQUEST['access']);
		pluginsSaveConfig();
		$pvars['vars']['info'] = msg(array("type" => "info", "info" => 'Права сохранены'));
	}
	
	foreach(pluginGetVariable('addnews_2', 'access') as $key => $val){
		switch ($key){
			case 0:
				$pvars['vars']['check_send'] = empty($val['send'])?'':'checked="checked"';
				$pvars['vars']['check_approve'] = empty($val['approve'])?'':'checked="checked"';
				$pvars['vars']['check_mainpage'] = empty($val['mainpage'])?'':'checked="checked"';
				$pvars['vars']['check_meta'] = empty($val['meta'])?'':'checked="checked"';
				$pvars['vars']['check_captcha'] = empty($val['captcha'])?'':'checked="checked"';
				$pvars['vars']['check_protec_bot'] = empty($val['protec_bot'])?'':'checked="checked"';
				break;
			case 1:
				$pvars['vars']['check_send1'] = empty($val['send'])?'':'checked="checked"';
				$pvars['vars']['check_approve1'] = empty($val['approve'])?'':'checked="checked"';
				$pvars['vars']['check_mainpage1'] = empty($val['mainpage'])?'':'checked="checked"';
				$pvars['vars']['check_meta1'] = empty($val['meta'])?'':'checked="checked"';
				$pvars['vars']['check_captcha1'] = empty($val['captcha'])?'':'checked="checked"';
				$pvars['vars']['check_protec_bot1'] = empty($val['protec_bot'])?'':'checked="checked"';
				break;
			case 2:
				$pvars['vars']['check_send2'] = empty($val['send'])?'':'checked="checked"';
				$pvars['vars']['check_approve2'] = empty($val['approve'])?'':'checked="checked"';
				$pvars['vars']['check_mainpage2'] = empty($val['mainpage'])?'':'checked="checked"';
				$pvars['vars']['check_meta2'] = empty($val['meta'])?'':'checked="checked"';
				$pvars['vars']['check_captcha2'] = empty($val['captcha'])?'':'checked="checked"';
				$pvars['vars']['check_protec_bot2'] = empty($val['protec_bot'])?'':'checked="checked"';
			break;
			case 3:
				$pvars['vars']['check_send3'] = empty($val['send'])?'':'checked="checked"';
				$pvars['vars']['check_approve3'] = empty($val['approve'])?'':'checked="checked"';
				$pvars['vars']['check_mainpage3'] = empty($val['mainpage'])?'':'checked="checked"';
				$pvars['vars']['check_meta3'] = empty($val['meta'])?'':'checked="checked"';
				$pvars['vars']['check_captcha3'] = empty($val['captcha'])?'':'checked="checked"';
				$pvars['vars']['check_protec_bot3'] = empty($val['protec_bot'])?'':'checked="checked"';
			break;
			case 4:
				$pvars['vars']['check_send4'] = empty($val['send'])?'':'checked="checked"';
				$pvars['vars']['check_approve4'] = empty($val['approve'])?'':'checked="checked"';
				$pvars['vars']['check_mainpage4'] = empty($val['mainpage'])?'':'checked="checked"';
				$pvars['vars']['check_meta4'] = empty($val['meta'])?'':'checked="checked"';
				$pvars['vars']['check_captcha4'] = empty($val['captcha'])?'':'checked="checked"';
				$pvars['vars']['check_protec_bot4'] = empty($val['protec_bot'])?'':'checked="checked"';
			break;
		}
	}
	
	$tpl->template('setting.rights', $tpath['config/setting.rights'].'config');
	$tpl->vars('setting.rights', $pvars);
	$tvars['vars'] = array (
		'entries' => $tpl->show('setting.rights'),
		'global' => 'Настройка прав'
	);
	
	$tpl->template('main', $tpath['config/main'].'config');
	$tpl->vars('main', $tvars);
	print $tpl->show('main');
}
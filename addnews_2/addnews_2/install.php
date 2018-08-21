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
	die ('HAL');

function plugin_addnews_2_install($action)
{
	$checkVer = explode('.', substr(engineVersion, 0,5));
	if($checkVer['0'] == 0 && $checkVer['1'] == 9 && $checkVer['2'] > 2)
		$check = true;
	else
		$check = false;
	
	
	$ULIB = new urlLibrary();
	$ULIB->loadConfig();
	$ULIB->registerCommand('addnews_2', '',
		array ('vars' =>
			array(),
			'descr'	=> array ('russian' => 'Добавить новость'),
		)
	);
	
	$UHANDLER = new urlHandler();
	$UHANDLER->loadConfig();
	
	$UHANDLER->registerHandler(0,
		array (
		'pluginName' => 'addnews_2',
		'handlerName' => '',
		'flagPrimary' => true,
		'flagFailContinue' => false,
		'flagDisabled' => false,
		'rstyle' => 
			array (
			  'rcmd' => '/send/',
			  'regex' => '#^/send/$#',
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
				  1 => '/send/',
				  2 => 0,
				),
			  ),
			),
		)
	);
	switch ($action)
	{
		case 'confirm':
			if($check)
				generate_install_page('addnews_2', 'Тыкай установить'); 
			else
				msg(array("type" => "info", "info" => "Версия CMS не соответствует допустимой<br />У вас установлена ".$checkVer['0'].".".$checkVer['1'].".<b>".$checkVer['2']."</b>. Требуется 0.9.3!"),0, 1);
			break;
		case 'autoapply':
		case 'apply':
			$ULIB->saveConfig();
			$UHANDLER->saveConfig();
			
			if (fixdb_plugin_install('addnews_2', '', 'install', ($action=='autoapply')?true:false)) {
				plugin_mark_installed('addnews_2');
				$_SESSION['addnews_2']['info'] = 'Вы только что установили плагин для добавления новостей с фронта сайта!<br />
				Адрес для добавления <a href="{url}">сылка</a><br />
				По ошибка, неточностям обращаться на страницу на форуме или мне на ICQ: 209388634 или jabber: rozard@ya.ru<br />
				Приятной работы!';
			} else {
				return false;
			}
			
			$params = array(
				'access' => array (
								  1 => 
								  array (
									'send' => '1',
									'approve' => '1',
									'mainpage' => '1',
									'meta' => '1',
									'captcha' => '1',
									'protec_bot' => '1',
								  ),
								  2 => 
								  array (
									'send' => '1',
									'approve' => '1',
									'mainpage' => '1',
									'meta' => '1',
									'captcha' => '1',
									'protec_bot' => '1',
								  ),
								  3 => 
								  array (
									'send' => '1',
									'approve' => '1',
									'mainpage' => '1',
									'meta' => '1',
									'captcha' => '1',
									'protec_bot' => '1',
								  ),
								  4 => 
								  array (
									'send' => '1',
									'approve' => '1',
									'mainpage' => '1',
									'meta' => '1',
									'captcha' => '1',
									'protec_bot' => '1',
								  ),
								  0 => 
								  array (
									'send' => '1',
									'approve' => '1',
									'mainpage' => '1',
									'meta' => '1',
									'captcha' => '1',
									'protec_bot' => '1',
								  ),
								),
				'titles' => '%home% / Добавить новость'
				
			);
			foreach ($params as $k => $v) {
				extra_set_param('addnews_2', $k, $v);
			}
			extra_commit_changes();
			break;
	}
	return true;
}
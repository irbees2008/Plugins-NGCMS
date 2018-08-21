<?php
/*
=====================================================
 NG Quotes block v0.01
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
	die ('HAL');
}
add_act('index', 'plugin_quotes_block');

$lang = LoadPluginLang('quotes', 'index');

function plugin_quotes_block()
{global $template, $tpl, $mysql, $config, $parse;
	
	$tpath = locatePluginTemplates(array('quotes_block', 'show_block'), 'quotes', pluginGetVariable('quotes', 'localsource'), '', 'block');
	
	switch (pluginGetVariable('quotes', 'rand'))
	{
		case 0:
			$res =$mysql->select('SELECT MAX(id) FROM '.prefix.'_quotes', -1);
			$rnd = rand(1, $res[0][0]);
			$rand = "where id = ${rnd}";
		break;
		case 1:
			$rand = 'order by rand() limit 1';
		break;
		default: $rand = 'order by rand() limit 1';
	}
	
	foreach ($mysql->select("SELECT content FROM ".prefix."_quotes ${rand}") as $row)
	{
		$content = $row['content'];
		
		if ($config['blocks_for_reg'])		{ $content = userblocks_quotes($content); }
		if ($config['use_htmlformatter'])	{ $content = $parse -> htmlformatter($content); }
		if ($config['use_bbcodes'])			{ $content = $parse -> bbcodes($content); }
		if ($config['use_smilies'])			{ $content = $parse -> smilies($content); }
		
		$pvars['vars'] = array (
			'name' => $content
		);
		
		$tpl -> template('show_block', $tpath['show_block']);
		$tpl -> vars('show_block', $pvars);
		$output .= $tpl -> show('show_block');
	}
	
	if ($output == null)
	{
		return $template['vars']['quotes_block'] = 'Цитат не существует';
	} else {
		$tvars['vars']['entries'] = $output;
	}
	
	$tpl -> template('quotes_block', $tpath['quotes_block']);
	$tpl -> vars('quotes_block', $tvars);
	$template['vars']['quotes_block'] = $tpl -> show('quotes_block');
}

function userblocks_quotes($content){
	global $config, $lang, $userROW;
	if (!$config['blocks_for_reg']) return $content;
	return preg_replace("#\[hide\]\s*(.*?)\s*\[/hide\]#is", is_array($userROW)?"$1":str_replace("{text}", $lang['quotes_not_logged'], $lang['quotes_not_logged_html']), $content);
}
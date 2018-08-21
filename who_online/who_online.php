<?php

/*
 * who_online for NextGeneration CMS (http://ngcms.ru/)
 * Copyright (C) 2011 Alexey N. Zhukov (http://digitalplace.ru)
 * based on Joe's code (http://webdecode.ru/)
 * http://digitalplace.ru
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or (at
 * your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */
 
# protect against hack attempts
if (!defined('NGCMS')) die ('Galaxy in danger');

add_act('index', 'who_online');

$BOOT = false;
$jouser = Array();

# return bot's name
function agent_isseter()
{
	$uar		= parse_ini_file('user-agent.ini');
	$bot_name	= '';
	
	foreach($uar as $key => $value)
		if(stripos($_SERVER['HTTP_USER_AGENT'], $key)){
			$bot_name = $value;
			break;
		}
	
	if ($bot_name)
		$GLOBALS['BOOT'] = true;
	else
		$bot_name = $_SERVER['HTTP_USER_AGENT'];
	
	return $bot_name;
}

function who_online ()
{
	global $mysql, $tpl, $config, $template, $userROW, $ip, $lang;

	$bru   = !$userROW['id']    ? agent_isseter()    : $_SERVER['HTTP_USER_AGENT'];
	$login = $userROW['name']   ? $userROW['name']   : '';
	$grp   = $userROW['status'] ? $userROW['status'] : ($GLOBALS['BOOT'] ? -1 : 0);
	$id    = $userROW['id']     ? $userROW['id']     : 0;
	$sess = md5($bru.$ip.$id);
	
	
	# check session
	$ss = $mysql->record('SELECT `session` FROM '.prefix.'_online WHERE `session` = '.db_squote($sess).' LIMIT 1');
	
	$db_column = array('session', 'id', 'lasttime', 'ip', 'agent', 'login', 'status', 'avatar', 'com', 'reg');
	$db_value = array(db_squote($sess), $id, time(), db_squote($ip), db_squote($bru), db_squote($login), $grp, db_squote($userROW['avatar']), db_squote($userROW['com']), db_squote($userROW['reg']));
	
	if ($ss == false) 
		$mysql->query('INSERT INTO `'.prefix.'_online` ('.implode(',', $db_column).') 
					   VALUES ('.implode(',', $db_value).')');
	else 	
		# else update last time
		$mysql->query('UPDATE `'.prefix.'_online` SET `lasttime`='.time().', `id`='.$id.', `login`='.db_squote($login).', `status`='.$grp.', `com`='.db_squote($userROW['com']).' WHERE `session`='.db_squote($ss['session']));
	
	$i = 0; $u = 0; $b = 0; $g = 0; $l = '';

	$tm = time() - intval(pluginGetVariable('who_online', 'timeout'));
	
	LoadPluginLang('who_online', 'main', '', '', ':');
	
	$res = $mysql->select('SELECT * FROM `'.prefix.'_online` WHERE `lasttime` > '.$tm.';');
	foreach ($res as $row)
	{
		if ($row['status'] == -1)
		{
			$b++;
			$k.= $row['agent'].', ';
		}
		elseif ($row['status'] > 0)
		{
			if ($jouser[$row['id']]) continue;
			
			switch($row['status']){
				case 1: $status = $lang['who_online:st_1']; break;
				case 2: $status = $lang['who_online:st_2']; break;
				case 3: $status = $lang['who_online:st_3']; break;
				case 4: $status = $lang['who_online:st_4']; break;
			}
			
			$u++;
			$profile_link = checkLinkAvailable('uprofile', 'show')?
				 generateLink('uprofile', 'show', array('name' => $row['login'], 'id' => $row['id'])):
				 generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('id' => $row['id']));
			$avatar_link = $row['avatar'] ? avatars_url.'/'.$row['avatar'] : avatars_url.'/noavatar.gif';	 
			$l .= str_replace(array('{profile_link}', '{avatar_link}', '{login}', '{status}', '{com}', '{reg}'), array($profile_link, $avatar_link, $row['login'], $status, $row['com'], langdate("j Q Y", $row['reg'])), $lang['who_online:user']);
			$jouser[$row['id']] = true;
			
		} else $g++;
	}
	
	$l = $l.$k;
	
	$online_user_list = substr($l, 0, strlen($l)-2);

	$tpath = locatePluginTemplates(array('sidebar'), 'who_online', intval(pluginGetVariable('who_online', 'localsource')));	
		
	$tvars['vars'] = array (
		'online_user_list' 	=> $online_user_list,
		'user_count' 		=> $u,
		'guest_count'		=> $g,
		'sum_count'			=> $u + $g
	);

	$tpl->template('sidebar', $tpath['sidebar']);
	$tpl->vars('sidebar', $tvars);
	$template['vars']['who_online'] = $tpl->show('sidebar');

	# clear old records
	if (pluginGetVariable('who_online', 'last_clear') < time() - intval(pluginGetVariable('who_online', 'time_clear'))){
		$mysql->query('DELETE FROM `'.prefix.'_online` WHERE `lasttime` < '.$tm.';');
		pluginSetVariable('who_online', 'last_clear', time());
		pluginsSaveConfig();
	}
}
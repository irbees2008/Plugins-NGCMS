<?php

if (!defined('NGCMS')) exit('HAL');

class ShowAvatar_newsNewsFilter extends NewsFilter {
	function showNews($newsID, $SQLnews, &$tvars, $mode = array()){
		global $mysql, $config, $userROW;

/*		
$use_cache = 1;
//Кодирум в md5
$cacheFileName = md5('avatar_news'.$config['theme'].$config['default_lang']).'.txt';
		
// Чтение (в параметрах плагин хранит: cache - флаг сообщающий надо ли использовать кеш, cacheExpire - время жизни кеша в секундах
     if ($use_cache)    {
        $cacheData = cacheRetrieveFile($cacheFileName, 300, 'avatar_news');
        if ($cacheData != false){
            // We got data from cache. Return it and stop
            $template['vars']['avatar_in_news'] = $cacheData;
            return;
        }
    }
	
//pluginGetVariable('avatar_news','cache')
//pluginGetVariable('avatar_news','cacheExpire')
*/


		$result=$mysql->record("select id, avatar from ".uprefix."_users where id = ".$SQLnews['author_id']." limit 1");
			
	// Check for new style of avatars storing
	if ($result['avatar']) {
		$uavatar = $result['avatar'];
	}

	// GRAVATAR.COM integration ** BEGIN **
	if ($result['avatar'] != '') {
		$avatar	= avatars_url.'/'.$uavatar;
	} else {
		if ($config['avatars_gravatar']) {
			$avatar	= 'http://www.gravatar.com/avatar/'.md5(strtolower($userROW['mail'])).'.jpg?s='.$config['avatar_wh'].'&d='.urlencode(avatars_url."/noavatar.gif");
		} else {
			$avatar = avatars_url."/noavatar.gif";
		}
	}
	$tvars['vars']['avatar_in_news'] = $avatar;
	
/*
	if ($use_cache) {
    // Запись
    cacheStoreFile($cacheFileName, $avatar, 'avatar_news');
}
*/
		
	}
}

register_filter('news','avatar_news', new ShowAvatar_newsNewsFilter);
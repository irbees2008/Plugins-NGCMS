<?php

if (!defined('NGCMS')) exit('HAL');

class UserInfo_newsNewsFilter extends NewsFilter {
    function showNews($newsID, $SQLnews, &$tvars, $mode = array()){
        global $mysql, $config, $userROW;

        $cache = pluginGetVariable('userinfo_news', 'cache');
        $cacheExpire = pluginGetVariable('userinfo_news', 'cacheExpire');

        //Кодирум в md5
        $cacheFileName = md5('userinfo_news'.$config['theme'].$config['default_lang'].$newsID.$SQLnews['author_id']).'.txt';

         if ($cache){
            $cacheData = unserialize(cacheRetrieveFile($cacheFileName, $cacheExpire, 'userinfo_news'));
            if ($cacheData != false){
                // We got data from cache. Return it and stop
                $tvars['vars']['userNewsInfo'] = $cacheData;
                return;
            }
        }
        
        $result = $mysql->record("select * from ".uprefix."_users where id = ".$SQLnews['author_id']." limit 1");
                
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
        
        $result['avatar'] = $avatar;
        $result['pass'] = "";
        $result['activation'] = "";
        $result['newpw'] = "";
        $result['authcookie'] = "";
        $result['ip'] = "";
               
        $xf = xf_decode($result['xfields']);
        $result['xf'] = $xf;

        $tvars['vars']['userNewsInfo'] = $result;
        
        if ($cache) {
            cacheStoreFile($cacheFileName, serialize($result), 'userinfo_news');
        }

    }
}

register_filter('news','userinfo_news', new UserInfo_newsNewsFilter);

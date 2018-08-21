<?php	

	define('CONSUMER_KEY', 'VmsYgJt2EqZ0u4QbClesg');
	define('CONSUMER_SECRET', 'UQVYYsy98n1mO6Xop3EmhJfpiZokGliQig743M');

    // Protect against hack attempts
    if (!defined('NGCMS')) die ('Galaxy in danger');
	
	// Preload config file    
	pluginsLoadConfig();		
	
	// Load lang files    
	LoadPluginLang('lasttweets', 'config', '', '', ':');
	
	switch ($_REQUEST['action']) {
		case 'general_submit': general_submit(); main(); break;
		case 'get_pin_code': get_pin_code(); break;
		default: main();
	}
	
function main(){
	global $tpl;
	
		$tvars['vars']['twi_username'] = pluginGetVariable('lasttweets', 'twi_username'); 
		$tvars['vars']['cacheExpire']  = pluginGetVariable('lasttweets', 'cacheExpire'); 
		$tvars['vars']['search']	   = pluginGetVariable('lasttweets', 'search');
		$tvars['vars']['count'] 	   = pluginGetVariable('lasttweets', 'count'); 
		$tvars['vars']['GMT'] 		   = pluginGetVariable('lasttweets', 'GMT');
		
		if(pluginGetVariable('lasttweets', 'localsource') == 0){
			$tvars['vars']['selected_localsource_0'] = 'selected';
			$tvars['vars']['selected_localsource_1'] = '';
		} else {
			$tvars['vars']['selected_localsource_0'] = '';
			$tvars['vars']['selected_localsource_1'] = 'selected';
		}
	
		if(pluginGetVariable('lasttweets', 'cache') == 0){
			$tvars['vars']['selected_cache_0'] = 'selected';
			$tvars['vars']['selected_cache_1'] = '';
		} else {
			$tvars['vars']['selected_cache_0'] = '';
			$tvars['vars']['selected_cache_1'] = 'selected';
		}
		
		switch(pluginGetVariable('lasttweets', 'timeline')){
			case 0: {
				$tvars['vars']['selected_timeline_0'] = 'selected';
				$tvars['vars']['selected_timeline_1'] = '';
				$tvars['vars']['selected_timeline_2'] = '';
				$tvars['vars']['style_twi_password'] = 'style="display:none"';
				$tvars['vars']['style_search'] = 'style="display:none"';
				break;
			}
			
			case 1:{
				$tvars['vars']['selected_timeline_0'] = '';
				$tvars['vars']['selected_timeline_1'] = 'selected';
				$tvars['vars']['selected_timeline_2'] = '';
				$tvars['vars']['style_twi_password'] = '';
				$tvars['vars']['style_search'] = 'style="display:none"';
				break;
			}
			
			case 2:{
				$tvars['vars']['selected_timeline_0'] = '';
				$tvars['vars']['selected_timeline_1'] = '';
				$tvars['vars']['selected_timeline_2'] = 'selected';
				$tvars['vars']['style_twi_password'] = 'style="display:none"';
				$tvars['vars']['style_search'] = '';
				break;
			}
		}

		$tpath = locatePluginTemplates(array('conf.main'), 'lasttweets', 1);   
		$tpl->template('conf.main', $tpath['conf.main']);
		$tpl->vars('conf.main', $tvars);
		print $tpl->show('conf.main');
}
	
function general_submit(){
	global $lang;

		if(isset($_POST['twi_username'], $_POST['cacheExpire'], $_POST['localsource'], $_POST['count'], $_POST['GMT'], $_POST['cache'], $_POST['timeline'], $_POST['search'])){ 
			pluginSetVariable('lasttweets', 'twi_username', trim($_POST['twi_username']));
			pluginSetVariable('lasttweets', 'cacheExpire', intval($_POST['cacheExpire']));
			pluginSetVariable('lasttweets', 'twi_password', $_POST['twi_password']);
			pluginSetVariable('lasttweets', 'localsource', $_POST['localsource']);
			pluginSetVariable('lasttweets', 'search', trim($_POST['search']));
			pluginSetVariable('lasttweets', 'count', intval($_POST['count']));
			pluginSetVariable('lasttweets', 'timeline', $_POST['timeline']);
			pluginSetVariable('lasttweets', 'GMT', intval($_POST['GMT']));
			pluginSetVariable('lasttweets', 'cache', $_POST['cache']);
						
			pluginsSaveConfig();
			msg(array('type' => 'info', 'info' => $lang['lasttweets:info_save_general']));
		}
}

function get_pin_code(){
	global $config;
	
	require_once('inc/twitteroauth.php');
	session_start();
	
	if(!isset($_REQUEST['oauth_token'], $_REQUEST['oauth_verifier'])){
		$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
		
		$request_token = $connection->getRequestToken($config['home_url'].'/engine/admin.php?mod=extra-config&plugin=lasttweets&action=get_pin_code');

		$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

		$url = $connection->getAuthorizeURL($token);

		echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
	} else {
	
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	
	pluginSetVariable('lasttweets', 'oauth_token', $access_token['oauth_token']);
	pluginSetVariable('lasttweets', 'oauth_token_secret', $access_token['oauth_token_secret']);
	pluginSetVariable('lasttweets', 'user_id', $access_token['user_id']);
	pluginsSaveConfig();
	
	unset($_SESSION['oauth_token']);
	unset($_SESSION['oauth_token_secret']);

	echo '<meta http-equiv="refresh" content="0; url='.$config['home_url'].'/engine/admin.php?mod=extra-config&plugin=lasttweets">';
	}
}
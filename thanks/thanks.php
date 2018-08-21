<?php
	/**
	* @descr Thanks for NG CMS
	* @author Vladimir.Kzi http://vladimir-kzi.org.ua/
	*/
	
// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

LoadPluginLang('thanks', 'main', '', '', ':');
register_plugin_page('thanks','','thanks_route');
loadPluginLibrary('uprofile', 'lib');


// =============================================================
// Route Index Page
// =============================================================v

function thanks_route(){
	global $userROW, $template, $lang;

	switch($_REQUEST['news_id']){
		case (is_numeric($_GET['news_id']))   : thanks_ajax();   break;
		default       : thanks_ajax();
	}

	return 0;
}

// =============================================================
// Ajax Thanks
// =============================================================

function thanks_ajax () {
	global $mysql, $ip, $twig, $lang, $tpl, $template, $PFILTERS, $parse, $userROW, $config, $TemplateCache;
	
	if(!$userROW['id']){
		msg(array("type" => "info", "info" => $lang['thanks:no_auth']));
		return 1;
		}

	if (!$_REQUEST['news_id']){
		msg(array("type" => "info", "info" => $lang['thanks:no_news']));
		return 1;
	  }
	  
	$post_id = $_REQUEST['news_id'];

	$check_news_id = $mysql->query("SELECT author FROM ".prefix."_news where id = ".db_squote($post_id)."");

	//$thxNews = mysql_fetch_array($check_news_id);
	while($thxNews = mysql_fetch_array($check_news_id)) {
	if ($thxNews['author'] != $userROW['name']) {

		$check_thanks = $mysql->query("SELECT id FROM ".prefix."_thanks WHERE id_post = ".db_squote($post_id)." AND user_name='".$userROW['name']."'");
		if(mysql_num_rows($check_thanks) == 0)
		{
			$mysql->query("INSERT INTO ".prefix."_thanks (`id_post`, `user_id`, `user_name`, `host_ip`) values (".db_squote($post_id).", '".$userROW['id']."', '".$userROW['name']."', '".$ip."')");
			$mysql->query("UPDATE ".prefix."_users SET thx_num = thx_num + 1  WHERE name='".$thxNews['author']."'");
			

		}
		else
		{
		msg(array("type" => "info", "info" => $lang['thanks:thanks_1']));
		return 1;
		}
	}
	else
	{ 
	msg(array("type" => "info", "info" => $lang['thanks:thanks_0']));
	return 1;
	}	
	}
}	

// =============================================================
// Thanks In News
// =============================================================

class ThanksNewsFilter extends NewsFilter {
        function showNews($newsID, $SQLnews, &$tvars) {
		         global $mysql, $ip, $twig, $lang, $tpl, $template, $PFILTERS, $parse, $userROW, $config, $TemplateCache;
				 
		$tpath = locatePluginTemplates(array('thanks', 'thanks'), 'thanks');
 
      if (!$userROW || $userROW['name'] == $SQLnews['author'])
	{
 			$template['regx']['/\[plugin_thanks\](.*?)\[\/plugin_thanks\]/si'] = '';		
	}
	else
	{
		$check_user = $mysql->query("SELECT id FROM ".prefix."_thanks WHERE id_post = '".$newsID."' AND user_name='".$userROW['name']."'");
		if (mysql_fetch_array($check_user) == 0)
		{
			$template['regx']['/\[plugin_thanks\](.*?)\[\/plugin_thanks\]/si'] = '$1';
		}
		else
		{
			$template['regx']['/\[plugin_thanks\](.*?)\[\/plugin_thanks\]/si'] = '';
		}
	}

	$link_thx = checkLinkAvailable('core', 'plugin')?
	generateLink('core', 'plugin', array('plugin' => 'thanks')):
	generateLink('core', 'plugin', array('plugin' => 'thanks'));
	
	/*$thanks_ajax .= <<<HTML
	<script type='text/javascript'>
function doCompletion() {
 $.ajax({url: '{$link_thx}?news_id={$newsID}', 	
 type: 'GET',	
 success: function(response)
 { alert('Вы сказали автору спасибо!');}
 });
 }
</script>
HTML;*/
$thanks_ajax .= <<<HTML
<script type='text/javascript'>
var thx_ajax = new sack();
function doThx(){
    thx_ajax.setVar("news_id", {$newsID});
    thx_ajax.requestFile = "{$link_thx}";
    thx_ajax.method = 'GET';
    thx_ajax.runAJAX();
	{ alert('Вы сказали автору спасибо!');}
    return false;
}
</script>
HTML;


	$sql_result_thx = $mysql->query("SELECT * FROM ".prefix."_thanks WHERE id_post = '".$newsID."' ORDER by id ASC");
	if (mysql_num_rows($sql_result_thx) > 0) {
	while ($thx = mysql_fetch_array($sql_result_thx)) 
		{ 
			$alink = checkLinkAvailable('uprofile', 'show')?
			generateLink('uprofile', 'show', array('name' => urlencode($thx['user_name']), 'id' => urlencode($thx['user_id']))):
			generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('name' => urlencode($thx['user_name']), 'id' => urlencode($thx['user_id'])));
			$thx_member .= "<a href=\"{$alink}\"><b>".$thx['user_name']."</b></a>, ";
			$all_members  = $thx_member;
                                                                                                                                 
			if (mysql_num_rows($sql_result_thx) > 10) {
				$all_members = "<div class=\"spoiler\"><div class=\"sp-head\" onclick=\"toggleSpoiler(this.parentNode, this);\"><b></b>Раскрыть</div><div class=\"sp-body\"><br /><!--QuoteBegin--><div class=\"quote\"><!--QuoteEBegin-->{$all_members}<!--QuoteEnd--></div><!--QuoteEEnd--></div></div>";
				}
		$thanks = $all_members;
		$thx_sayed = $lang['thanks:thanks'];
	}
	}
	else
	{
		$thanks = $lang['thanks:thanks_no'];
		$thx_sayed = "";
	}
	
		
		$tVars['vars'] = array(
        'thanks_ajax'		=>	$thanks_ajax,
		'thx_sayed'		=>	$thx_sayed,
		'thanks'		=>	$thanks
    );
	
	$tpl -> template('thanks', $tpath['thanks']);
    $tpl -> vars('thanks', $tVars);
	$output = $tpl -> show('thanks');
    $tvars['vars']['plugin_thanks'] = $output;
}

}
register_filter('news','thanks', new ThanksNewsFilter);

// =============================================================
// Thanks In uProfile
// =============================================================

class uThanksFilter extends p_uprofileFilter {
function showProfile($userID, $SQLrow, &$tvars) {
		//global $mysql, $twig, $lang, $tpl, $template, $PFILTERS, $parse, $userROW, $config, $TemplateCache;
    //$thx_num  = $mysql->query("SELECT thx_num FROM ".prefix."_users where id = '{$userID}'");
	//while ($thx = mysql_fetch_array($thx_num)){
	//$tvars['vars']['thanks'] = $thx['thx_num'];
	$tvars['vars']['plugin_thanks_num'] = $SQLrow['thx_num'];
	//}
		}
function showProfilePre($userID, $SQLrow) {
	$tvars['vars']['plugin_thanks_num'] = $SQLrow['thx_num'];
		}
	}
register_filter('plugin.uprofile','thanks', new uThanksFilter);

?>
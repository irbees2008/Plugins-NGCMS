<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

loadPluginLang('addnews', 'main', '', '', ':');

@include_once root.'includes/classes/upload.class.php';

register_plugin_page('addnews', '', 'addnews', 0);

$situation = "news";

function addnews_screen($status) {
	global $config, $tpl, $template, $PFILTERS, $lang, $catz, $catmap;

	// Determine paths for all template files
	$tpath	= locatePluginTemplates(array('addnews', 'addnews'), 'addnews', pluginGetVariable('addnews', 'localsource'), pluginGetVariable('addnews', 'skin')?pluginGetVariable('addnews', 'skin'):'default');

	$tvars['vars'] = array(
		'php_self'		=> $PHP_SELF,
		'tpl_url'		=> tpl_url,
		'smilies'		=> ($config['use_smilies'])?InsertSmilies('', 20, 'currentInputAreaID'):'',
		'quicktags'		=> ($config['use_bbcodes'])?QuickTags('currentInputAreaID', 'news'):'',
		'flag_approve'	=> (!checkPerm('approve', $status))?'disabled="disabled"':'',
		'flag_mainpage'	=> (!checkPerm('mainpage', $status))?'disabled="disabled"':''
	);

	$categories = pluginGetVariable('addnews', 'categories');

	if (is_array($categories) && checkPerm('categories', $status)) {
		$tvars['regx']['#\[categories\](.+?)\[\\/categories\]#is']	= '$1';
		
		$catout = '<select multiple="multiple" name="cats[]">';
		foreach ($categories as $cat) {
			$catout .= '<option value="'.$cat.'">'.$catz[$catmap[$cat]]['name'].'</option>';
		}
		$catout .= '</select>';
		
		$tvars['vars']['categories'] = $catout;
	} else
		$tvars['regx']['#\[categories\](.+?)\[\\/categories\]#is']	= '';
	
	$tvars['regx']['#\[altname\](.+?)\[\\/altname\]#is']		= (checkPerm('altname', $status))?'$1':'';
	$tvars['regx']['#\[meta\](.+?)\[\\/meta\]#is']				= ($config['meta'] && checkPerm('meta', $status))?'$1':'';

	if ($status == 0) {
		$tvars['regx']['#\[not-logged\](.+?)\[\\/not-logged\]#is']	= '$1';	
		//Fill name from comments cookies if not empty
		$tvars['vars']['savedname'] = ($_COOKIE['com_username'] && trim($_COOKIE['com_username']) != "")?secure_html(urldecode($_COOKIE['com_username'])):'';
	} else
		$tvars['regx']['#\[not-logged\](.+?)\[\\/not-logged\]#is']	= '';

	if (checkPerm('captcha', $status)) {
		$tvars['regx']['#\[captcha\](.+?)\[\\/captcha\]#is']	= '$1';
		$tvars['vars']['rand'] = rand(00000, 99999);
		$tvars['vars']['captcha_url'] = admin_url."/captcha.php?id=addnews";
		// Now let's generate our own code
		$_SESSION['captcha.addnews'] = $tvars['vars']['rand'];
	} else
		$tvars['regx']['#\[captcha\](.+?)\[\\/captcha\]#is']	= '';

	// Generate data for content input fields
	if ($config['news.edit.split']) {
		$tvars['regx']['#\[edit\.split\](.+?)\[\\/edit\.split\]#is']		= '$1';
		$tvars['regx']['#\[edit\.nosplit\](.+?)\[\\/edit\.nosplit\]#is']	= '';
	} else {
		$tvars['regx']['#\[edit\.split\](.+?)\[\\/edit\.split\]#is']		= '';
		$tvars['regx']['#\[edit\.nosplit\](.+?)\[\\/edit\.nosplit\]#is']	= '$1';
	}

	// Run interceptors
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) { $v->addNewsForm($tvars); }

	$tpl -> template('addnews', $tpath['addnews']);
	$tpl -> vars('addnews', $tvars);
	$template['vars']['mainblock'] = $tpl -> show('addnews');
}

function doAdd() {
	global $mysql, $AUTH_METHOD, $config, $lang, $userROW, $parse, $PFILTERS, $catz, $catmap;

	// Check membership
	// If login/pass is entered (either logged or not)
	if ($_REQUEST['name'] && $_REQUEST['password']) {
		$auth	= $AUTH_METHOD[$config['auth_module']];
		$user	= $auth->login(0, $_REQUEST['name'], $_REQUEST['password']);
		if (!is_array($user)) {
			msg(array("type" => "error", "text" => $lang['addnews:msge_password']));
			return;
		}
	}

	// Entered data have higher priority then login data
	if (is_array($user)) {
		$SQL['author']			= $user['name'];
		$SQL['author_id']		= $user['id'];
		$status					= $user['status'];		
	} else if (is_array($userROW)) {
		$SQL['author']			= $userROW['name'];
		$SQL['author_id']		= $userROW['id'];
		$status					= $userROW['status'];		
	} else {
		$SQL['author']			= secure_html(convert(trim($_REQUEST['name'])));
		$SQL['author_id']		= 0;
		$status					= 0;
	}

	// If user is not logged, make some additional tests
	if (!$status) {
		if (!$SQL['author']) {
			msg(array("type" => "error", "text" => $lang['addnews:msge_name']));
			return;
		}
		// Check if author name use incorrect symbols. Check should be done only for unregs
		if ((!$SQL['author_id']) && (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/", $SQL['author']) || strlen($SQL['author']) > 60)) {
			msg(array("type" => "error", "text" => $lang['addnews:msge_badname']));
			return;
		}
	}
	
	// Check captcha for unregistered visitors
	if (checkPerm('captcha', $status)) {
		$vcode = $_REQUEST['vcode'];
		if ((!$vcode) || ($vcode != $_SESSION['captcha.addnews'])) {
			// Wrong CAPTCHA code (!!!)
			msg(array("type" => "error", "text" => $lang['addnews:msge_vcode']));				
			return;
		}
	}
	
	$title = $_REQUEST['title'];

	// Fill content
	$content	= '';

	// Check if EDITOR SPLIT feature is activated
	if ($config['news.edit.split']) {
		// Prepare delimiter
		$ed = '<!--more-->';
		$content = $_REQUEST['ng_news_content_short'].(($_REQUEST['ng_news_content_full'] != '')?$ed.$_REQUEST['ng_news_content_full']:'');

	} else {
		$content = $_REQUEST['ng_news_content'];
	}

	// Rewrite `\r\n` to `\n`
	$content = str_replace("\r\n", "\n", $content);

	// Check title
	if ( (!strlen(trim($title))) || (!strlen(trim($content))) ) {
		msg(array("type" => "error", "text" => $lang['addnews:msge_fields']));
		return 0;
	}

	$SQL['title']	= $title;
	$SQL['content']	= $content;

	$alt_name = $parse->translit(trim($_REQUEST['alt_name']), 1);

	// Check for dup if alt_name is specified
	if ($alt_name) {
		if ( is_array($mysql->record("select id from ".prefix."_news where alt_name = ".db_squote($alt_name)." limit 1")) ) {
			msg(array("type" => "error", "text" => $lang['addnews:msge_alt_name']));
			return;
		}
		$SQL['alt_name'] = $alt_name;
	} else {
		// Generate uniq alt_name if no alt_name specified
		$alt_name = strtolower($parse->translit(trim($title), 1));
		// Make a conversion:
		// * '.'  to '_'
		// * '__' to '_' (several to one)
		// * Delete leading/finishing '_'
		$alt_name = preg_replace(array('/\./', '/(_{2,20})/', '/^(_+)/', '/(_+)$/'), array('_', '_'), $alt_name);

		// Make alt_name equal to '_' if it appear to be blank after conversion
		if ($alt_name == '') $alt_name = '_';

		$i = '';
		while ( is_array($mysql->record("select id from ".prefix."_news where alt_name = ".db_squote($alt_name.$i)." limit 1")) ) {
			$i++;
		}
		$SQL['alt_name'] = $alt_name.$i;
	}

	$SQL['postdate'] = time() + ($config['date_adjust'] * 60);

	$SQL['approve']		= (checkPerm('approve', $status))?intval($_REQUEST['approve']):'';
	$SQL['mainpage']	= (checkPerm('mainpage', $status))?intval($_REQUEST['mainpage']):'';

	if ($config['meta'] && checkPerm('meta', $status)) {
		$SQL['description']	= $_REQUEST['description'];
		$SQL['keywords']	= $_REQUEST['keywords'];
	}

	
	$catids = array ();
	$cats	= $_REQUEST['cats'];

	if ($cats)
		foreach ($cats as $cat) {
			$catids[intval($cat)] = 1;
		}
	
	$SQL['catid']		= implode(",", array_keys($catids));

	exec_acts('addnews');

	$pluginNoError = 1;
	if (is_array($PFILTERS['news']))
		foreach ($PFILTERS['news'] as $k => $v) {
			if (!($pluginNoError = $v->addNews($tvars, $SQL))) {
				msg(array("type" => "error", "text" => str_replace('{plugin}', $k, $lang['addnews:msge_pluginlock'])));
				break;
			}
		}

	if (!$pluginNoError) {
		return 0;
	}

	$vnames = array(); $vparams = array();
	foreach ($SQL as $k => $v) { $vnames[]  = $k; $vparams[] = db_squote($v); }

	$mysql->query("insert into ".prefix."_news (".implode(",",$vnames).") values (".implode(",",$vparams).")");
	$id = $mysql->result("SELECT LAST_INSERT_ID() as id");

	// Notify plugins about adding new news
	if (is_array($PFILTERS['news']))
	foreach ($PFILTERS['news'] as $k => $v) { $v->addNewsNotify($tvars, $SQL, $id); }

	msg(array("text" => $lang['addnews:msgo_add']));

	$_SESSION['captcha.addnews'] = rand(00000, 99999);
	@setcookie("com_username", urlencode($SQL['author']), 0, '/');

	return 1;

}

function addnews() {
	global $userROW, $lang, $SYSTEM_FLAGS;

	$SYSTEM_FLAGS['info']['title']['group'] = $lang['addnews:header.title'];

	$status = is_array($userROW)?$userROW['status']:0;

	if(checkPerm('user', $status, true)){
		switch($_REQUEST['action']) {
			case "doAdd"	: doAdd();   break;
			default			: addnews_screen($status);
		}
	}

	return 0;
}

function checkPerm($action, $status, $alert = false) {
	global $lang;

	$perm = pluginGetVariable('addnews', 'perm');

	if (is_array($perm[$action]) && in_array($status, $perm[$action])) {
		return 1;
	} else {
		if ($alert)
			msg(array("type" => "error", "text" => $lang['addnews:msge.status']));
	}

	return;
}
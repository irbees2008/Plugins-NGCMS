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

@include_once root.'includes/classes/upload.class.php';
register_plugin_page('addnews_2','','addnews_2');
add_act('index_post', 'addnews_2_header_show');

function addnews_2_header_show()
{global $SYSTEM_FLAGS, $template;
	$template['vars']['titles'] = trim(str_replace(
				array ('%home%'),
				array ($SYSTEM_FLAGS['info']['title']['header']),
				pluginGetVariable('addnews_2', 'titles')));
}

function addnews_2()
{global $SYSTEM_FLAGS, $CurrentHandler, $template, $tpl, $mysql, $config, $userROW, $parse, $catmap, $PFILTERS;
	
	switch($CurrentHandler['handlerParams']['value']['pluginName'])
	{
		case 'core': 
			if(checkLinkAvailable('addnews_2', ''))
			{
				return redirect_addnews_2(generateLink('addnews_2', ''));
			} break;
	}
	
	$tpath = locatePluginTemplates(array('addnews', 'no_access', 'preview'), 'addnews_2', pluginGetVariable('addnews_2', 'localsource'));
	$access = pluginGetVariable('addnews_2', 'access');
	$users_access = $access[intval($userROW['status'])];
	
	//print "<pre>".var_export($_SESSION['addnews_2'], true)."</pre>";
	if(isset($_SESSION['addnews_2']) && !empty($_SESSION['addnews_2']))
	{
		foreach($_SESSION['addnews_2'] as $key => $val)
		{
			switch($key)
			{
				case 'info': $info .= msg(array("type" => "info", "info" => $val), 0, 2); break;
				case 'error': $info .= msg(array("type" => "error", "text" => $val), 0, 2); break;
			}
		}
		unset($_SESSION['addnews_2']);
	} else {
		$info = '';
	}
	
	if( isset($users_access['send']) )
	{
		$tvars['vars']['preview'] = '';
		if( isset($_REQUEST['preview']) )
		{
			$title = secure_html($_REQUEST['title']);
			$alt_name = $parse->translit(trim($_REQUEST['alt_name']), 1);
			$description	= secure_html($_REQUEST['description']);
			$keywords	= secure_html($_REQUEST['keywords']);
			$mainpage = intval($_REQUEST['mainpage']);
			$approve = intval($_REQUEST['approve']);
			$catids = array ();
			if (intval($_POST['category']) && isset($catmap[intval($_POST['category'])])) {
				$catids[intval($_POST['category'])] = 1;
			}
			
			foreach ($_POST as $k => $v){
				if (preg_match('#^category_(\d+)$#', $k, $match) && $v && isset($catmap[intval($match[1])]))
					$catids[$match[1]] = 1;
			}
			
			$SQL['catid'] = implode(",", array_keys($catids));
			
			$cats = explode(",", $SQL['catid']);
			
			if(false)
			{
				$short = $parse -> bbcodes($_REQUEST['ng_news_content_short']);
				$short = $parse -> smilies($short);
				$full = $parse -> bbcodes($_REQUEST['ng_news_content_full']);
				$full = $parse -> smilies($full);
				
				$short_l = $short;
				$full_l = $full;
			} else {
				$content = $_REQUEST['ng_news_content'];
				$content_l = nl2br($parse -> smilies($parse -> bbcodes($_REQUEST['ng_news_content'])));
			}
			
			$ttvars['vars']['content'] = (false)?secure_html($short_l).(($full_l != '')?'<!--more-->'.secure_html($full_l):''):$content_l;
			
			$tpl -> template('preview', $tpath['preview']);
			$tpl -> vars('preview', $ttvars);
			$tvars['vars']['preview'] = $tpl -> show('preview');
		}
		
		if (isset($_REQUEST['submit'])){
			
			if(false)
				$content = $_REQUEST['ng_news_content_short'].(($_REQUEST['ng_news_content_full'] != '')?'<!--more-->'.$_REQUEST['ng_news_content_full']:'');
			else
				$content = $_REQUEST['ng_news_content'];
			
			$SQL['content'] = str_replace("\r\n", "\n", $content);
			
			if (empty($SQL['content']))
				$error_text[] = 'Сообщение не заполнено';
			
			$title = secure_html($_REQUEST['title']);
			
			$SQL['title'] = $title;
			
			if(empty($SQL['title']))
				$error_text[] = 'Заголовок не заполнен';
			
			$alt_name = $parse->translit(trim($_REQUEST['alt_name']), 1);
			
			if ($alt_name){
				if ( is_array($mysql->record("select id from ".prefix."_news where alt_name = ".db_squote($alt_name)." limit 1")) )
					$error_text[] = 'Какая то проблема с альтернативным именем';
				
				$SQL['alt_name'] = $alt_name;
			} else {
				$alt_name = strtolower($parse->translit(trim($title), 1));
				
				$alt_name = preg_replace(array('/\./', '/(_{2,20})/', '/^(_+)/', '/(_+)$/'), array('_', '_'), $alt_name);
				
				if ($alt_name == '') $alt_name = '_';
				
				$i = '';
				while ( is_array($mysql->record("select id from ".prefix."_news where alt_name = ".db_squote($alt_name.$i)." limit 1")) ) {
					$i++;
				}
				$alt_name = $alt_name.$i;
			}
			
			$SQL['alt_name'] = $alt_name;
			$SQL['postdate'] = time() + ($config['date_adjust'] * 60);
			
			$catids = array ();
			if (intval($_POST['category']) && isset($catmap[intval($_POST['category'])])) {
				$catids[intval($_POST['category'])] = 1;
			}
			
			foreach ($_POST as $k => $v){
				if (preg_match('#^category_(\d+)$#', $k, $match) && $v && isset($catmap[intval($match[1])]))
					$catids[$match[1]] = 1;
			}
			if($users_access['meta'])
			{
				$description	= secure_html($_REQUEST['description']);
				$keywords	= secure_html($_REQUEST['keywords']);
				$SQL['description'] = $description;
				$SQL['keywords'] = $keywords;
			}
			
			$SQL['author']		= !empty($userROW['name'])?$userROW['name']:'Гость';
			$SQL['author_id']	= !empty($userROW['id'])?intval($userROW['id']):0;
			$SQL['catid']		= implode(",", array_keys($catids));
			$cats = explode(",", $SQL['catid']);
			//print "<pre>".var_export($SQL['catid'], true)."</pre>";
			if($users_access['mainpage'])
				$mainpage = intval($_REQUEST['mainpage']);
				$SQL['mainpage'] = $mainpage;
			
			if($users_access['approve'])
				$approve = intval($_REQUEST['approve']);
				$SQL['approve'] = $approve;
			
			if($users_access['captcha'])
			{
				if($_SESSION['captcha'] != $_REQUEST['vcode'])
					$error_text[] = 'проверочный код не верный';
			}
			
			if($users_access['protec_bot'])
			{
				
				if (is_numeric($_REQUEST['result']))
				{
					if ((intval($_REQUEST['result']))===(intval($_SESSION['res']))) 
					{
						unset($_SESSION['res']);
					} else  {
						$error_text[] = 'Учи математику<br />Правильный ответ: '.$_SESSION['res'];
						unset($_SESSION['res']);
						unset($_REQUEST['result']);
					}
				} else {
					$error_text[] = 'Учи математику<br />Правильный ответ: '.$_SESSION['res'];
				}
			}
			
			if(empty($error_text))
			{
				$vnames = array(); $vparams = array();
				foreach ($SQL as $k => $v) { $vnames[]  = $k; $vparams[] = db_squote($v); }
				
				$mysql->query("insert into ".prefix."_news (".implode(",",$vnames).") values (".implode(",",$vparams).")");
				$id = $mysql->result("SELECT LAST_INSERT_ID() as id");
				
				if ($SQL['approve']){
					if (count($catids)){
						$mysql->query("update ".prefix."_category set posts=posts+1 where id in (".implode(", ",array_keys($catids)).")");
						foreach (array_keys($catids) as $catid) {
							$mysql->query("insert into ".prefix."_news_map (newsID, categoryID) values (".db_squote($id).", ".db_squote($catid).")");
						}
					}
					if(is_array($userROW))
						$mysql->query("update ".uprefix."_users set news=news+1 where id=".$SQL['author_id']);
				}
				
				$fmanager = new file_managment();
				
				if (is_array($_FILES['userfile']['name']))
					foreach($_FILES['userfile']['name'] as $i => $v) {
						if ($v == '')
							continue;
						
						
						$flagUpdateAttachCount = true;
						$up = $fmanager->file_upload(array('dsn' => true, 'linked_ds' => 1, 'linked_id' => $id, 'type' => 'file', 'http_var' => 'userfile', 'http_varnum' => $i));
						if (!is_array($up)) {
							$error_text[] = 'Из за ошибки файлы не были прикреплены';
							$_SESSION['addnews_2']['error'] = str_replace( array('{user}'), array($SQL['author']), 'Ошибка при загрузке файлов');
						}
						
					}
				
				if ($flagUpdateAttachCount){
					$numFiles = $mysql->result("select count(*) as cnt from ".prefix."_files where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($id).")");
					$numImages = $mysql->result("select count(*) as cnt from ".prefix."_images where (storage=1) and (linked_ds=1) and (linked_id=".db_squote($id).")");
					
					$mysql->query("update ".prefix."_news set num_files = ".intval($numFiles)." where id = ".db_squote($id));
					$mysql->query("update ".prefix."_news set num_images = ".intval($numImages)." where id = ".db_squote($id));
				}
				$_SESSION['addnews_2']['info'] = $SQL['author'].' Новость успешно добавлена';
				redirect_addnews_2(link_addnews_2());
			}
		} else {
			
		}
		
		$tvars['vars']['categories'] = makeCategoryList(array('doempty' => 1, 'nameval' => 0,   'selected' => count($cats)?$cats[0]:0));
		$tvars['vars']['addit_category'] = makeCategoryList(array('nameval' => 0, 'checkarea' => 1, 'selected' => (count($cats)>1)?array_slice($cats,1):array()));
		
		if($users_access['meta'])
			$tvars['regx']['/\[meta\](.*?)\[\/meta\]/si'] = '$1';
		else
			$tvars['regx']['/\[meta\](.*?)\[\/meta\]/si'] = '';
		
		if($users_access['approve'])
			$tvars['regx']['/\[approve\](.*?)\[\/approve\]/si'] = '$1';
		else
			$tvars['regx']['/\[approve\](.*?)\[\/approve\]/si'] = '';
		
		if($users_access['mainpage'])
			$tvars['regx']['/\[mainpage\](.*?)\[\/mainpage\]/si'] = '$1';
		else
			$tvars['regx']['/\[mainpage\](.*?)\[\/mainpage\]/si'] = '';
		
		if($users_access['captcha'])
		{
			$_SESSION['captcha'] = rand(00000, 99999);
			$tvars['vars']['captcha_url'] = admin_url.'/captcha.php';
			$tvars['regx']['/\[captcha\](.*?)\[\/captcha\]/si'] = '$1';
		} else {
			$tvars['regx']['/\[captcha\](.*?)\[\/captcha\]/si'] = '';
		}
		
		if($users_access['protec_bot'])
		{
			$tvars['regx']['/\[protec_bot\](.*?)\[\/protec_bot\]/si'] = '$1';
			$one = rand(0,20);
			$two = rand(0,20);
			
			if (rand(0,1)>0)
			{
				$result = "$one+$two";
				$_SESSION['res'] = $one+$two;
			}
			else
			{
				$result = "$one-$two";
				$_SESSION['res'] =$one-$two;
			}
			
			$tvars['vars']['result'] = $result;
		} else {
			$tvars['regx']['/\[protec_bot\](.*?)\[\/protec_bot\]/si'] = '';
		}
		
		if(!empty($error_text))
		{
			foreach($error_text as $error)
			{
				$error_input .= msg(array("type" => "error", "text" => $error), 0, 2);
			}
		} else {
			$error_input = '';
		}
		
		$tvars['vars']['error'] = $error_input;
		$tvars['vars']['info'] = $info;
		$tvars['vars']['title'] = $title;
		$tvars['vars']['alt_name'] = $alt_name;
		$tvars['vars']['quicktags'] = BBTags('currentInputAreaID', 'news');
		$tvars['vars']['smilies'] = InsertSmilies('', 20, 'currentInputAreaID');
		$tvars['vars']['description'] =  $description;
		$tvars['vars']['keywords'] = $keywords;
		$tvars['vars']['mainpage'] = empty($mainpage)?'':'checked="checked"';
		$tvars['vars']['approve'] = empty($approve)?'':'checked="checked"';
		if(false){
			$tvars['vars']['short'] = $_REQUEST['ng_news_content_short'];
			$tvars['vars']['full'] = $_REQUEST['ng_news_content_full'];
		} else {
			$tvars['vars']['short'] = '';
			$tvars['vars']['full'] = '';
			$tvars['vars']['content'] = $content;
		}
		
		if (is_array($PFILTERS['news']))
			foreach ($PFILTERS['news'] as $k => $v) { $v->addNewsForm($tvars); }
		
		print "<pre>".var_export($tvars['plugin']['xfields']['0'], true)."</pre>";
		
		$tpl -> template('addnews', $tpath['addnews']);
		$tpl -> vars('addnews', $tvars);
		$template['vars']['mainblock'] = $tpl -> show('addnews');
	} else {
		$tpl -> template('no_access', $tpath['no_access']);
		$tpl -> vars('no_access', $tvars);
		$template['vars']['mainblock'] = $tpl -> show('no_access');
	}
}

function redirect_addnews_2($url)
{
	if (headers_sent()) {
		echo "<script>document.location.href='{$url}';</script>\n";
		exit;
	} else {
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: {$url}");
		exit;
	}
}

function link_addnews_2()
{
	$componentsURL = checkLinkAvailable('addnews_2', '')?
					generateLink('addnews_2', ''):
					generateLink('core', 'plugin', array('plugin' => 'addnews_2'));
	
	return $componentsURL;
}

function BBTags($area = false, $template = false) {
	global $config, $lang, $tpl, $PHP_SELF;

	$tvars['vars'] = array(
		'php_self'	=>	$config['admin_url'].'/admin.php',
		'area'		=>	$area?$area:"''"
	);

	if (!in_array($template, array('pmmes', 'editcom', 'news', 'static')))
		return false;

	$tplname = 'qt_'.$template;

	$tpl->template($tplname, tpl_actions);
	$tpl->vars($tplname, $tvars);
	return $tpl->show($tplname);
}
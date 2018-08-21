<?php
// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');
add_act('index', 'plugin_zakaz_showScreen');
//register_plugin_page('zakaz','','plugin_zakaz_screen',1);
register_plugin_page('zakaz','','plugin_zakaz_post',1);
loadPluginLang('zakaz', 'main', '', '', ':');
function plugin_zakaz_showScreen($mode = 9, $errorText = '') {
global $tpl, $template, $mysql, $lang, $config,$TemplateCache,$CurrentHandler,$catz,$catmap;
	// Determine paths for all template files
	$tpath = locatePluginTemplates(array('zakazform'), 'zakaz', extra_get_param('zakaz', 'localsource'));
if ((!$mode)||($mode<2))
{
	if (isset($CurrentHandler['params']['catid'])) 
	{
		$iscat['cat'] = true;
		$catid = $CurrentHandler['params']['catid'];
	}
	else if (isset($CurrentHandler['params']['category'])) 
	{
		$iscat = true;
		$catid = array_search($CurrentHandler['params']['category'], $catmap);
	}
	else
	{
		$iscat = false;
		$catid = 0;
	}

}
 $captcha = '';
		$tvars = array();
		$tvars['vars']['rand'] = rand(00000, 99999);
		$tvars['vars']['captcha_url'] = admin_url."/captcha.php?id=zakaz";
		$_SESSION['captcha.zakaz'] = rand(00000, 99999);
	 $tvars['vars']['catid']=$catid;
	$tvars['vars']['zname']=($mode<2)?secure_html($_REQUEST['zname']):'';
	$tvars['vars']['zemail']=($mode<2)?secure_html($_REQUEST['zemail']):'';
	$tvars['vars']['phone']=($mode<2)?secure_html($_REQUEST['phone']):'';
	$tvars['vars']['zakazmes']=($mode<2)?secure_html($_REQUEST['zakazmes']):'';
	$tvars['vars']['captcha']		= $captcha;
	$tvars['vars']['form_url']		= generateLink('core', 'plugin', array('plugin' => 'zakaz'), array());
	
	
	if ((!$mode)||($mode==9))
	{
	$tvars['vars']['errorText']	= $errorText;
	$tvars['regx']['#\[error\](.*?)\[\/error\]#is']	= ($errorText == '')?'':'$1';
		$tvars['regx']['#\[isform\](.*?)\[\/isform\]#is']='$1';
		$tvars['regx']['#\[issend\](.*?)\[\/issend\]#is']='';
		$tpl->template('zakazform', $tpath['zakazform']);
		$tpl->vars('zakazform', $tvars);
		$template['vars']['showformzakaz']=$tpl->show('zakazform');

		
	} 
	elseif ($mode<2)
	{
	$tvars['vars']['errorText']	= $errorText;
	$tvars['regx']['#\[error\](.*?)\[\/error\]#is']	= ($errorText == '')?'':'$1';
		$tvars['regx']['#\[isform\](.*?)\[\/isform\]#is']='$1';
		$tvars['regx']['#\[issend\](.*?)\[\/issend\]#is']='';
		$tpl->template('zakazform', $tpath['zakazform']);
		$tpl->vars('zakazform', $tvars);
		$template['vars']['mainblock']=$tpl->show('zakazform');
	}
	else
	{
	$tvars['regx']['#\[error\](.*?)\[\/error\]#is']	= '';
		$tvars['vars']['zname']	=secure_html($_REQUEST['zname']);
		$tvars['vars']['zemail']=secure_html($_REQUEST['zemail']);
		$tvars['vars']['phone']	= secure_html($_REQUEST['phone']);
		$tvars['vars']['zakazmes']=secure_html($_REQUEST['zakazmes']).(($errorText == '')?'':' ‘‡ÈÎ '.$errorText);
		$tvars['regx']['#\[isform\](.*?)\[\/isform\]#is']= '';
		$tvars['regx']['#\[issend\](.*?)\[\/issend\]#is']='$1';
		$tpl->template('zakazform', $tpath['zakazform']);
		$tpl->vars('zakazform', $tvars);
		$template['vars']['mainblock']=$tpl->show('zakazform');
	}
}
function plugin_zakaz_post() 
{
	global $template, $tpl, $lang, $mysql, $userROW, $SYSTEM_FLAGS,$catz,$config;
	$tpath = locatePluginTemplates(array('site.infoblock','zakazform','htmail'), 'zakaz', extra_get_param('zakaz', 'localsource'));
	$SYSTEM_FLAGS['info']['title']['group']		= $lang['zakaz:header.title'];
	$SYSTEM_FLAGS['info']['title']['item']	= str_replace('{title}','«‡Í‡Á', $lang['zakaz:header.send']);
		$vcode = $_REQUEST['vcode'];
		if (!$_REQUEST['vcode']&& empty($_REQUEST['zname']) && empty($_REQUEST['zakazmes']))
		{
			plugin_zakaz_showScreen(1, $lang['zakaz:startzakaz']);	
			return 0;
		}
		if ($vcode != $_SESSION['captcha.zakaz']) {
			// Wrong CAPTCHA code (!!!)
			plugin_zakaz_showScreen(1, $lang['zakaz:sform.captcha.badcode']);
			return;
		}
		if(empty($_REQUEST['zname'])||strlen($_REQUEST['zname'])<2||!preg_match('/^[¿-ﬂ‡-ˇ\s]+$/', $_REQUEST['zname']))
		{
			plugin_zakaz_showScreen(1,$lang['zakaz:nofio']);
			return;
		}
		if(!preg_match('/^[\.\-_A-Za-z0-9]+?@[\.\-A-Za-z0-9]+?\.[A-Za-z0-9]{2,6}$/', $_REQUEST['zemail']))
		{
			if(!preg_match('/^[0-9-+()\s]{5,25}$/', $_REQUEST['phone']))
			{
				plugin_zakaz_showScreen(1,$lang['zakaz:notelmyl']);
			return;
			}
		}
		if(empty($_REQUEST['zakazmes'])&& !$_FILES['attach']['name'])
		{
			plugin_zakaz_showScreen(1,$lang['zakaz:nozakaz']);
			return;
		}
		if ($_FILES['attach']['name'])
		{
			$fname	= $_FILES['attach']['name'];
			$fsize	= $_FILES['attach']['size'];
			$ftype	= $_FILES['attach']['type'];
			$ftmp	= $_FILES['attach']['tmp_name'];
			$ferr	= $_FILES['attach']['error'];
			
			$fil = explode(".", strtolower($fname));
			$ext = count($fil)?array_pop($fil):'';
			$allowexts=array();
			$allowexts=explode(',',extra_get_param('zakaz', 'allowexts'));
			$maxf=extra_get_param('zakaz', 'maxf')*1024;
			 
			if($fsize>$maxf)
			{
				@unlink($ftmp);
				plugin_zakaz_showScreen(1, $lang['zakaz:uplmaxsize'].extra_get_param('zakaz', 'maxf').'  ·');
				return;
			}
			elseif (!in_array($ext,$allowexts))
			{
				@unlink($ftmp);
				plugin_zakaz_showScreen(1,$lang['zakaz:noallext'].extra_get_param('zakaz', 'allowexts'));
				return;
			}
			elseif (strpos($ftype,'php')||strpos($ftype,'video')||strpos($ftype,'image')||strpos($ftype,'audio')||strpos($ftype,'html')||strpos($ftype,'octet')||strpos($ftype,'perl'))
			{
				@unlink($ftmp);
				plugin_zakaz_showScreen(1, $lang['zakaz:noallext']);
				return;
			}
			elseif (!$ftmp || !file_exists($ftmp)) 
			{
				
				plugin_zakaz_showScreen(1, $lang['zakaz:uplservtrouble']);
			return;
			}
			elseif(!move_uploaded_file($ftmp, $config['files_dir'].'/'.'mail_'.$fname)) 
			{
				plugin_zakaz_showScreen(1, $lang['zakaz:uplservtrouble']);
			}
			else
			{
				$ftmp=$config['files_dir'].'/'.'mail_'.$fname;
			}
		}
	$catname=($_REQUEST['catid'])?$catz[$_REQUEST['catid']]['name']:'';
	$zmes=preg_replace('/\n/', "<br>", secure_html($_REQUEST['zakazmes']));
	$output = '';
	$mailSubject =  $lang['zakaz:mail.subj'];
	$mailBody = '';
		$tmvars = array('vars' => array(
					'zname'			=> secure_html($_REQUEST['zname']),
					'zemail'			=> secure_html($_REQUEST['zemail']),
					'phone'			=> secure_html($_REQUEST['phone']),
					'zakazmes'			=>$zmes.(($_FILES['attach']['name'])?'<br>‘‡ÈÎ '.$fname:''),
					'from'			=> getenv("HTTP_REFERER").''.$catname,
					'ip' =>getenv("REMOTE_ADDR")
		));

		$tpl->template('htmail', $tpath['htmail']);
		$tpl->vars('htmail', $tmvars);
		$mailBody .= $tpl->show('htmail');



	// Select recipient group
	$em = explode(',',PluginGetVariable('zakaz','maillist'));
	$mailCount = 0;
	foreach ($em as $email) {
		if (trim($email) == '')
			continue;

		$mailCount++;
		zzMail($email, $mailSubject, $mailBody,(($ftmp)?$ftmp:''), false, 'text/html');
//		zzMail($email, $mailSubject, $mailBody,(($fname)?$fname:''), false, 'text/html');
	}
if ($mailCount>0)
{
	if ($_FILES['attach']['name'])
		@unlink($ftmp);
	plugin_zakaz_showScreen(2,$fname);
	return;
}
else
{
	if ($_FILES['attach']['name'])
		@unlink($ftmp);
	plugin_zakaz_showScreen(1, $lang['zakaz:uplservtrouble']);
	return;
}
}
?>
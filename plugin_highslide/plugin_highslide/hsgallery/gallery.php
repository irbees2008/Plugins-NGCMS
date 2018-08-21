<?php
//HighSlide Preview And Gallery
//(c)CyberMama
// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');
add_act('index', 'plugin_hsgallery');
function plugin_hsgallery(){
	global $template,$config, $mysql, $tpl, $lang,$CurrentHandler;
$viewmode = extra_get_param('hsgallery','viewmode');
$fotocats=array();
$fotocats=explode(',',extra_get_param('hsgallery','folders'));
$hscolor=extra_get_param('hsgallery','hscolor');
$enhsglobal=extra_get_param('hsgallery','enhsglobal');
$tplName='hsscript_'.$viewmode;
$tplG=($viewmode==4)?'gallery_inpage':'gallery';
$tplE=($viewmode==4)?'entries_inpage':'entries';
$hsc1=($hscolor)?'dark':'white';
$hsc2=($hscolor)?'glossy-dark':'rounded-white';
if ($CurrentHandler['pluginName']=='static' AND in_array($CurrentHandler['params']['altname'],$fotocats))
{ 
$cacheFileName = md5('hsgallery'.$config['theme'].$config['default_lang'].$year.$month).'.txt';
if (extra_get_param('hsgallery','cache')) {
		$cacheData = cacheRetrieveFile($cacheFileName, extra_get_param('hsgallery','cacheExpire'), 'hsgallery');
		if ($cacheData != false) {
			// We got data from cache. Return it and stop
			$template['vars']['plugin_hsgallery'] = $cacheData;
			return;
		}
	}
	$fotocat=$CurrentHandler['params']['altname'];
	$tpath = locatePluginTemplates(array('entries', 'gallery','entries_inpage', 'gallery_inpage', 'hsscript_0','hsscript_1','hsscript_2','hsscript_3','hsscript_4'), 'hsgallery',extra_get_param('hsgallery', 'localsource'));
	$tvars['vars'] = array ( 'admin_url' => admin_url,'hsc1' => $hsc1,'hsc2' => $hsc2);
	$tpl -> template($tplName, $tpath[$tplName]);
	$tpl -> vars($tplName, $tvars);
	$output = $tpl -> show($tplName);
	$template['vars']['htmlvars'] = $output;
$result = '';
$first=true;
	foreach ($mysql->select("select * from ".prefix."_images  
	where  folder ='".$fotocat."' order by  id desc") as $row) {
	$title=$row['description'];
	$fullurl="/uploads/images/".$fotocat."/".$row['orig_name'];
	$thurl="/uploads/images/".$fotocat."/thumb/".$row['orig_name'];
	$fid=($first)?'id="thumb1"':'';
	$first=false;
    $tvars['vars'] = array(
			'link'		=>	$fullurl,
			'linkth'	=>$thurl,
			'alt'		=>	$title,
			'fid'       =>$fid
		);  
		$tpl -> template($tplE, $tpath[$tplE]);
		$tpl -> vars($tplE, $tvars);
		$result .= $tpl -> show($tplE);
	
  }
	unset($tvars);
	$tvars['vars'] = array ( 'tpl_url' => tpl_url, 'entries' => $result);
	$tpl -> template($tplG, $tpath[$tplG]);
	$tpl -> vars($tplG, $tvars);
	$output = $tpl -> show($tplG);
	$template['vars']['plugin_hsgallery'] = $output;
	
		if (extra_get_param('hsgallery','cache')) {
		cacheStoreFile($cacheFileName, $output, 'hsgallery');
	}
}
else
{
	if ($enhsglobal)
	{
	$globalmode = extra_get_param('hsgallery','globalmode');
	$tplName='hsscript_'.$globalmode;
	$tpath = locatePluginTemplates(array('entries', 'gallery','entries_inpage', 'gallery_inpage', 'hsscript_0','hsscript_1','hsscript_2','hsscript_3','hsscript_4'), 'hsgallery',extra_get_param('hsgallery', 'localsource'));
	$tvars['vars'] = array ( 'admin_url' => admin_url,'hsc1' => $hsc1,'hsc2' => $hsc2);
	$tpl -> template($tplName, $tpath[$tplName]);
	$tpl -> vars($tplName, $tvars);
	$output = $tpl -> show($tplName);
	$template['vars']['htmlvars'] = $output;
   }
	$template['vars']['plugin_hsgallery'] ='';
}
}




<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');
	
//
// Configuration file for plugin
//

pluginsLoadConfig();
LoadPluginLang('social', 'config', '', '', ':');

switch ($_REQUEST['action']) {
	case 'list':           showList(); break;
	case 'about':          about(); break;
	case 'add':            edit(); break;
	case 'editSubmit':     editSubmit(); showList(); break;
	case 'del':            delete(); break;
	
	case 'moveUp':         move('up'); showList(); break;
	case 'moveDown':       move('down'); showList(); break;

	case 'restore':        restore(); showList(); break;	
	case 'clearCache':     clearCache(); main(); break;
	case 'generalSubmit':  generalSubmit(); main(); break;
	
	default: main();
}

function main() {
	global $tpl, $lang;

	$skList = array();
	if ($skDir = opendir(extras_dir.'/social/tpl/skins')) {
		while ($skFile = readdir($skDir)) {
			if (!preg_match('/^\./', $skFile)) {
				$skList[$skFile] = $skFile;
			}
		}
		closedir($skDir);
	}
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.general.form'), 'social', 1);
	
	$tvars['vars']['localsource'] = MakeDropDown(array(0 => $lang['social:localsource.site'], 1 => $lang['social:localsource.plugin']), 'localsource', pluginGetVariable('social', 'localsource'));
	$tvars['vars']['skin'] = MakeDropDown($skList, 'skin', pluginGetVariable('social', 'skin'));
	$tvars['vars']['cache'] = MakeDropDown(array(0 => $lang['noa'], 1 => $lang['yesa']), 'cache', pluginGetVariable('social', 'cache'));
	$tvars['vars']['cacheExpire'] = pluginGetVariable('social', 'cacheExpire');
	$tvars['vars']['login'] = pluginGetVariable('social', 'login');
	$tvars['vars']['api_key'] = pluginGetVariable('social', 'api_key');

	$tpl->template('conf.general.form', $tpath['conf.general.form']);
	$tpl->vars('conf.general.form', $tvars);
	$tvars['vars']['entries'] = $tpl->show('conf.general.form');
	
	$tvars['vars']['action'] = $lang['social:buttonGeneral'];

	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function about() {
	global $tpl, $lang;
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.about'), 'social', 1);
	
	$tpl->template('conf.about', $tpath['conf.about']);
	$tpl->vars('conf.about');
	$tvars['vars']['entries'] = $tpl->show('conf.about');
	
	$tvars['vars']['action'] = $lang['social:buttonAbout'];

	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function showList() {
	global $tpl, $lang;
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.list', 'conf.list.row'), 'social', 1);
	$services = pluginGetVariable('social', 'services');
	
	$output = '';
	if (is_array($services)){
		foreach($services as $id=>$row)
		{
			$pvars['regx']['/\[active\](.*?)\[\/active\]/si']        = $row['active']?'$1':'';
			$pvars['regx']['/\[no-active\](.*?)\[\/no-active\]/si']  = $row['active']?'':'$1';
			$pvars['vars']['id']     = $id;
			$pvars['vars']['name']   = $row['name'];
			$pvars['vars']['title']  = $row['title'];
			$pvars['vars']['link']   = $row['link'];
			$pvars['vars']['img']    = $row['img'];
            $pvars['vars']['[linkup]']      = '<a href="?mod=extra-config&plugin=social&action=moveUp&id='.$id.'">';
            $pvars['vars']['[/linkup]']     = '</a>';
            $pvars['vars']['[linkdown]']    = '<a href="?mod=extra-config&plugin=social&action=moveDown&id='.$id.'">';
            $pvars['vars']['[/linkdown]']   = '</a>';
			
			$tpl->template('conf.list.row', $tpath['conf.list.row']);
			$tpl->vars('conf.list.row', $pvars);
			$output .= $tpl->show('conf.list.row');
		}
	}

	$ttvars['vars']['entries'] = $output;
	
	$tpl->template('conf.list', $tpath['conf.list']);
	$tpl->vars('conf.list', $ttvars);
	$tvars['vars']['entries'] = $tpl->show('conf.list');
	
	$tvars['vars']['action'] = $lang['social:buttonList'];
	
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function edit() {
	global $tpl, $lang;

	$id = -1;
	$active = 0;
	$name = '';
	$title = '';
	$link = '';
	$img = '';
	if (isset($_GET['id'])){
		$id = intval($_GET['id']);
		$services = pluginGetVariable('social', 'services');
		if (!isset($services[$id])) $id = -1;
		else{
			$active = $services[$id]['active'];
			$name = $services[$id]['name'];
			$title = $services[$id]['title'];
			$link = $services[$id]['link'];
			$img = $services[$id]['img'];
		}
	}
	
	$tpath = locatePluginTemplates(array('conf.main', 'conf.edit.form'), 'social', 1);
	
	$pvars['vars']['id'] = $id;
	$pvars['vars']['active'] = MakeDropDown(array(0 => $lang['social:service.active.off'], 1 => $lang['social:service.active.on']), 'active', $active);
	$pvars['vars']['name'] = $name;
	$pvars['vars']['title'] = $title;
	$pvars['vars']['link'] = $link;
	$pvars['vars']['service.img'] = $lang['social:service.img'];
	$pvars['vars']['service.img.desc'] = $lang['social:service.img.localsource#desc'];
  
    if ((!pluginGetVariable('social', 'localsource'))&&(is_dir(tpl_site.'/plugins/social/images'))) {
		$pvars['vars']['img'] = MakeDropDown(ListFiles(tpl_site.'/plugins/social/images', array('gif', 'jpg', 'png'), 2), 'img', $img);
    } elseif ((pluginGetVariable('social', 'localsource'))&&(pluginGetVariable('social', 'skin'))&&(is_dir(extras_dir.'/social/tpl/skins/'.pluginGetVariable('social', 'skin').'/images'))) {
		$pvars['vars']['img'] = MakeDropDown(ListFiles(extras_dir.'/social/tpl/skins/'.pluginGetVariable('social', 'skin').'/images', array('gif', 'jpg', 'png'), 2), 'img', $img);        
    } elseif (is_dir(extras_dir.'/social/tpl/skins/default/images')){
        $pvars['vars']['service.img.desc'] = $lang['social:service.img.default#desc'];        
        $pvars['vars']['img'] = MakeDropDown(ListFiles(extras_dir.'/social/tpl/skins/default/images', array('gif', 'jpg', 'png'), 2), 'img', $img);
    } else {
		$pvars['vars']['service.img'] = $lang['social:service.img.error'];
		$pvars['vars']['service.img.desc'] = $lang['social:service.img.error#desc'];
		$pvars['vars']['img'] = '';        
    }
		
	$tpl->template('conf.edit.form', $tpath['conf.edit.form']);
	$tpl->vars('conf.edit.form', $pvars);
	$output .= $tpl->show('conf.edit.form');

	$tvars['vars']['entries'] = $output;
	$tvars['vars']['action'] = $lang['social:buttonEdit'];
	
	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function editSubmit() {
	global $lang;
	
	if (!isset($_POST['name'], $_POST['title'], $_POST['active'], $_POST['link'], $_POST['img'])) return;
	
	$id = -1;
	if (isset($_POST['id']))$id = intval($_POST['id']);
	$name	= trim(secure_html($_POST['name']));
	$title	= trim(secure_html($_POST['title']));
	$active	= intval($_POST['active']);
	$link	= trim(secure_html($_POST['link']));
	$img	= trim(secure_html($_POST['img']));

	$services = pluginGetVariable('social', 'services');
	if (is_array($services)){
		if (!isset($services[$id])) $id = count($services);
	}
	else $id = 0;

	$services[$id]['name']		= $name;
	$services[$id]['title']		= $title;
	$services[$id]['active']	= $active;
	$services[$id]['link']		= $link;
	$services[$id]['img']		= $img;
	
	pluginSetVariable('social', 'services', $services);
	pluginsSaveConfig();
	
	msg(array('type' => 'info', 'info' => $lang['social:msgi.editSubmit']));
	
	if (pluginGetVariable('social', 'cache')) clearCache();
}

function delete() {
	global $tpl, $lang;

	if (!isset($_REQUEST['id'])) return;
	$id = intval($_REQUEST['id']);
	
	$services = pluginGetVariable('social', 'services');
	
	if (!isset($services[$id])){
		showList();
		return true;
	}
	
	if (isset($_POST['commit']))
	{
		if ($_POST['commit'] == 'yes')
		{
			if (isset($services[$id])){
				unset($services[$id]);
				pluginSetVariable('social', 'services', $services);
				pluginsSaveConfig();
			}
			
			msg(array('type' => 'info', 'info' => $lang['social:msgi.delete']));
			if (pluginGetVariable('social', 'cache')) clearCache();
		}
		showList();
		return true;
	}
	$tpath = locatePluginTemplates(array('conf.main', 'conf.commit.form'), 'social', 1);
	$tvars['vars']['id'] = $id;
	$tvars['vars']['commit'] = sprintf($lang['social:service.commit#desc'], $services[$id]['name']);
	
	$tpl->template('conf.commit.form', $tpath['conf.commit.form']);
	$tpl->vars('conf.commit.form', $tvars);
	$tvars['vars']['entries'] = $tpl->show('conf.commit.form');
	
	$tvars['vars']['action'] = $lang['social:service.commit'];

	$tpl->template('conf.main', $tpath['conf.main']);
	$tpl->vars('conf.main', $tvars);
	print $tpl->show('conf.main');
}

function generalSubmit() {
	global $lang;
	pluginSetVariable('social', 'localsource', intval($_POST['localsource']));
	pluginSetVariable('social', 'skin', trim(secure_html($_POST['skin'])));
	pluginSetVariable('social', 'cache', intval($_POST['cache']));
	pluginSetVariable('social', 'cacheExpire', intval($_POST['cacheExpire']));
	pluginSetVariable('social', 'login', trim(secure_html($_POST['login'])));
	pluginSetVariable('social', 'api_key', trim(secure_html($_POST['api_key'])));
	pluginsSaveConfig();
	if (pluginGetVariable('social', 'cache')) clearCache();
	msg(array('type' => 'info', 'info' => $lang['social:msgi.generalSubmit']));
}

function clearCache() {
	global $lang;
	if (($dir = get_plugcache_dir('social'))) {
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) { 
				if ($file == "." || $file == "..")
					continue;
				unlink ($dir.$file);
			}
			closedir($handle); 
		}
		msg(array('type' => 'info', 'info' => $lang['social:msgi.clearCache']));
	}
}

function restore() {
	global $lang;

	$services = array();
    array_push($services, array('name' => 'Twitter','title' => 'Добавить в Twitter','active' => 0,'link' => 'http://twitter.com/share?text=%title%+&#124;+%content%&amp;url=%link%','img' => 'twitter.png'));
    array_push($services, array('name' => 'Google Buzz','title' => 'Добавить в Google Buzz','active' => 0,'link' => 'http://www.google.com/buzz/post?message=%title%+&#124;+%content%&amp;url=%link%','img' => ''));
    array_push($services, array('name' => 'Facebook','title' => 'Поделиться ссылкой на FaceBook','active' => 0,'link' => 'http://www.facebook.com/sharer.php?t=%title%&amp;description=%content%&amp;u=%link%','img' => 'facebook.png'));
    array_push($services, array('name' => 'ВКонтакте','title' => 'Поделиться ВКонтакте','active' => 0,'link' => 'http://vk.com/share.php?title=%title%&amp;description=%content%&amp;url=%link%','img' => 'vk.png'));
    array_push($services, array('name' => 'Одноклассники','title' => 'Добавить в Одноклассники','active' => 0,'link' => 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl=%link%?title=%title%+&#124;+%content%','img' => 'odnoklassniki.png'));
    array_push($services, array('name' => 'LiveJournal','title' => 'Добавить в свой блог на livejournal.com','active' => 0,'link' => 'http://www.livejournal.com/update.bml?event=%link%&amp;subject=%title%','img' => 'livejournal.png')); 
    array_push($services, array('name' => 'Blogger','title' => 'Добавить в свой блог на Blogger.com','active' => 0,'link' => 'http://www.blogger.com/blog-this.pyra?t=%content%&amp;u=%link%&amp;n=%title%','img' => 'blogger.png'));
    array_push($services, array('name' => 'Я.ру','title' => 'Поделиться ссылкой на Я.ру','active' => 0,'link' => 'http://my.ya.ru/posts_add_link.xml?URL=%link%&amp;title=%title%&amp;body=%content%','img' => 'yaru.png'));
    array_push($services, array('name' => 'Мой круг','title' => 'Добавить в Мой Круг','active' => 0,'link' => 'http://moikrug.ru/profile/links/','img' => 'moikrug.png'));
    array_push($services, array('name' => 'Мой мир','title' => 'Добавить в Мой Мир','active' => 0,'link' => 'http://connect.mail.ru/share?share_url=%link%','img' => 'moimir.png'));
    array_push($services, array('name' => 'del.icio.us','title' => 'Добавить в del.icio.us','active' => 0,'link' => 'http://delicious.com/post?url=%link%&amp;title=%title%&amp;notes=%content%','img' => 'delicious.png'));
    array_push($services, array('name' => 'Digg','title' => 'Добавить в Digg','active' => 0,'link' => 'http://digg.com/submit?phase=2&amp;url=%link%&amp;title=%title%&amp;bodytext=%content%','img' => 'digg.png'));
    array_push($services, array('name' => 'StumbleUpon','title' => 'Добавить в StumbleUpon','active' => 0,'link' => 'http://www.stumbleupon.com/submit?url=%link%&amp;title=%title%','img' => 'stumbleupon.png'));
    array_push($services, array('name' => 'Google Bookmarks','title' => 'Добавить в Закладки Google','active' => 0,'link' => 'http://www.google.com/bookmarks/mark?op=add&title=%title%&amp;bkmk=%link%&amp;annotation=%content%','img' => 'google.png'));
    array_push($services, array('name' => 'YahooMyWeb','title' => 'Добавить в YahooMyWeb','active' => 0,'link' => 'http://myweb2.search.yahoo.com/myresults/bookmarklet?u=%link%&amp;t=%title%','img' => 'yahoo.png'));
    array_push($services, array('name' => 'Technorati','title' => 'Добавить в Technorati','active' => 0,'link' => 'http://technorati.com/faves?add=%link%','img' => 'technorati.png'));
    array_push($services, array('name' => 'Slashdot','title' => 'Добавить в Slashdot','active' => 0,'link' => 'http://www.slashdot.org/bookmark.pl?url=%link%&amp;title=%title%','img' => ''));
    array_push($services, array('name' => 'Newsland','title' => 'Добавить в Newsland','active' => 0,'link' => 'http://www.newsland.ru/News/Add/','img' => ''));
    array_push($services, array('name' => 'News2.ru','title' => 'Добавить в News2.ru','active' => 0,'link' => 'http://news2.ru/add_story.php?url=%link%', 'img' => ''));
    array_push($services, array('name' => 'ЛиРу (Liveinternet)','title' => 'Добавить в свой блог на ЛиРу (Liveinternet)','active' => 0,'link' => 'http://www.liveinternet.ru/journal_post.php?action=l_add&amp;cnurl=%link%','img' => ''));
    array_push($services, array('name' => 'СМИ2','title' => 'Добавить в СМИ2','active' => 0,'link' => 'http://smi2.ru/add/','img' => ''));
    array_push($services, array('name' => 'RUmarkz','title' => 'Добавить в RUmarkz','active' => 0,'link' => 'http://rumarkz.ru/bookmarks/?action=add&popup=1&address=%link%&amp;title=%title%','img' => ''));
    array_push($services, array('name' => 'Ваау!','title' => 'Добавить в Ваау!','active' => 0,'link' => 'http://www.vaau.ru/submit/?action=step2&url=%link%','img' => ''));
    array_push($services, array('name' => 'Memori.ru','title' => 'Добавить в Memori.ru','active' => 0,'link' => 'http://memori.ru/link/?sm=1&u_data[url]=%link%&amp;u_data[name]=%title%','img' => 'memori.png'));
    array_push($services, array('name' => 'RuCity.com','title' => 'Добавить в RuCity.com','active' => 0,'link' => 'http://www.rucity.com/bookmarks.php?action=add&address=%link%&amp;title=%title%','img' => ''));
    array_push($services, array('name' => 'МоёМесто.ru','title' => 'Добавить в МоёМесто.ru','active' => 0,'link' => 'http://moemesto.ru/post.php?url=%link%&amp;title=%title%','img' => ''));
    array_push($services, array('name' => 'Мои новости','title' => 'Добавить в Мои новости','active' => 0,'link' => 'http://www.moinovosti.com/submit.php?url=%link%','img' => ''));
    array_push($services, array('name' => 'Mister Wong','title' => 'Добавить в Mister Wong','active' => 0,'link' => 'http://www.mister-wong.ru/index.php?action=addurl&amp;bm_url=%link%&amp;bm_description=%title%','img' => ''));
    array_push($services, array('name' => 'Myscoop','title' => 'Добавить в Myscoop','active' => 0,'link' => 'http://myscoop.ru/add/','img' => ''));
    array_push($services, array('name' => 'БобрДобр','title' => 'Добавить в БобрДобр','active' => 0,'link' => 'http://www.bobrdobr.ru/addext.html?url=%link%&amp;title=%title%','img' => ''));
    array_push($services, array('name' => 'NewsGrad','title' => 'Добавить в NewsGrad','active' => 0,'link' => 'http://www.newsgrad.com/news/add','img' => ''));
    array_push($services, array('name' => 'LinkedIn','title' => 'Добавить в LinkedIn','active' => 0,'link' => 'http://www.linkedin.com/shareArticle?mini=true&amp;url=%link%&amp;title=%title%&amp;summary=%content%','img' => 'linkedin.png'));       
	
    pluginSetVariable('social', 'services', $services);
	pluginsSaveConfig();
	
	msg(array('type' => 'info', 'info' => $lang['social:msgi.editSubmit']));
	
	if (pluginGetVariable('social', 'cache')) clearCache();
}

function move($action) {
	global $lang;
        
   	if (!isset($_REQUEST['id'])) return;
    $index = intval($_REQUEST['id']);
    if ($index == -1) return;
	
	$services = pluginGetVariable('social', 'services');
	
	if (!isset($services[$index])){
		showList();
		return true;
	}	
       
    switch ($action) {
		case 'up':    $offset = -1; $msgInfo = $lang['social:msgi.doneUp']; break;
		case 'down':  $offset = 1; $msgInfo = $lang['social:msgi.doneDown']; break;
	}
    
    $index2 = $index + $offset;

    if ($index2 < 0) $index2 = 0;
    if ($index2 > (count($services)-1)) $index2 = count($services)-1;
    if ($index == $index2)	return 1;

    $a = min($index, $index2);
    $b = max($index, $index2); 

    $temp = $services[$a];
    $services[$a] = $services[$b]; 
    $services[$b] = $temp;

    pluginSetVariable('social', 'services', $services);
	pluginsSaveConfig();
	
	msg(array('type' => 'info', 'info' => $msgInfo));
	
	if (pluginGetVariable('social', 'cache')) clearCache();
}


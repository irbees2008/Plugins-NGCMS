<?php

if (!defined('NGCMS'))
	die ('HAL');

rpcRegisterFunction('events_send_main', 'events_snd_me');
rpcRegisterFunction('events_filter_main', 'events_flt_me');
rpcRegisterFunction('events_archive_main', 'events_arch_me');
	
include_once(dirname(__FILE__).'/cache.php');
include_once(dirname(__FILE__).'/events.php');

function plugin_block_events($number, $mode, $cat, $toU, $pagination, $expired, $overrideTemplateName, $cacheExpire) {
	global $config, $mysql, $tpl, $template, $twig, $twigLoader, $langMonths, $lang, $TemplateCache, $userROW;

	// Prepare keys for cacheing
	$cacheKeys = array();
	$cacheDisabled = false;
	
	
	if(isset($cat) && !empty($cat))
	{
		$cat_id = ' and n.cat_id IN ( '.$cat.' ) ';
	} else {
		$cat_id = '';
	}
	$sorting = $cat_id;
	
	if (($number < 1) || ($number > 100))
		$number = 10000;
		
	if(isset($toU) && !empty($toU))
	{
		$user_id = ' and n.author_id = '.$userROW['id'].' ';
	} else {
		$user_id = '';
	}
	$sorting_user = $user_id;
	
	if(isset($expired) && !empty($expired))
	{
		$expired_f = ' and n.expired != "" ';
	} else {
		$expired_f = ' and n.expired = "" ';
	}
	$sorting_expired = $expired_f;
	
		
	switch ($mode) {
		case 'view':    $sql = 'SELECT *, c.id as cid, n.id as nid FROM '.prefix.'_events n LEFT JOIN '.prefix.'_events_cat c ON n.cat_id = c.id LEFT JOIN '.prefix.'_events_images i ON n.id = i.zid WHERE n.active = \'1\' '.$sorting.' '.$sorting_user.' '.$sorting_expired.' GROUP BY n.id ORDER BY n.views DESC';
						//$sql_count = 'SELECT COUNT(*) as cnt FROM '.prefix.'_events n LEFT JOIN '.prefix.'_events_cat c ON n.cat_id = c.id LEFT JOIN '.prefix.'_events_images i ON n.id = i.zid WHERE n.active = \'1\' '.$sorting.' '.$sorting_user.' '.$sorting_expired.' GROUP BY n.id ORDER BY n.views DESC';
						break;
		case 'last':	$sql = 'SELECT *, c.id as cid, n.id as nid FROM '.prefix.'_events n LEFT JOIN '.prefix.'_events_cat c ON n.cat_id = c.id LEFT JOIN '.prefix.'_events_images i ON n.id = i.zid WHERE n.active = \'1\' '.$sorting.' '.$sorting_user.' '.$sorting_expired.' GROUP BY n.id ORDER BY editdate DESC';
						//$sql_count = 'SELECT COUNT(*) as cnt FROM '.prefix.'_events n LEFT JOIN '.prefix.'_events_cat c ON n.cat_id = c.id LEFT JOIN '.prefix.'_events_images i ON n.id = i.zid WHERE n.active = \'1\' '.$sorting.' '.$sorting_user.' '.$sorting_expired.' GROUP BY n.id ORDER BY editdate DESC';
						break;
		case 'rnd':		$cacheDisabled = true;
						$sql = 'SELECT *, c.id as cid, n.id as nid FROM '.prefix.'_events n LEFT JOIN '.prefix.'_events_cat c ON n.cat_id = c.id LEFT JOIN '.prefix.'_events_images i ON n.id = i.zid WHERE n.active = \'1\' '.$sorting.' '.$sorting_user.' '.$sorting_expired.' GROUP BY n.id ORDER BY RAND() DESC';
						//$sql_count = 'SELECT COUNT(*) as cnt FROM '.prefix.'_events n LEFT JOIN '.prefix.'_events_cat c ON n.cat_id = c.id LEFT JOIN '.prefix.'_events_images i ON n.id = i.zid WHERE n.active = \'1\' '.$sorting.' '.$sorting_user.' '.$sorting_expired.' GROUP BY n.id ORDER BY RAND() DESC';
						break;
		default:		$mode = 'last';
						$sql = 'SELECT *, c.id as cid, n.id as nid FROM '.prefix.'_events n LEFT JOIN '.prefix.'_events_cat c ON n.cat_id = c.id LEFT JOIN '.prefix.'_events_images i ON n.id = i.zid WHERE n.active = \'1\' '.$sorting.' '.$sorting_user.' '.$sorting_expired.' GROUP BY n.id ORDER BY editdate DESC';
						//$sql_count = 'SELECT COUNT(*) as cnt FROM '.prefix.'_events n LEFT JOIN '.prefix.'_events_cat c ON n.cat_id = c.id LEFT JOIN '.prefix.'_events_images i ON n.id = i.zid WHERE n.active = \'1\' '.$sorting.' '.$sorting_user.' '.$sorting_expired.' GROUP BY n.id ORDER BY editdate DESC';
						break;
	}
	$sql .= " limit ".$number;


	if ($overrideTemplateName) {
        $templateName = 'block/'.$overrideTemplateName;
    } else {
         $templateName = 'block/block_events';
    }
	
	// Determine paths for all template files
	$tpath = locatePluginTemplates(array($templateName), 'events', pluginGetVariable('events', 'localsource'));

	
	// Preload template configuration variables
	@templateLoadVariables();
	
	$cacheKeys []= '|number='.$number;
	$cacheKeys []= '|mode='.$mode;
	$cacheKeys []= '|cat='.$cat;
	$cacheKeys []= '|templateName='.$templateName;

	// Generate cache file name [ we should take into account SWITCHER plugin ]
	$cacheFileName = md5('events'.$config['theme'].$templateName.$config['default_lang'].join('', $cacheKeys)).'.txt';

	if (!$cacheDisabled && ($cacheExpire > 0)) {
		$cacheData = cacheRetrieveFile($cacheFileName, $cacheExpire, 'events');
		if ($cacheData != false) {
			// We got data from cache. Return it and stop
			return $cacheData;
		}
	}
	

	foreach ($mysql->select($sql) as $row) {
		
		if($row['author_id'] != 0) {
			$alink = checkLinkAvailable('uprofile', 'show')?
						generateLink('uprofile', 'show', array('id' => $row['author_id'])):
						generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('id' => $row['author_id']));
		}
		else { $alink = ''; }
		
		$fulllink = checkLinkAvailable('events', 'show')?
			generateLink('events', 'show', array('id' => $row['nid'])):
			generateLink('core', 'plugin', array('plugin' => 'events', 'handler' => 'show'), array('id' => $row['nid']));
		
		$catlink = checkLinkAvailable('events', '')?
			generateLink('events', '', array('cat' => $row['cid'])):
			generateLink('core', 'plugin', array('plugin' => 'events'), array('cat' => $row['cid']));
		
		$tEntries [] = array(
			'nid'					=>	$row['nid'],
			'date'					=>	$row['date'],
			'editdate'				=>	$row['editdate'],
			'views'					=>	$row['views'],
			'announce_name'			=>	$row['announce_name'],
			'author'				=>	$row['author'],
			'author_id'				=>	$row['author_id'],
			'author_email'			=>	$row['author_email'],
			'announce_period'		=>	$row['announce_period'],
			'announce_description'	=>	$row['announce_description'],
			'announce_contacts'		=>	$row['announce_contacts'],
			'fulllink'				=>	$fulllink,
			'catlink'				=>	$catlink,
			'cat_name'				=>	$row['cat_name'],
			'pid'					=>	$row['pid'],
			'filepath'				=>	$row['filepath'],
			'editlink'				=>	home.'/plugin/events/edit/?id='.$row['nid'],
			'unpublishlink' 		=> 	home.'/plugin/events/unpublish/?id='.$row['nid'],
			'ulink' => checkLinkAvailable('uprofile', 'show')?
									generateLink('uprofile', 'show', array('id' => $row['author_id'])):
									generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('id' => $row['author_id']))
		);
		//var_dump($row);
	}
	
	
	
	if(isset($pagination) && !empty($pagination))
	{
		//$get_total_rows = $mysql->select($sql_count);
		//$pages = ceil($get_total_rows[0]['cnt']/$number);	
		$pagination = '';
	} else {
		$pagination = '';
	}
	
	$tVars['entries']	= $tEntries;
	$tVars['tpl_url'] = tpl_url;
	$tVars['home'] = home;

	$xt = $twig->loadTemplate($tpath[$templateName].$templateName.'.tpl');
	$output = $xt->render($tVars);
	
	if (!$cacheDisabled && ($cacheExpire > 0)) {
		cacheStoreFile($cacheFileName, $output, 'events');
	}
	
	return $output;
}

function plugin_m_events() {
	global $config;

	$events_dir = get_plugcfg_dir('events');
	generate_entries_cnt_cache();
	
	if(file_exists($events_dir.'/cache_entries_cnt.php')){
		$output = unserialize(file_get_contents($events_dir.'/cache_entries_cnt.php'));
	} else {
		$output = '';
	}

	return $output;
}

function plugin_m_events_send() {
	global $config, $twig, $mysql;
	
	$tpath = locatePluginTemplates(array('send_events_main'), 'events', pluginGetVariable('events', 'localsource'));
	$xt = $twig->loadTemplate($tpath['send_events_main'].'send_events_main.tpl');

	$res = mysql_query("SELECT * FROM ".prefix."_events_cat ORDER BY id");
	$cats = getCats($res);
	$categories = getTree($cats, $row['cat_id'], 0);
	
	$res = $mysql->select("SELECT * FROM ".prefix."_events_cities ORDER BY city");
	
	foreach($res as $v){
		$cities .= '<option value="'.$v['id'].'">'.$v['city'].'</option>';
    }
	
	$tVars = array(
			'categories' => $categories,
			'cities' => $cities,
			//'act' => home."/engine/rpc.php?methodName=news_feedback_add"
			);
	
	$output = $xt->render($tVars);

	
	return $output;
}


function plugin_m_events_archive() {
	global $config, $twig, $mysql;
	
	$tpath = locatePluginTemplates(array('archive_main'), 'events', pluginGetVariable('events', 'localsource'));
	$xt = $twig->loadTemplate($tpath['archive_main'].'archive_main.tpl');

	
	$tVars = array(
			);
	
	$output = $xt->render($tVars);

	
	return $output;
}


function plugin_m_events_filter() {
	global $config, $twig, $mysql;
	
	$tpath = locatePluginTemplates(array('filter_main'), 'events', pluginGetVariable('events', 'localsource'));
	$xt = $twig->loadTemplate($tpath['filter_main'].'filter_main.tpl');

	$res = mysql_query("SELECT * FROM ".prefix."_events_cat ORDER BY id");
	$cats = getCats($res);
	$categories = getTree($cats, $row['cat_id'], 0);
	
	$res = $mysql->select("SELECT * FROM ".prefix."_events_cities ORDER BY city");
	
	foreach($res as $v){
		$cities .= '<option value="'.$v['id'].'">'.$v['city'].'</option>';
		/*
				if($k==$flg) { $out .= '<option value="'.$k.'" selected>'.str_repeat($ft, $l).$v['cat_name'].'</option>'; }
		else { $out .= '<option value="'.$k.'">'.str_repeat($ft, $l).$v['cat_name'].'</option>'; }
			if(!empty($v['children'])){ 	
				//$l = $l + 1;
				$out .= getTree($v['children'], $flg, $l + 1);
				//$l = $l - 1;
			}
			*/
		
    }
	//var_dump($cities);
	
	$tVars = array(
			'categories' => $categories,
			'cities' => $cities,
			//'act' => home."/engine/rpc.php?methodName=news_feedback_add"
			);
	
	$output = $xt->render($tVars);

	
	return $output;
}

function events_arch_me($params) {
	global $tpl, $template, $twig, $SYSTEM_FLAGS, $config, $userROW, $mysql, $TemplateCache;

	// Prepare basic reply array
		$results = array();

		$news_per_page			= pluginGetVariable('events', 'count_filter_archive');
		$announce_page_filter = secure_html($params['announce_page_filter']);
		
		$conditions = array();

		array_push($conditions, "e.expired != ''");
		array_push($conditions, "e.active = 1");
			
		$fSort = "ORDER BY e.id DESC";
		
		$sqlQPart = "from ".prefix."_events e LEFT JOIN ".prefix."_events_cat c ON e.cat_id = c.id LEFT JOIN ".prefix."_users u ON e.author_id = u.id ".(count($conditions)?"where ".implode(" AND ", $conditions):'').' '.$fSort;
		$sqlQCount = "select count(e.id) ".$sqlQPart;
		$sqlQ = "select e.date as edate, e.views as eviews, e.announce_name as eannounce_name, e.announce_place as eannounce_place, e.announce_description as eannounce_description, e.city as ecity, u.name as uname, u.xfields_ucity as xfields_ucity, u.xfields_ubirthdate as xfields_ubirthdate, u.avatar as uavatar, u.id as uid, e.id as eid, c.id as cid ".$sqlQPart;
		
		$pageNo		= intval($announce_page_filter)?$announce_page_filter:0;
		if ($pageNo < 1)	$pageNo = 1;
		if (!$start_from)	$start_from = ($pageNo - 1)* $news_per_page;
		
		$count = $mysql->result($sqlQCount);
		$countPages = ceil($count / $news_per_page);

		// Preload template configuration variables
		templateLoadVariables();

		// Use default <noavatar> file
		// - Check if noavatar is defined on template level
		$tplVars = $TemplateCache['site']['#variables'];
		$noAvatarURL = (isset($tplVars['configuration']) && is_array($tplVars['configuration']) && isset($tplVars['configuration']['noAvatarImage']) && $tplVars['configuration']['noAvatarImage'])?(tpl_url."/".$tplVars['configuration']['noAvatarImage']):(avatars_url."/noavatar.jpg");
		
		foreach ($mysql->select($sqlQ.' LIMIT '.$start_from.', '.$news_per_page) as $row)
		{
		
		//var_dump($row);
		
		$fulllink = checkLinkAvailable('events', 'show')?
			generateLink('events', 'show', array('id' => $row['eid'])):
			generateLink('core', 'plugin', array('plugin' => 'events', 'handler' => 'show'), array('id' => $row['eid']));
		
		$catlink = checkLinkAvailable('events', '')?
			generateLink('events', '', array('cat' => $row['cid'])):
			generateLink('core', 'plugin', array('plugin' => 'events'), array('cat' => $row['cid']));
			
		// If avatar is set
		if ($row['uavatar'] != '') {
			$avatar = avatars_url.'/'.((preg_match('/^'.$row['uid'].'\./', $row['uavatar']))?($row['uid'].'.'):'').$row['uavatar'];
		} else {
			$avatar = $noAvatarURL;
		}

			
			$tEntry[] = array (
				'id' => $row['id'],
				'date' => $row['edate'],
				'views' => $row['eviews'],
				'announce_name' => $row['eannounce_name'],
				'announce_place' => $row['eannounce_place'],
				'announce_description' => $row['eannounce_description'],
				'city' => $row['ecity'],
				'uname' => $row['uname'],
				'ucity' => $row['xfields_ucity'],
				'uage' =>  dataDiff($row['xfields_ubirthdate']),
				'wordage' => ruDecline(dataDiff($row['xfields_ubirthdate']),"год","года","лет"),
				'author_link' => checkLinkAvailable('uprofile', 'show')?
									generateLink('uprofile', 'show', array('name' => $row['uname'], 'id' => $row['uid'])):
									generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('name' => $row['uname'], 'id' => $row['uid'])),
				'avatar' => $avatar,
				'fulllink' => $fulllink,
				'catlink' => $catlink
			);
		}
		
		
		$tpath = locatePluginTemplates(array('list_result_archive'), 'events', 1);
		$xt = $twig->loadTemplate($tpath['list_result_archive'].'list_result_archive.tpl');

		$tVars = array(
			'pagesss' => generateLP( array('current' => $pageNo, 'count' => $countPages, 'url' => '#', 'tpl' => 'pages_archive')),
			'entries' => isset($tEntry)?$tEntry:'',
			'tpl_url'		=>	tpl_url,
			'home'			=>	home,
			'announce_page_filter'			=>	secure_html($announce_page_filter),

		);


		if(empty($row)) {
			$results = array(
				'event_archive' => 2,
				'event_archive_text' => iconv('Windows-1251', 'UTF-8','Нет событий подходящих под фильтр!')
			);
		}
		else {
			$results = array(
				'event_archive'	=> 100,
				'event_archive_text' => iconv('Windows-1251', 'UTF-8',$xt->render($tVars))
			);
		
		}



	return array('status' => 1, 'errorCode' => 0, 'data' => $results);
}

function events_flt_me($params) {
	global $tpl, $template, $twig, $SYSTEM_FLAGS, $config, $userROW, $mysql, $TemplateCache;

	// Prepare basic reply array
		$results = array();
		
		$announce_city_filter = intval($params['announce_city_filter']);
		$announce_type_filter = intval($params['announce_type_filter']);
		$announce_sex_filter = secure_html($params['announce_sex_filter']);
		$announce_datepicker_filter = secure_html($params['announce_datepicker_filter']);
		$news_per_page			= pluginGetVariable('events', 'count_filter');
		$announce_page_filter = secure_html($params['announce_page_filter']);
		
		$conditions = array();
			if ($announce_city_filter) {
				array_push($conditions, "e.city = ".db_squote($announce_city_filter));
			}
			
			if ($announce_type_filter) {
				array_push($conditions, "e.cat_id = ".db_squote($announce_type_filter));
			}
			
			if ($announce_sex_filter && $announce_sex_filter != 'N') {
				array_push($conditions, "e.gender = ".db_squote($announce_sex_filter));
			}
			
			if ($announce_datepicker_filter) {
				$start_date = strtotime($announce_datepicker_filter);
				$end_date =  strtotime('+1 days', strtotime($announce_datepicker_filter));
				array_push($conditions, "e.date > ".db_squote($start_date)." AND date < ".db_squote($end_date));
			}

		array_push($conditions, "e.expired = ''");
		array_push($conditions, "e.active = 1");
			
		$fSort = "ORDER BY e.id DESC";
		
		$sqlQPart = "from ".prefix."_events e LEFT JOIN ".prefix."_events_cat c ON e.cat_id = c.id LEFT JOIN ".prefix."_users u ON e.author_id = u.id ".(count($conditions)?"where ".implode(" AND ", $conditions):'').' '.$fSort;
		$sqlQCount = "select count(e.id) ".$sqlQPart;
		$sqlQ = "select e.date as edate, e.views as eviews, e.announce_name as eannounce_name, e.announce_place as eannounce_place, e.announce_description as eannounce_description, e.city as ecity, u.name as uname, u.xfields_ucity as xfields_ucity, u.xfields_ubirthdate as xfields_ubirthdate, u.avatar as uavatar, u.id as uid, e.id as eid, c.id as cid ".$sqlQPart;
		
		$pageNo		= intval($announce_page_filter)?$announce_page_filter:0;
		if ($pageNo < 1)	$pageNo = 1;
		if (!$start_from)	$start_from = ($pageNo - 1)* $news_per_page;
		
		$count = $mysql->result($sqlQCount);
		$countPages = ceil($count / $news_per_page);

		// Preload template configuration variables
		templateLoadVariables();

		// Use default <noavatar> file
		// - Check if noavatar is defined on template level
		$tplVars = $TemplateCache['site']['#variables'];
		$noAvatarURL = (isset($tplVars['configuration']) && is_array($tplVars['configuration']) && isset($tplVars['configuration']['noAvatarImage']) && $tplVars['configuration']['noAvatarImage'])?(tpl_url."/".$tplVars['configuration']['noAvatarImage']):(avatars_url."/noavatar.jpg");
		
		foreach ($mysql->select($sqlQ.' LIMIT '.$start_from.', '.$news_per_page) as $row)
		{
		
		//var_dump($row);
		
		$fulllink = checkLinkAvailable('events', 'show')?
			generateLink('events', 'show', array('id' => $row['eid'])):
			generateLink('core', 'plugin', array('plugin' => 'events', 'handler' => 'show'), array('id' => $row['eid']));
		
		$catlink = checkLinkAvailable('events', '')?
			generateLink('events', '', array('cat' => $row['cid'])):
			generateLink('core', 'plugin', array('plugin' => 'events'), array('cat' => $row['cid']));
			
		// If avatar is set
		if ($row['uavatar'] != '') {
			$avatar = avatars_url.'/'.((preg_match('/^'.$row['uid'].'\./', $row['uavatar']))?($row['uid'].'.'):'').$row['uavatar'];
		} else {
			$avatar = $noAvatarURL;
		}
			
			$tEntry[] = array (
				'id' => $row['id'],
				'date' => $row['edate'],
				'views' => $row['eviews'],
				'announce_name' => $row['eannounce_name'],
				'announce_place' => $row['eannounce_place'],
				'announce_description' => $row['eannounce_description'],
				'city' => $row['ecity'],
				'uname' => $row['uname'],
				'ucity' => $row['xfields_ucity'],
				'uage' =>  dataDiff($row['xfields_ubirthdate']),
				'wordage' => ruDecline(dataDiff(date('Y-m-d', $row['xfields_ubirthdate'])),"год","года","лет"),
				'author_link' => checkLinkAvailable('uprofile', 'show')?
									generateLink('uprofile', 'show', array('name' => $row['uname'], 'id' => $row['uid'])):
									generateLink('core', 'plugin', array('plugin' => 'uprofile', 'handler' => 'show'), array('name' => $row['uname'], 'id' => $row['uid'])),
				'avatar' => $avatar,
				'fulllink' => $fulllink,
				'catlink' => $catlink
			);
		}
		
		
		$tpath = locatePluginTemplates(array('list_result_filter'), 'events', 1);
		$xt = $twig->loadTemplate($tpath['list_result_filter'].'list_result_filter.tpl');

		$tVars = array(
			'pagesss' => generateLP( array('current' => $pageNo, 'count' => $countPages, 'url' => '#', 'tpl' => 'pages_filter')),
			'entries' => isset($tEntry)?$tEntry:'',
			'tpl_url'		=>	tpl_url,
			'home'			=>	home,
			'announce_city_filter'	=>	secure_html($announce_city_filter),
			'announce_type_filter'			=>	secure_html($announce_type_filter),
			'announce_sex_filter'			=>	secure_html($announce_sex_filter),
			'announce_page_filter'			=>	secure_html($announce_page_filter),

		);


		if(empty($row)) {
			$results = array(
				'event_filter' => 2,
				'event_filter_text' => iconv('Windows-1251', 'UTF-8','Нет событий подходящих под фильтр!')
			);
		}
		else {
			$results = array(
				'event_filter'	=> 100,
				'event_filter_text' => iconv('Windows-1251', 'UTF-8',$xt->render($tVars))
			);
		
		}



	return array('status' => 1, 'errorCode' => 0, 'data' => $results);
}


function ruDecline($n, $var1, $var2, $var3)
{
    $n = abs($n) % 100;
    $n1 = $n % 10;
    if ($n > 10 && $n < 20) return $var3;
    if ($n1 > 1 && $n1 < 5) return $var2;
    if ($n1 == 1) return $var1;
    return $var3;
}

function dataDiff($birthday)
{
	$birthday_timestamp = strtotime($birthday);
	  $age = date('Y') - date('Y', $birthday_timestamp);
	  if (date('md', $birthday_timestamp) > date('md')) {
		$age--;
	  }
	  return $age;
}

// Generate page list for admin panel
// * current - number of current page
// * count   - total count of pages
// * url	 - URL of page, %page% will be replaced by page number
// * maxNavigations - max number of navigation links
function generateLP($param){
	global $tpl, $TemplateCache;

	if ($param['count'] < 2) return '';

	//templateLoadVariables(true, 1);
	
	//var_dump($TemplateCache['admin']['#variables']['navigation']);
	
	$nav = array(
		"prevlink" => "<a style='cursor:pointer;' data-page='%page%' class='prev'>%page%</a> ",
		"nextlink" => "<a style='cursor:pointer;' data-page='%page%' class='next'>%page%</a> ",
		"current_page" => "<a style='cursor:pointer;' class='current' data-page='%page%'>%page%</a> ",
		"link_page" => "<a style='cursor:pointer;' data-page='%page%'>%page%</a> ",
		"dots" => " ... "
	);
	//$nav = $TemplateCache['admin']['#variables']['navigation'];
	
	$tpl_name = $param['tpl'];
	
	//var_dump($tpl_name);

	$tpath = locatePluginTemplates(array($tpl_name), 'events', 1);
	$tpl -> template($tpl_name, $tpath[$tpl_name]);

	// Prev page link
	if ($param['current'] > 1) {
		$prev = $param['current'] - 1;
		$tvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',str_replace('%page%', $prev, $param['url']), $nav['prevlink']));
	} else {
		$tvars['regx']["'\[prev-link\](.*?)\[/prev-link\]'si"] = "";
		$no_prev = true;
	}

	// ===[ TO PUT INTO CONFIG ]===
	$pages = '';
	if (isset($param['maxNavigations']) && ($param['maxNavigations'] > 3) && ($param['maxNavigations'] < 500)) {
		$maxNavigations		= intval($param['maxNavigations']);
	} else {
		$maxNavigations 		= 10;
	}

	$sectionSize	= floor($maxNavigations / 3);
	if ($param['count'] > $maxNavigations) {
		// We have more than 10 pages. Let's generate 3 parts
		// Situation #1: 1,2,3,4,[5],6 ... 128
		if ($param['current'] < ($sectionSize * 2)) {
			$pages .= generateNavi($param['current'], 1, $sectionSize * 2, $param['url'], $nav);
			$pages .= " ... ";
			$pages .= generateNavi($param['current'], $param['count']-$sectionSize, $param['count'], $param['url'], $nav);
		} elseif ($param['current'] > ($param['count'] - $sectionSize * 2 + 1)) {
			$pages .= generateNavi($param['current'], 1, $sectionSize, $param['url'], $nav);
			$pages .= " ... ";
			$pages .= generateNavi($param['current'], $param['count']-$sectionSize*2 + 1, $param['count'], $param['url'], $nav);
		} else {
			$pages .= generateNavi($param['current'], 1, $sectionSize, $param['url'], $nav);
			$pages .= " ... ";
			$pages .= generateNavi($param['current'], $param['current']-1, $param['current']+1, $param['url'], $nav);
			$pages .= " ... ";
			$pages .= generateNavi($param['current'], $param['count']-$sectionSize, $param['count'], $param['url'], $nav);
		}
	} else {
		// If we have less then 10 pages
		$pages .= generateNavi($param['current'], 1, $param['count'], $param['url'], $nav);
	}


	$tvars['vars']['pages'] = $pages;
	if ($prev + 2 <= $param['count']) {
		$next = $prev + 2;
		$tvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = str_replace('%page%',"$1",str_replace('%link%',str_replace('%page%', $next, $param['url']), $nav['nextlink']));
	} else {
		$tvars['regx']["'\[next-link\](.*?)\[/next-link\]'si"] = "";
		$no_next = true;
	}
	$tpl -> vars($tpl_name, $tvars);
	return $tpl -> show($tpl_name);
}

function generateNavi($current, $start, $stop, $link, $navigations){
	$result = '';
	//print "call generateAdminNavigations(current=".$current.", start=".$start.", stop=".$stop.")<br>\n";
	//print "Navigations: <pre>"; var_dump($navigations); print "</pre>";
	for ($j=$start; $j<=$stop; $j++) {
		if ($j == $current) {
			$result .= str_replace('%page%',$j,$navigations['current_page']);
		} else {
			$row['page'] = $j;
			$result .= str_replace('%page%',$j,str_replace('%link%',str_replace('%page%', $j, $link), $navigations['link_page']));
		}
	}
	return $result;
}


function events_snd_me($params) {
	global $tpl, $template, $twig, $SYSTEM_FLAGS, $config, $userROW, $mysql;

	// Prepare basic reply array
		$results = array();

		$announce_city = intval($params['announce_city']);
		$announce_type = intval($params['announce_type']);
		$announce_name = secure_html($params['announce_name']);
		$announce_place = secure_html($params['announce_place']);
		$announce_timepicker = secure_html($params['announce_timepicker']);
		$announce_datepicker = secure_html($params['announce_datepicker']);
		$announce_description = secure_html($params['announce_description']);

		if(!(isset($userROW) && !empty($userROW)))
		{
			if(!intval(pluginGetVariable('events', 'send_guest'))) {
				$error_text[] = 'Вы не зарегистрированны!';
			}
		}
	
		if(empty($announce_city))
		{
			$error_text[] = 'Вы не заполнили город мероприятия!';
		}
		
		if(!empty($announce_type))
		{
			$cat = $mysql->result('SELECT 1 FROM '.prefix.'_events_cat WHERE id = \'' . $announce_type . '\' LIMIT 1');
			
			if(empty($cat))
			{
				$error_text[] = 'Такой категории мероприятия не существует!';
			}
		} else {
			$error_text[] = 'Вы не заполнили вид мероприятия!';
		}
				
		if(empty($announce_name))
		{
			$error_text[] = 'Вы не заполнили место мероприятия!';
		}
		
		if(empty($announce_place))
		{
			$error_text[] = 'Вы не заполнили место сбора мероприятия!';
		}

		if (empty($announce_timepicker)) 
		{
			$error_text[] = "Вы не заполнили время мероприятия!";
		}
		
		if (empty($announce_datepicker)) 
		{
			$error_text[] = "Вы не заполнили дату мероприятия!";
		}

		if (empty($announce_description)) 
		{
			$error_text[] = "Вы не заполнили описание мероприятия!";
		}

		
		if( empty($error_text) )
		{
		
		$timestamp = strtotime($announce_datepicker." ".$announce_timepicker);
		$editdate = time() + ($config['date_adjust'] * 60);
		
		$mysql->query('INSERT INTO '.prefix.'_events (date, editdate, announce_name, announce_place, author_id, announce_description, cat_id, city, gender, active) 
					VALUES 
					(	'.intval($timestamp).',
						'.intval($editdate).',
						'.db_squote($announce_name).',
						'.db_squote($announce_place).',
						'.db_squote($userROW['id']).',
						'.db_squote($announce_description).',
						'.db_squote($announce_type).',
						'.db_squote($announce_city).',
						'.db_squote($userROW['xfields_ugender']).',
						\'1\'
					)
				');

			$results = array(
				'event_send' => 100,
				'event_send_text' => iconv('Windows-1251', 'UTF-8','Ваше сообщение отправленно!')
			);
		}
		
		if (!empty($error_text))
		{
			$results = array(
				'event_send'	=> 2,
				'event_send_text' => iconv( 'Windows-1251', 'UTF-8', implode('<br />', $error_text) )
			);
		}


	return array('status' => 1, 'errorCode' => 0, 'data' => $results);
}

function plugin_m_events_catz_tree() {
	global $config;

	$events_dir = get_plugcfg_dir('events');
	generate_catz_cache();
	
	if(file_exists($events_dir.'/cache_catz.php')){
		$output = unserialize(file_get_contents($events_dir.'/cache_catz.php'));
	} else {
		$output = '';
	}

	return $output;
}

//
// Show data block for xnews plugin
// Params:
// * number			- Max num entries for top_active_users
// * mode			- Mode for show
// * template		- Personal template for plugin
// * cacheExpire	- age of cache [in seconds]
function plugin_block_events_showTwig($params) {
	global $CurrentHandler, $config;

	return plugin_block_events($params['number'], $params['mode'], $params['cat'], $params['toU'], $params['pagination'], $params['expired'], $params['template'], isset($params['cacheExpire'])?$params['cacheExpire']:0);
}

function plugin_m_events_showTwig($params) {
	global $CurrentHandler, $config;

	return plugin_m_events();
}

function plugin_m_events_catz_tree_showTwig($params) {
	global $CurrentHandler, $config;

	return plugin_m_events_catz_tree();
}

function plugin_block_events_sendTwig($params) {
	global $CurrentHandler, $config;

	return plugin_m_events_send();
}

function plugin_block_events_filterTwig($params) {
	global $CurrentHandler, $config;

	return plugin_m_events_filter();
}

function plugin_block_events_archiveTwig($params) {
	global $CurrentHandler, $config;

	return plugin_m_events_archive();
}


twigRegisterFunction('events', 'show', plugin_block_events_showTwig);
twigRegisterFunction('events', 'show_entries_cnt', plugin_m_events_showTwig);
twigRegisterFunction('events', 'show_catz_tree', plugin_m_events_catz_tree_showTwig);
twigRegisterFunction('events', 'send', plugin_block_events_sendTwig);
twigRegisterFunction('events', 'filter', plugin_block_events_filterTwig);
twigRegisterFunction('events', 'archive', plugin_block_events_archiveTwig);
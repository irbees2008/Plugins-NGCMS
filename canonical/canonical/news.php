<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

class CanonicalNewsFilter extends NewsFilter {

	function showNews($newsID, $SQLnews, &$tvars, &$mode) { 	
		global $CurrentHandler;

		$url = newsGenerateLink($SQLnews,false,isset($_REQUEST['page'])?intval($_REQUEST['page']):0,true);

		if ((($CurrentHandler['handlerName'] == 'news')||($CurrentHandler['handlerName'] == 'print'))&&('http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] != $url))
			register_htmlvar('plain','<link rel="canonical" href="'.$url.'" />');
		return 1;

	}
}
register_filter('news','canonical', new CanonicalNewsFilter);
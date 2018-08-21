<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');

class MessegingNewsFilter extends NewsFilter {

	function addNewsForm(&$tvars) {
		//$perm = checkPermission(array('plugin' => '#admin', 'item' => 'news'), null, array('personal.publish', 'personal.unpublish', 'other.publish', 'other.unpublish'));

		$tvars['plugin']['news_messaging']  = '<label><input type="checkbox" name="news_messaging" value="1" class="check"> Сделать рассылку новости?</label>';
		return 1;
	}
	
function addNews(&$tvars, &$SQL) {
global $mysql, $parse;

$nsubject = pluginGetVariable('news_messaging', 'nsubject');
$ncontent = pluginGetVariable('news_messaging', 'ncontent');

	if ($_REQUEST['news_messaging'] == 1) {
				$mailSubject = str_replace(array('{news_title}'), array($_REQUEST['title']), $nsubject);
				$link_to_news = newsGenerateLink(&$SQL, false, 0, true);
				$mailContent = str_replace(array('{news_content}', '{link_to_news}', '{news_title}'), array($_REQUEST['ng_news_content'], $link_to_news, $_REQUEST['title']), $ncontent);
				list ($short_news, $full_news) = explode('<!--more-->', $mailContent, 2);
				$mailContent  = $short_news.$full_news;
				$mailContent = $parse->htmlformatter($parse->bbcodes($mailContent));
				
				
//print_r($mailSubject);
//print_r($mailContent);
foreach ($mysql->select("SELECT mail FROM `".uprefix."_users`") as $row) {
zzMail($row['mail'], $mailSubject, $mailContent);
}

}
			


		return 1;
	}

}

register_filter('news','news_messaging', new MessegingNewsFilter);
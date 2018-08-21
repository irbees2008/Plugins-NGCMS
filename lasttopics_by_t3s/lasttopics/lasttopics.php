<?php
if (!defined('NGCMS')) die ('HAL');
add_act('index', 'lasttopics');

	function lasttopics(){
		global $tvars, $template, $tvars, $tpl, $mysql;
		global $action, $category, $cstart, $tvars, $year, $month;
		
		$cacheFileName = md5('lasttopics'.$config['theme'].$config['default_lang'].$year.$month).'.txt';
	
		if (extra_get_param('lasttopics','cache')){
		$cacheData = cacheRetrieveFile($cacheFileName, extra_get_param('lasttopics','cacheExpire'), 'lasttopics');
		if ($cacheData != false){
			// We got data from cache. Return it and stop
			$template['vars']['lasttopics'] = $cacheData;
			return;
								}
											}
		

		if ($rssurl = extra_get_param('lasttopics','rssurl')){
		$rssurl = extra_get_param('lasttopics','rssurl');
														}
		else{
		$rssurl = 'http://www.nulled.cc/external.php?type=RSS2';
			}




		$num = extra_get_param('lasttopics','number');
		if (($num < 1) || ($num > 50)) {$num = 10;}
		
		$name_length  = extra_get_param('lasttopics','topicname');
		if (($name_length < 10) || ($name_length > 100)) {$name_length = 30;}
		





 
$rss = simplexml_load_file($rssurl);//�������������� XML-���� � ������
 
//���� ��� ������ ���� RSS �����
foreach ($rss->channel->item as $item) {

		$link = str_replace('&','&amp;',$item->link);

		$title = strip_tags($item->title);//������ ����
		$title = iconv("UTF-8", "CP1251//IGNORE", $title);
		
		$description  = strip_tags($item->description);//������ ����
		$words = split(" ", $description);
		if ( count($words)>$name_length ) $description = join(" ", array_slice($words, 0, $name_length));//�������� ���-�� ���� ������ �� �������� �������
		$description = iconv("UTF-8", "CP1251//IGNORE", $description);


		$tvars['vars'] = array	(
				'topic_link'	=>	$link,
				'topic_name'	=>	$title,
				'topic_data'	=>	$description

								);


			$tpl -> template('lasttopics', extras_dir."/lasttopics/tpl");
			$tpl -> vars('lasttopics', $tvars);
			$output .= $tpl -> show('lasttopics');
			$template['vars']['lasttopics'] = $output;
			if (extra_get_param('lasttopics','cache')) {
				cacheStoreFile($cacheFileName, $output, 'lasttopics');
				}


$i++;

if ($i>=$num) break;

		}



}
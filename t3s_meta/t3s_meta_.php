<?php
if (!defined('NGCMS')) die ('HAL');
add_act('index', 't3s_meta');

function t3s_meta(){
	global $template, $mysql, $tpl, $SYSTEM_FLAGS;
		
	$t3s_id = $SYSTEM_FLAGS['news']['db.id'];
	$t3s_keywd = $SYSTEM_FLAGS['meta']['keywords'];
	$keywd_db = explode(", ", $t3s_keywd);


	$query = "select title, content from ".uprefix."_news where id = ".$t3s_id." LIMIT 1 ";
	$row = $mysql->record($query );
	$t3s_title = $row['title'];
	$t3s_content = $row['content'];

		$t3s_content  = strip_tags($t3s_content);//нахуй лишние теги
		$t3s_content = str_replace('<!--more-->', ' ', $t3s_content);
		$t3s_content = substr($t3s_content,0,160);

		$words = split(" ", $t3s_content);
		if ( count($words)>22 ) $t3s_content = join(" ", array_slice($words, 0, 22));

	$SYSTEM_FLAGS['info'] = '';
	$SYSTEM_FLAGS['info']['title']['item'] = $t3s_title.', '.$keywd_db[0];


	if ($SYSTEM_FLAGS['meta']['description'] == ''){
		$SYSTEM_FLAGS['meta']['description'] = $t3s_content;
	}

}
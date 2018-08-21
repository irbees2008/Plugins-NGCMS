<?php

if (!defined('2z')) { die("Don't you figure you're so cool?"); }

add_act('index_post', 'user_news');


function Prepare(){}
function user_news(){
	global $template, $tpl,$parse, $htmlformatter, $config, $mysql, $users, $action, $user;

	$tem_name = extra_get_param('user_news','templ');
	if ($tem_name == '')
	{$tem_name = 'news.short';}
	$count_per = extra_get_param('user_news','c_num');
	if ($count_per == '')
	{$count_per = 10;}

// - - - blog

	if ($action == 'users'){
		$page = 1;
		$now_dest = $page*11-11;
		$row_n = $mysql->query("select * from ".prefix."_news where author='".$user."' AND approve=1 ORDER by pinned DESC, postdate DESC LIMIT 0,".$count_per."");
		$row_n_num	= $mysql->rows($row_n);
		$template['vars']['mainblock'] .= '<br><h3>Блог пользователя '.$user.'</h3>'; 
		for ($p = 0; $p < $row_n_num; $p++) {
		$tpl -> template($tem_name, tpl_dir.$config['theme']);
		$tvars = Prepare($row, $page);
		$result_arr = mysql_fetch_array($row_n);
		$link =	GetLink($result_arr['alt_name'], $row_n);
		$tvars['vars']['title']		=	$result_arr['title'];
		$tvars['vars']['author']	=	$result_arr['author'];
		$tvars['vars']['category']	=	$result_arr['category'];
		$content = explode('<!--more-->', $result_arr['content']);
		$tvars['vars']['short-story'] = ($config['use_htmlformatter'] && (!$row['raw']))?($parse->htmlformatter($content[0])):($content[0]);
		$tvars['vars']['views']	=	$result_arr['views'];
		$tvars['vars']['date']	=	langdate(timestamp, $result_arr['postdate']);
		$tvars['vars']['comments-num']	=	$result_arr['com'];
		$tvars['vars']['link']	=	GetLink('full', array('alt_name' => $result_arr['alt_name']));
		$tvars['vars']['[link]']	=	'<a href="'.$tvars['vars']['link'].'">';
		$tvars['vars']['[/link]'] = '</a>';
		$tvars['vars']['plugin_rating'] = '';
		
		$tpl -> vars($tem_name, $tvars);
		$template['vars']['mainblock'] .= $parse->smilies($parse->bbcodes($tpl -> show($tem_name)));
		}
		}
		// - - - blog end
}
?>
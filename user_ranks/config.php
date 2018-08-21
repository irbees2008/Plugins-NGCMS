<?php

plugins_load_config();
LoadPluginLang('user_ranks', 'main', '', 'ur');

$cfg = array();
array_push($cfg, array('descr' => $lang['ur_descr']));
array_push($cfg, array('name' => 'rank_type', 'title' => $lang['ur_rank_type'], 'type' => 'select', 'values' => array ( 'com' => $lang['ur_by_com'], 'news' => $lang['ur_by_news']), 'value' => extra_get_param($plugin,'rank_type')));
array_push($cfg, array('name' => 'rank_name1', 'title' => $lang['ur_rank_name'],'type' => 'input', 'html_flags' => 'size=40', 'value' => extra_get_param('user_ranks','rank_name1')));
array_push($cfg, array('name' => 'rank_val1', 'title' => $lang['ur_rank_val'],'type' => 'input', 'html_flags' => 'size=20', 'value' => extra_get_param('user_ranks','rank_val1')));
array_push($cfg, array('name' => 'rank_name2', 'title' => $lang['ur_rank_name'],'type' => 'input', 'html_flags' => 'size=40', 'value' => extra_get_param('user_ranks','rank_name2')));
array_push($cfg, array('name' => 'rank_val2', 'title' => $lang['ur_rank_val'],'type' => 'input', 'html_flags' => 'size=20', 'value' => extra_get_param('user_ranks','rank_val2')));
array_push($cfg, array('name' => 'rank_name3', 'title' => $lang['ur_rank_name'],'type' => 'input', 'html_flags' => 'size=40', 'value' => extra_get_param('user_ranks','rank_name3')));
array_push($cfg, array('name' => 'rank_val3', 'title' => $lang['ur_rank_val'],'type' => 'input', 'html_flags' => 'size=20', 'value' => extra_get_param('user_ranks','rank_val3')));
array_push($cfg, array('name' => 'rank_name4', 'title' => $lang['ur_rank_name'],'type' => 'input', 'html_flags' => 'size=40', 'value' => extra_get_param('user_ranks','rank_name4')));
array_push($cfg, array('name' => 'rank_val4', 'title' => $lang['ur_rank_val'],'type' => 'input', 'html_flags' => 'size=20', 'value' => extra_get_param('user_ranks','rank_val4')));
array_push($cfg, array('name' => 'rank_name5', 'title' => $lang['ur_rank_name'],'type' => 'input', 'html_flags' => 'size=40', 'value' => extra_get_param('user_ranks','rank_name5')));
array_push($cfg, array('name' => 'rank_val5', 'title' => $lang['ur_rank_val'],'type' => 'input', 'html_flags' => 'size=20', 'value' => extra_get_param('user_ranks','rank_val5')));
array_push($cfg, array('name' => 'rank_name6', 'title' => $lang['ur_rank_name'],'type' => 'input', 'html_flags' => 'size=40', 'value' => extra_get_param('user_ranks','rank_name6')));
array_push($cfg, array('name' => 'rank_val6', 'title' => $lang['ur_rank_val'],'type' => 'input', 'html_flags' => 'size=20', 'value' => extra_get_param('user_ranks','rank_val6')));
array_push($cfg, array('name' => 'rank_name7', 'title' => $lang['ur_rank_name'],'type' => 'input', 'html_flags' => 'size=40', 'value' => extra_get_param('user_ranks','rank_name7')));
array_push($cfg, array('name' => 'rank_val7', 'title' => $lang['ur_rank_val'],'type' => 'input', 'html_flags' => 'size=20', 'value' => extra_get_param('user_ranks','rank_val7')));
array_push($cfg, array('name' => 'rank_guest', 'title' => $lang['ur_rank_guest'],'type' => 'input', 'html_flags' => 'size=40', 'value' => extra_get_param('user_ranks','rank_guest')));

if ($_REQUEST['action'] == 'commit') {
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page('user_ranks', $cfg);
}


?>
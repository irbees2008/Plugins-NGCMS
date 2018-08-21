<?php

plugins_load_config();
LoadPluginLang('messaging', 'messaging', '', 'mes');

$cfg = array();
array_push($cfg, array('descr' => $lang['mes_descr']));
array_push($cfg, array('name' => 'method', 'title' => $lang['mes_method'], 'type' => 'select', 'values' => array ( '0' => $lang['mes_by_mail'], '1' => $lang['mes_by_pm'])));
array_push($cfg, array('name' => 'group', 'title' => $lang['mes_group'], 'type' => 'select', 'values' => array ( '0' => $lang['mes_all'], '1' => $lang['mes_admins'], '2' => $lang['mes_editors'], '3' => $lang['mes_journalists'])));
array_push($cfg, array('name' => 'subject', 'title' => $lang['mes_subject'], 'type' => 'input', 'html_flags' => 'size=40', 'value' => ''));
array_push($cfg, array('type' => 'manual', 'input' => QuickTags(false, "pmmes")));
array_push($cfg, array('type' => 'manual', 'input' => ($config['use_smilies'] == "1") ? InsertSmilies("content", 10) : ''));
array_push($cfg, array('name' => 'content', 'title' => $lang['mes_content'], 'type' => 'text', 'html_flags' => 'rows=8 cols=60', 'value' => ''));

if ($_REQUEST['action'] == 'commit') {
	messaging($_REQUEST['method'], $_REQUEST['group'], $_REQUEST['subject'], $_REQUEST['content']);
} else {
	generate_config_page('messaging', $cfg);
}


?>
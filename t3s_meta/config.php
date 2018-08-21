<?php

//
// Configuration file for plugin
//

// Preload config file
plugins_load_config();


// Fill configuration parameters

$cfg = array();

$cfgX = array();
array_push($cfgX, array('name' => 'cache', 'title' => "������������ �����������<br /><small><b>��</b> - ����������� ������������<br /><b>���</b> - ����������� �� ������������</small>", 'type' => 'select', 'values' => array ( '1' => '��', '0' => '���'), 'value' => intval(extra_get_param('t3s_meta','cache'))));
array_push($cfgX, array('name' => 'cacheExpire', 'title' => '������ ���������� ���� (� ��������)<br /><small>(����� ������� ������ ���������� ���������� ����. �������� �� ���������: <b>10800</b>, �.�. 3 ����)', 'type' => 'input', 'value' => intval(extra_get_param('t3s_meta','cacheExpire'))?extra_get_param('t3s_meta','cacheExpire'):'10800'));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>��������� �����������</b>', 'entries' => $cfgX));
// RUN 
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes('t3s_meta', $cfg);
	print_commit_complete('t3s_meta');
} else {
	generate_config_page('t3s_meta', $cfg);
}

?>
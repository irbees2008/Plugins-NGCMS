<?php

# protect against hack attempts
if (!defined('NGCMS')) die ('Galaxy in danger');

# preload config file
PluginsLoadConfig();

# fill configuration parameters
$cfg = array();

$cfgX = array();
array_push($cfgX, array('name' => 'localsource', 'title' => '�������� ������� �� �������� ������ ����� ����� ������� ��� �����������<br /><small><b>������ �����</b> - ������ ����� �������� ����� ������� �� ������ ������� �����; � ������ ������������� - ������� ����� ����� �� ������������ �������� �������<br /><b>������</b> - ������� ����� ������� �� ������������ �������� �������</small>', 'type' => 'select', 'values' => array ( '0' => '������ �����', '1' => '������'), 'value' => intval(pluginGetVariable($plugin,'localsource'))));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>��������� �����������</b>', 'entries' => $cfgX));

$cfgX = array();
array_push($cfgX, array('name' => 'timeout', 'title' => '�������<br /><small>������ ������������ �� ��������� N ������<br />�������� �� ���������: <b>300</b></small>', 'type' => 'input', 'value' => intval(pluginGetVariable($plugin, 'timeout'))?pluginGetVariable($plugin ,'timeout'):'300'));
array_push($cfgX, array('name' => 'time_clear', 'title' => '������� ������� �� �� ������ �������<br /><small>�������� �� ���������: <b>3600</b></small>', 'type' => 'input', 'value' => intval(pluginGetVariable($plugin, 'time_clear'))?pluginGetVariable($plugin ,'time_clear'):'3600'));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>�����</b>', 'entries' => $cfgX));


// RUN 
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page($plugin, $cfg);
}

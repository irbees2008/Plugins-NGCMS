<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');
// Preload config file
plugins_load_config();

// Fill configuration parameters
$cfg = array();
array_push($cfg, array('name' => 'maillist', 'title' => "������� ����� ������� ������ e-mail �� ������� ����� ���������� ������ �����", 'type' => 'input', 'value' => extra_get_param($plugin,'maillist')));
array_push($cfg, array('name' => 'allowexts', 'title' => "������� ����� ������� ������ ���������� ������ ���������� � �������", 'type' => 'input', 'value' => extra_get_param($plugin,'allowexts')));
array_push($cfg, array('name' => 'maxf', 'title' => "������������ ����� �������������� ����� (� ��)", 'type' => 'input', 'value' => extra_get_param($plugin,'maxf')));
array_push($cfg, array('name' => 'localsource', 'title' => "�������� ������� �� �������� ������ ����� ����� ������� ��� �����������<br /><small><b>������ �����</b> - ������ ����� �������� ����� ������� �� ������ ������� �����; � ������ ������������� - ������� ����� ����� �� ������������ �������� �������<br /><b>������</b> - ������� ����� ������� �� ������������ �������� �������</small>", 'type' => 'select', 'values' => array ( '0' => '������ �����', '1' => '������'), 'value' => intval(extra_get_param($plugin,'localsource'))));
// RUN 
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page($plugin, $cfg);
}


?>

<?php

	// Protect against hack attempts
    if (!defined('NGCMS')) die ('Galaxy in danger');
	
	// Preload config file
    plugins_load_config();
	
	// Fill configuration parameters
    $cfg = array();
	array_push($cfg,  array('descr' => '������ ���������� ������ �� ��������� � ���������� �������.'));
	array_push($cfg, array('name' => 'full_mode', 'title' => "�������� � ������ �������", 'type' => 'checkbox', value => extra_get_param('neighboring_news','full_mode')));
	array_push($cfg, array('name' => 'short_mode', 'title' => "�������� � ������� �������<br /><small>�� �������������, �.�. ���������� �������� � �� ���������� �� (2*���������� �������� �� ������� ��������)</small>", 'type' => 'checkbox', value => extra_get_param('neighboring_news','short_mode')));
	array_push($cfg, array('name' => 'compare', 'title' => '�������� ������� �� ���������', 'type' => 'select',  'values' => array ( '1' => '1 - ��������� ������ �������', '2' => '2 - ������ ������������'), value => intval(extra_get_param('neighboring_news','compare'))));
	array_push($cfg, array('name' => 'localsource', 'title' => "�������� ������� �� �������� ������ ����� ����� ������� ��� �����������<br /><small><b>������ �����</b> - ������ ����� �������� ����� ������� �� ������ ������� �����; � ������ ������������� - ������� ����� ����� �� ������������ �������� �������<br /><b>������</b> - ������� ����� ������� �� ������������ �������� �������</small>", 'type' => 'select', 'values' => array ( '0' => '������ �����', '1' => '������'), 'value' => intval(extra_get_param('neighboring_news','localsource'))));
	
	// RUN 
    if ($_REQUEST['action'] == 'commit') {
       // If submit requested, do config save
       commit_plugin_config_changes('neighboring_news', $cfg);
       print_commit_complete('neighboring_news');
    } else {
       generate_config_page('neighboring_news', $cfg);
    }
?>

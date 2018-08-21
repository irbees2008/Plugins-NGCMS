<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');


//
// Configuration file for plugin
//

// Preload config file
plugins_load_config();


// Fill configuration parameters
$cfg = array();
$cfgX = array();
array_push($cfg, array('descr' => '������ ������ �������������� ����������'));
array_push($cfgX, array('name' => 'iplock', 'title' => '� ������ ���������� ���������� �������� ����� ��� �����?', 'descr' => '�������� ��� ������ � ������ ���� �� ������������ ������� ���������� �� �����������','type' => 'select',  'values' => array ( '0' => '�����', '1' => '�����'), value => pluginGetVariable('stats','iplock')));
array_push($cfgX, array('name' => 'en_dbprefix1', 'title' => '������������ ���������� ����������� �������?', 'descr' => '���� �� ��������� �������, �� ������ ������� ���������� ����������� �������','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix1')));
array_push($cfgX, array('name' => 'en_dbprefix2', 'title' => '������������ ���������� ���������?', 'descr' => '���� �� ��������� �������, �� ������ ������� ���������� ���������','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix2')));
array_push($cfgX, array('name' => 'en_dbprefix3', 'title' => '������������ ���������� ��������?', 'descr' => '���� �� ��������� �������, �� ������ ������� ���������� ��������','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix3')));
array_push($cfgX, array('name' => 'en_dbprefix4', 'title' => '������������ ���������� ���������������� ��������?', 'descr' => '���� �� ��������� �������, �� ������ ������� ���������� ���������������� ��������','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix4')));
array_push($cfgX, array('name' => 'en_dbprefix5', 'title' => '������������ ���������� ������������?', 'descr' => '���� �� ��������� �������, �� ������ ������� ���������� ������������','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix5')));
array_push($cfgX, array('name' => 'en_dbprefix6', 'title' => '������������ ���������� ������������������ �������������?', 'descr' => '���� �� ��������� �������, �� ������ ������� ���������� ������������������ �������������','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix6')));
array_push($cfgX, array('name' => 'en_dbprefix7', 'title' => '������������ ���������� ���������� �������������?', 'descr' => '���� �� ��������� �������, �� ������ ������� ���������� ���������� �������������','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix7')));
array_push($cfgX, array('name' => 'en_dbprefix8', 'title' => '������������ ���������� ����������� �����������?', 'descr' => '���� �� ��������� �������, �� ������ ������� ���������� ����������� �����������','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix8')));
array_push($cfgX, array('name' => 'en_dbprefix9', 'title' => '������������ ���������� ����������� ������?', 'descr' => '���� �� ��������� �������, �� ������ ������� ���������� ����������� ������','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix9')));
array_push($cfgX, array('name' => 'en_dbprefix10', 'title' => '������������ ���������� ����� �� ����?', 'descr' => '���� �� ��������� �������, �� ������ ������� ���������� ���������� �� ����','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix10')));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>�������� ���������</b>', 'entries' => $cfgX));

$cfgX = array();
array_push($cfgX, array('name' => 'en_dbprefix', 'title' => '������������ ������ ������� ��� �������', 'descr' => '�� ������ ������������ ������� ������� ���������� NG CMS ���� �� ���������� � ��� �� ��','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix')));
array_push($cfgX, array('name' => 'dbprefix', 'title' => '������ ������� ��� ������� �������������', 'descr' => '������� ��� ������ ������� ������� ������������� NG CMS, ������� �� ������ ������������ ��� �����������. ������� ������ ���� � ��� �� �� ��� � ������� ����������� NG CMS.','type' => 'input', value => pluginGetVariable('stats','dbprefix')));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>��������� ������� ��� ������</b>', 'entries' => $cfgX));

// RUN
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes('stats', $cfg);
	print_commit_complete('stats');
} else {
	generate_config_page('stats', $cfg);
}


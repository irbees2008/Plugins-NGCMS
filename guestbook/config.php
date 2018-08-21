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
array_push($cfgX, array('name' => 'usmilies', 'title' => "��������� ������������ ������ ��� ��������� ���������?", 'type' => 'select', 'values' => array ( '1' => '��', '0' => '���'), 'value' => intval(extra_get_param($plugin,'usmilies'))));
array_push($cfgX, array('name' => 'ubbcodes', 'title' => "��������� ������������ BB-���� ��� ��������� ���������?", 'type' => 'select', 'values' => array ( '1' => '��', '0' => '���'), 'value' => intval(extra_get_param($plugin,'ubbcodes'))));
array_push($cfgX, array('name' => 'minlength', 'title' => "����������� ����� ���������", 'descr' => "", 'html_flags' => 'size=5', 'type' => 'input', 'value' => intval(extra_get_param($plugin,'minlength'))?extra_get_param($plugin,'minlength'):'3'));
array_push($cfgX, array('name' => 'maxlength', 'title' => "������������ ����� ���������", 'descr' => "��� ���������� �������� ����� ������ ������", 'html_flags' => 'size=5', 'type' => 'input', 'value' => intval(extra_get_param($plugin,'maxlength'))?extra_get_param($plugin,'maxlength'):'500'));
array_push($cfgX, array('name' => 'guests', 'title' => "��������� ��������� ������ ������?", 'type' => 'select', 'values' => array ( '1' => '��', '0' => '���'), 'value' => intval(extra_get_param($plugin,'guests'))));
array_push($cfgX, array('name' => 'ecaptcha', 'title' => "���������� CAPTCHA ��� ������?", 'type' => 'select', 'values' => array ( '1' => '��', '0' => '���'), 'value' => intval(extra_get_param($plugin,'ecaptcha'))));
array_push($cfgX, array('name' => 'perpage', 'title' => "���������� ������� �� ��������", 'descr' => "", 'html_flags' => 'size=5', 'type' => 'input', 'value' => intval(extra_get_param($plugin,'perpage'))?extra_get_param($plugin,'perpage'):'15'));
array_push($cfgX, array('name' => 'order', 'title' => "������� ���������� ���������, �����������", 'type' => 'select', 'values' => array ( 'DESC' => '����������', 'ASC' => '�������'), 'value' => extra_get_param($plugin,'order')));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>��������� �������� �����</b>', 'entries' => $cfgX));


// RUN 
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page($plugin, $cfg);
}


?>
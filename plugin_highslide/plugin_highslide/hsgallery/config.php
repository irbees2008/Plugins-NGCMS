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
array_push($cfgX, array('name' => 'viewmode', 'title' => "������� ����������� �������.",'descr' =>"���� �� ������� '���� �������' - ���������� ��� � ����������� HighSlide � ����� hsscript_0.tpl ", 'type' => 'select', 'values' => array ( '0'=>'���� �������','1' => '�������', '2' => '�����-��� �� �����','3'=>'�������� � �����������','4'=>'���������� ��������'), 'value' => intval(extra_get_param($plugin,'viewmode'))));
array_push($cfgX, array('name' => 'enhsglobal', 'title' => "�������� ������ highslide �����", 'descr' =>"����� �������� ������ highslide �� ��� �����, ���� �� ������ ������������ ��������� ��� ���� �� ��� �����.<br> ��� �� ���������� ��������� ������ 4 � 5 �� ���� ���������� <a href='http://ngcms.ru/forum/viewtopic.php?id=283&p=1'>http://ngcms.ru/forum/viewtopic.php?id=283&p=1</a> <br> ��������!!! ���� �� ��� ����������� ��������� highslide �� ���� ���������� <a href='http://ngcms.ru/forum/viewtopic.php?id=283&p=1'>http://ngcms.ru/forum/viewtopic.php?id=283&p=1</a> <br> ������� �� main.tpl ��� �� ������ 3 � �������� ��� ����� ", 'type' => 'checkbox', 'value' => extra_get_param($plugin,'enhsglobal')));
array_push($cfgX, array('name' => 'globalmode', 'title' => "������� ����������� highslide - ��������� �� ������ ��������� �����.",'descr' =>"���� �������� ��������� ����", 'type' => 'select', 'values' => array ( '0'=>'���� �������','1' => '�������', '2' => '�����-��� �� �����','3'=>'�������� � �����������'), 'value' => intval(extra_get_param($plugin,'globalmode'))));
array_push($cfgX, array('name' => 'folders', 'title' => "Altname ������� ��� �������", 'descr' => "������� altname ����������� �������, ����� �������,  � ������� ����� �������������� �������<br>��������!!! ��� �� ��� ������ �������� ������ ���� ������� ��������� ����������� � ����� �� ���������", 'type' => 'input', 'value' => (extra_get_param($plugin,'folders'))?extra_get_param($plugin,'folders'):''));
array_push($cfgX, array('name' => 'hscolor', 'title' => "���� ����������", 'descr' =>"��������� ������ ��� ����� ���������� highslide -������ �� ������ ���� (�� ��������� - �����)<br>�� ������ ������������� ������ ����� �������������� ���� highslide.css", 'type' => 'checkbox', 'value' => extra_get_param($plugin,'hscolor')));
array_push($cfgX, array('name' => 'localsource', 'title' => "�������� ������� �� �������� ������ ����� ����� ������� ��� �����������<br /><small><b>������ �����</b> - ������ ����� �������� ����� ������� �� ������ ������� �����; � ������ ������������� - ������� ����� ����� �� ������������ �������� �������<br /><b>������</b> - ������� ����� ������� �� ������������ �������� �������</small>", 'type' => 'select', 'values' => array ( '0' => '������ �����', '1' => '������'), 'value' => intval(extra_get_param($plugin,'localsource'))));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>��������� �����������</b>', 'entries' => $cfgX));
$cfgX = array();
array_push($cfgX, array('name' => 'cache', 'title' => "������������ ����������� ������<br /><small><b>��</b> - ����������� ������������<br /><b>���</b> - ����������� �� ������������</small><br>��������!!! ���� ������� ��������� �� ���������� ���������, ����������� ������ �� ����� ��������� ����� ������������ ���� � �� ��, ", 'type' => 'select', 'values' => array ( '1' => '��', '0' => '���'), 'value' => intval(extra_get_param($plugin,'cache'))));
array_push($cfgX, array('name' => 'cacheExpire', 'title' => "������ ���������� ����<br /><small>(����� ������� ������ ���������� ���������� ����. �������� �� ���������: <b>60</b>)</small>", 'html_flags' => 'size=5', 'type' => 'input', 'value' => intval(extra_get_param($plugin,'cacheExpire'))?extra_get_param($plugin,'cacheExpire'):'60'));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>��������� �����������</b>', 'entries' => $cfgX));


// RUN
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page($plugin, $cfg);
}


?>

<?php
//
// Configuration file for plugin
//

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');
// Preload config file
plugins_load_config();
// Fill configuration parameters
$cfg = array();
array_push($cfg, array('descr' => '������ ������ �� ���� � �� �������. ���� ����� ��������� ��������� ���������.'));


$cfgX = array();
array_push($cfgX, array('name' => 'cache', 'title' => "������������ �����������<br /><small><b>��</b> - ����������� ������������<br /><b>���</b> - ����������� �� ������������</small>", 'type' => 'select', 'values' => array ( '1' => '��', '0' => '���'), 'value' => intval(extra_get_param('adssearch1','cache'))));
array_push($cfgX, array('name' => 'cacheExpire', 'title' => '������ ���������� ���� (� ��������)<br /><small>(����� ������� ������ ���������� ���������� ����. �������� �� ���������: <b>10800</b>, �.�. 3 ����)', 'type' => 'input', 'value' => intval(extra_get_param('adssearch1','cacheExpire'))?extra_get_param('adssearch1','cacheExpire'):'10800'));
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
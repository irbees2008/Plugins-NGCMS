<?php

//
// Configuration file for plugin
//

// Preload config file
plugins_load_config();


// Fill configuration parameters

$cfg = array();

$cfgX = array();
array_push($cfgX, array('name' => 'position', 'title' => "���������� ���������� �����:<small><br /><b>�����</b> -  �� ���������� �����<br /><b>�����</b> - ������ �� �������� ��������<br /><b>!����� </b> - ����� ����� �������<br /><b>�����</b> - �� ���� ���������</small>", 'type' => 'select', 'values' => array ( '' => '�����', 'root' => '�����', 'noroot' => '!�����', 'all' => '�����'), 'value' => extra_get_param('lasttopics','position')));
array_push($cfgX, array('name' => 'rssurl', 'title' => '����� RSS-����� ������:', 'descr' => '����� RSS-����� ������ (����.: http://www.nulled.cc/<b>external.php?type=RSS2</b>)', 'type' => 'input', 'value' => extra_get_param('lasttopics','rssurl')));
array_push($cfgX, array('name' => 'number', 'title' => '���������� ��������� ���������:', 'descr' => '��������: 10', 'type' => 'input', 'value' => extra_get_param('lasttopics','number')));
array_push($cfgX, array('name' => 'topicname', 'title' => '���-�� ���� � ������:', 'descr' => '��������: 25', 'type' => 'input', 'value' => extra_get_param('lasttopics','topicname')));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>��������� ������ �������� ���������</b>', 'entries' => $cfgX));

$cfgX = array();
array_push($cfgX, array('name' => 'cache', 'title' => "������������ ����������� ����� �����<br /><small><b>��</b> - ����������� ������������<br /><b>���</b> - ����������� �� ������������</small>", 'type' => 'select', 'values' => array ( '1' => '��', '0' => '���'), 'value' => intval(extra_get_param('lasttopics','cache'))));
array_push($cfgX, array('name' => 'cacheExpire', 'title' => '������ ���������� ���� (� ��������)<br /><small>(����� ������� ������ ���������� ���������� ����. �������� �� ���������: <b>10800</b>, �.�. 3 ����)', 'type' => 'input', 'value' => intval(extra_get_param('lasttopics','cacheExpire'))?extra_get_param('lasttopics','cacheExpire'):'10800'));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>��������� �����������</b>', 'entries' => $cfgX));
// RUN 
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes('lasttopics', $cfg);
	print_commit_complete('lasttopics');
} else {
	generate_config_page('lasttopics', $cfg);
}

?>
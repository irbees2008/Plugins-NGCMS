<?php
plugins_load_config();

$cfg = array();
array_push($cfg, array('descr' => '������ ������� ������ �������������, ������������������ �� �����.'));
array_push($cfg, array('name' => 'perpage',   'title' => '���-�� ������������� ��� ����������� �� ����� ��������', 'type' => 'input', 'value' => extra_get_param($plugin,'perpage')));
array_push($cfg, array('name' => 'sort',   'title' => '����������� ��:', 'descr' => '�������� �� ������ ��������� ����������� �������������.', 'type' => 'select', 'values' => array ( 'nickname' => '���', 'status' => '������', 'num_news' => '���-�� ��������', 'num_com' => '���-�� ������������', 'regdate' => '���������������'), 'value' => extra_get_param($plugin,'sort')));
array_push($cfg, array('name' => 'order',   'title' => '����������� ��:', 'descr' => '�������� ������� ����������� �������������.', 'type' => 'select', 'values' => array ('asc' => '�����������', 'desc' => '��������'), 'value' => extra_get_param($plugin,'order')));
array_push($cfg, array('name' => 'fdate',   'title' => '������ ������ ����', 'descr' => '��������� �������� �������� <a href="http://php.net/date">�����</a>.', 'type' => 'input', 'value' => extra_get_param($plugin,'fdate')));

if ($_REQUEST['action'] == 'commit') {
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete('userlist');
} else {
	generate_config_page('userlist', $cfg);
}
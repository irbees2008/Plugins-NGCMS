<?php

if (!defined('NGCMS')) die ('HAL');

plugins_load_config();

$cfg = array();
$cfgX = array();
array_push($cfg, array('descr' => '������ ���� �� ����� �������'));

$cfgX = array();
array_push($cfgX, array('name' => 'p_count', 'title' => "���������� ����� ����� ������ � ����� �������", 'type' => 'input', 'value' => intval(extra_get_param($plugin,'p_count'))));
array_push($cfgX, array('name' => 'c_replace', 'title' => "����� ������", 'type' => 'select', 'values' => array ( '0' => '�� ������ ����������', '1' => '������ ��������� ��� ����� ��������', '2' => '������ ���������� � ������ ��������'), 'value' => intval(extra_get_param($plugin,'c_replace'))));
array_push($cfgX, array('name' => 'replace', 'title' => "������<br><br><i>������� ����� ����� ��������� | � ��������� �����</i><br />������:<br />test|http://test|2<br />test2|http://test2<br>������: ���_������|��_���_��������|����������_�_�����_�������",'type' => 'text', 'html_flags' => 'rows=20 cols=130', 'value' => extra_get_param($plugin,'replace')));
array_push($cfgX, array('name' => 'str_url', 'title' => "������ �������<br /><small>�����:<br /><b>%search%</b> - ������� �����<br /><b>%replace%</b> - ���������� �����<br /><b>%scriptLibrary%</b> - ���� �� ��������� http://site/lib<br /><b>%home%</b> - ����� ����� http://ngcms<br /></small><br />������: <pre><a href='%replace%'>%search%</a></pre>", 'type' => 'input', 'html_flags' =>'size="80"', 'value' => extra_get_param($plugin,'str_url')));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>��������� �������</b>', 'entries' => $cfgX));

if ($_REQUEST['action'] == 'commit') {
	commit_plugin_config_changes('text_replace', $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page('text_replace', $cfg);
}

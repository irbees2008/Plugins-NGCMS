<?php
if (!defined('NGCMS')) { die("Don't you figure you're so cool?"); }

plugins_load_config();
	$cfg = array();
		array_push($cfg, array('name' => 'length', 'title' => '����������� ����� �����', 'descr' => '(������� ������� 5)', 'type' => 'input', 'html_flags' => 'style="width: 200px;"', 'value' => extra_get_param('autokeys','length')));
		array_push($cfg, array('name' => 'sub', 'title' => '������������ ����� �����', 'descr' => '�� ��������� �� ����������', 'type' => 'input', 'html_flags' => 'style="width: 200px;"', 'value' => extra_get_param('autokeys','sub')));
		array_push($cfg, array('name' => 'occur', 'title' => '����������� ����� ���������� �����', 'descr' => '(������� ������� 2)','type' => 'input', 'html_flags' => 'style="width: 200px;"', 'value' => extra_get_param('autokeys','occur')));
		array_push($cfg, array('name' => 'block_y', 'title' => '<b>������������� �����</b>', 'descr' => '���������/���������� �����','type' => 'checkbox', value => extra_get_param('autokeys','block_y')));
		array_push($cfg, array('name' => 'block', 'title' => '������ ������������� ����<br><br><i>�� ������ ������ �������� �� ����� �����. ����� �� ����� ������ �� ����� �������� � keywords.</i>','type' => 'text', 'html_flags' => 'rows=8 cols=60', 'value' => extra_get_param('autokeys','block')));
		array_push($cfg, array('name' => 'good_y', 'title' => '<b>�������� �����</b>', 'descr' => '���������/���������� �����','type' => 'checkbox', value => extra_get_param('autokeys','good_y')));
		array_push($cfg, array('name' => 'good', 'title' => '������ �������� ����<br><br><i>�� ������ ������ �������� �� ����� �����. ����� �� ����� ������ ����� �������� � keywords.</i>','type' => 'text', 'html_flags' => 'rows=8 cols=60', 'value' => extra_get_param('autokeys','good')));
		array_push($cfg, array('name' => 'add_title', 'title' => '��������� ���������', 'descr' => '���������� ��������� ������� � ������ ������� ��� ��������� �������� ����<br />�������� �� 0 �� �������������: <br />0 - �� ���������, 1 - ���������, 2 - �������� ��� ����', 'type' => 'input', 'html_flags' => 'style="width: 200px;"', 'value' => extra_get_param('autokeys','add_title')));
		array_push($cfg, array('name' => 'sum', 'title' => '����� �������� ����', 'descr' => '����� ���� �������� ���� ������������ �������� (�� ��������� <=245 �������)', 'type' => 'input', 'html_flags' => 'style="width: 200px;"', 'value' => extra_get_param('autokeys','sum')));
		array_push($cfg, array('name' => 'count', 'title' => '���������� �������� ����', 'descr' => '���������� �������� ���� ������������ �������� (�� ��������� �������������� ����������)', 'type' => 'input', 'html_flags' => 'style="width: 200px;"', 'value' => extra_get_param('autokeys','count')));
		array_push($cfg, array('name' => 'good_b', 'title' => '<b>�������� ����</b>', 'descr' => '�������� ���� � ���� [b]','type' => 'checkbox', value => extra_get_param('autokeys','good_b')));
		
		
	if ($_REQUEST['action'] == 'commit') {
		commit_plugin_config_changes('autokeys', $cfg);
		print "��������� ��������: <a href='admin.php?mod=extra-config&plugin=autokeys'>������� ��� �����</a>\n";
	} else {
		generate_config_page('autokeys', $cfg);
	}
?>
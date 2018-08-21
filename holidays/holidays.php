<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');


add_act('index', 'holidays');

// Get content [ array - content and deferred elements ]
function holidays() {
global $template;


// ������������
$klvmsg="7";  // ������� �������� ���?
$klvdays="30";  // ������������ �������� �������, ����
$datafile=root."plugins/holidays/dat_holidays/holidays.dat"; // ��� ����� ���� ������
$months = array("", "������", "�������", "�����", "������", "���", "����", "����", "�������", "��������", "�������", "������", "�������");
$date=date("d ".$months[date('n')]." Y"); // �����.�����.���
$time=date("H:i:s"); // ����:������:�������

$holidays .= "";
$day=$date=date("d"); // ����
$month=$date=date("n"); // �����
$year=$date=date("Y"); // ���
if ($month==12) {$year++;} // ����� ����� ������ ��������� ���������
$vchera=$day-1;
$klvchasov=$klvdays*30;
$lines=file($datafile);
$itogo=count($lines); $i=0;

do {$dt=explode("|",$lines[$i]);

$todaydate=date("d ".$months[date('n')]." Y");
$tekdt=mktime();

$newdate=mktime(0,0,0,$dt[1],$dy[0],$year);
$dayx=date("d ".$months[date('n')]." Y",$newdate); // ����������� ��� �� ��������� � ������������ ������
$hdate=ceil(($newdate-$tekdt)/3600); // ����� ������� ����� �������� �������
$ddate=ceil($hdate/24); // ������� ������� ���� �� �������

// �������� ����� ����/���/���� � ������� ����
$dney="����"; if ($ddate=="1") {$dney="����";} if ($ddate=="2" or $ddate=="3" or $ddate=="4") {$dney="���";}

if (($dt[0]==$vchera) and ($dt[1]==$month)) {$holidays .= "<IMG src='/engine/plugins/holidays/images/happy2.gif'> ����� ��� ��������:<IMG src='/engine/plugins/holidays/images/down.gif'> <strong>$dt[2]</strong>";}
if (($dt[0]==$day) and ($dt[1]==$month)) {$holidays .= "<IMG src='/engine/plugins/holidays/images/happy.gif'> ������� ��������:<IMG src='/engine/plugins/holidays/images/down.gif'> <strong>$dt[2]</strong><br>";}
if ($klvmsg>1) {

if (($hdate>1) and ($hdate<$klvchasov)) {
if (!isset($m1)) {$holidays .= "<IMG src='/engine/plugins/holidays/images/info.gif'> � ��������� ����� ��������� ���������:<DIV style='BORDER-BOTTOM: #515151 1px dashed'></DIV>"; $m1=1;}
$klvmsg--; $holidays .="<IMG src='/engine/plugins/holidays/images/data.gif'> <font color='#cc0017'><B>$dayx</B></font> <small>����� <B>$ddate</B> $dney</small><br><IMG src='/engine/plugins/holidays/images/down.gif'> $dt[2]<DIV style='BORDER-BOTTOM: #515151 1px dashed'></DIV>";} }

$i++;
} while($i<$itogo);

$holidays .= "";


$output = $holidays;

$template['vars']['plugin_holidays'] = $output;
}
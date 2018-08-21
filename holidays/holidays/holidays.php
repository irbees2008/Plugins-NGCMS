<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');


add_act('index', 'holidays');

// Get content [ array - content and deferred elements ]
function holidays() {
global $template;


// Конфигурация
$klvmsg="7";  // Сколько выводить дат?
$klvdays="30";  // Максимальное удалённое событие, дней
$datafile=root."plugins/holidays/dat_holidays/holidays.dat"; // Имя файла базы данных
$months = array("", "января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
$date=date("d ".$months[date('n')]." Y"); // число.месяц.год
$time=date("H:i:s"); // часы:минуты:секунды

$holidays .= "";
$day=$date=date("d"); // день
$month=$date=date("n"); // месяц
$year=$date=date("Y"); // год
if ($month==12) {$year++;} // Чтобы верно считал январские праздники
$vchera=$day-1;
$klvchasov=$klvdays*30;
$lines=file($datafile);
$itogo=count($lines); $i=0;

do {$dt=explode("|",$lines[$i]);

$todaydate=date("d ".$months[date('n')]." Y");
$tekdt=mktime();

$newdate=mktime(0,0,0,$dt[1],$dy[0],$year);
$dayx=date("d ".$months[date('n')]." Y",$newdate); // конверируем дни до праздника в человеческий формат
$hdate=ceil(($newdate-$tekdt)/3600); // через сколько ЧАСОВ наступит событие
$ddate=ceil($hdate/24); // считаем сколько дней до события

// приводим слово ДЕНЬ/ДНЯ/ДНЕЙ к нужному типу
$dney="дней"; if ($ddate=="1") {$dney="день";} if ($ddate=="2" or $ddate=="3" or $ddate=="4") {$dney="дня";}

if (($dt[0]==$vchera) and ($dt[1]==$month)) {$holidays .= "<IMG src='/engine/plugins/holidays/images/happy2.gif'> Вчера был праздник:<IMG src='/engine/plugins/holidays/images/down.gif'> <strong>$dt[2]</strong>";}
if (($dt[0]==$day) and ($dt[1]==$month)) {$holidays .= "<IMG src='/engine/plugins/holidays/images/happy.gif'> Сегодня праздник:<IMG src='/engine/plugins/holidays/images/down.gif'> <strong>$dt[2]</strong><br>";}
if ($klvmsg>1) {

if (($hdate>1) and ($hdate<$klvchasov)) {
if (!isset($m1)) {$holidays .= "<IMG src='/engine/plugins/holidays/images/info.gif'> В ближайщее время ожидаются праздники:<DIV style='BORDER-BOTTOM: #515151 1px dashed'></DIV>"; $m1=1;}
$klvmsg--; $holidays .="<IMG src='/engine/plugins/holidays/images/data.gif'> <font color='#cc0017'><B>$dayx</B></font> <small>через <B>$ddate</B> $dney</small><br><IMG src='/engine/plugins/holidays/images/down.gif'> $dt[2]<DIV style='BORDER-BOTTOM: #515151 1px dashed'></DIV>";} }

$i++;
} while($i<$itogo);

$holidays .= "";


$output = $holidays;

$template['vars']['plugin_holidays'] = $output;
}
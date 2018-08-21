<?php
$city_id = 27612; //id города, вписать свой, можно узнать тут https://pogoda.yandex.ru/static/cities.xml - параметр city id=
$cache_lifetime = 7200; //время кэша файла в секундах, 3600=1 час
$cache_file = 'weather_'.$city_id.'.xml'; // временный файл-кэш 

function loadxmlyansex($city_id)
 {
$url = 'http://export.yandex.ru/weather-ng/forecasts/'.$city_id.'.xml';
$userAgent = 'Googlebot/2.1 (+http://www.google.com/bot.html)';
$xml = 'weather_'.$city_id.'.xml';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
$output = curl_exec($ch);
$fh = fopen($xml, 'w');
fwrite($fh, $output);
fclose($fh);
 }

if ( file_exists($cache_file) ) {
 $cache_modified = time() - @filemtime($cache_file);
 if ( $cache_modified > $cache_lifetime ) 

{ 
//обновляем файл погоды, если время файла кэша устарело
loadxmlyansex($city_id);
}
}
else {
//если нет файла погоды вообще, закачиваем его
loadxmlyansex($city_id);
}
?>
<?php
if(file_exists($cache_file)):
$data = simplexml_load_file($cache_file); ?>
<style type="text/css">
.weather .date{font-size:13px;font-weight:700;padding-bottom:5px;text-transform:uppercase;border-bottom:1px solid #d5d5d5;margin-top:10px;}
.weather .item{background-color:#DAD9D5;padding:15px;font-family:Georgia;margin-bottom:20px;}
.weather .item table{border:0;width:100%;}
.weather .item table td{padding-bottom:15px;width:20%;vertical-align:baseline;padding-right:5px;}
.weather .item .day-part td{font-size:18px;}
.weather .item .day-temp td{font-size:30px;}
.weather .item .day-temp td img{margin-left:5px;}
.weather .item .day-param td{font-size:12px;}
.weather .item .day-param td p{padding-bottom:3px;}
.weather .days{margin-top:35px;border:0;width:100%;}
.weather .days td{width:50%;padding-bottom:35px;}
.weather .days a{font-family:Georgia;font-size:18px;text-decoration:underline;font-weight:700;}

.tabs input[type=radio] {
position: absolute;
top: -9999px;
left: -9999px;
}
.tabs {
width: 1000px;
float: none;
list-style: none;
position: relative;
padding: 0;
margin: 75px auto;
font-family: "Georgia";
}
.tabs li{
float: left;
}
.tabs label {
display: block;
padding: 10px 8px;
border-radius: 2px 2px 0 0;
color: #000;
font-size: 18px;
font-weight: normal;
font-family: 'Lily Script One', helveti;
background: rgba(255,255,255,0.2);
cursor: pointer;
position: relative;
top: 3px;
-webkit-transition: all 0.2s ease-in-out;
-moz-transition: all 0.2s ease-in-out;
-o-transition: all 0.2s ease-in-out;
transition: all 0.2s ease-in-out;
}
.tabs label:hover {
background: rgba(255,255,255,0.5);
top: 0;
}
[id^=tab]:checked + label {
background: #B7B7B7;
color: white;
}
[id^=tab]:checked ~ [id^=tab-content] {
display: block;
}
.tab-content{
z-index: 2;
display: none;
text-align: left;
width: 100%;
font-size: 20px;
line-height: 140%;
padding-top: 10px;
background: #B7B7B7;
padding: 15px;
color: white;
position: absolute;
top: 53px;
left: 0;
box-sizing: border-box;
-webkit-animation-duration: 0.5s;
-o-animation-duration: 0.5s;
-moz-animation-duration: 0.5s;
animation-duration: 0.5s;
}</style><div class="weather"><ul class="tabs"><?php
foreach($data->day as $day):?>
<li><?php $s6=$s6+1;?><input type="radio" 
<?php if($s6==1) echo "checked";
else
echo "";?> name="tabs" id="tab<?php echo $s1=$s1+1;?>">
<label for="tab<?php echo $s2=$s2+1;?>"><div class="date"><?php echo getDayDate($day['date']);?></div></label>
<div id="tab-content<?php echo $s3=$s3+1;?>" class="tab-content animated fadeIn">
<div class="item"><table><tr class="day-part"><td>Утром</td><td>Днем</td><td>Вечером</td><td>Ночью</td>
</tr><tr class="day-temp"><?php for($i = 0;$i < 4;$i++): // т.к. нам не нужны данные day_short и night_short, мы останавливаем проход на 4
$img = $day->day_part[$i]->{'image-v3'};?><td><?php echo getTempSign($day->day_part[$i]->{'temperature-data'}->avg);?> °C <img src="pogoda/<?php echo $img;?>.png" width="48" height="48" /></td><?php endfor;?>
</tr><tr class="day-param"><?php for($i = 0;$i < 4;$i++): // т.к. нам не нужны данные day_short и night_short, мы останавливаем проход на 4?>
<td><p><strong><?php echo $day->day_part[$i]->weather_type;?></strong></p><p>ветер: <?php echo getWindDirection($day->day_part[$i]->wind_direction).' '.$day->day_part[$i]->wind_speed;?> м/с</p>
<p>влажность: <?php echo $day->day_part[$i]->humidity;?>%</p><p>давление: <?php echo $day->day_part[$i]->pressure;?> мм рт. ст.</p>
</td><?php endfor;?></tr></table></div></div></li><?php
endforeach;?>
</ul></div>
<?php
endif;
// получаем локализованную дату
function getDayDate($date)
{
$date = strtotime($date);
$months = array('','/01','/02','/03','/04','/05','/06','/07','/08','/09','/10','/11','/12');
$days = array('ВС','ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ');
return $days[date('w', $date)].', '.(int)date('d',$date).' '.$months[date('n', $date)];
}
// получаем знак температуры
function getTempSign($temp)
{
$temp = (int)$temp;
return $temp > 0 ? '+'.$temp : $temp;
}
// получаем направления ветра
function getWindDirection($wind)
{
$wind = (string)$wind;
$wind_direction = array('s'=>'&#8593; ю','n'=>'&#8595; с','w'=>'&#8594; з','e'=>'&#8592; в','sw'=>'&#8599; юз','se'=>'&#8598; юв','nw'=>'&#8600; сз','ne'=>'&#8601; св');
return $wind_direction[$wind];
}
?>
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
array_push($cfgX, array('name' => 'viewmode', 'title' => "Вариант отображения галереи.",'descr' =>"Если вы выбрали 'Свой вариант' - разместите код с настройками HighSlide в файле hsscript_0.tpl ", 'type' => 'select', 'values' => array ( '0'=>'Свой вариант','1' => 'Простой', '2' => 'Слайд-шоу по клику','3'=>'Слайдшоу с превьюшками','4'=>'Встроенное слайдшоу'), 'value' => intval(extra_get_param($plugin,'viewmode'))));
array_push($cfgX, array('name' => 'enhsglobal', 'title' => "Включить скрипт highslide везде", 'descr' =>"Можно включить скрипт highslide на всём сайте, если вы хотите использовать превьюшки для фото на всём сайте.<br> Так же необходимо выполнить пункты 4 и 5 из этой инструкции <a href='http://ngcms.ru/forum/viewtopic.php?id=283&p=1'>http://ngcms.ru/forum/viewtopic.php?id=283&p=1</a> <br> ВНИМАНИЕ!!! Если вы уже используете превьюшки highslide по этой инструкции <a href='http://ngcms.ru/forum/viewtopic.php?id=283&p=1'>http://ngcms.ru/forum/viewtopic.php?id=283&p=1</a> <br> Удалите из main.tpl код из пункта 3 и включите эту опцию ", 'type' => 'checkbox', 'value' => extra_get_param($plugin,'enhsglobal')));
array_push($cfgX, array('name' => 'globalmode', 'title' => "Вариант отображения highslide - превьюшек на других страницах сайта.",'descr' =>"Если включена настройка выше", 'type' => 'select', 'values' => array ( '0'=>'Свой вариант','1' => 'Простой', '2' => 'Слайд-шоу по клику','3'=>'Слайдшоу с превьюшками'), 'value' => intval(extra_get_param($plugin,'globalmode'))));
array_push($cfgX, array('name' => 'folders', 'title' => "Altname страниц для галереи", 'descr' => "Укажите altname статических страниц, через запятую,  в которых будут генерироваться галереи<br>ВНИМАНИЕ!!! Так же для каждой страницы должна быть создана категория изображений с таким же названием", 'type' => 'input', 'value' => (extra_get_param($plugin,'folders'))?extra_get_param($plugin,'folders'):''));
array_push($cfgX, array('name' => 'hscolor', 'title' => "Цвет оформления", 'descr' =>"Поставьте птичку для смены оформления highslide -превью на чёрный цвет (По умолчанию - белый)<br>Вы можете самостятельно задать стиль отредактировав файл highslide.css", 'type' => 'checkbox', 'value' => extra_get_param($plugin,'hscolor')));
array_push($cfgX, array('name' => 'localsource', 'title' => "Выберите каталог из которого плагин будет брать шаблоны для отображения<br /><small><b>Шаблон сайта</b> - плагин будет пытаться взять шаблоны из общего шаблона сайта; в случае недоступности - шаблоны будут взяты из собственного каталога плагина<br /><b>Плагин</b> - шаблоны будут браться из собственного каталога плагина</small>", 'type' => 'select', 'values' => array ( '0' => 'Шаблон сайта', '1' => 'Плагин'), 'value' => intval(extra_get_param($plugin,'localsource'))));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки отображения</b>', 'entries' => $cfgX));
$cfgX = array();
array_push($cfgX, array('name' => 'cache', 'title' => "Использовать кеширование данных<br /><small><b>Да</b> - кеширование используется<br /><b>Нет</b> - кеширование не используется</small><br>ВНИМАНИЕ!!! Если галерея выводится на нескольких страницах, отображение глючит на обоих страницах может отображаться одно и то же, ", 'type' => 'select', 'values' => array ( '1' => 'Да', '0' => 'Нет'), 'value' => intval(extra_get_param($plugin,'cache'))));
array_push($cfgX, array('name' => 'cacheExpire', 'title' => "Период обновления кеша<br /><small>(через сколько секунд происходит обновление кеша. Значение по умолчанию: <b>60</b>)</small>", 'html_flags' => 'size=5', 'type' => 'input', 'value' => intval(extra_get_param($plugin,'cacheExpire'))?extra_get_param($plugin,'cacheExpire'):'60'));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки кеширования</b>', 'entries' => $cfgX));


// RUN
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page($plugin, $cfg);
}


?>

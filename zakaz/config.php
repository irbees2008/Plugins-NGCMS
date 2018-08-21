<?php

// Protect against hack attempts
if (!defined('NGCMS')) die ('HAL');
// Preload config file
plugins_load_config();

// Fill configuration parameters
$cfg = array();
array_push($cfg, array('name' => 'maillist', 'title' => "Введите через запятую список e-mail на которые будут отправлены данные формы", 'type' => 'input', 'value' => extra_get_param($plugin,'maillist')));
array_push($cfg, array('name' => 'allowexts', 'title' => "Введите через запятую список расширений файлов допустимых к загузке", 'type' => 'input', 'value' => extra_get_param($plugin,'allowexts')));
array_push($cfg, array('name' => 'maxf', 'title' => "Максимальный объём прикрепляемого файла (в Кб)", 'type' => 'input', 'value' => extra_get_param($plugin,'maxf')));
array_push($cfg, array('name' => 'localsource', 'title' => "Выберите каталог из которого плагин будет брать шаблоны для отображения<br /><small><b>Шаблон сайта</b> - плагин будет пытаться взять шаблоны из общего шаблона сайта; в случае недоступности - шаблоны будут взяты из собственного каталога плагина<br /><b>Плагин</b> - шаблоны будут браться из собственного каталога плагина</small>", 'type' => 'select', 'values' => array ( '0' => 'Шаблон сайта', '1' => 'Плагин'), 'value' => intval(extra_get_param($plugin,'localsource'))));
// RUN 
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page($plugin, $cfg);
}


?>

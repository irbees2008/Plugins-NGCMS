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
array_push($cfgX, array('name' => 'usmilies', 'title' => "Разрешить использовать смайлы при написании сообщений?", 'type' => 'select', 'values' => array ( '1' => 'Да', '0' => 'Нет'), 'value' => intval(extra_get_param($plugin,'usmilies'))));
array_push($cfgX, array('name' => 'ubbcodes', 'title' => "Разрешить использовать BB-коды при написании сообщений?", 'type' => 'select', 'values' => array ( '1' => 'Да', '0' => 'Нет'), 'value' => intval(extra_get_param($plugin,'ubbcodes'))));
array_push($cfgX, array('name' => 'minlength', 'title' => "Минимальная длина сообщения", 'descr' => "", 'html_flags' => 'size=5', 'type' => 'input', 'value' => intval(extra_get_param($plugin,'minlength'))?extra_get_param($plugin,'minlength'):'3'));
array_push($cfgX, array('name' => 'maxlength', 'title' => "Максимальная длина сообщения", 'descr' => "при превышении пределов будет выдана ошибка", 'html_flags' => 'size=5', 'type' => 'input', 'value' => intval(extra_get_param($plugin,'maxlength'))?extra_get_param($plugin,'maxlength'):'500'));
array_push($cfgX, array('name' => 'guests', 'title' => "Разрешить оставлять отзывы гостям?", 'type' => 'select', 'values' => array ( '1' => 'Да', '0' => 'Нет'), 'value' => intval(extra_get_param($plugin,'guests'))));
array_push($cfgX, array('name' => 'ecaptcha', 'title' => "Отображать CAPTCHA для гостей?", 'type' => 'select', 'values' => array ( '1' => 'Да', '0' => 'Нет'), 'value' => intval(extra_get_param($plugin,'ecaptcha'))));
array_push($cfgX, array('name' => 'perpage', 'title' => "Количество записей на странице", 'descr' => "", 'html_flags' => 'size=5', 'type' => 'input', 'value' => intval(extra_get_param($plugin,'perpage'))?extra_get_param($plugin,'perpage'):'15'));
array_push($cfgX, array('name' => 'order', 'title' => "Сначала показывать сообщения, оставленные", 'type' => 'select', 'values' => array ( 'DESC' => 'Последними', 'ASC' => 'Первыми'), 'value' => extra_get_param($plugin,'order')));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>Настройки гостевой книги</b>', 'entries' => $cfgX));


// RUN 
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete($plugin);
} else {
	generate_config_page($plugin, $cfg);
}


?>
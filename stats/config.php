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
array_push($cfg, array('descr' => 'Плагин вывода количественной статистики'));
array_push($cfgX, array('name' => 'iplock', 'title' => 'В случае отсутствия материалов выводить цифры или слова?', 'descr' => 'Отметьте что писать в случае если по определенной позиции материалов не обнаружится','type' => 'select',  'values' => array ( '0' => 'цифры', '1' => 'слова'), value => pluginGetVariable('stats','iplock')));
array_push($cfgX, array('name' => 'en_dbprefix1', 'title' => 'Подсчитывать количество статических страниц?', 'descr' => 'Если Вы поставите галочку, то плагин выведет колчиество статических страниц','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix1')));
array_push($cfgX, array('name' => 'en_dbprefix2', 'title' => 'Подсчитывать количество категорий?', 'descr' => 'Если Вы поставите галочку, то плагин выведет колчиество категорий','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix2')));
array_push($cfgX, array('name' => 'en_dbprefix3', 'title' => 'Подсчитывать количество новостей?', 'descr' => 'Если Вы поставите галочку, то плагин выведет колчиество новостей','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix3')));
array_push($cfgX, array('name' => 'en_dbprefix4', 'title' => 'Подсчитывать количество неопубликованных новостей?', 'descr' => 'Если Вы поставите галочку, то плагин выведет колчиество неопубликованных новостей','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix4')));
array_push($cfgX, array('name' => 'en_dbprefix5', 'title' => 'Подсчитывать количество комментариев?', 'descr' => 'Если Вы поставите галочку, то плагин выведет колчиество комментариев','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix5')));
array_push($cfgX, array('name' => 'en_dbprefix6', 'title' => 'Подсчитывать количество зарегестрированных пользователей?', 'descr' => 'Если Вы поставите галочку, то плагин выведет колчиество зарегестрированных пользователей','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix6')));
array_push($cfgX, array('name' => 'en_dbprefix7', 'title' => 'Подсчитывать количество неактивных пользователей?', 'descr' => 'Если Вы поставите галочку, то плагин выведет колчиество неактивных пользователей','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix7')));
array_push($cfgX, array('name' => 'en_dbprefix8', 'title' => 'Подсчитывать количество загруженных изображений?', 'descr' => 'Если Вы поставите галочку, то плагин выведет колчиество загруженных изображений','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix8')));
array_push($cfgX, array('name' => 'en_dbprefix9', 'title' => 'Подсчитывать количество загруженных файлов?', 'descr' => 'Если Вы поставите галочку, то плагин выведет колчиество загруженных файлов','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix9')));
array_push($cfgX, array('name' => 'en_dbprefix10', 'title' => 'Подсчитывать количество банов по айпи?', 'descr' => 'Если Вы поставите галочку, то плагин выведет колчиество забаненных по айпи','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix10')));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>Основные настройки</b>', 'entries' => $cfgX));

$cfgX = array();
array_push($cfgX, array('name' => 'en_dbprefix', 'title' => 'Использовать другой префикс для таблицы', 'descr' => 'вы можете использовать таблицу другого экземпляра NG CMS если он установлен в той же БД','type' => 'checkbox', value => pluginGetVariable('stats','en_dbprefix')));
array_push($cfgX, array('name' => 'dbprefix', 'title' => 'Другой префикс для таблицы пользователей', 'descr' => 'введите тут другой префикс таблицы пользователей NG CMS, который вы хотите использовать для авторизации. таблица должна быть в той же БД где и текущая инсталляция NG CMS.','type' => 'input', value => pluginGetVariable('stats','dbprefix')));
array_push($cfg,  array('mode' => 'group', 'title' => '<b>Отдельный префикс для таблиц</b>', 'entries' => $cfgX));

// RUN
if ($_REQUEST['action'] == 'commit') {
	// If submit requested, do config save
	commit_plugin_config_changes('stats', $cfg);
	print_commit_complete('stats');
} else {
	generate_config_page('stats', $cfg);
}


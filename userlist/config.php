<?php
plugins_load_config();

$cfg = array();
array_push($cfg, array('descr' => 'Плагин выводит список пользователей, зарегистрированных на сайте.'));
array_push($cfg, array('name' => 'perpage',   'title' => 'Кол-во пользователей для отображения на одной странице', 'type' => 'input', 'value' => extra_get_param($plugin,'perpage')));
array_push($cfg, array('name' => 'sort',   'title' => 'Сортировать по:', 'descr' => 'Выберите по какому параметру сортировать пользователей.', 'type' => 'select', 'values' => array ( 'nickname' => 'Имя', 'status' => 'Статус', 'num_news' => 'Кол-во новостей', 'num_com' => 'Кол-во комментариев', 'regdate' => 'Зарегистрирован'), 'value' => extra_get_param($plugin,'sort')));
array_push($cfg, array('name' => 'order',   'title' => 'Упорядочить по:', 'descr' => 'Выберите порядок отображения пользователей.', 'type' => 'select', 'values' => array ('asc' => 'Возрастанию', 'desc' => 'Убыванию'), 'value' => extra_get_param($plugin,'order')));
array_push($cfg, array('name' => 'fdate',   'title' => 'Формат вывода даты', 'descr' => 'Возможные значения смотреть <a href="http://php.net/date">здесь</a>.', 'type' => 'input', 'value' => extra_get_param($plugin,'fdate')));

if ($_REQUEST['action'] == 'commit') {
	commit_plugin_config_changes($plugin, $cfg);
	print_commit_complete('userlist');
} else {
	generate_config_page('userlist', $cfg);
}